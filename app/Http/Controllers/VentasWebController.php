<?php

namespace App\Http\Controllers;

use App\Models\Adicionale;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Services\Ventas\Contracts\ProductoVentaServiceInterface;
use App\Services\Ventas\Contracts\StockServiceInterface;
use App\Services\Ventas\DTOs\VentaResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VentasWebController extends Controller
{
    public function __construct(
        private ProductoVentaServiceInterface $productoVentaService,
        private StockServiceInterface $stockService
        // private CalculadoraVentaServiceInterface $calculadoraService
    ) {}
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
            // Log::info('Llamado a la funcion carrito_ProductosVenta');
            $user = User::find(auth()->user()->id);

            $carrito = collect($request->carrito['items']);
            // Log::debug('Contenido del carrito recibido desde frontEnd', [$carrito]);

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
                    $producto_id = $item['id'];
                    $detalles = $item['detalles'];

                    $producto = Producto::publicoTienda()->findOrFail($producto_id); 

                    foreach ($detalles as $detalle) {
                        $adicionales_ids = $detalle['adicionales'] ?? [];
                        $cantidad_detalle = $detalle['cantidad'];
                        $adicionales = Adicionale::whereIn('id', $adicionales_ids)->get()->keyBy('id');

                        $respuestaAdicion = $this->productoVentaService
                        ->agregarProductoCliente($venta_activa, 
                                                $producto, 
                                                $adicionales, 
                                                $cantidad_detalle );
                    }

                    // Log::info("Procesando producto del carrito", [
                    //     'producto_id' => $producto_id,
                    //     'cantidad_total' => $cantidad_total,
                    //     'adicionales_estructura' => $adicionales_estructura,
                    //     'detalles_count' => count($detalles)
                    // ]);
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

        // De momento trabajamos con un sucursale_id = 1;
        $verificacionStock = $this->stockService->verificarStockCompleto($producto, $adicionales, $cantidad, 1);

        if (!$verificacionStock->success) {
            return response()->json($verificacionStock->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Revisar si el usuario se encuentra autenticado
        if (!auth()->check()) {
            $ventaResponse = VentaResponse::error(
                'El usuario no ha iniciado sesión. El producto se agregará a su carrito.',
                ['sesion_inactiva' => 'El usuario no ha iniciado sesión en la aplicación']
            );
            return new JsonResponse($ventaResponse->toArray(), Response::HTTP_CONFLICT);
        }
        // Revisar si el usuario tiene una venta activa
        $user = auth()->user();
        $venta_activa = $user->ventaActiva;

        if (! $venta_activa) {
            $ventaResponse =  VentaResponse::error(
                'No hay venta activa. El producto se agregará a su carrito.',
                ['venta_activa' => 'No se encontró una venta activa para el usuario']
            );
            return new JsonResponse($ventaResponse->toArray(), Response::HTTP_CONFLICT);
        }

        // No comparar con detalles existentes, siempre agregar nuevos registros (orden) 
        // dentro de adicionales en producto_venta
        
        $respuestaVenta = $this->productoVentaService->agregarProductoCliente($venta_activa, $producto, $adicionales, $cantidad);
        if (!$respuestaVenta->success) {
            return response()->json($respuestaVenta->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return response()->json([
            'message'=> 'Solicitud agregar venta procesada exitosamente',
        ],Response::HTTP_CREATED);
    }
}
