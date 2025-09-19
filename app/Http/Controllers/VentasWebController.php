<?php

namespace App\Http\Controllers;

use App\Helpers\ProductosHelper;
use App\Helpers\VentasClienteHelper;
use App\Models\Adicionale;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VentasWebController extends Controller
{
    public function generarVentaQR() {   
        
        try {
            $user=User::find(auth()->user()->id);
            
            // Determinar si el usuario dispone de una venta activa
            $venta_activa = $user->ventaActiva;

            if ($venta_activa) {
                // Ya existe una venta activa para el usuario
                return response()->json([
                    'message' => 'Ya existe una venta activa para este usuario',
                    'venta_id' => $venta_activa->id,    
                ], 409);
            } else {
                // No existe una venta activa para el usuario
                $nueva_venta = Venta::create([
                    
                    'usuario_id' => 1,
                    'sucursale_id' => 1, // Basado en el JSON proporcionado
                    'cliente_id' => $user->id, // Asumiendo que el usuario es también el cliente
                    'total' => 0.00,
                    'puntos' => 0,
                    'descuento' => 0.00,
                    'tipo' => null,
                    'usuario_manual' => 'prueba',
                    'impreso' => 0,
                    'pagado' => 0,
                    'cocina' => 0,
                    'cocina_at' => null,
                    'despachado_cocina' => 0,
                    'historial_venta_id' => null,
                    'tipo_entrega' => null
                ]);
                return response()->json([
                    'message'=> 'La nueva venta para el usuario fue generada exitosamente',
                    'venta_id' => $nueva_venta->id,
                    'data' => $nueva_venta,
                ], 200);
            }
        } catch (\Throwable $th) {
            Log::error("Error al tratar de revisar/generar venta para el cliente-usuario", [
                'error' => $th->getMessage(),
                'user_id' => auth()->user()->id ?? null,
                'trace' => $th->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => 'No se pudo procesar la solicitud'
            ], 500);
        }
    }

    public function carrito_ProductosVenta(Request $request) {
        try {
            Log::info('Llamado a la funcion carrito_ProductosVenta');
            $user = User::find(auth()->user()->id);

            $carrito = collect($request->carrito['items']);
            Log::debug('Contenido del carrito recibido desde frontEnd', [$carrito]);

            $adicionales_generales = Adicionale::all()->keyBy('id'); // Indexar por ID para acceso rápido

            $venta_activa = $user->ventaActiva;

            // Verificar que existe una venta activa
            if (!$venta_activa) {
                return response()->json([
                    'message' => 'No se encontró una venta activa para el usuario',
                    'error' => 'Venta activa requerida'
                ], 404);
            }

            // Usar transacción para asegurar consistencia de datos
            DB::transaction(function () use ($carrito, $venta_activa, $adicionales_generales, $user) {
                foreach ($carrito as $item) {
                    $main_id = $item['id'];
                    $detalles = $item['detalles'];

                    // Calcular cantidad total para este producto
                    $cantidad_total = array_sum(array_column($detalles, 'cantidad'));

                    // Construir estructura de adicionales esperada por el sistema
                    $adicionales_estructura = [];
                    $orden_counter = 1;

                    foreach ($detalles as $detalle) {
                        $adicionales_detalle = $detalle['adicionales'];
                        $cantidad_detalle = $detalle['cantidad'];

                        // Para cada cantidad de este detalle, crear una entrada separada
                        for ($i = 0; $i < $cantidad_detalle; $i++) {
                            if ($adicionales_detalle === null) {
                                // Para items sin adicionales (como base)
                                $adicionales_estructura[(string)$orden_counter] = [];
                            } else {
                                // Para items con adicionales, crear objetos mockup
                                $extras_array = [];
                                foreach ($adicionales_detalle as $adicional_id) {
                                    $infoAdicional = $adicionales_generales->get($adicional_id);
                                    if ($infoAdicional) {
                                        // Crear estructura como en producción: {"nombre_adicional": "precio"}
                                        $extras_array[] = [
                                            $infoAdicional->nombre => $infoAdicional->precio
                                        ];
                                    } else {
                                        // Log si no se encuentra el adicional
                                        Log::warning("Adicional ID no encontrado al sincronizar carrito con QR Venta para el usuario con id {user_id}", [
                                            'user_id' => $user->id,
                                            'adicional_id' => $adicional_id
                                        ]);
                                    }
                                }
                                $adicionales_estructura[(string)$orden_counter] = $extras_array;
                            }
                            $orden_counter++;
                        }
                    }

                    // Crear una sola entrada por producto
                    $venta_activa->productos()->attach($main_id, [
                        'cantidad' => $cantidad_total,
                        'adicionales' => json_encode($adicionales_estructura),
                        'estado_actual' => 'pendiente',
                        'observacion' => null,
                    ]);

                    Log::info("Procesando producto del carrito", [
                        'producto_id' => $main_id,
                        'cantidad_total' => $cantidad_total,
                        'adicionales_estructura' => $adicionales_estructura,
                        'detalles_count' => count($detalles)
                    ]);
                }
            });

            Log::info('Carrito procesado exitosamente en transacción');

            return response()->json([
                'message' => 'Carrito procesado exitosamente',
                'items_procesados' => $carrito->sum(function ($item) {
                    return count($item['detalles']);
                })
            ], 200);

        } catch (\Throwable $th) {
            Log::error("Error al generar nuevos producto_venta para el carrito", [
                'error' => $th->getMessage(),
                'user_id' => auth()->user()->id ?? null,
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => 'No se pudo procesar la solicitud'
            ], 500);
        }
    }
    public function agregarProductoVenta(Request $request) {
        $producto_id = $request->producto_id;
        $adicionales_ids = $request->adicionales_ids;
        $cantidad = $request->cantidad;

        $producto = Producto::publicoTienda()->findOrFail($producto_id); 
        if (! $producto) {
            return response()->json([
                'message'=> 'El producto solicitado no existe',
                'error' => 'No se encontro una registro de producto perteneciente al identificador utilizado'
            ],Response::HTTP_NOT_FOUND);
        }
        $adicionales = Adicionale::whereIn('id', $adicionales_ids)->get()->keyBy('id');

        $productosHelper = new ProductosHelper();
        $productosHelper->verificarStockGeneral($producto,$adicionales,$cantidad);

        // $productosHelper->verificarStockGeneral($producto,$adicionales_ids,$cantidad);

        // Revisar si el usuario se encuentra autenticado
        if (!auth()->check()) {
            return response()->json([
                'message'=> 'El usuario no ha iniciado sesión. El producto se agregará a su carrito.',
                'error' => 'El usuario no ha iniciado sesión en la aplicación'
            ], Response::HTTP_CONFLICT);
        }

        // Revisar si el usuario tiene una venta activa
        $user = auth()->user();
        $venta_activa = $user->ventaActiva;
        
        if (! $venta_activa) {
            return response()->json([
                'message'=> 'No hay venta activa. El producto se agregará a su carrito.',
                'error' => 'No se encontró una venta activa para el usuario'
            ], Response::HTTP_CONFLICT);
        }

        // No comparar con detalles existentes, siempre agregar nuevos registros (orden) 
        // dentro de adicionales en producto_venta
        $ventasHelper = new VentasClienteHelper($venta_activa);

        Log::debug('Contenido del producto recibido desde frontEnd: 
        ProductoID: {producto_id}, Adicionales: {adicionales_ids}, Cantidad: {cantidad}', [$producto_id, $adicionales_ids, $cantidad]);
        
        $ventasHelper->adicionar($producto, $adicionales, $cantidad);

        return response()->json([
            'message'=> 'Solicitud agregar venta procesada exitosamente',
        ],Response::HTTP_CREATED);
    }

    // protected function agregarADicional(Adicionale $adicional, $item)


    // public function carrito_ProductosVenta(Request $request) {
    //     try {
    //         // Log::info('Llamado a la funcion carrito_ProductosVenta');
    //         $user=User::find(auth()->user()->id);
    //         // Log::debug('Contenido de request recibido desde frontEnd',[$request]);
    //         $carrito = collect($request->carrito['items']);
    //         // Log::debug('Contenido del carrito recibido desde frontEnd',[$carrito]);
    //         $venta_activa = $user->ventaActiva;

    //         // Verificar que existe una venta activa
    //         if (!$venta_activa) {
    //             return response()->json([
    //                 'message' => 'No se encontró una venta activa para el usuario',
    //                 'error' => 'Venta activa requerida'
    //             ], 404);
    //         }
            
    //         DB::transaction(function() use ($carrito, $venta_activa)
    //         {
    //             foreach ($carrito as $item) {
    //                 $main_id = $item['id'];
    //                 $detalles = $item['detalles'];
                    
    //                 // Iterar a través de cada detalle del item
    //                 foreach ($detalles as $detalle) {
    //                     $adicionales = $detalle['adicionales'];
    //                     $cantidad = $detalle['cantidad'];
                        
    //                     $venta_activa->productos()->attach($main_id, [
    //                         'cantidad' => $cantidad,
    //                         'adicionales' => json_encode($adicionales), // Store as JSON
    //                         'estado_actual' => 'pendiente',
    //                         'observacion' => null,
    //                     ]);

    //                     Log::info("Procesando item del carrito", [
    //                         'main_id' => $main_id,
    //                         'adicionales' => $adicionales,
    //                         'cantidad' => $cantidad,
    //                         'key' => $detalle['key']
    //                     ]);
    //                 }
    //             }                
    //         });

    //         return response()->json([
    //             'message' => 'Carrito procesado exitosamente',
    //             'items_procesados' => $carrito->sum(function ($item) {
    //             return count($item['detalles']);
    //         })
    //         ], 200);

    //     } catch (\Throwable $th) {
    //         Log::error("Error al generar nuevos producto_venta para el carrito", [
    //             'error' => $th->getMessage(),
    //             'user_id' => auth()->user()->id ?? null,
    //             'trace' => $th->getTraceAsString()
    //         ]);
    //         return response()->json([
    //             'message' => 'Error interno del servidor',
    //             'error' => 'No se pudo procesar la solicitud'
    //         ], 500);
    //     }
    // }
}
