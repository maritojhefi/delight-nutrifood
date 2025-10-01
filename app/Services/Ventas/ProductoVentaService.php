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
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

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
                $stockResponse = $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
                if (!$stockResponse->success) {
                    return VentaResponse::error('Error al restaurar stock: ' . $stockResponse->message);
                }
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

            $productoVenta = $venta->productos()
                ->where('producto_id', $producto->id)
                ->wherePivot('aceptado', false)
                ->withPivot('id', 'cantidad', 'adicionales')
                ->first();

            return VentaResponse::success(
                $productoVenta ? $productoVenta->pivot : null,
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
                $stockResponse = $this->stockService->actualizarStock($producto, 'restar', $registro->cantidad, $venta->sucursale_id);
                if (!$stockResponse->success) {
                    return VentaResponse::error('Error al restaurar stock: ' . $stockResponse->message);
                }
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

            // Restaurar stock si es contable
            if ($producto->contable) {
                $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
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

    public function guardarObservacionPivotID(int $producto_venta_id, ?string $observacion): VentaResponse
    {
        try {
            DB::table('producto_venta')
                ->where('id', $producto_venta_id)
                ->update(['observacion' => $observacion]);

            return VentaResponse::success(null, 'Observación guardada');
        } catch (\Exception $e) {
            return VentaResponse::error('Error al guardar observación: ' . $e->getMessage());
        }
    }

    public function agregarProductoCliente(Venta $venta, Producto $producto, Collection $adicionales, int $cantidad): VentaResponse
    {
        try {
            DB::beginTransaction();

            $existeProductoVenta = $venta->productos()
                    ->where('producto_id', $producto->id)
                    ->wherePivot('aceptado', false)->exists();
            
            if (!$existeProductoVenta) {
                // Log::debug("No existe registro en producto_venta, creando para ",[$producto->id]);
                $venta->productos()->attach($producto->id, ['cantidad' => $cantidad]);
            } else {
                // Log::debug("Existe registro en producto_venta, creando para ",[$producto->id]);
                $venta->productos()->wherePivot('aceptado', false)->updateExistingPivot($producto->id, [
                    'cantidad' => DB::raw("cantidad + {$cantidad}")
                ]);
            }

            // Actualizar adicionales si es necesario
            if ($producto->medicion == 'unidad') {
                $this->procesarAdicionalesBatch($venta, $producto->id, $adicionales, $cantidad);
            }

            if ($producto->contable) {
                $stockResponse = $this->stockService->actualizarStock(
                    $producto,
                    $cantidad === 1 ? 'sumar' : 'sumarvarios',
                    $cantidad,
                    $venta->sucursale_id
                );

                if (!$stockResponse->success) {
                    // Log::debug("Error en stockResponse, realizando rollback: ", [$stockResponse]);
                    return $stockResponse;
                }
            }
            DB::commit();

            $productoVenta = $venta->productos()
            ->where('producto_id', $producto->id)
            ->wherePivot('aceptado', false)
            ->withPivot('id', 'cantidad')
            ->first();

            return VentaResponse::success(
                $productoVenta ? $productoVenta->pivot : null,
                "ProductoVenta agregado/actualizado exitosamente"
            );
        } catch (VentaException $e) {
            DB::rollBack();
            return VentaResponse::error($e->getMessage(), [], null);
        } catch (\Exception  $e) {
            DB::rollBack();
            return VentaResponse::error('Error al agregar producto: ' . $e ->getMessage());
        }
    }

    private function procesarAdicionalesBatch(Venta $venta, int $productoId, Collection $extras, int $cantidad)
    {
        try {
            // Obtener el registro de producto_venta necesario
            $productoVenta = $venta->productos()
                    ->where('producto_id', $productoId)
                    ->wherePivot('aceptado', false)      // Obtener solo pedidos no aceptados
                    ->first();
            
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
            ->where('aceptado', false)
            ->update(['adicionales' => json_encode($json)]);

            return VentaResponse::success(null, 'Adicionales actualizados');
        } catch (\Exception $e) {
            return VentaResponse::error('Error al actualizar adicionales: ' . $e->getMessage());
        }
    }

    public function disminuirProductoCLiente(Venta $venta, int $producto_venta_id): VentaResponse 
    {
        try {
            return DB::transaction(function () use ($venta, $producto_venta_id) {
                // $habilitadoAceptados = auth()->check() && in_array(auth()->user()->role->nombre, ['admin', 'cajero']);

                if ($venta->pagado) {
                    throw VentaException::ventaPagada();
                }

                Log::debug("consulta disminuirProductoCliente con pventa id : $producto_venta_id, y producto_id: $venta->id.", []);

                $registro = DB::table('producto_venta')
                        ->where('id', $producto_venta_id)
                        ->where('venta_id', $venta->id)
                        ->where('aceptado', false)
                        ->lockForUpdate() // Add pessimistic locking
                        ->first();

                

                if (!$registro) {
                    throw new \Exception('El producto no existe en esta venta');
                }

                $producto = Producto::publicoTienda()->find($registro->producto_id);

                // Restaurar stock si es contable
                if ($producto->contable) {
                    $stockResponse = $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
                    if (!$stockResponse->success) {
                        throw new \Exception('Error al restaurar stock: ' . $stockResponse->message);
                    }
                }

                if ($registro->cantidad <= 1) {
                    throw new VentaException("No puede disminuir mas", "warning");
                }

                DB::table('producto_venta')
                    ->where('venta_id', $venta->id)
                    ->where('producto_id', $producto->id)
                    ->decrement('cantidad', 1);

                $this->actualizarAdicionales($venta, $producto, 'restar');

                // Actualizar totales
                $this->calculadoraService->actualizarTotalesVenta($venta);
                
                $productoVenta = $venta->productos()
                    ->where('producto_id', $producto->id)
                    ->wherePivot('aceptado', false)
                    ->withPivot('id', 'cantidad', 'adicionales')
                    ->first();

                return VentaResponse::success(
                    $productoVenta ? $productoVenta->pivot : null,
                    "Se eliminó 1 {$producto->nombre} de esta venta"
                );
            });
        } catch (VentaException $e) {
            if ($e->type == "warning") {
                return VentaResponse::warning($e->getMessage());
            }
            return VentaResponse::error($e->getMessage(), [], null);
        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar producto: ' . $e->getMessage());
        }
    }

    public function eliminarItemPivotID(Venta $venta, int $producto_venta_id, int $posicion): VentaResponse {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }


            $habilitadoAceptados = auth()->check() && in_array(auth()->user()->role->nombre, ['admin', 'cajero']);

            return DB::transaction(function () use ($venta, $producto_venta_id, $posicion, $habilitadoAceptados) {
                
                $consultaPV = DB::table('producto_venta')
                    ->where('id', $producto_venta_id);

                if(!$habilitadoAceptados) {
                    $consultaPV->where('aceptado', false);
                }
                
                $pivot = $consultaPV->first();

                if (!$pivot) {
                    throw new \Exception('ProductoVenta no encontrado');
                }

                $producto = Producto::find($pivot->producto_id);
                $array = json_decode($pivot->adicionales, true);

                // Revisar si es el ultimo item
                if (count($array) == 1) {
                    return VentaResponse::warning('No puede eliminar el único item disponible');
                }

                // Restaurar stock si es contable
                if ($producto->contable) {
                    $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
                }

                // Restaurar stock de adicionales
                foreach ($array[$posicion] as $nombreAdicional) {
                    $adicional = Adicionale::where('nombre', key($nombreAdicional))->first();
                    if ($adicional && $adicional->contable) {
                        $adicional->increment('cantidad');
                        $adicional->save();
                        // GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
                    }  
                }

                // Eliminar item y reorganizar
                unset($array[$posicion]);
                $array = array_values($array);
                $nuevoArray = array_combine(range(1, count($array)), array_values($array));

                // Actualizar en base de datos
                DB::table('producto_venta')
                    ->where('id', $producto_venta_id)
                    ->update(['adicionales' => json_encode($nuevoArray)]);

                DB::table('producto_venta')
                    ->where('id', $producto_venta_id)
                    ->decrement('cantidad');

                // // Actualizar totales
                // $this->calculadoraService->actualizarTotalesVenta($venta);

                return VentaResponse::success(null, 'Item eliminado correctamente');
            });

        } catch (VentaException $e) {
            return VentaResponse::warning($e->getMessage());
        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar item: ' . $e->getMessage());
        }
    }

    public function eliminarProductoCompletoCliente(Venta $venta, int $producto_venta_id)    
    {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }

            $habilitadoAceptados = auth()->check() && in_array(auth()->user()->role->nombre, ['admin', 'cajero']);

            return DB::transaction(function () use ($venta, $producto_venta_id, $habilitadoAceptados) { 

                $consultaPV = DB::table('producto_venta')
                    ->where('id', $producto_venta_id);

                if(!$habilitadoAceptados) {
                    $consultaPV->where('aceptado', false);
                }
                
                $pivot = $consultaPV->first();

                if (!$pivot) {
                    throw new \Exception('ProductoVenta no encontrado');
                }

                $producto = Producto::find($pivot->producto_id);
                $arrayOrdenes = json_decode($pivot->adicionales, true);

                // Restaurar stock si es contable
                if ($producto->contable) {
                    $this->stockService->actualizarStock($producto, 'restar', $pivot->cantidad, $venta->sucursale_id);
                }

                // Extraer todos los nombres de adicionales
                $nombresAdicionales = [];
                foreach ($arrayOrdenes as $orden) {
                    foreach ($orden as $adicionalData) {
                        $nombresAdicionales[] = key($adicionalData);
                    }
                }

                // Cargar todos los adicionales de una vez, indexados por nombre
                $adicionales = Adicionale::whereIn('nombre', array_unique($nombresAdicionales))
                    ->get()
                    ->keyBy('nombre');

                // Restaurar el stock de los adicionales del producto_venta
                for ($i = 1; $i <= count($arrayOrdenes); $i++) {
                    foreach ($arrayOrdenes[$i] as $nombreAdicional) {
                        $nombre = key($nombreAdicional);
                        $adicional = $adicionales->get($nombre);
                        
                        if ($adicional && $adicional->contable) {
                            $adicional->increment('cantidad');
                            $adicional->save();
                            // GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
                        }  
                    }
                }

                // Eliminar registrod de la tabla pivote
                DB::table('producto_venta')->where('id', $pivot->id)->delete();

                return VentaResponse::success(null, "solicitud realizada exitosamente");
            });
        } catch (VentaException $e) {
            return VentaResponse::warning($e->getMessage());
        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar el pedido: ' . $e->getMessage());
        }
    }

    // #region Transformar ProductoVenta para visualiacion en [CLIENTE-MI_PEDIDO]

    public function obtenerProductosVenta(Venta $venta): VentaResponse
    {
        try {
            if (!$venta) {
                return VentaResponse::error('No se encontró una venta activa');
            }

            $productos = $venta->productos()->get();
            
            if ($productos->isEmpty()) {
                return VentaResponse::success([], 'No hay productos en esta venta');
            }

            $productosProcessed = $this->procesarProductosVenta($productos);

            return VentaResponse::success($productosProcessed);

        } catch (\Exception $e) {
            Log::error('Error obteniendo productos de venta', [
                'venta_id' => $venta->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return VentaResponse::error('Error interno del servidor');
        }
    }

    public function obtenerProductoVentaIndividual(Venta $venta, $pivotId): VentaResponse
    {
        try {
            if (!$venta) {
                return VentaResponse::error('No se encontró una venta activa');
            }

            // Consultar por el registro del producto especifico para el pivot solicitado
            $producto = $venta->productos()
                ->wherePivot('id', $pivotId)
                ->first();

            if (!$producto) {
                return VentaResponse::success([], 'El producto buscado no existe en producto_venta');
            }

            // Procesar un unico ProductoVenta
            $productoProcesado = $this->procesarProductoVentaIndividual($producto);

            return VentaResponse::success($productoProcesado);

        } catch (\Exception $e) {
            Log::error('Error obteniendo el producto correspondiente a la venta', [
                'venta_id' => $venta->id ?? null,
                'pivot_id' => $pivotId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return VentaResponse::error('Error interno del servidor');
        }
    }

    private function procesarProductosVenta(Collection $productos): array
    {
        // Pre-cargado de todos los adicionales necesarios para el proceso
        $allAdicionalesNames = $this->extraerNombresAdicionales($productos);
        $adicionales = $this->adicionalesPorNombre($allAdicionalesNames);

        return $productos->map(function ($producto) use ($adicionales) {
            return $this->transformarProducto($producto, $adicionales);
        })->toArray();
    }

    private function procesarProductoVentaIndividual($producto): array
    {
        $adicionalesData = json_decode($producto->pivot->adicionales ?? '{}', true);
        $adicionalesNames = $this->extraerNombresDeAdicionalesData($adicionalesData);
        
        $adicionales = $this->adicionalesPorNombre(collect($adicionalesNames));
        
        return $this->transformarProducto($producto, $adicionales);
    }

    private function extraerNombresAdicionales(Collection $productos): SupportCollection
    {
        return $productos
            ->map(function ($producto) {
                $adicionalesData = json_decode($producto->pivot->adicionales ?? '{}', true);
                return $this->extraerNombresDeAdicionalesData($adicionalesData);
            })
            ->flatten()
            ->unique()
            ->filter();
    }

    private function extraerNombresDeAdicionalesData(array $adicionalesData): array
    {
        if (empty($adicionalesData)) {
            return [];
        }

        $names = [];
        foreach ($adicionalesData as $group) {
            if (is_array($group)) {
                foreach ($group as $adicional) {
                    if (is_array($adicional)) {
                        $names = array_merge($names, array_keys($adicional));
                    }
                }
            }
        }

        return $names;
    }

    private function adicionalesPorNombre(SupportCollection $names): Collection
    {
        if ($names->isEmpty()) {
            return new Collection();
        }

        return Adicionale::whereIn('nombre', $names->toArray())
            ->get()
            ->keyBy('nombre');
    }

    private function transformarProducto($producto, Collection $adicionales): array
    {
        $adicionalesData = json_decode($producto->pivot->adicionales ?? '{}', true);
        [$processedAdicionales, $precioTotalAdicionales] = $this->procesarAdicionales($adicionalesData, $adicionales);

        return [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'detalle' => $producto->detalle,
            'adicionales' => $processedAdicionales,
            'costo_adicionales' => $precioTotalAdicionales,
            'precio_final' => ($producto->precioReal() * $producto->pivot->cantidad) + $precioTotalAdicionales,  // You can add this if needed
            'tiene_descuento' => $producto->precio !== $producto->precioReal(),
            'precio_original' => $producto->precio,
            'precio' => $producto->precioReal(),
            'imagen' => $producto->pathAttachment(),
            'cantidad' => $producto->pivot->cantidad,
            'estado_actual' => $producto->pivot->estado_actual,
            'aceptado' => $producto->pivot->aceptado,
            'observacion' => $producto->pivot->observacion,
            'pivot_id' => $producto->pivot->id,
            'tipo' => ($producto->subcategoria && $producto->subcategoria->adicionales->isNotEmpty()) 
                ? 'complejo' 
                : 'simple'
        ];
    }

    private function procesarAdicionales(array $adicionalesData, Collection $adicionales): array
    {
        if (empty($adicionalesData)) {
            return [[], 0];
        }

        $processed = [];
        $precioTotal = 0;

        foreach ($adicionalesData as $groupIndex => $group) {
            $processed[$groupIndex] = [];
            
            if (!is_array($group)) {
                continue;
            }

            foreach ($group as $adicionalItem) {
                if (!is_array($adicionalItem)) {
                    continue;
                }

                foreach ($adicionalItem as $nombre => $precio) {
                    $adicional = $adicionales->get($nombre);
                    
                    if ($adicional) {
                        $processed[$groupIndex][] = [
                            'id' => $adicional->id,
                            'nombre' => ucfirst($adicional->nombre),
                            'precio' => (float) $adicional->precio  // Using $adicional->precio
                        ];
                        
                        $precioTotal += (float) $adicional->precio;
                    } else {
                        Log::warning('Adicional no encontrado', [
                            'nombre' => $nombre,
                            'available_adicionales' => $adicionales->keys()->take(10)->toArray()
                        ]);
                    }
                }
            }
        }

        return [$processed, $precioTotal];
    }

    // #endregion
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
