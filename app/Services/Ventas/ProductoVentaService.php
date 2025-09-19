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
use Illuminate\Database\Eloquent\Collection;

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
            return VentaResponse::error('Error al agregar producto: ' . $e ->getMessage());
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
                // event(new CocinaPedidoEvent("Se actualizó la mesa {$venta->id}"));
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

    public function agregarProductoCliente(Venta $venta, Producto $producto, Collection $adicionales, int $cantidad): VentaResponse
    {
        try {
            DB::beginTransaction();

            $existeProductoVenta = $venta->productos()->where('producto_id', $producto->id)->exists();
            
            if (!$existeProductoVenta) {
                $venta->productos()->attach($producto->id, ['cantidad' => $cantidad]);
            } else {
                $venta->productos()->updateExistingPivot($producto->id, [
                    'cantidad' => DB::raw("cantidad + {$cantidad}")
                ]);
            }

            // Actualizar adicionales si es necesario
            if ($producto->medicion == 'unidad') {
                $this->procesarAdicionalesBatch($venta, $producto->id, $adicionales, $cantidad);
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

            DB::commit();
            return VentaResponse::success(null,"ProductoVenta agregado/actualizado exitosamente");
        } catch (VentaException $e) {
            DB::rollBack();
            return VentaResponse::error($e->getMessage(), [], null);
        } catch (\Exception  $e) {
            // Rollback en caso de error
            DB::rollBack();
            return VentaResponse::error('Error al agregar producto: ' . $e ->getMessage());
            // Re-throw the exception or handle it appropriately
            // throw new HttpResponseException(response()->json([
            //     'message' => 'Error al procesar la orden: ' . $e->getMessage()
            // ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    private function procesarAdicionalesBatch(Venta $venta, int $productoId, Collection $extras, int $cantidad)
    {
        try {
            // Obtener el registro de producto_venta necesario
            $productoVenta = $venta->productos()->where('producto_id', $productoId)->first();
            
            if (!$productoVenta) {
                return VentaResponse::error('Producto no encontrado en la venta');
            }

            // Obtener el listado de adicionales actual
            $listaActual = $productoVenta->pivot->adicionales;
            $json = $listaActual ? json_decode($listaActual, true) : [];

            // En caso de no disponer de adicionales, insertat arrays vacios
            if ($extras->isEmpty()) {
                for ($i = 0; $i < $cantidad; $i++) {
                    $siguiente_clave = count($json) + 1;
                    $json[$siguiente_clave] = []; // Empty array for each unit
                }
            } else {
                // Validar el stock necesario
                foreach ($extras as $adicional) {
                    if ($adicional->contable == true && $adicional->cantidad < $cantidad) {
                        return VentaResponse::warning("No hay stock suficiente para el adicional: {$adicional->nombre}. Stock disponible: {$adicional->cantidad}, requerido: {$cantidad}");
                        // throw new \Exception("No hay stock suficiente para el adicional: {$adicional->nombre}. Stock disponible: {$adicional->cantidad}, requerido: {$cantidad}");
                    }
                }

                // Procesar las veces determinadas por la cantidad
                for ($i = 0; $i < $cantidad; $i++) {
                    $siguiente_clave = count($json) + 1;
                    $array_extras = [];

                    foreach ($extras as $adicional) {
                        if ($adicional->contable == true && $i == 0) {
                            $adicional->decrement('cantidad', $cantidad);
                        }
                        $array_extras[] = [$adicional->nombre => $adicional->precio];
                    }

                    $json[$siguiente_clave] = $array_extras;
                }
            }
            
            // Actualizar producto_venta
            DB::table('producto_venta')
            ->where('venta_id', $venta->id)
            ->where('producto_id', $productoId)
            ->update(['adicionales' => json_encode($json)]);

            return VentaResponse::success(null, 'Adicionales actualizados');
        } catch (\Exception $e) {
            return VentaResponse::error('Error al actualizar adicionales: ' . $e->getMessage());
        }
    }

    // private function actualizarAdicionalesCliente(Venta $venta, $productoVenta, $operacion, $extras) {
    //     // Obtenemos el primer registro correspondiente al idproducto en producto_venta
    //     // $productoVenta = $venta->productos()
    //     // ->where('producto_id', $idproducto)
    //     // ->first();

    //     if ($productoVenta) {
    //         // El listado actual son las ordenes registradas en el producto_venta antes
    //         // de su actualizacion
    //         $listaActual = $productoVenta->pivot->adicionales;

    //         // Decodificat el JSON si existe, de lo contrario, inicializa un arreglo vacío
    //         $json = $listaActual ? json_decode($listaActual, true) : [];

    //         // Determina la siguiente clave numérica
    //         $siguiente_clave = count($json) + 1;

    //         if ($operacion == 'sumar') {
    //             // Asigna explícitamente la nueva clave numérica.
    //             // Esto mantiene la estructura de objeto JSON.
    //             if ($extras->isEmpty()) {
    //                 // Si extras esta vacio, se agrega una nueva orden sin detalles
    //                 $json[$siguiente_clave] = [];
    //             } else {
    //                 $array_extras = [];
                    
    //                 // Validar stock para todos los adicionales
    //                 foreach ($extras as $adicional) {
    //                     if ($adicional->contable == true && $adicional->cantidad == 0) {
    //                         throw new \Exception("No hay stock disponible para el adicional: {$adicional->nombre}");
    //                     }
    //                 }
    //                 // Por cada extra, crear un detalle y asignarlo a una nueva orden (clave en producto_venta.adicionales)
    //                 foreach ($extras as $adicional) {
    //                     if ($adicional->contable == true && $adicional->cantidad >= 1) {
    //                         $adicional->decrement('cantidad');
    //                     }
    //                     $array_extras[] = [$adicional->nombre => $adicional->precio];
    //                 }
    //                 $json[$siguiente_clave] = $array_extras;
    //             }
    //             // Codifica el arreglo de vuelta a JSON antes de guardar
    //             $venta->productos()
    //             ->updateExistingPivot($idproducto, [
    //                 'adicionales' => json_encode($json)
    //             ]);
    //         }
    //     }
    // }


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
