<?php

namespace App\Services\Ventas;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Adicionale;
use App\Helpers\GlobalHelper;
use App\Events\CocinaPedidoEvent;
use Illuminate\Support\Facades\DB;
use App\Services\Ventas\DTOs\VentaResponse;
use App\Services\Ventas\Exceptions\VentaException;
use App\Services\Ventas\Contracts\StockServiceInterface;
use App\Services\Ventas\Contracts\ProductoVentaServiceInterface;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class ProductoVentaService implements ProductoVentaServiceInterface
{
    public function __construct(
        private StockServiceInterface $stockService,
        private CalculadoraVentaServiceInterface $calculadoraService
    ) {}

    public function agregarProducto(Venta $venta, Producto $producto, int $cantidad = 1): VentaResponse
    {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }

            // Verificar stock si el producto es contable
            if ($producto->contable) {
                if (!$this->stockService->verificarStock($producto, $cantidad, $venta->sucursale_id)) {
                    throw VentaException::stockInsuficiente($producto->nombre);
                }

                $stockResponse = $this->stockService->actualizarStock(
                    $producto, 
                    $cantidad === 1 ? 'sumar' : 'sumarvarios', 
                    $cantidad, 
                    $venta->sucursale_id
                );

                if (!$stockResponse->success) {
                    return $stockResponse;
                }
            }

            // Agregar producto a la venta
            $registro = DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->first();

            if (!$registro) {
                $venta->productos()->attach($producto->id);
            } else {
                DB::table('producto_venta')
                    ->where('venta_id', $venta->id)
                    ->where('producto_id', $producto->id)
                    ->increment('cantidad', $cantidad);
            }

            // Actualizar adicionales si es necesario
            if ($producto->medicion === 'unidad') {
                $this->actualizarAdicionales($venta, $producto, $cantidad === 1 ? 'sumar' : 'muchos', $cantidad);
            }

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            return VentaResponse::success(
                null, 
                "Se agregó {$cantidad} {$producto->nombre} a esta venta"
            );

        } catch (VentaException $e) {
            return VentaResponse::error($e->getMessage(), [], null);
        } catch (\Exception $e) {
            return VentaResponse::error('Error al agregar producto: ' . $e->getMessage());
        }
    }

    public function eliminarUnoProducto(Venta $venta, Producto $producto): VentaResponse
    {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }

            $registro = DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->first();

            if (!$registro) {
                return VentaResponse::error('El producto no existe en esta venta');
            }

            // Restaurar stock si es contable
            if ($producto->contable) {
                $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
            }

            // Actualizar o eliminar registro
            if ($registro->cantidad == 1) {
                $venta->productos()->detach($producto->id);
            } else {
                DB::table('producto_venta')
                    ->where('venta_id', $venta->id)
                    ->where('producto_id', $producto->id)
                    ->decrement('cantidad', 1);
            }

            // Actualizar adicionales
            $this->actualizarAdicionales($venta, $producto, 'restar');

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            return VentaResponse::success(
                null, 
                "Se eliminó 1 {$producto->nombre} de esta venta"
            );

        } catch (VentaException $e) {
            return VentaResponse::error($e->getMessage(), [], null);
        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar producto: ' . $e->getMessage());
        }
    }

    public function eliminarProductoCompleto(Venta $venta, Producto $producto): VentaResponse
    {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }

            $registro = DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->first();

            if (!$registro) {
                return VentaResponse::warning("El producto {$producto->nombre} no existe en esta venta");
            }

            // Verificar si tiene adicionales personalizados
            $arrayAdicionales = json_decode($registro->adicionales);
            $todoVacio = $this->verificarAdicionalesVacios($arrayAdicionales);

            if (!$todoVacio) {
                throw VentaException::productoConAdicionales($producto->nombre);
            }

            // Restaurar stock si es contable
            if ($producto->contable) {
                $this->stockService->actualizarStock($producto, 'restar', $registro->cantidad, $venta->sucursale_id);
            }

            // Eliminar producto
            $venta->productos()->detach($producto->id);

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Disparar evento si no es de ECO-TIENDA
            if ($producto->subcategoria->categoria->nombre !== 'ECO-TIENDA') {
                event(new CocinaPedidoEvent("Se actualizó la mesa {$venta->id}"));
            }

            return VentaResponse::success(
                null, 
                "Se eliminó {$producto->nombre} de esta venta"
            );

        } catch (VentaException $e) {
            return VentaResponse::warning($e->getMessage());
        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar producto: ' . $e->getMessage());
        }
    }

    public function agregarAdicional(Venta $venta, Producto $producto, Adicionale $adicional, int $item): VentaResponse
    {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }

            if ($producto->medicion !== 'unidad') {
                return VentaResponse::warning('Solo se pueden agregar adicionales a productos por unidad');
            }

            // Verificar stock del adicional
            if ($adicional->contable && $adicional->cantidad == 0) {
                return VentaResponse::warning("No existe stock para {$adicional->nombre}");
            }

            // Obtener adicionales actuales
            $pivot = DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->first();

            $array = json_decode($pivot->adicionales, true);
            
            // Agregar adicional al item específico
            for ($i = 1; $i <= $item; $i++) {
                if ($i == $item) {
                    $array[$i][] = [$adicional->nombre => $adicional->precio];
                }
            }

            // Actualizar en base de datos
            DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->update(['adicionales' => json_encode($array)]);

            // Actualizar stock del adicional
            if ($adicional->contable) {
                GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'reducir');
                $adicional->decrement('cantidad');
                $adicional->save();
            }

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            return VentaResponse::success(null, 'Adicional agregado correctamente');

        } catch (VentaException $e) {
            return VentaResponse::warning($e->getMessage());
        } catch (\Exception $e) {
            return VentaResponse::error('Error al agregar adicional: ' . $e->getMessage());
        }
    }

    public function eliminarItem(Venta $venta, Producto $producto, int $posicion): VentaResponse
    {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }

            $pivot = DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->first();

            $array = json_decode($pivot->adicionales, true);

            if (count($array) == 1) {
                return VentaResponse::warning('No puede eliminar el único item disponible');
            }

            // Restaurar stock de adicionales
            foreach ($array[$posicion] as $adic) {
                $adicional = Adicionale::where('nombre', key($adic))->first();
                if ($adicional && $adicional->contable) {
                    $adicional->increment('cantidad');
                    $adicional->save();
                    GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
                }
            }

            // Eliminar item y reorganizar
            unset($array[$posicion]);
            $array = array_values($array);
            $nuevoArray = array_combine(range(1, count($array)), array_values($array));

            // Actualizar en base de datos
            DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->update(['adicionales' => json_encode($nuevoArray)]);

            DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->decrement('cantidad');

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            return VentaResponse::success(null, 'Item eliminado correctamente');

        } catch (VentaException $e) {
            return VentaResponse::warning($e->getMessage());
        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar item: ' . $e->getMessage());
        }
    }

    public function actualizarAdicionales(Venta $venta, Producto $producto, string $operacion, ?int $cantidadEspecifica = null): VentaResponse
    {
        try {
            $registro = DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->first();

            if (!$registro) {
                return VentaResponse::error('Producto no encontrado en la venta');
            }

            $listaadicionales = $registro->adicionales;

            if ($listaadicionales == null) {
                $string = '{"1":[]}';
                DB::table('producto_venta')
                    ->where('producto_id', $producto->id)
                    ->where('venta_id', $venta->id)
                    ->update(['adicionales' => $string]);
            } else {
                $json = json_decode($listaadicionales, true);
                $cantidad = count($json);

                switch ($operacion) {
                    case 'sumar':
                        $json[] = [];
                        break;
                        
                    case 'muchos':
                        for ($i = 0; $i < $cantidadEspecifica; $i++) {
                            $json[] = [];
                        }
                        break;
                        
                    case 'restar':
                        if ($registro->cantidad > 0) {
                            // Restaurar stock de adicionales
                            foreach ($json[$cantidad] as $adic) {
                                $adicional = Adicionale::where('nombre', key($adic))->first();
                                if ($adicional && $adicional->contable) {
                                    $adicional->increment('cantidad');
                                    $adicional->save();
                                    GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
                                }
                            }
                            unset($json[$cantidad]);
                        }
                        break;
                }

                DB::table('producto_venta')
                    ->where('producto_id', $producto->id)
                    ->where('venta_id', $venta->id)
                    ->update(['adicionales' => json_encode($json)]);
            }

            return VentaResponse::success(null, 'Adicionales actualizados');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al actualizar adicionales: ' . $e->getMessage());
        }
    }

    public function guardarObservacion(Venta $venta, Producto $producto, string $observacion): VentaResponse
    {
        try {
            DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->update(['observacion' => $observacion]);

            return VentaResponse::success(null, 'Observación guardada');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al guardar observación: ' . $e->getMessage());
        }
    }

    public function agregarDesdeplan(Venta $venta, int $userId, int $planId, int $productoId): VentaResponse
    {
        try {
            // Decrementar plan del usuario
            DB::table('plane_user')
                ->where('user_id', $userId)
                ->where('plane_id', $planId)
                ->decrement('restante', 1);

            $producto = Producto::find($productoId);
            
            // Agregar descuento al plan
            $descuentoAdicional = $producto->descuento ?: $producto->precio;
            $venta->descuento = $venta->descuento + $descuentoAdicional;
            $venta->save();

            // Agregar producto
            $this->agregarProducto($venta, $producto);

            return VentaResponse::success(null, 'Se restó una unidad al plan y se agregó el producto');

        } catch (\Exception $e) {
            return VentaResponse::error('Error al agregar desde plan: ' . $e->getMessage());
        }
    }

    private function verificarAdicionalesVacios($arrayAdicionales): bool
    {
        if (is_null($arrayAdicionales)) {
            return true;
        }

        $array = (array) $arrayAdicionales;
        return empty(array_filter($array, function ($item) {
            return !empty($item);
        }));
    }
}
