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
                // (object)[
                //     'id' => 1,
                //     'nombre' => 'Licuado de Maracuya',
                //     'descripcion' => 'Licuado frutal (incluye leche)',
                //     'precio' => 17.00,
                //     'imagen' => 'https://imgs.search.brave.com/Qfi-c-huarpu_eQVDaAbpgHCo6Zy1J8Kaa5wLYybgYo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMxLm1pbmhhdmlk/YS5jb20uYnIvcmVj/aXBlcy81My9lOC8y/Yy9jMC9zbW9vdGhp/ZS1kZS1tYXJhY3Vq/YS1hbXBfaGVyby0x/LmpwZw',
                //     'cantidad' =>  2
                // ],
                // (object)[
                //     'id' => 2,
                //     'nombre' => 'Sandwich de Pollo a la Plancha (integral)',
                //     'descripcion' => 'Sandwich de pechuga de pollo y verduras',
                //     'precio' => 20.50,
                //     'imagen' => 'https://imgs.search.brave.com/IV9dHgQWK5Sw3TkmrL5PaAt8Nm3qkhTjxDitXy1F2yI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90b3Rh/c3RlLmNvbS93cC1j/b250ZW50L3VwbG9h/ZHMvMjAyMi8wNi9z/YW5kd2ljaC1iYXNl/LXJlY2lwZS5qcGVn',
                //     'cantidad' => 3
                // ],
                // (object)[
                //     'id' => 3,
                //     'nombre' => 'Ecobolsa Delight Clara',
                //     'descripcion' => 'Bolsa reutilizable linea delight (tono claro)',
                //     'precio' => 25.00,
                //     'imagen' => 'https://imgs.search.brave.com/B6USg04HGTirSXeb2VylLdx0Uqyyb8r_iblnTyrRhPw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NTFZWXZvbW16S0wu/anBn',
                //     'cantidad' => 1
                // ],
                // (object)[
                //     'id' => 4,
                //     'nombre' => 'PLAN MENSUAL DESAYUNO ',
                //     'descripcion' => 'desayuno compuesto por una bebida fria/caliente y su acompañamiento',
                //     'precio' => 320.00,
                //     // 'imagen' => '/imagenes/delight/default-bg-1.png',
                //     'cantidad' => 1 
                // ],  
                // (object)[
                //     'id' => 5,
                //     'nombre' => 'PLAN MENSUAL CENA - ALMUERZO',
                //     'descripcion' => 'PLAN 20 DIAS DE CONSUMO',
                //     'precio' => 359.00,
                //     // 'imagen' => '/imagenes/delight/default-bg-1.png',
                //     'cantidad' => 1
                // ],
                
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

    public function validateCarrito(Request $request)
    {
        try {
            $sucursaleId = 1; // Hardcodeado por ahora, pero deberia obtenerse de $request->sucursale_id
            $itemsCarrito = collect($request->items);
            
            // Separa productos de planes
            $idsProductos = $itemsCarrito->where('isPlan', false)->pluck('id');
            $idsPlanes = $itemsCarrito->where('isPlan', true)->pluck('id');

            Log::info('Validating cart items', [
                'sucursale_id' => $sucursaleId,
                'product_ids' => $idsProductos,
                'plan_ids' => $idsPlanes,
                'cart_items' => $itemsCarrito->toArray()
            ]);

            // Procesa productos regulares
            $productos = collect(); // Initialize as empty collection
            
            if ($idsProductos->isNotEmpty()) {
                $productos = Producto::whereIn('id', $idsProductos)
                    ->with(['sucursale' => function($query) use ($sucursaleId) {
                        $query->where('sucursale_id', $sucursaleId);
                    }])
                    ->get()
                    ->map(function ($producto) use ($itemsCarrito, $sucursaleId) {
                        $cartItem = $itemsCarrito->firstWhere('id', $producto->id);
                        $stockSucursal = $producto->sucursale->firstWhere('pivot.sucursale_id', $sucursaleId);

                        Log::debug('Processing product', [
                            'product_id' => $producto->id,
                            'cart_item' => $cartItem,
                            'stock_sucursal' => $stockSucursal ? [
                                'sucursale_id' => $stockSucursal->id,
                                'pivot' => $stockSucursal->pivot
                            ] : null
                        ]);
                        
                        $stockDisponible = $stockSucursal ? $stockSucursal->pivot->cantidad : 0;
                        $cantidadSolicitada = $cartItem['quantity'] ?? 0;
                        
                        $estado = $this->determinarEstado($stockDisponible, $cantidadSolicitada);
                        
                        return [
                            'id' => $producto->id,
                            'nombre' => $producto->nombre,
                            'detalle' => $producto->detalle,
                            'precio' => $producto->precio,
                            'imagen' => $producto->imagen,
                            'sucursale_id' => $sucursaleId,
                            'stock_disponible' => $stockDisponible,
                            'cantidad_solicitada' => $cantidadSolicitada,
                            'estado' => $estado,
                            'max_permitido' => min($stockDisponible, $stockSucursal->pivot->max ?? 10),
                            'isPlan' => false // Mark as regular product
                        ];
                    });
            }

            // Procesa Planes
            $planes = collect(); // Initialize as empty collection
            
            if ($idsPlanes->isNotEmpty()) {
                $planes = Producto::whereIn('id', $idsPlanes)
                    ->get()
                    ->map(function ($plan) use ($itemsCarrito) {
                        $cartItem = $itemsCarrito->firstWhere('id', $plan->id);
                        
                        Log::debug('Processing plan', [
                            'plan_id' => $plan->id,
                            'cart_item' => $cartItem
                        ]);
                        
                        return [
                            'id' => $plan->id,
                            'nombre' => $plan->nombre,
                            'detalle' => $plan->detalle,
                            'precio' => $plan->precio,
                            'imagen' => null,
                            'sucursale_id' => null,
                            'stock_disponible' => null,
                            'cantidad_solicitada' => $cartItem['quantity'] ?? 0,
                            'estado' => 'disponible',
                            'max_permitido' => 12,
                            'isPlan' => true
                        ];
                    });
            }

            // Combine products and plans - both are now regular Collections
            $allItems = $productos->merge($planes);

            // Categorize items by status
            $itemsDisponibles = $allItems->filter(fn($p) => $p['estado'] === 'disponible');
            $itemsEscasos = $allItems->filter(fn($p) => $p['estado'] === 'escaso');
            $itemsAgotados = $allItems->filter(fn($p) => $p['estado'] === 'agotado');

            return response()->json([
                'disponibles' => $itemsDisponibles,
                'escasos' => $itemsEscasos,
                'agotados' => $itemsAgotados,
                'resumen' => [
                    'total_items' => $allItems->count(),
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

    protected function determinarEstado($stockDisponible, $cantidadSolicitada)
    {
        if ($stockDisponible <= 0) {
            return 'agotado';
        }
        
        if ($stockDisponible >= $cantidadSolicitada) {
            return 'disponible';
        }
        
        return 'escaso';
    }
}
