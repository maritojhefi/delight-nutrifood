<?php

namespace App\Http\Controllers;

use App\Models\Adicionale;
use App\Models\Producto;
use App\Models\User;
use Error;
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

    public function validateCarrito(Request $request)
    {
        try {
            $sucursaleId = 1; // Hardcodeado por ahora, pero deberia obtenerse de $request->sucursale_id
            $itemsCarrito = collect($request->items);
            
            $idsProductos = $itemsCarrito->pluck('id');

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

                        // Revisar si el producto tiene relaciones de stock (stock limitado)
                        // o no (stock infinito)
                        $isInfiniteStock = $producto->unfilteredSucursale->isEmpty();
                        
                        if ($isInfiniteStock) {
                            // Stock infinito del producto
                            $stockSucursal = null;
                            $stockDisponible = "INFINITO";
                        } else {
                            // Stock limitado del producto
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

                        $adicionalesItemCarrito = $cartItem['adicionales'] ?? [];

                        $infoAdicionales = [];

                        $adicionalesLimitados = false;

                        foreach ($adicionalesItemCarrito as $adicionalCarrito) {
                            $adicional = Adicionale::findOrFail($adicionalCarrito);
                            if ($adicional->contable == 1 && $cantidadSolicitada > $adicional->cantidad ) {
                                $adicionalesLimitados = true;
                                // throw new Error(`Se solicitaron mas unidades del adicional `. $adicional->nombre . `que las unidades existentes.
                                // Unidades solicitadas: ` . $adicionalCarrito->quantity. ` Unidades disponibles: ` . $adicional->cantidad);
                            }
                            $newEntry = [
                                "id" => $adicional->id,
                                "nombre" => ucfirst($adicional->nombre),
                                "quantity" => $cantidadSolicitada,
                                "id_grupo" => "MockupID",
                            ];

                            $infoAdicionales[] = $newEntry;
                        }

                        $estado = $this->determinarEstado($stockDisponible, $cantidadSolicitada);
                        
                        return [
                            'id' => $producto->id,
                            'nombre' => $producto->nombre,
                            'detalle' => $producto->detalle,
                            'adicionales' => $infoAdicionales,
                            'tiene_descuento' => ($producto->precio == $producto-> precioReal()) ? false : true,
                            'precio_original' => $producto->precio,
                            'precio' => $producto->precioReal(),
                            'imagen' => $producto->pathAttachment(),
                            'sucursale_id' => $sucursaleId,
                            'stock_disponible' => $stockDisponible,
                            'cantidad_solicitada' => $cantidadSolicitada,
                            'estado' => $estado,
                            'max_permitido' => $stockDisponible === "INFINITO" ? "INFINITO" : $stockDisponible,
                            'adicionalesLimitados' => $adicionalesLimitados
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
