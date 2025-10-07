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
use App\Services\Ventas\Contracts\StockServiceInterface;
use Illuminate\Support\Collection;

class CarritoController extends Controller
{
    public function __construct(
        private StockServiceInterface $stockService
    ) {}

    public function index()
    {
        $user=User::find(auth()->user()->id);
        
        $venta_activa = $user->ventaActiva;

        return view('client.carrito.index',compact('user','venta_activa'));
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
            
    //         $idsProductos = $itemsCarrito->pluck('id');
            
    //         if ($idsProductos->isNotEmpty()) {
    //             $productos = Producto::whereIn('id', $idsProductos)->get()
    //             ->map(function ($producto) use ($itemsCarrito, $sucursaleId) {
    //                 $cartItem = $itemsCarrito->firstWhere('id', $producto->id);

    //                 // Stock principal
    //                 if (!$producto->contable) {
    //                     $stockDisponible = "INFINITO";
    //                 } else {
    //                     $stockDisponible = $this->stockService->obtenerStockTotal($producto, $sucursaleId);
    //                 }

    //                 $cantidadSolicitada = $cartItem['cantidad'] ?? 0;

    //                 // === Adicionales ===
    //                 $adicionalesCarrito = $cartItem['adicionales'] ?? [];

    //                 // 1) Flatten all group arrays
    //                 $adicionalesFlattened = collect($adicionalesCarrito)->flatten();

    //                 // 2) Count frequencies
    //                 $adicionalesContados = $adicionalesFlattened->countBy();

    //                 $infoAdicionales = [];
    //                 $adicionalesLimitados = false;

    //                 foreach ($adicionalesContados as $adicionalId => $cantidadAdicionalSolicitada) {
    //                     $adicional = Adicionale::find($adicionalId);

    //                     if ($adicional) {
    //                         if ($adicional->contable == 1 && $cantidadAdicionalSolicitada > $adicional->cantidad) {
    //                             $adicionalesLimitados = true;
    //                         }

    //                         $infoAdicionales[] = [
    //                             "id" => $adicional->id,
    //                             "nombre" => ucfirst($adicional->nombre),
    //                             "cantidad" => $cantidadAdicionalSolicitada,
    //                             "id_grupo" => null // optional if you don’t need groups anymore
    //                         ];
    //                     }
    //                 }

    //                 $estado = $this->determinarEstado($stockDisponible, $cantidadSolicitada);

    //                 return [
    //                     'id' => $producto->id,
    //                     'nombre' => $producto->nombre,
    //                     'detalle' => $producto->detalle,
    //                     'adicionales' => $infoAdicionales,
    //                     'tiene_descuento' => ($producto->precio == $producto->precioReal()) ? false : true,
    //                     'precio_original' => $producto->precio,
    //                     'precio' => $producto->precioReal(),
    //                     'imagen' => $producto->pathAttachment(),
    //                     'sucursale_id' => $sucursaleId,
    //                     'stock_disponible' => $stockDisponible,
    //                     'cantidad_solicitada' => $cantidadSolicitada,
    //                     'estado' => $estado,
    //                     'max_permitido' => $stockDisponible === "INFINITO" ? "INFINITO" : $stockDisponible,
    //                     'adicionalesLimitados' => $adicionalesLimitados,
    //                     'tipo' => $producto->subcategoria && $producto->subcategoria->adicionales->isNotEmpty()
    //                     ? "complejo"
    //                     : "simple",
    //                 ];
    //             });
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

    // public function sincronizarCarrito(Request $request) {
    //     try {
    //         // Obtener items en el carrito
    //         $itemsCarrito = collect($request->items);

    //         // Distintos items pueden pertenecer a un mismo producto
    //     } catch (\Throwable $th) {
    //         Log::error('Error al sincronizar carrito y producto_venta', [$th]);
    //         throw $th;
    //     }
    // }

    

/**
 * Improved version of validateCarrito function
 */
public function validateCarrito(Request $request)
{
    try {
        $sucursaleId = $request->input('sucursale_id', 1);
        $itemsCarrito = collect($request->items ?? []);
        
        // Early return if no items
        if ($itemsCarrito->isEmpty()) {
            return $this->buildEmptyCartResponse();
        }
        
        $idsProductos = $itemsCarrito->pluck('id')->filter()->unique();
        
        $productos = $this->processCartItems($idsProductos, $itemsCarrito, $sucursaleId);
        
        return $this->buildCartValidationResponse($productos);
        
    } catch (\Throwable $th) {
        Log::error('Error validating cart items', [
            'error' => $th->getMessage(),
            'trace' => $th->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
        return response()->json([
            'error' => 'Error al validar los items del carrito. Por favor, intente nuevamente.'
        ], 500);
    }
}

/**
 * Single item validation function
 */
public function validateCartItem(Request $request)
{
    try {
        $sucursaleId = $request->input('sucursale_id', 1);
        $itemData = $request->only(['id', 'cantidad', 'adicionales']);
        
        // Validate required fields
        if (!isset($itemData['id']) || !isset($itemData['cantidad'])) {
            return response()->json([
                'error' => 'ID del producto y cantidad son requeridos.'
            ], 422);
        }
        
        $producto = Producto::find($itemData['id']);
        
        if (!$producto) {
            return response()->json([
                'error' => 'Producto no encontrado.'
            ], 404);
        }
        
        $validatedItem = $this->processIndividualItem($producto, $itemData, $sucursaleId);
        
        return response()->json([
            'item' => $validatedItem,
            'valid' => $validatedItem['estado'] === 'disponible' && !$validatedItem['adicionalesLimitados']
        ]);
        
    } catch (\Throwable $th) {
        Log::error('Error validating cart item', [
            'error' => $th->getMessage(),
            'trace' => $th->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
        return response()->json([
            'error' => 'Error al validar el item. Por favor, intente nuevamente.'
        ], 500);
    }
}

/**
 * Process multiple cart items
 */
private function processCartItems(Collection $idsProductos, Collection $itemsCarrito, int $sucursaleId): Collection
{
    return Producto::whereIn('id', $idsProductos)
        ->with(['subcategoria.adicionales']) // Eager load relationships
        ->get()
        ->map(function ($producto) use ($itemsCarrito, $sucursaleId) {
            $cartItem = $itemsCarrito->firstWhere('id', $producto->id);
            return $this->processIndividualItem($producto, $cartItem, $sucursaleId);
        });
}

/**
 * Process a single cart item
 */
private function processIndividualItem($producto, array $itemData, int $sucursaleId): array
{
    $cantidadSolicitada = max(0, (int) ($itemData['cantidad'] ?? 0));
    
    // Calculate available stock
    $stockDisponible = $this->stockService->obtenerStockTotal($producto, $sucursaleId);
    
    // Process adicionales
    [$infoAdicionales, $adicionalesLimitados, $precioTotalAdicionales] = $this->processAdicionales(
        $itemData['adicionales'] ?? []
    );
    
    $estado = $this->determinarEstado($stockDisponible, $cantidadSolicitada);
    
    return [
        'id' => $producto->id,
        'nombre' => $producto->nombre,
        'detalle' => $producto->detalle,
        'adicionales' => $infoAdicionales,
        'costo_adicionales' => $precioTotalAdicionales,
        'tiene_descuento' => $producto->precio !== $producto->precioReal(),
        'precio_original' => $producto->precio,
        'precio' => $producto->precioReal(),
        'precio_final' => ($producto->precioReal() * $cantidadSolicitada) + $precioTotalAdicionales,
        'imagen' => $producto->pathAttachment(),
        'sucursale_id' => $sucursaleId,
        'stock_disponible' => $stockDisponible,
        'cantidad_solicitada' => $cantidadSolicitada,
        'estado' => $estado,
        'max_permitido' => $stockDisponible === "INFINITO" ? "INFINITO" : $stockDisponible,
        'stock_disponible' => $stockDisponible,
        'adicionalesLimitados' => $adicionalesLimitados,
        'tipo' => $this->determinarTipoProducto($producto),
    ];
}

/**
 * Process adicionales for a cart item
 */
private function processAdicionales(array $adicionalesCarrito): array
{
    if (empty($adicionalesCarrito)) {
        return [[], false, 0];
    }
    $precioTotal = 0;

    $adicionalesContados = collect($adicionalesCarrito)
        ->flatten()
        ->filter()
        ->countBy();
    
    $adicionales = [];
    if ($adicionalesContados->isNotEmpty()) {
        $adicionales = Adicionale::whereIn('id', $adicionalesContados->keys())->get()->keyBy('id');
    }
    
    $infoAdicionales = [];
    $adicionalesLimitados = false;
    
    foreach ($adicionalesCarrito as $groupIndex => $adicionalesGroup) {
        $infoAdicionales[$groupIndex] = [];
        
        $groupContados = collect($adicionalesGroup)
            ->filter()
            ->countBy();
        
        foreach ($groupContados as $adicionalId => $cantidadSolicitada) {
            $adicional = $adicionales->get($adicionalId);
            
            if ($adicional) {
                if ($adicional->contable && $cantidadSolicitada > $adicional->cantidad) {
                    $adicionalesLimitados = true;
                }

                $infoAdicionales[$groupIndex][] = [
                    'id' => $adicional->id,
                    'nombre' => ucfirst($adicional->nombre),
                    'cantidad' => $cantidadSolicitada,
                    'cantidad_disponible' => $adicional->contable ? $adicional->cantidad : 'INFINITO',
                    'limitado' => $adicional->contable && $cantidadSolicitada > $adicional->cantidad,
                ];

                $precioTotal += (float) $adicional->precio;
            }
        }
    }
    
    return [$infoAdicionales, $adicionalesLimitados, $precioTotal];
}

/**
 * Determine product type
 */
private function determinarTipoProducto($producto): string
{
    return ($producto->subcategoria && $producto->subcategoria->adicionales->isNotEmpty()) 
        ? 'complejo' 
        : 'simple';
}

/**
 * Build response for empty cart
 */
private function buildEmptyCartResponse(): \Illuminate\Http\JsonResponse
{
    return response()->json([
        'disponibles' => collect(),
        'escasos' => collect(),
        'agotados' => collect(),
        'resumen' => [
            'total_items' => 0,
            'disponibles' => 0,
            'escasos' => 0,
            'agotados' => 0
        ]
    ]);
}

/**
 * Build cart validation response
 */
private function buildCartValidationResponse(Collection $productos): \Illuminate\Http\JsonResponse
{
    $itemsDisponibles = $productos->filter(fn($p) => $p['estado'] === 'disponible');
    $itemsEscasos = $productos->filter(fn($p) => $p['estado'] === 'escaso');
    $itemsAgotados = $productos->filter(fn($p) => $p['estado'] === 'agotado');
    
    return response()->json([
        'disponibles' => $itemsDisponibles->values(),
        'escasos' => $itemsEscasos->values(),
        'agotados' => $itemsAgotados->values(),
        'resumen' => [
            'total_items' => $productos->count(),
            'disponibles' => $itemsDisponibles->count(),
            'escasos' => $itemsEscasos->count(),
            'agotados' => $itemsAgotados->count()
        ]
    ]);
}
    
    protected function determinarEstado($stockDisponible, $cantidadSolicitada)
    {
        if ($stockDisponible === PHP_INT_MAX) {
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
