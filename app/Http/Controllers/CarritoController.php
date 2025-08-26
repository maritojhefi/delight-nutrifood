<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\SucursalesController;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CarritoController extends Controller
{
    public function index()
    {
        $user=User::find(auth()->user()->id);

        $listado = [
            ];        
       

        return view('client.carrito.index',compact('user','listado'));
    }
    public function addToCarrito($id)
    {
        if(!Auth::check())
        {
            return 'logout';
        }
        $siExiste=DB::table('producto_user')->where('producto_id',$id)->where('user_id',auth()->user()->id)->first();
        if($siExiste)
        {
            DB::table('producto_user')->where('producto_id',$id)->where('user_id',auth()->user()->id)->increment('cantidad');
        }
        else
        {
            DB::table('producto_user')->insert(['user_id'=>auth()->user()->id,'producto_id'=>$id]);
        }
    }

    // public function validateCarrito(Request $request)
    // {
    //     try {
    //         $sucursaleId = 1; // Hardcodeado por ahora, pero deberia obtenerse de $request->sucursale_id
    //         $itemsCarrito = collect($request->items);
            
    //         // Separa productos de planes
    //         // $idsProductos = $itemsCarrito->where('isPlan', false)->pluck('id');
    //         $idsProductos = $itemsCarrito->pluck('id');

    //         // $idsPlanes = $itemsCarrito->where('isPlan', true)->pluck('id');

    //         Log::info('Validating cart items', [
    //             'sucursale_id' => $sucursaleId,
    //             'product_ids' => $idsProductos,
    //             // 'plan_ids' => $idsPlanes,
    //             'cart_items' => $itemsCarrito->toArray()
    //         ]);

    //         // Procesa productos regulares
    //         $productos = collect();
            
    //         if ($idsProductos->isNotEmpty()) {
    //             $productos = Producto::whereIn('id', $idsProductos)
    //                 // ->with(['sucursale' => function($query) use ($sucursaleId) {
    //                 //     $query->where('sucursale_id', $sucursaleId);
    //                 // }])
    //                 ->get()
    //                 ->map(function ($producto) use ($itemsCarrito, $sucursaleId) {
    //                     $cartItem = $itemsCarrito->firstWhere('id', $producto->id);

    //                     if ($producto->unfilteredSucursale->isNotEmpty()) {
    //                         $stockSucursal = $producto->sucursale->firstWhere('pivot.sucursale_id', $sucursaleId);
    //                         $stockDisponible = $stockSucursal ? $stockSucursal->pivot->cantidad : 0;
    //                     } else {
    //                         $stockSucursal = "INFINITO";
    //                         $stockDisponible = "INFINITO";
    //                     }

    //                     // $stockSucursal = $producto->sucursale->firstWhere('pivot.sucursale_id', $sucursaleId);

    //                     Log::debug('Processing product', [
    //                         'product_id' => $producto->id,
    //                         'cart_item' => $cartItem,
    //                         'stock_sucursal' => $stockSucursal ? [
    //                             'sucursale_id' => $stockSucursal->id,
    //                             'pivot' => $stockSucursal->pivot
    //                         ] : null
    //                     ]);
                        
    //                     $cantidadSolicitada = $cartItem['quantity'] ?? 0;
                        
    //                     $estado = $this->determinarEstado($stockDisponible, $cantidadSolicitada);
                        
    //                     return [
    //                         'id' => $producto->id,
    //                         'nombre' => $producto->nombre,
    //                         'detalle' => $producto->detalle,
    //                         'precio' => $producto->precio,
    //                         'imagen' => $producto->imagen,
    //                         'sucursale_id' => $sucursaleId,
    //                         'stock_disponible' => $stockDisponible,
    //                         'cantidad_solicitada' => $cantidadSolicitada,
    //                         'estado' => $estado,
    //                         // 'max_permitido' => min($stockDisponible, $stockSucursal->pivot->max ?? 10),
    //                         'max_permitido' => $stockDisponible,
    //                         // 'isPlan' => false // Mark as regular product
    //                     ];
    //                 });
    //         }

    //         $itemsDisponibles = $productos->filter(fn($p) => $p['estado'] === 'disponible');
    //         $itemsEscasos = $productos->filter(fn($p) => $p['estado'] === 'escaso');
    //         $itemsAgotados = $productos->filter(fn($p) => $p['estado'] === 'agotado');

    //         return response()->json([
    //             'disponibles' => $itemsDisponibles,
    //             'escasos' => $itemsEscasos,
    //             'agotados' => $itemsAgotados,
    //             'resumen' => [
    //                 // 'total_items' => $allItems->count(),
    //                 'total_items' => $productos->count(),
    //                 'disponibles' => $itemsDisponibles->count(),
    //                 'escasos' => $itemsEscasos->count(),
    //                 'agotados' => $itemsAgotados->count()
    //             ]
    //         ]);
    //     } catch (\Throwable $th) {
    //         Log::error('Error validating cart items', [
    //             'error' => $th->getMessage(),
    //             'trace' => $th->getTraceAsString()
    //         ]);
    //         return response()->json([
    //             'error' => 'Error al validar los items del carrito. Por favor, intente nuevamente.'
    //         ], 500);
    //     }
    // }

    // protected function determinarEstado($stockDisponible, $cantidadSolicitada)
    // {
    //     if ($stockDisponible <= 0) {
    //         return 'agotado';
    //     }
        
    //     if ($stockDisponible >= $cantidadSolicitada || $stockDisponible == "INFINITO") {
    //         return 'disponible';
    //     }
        
    //     return 'escaso';
    // }

    public function validateCarrito(Request $request)
    {
        try {
            $sucursaleId = 1; // Hardcodeado por ahora, pero deberia obtenerse de $request->sucursale_id
            $itemsCarrito = collect($request->items);
            
            // Separa productos de planes
            // $idsProductos = $itemsCarrito->where('isPlan', false)->pluck('id');
            $idsProductos = $itemsCarrito->pluck('id');

            // $idsPlanes = $itemsCarrito->where('isPlan', true)->pluck('id');

            Log::info('Validating cart items', [
                'sucursale_id' => $sucursaleId,
                'product_ids' => $idsProductos,
                // 'plan_ids' => $idsPlanes,
                'cart_items' => $itemsCarrito->toArray()
            ]);

            // Procesa productos regulares
            $productos = collect();
            
            if ($idsProductos->isNotEmpty()) {
                $productos = Producto::whereIn('id', $idsProductos)
                    // ->with(['sucursale' => function($query) use ($sucursaleId) {
                    //     $query->where('sucursale_id', $sucursaleId);
                    // }])
                    ->get()
                    ->map(function ($producto) use ($itemsCarrito, $sucursaleId) {
                        $cartItem = $itemsCarrito->firstWhere('id', $producto->id);

                        // Check if product has stock relations (limited stock) or not (infinite stock)
                        $isInfiniteStock = $producto->unfilteredSucursale->isEmpty();
                        
                        if ($isInfiniteStock) {
                            // Infinite stock product
                            $stockSucursal = null;
                            $stockDisponible = "INFINITO";
                        } else {
                            // Limited stock product
                            $stockSucursal = $producto->unfilteredSucursale->firstWhere('pivot.sucursale_id', $sucursaleId);
                            $stockDisponible = $stockSucursal ? $stockSucursal->pivot->cantidad : 0;
                        }

                        // Log::debug('Processing product', [
                        //     'product_id' => $producto->id,
                        //     'cart_item' => $cartItem,
                        //     'is_infinite_stock' => $isInfiniteStock,
                        //     'stock_sucursal' => $stockSucursal ? [
                        //         'sucursale_id' => $stockSucursal->id,
                        //         'pivot' => $stockSucursal->pivot
                        //     ] : 'infinite_stock'
                        // ]);
                        
                        $cantidadSolicitada = $cartItem['quantity'] ?? 0;
                        
                        $estado = $this->determinarEstado($stockDisponible, $cantidadSolicitada);
                        
                        return [
                            'id' => $producto->id,
                            'nombre' => $producto->nombre,
                            'detalle' => $producto->detalle,
                            'precio' => $producto->precioReal(),
                            'imagen' => asset('imagenes/productos/' . $producto->imagen),
                            'sucursale_id' => $sucursaleId,
                            'stock_disponible' => $stockDisponible,
                            'cantidad_solicitada' => $cantidadSolicitada,
                            'estado' => $estado,
                            'max_permitido' => $stockDisponible === "INFINITO" ? "INFINITO" : $stockDisponible,
                            // 'isPlan' => false // Mark as regular product
                        ];
                    });
            }

            $itemsDisponibles = $productos->filter(fn($p) => $p['estado'] === 'disponible');
            $itemsEscasos = $productos->filter(fn($p) => $p['estado'] === 'escaso');
            $itemsAgotados = $productos->filter(fn($p) => $p['estado'] === 'agotado');

            return response()->json([
                'disponibles' => $itemsDisponibles,
                'escasos' => $itemsEscasos,
                'agotados' => $itemsAgotados,
                'resumen' => [
                    // 'total_items' => $allItems->count(),
                    'total_items' => $productos->count(),
                    'disponibles' => $itemsDisponibles->count(),
                    'escasos' => $itemsEscasos->count(),
                    'agotados' => $itemsAgotados->count()
                ]
            ]);
        } catch (\Throwable $th) {
            Log::error('Error validating cart items', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Error al validar los items del carrito. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    // public function validateProduct(Request $request)
    // {
    //     try {
    //         $sucursaleId = 1; // Hardcodeado por ahora, pero en el futuro deberia obtenerse de $request->sucursale_id
    //         $productoId = $request->producto_id;
    //         $cantidadSolicitada = $request->quantity ?? 1;

    //         Log::info('Validating single product', [
    //             'sucursale_id' => $sucursaleId,
    //             'product_id' => $productoId,
    //             'quantity' => $cantidadSolicitada
    //         ]);

    //         // Buscar el producto
    //         $producto = Producto::find($productoId);

    //         if (!$producto) {
    //             return response()->json([
    //                 'error' => 'Producto no encontrado'
    //             ], 404);
    //         }

    //         // Check if product has stock relations (limited stock) or not (infinite stock)
    //         $isInfiniteStock = $producto->unfilteredSucursale->isEmpty();
            
    //         if ($isInfiniteStock) {
    //             // Infinite stock product
    //             $stockSucursal = null;
    //             $stockDisponible = "INFINITO";
    //         } else {
    //             // Limited stock product
    //             $stockSucursal = $producto->unfilteredSucursale->firstWhere('pivot.sucursale_id', $sucursaleId);
    //             $stockDisponible = $stockSucursal ? $stockSucursal->pivot->cantidad : 0;
    //         }

    //         $estado = $this->determinarEstado($stockDisponible, $cantidadSolicitada);

    //         return response()->json([
    //             'id' => $producto->id,
    //             'nombre' => $producto->nombre,
    //             'detalle' => $producto->detalle,
    //             'precio' => $producto->precio,
    //             'imagen' => asset('imagenes/productos/' . $producto->imagen),
    //             'sucursale_id' => $sucursaleId,
    //             'stock_disponible' => $stockDisponible,
    //             'cantidad_solicitada' => $cantidadSolicitada,
    //             'estado' => $estado,
    //             'max_permitido' => $stockDisponible === "INFINITO" ? "INFINITO" : $stockDisponible,
    //         ]);

    //     } catch (\Throwable $th) {
    //         Log::error('Error validating single product', [
    //             'error' => $th->getMessage(),
    //             'trace' => $th->getTraceAsString()
    //         ]);
    //         return response()->json([
    //             'error' => 'Error al validar el producto. Por favor, intente nuevamente.'
    //         ], 500);
    //     }
    // }

    protected function determinarEstado($stockDisponible, $cantidadSolicitada)
    {
        if ($stockDisponible === "INFINITO") {
            return 'disponible';
        }
        
        if ($stockDisponible <= 0) {
            return 'agotado';
        }
        
        if ($stockDisponible >= $cantidadSolicitada) {
            return 'disponible';
        }
        
        return 'escaso';
    }
}
