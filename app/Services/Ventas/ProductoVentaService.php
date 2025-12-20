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

            // Actualizar totales primero
            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Actualizar campos de descuentos en la tabla pivot después de actualizar totales
            $this->actualizarCamposDescuentos($venta, $producto);

            // Disparar evento para cocina/nutribar según sección del producto
            $this->dispararEventoPedido($venta, $producto, "Se agregó {$cantidad} {$producto->nombre}", 'success');

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

            // Validar que el último item se pueda eliminar (solo si tiene sección)
            $adicionales = json_decode($registro->adicionales, true);
            if ($adicionales && count($adicionales) > 0) {
                $ultimaPosicion = count($adicionales);
                $validacion = $this->validarEliminacionItem($producto, $adicionales, $ultimaPosicion);
                if (!$validacion->success) {
                    return $validacion;
                }
            }

            // Restaurar stock del producto si es contable
            if ($producto->contable) {
                $stockResponse = $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
                if (!$stockResponse->success) {
                    return VentaResponse::error('Error al restaurar stock: ' . $stockResponse->message);
                }
            }

            // Actualizar o eliminar registro
            if ($registro->cantidad == 1) {
                // Si es el último, restaurar stock de adicionales antes de eliminar
                if ($adicionales && count($adicionales) > 0) {
                    $ultimoItem = $adicionales[count($adicionales)];
                    $this->restaurarStockAdicionales([$ultimoItem]);
                }
                $venta->productos()->detach($producto->id);
            } else {
                // Si quedan más, actualizar adicionales (esto restaura stock automáticamente)
                $this->actualizarAdicionales($venta, $producto, 'restar');

                DB::table('producto_venta')
                    ->where('venta_id', $venta->id)
                    ->where('producto_id', $producto->id)
                    ->decrement('cantidad', 1);

                // Actualizar campos de descuentos si el producto sigue en la venta
                $this->actualizarCamposDescuentos($venta, $producto);
            }

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            $productoVenta = $venta->productos()
                ->where('producto_id', $producto->id)
                ->wherePivot('aceptado', false)
                ->withPivot('id', 'cantidad', 'adicionales')
                ->first();

            // Disparar evento para cocina/nutribar según sección del producto
            $this->dispararEventoPedido($venta, $producto, "Se eliminó 1 {$producto->nombre}", 'warning');

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

            // Decodificar adicionales
            $arrayAdicionales = json_decode($registro->adicionales, true);

            // Validar que todos los items estén pendientes si el producto tiene sección
            if ($producto->seccion && $arrayAdicionales) {
                foreach ($arrayAdicionales as $indice => $itemData) {
                    $estado = $itemData['estado'] ?? 'pendiente';
                    if ($estado !== 'pendiente') {
                        throw new \Exception(
                            "No se puede eliminar el producto completo. Tiene items en estado '{$estado}' que ya están siendo preparados. Solo se pueden eliminar productos con todos los items pendientes."
                        );
                    }
                }
            }

            // Restaurar stock del producto si es contable
            if ($producto->contable) {
                $stockResponse = $this->stockService->actualizarStock($producto, 'restar', $registro->cantidad, $venta->sucursale_id);
                if (!$stockResponse->success) {
                    return VentaResponse::error('Error al restaurar stock: ' . $stockResponse->message);
                }
            }

            // Restaurar stock de TODOS los adicionales
            if ($arrayAdicionales) {
                $this->restaurarStockAdicionales($arrayAdicionales);
            }

            // Eliminar producto
            $venta->productos()->detach($producto->id);

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Disparar evento para cocina/nutribar según sección del producto
            $this->dispararEventoPedido($venta, $producto, "Se eliminó {$producto->nombre}", 'warning');

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
                    // Compatibilidad: verificar si usa formato nuevo o antiguo
                    if (isset($array[$i]['adicionales'])) {
                        // Formato nuevo
                        $array[$i]['adicionales'][] = [$adicional->nombre => $adicional->precio];
                    } else {
                        // Formato antiguo - migrar a nuevo formato
                        $adicionalesAntiguos = $array[$i];
                        $array[$i] = [
                            'adicionales' => $adicionalesAntiguos,
                            'agregado_at' => now()->toDateTimeString(),
                            'estado' => 'pendiente'
                        ];
                        $array[$i]['adicionales'][] = [$adicional->nombre => $adicional->precio];
                    }
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

            // Actualizar campos de descuentos en la tabla pivot
            $this->actualizarCamposDescuentos($venta, $producto);

            // Actualizar totales
            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Disparar evento para cocina/nutribar según sección del producto
            $this->dispararEventoPedido($venta, $producto, "Se agregó adicional {$adicional->nombre} a {$producto->nombre}", 'success');

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

            // Validar si el item se puede eliminar (solo pendientes si tiene sección)
            $validacion = $this->validarEliminacionItem($producto, $array, $posicion);
            if (!$validacion->success) {
                return $validacion;
            }


            // Restaurar stock del producto si es contable
            if ($producto->contable) {
                $stockResponse = $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
                if (!$stockResponse->success) {
                    return VentaResponse::error('Error al restaurar stock: ' . $stockResponse->message);
                }
            }

            // Restaurar stock de adicionales del item
            $itemEliminado = $array[$posicion];
            $this->restaurarStockAdicionales([$itemEliminado]);

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

            // Actualizar totales primero
            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Actualizar campos de descuentos en la tabla pivot
            $this->actualizarCamposDescuentos($venta, $producto);

            // Disparar evento para cocina/nutribar según sección del producto
            $this->dispararEventoPedido($venta, $producto, "Se eliminó un item de {$producto->nombre}", 'warning');

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
                $string = json_encode([
                    '1' => [
                        'adicionales' => [],
                        'agregado_at' => now()->toDateTimeString(),
                        'estado' => 'pendiente'
                    ]
                ]);
                DB::table('producto_venta')
                    ->where('producto_id', $producto->id)
                    ->where('venta_id', $venta->id)
                    ->update(['adicionales' => $string]);
            } else {
                $json = json_decode($listaadicionales, true);
                $cantidad = count($json);

                switch ($operacion) {
                    case 'sumar':
                        $json[] = [
                            'adicionales' => [],
                            'agregado_at' => now()->toDateTimeString(),
                            'estado' => 'pendiente'
                        ];
                        break;

                    case 'muchos':
                        for ($i = 0; $i < $cantidadEspecifica; $i++) {
                            $json[] = [
                                'adicionales' => [],
                                'agregado_at' => now()->toDateTimeString(),
                                'estado' => 'pendiente'
                            ];
                        }
                        break;

                    case 'restar':
                        if ($registro->cantidad > 0) {
                            // Restaurar stock de adicionales del último item
                            $ultimoItem = $json[$cantidad];
                            $this->restaurarStockAdicionales([$ultimoItem]);
                            unset($json[$cantidad]);
                        }
                        break;
                }

                DB::table('producto_venta')
                    ->where('producto_id', $producto->id)
                    ->where('venta_id', $venta->id)
                    ->update(['adicionales' => json_encode($json)]);
            }

            // Actualizar totales primero
            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Actualizar campos de descuentos en la tabla pivot
            $this->actualizarCamposDescuentos($venta, $producto);

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

    // Servicio para el agregado de productos_venta con la configuracion solicitada por el cliente, validando el stock disponible
    public function agregarProductoCliente(Venta $venta, Producto $producto, Collection $adicionales, int $cantidad, ?string $observacion = null): VentaResponse
    {
        try {
            DB::beginTransaction();

            // CRITICAL FIX: Get current cantidad BEFORE updating
            $productoVentaExistente = $venta->productos()
                ->where('producto_id', $producto->id)
                ->wherePivot('aceptado', false)
                ->first();

            $existeProductoVenta = !is_null($productoVentaExistente);
            $cantidadActual = $existeProductoVenta ? $productoVentaExistente->pivot->cantidad : 0;

            // Update or create producto_venta
            if (!$existeProductoVenta) {
                $venta->productos()->attach($producto->id, [
                    'cantidad' => $cantidad,
                    'observacion' => $observacion
                ]);
            } else {
                // CRITICAL FIX: Use calculated value instead of DB::raw to avoid race conditions
                $updateData = ['cantidad' => $cantidadActual + $cantidad];

                // Si hay una nueva observación, actualizarla o concatenarla
                if ($observacion) {
                    $observacionExistente = $productoVentaExistente->pivot->observacion;
                    if ($observacionExistente && trim($observacionExistente) !== '') {
                        // Si ya hay observación, concatenar si es diferente
                        if ($observacionExistente !== $observacion) {
                            $updateData['observacion'] = $observacionExistente . ' | ' . $observacion;
                        }
                    } else {
                        $updateData['observacion'] = $observacion;
                    }
                }

                $venta->productos()
                    ->wherePivot('aceptado', false)
                    ->updateExistingPivot($producto->id, $updateData);
            }

            // Process adicionales if needed
            if ($producto->medicion == 'unidad') {
                $respuestaProcesado = $this->procesarAdicionalesBatch($venta, $producto->id, $adicionales, $cantidad);

                // CRITICAL FIX: Check if adicionales processing failed
                if (!$respuestaProcesado->success) {
                    DB::rollBack();
                    return $respuestaProcesado;
                }
            }

            // Validate and update stock
            if ($producto->contable) {
                $stockResponse = $this->stockService->actualizarStock(
                    $producto,
                    $cantidad === 1 ? 'sumar' : 'sumarvarios',
                    $cantidad,
                    $venta->sucursale_id
                );

                if (!$stockResponse->success) {
                    DB::rollBack();
                    return $stockResponse;
                }
            }

            DB::commit();

            // Get updated producto_venta
            $productoVenta = $venta->productos()
                ->where('producto_id', $producto->id)
                ->wherePivot('aceptado', false)
                ->withPivot('id', 'cantidad')
                ->first();

            $productoVentaInfo = $this->obtenerProductoVentaIndividual($venta, $productoVenta->pivot->id);
            if (!$productoVentaInfo->success) {
                throw new VentaException("Error al obtener el registro correspondiente", "error", [], 404);
            }

            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Actualizar campos de descuentos para todos los productos
            $this->actualizarTodosLosCamposDescuentos($venta);

            // Disparar evento para cocina/nutribar según sección del producto
            $this->dispararEventoPedido($venta, $producto, "Se agregó pedido de {$producto->nombre}", 'success');

            return VentaResponse::success(
                $productoVentaInfo->data,
                "ProductoVenta agregado/actualizado exitosamente"
            );
        } catch (VentaException $e) {
            Log::error("VentaException capturada, ejecutando rollback", [
                'message' => $e->getMessage(),
                'producto' => $producto->nombre ?? 'desconocido'
            ]);
            DB::rollBack();
            return VentaResponse::error($e->getMessage(), [], null);
        } catch (\Exception $e) {
            Log::error("Exception capturada, ejecutando rollback", [
                'message' => $e->getMessage(),
                'producto' => $producto->nombre ?? 'desconocido',
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollBack();
            return VentaResponse::error('Error al agregar producto: ' . $e->getMessage());
        }
    }

    // Servicio Helper para el procesado de adicionales incluidos en una solicitud realizada por el cliente.
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

            // En caso de no disponer de adicionales, insertar items vacios con metadata
            if ($extras->isEmpty()) {
                for ($i = 0; $i < $cantidad; $i++) {
                    $siguiente_clave = count($json) + 1;
                    $json[$siguiente_clave] = [
                        'adicionales' => [],
                        'agregado_at' => now()->toDateTimeString(),
                        'estado' => 'pendiente'
                    ];
                }
            } else {
                // Validar el stock necesario
                foreach ($extras as $adicional) {
                    if ($adicional->contable == true && $adicional->cantidad < $cantidad) {
                        return VentaResponse::warning("No hay stock suficiente para el adicional: {$adicional->nombre}. Stock disponible: {$adicional->cantidad}, requerido: {$cantidad}");
                    }
                }

                // Procesar las veces determinadas por la cantidad
                for ($i = 0; $i < $cantidad; $i++) {
                    $siguiente_clave = count($json) + 1;
                    $array_extras = [];

                    foreach ($extras as $adicional) {
                        if ($adicional->contable == true && $i == 0) {
                            $adicional->decrement('cantidad', $cantidad);
                            GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'reducir', $cantidad);
                        }
                        $array_extras[] = [$adicional->nombre => $adicional->precio];
                    }

                    $json[$siguiente_clave] = [
                        'adicionales' => $array_extras,
                        'agregado_at' => now()->toDateTimeString(),
                        'estado' => 'pendiente'
                    ];
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

    // Servicio para la actualizacion de una orden en "adicionales" para un registro de "productos_venta" con la configuracion solicitada por el cliente.
    public function actualizarOrdenVentaCliente(Venta $venta, $productoVenta, Producto $producto, Collection $adicionalesNuevos, int $indice): VentaResponse
    {
        try {
            DB::beginTransaction();

            $adicionalesOriginales = json_decode($productoVenta->adicionales, true);

            // Restaurar stock de los adicionales originales del item
            if (isset($adicionalesOriginales[$indice])) {
                $itemOriginal = $adicionalesOriginales[$indice];
                $this->restaurarStockAdicionales([$itemOriginal]);
            }

            $adicionalesNuevos = $adicionalesNuevos->fresh();
            $verificacionStock = $this->stockService
                ->verificarStockCompleto($producto, $adicionalesNuevos, 1, $venta->sucursale->id);

            if (!$verificacionStock->success) {
                DB::rollBack();
                throw VentaException::sinStockOrden($verificacionStock->toArray(), 422);
            }

            if ($adicionalesNuevos->isEmpty()) {
                $adicionalesOriginales[$indice] = [
                    'adicionales' => [],
                    'agregado_at' => $adicionalesOriginales[$indice]['agregado_at'] ?? now()->toDateTimeString(),
                    'estado' => $adicionalesOriginales[$indice]['estado'] ?? 'pendiente'
                ];
            } else {
                $nuevoArray = [];
                foreach ($adicionalesNuevos as $adicional) {
                    if ($adicional->contable == true) {
                        $adicional->decrement('cantidad', 1);
                        $adicional->save();
                        GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'reducir');
                    }
                    $nuevoArray[] = [$adicional->nombre => $adicional->precio];
                }
                $adicionalesOriginales[$indice] = [
                    'adicionales' => $nuevoArray,
                    'agregado_at' => $adicionalesOriginales[$indice]['agregado_at'] ?? now()->toDateTimeString(),
                    'estado' => $adicionalesOriginales[$indice]['estado'] ?? 'pendiente'
                ];
            }

            DB::table('producto_venta')
                ->where('id', $productoVenta->id)
                ->update(['adicionales' => json_encode($adicionalesOriginales)]);

            DB::commit();

            $solicitudInfoProducto = $this->obtenerProductoVentaIndividual($venta, $productoVenta->id);
            if (!$solicitudInfoProducto->success) {
                throw new VentaException("No se pudo obtener la informacion del pedido.", 'error', [], 500);
            }

            $this->calculadoraService->actualizarTotalesVenta($venta);

            // Actualizar campos de descuentos para todos los productos
            $this->actualizarTodosLosCamposDescuentos($venta);

            // Disparar evento para cocina/nutribar según sección del producto
            $this->dispararEventoPedido($venta, $producto, "Se actualizó pedido de {$producto->nombre}", 'success');

            return VentaResponse::success($solicitudInfoProducto->toArray(), "Adicionales actualizados correctamente");
        } catch (VentaException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar adicionales: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw new VentaException('Error al actualizar adicionales: ' . $e->getMessage(), 'error', [], 500);
        }
    }

    // Reduce en 1 el pedido realizado por el cliente, necesario para productos simples
    public function disminuirProductoCLiente(Venta $venta, int $producto_venta_id): VentaResponse
    {
        try {
            return DB::transaction(function () use ($venta, $producto_venta_id) {
                // $habilitadoAceptados = auth()->check() && in_array(auth()->user()->role->nombre, ['admin', 'cajero']);

                if ($venta->pagado) {
                    throw VentaException::ventaPagada();
                }

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

                // Actualizar campos de descuentos para todos los productos
                $this->actualizarTodosLosCamposDescuentos($venta);

                $productoVentaInfo = $this->obtenerProductoVentaIndividual($venta, $producto_venta_id);
                if (!$productoVentaInfo->success) {
                    throw new VentaException("Error al obtener el registro correspondiente", "error", [], 404);
                }

                // Disparar evento para cocina/nutribar según sección del producto
                $this->dispararEventoPedido($venta, $producto, "Se disminuyó cantidad de {$producto->nombre}", 'warning');

                return VentaResponse::success(
                    $productoVentaInfo ? $productoVentaInfo->data : null,
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

    // Elimina por completo un registro de un adicional en una posicion (indice) en particular, guiandose por el identificador unico de la tabla producto_venta
    // IMPORTANTE, necesario para identificar que el elemento a eliminarse es un pedido aceptado o no aceptado
    public function eliminarItemPivotID(Venta $venta, int $producto_venta_id, int $posicion): VentaResponse
    {
        try {
            if ($venta->pagado) {
                throw VentaException::ventaPagada();
            }


            $habilitadoAceptados = auth()->check() && in_array(auth()->user()->role->nombre, ['admin', 'cajero']);

            return DB::transaction(function () use ($venta, $producto_venta_id, $posicion, $habilitadoAceptados) {

                $consultaPV = DB::table('producto_venta')
                    ->where('id', $producto_venta_id);

                if (!$habilitadoAceptados) {
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

                // Validar si el item se puede eliminar (solo pendientes si tiene sección)
                $validacion = $this->validarEliminacionItem($producto, $array, $posicion);
                if (!$validacion->success) {
                    return $validacion;
                }

                // Restaurar stock del producto si es contable
                if ($producto->contable) {
                    $this->stockService->actualizarStock($producto, 'restar', 1, $venta->sucursale_id);
                }

                // Restaurar stock de adicionales del item
                $itemEliminado = $array[$posicion];
                $this->restaurarStockAdicionales([$itemEliminado]);

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

                $this->calculadoraService->actualizarTotalesVenta($venta);

                // Actualizar campos de descuentos para todos los productos
                $this->actualizarTodosLosCamposDescuentos($venta);

                // Disparar evento para cocina/nutribar según sección del producto
                $this->dispararEventoPedido($venta, $producto, "Se eliminó un item de {$producto->nombre}", 'warning');

                return VentaResponse::success(null, 'Item eliminado correctamente');
            });
        } catch (VentaException $e) {
            return VentaResponse::warning($e->getMessage());
        } catch (\Exception $e) {
            return VentaResponse::error('Error al eliminar item: ' . $e->getMessage());
        }
    }

    // Elimina por completo un registro de "producto_venta", realizando el restock necesario del producto y sus adicionales
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

                if (!$habilitadoAceptados) {
                    $consultaPV->where('aceptado', false);
                }

                $pivot = $consultaPV->first();

                if (!$pivot) {
                    throw new \Exception('ProductoVenta no encontrado');
                }

                $producto = Producto::find($pivot->producto_id);
                $arrayOrdenes = json_decode($pivot->adicionales, true);

                // Validar que todos los items estén pendientes si el producto tiene sección
                if ($producto->seccion && $arrayOrdenes) {
                    foreach ($arrayOrdenes as $indice => $itemData) {
                        $estado = $itemData['estado'] ?? 'pendiente';
                        if ($estado !== 'pendiente') {
                            throw new \Exception(
                                "No se puede eliminar el pedido. Tiene items en estado '{$estado}' que ya están siendo preparados en cocina. Solo se pueden eliminar pedidos con todos los items pendientes."
                            );
                        }
                    }
                }

                // Restaurar stock del producto si es contable
                if ($producto->contable) {
                    $this->stockService->actualizarStock($producto, 'restar', $pivot->cantidad, $venta->sucursale_id);
                }

                // Restaurar stock de TODOS los adicionales del producto_venta
                if ($arrayOrdenes) {
                    $this->restaurarStockAdicionales($arrayOrdenes);
                }

                // Eliminar registrod de la tabla pivote
                DB::table('producto_venta')->where('id', $pivot->id)->delete();

                $this->calculadoraService->actualizarTotalesVenta($venta);

                // Actualizar campos de descuentos para todos los productos
                $this->actualizarTodosLosCamposDescuentos($venta);

                // Disparar evento para cocina/nutribar según sección del producto
                $this->dispararEventoPedido($venta, $producto, "Se eliminó pedido de {$producto->nombre}", 'warning');

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

            $productosProcessed = [];
            $message = "";
            $pedidos_totales_cliente = $this->obtenerCantidadProductosVenta($venta);

            if ($productos->isEmpty()) {
                $message = 'No hay productos en esta venta';
            } else {
                $productosProcessed = $this->procesarProductosVenta($productos, $venta->sucursale_id);
                $message = 'Productos obtenidos exitosamente';
            }

            $productosyCantidad = [
                'productos' => $productosProcessed,
                'cantidad_pedido' => $pedidos_totales_cliente
            ];

            return VentaResponse::success($productosyCantidad, $message);
        } catch (\Exception $e) {
            Log::error('Error obteniendo productos de venta', [
                'venta_id' => $venta->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return VentaResponse::error('Error interno del servidor');
        }
    }

    public function obtenerProductoVentaIndividual(Venta $venta, $producto_venta_id): VentaResponse
    {
        try {
            if (!$venta) {
                return VentaResponse::error('No se encontró una venta activa');
            }

            // Consultar por el registro del producto especifico para el pivot solicitado
            $producto = $venta->productos()
                ->wherePivot('id', $producto_venta_id)
                ->first();

            if (!$producto) {
                return VentaResponse::success([], 'El producto buscado no existe en producto_venta');
            }

            // Procesar un unico ProductoVenta
            $productoProcesado = $this->procesarProductoVentaIndividual($producto, $venta->sucursale_id);
            $pedidos_totales_cliente = $this->obtenerCantidadProductosVenta($venta);
            $productoProcesado['pedidos_totales_cliente'] = $pedidos_totales_cliente;
            return VentaResponse::success($productoProcesado);
        } catch (\Exception $e) {
            Log::error('Error obteniendo el producto correspondiente a la venta', [
                'venta_id' => $venta->id ?? null,
                'pivot_id' => $producto_venta_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return VentaResponse::error('Error interno del servidor');
        }
    }

    public function obtenerOrdenPorIndice(Venta $venta, int $producto_venta_id, int $indice): VentaResponse
    {
        try {
            if (!$venta) {
                return VentaResponse::error('No se encontró una venta activa');
            }

            // Consultar por el registro del producto especifico para el pivot solicitado
            $registro = DB::table('producto_venta')
                ->where('aceptado', false)
                ->find($producto_venta_id);

            $adicionalesProductoVenta = json_decode($registro->adicionales, true);
            $itemData = $adicionalesProductoVenta[$indice];
            $adicionalesIndice = $itemData['adicionales'] ?? $itemData; // Compatibilidad con formato antiguo

            $adicionales = [];

            foreach ($adicionalesIndice as $adic) {
                $adicional = Adicionale::where('nombre', key($adic))->first();
                $adicionales[] = [
                    "id" => $adicional->id,
                    "nombre" => $adicional->nombre,
                    "precio" => $adicional->precio
                ];
            }
            return VentaResponse::success($adicionales, "Exito en el llamado al servicio");
        } catch (\Throwable $th) {
            return VentaResponse::error('Error interno del servidor');
        }
    }

    private function procesarProductosVenta(Collection $productos, int $sucursaleId): array
    {
        // Pre-cargado de todos los adicionales necesarios para el proceso
        $allAdicionalesNames = $this->extraerNombresAdicionales($productos);
        $adicionales = $this->adicionalesPorNombre($allAdicionalesNames);

        return $productos->map(function ($producto) use ($adicionales, $sucursaleId) {
            return $this->transformarProducto($producto, $adicionales, $sucursaleId);
        })->toArray();
    }

    private function procesarProductoVentaIndividual(Producto $producto, int $sucursaleId): array
    {
        $adicionalesData = json_decode($producto->pivot->adicionales ?? '{}', true);
        $adicionalesNames = $this->extraerNombresDeAdicionalesData($adicionalesData);

        $adicionales = $this->adicionalesPorNombre(collect($adicionalesNames));

        return $this->transformarProducto($producto, $adicionales, $sucursaleId);
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
                // Verificar si es formato nuevo (con 'adicionales')
                if (isset($group['adicionales'])) {
                    foreach ($group['adicionales'] as $adicional) {
                        if (is_array($adicional)) {
                            $names = array_merge($names, array_keys($adicional));
                        }
                    }
                } else {
                    // Formato antiguo
                    foreach ($group as $adicional) {
                        if (is_array($adicional)) {
                            $names = array_merge($names, array_keys($adicional));
                        }
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

    private function transformarProducto($producto, Collection $adicionales, int $sucursaleId): array
    {
        $adicionalesData = json_decode($producto->pivot->adicionales ?? '{}', true);
        [$processedAdicionales, $precioTotalAdicionales] = $this->procesarAdicionales($adicionalesData, $adicionales);
        $stockDisponible = $this->stockService->obtenerStockTotal($producto, $sucursaleId);

        return [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'detalle' => $producto->detalle,
            'adicionales' => $processedAdicionales,
            'costo_adicionales' => $precioTotalAdicionales,
            'stock_disponible' => $stockDisponible,
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

            // Verificar si es formato nuevo (con 'adicionales')
            $adicionalesArray = $group['adicionales'] ?? $group;

            foreach ($adicionalesArray as $adicionalItem) {
                if (!is_array($adicionalItem)) {
                    continue;
                }

                foreach ($adicionalItem as $nombre => $precio) {
                    $adicional = $adicionales->get($nombre);

                    if ($adicional) {
                        $processed[$groupIndex][] = [
                            'id' => $adicional->id,
                            'nombre' => ucfirst($adicional->nombre),
                            'precio' => (float) $adicional->precio
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

    private function obtenerCantidadProductosVenta(Venta $venta): int
    {
        return (int) $venta->productos()->sum('cantidad');
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

    private function actualizarCamposDescuentos(Venta $venta, Producto $producto): void
    {
        // Calcular descuentos usando el servicio de convenio
        $calculos = $this->calculadoraService->calcularVenta($venta);
        $prodLista = collect($calculos->listaCuenta)->firstWhere('id', $producto->id);

        if ($prodLista) {

            // Calcular total original (precio original * cantidad)
            $totalOriginal = $prodLista['precio_original'] * $prodLista['cantidad'];

            DB::table('producto_venta')
                ->where('venta_id', $venta->id)
                ->where('producto_id', $producto->id)
                ->update([
                    'total' => $totalOriginal,
                    'descuento_producto' => $prodLista['descuento_producto'] ?? 0,
                    'descuento_convenio' => $prodLista['descuento_convenio'] ?? 0,
                    'total_adicionales' => $prodLista['total_adicionales'] ?? 0,
                ]);
        } else {
            Log::warning('No se encontró producto en lista calculada', [
                'producto_id' => $producto->id,
                'venta_id' => $venta->id
            ]);
        }
    }

    /**
     * Actualiza los campos de descuentos para TODOS los productos de la venta
     * Utilizar este método cuando se necesite recalcular todos los productos
     * (por ejemplo, cuando cambian convenios o descuentos globales)
     */
    private function actualizarTodosLosCamposDescuentos(Venta $venta): void
    {
        // Calcular descuentos usando el servicio de convenio
        $calculos = $this->calculadoraService->calcularVenta($venta);

        if (empty($calculos->listaCuenta)) {
            Log::warning('Lista de cuenta vacía al actualizar campos de descuentos', [
                'venta_id' => $venta->id
            ]);
            return;
        }

        foreach ($calculos->listaCuenta as $prodLista) {
            // Calcular total original (precio original * cantidad)
            $totalOriginal = $prodLista['precio_original'] * $prodLista['cantidad'];

            DB::table('producto_venta')
                ->where('venta_id', $venta->id)
                ->where('producto_id', $prodLista['id'])
                ->update([
                    'total' => $totalOriginal,
                    'descuento_producto' => $prodLista['descuento_producto'] ?? 0,
                    'descuento_convenio' => $prodLista['descuento_convenio'] ?? 0,
                    'total_adicionales' => $prodLista['total_adicionales'] ?? 0,
                ]);
        }
    }

    /**
     * Cambia el estado de un item específico en el campo adicionales
     * 
     * @param int $producto_venta_id ID del registro en producto_venta
     * @param int $indice Índice del item (1, 2, 3, etc.)
     * @param string $nuevoEstado Nuevo estado ('pendiente', 'preparacion', 'despachado')
     * @return VentaResponse
     */
    public function cambiarEstadoItem(int $producto_venta_id, int $indice, string $nuevoEstado): VentaResponse
    {
        try {
            $registro = DB::table('producto_venta')->find($producto_venta_id);

            if (!$registro) {
                return VentaResponse::error('Producto no encontrado');
            }

            $adicionales = json_decode($registro->adicionales, true);

            if (!isset($adicionales[$indice])) {
                return VentaResponse::error('Item no encontrado en el índice especificado');
            }

            // Verificar si es formato nuevo o migrar
            if (!isset($adicionales[$indice]['estado'])) {
                // Migrar formato antiguo a nuevo
                $adicionales[$indice] = [
                    'adicionales' => $adicionales[$indice],
                    'agregado_at' => now()->toDateTimeString(),
                    'estado' => $nuevoEstado
                ];
            } else {
                // Actualizar estado en formato nuevo
                $adicionales[$indice]['estado'] = $nuevoEstado;
            }

            // Actualizar en base de datos
            DB::table('producto_venta')
                ->where('id', $producto_venta_id)
                ->update(['adicionales' => json_encode($adicionales)]);

            // Calcular y actualizar el estado_actual según los estados de todos los items
            $nuevoEstadoActual = $this->calcularEstadoActual($adicionales);
            DB::table('producto_venta')
                ->where('id', $producto_venta_id)
                ->update(['estado_actual' => $nuevoEstadoActual]);

            return VentaResponse::success(null, 'Estado del item actualizado correctamente');
        } catch (\Exception $e) {
            return VentaResponse::error('Error al cambiar estado del item: ' . $e->getMessage());
        }
    }

    /**
     * Valida si un item puede ser eliminado basándose en su estado
     * Solo se pueden eliminar items pendientes si el producto tiene sección
     * 
     * @param Producto $producto Producto del item
     * @param array $adicionales Array completo de adicionales
     * @param int $posicion Posición del item a eliminar
     * @return VentaResponse
     */
    private function validarEliminacionItem(Producto $producto, array $adicionales, int $posicion): VentaResponse
    {
        // Si el producto no tiene sección, permitir eliminar siempre
        if (!$producto->seccion) {
            return VentaResponse::success(null, 'Validación exitosa');
        }

        // Verificar si el item existe
        if (!isset($adicionales[$posicion])) {
            return VentaResponse::error('Item no encontrado en la posición especificada');
        }

        $itemData = $adicionales[$posicion];
        $estado = $itemData['estado'] ?? 'pendiente';

        // Si el item no está pendiente, no permitir eliminarlo
        if ($estado !== 'pendiente') {
            return VentaResponse::error(
                "No se puede eliminar. El item está en estado '{$estado}' y ya está siendo preparado en cocina. Solo se pueden eliminar items pendientes."
            );
        }

        return VentaResponse::success(null, 'Validación exitosa');
    }

    /**
     * Calcula el estado_actual del registro producto_venta basándose en los estados de todos sus items
     * 
     * Lógica:
     * - Si todos están despachados → 'despachado'
     * - Si hay alguno en preparación → 'preparacion'
     * - En cualquier otro caso → 'pendiente'
     * 
     * @param array $adicionales Array de adicionales del producto_venta
     * @return string Estado calculado ('pendiente', 'preparacion', 'despachado')
     */
    private function calcularEstadoActual(array $adicionales): string
    {
        $todosDespachados = true;
        $hayEnPreparacion = false;

        foreach ($adicionales as $item) {
            if (isset($item['estado'])) {
                $estado = $item['estado'];

                if ($estado !== 'despachado') {
                    $todosDespachados = false;
                }

                if ($estado === 'preparacion') {
                    $hayEnPreparacion = true;
                }
            } else {
                // Si no tiene estado (formato antiguo), considerarlo como pendiente
                $todosDespachados = false;
            }
        }

        if ($todosDespachados) {
            return 'despachado';
        } elseif ($hayEnPreparacion) {
            return 'pendiente';
        } else {
            return 'pendiente';
        }
    }

    /**
     * Verifica si todos los items de un producto_venta están en estado 'despachado'
     * 
     * @param array $adicionales Array de adicionales del producto_venta
     * @return bool
     */
    private function todosItemsDespachados(array $adicionales): bool
    {
        foreach ($adicionales as $item) {
            // Verificar formato nuevo
            if (isset($item['estado'])) {
                if ($item['estado'] !== 'despachado') {
                    return false;
                }
            } else {
                // Formato antiguo no tiene estado, considerarlo como pendiente
                return false;
            }
        }

        return true;
    }

    /**
     * Restaura el stock de adicionales contables de un item o varios items
     * 
     * @param array $items Array de items (formato: [['adicionales' => [...]] ó formato antiguo])
     * @param int|null $cantidad Cantidad de veces a restaurar (default: 1 por cada item)
     * @return void
     */
    private function restaurarStockAdicionales(array $items, ?int $cantidad = null): void
    {
        // Si está vacío, no hacer nada
        if (empty($items)) {
            return;
        }

        // Detectar si es un solo item o un array de items
        // Un solo item tiene la clave 'adicionales' directamente
        if (isset($items['adicionales'])) {
            // Es un solo item en formato nuevo
            $items = [$items];
        } else {
            // Es un array de items, convertir a indexado desde 0
            $primerElemento = reset($items);

            // Si el primer elemento no es un array, es formato antiguo de un solo item
            if (!is_array($primerElemento)) {
                $items = [$items];
            } else {
                // Es array de items, normalizar índices
                $items = array_values($items);
            }
        }

        foreach ($items as $itemData) {
            // Compatibilidad con formato antiguo y nuevo
            $adicionalesItem = $itemData['adicionales'] ?? $itemData;

            // Si está vacío, continuar con el siguiente
            if (empty($adicionalesItem)) {
                continue;
            }

            foreach ($adicionalesItem as $adic) {
                $nombreAdicional = is_array($adic) ? key($adic) : $adic;
                $adicional = Adicionale::where('nombre', $nombreAdicional)->first();

                if ($adicional && $adicional->contable) {
                    $cantidadRestaurar = $cantidad ?? 1;

                    // Restaurar stock en la tabla adicionales
                    $adicional->increment('cantidad', $cantidadRestaurar);
                    $adicional->save();

                    // Actualizar en el menú del día (tabla almuerzos) - UNA sola llamada con la cantidad
                    GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar', $cantidadRestaurar);
                }
            }
        }
    }

    /**
     * Dispara el evento de actualización para cocina/nutribar según la sección del producto
     */
    private function dispararEventoPedido(Venta $venta, Producto $producto, string $mensaje, string $icono): void
    {
        // Solo disparar evento si el producto tiene una sección definida
        if (!empty($producto->seccion)) {
            $userId = auth()->check() ? auth()->user()->id : null;

            if ($venta->cliente) {
                $mensaje = $mensaje . ', para el cliente: ' . $venta->cliente->name;
            }
            event(new CocinaPedidoEvent($mensaje, $venta->id, $userId, $producto->seccion, $icono));
        }
    }
}
