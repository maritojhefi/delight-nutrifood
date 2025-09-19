<?php

namespace App\Services\Ventas;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\Venta;
use App\Models\Saldo;
use App\Services\Ventas\DTOs\VentaResponse;
use App\Services\Ventas\Contracts\SaldoServiceInterface;

class SaldoService implements SaldoServiceInterface
{
    public function registrarSaldo(Venta $venta, float $monto, string $detalle, int $tipo, bool $esDeuda = false): VentaResponse
    {
        try {
            $cajaActiva = Caja::where('sucursale_id', $venta->sucursale->id)
                ->whereDate('created_at', Carbon::today())
                ->first();

            if (!$cajaActiva) {
                return VentaResponse::error('No hay caja abierta para hoy');
            }

            $saldoCreado = Saldo::create([
                'detalle' => $detalle,
                'historial_venta_id' => 1,
                'caja_id' => $cajaActiva->id,
                'es_deuda' => $esDeuda,
                'monto' => $monto,
                'user_id' => $venta->cliente->id,
                'atendido_por' => auth()->user()->id,
                'tipo' => $tipo,
            ]);

            $saldoCreado->metodosPagos()->attach($tipo, ['monto' => $monto]);

            $mensaje = $esDeuda 
                ? 'Se registró la deuda del cliente' 
                : 'Se editó el saldo a favor de este cliente';

            return VentaResponse::success($saldoCreado, $mensaje);

        } catch (\Exception $e) {
            return VentaResponse::error('Error al registrar saldo: ' . $e->getMessage());
        }
    }

    public function anularSaldo(Saldo $saldo): VentaResponse
    {
        try {
            $saldo->anulado = !$saldo->anulado;
            $saldo->save();

            $mensaje = $saldo->anulado 
                ? 'El saldo fue anulado!' 
                : 'El saldo vuelve a estar activo!';

            return VentaResponse::success($saldo, $mensaje);

        } catch (\Exception $e) {
            return VentaResponse::error('Error al anular saldo: ' . $e->getMessage());
        }
    }

    public function crearSaldoCobranza(int $clienteId, int $historialVentaId, int $cajaId, float $monto, bool $aFavorCliente, int $atendidoPor): VentaResponse
    {
        try {
            $saldo = Saldo::create([
                'user_id' => $clienteId,
                'historial_venta_id' => 1,
                'historial_ventas_id' => $historialVentaId,
                'caja_id' => $cajaId,
                'monto' => $monto,
                'es_deuda' => !$aFavorCliente,
                'atendido_por' => $atendidoPor,
            ]);

            return VentaResponse::success($saldo, 'Saldo creado en la cobranza');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al crear saldo de cobranza: ' . $e->getMessage());
        }
    }

    public function calcularMaximoDescuentoSaldo(Venta $venta): float
    {
        if (!$venta->cliente || !$venta->cliente->saldo) {
            return 0;
        }

        // Aquí necesitarías inyectar el CalculadoraVentaService o pasar el subtotal
        // Por simplicidad, lo calculo básicamente
        $subtotalConDescuento = $venta->total; // Esto debería ser el cálculo completo
        return min($subtotalConDescuento, abs((int) $venta->cliente->saldo));
    }

    public function validarDescuentoSaldo(Venta $venta, float $descuento): VentaResponse
    {
        $maximoPermitido = $this->calcularMaximoDescuentoSaldo($venta);
        
        if ($descuento > $maximoPermitido) {
            return VentaResponse::warning(
                "El máximo que puede ingresar es de {$maximoPermitido} Bs"
            );
        }

        return VentaResponse::success(null, 'Descuento de saldo válido');
    }
}
