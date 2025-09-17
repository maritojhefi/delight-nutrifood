<?php

namespace App\Services\Ventas;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\User;
use App\Models\Venta;
use App\Models\MetodoPago;
use App\Models\Historial_venta;
use App\Events\CocinaPedidoEvent;
use Illuminate\Support\Facades\DB;
use App\Services\Ventas\DTOs\VentaResponse;
use App\Services\Ventas\Exceptions\VentaException;
use App\Services\Ventas\Contracts\SaldoServiceInterface;
use App\Services\Ventas\Contracts\VentaServiceInterface;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class VentaService implements VentaServiceInterface
{
    public function __construct(
        private CalculadoraVentaServiceInterface $calculadoraService,
        private SaldoServiceInterface $saldoService
    ) {}

    public function crearVenta(int $usuarioId, int $sucursalId, ?int $clienteId = null): VentaResponse
    {
        try {
            $venta = Venta::create([
                'usuario_id' => $usuarioId,
                'sucursale_id' => $sucursalId,
                'cliente_id' => $clienteId,
            ]);

            return VentaResponse::success($venta, 'Nueva venta creada');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al crear venta: ' . $e->getMessage());
        }
    }

    public function cobrarVenta(Venta $venta, array $metodosSeleccionados, float $totalAcumulado, float $subtotalConDescuento, float $descuentoSaldo = 0): VentaResponse
    {
        try {
            // Validaciones previas
            $validacion = $this->validarVentaCobrable($venta, $metodosSeleccionados, $totalAcumulado, $subtotalConDescuento);
            if (!$validacion->success) {
                return $validacion;
            }

            $cajaActiva = Caja::where('sucursale_id', $venta->sucursale->id)
                ->whereDate('created_at', Carbon::today())
                ->first();

            if (!$cajaActiva) {
                throw VentaException::cajaSinAbrir();
            }

            if ($cajaActiva->estado !== 'abierto') {
                throw VentaException::cajaCerrada();
            }

            DB::beginTransaction();

            try {
                // Calcular datos de la venta
                $calculos = $this->calculadoraService->calcularVenta($venta, $descuentoSaldo);
                
                // Determinar saldo resultante
                $saldoResultante = $this->calculadoraService->calcularSaldoResultante($totalAcumulado, $subtotalConDescuento);
                
                // Actualizar caja
                DB::table('cajas')
                    ->where('id', $cajaActiva->id)
                    ->increment('acumulado', $calculos->subtotal - $venta->descuento - $venta->saldo - $calculos->descuentoProductos);

                // Crear historial de venta
                $historialVenta = Historial_venta::create([
                    'caja_id' => $cajaActiva->id,
                    'usuario_id' => auth()->user()->id,
                    'sucursale_id' => $venta->sucursale_id,
                    'cliente_id' => $venta->cliente_id,
                    'total' => $calculos->subtotal - $calculos->descuentoProductos,
                    'puntos' => $calculos->puntos,
                    'descuento' => $venta->descuento,
                    'tipo' => 'N/A',
                    'saldo' => 0,
                    // Campos v2
                    'subtotal' => $calculos->subtotal,
                    'total_pagado' => $totalAcumulado,
                    'total_a_pagar' => $subtotalConDescuento,
                    'descuento_productos' => $calculos->descuentoProductos,
                    'descuento_saldo' => $descuentoSaldo,
                    'descuento_manual' => $venta->descuento,
                    'total_descuento' => $calculos->descuentoProductos + $venta->descuento,
                    'saldo_monto' => $saldoResultante['montoSaldo'],
                    'a_favor_cliente' => $saldoResultante['saldoAFavorCliente'],
                ]);

                // Actualizar puntos del cliente
                if ($venta->cliente_id) {
                    DB::table('users')
                        ->where('id', $venta->cliente_id)
                        ->increment('puntos', $calculos->puntos);
                }

                // Crear saldo si es necesario
                if ($saldoResultante['montoSaldo'] > 0) {
                    $this->saldoService->crearSaldoCobranza(
                        $venta->cliente_id,
                        $historialVenta->id,
                        $cajaActiva->id,
                        $saldoResultante['montoSaldo'],
                        $saldoResultante['saldoAFavorCliente'],
                        auth()->user()->id
                    );
                }

                // Guardar métodos de pago
                foreach ($metodosSeleccionados as $codigo => $data) {
                    if ($data['activo'] == true && isset($data['valor']) && is_numeric($data['valor'])) {
                        $metodo = MetodoPago::where('codigo', $codigo)->first();
                        $historialVenta->metodosPagos()->attach($metodo->id, ['monto' => $data['valor']]);
                    }
                }

                // Copiar productos al historial
                $productos = $venta->productos;
                foreach ($productos as $producto) {
                    $prodLista = collect($calculos->listaCuenta)->firstWhere('id', $producto->id);
                    
                    $historialVenta->productos()->attach($producto->id, [
                        'cantidad' => $producto->pivot->cantidad,
                        'adicionales' => $producto->pivot->adicionales,
                        'precio_subtotal' => $prodLista['subtotal'] ?? 0,
                        'precio_unitario' => $prodLista['precio'] ?? $producto->precio,
                        'descuento_producto' => $prodLista['descuento_producto'] ?? 0,
                    ]);
                }

                // Marcar venta como pagada
                $venta->historial_venta_id = $historialVenta->id;
                $venta->pagado = true;
                $venta->save();

                DB::commit();

                return VentaResponse::success(
                    $venta->fresh(), 
                    'Esta venta ahora se encuentra pagada!'
                );

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (VentaException $e) {
            return VentaResponse::error($e->getMessage(), [], null);
        } catch (\Exception $e) {
            return VentaResponse::error('Error al cobrar venta: ' . $e->getMessage());
        }
    }

    public function cerrarVenta(Venta $venta): VentaResponse
    {
        try {
            if ($venta->cocina && !$venta->despachado_cocina) {
                throw VentaException::pedidoSinDespachar();
            }

            if (!$venta->pagado) {
                return VentaResponse::warning('Primero marque como pagado esta venta');
            }

            $venta->productos()->detach();
            $venta->delete();

            return VentaResponse::success(null, 'Se finalizó esta venta');

        } catch (VentaException $e) {
            return VentaResponse::warning($e->getMessage());
        } catch (\Exception $e) {
            return VentaResponse::error('Error al cerrar venta: ' . $e->getMessage());
        }
    }

    public function eliminarVenta(Venta $venta): VentaResponse
    {
        try {
            $venta->delete();
            return VentaResponse::warning('Venta eliminada', null);

        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar venta: ' . $e->getMessage());
        }
    }

    public function cambiarClienteVenta(Venta $venta, User $cliente): VentaResponse
    {
        try {
            $venta->cliente_id = $cliente->id;
            $venta->usuario_manual = null;
            $venta->save();

            return VentaResponse::success(
                $venta->fresh(),
                "Se asignó a esta venta el cliente: {$cliente->name}"
            );

        } catch (\Exception $e) {
            return VentaResponse::error('Error al cambiar cliente: ' . $e->getMessage());
        }
    }

    public function agregarUsuarioManual(Venta $venta, string $usuarioManual): VentaResponse
    {
        try {
            if (empty(trim($usuarioManual))) {
                return VentaResponse::warning('Formato Incorrecto');
            }

            $venta->usuario_manual = $usuarioManual;
            $venta->save();

            return VentaResponse::success($venta->fresh(), 'Hecho!');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al agregar usuario manual: ' . $e->getMessage());
        }
    }

    public function editarDescuento(Venta $venta, float $descuento): VentaResponse
    {
        try {
            $venta->descuento = $descuento;
            $venta->save();

            $this->calculadoraService->actualizarTotalesVenta($venta);

            return VentaResponse::success($venta->fresh(), 'Descuento actualizado!');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al editar descuento: ' . $e->getMessage());
        }
    }

    public function enviarACocina(Venta $venta): VentaResponse
    {
        try {
            $venta->cocina = true;
            $venta->cocina_at = Carbon::now();
            $venta->save();

            event(new CocinaPedidoEvent('Se actualizó la lista'));

            return VentaResponse::success($venta->fresh(), 'Se envió a cocina!');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al enviar a cocina: ' . $e->getMessage());
        }
    }

    public function validarVentaModificable(Venta $venta): VentaResponse
    {
        if ($venta->pagado) {
            return VentaResponse::warning('La venta ya ha sido pagada, no se puede modificar');
        }

        return VentaResponse::success(null, 'Venta modificable');
    }

    public function validarVentaCobrable(Venta $venta, array $metodosSeleccionados, float $totalAcumulado, float $subtotalConDescuento): VentaResponse
    {
        if ($venta->pagado) {
            return VentaResponse::warning('Esta venta ya se encuentra pagada!');
        }

        if (!$venta->cliente && $totalAcumulado != $subtotalConDescuento) {
            return VentaResponse::error('Los métodos de pago no equivalen al monto total de la venta');
        }

        return VentaResponse::success(null, 'Venta válida para cobrar');
    }
}
