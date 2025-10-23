<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Http\Resources\Producto\ProductoDetalle;
use App\Http\Resources\Producto\ProductoListado;
use App\Http\Resources\ProductResource;
use App\Models\Adicionale;
use App\Models\Almuerzo;
use App\Models\Producto;
use Illuminate\Support\Str;
use App\Models\GaleriaFotos;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class ProductoController extends Controller
{
    public function lineadelightsubcategoria($id)
    {
        $subcategoria = Subcategoria::find($id);

        return view('client.productos.delight-subcategoria', compact('subcategoria'));
    }
    public function lineadelightproducto($id)
    {
        $producto = Producto::publicoTienda()->findOrFail($id);
        $stockDisponible = $producto->contable ? $producto->stockTotal() > 0 : true;

        // Procesar la imagen del producto y su url
        $producto->imagen = $producto->pathAttachment();
        $producto->url_detalle = route('delight.detalleproducto', $producto->id);
        $adicionales = $producto->subcategoria->adicionales;

        // Obtener productos similares excluyendo el producto ya obtenido
        $similares = $producto->subcategoria->productos
            // Omitir el producto actual entre los similares
            ->reject(fn($p) => $p->id == $id || $p->estado != 'activo')
            ->shuffle()
            ->take(5)
            ->map(function ($p) {
                $p->imagen = 
                asset($p->pathAttachment());
                $p->url_detalle = route('delight.detalleproducto', $p->id);
                return $p;
            });

        return view('client.productos.delight-producto', compact('producto', 'stockDisponible', 'similares', 'adicionales'));
    }
    public function detallesubcategoria($id)
    {
        $subcategoria = Subcategoria::find($id);

        return view('client.productos.detallesubcategoria', compact('subcategoria'));
    }
    public function index()
    {
        $productos = Producto::publicoTienda()->select('productos.*')
            ->leftjoin('subcategorias', 'subcategorias.id', 'productos.subcategoria_id')
            ->leftjoin('categorias', 'categorias.id', 'subcategorias.categoria_id')
            ->where('categorias.nombre', 'ECO-TIENDA')
            ->with(['tag'])
            ->get();

        $productos = $productos->map(function ($producto) {
            // $producto->tiene_stock = !($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0);
            $producto->tiene_stock = $producto->contable ? $producto->stockTotal() > 0 : true;
            return $producto;
        });

        $subcategorias = Subcategoria::has('productos')->where('categoria_id', 1)->orderBy('nombre')->get();
        $masVendidos = $productos->sortByDesc('cantidad_vendida')->take(10);
        $masRecientes = $productos->sortByDesc('created_at')->take(10);
        $enDescuento = $productos->where('descuento', '!=', null)->where('descuento', '!=', 0)->shuffle();
        $conMasPuntos = $productos->where('puntos', '!=', null)->where('puntos', '!=', 0)->shuffle()->take(10);
        $suplementosStark = $productos->where('subcategoria_id', 24);

        return view('client.productos.index', compact('subcategorias', 'masVendidos', 'masRecientes', 'enDescuento', 'conMasPuntos', 'suplementosStark'));
    }
    public function subcategorias()
    {
        // Obtener solo subcategorias con productos disponibles y visibles a clientes
        $subcategorias = Subcategoria::tieneProductosDisponibles()
        // Pertenecientes a la categoria ECO-TIENDA
            ->where('categoria_id', 1)
            ->orderBy('nombre')
            ->get();
            
        return view('client.productos.subcategorias', data: compact('subcategorias'));
    }
    public function detalleproducto($id)
    {
        try {
        $producto = Producto::publicoTienda()->findOrFail($id);
        $stockDisponible = $producto->contable ? $producto->stockTotal() > 0 : true;
        // Procesar la imagen del producto y su url
        $producto->imagen = $producto->pathAttachment();
        $producto->url_detalle = route('delight.detalleproducto', $producto->id);
        $adicionales = $producto->subcategoria->adicionales;

        // Obtener productos similares excluyendo el producto ya obtenido
        $similares = $producto->subcategoria->productos
            // Omitir el producto actual entre los similares
            ->reject(fn($p) => $p->id == $id || $p->estado != 'activo')
            ->shuffle()
            ->take(5)
            ->map(function ($p) {
                $p->imagen = 
                asset($p->pathAttachment());
                $p->url_detalle = route('delight.detalleproducto', $p->id);
                return $p;
            });

        return view('client.productos.delight-producto', compact('producto', 'stockDisponible', 'similares', 'adicionales'));
        } catch (\Throwable $th) {
            Log::error("Error al renderizar pagina: ", [$th]);
        }
        
    }
    public function menusemanal()
    {
        $subcategorias=Subcategoria::has('productos')->inRandomOrder()->get();
        $almuerzos=Almuerzo::all();
        $galeria=GaleriaFotos::inRandomOrder()->get();
        return view('client.productos.menusemanal',compact('galeria','almuerzos','subcategorias'));
    }
    public function buscarProductos(Request $request, $tipo = null, $query)
    {
        try {

            $filtro = [];
            
            // Filtro manual para la categoria a utilizarse al momento de realizar la busqueda.
            switch (strtolower($tipo)) {
                case 'lineadelight':
                    $filtro = ['Cocina','Panaderia/Reposteria'];
                    break;
                case 'eco-tienda':
                    $filtro = ['ECO-TIENDA'];
                    break;
                case (null || ''):
                    $filtro = [];
                    break;
                default:
                    $errorMessage = "El tipo utilizado: " . $tipo . " no dispone de categorias asociadas, por favor, verifique el valor establecido para el buscador";
                    throw new Exception($errorMessage);
            }

            function normalizarTexto($texto)
            {
                $texto = strtolower($texto);
                $texto = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'ñ'],
                    ['a', 'e', 'i', 'o', 'u', 'n'],
                    $texto
                );
                return $texto;
            }

            $query = normalizarTexto(trim($query));
            $letras = str_split(preg_replace('/\s+/', '', $query));

            $productos = Cache::get('productos', collect());

            if ($productos->isEmpty()) {
                // app(ProductoObserver::class)->cachearProductos();
                GlobalHelper::cachearProductos();
                $productos = Cache::get('productos', collect());
            }

            // Filtrar productos de acuerdo al valor del $tipo
            if (!empty($filtro)) {
                $productos = $productos->filter(function ($producto) use ($filtro) {
                    // Revisamos existencia de las relaciones y si el nombre de la categoria existe dentro del filtro
                    return $producto->subcategoria 
                        && $producto->subcategoria->categoria 
                        && in_array($producto->subcategoria->categoria->nombre, $filtro);
                });
            }

            $productosTransformados = $productos->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    // 'url_imagen' => $producto->pathAttachment(),
                    'url_imagen' => $producto->pathImagen ? 
                        $producto->pathImagen : 
                        asset(GlobalHelper::getValorAtributoSetting('busqueda_default')),
                    'url' => route('delight.detalleproducto', $producto->id),
                    'tiene_descuento' => ($producto->precio == $producto->precioReal()) ? false : true,
                    'precioOriginal' => $producto->precio,
                    'precioFinal' => $producto->precioReal(),
                    'subcategoria' => $producto->subcategoria->nombre ?? '',
                    'tags' => $producto->tag,
                    'data-filter-name' => strtolower(
                        $producto->nombre . ' ' .
                        ($producto->subcategoria->nombre ?? '') . ' ' .
                        $producto->tag->pluck('nombre')->join(' ')
                    ),
                ];
            });

            $productosConPeso = collect($productosTransformados)->map(function ($producto) use ($query, $letras) 
            {
                $peso = 0;
                
                // Convertir a Array en caso de trabajar con un modelo Eloquent
                $productoArray = is_array($producto) ? $producto : $producto->toArray();
                
                $texto = normalizarTexto($productoArray['data-filter-name'] ?? '');
                $nombre = normalizarTexto($productoArray['nombre'] ?? '');
                $subcategoria = normalizarTexto($productoArray['subcategoria'] ?? '');

                if (str_contains($texto, $query)) {
                    $peso += 40;
                }

                if (str_contains($nombre, $query)) {
                    $peso += 80;
                }

                if (str_contains($subcategoria, $query)) {
                    $peso += 20;
                }

                $distancia = levenshtein($query, $nombre);
                if ($distancia <= 3) {
                    $peso += max(0, 20 - ($distancia * 5));
                }

                $palabras = explode(' ', $texto);
                foreach ($palabras as $palabra) {
                    $dist = levenshtein($query, $palabra);
                    if ($dist <= 3) {
                        $peso += max(0, 10 - ($dist * 3));
                    }
                    if (str_contains($palabra, $query) || str_contains($query, $palabra)) {
                        $peso += 5;
                    }
                }

                foreach ($letras as $letra) {
                    if (str_contains($texto, $letra)) {
                        $peso += 1;
                    }
                    if (str_contains($nombre, $letra)) {
                        $peso += 10;
                    }
                }

                return [
                    'producto' => $productoArray,
                    'peso' => $peso,
                ];
            });

            $resultado = $productosConPeso
                ->filter(fn($item) => $item['peso'] > (strlen($query) * 0))
                ->sortByDesc('peso')
                ->values()
                ->take(10)
                ->map(function ($item) {
                    // Trabajando con arrays
                    return array_merge($item['producto'], ['peso' => $item['peso']]);
                });

            return response()->json($resultado);

        } catch (\Throwable $th) {
            Log::error("Error al buscar productos: " . $th->getMessage());

            return response()->json([
                'error' => 'Error al buscar productos',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function productosSubcategoria($id)
    {
        try {
            $productos = Producto::publicoTienda()
                ->with('tags')
                ->where('subcategoria_id', $id)
                ->orderByRaw('CASE 
                            WHEN descuento IS NOT NULL AND descuento > 0 AND descuento < precio THEN 0 
                            ELSE 1 
                        END')
                ->get();

            return ProductoListado::collection($productos);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al obtener los productos de la categoria. Por favor, intente nuevamente.'
            ], 500);
        }
    }
    public function getProductoTag($id) {
        try {
            $productos = Producto::publicoTienda()
                ->with('tags')
                ->whereHas('tags', function ($query) use ($id) {
                    $query->where('tags.id', $id);
                })
                ->get();

            return ProductoListado::collection($productos);
            
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al obtener los productos del tag seleccionado. Por favor, intente nuevamente.'
            ], 500);
        }
    }
    public function checkProductStock($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto == null) {
            return response()->json(["error" => "No existe un producto con el id proporcionado"], 404);
        }

        // Verificar si el producto es contable
        if (!$producto->contable) {
            // Al no disponer, se asume que tiene productos infinitos
            return response()->json(["stock" => -1, "unlimited" => true], 200);
        } else {
            // Retornamos la cantidad de stock disponible
            return response()->json(["stock" => $producto->stockTotal(), "unlimited" => false], 200);
            // Retornar el stock disponbile para su su
        }

        // // Verificar si el producto no dispone de una sucursal
        // if ($producto->unfilteredSucursale->isEmpty()) {
        //     // Al no disponer, se asume que tiene productos infinitos
        //     return response()->json(["stock" => -1, "unlimited" => true], 200);
        // } else {
        //     // Retornamos la cantidad de stock disponible
        //     return response()->json(["stock" => $producto->stock_actual, "unlimited" => false], 200);
        // }
    }
    public function validarProductoAdicionales(Request $request) {
        try {
            // $producto_id = $request->producto_ID;
            $adicionales_ids = $request->adicionales_ids;
            $cantidad = $request->cantidad;

            // Log::debug('Producto ID: ', [$producto_id]);
            // Log::debug('IDs de Adicionales: ', $adicionales_ids);
            // Log::debug('Cantidad solicitada: ', [$cantidad]);
            // $producto = Producto::findOrFail($producto_id);
            
            $adicionalesObservados = $this->obtenerAdicionalesAgotados($adicionales_ids,$cantidad);


            if (!empty($adicionalesObservados)) {
                $agotados = collect($adicionalesObservados['agotados']);
                $limitados = collect($adicionalesObservados['limitados']);
                $cantidadMaxima = $adicionalesObservados['cantidadMaxima'];

                if (!empty($agotados) || !empty($limitados)) {
                    return response()->json([
                        'success' => false,
                        'messageAgotados' => "Los siguientes adicionales se encuentran agotados: {$agotados->pluck('nombre')->implode(', ')}",
                        'messageLimitados' => "Stock disponible: {$limitados->map(fn($item) => "{$item['nombre']} ({$item['stock']})")->implode(', ')}.
                         Puedes actualizar tu orden presionando el boton de abajo.",
                         // 'messageLimitados' => "El stock para: {$limitados->pluck('nombre')->implode(', ')}; es bajo, puedes actualizar tu orden presionando el boton de abajo.",
                        'idsAdicionalesAgotados' => $agotados->pluck('id')->all(),
                        'idsAdicionalesLimitados' => $limitados->pluck('id')->all(),
                        'cantidadMaxima' => $cantidadMaxima
                    ], 422);
                }
            }


            
            return response()->json([
                'success' => true,
                'mensaje' => 'Éxito en el chequeo.',
            ], 200);

        } catch (\Throwable $th) {
            Log::error('Error al validar adicionales', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al validar adicionales.'
            ], 500);
        }
    }
    public function getProduct($id)
    {
        try {
            $producto = Producto::with('subcategoria.adicionales')->findOrFail($id);
            return new ProductResource($producto);
        } catch (\Throwable $th) {
            Log::error("Error al solicitar producto: ", [$th]);
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
    }
    public function getProductoDetalle($id) {
        try {
            $producto = Producto::publicoTienda()->with('subcategoria.adicionales')->findOrFail($id);
            return new ProductoDetalle($producto);
        } catch (\Throwable $th) {
            Log::error("Error al solicitar producto: ", [$th]);
            return response()->json(['error' => 'Error al solicitar el producto'], 500);
        }
    }
    private function obtenerAdicionalesAgotados($adicionales_ids, $cantidadSolicitada) {
        $agotados = [];
        $limitados = [];
        $cantidadMaxima = PHP_FLOAT_MAX;

        foreach ($adicionales_ids as $adicionalId) {
            $adicional = Adicionale::find($adicionalId);
            
            if (!$adicional || ($adicional->contable && $adicional->cantidad <= 0)) {
                $agotados[] = [
                    'id' => $adicionalId,
                    'nombre' => $adicional ? $adicional->nombre : "Item ID: {$adicionalId}",
                ];
            } else if ($adicional->contable && $adicional->cantidad < $cantidadSolicitada) {
                $limitados[] = [
                    'id' => $adicionalId,
                    'nombre' => $adicional ? $adicional->nombre : "Item ID: {$adicionalId}",
                    'stock' => $adicional->cantidad,
                ];

                if ($adicional->cantidad < $cantidadMaxima) {
                    $cantidadMaxima = $adicional->cantidad;
                }
            }
        }

        if ($cantidadMaxima == PHP_FLOAT_MAX) {
            $cantidadMaxima = null;
        }

        if (empty($agotados) && empty($limitados)) {
            return [];
        }

        return ["agotados" => $agotados, "limitados" => $limitados, "cantidadMaxima" => $cantidadMaxima];    
    }
}
