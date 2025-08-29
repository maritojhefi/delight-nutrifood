<?php

namespace App\Http\Controllers;

use App\Models\Almuerzo;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Str;
use App\Models\GaleriaFotos;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProductoController extends Controller
{
    public function lineadelightsubcategoria($id)
    {
        $subcategoria = Subcategoria::find($id);

        return view('client.productos.delight-subcategoria', compact('subcategoria'));
    }
    public function lineadelightproducto($id)
    {
        $producto = Producto::findOrFail($id);
        $nombrearray = Str::of($producto->nombre)->explode(' ');

        // Procesar la imagen del producto y su url
        $producto->imagen = $producto->imagen
            ? asset('imagenes/productos/' . $producto->imagen)
            : asset('imagenes/delight/default-bg-1.png');
        $producto->url_detalle = route('delight.detalleproducto', $producto->id);

        // Obtener productos similares excluyendo el producto ya obtenido
        $similares = $producto->subcategoria->productos
            ->reject(fn($p) => $p->id == $id || $p->estado != 'activo')  // Dual filter
            ->shuffle()
            ->take(5)
            ->map(function ($p) {
                $p->imagen = $p->imagen
                    ? asset('imagenes/productos/' . $p->imagen)
                    : asset('imagenes/delight/21.jpeg');
                $p->url_detalle = route('delight.detalleproducto', $p->id);
                return $p;
            });

        return view('client.productos.delight-producto', compact('producto', 'nombrearray', 'similares'));
    }
    public function detallesubcategoria($id)
    {
        $subcategoria = Subcategoria::find($id);

        return view('client.productos.detallesubcategoria', compact('subcategoria'));
    }
    public function index()
    {
        try {
            $productos = Producto::select('productos.*')
                ->leftjoin('subcategorias', 'subcategorias.id', 'productos.subcategoria_id')
                ->leftjoin('categorias', 'categorias.id', 'subcategorias.categoria_id')
                ->where('productos.estado', 'activo')
                ->where('categorias.nombre', 'ECO-TIENDA')
                ->with(['unfilteredSucursale', 'tag'])
                ->get();

            $productos = $productos->map(function ($producto) {
                $producto->tiene_stock = !($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0);
                return $producto;
            });

            $subcategorias = Subcategoria::has('productos')->where('categoria_id', 1)->orderBy('nombre')->get();
            $masVendidos = $productos->sortByDesc('cantidad_vendida')->take(10);
            $masRecientes = $productos->sortByDesc('created_at')->take(10);
            $enDescuento = $productos->where('descuento', '!=', null)->where('descuento', '!=', 0)->shuffle();
            $conMasPuntos = $productos->where('puntos', '!=', null)->where('puntos', '!=', 0)->shuffle()->take(10);
            $suplementosStark = $productos->where('subcategoria_id', 24);

            // Log::debug('Productos de la Eco Tienda en oferta obtenidos correctamente', [
            //     'productos' => $enDescuento->where('id')
            // ]);

            return view('client.productos.index', compact('subcategorias', 'masVendidos', 'masRecientes', 'enDescuento', 'conMasPuntos', 'suplementosStark'));
        } catch (\Throwable $th) {
            // Log::error('Error al obtener los productos de la Eco Tienda', [
            //     'error' => $th->getMessage(),
            //     'trace' => $th->getTraceAsString()
            // ]);
        }
    }
    public function subcategorias()
    {
        $subcategorias = Subcategoria::has('productos')->where('categoria_id', 1)
            ->orderBy('nombre')
            ->get();
        return view('client.productos.subcategorias', data: compact('subcategorias'));
    }
    public function detalleproducto($id)
    {
        $producto = Producto::findOrFail($id);
        $nombrearray = Str::of($producto->nombre)->explode(delimiter: ' ');

        $similares = $producto->subcategoria->productos
            ->reject(fn($p) => $p->id == $id || $p->estado != 'activo')  // Dual filter
            ->shuffle()
            ->take(5)
            ->map(function ($p) {
                $p->imagen = $p->pathAttachment();
                $p->url_detalle = route('delight.detalleproducto', $p->id);
                return $p;
            });
        //dd($nombrearray);
        return view('client.productos.delight-producto', compact('producto', 'nombrearray', 'similares'));
    }
    public function menusemanal()
    {
        $subcategorias=Subcategoria::has('productos')->inRandomOrder()->get();
        $almuerzos=Almuerzo::all();
        $galeria=GaleriaFotos::inRandomOrder()->get();
        return view('client.productos.menusemanal',compact('galeria','almuerzos','subcategorias'));
    }
    protected function cachearProductos()
    {
        try {
            $productos = Producto::where('estado', 'activo')
                ->with(['subcategoria', 'tag'])->get();
            // $productos = $query->get();

            $productosTransformados = $productos->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'url_imagen' => $producto->pathAttachment(),
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

            Cache::put('productos_cacheados', $productosTransformados, now()->addDays(1));
            
        } catch (\Throwable $th) {
            Log::error('Error al cachear los productos', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
    public function buscarProductos(Request $request, $tipo = null, $query)
    {
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
        $cacheKey = 'busqueda_' . $query;
        $letras = str_split(preg_replace('/\s+/', '', $query));

        Log::debug('Iniciando búsqueda de productos', ['query' => $query, 'cacheKey' => $cacheKey]);

        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $productos = Cache::get('productos_cacheados', collect());

        if (isset($productos)) {
            $this -> cachearProductos();
            $productos = Cache::get('productos_cacheados', collect());
        }

        Log::debug("The entire productos collection: ", $productos->toArray());

        $productosConPeso = collect($productos)->map(function ($producto) use ($query, $letras) 
        {
            $peso = 0;
            $texto = normalizarTexto($producto['data-filter-name'] ?? '');
            $nombre = normalizarTexto($producto['nombre'] ?? '');
            $subcategoria = normalizarTexto($producto['subcategoria'] ?? '');

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
                'producto' => $producto,
                'peso' => $peso,
            ];
        });

        $resultado = $productosConPeso
            ->filter(fn($item) => $item['peso'] > (strlen($query) * 0))
            ->sortByDesc('peso')
            ->values()
            ->take(10)
            ->map(function ($item) {
                return array_merge($item['producto'], ['peso' => $item['peso']]);
        });

        Cache::put($cacheKey, $resultado, now()->addDay());

        return response()->json($resultado);
    }
    public function productosSubcategoria($id)
    {
        try {
            $productos = Producto::select('productos.*')
                ->with(['unfilteredSucursale', 'tag'])
                ->where('subcategoria_id', $id)
                ->where('estado', 'activo')
                ->orderByRaw('CASE 
                            WHEN descuento IS NOT NULL AND descuento > 0 AND descuento < precio THEN 0 
                            ELSE 1 
                        END')
                ->orderBy('nombre')
                ->get();

            foreach ($productos as $producto) {
                if ($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0) {
                    $producto->tiene_stock = false;
                } else {
                    $producto->tiene_stock = true;
                }

                $producto->imagen = $producto->imagen ? asset('imagenes/productos/' . $producto->imagen) : asset('imagenes/delight/default-bg-1.png');
                $producto->url_detalle = route('delight.detalleproducto', $producto->id);
            }

            return response()->json($productos, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al obtener los productos de la categoria. Por favor, intente nuevamente.'
            ], 500);
        }
    }
    public function checkProductStock($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto == null) {
            return response()->json(["error" => "No existe un producto con el id proporcionado"], 404);
        }

        // Verificar si el producto no dispone de una sucursal
        if ($producto->unfilteredSucursale->isEmpty()) {
            // Al no disponer, se asume que tiene productos infinitos
            return response()->json(["stock" => -1, "unlimited" => true], 200);
        } else {
            // Retornamos la cantidad de stock disponible
            return response()->json(["stock" => $producto->stock_actual, "unlimited" => false], 200);
        }
    }
    public function getProduct($id) {
        try {
            $producto = Producto::findOrFail($id);
            return response()->json($producto, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
    }
}
