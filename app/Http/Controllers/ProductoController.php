<?php

namespace App\Http\Controllers;

use App\Models\Almuerzo;
use App\Models\Categoria;
use App\Models\GaleriaFotos;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ProductoController extends Controller
{
    public function lineadelightsubcategoria($id)
    {
        $subcategoria=Subcategoria::find($id);
       
        return view('client.productos.delight-subcategoria',compact('subcategoria'));
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
            ->reject(fn ($p) => $p->id == $id || $p->estado != 'activo')  // Dual filter
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
        $subcategoria=Subcategoria::find($id);
       
       return view('client.productos.detallesubcategoria',compact('subcategoria'));
    }
    public function index(){   
        // // 1. Corregir el typo.
        // if (session()->missing('productos')) {
        //     $productos=Producto::select('productos.*')
        //     ->leftjoin('subcategorias','subcategorias.id','productos.subcategoria_id')
        //     ->leftjoin('categorias','categorias.id','subcategorias.categoria_id')
        //     ->where('productos.estado','activo')
        //     ->where('categorias.nombre','ECO-TIENDA')
        //     ->get(); 
        //     // 2. Guardar los datos en la sesión para futuros accesos
        //     session(['productos' => $productos]);
        // } else {
        //     // 3. Si la sesión ya tiene los productos, obtenerlos de ahí
        //     $productos = session('productos');
        // }

        try {
                    $productos=Producto::select('productos.*')
            ->leftjoin('subcategorias','subcategorias.id','productos.subcategoria_id')
            ->leftjoin('categorias','categorias.id','subcategorias.categoria_id')
            ->where('productos.estado','activo')
            ->where('categorias.nombre','ECO-TIENDA')
            ->get(); 

        // foreach ($productos as $producto) {
        //     if ($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0) {
        //         $producto->tiene_stock = false;
        //     } else {
        //         $producto->tiene_stock = true;
        //     }
        // }

        $subcategorias=Subcategoria::has('productos')->where('categoria_id',1)->orderBy('nombre')->get();
        $masVendidos = $productos->sortByDesc('cantidad_vendida')->take(10);
        $masRecientes = $productos->sortByDesc('created_at')->take(10);
        $enDescuento=$productos->where('descuento','!=',null)->where('descuento','!=',0)->shuffle();
        $conMasPuntos=$productos->where('puntos','!=',null)->where('puntos','!=',0)->shuffle()->take(10);
        $suplementosStark = $productos->where('subcategoria_id', 24);
        return view('client.productos.index',compact('subcategorias','masVendidos','masRecientes','enDescuento','conMasPuntos','suplementosStark'));
        } catch (\Throwable $th) {
            Log::error('Error al obtener los productos de la Eco Tienda', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }

    }
    public function subcategorias() {
        $subcategorias = Subcategoria::has('productos')->where('categoria_id', 1)
                                    ->orderBy('nombre')
                                    ->get();
        return view('client.productos.subcategorias', data: compact('subcategorias'));
    }
    public function detalleproducto($id){
        $producto=Producto::findOrFail($id);
        $nombrearray=Str::of($producto->nombre)->explode(delimiter: ' ');

        $similares = $producto->subcategoria->productos
            ->reject(fn ($p) => $p->id == $id || $p->estado != 'activo')  // Dual filter
            ->shuffle()
            ->take(5)
            ->map(function ($p) {
                $p->imagen = $p->imagen
                    ? asset('imagenes/productos/' . $p->imagen)
                    : asset('imagenes/delight/21.jpeg');
                $p->url_detalle = route('delight.detalleproducto', $p->id);
                return $p;
            });
        //dd($nombrearray);
        return view('client.productos.delight-producto',compact('producto','nombrearray','similares'));
    }
    public function menusemanal()
    {
        $subcategorias=Subcategoria::has('productos')->inRandomOrder()->get();
        $almuerzos=Almuerzo::all();
        $galeria=GaleriaFotos::inRandomOrder()->get();
        return view('client.productos.menusemanal',compact('galeria','almuerzos','subcategorias'));
    }
    public function productosSubcategoria($id)
    {
        try {
            $productos = Producto::select('productos.*')
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
                $producto ->url_detalle = route('delight.detalleproducto', $producto->id);
            }

            return response()->json($productos, 200);
        } catch (\Throwable $th) {
            // Log::error(`Error al obtener los productos de la categoria con id: ` + $id, [
            //     'error' => $th->getMessage(),
            //     'trace' => $th->getTraceAsString()
            // ]);

            return response()->json([
                'error' => 'Error al obtener los productos de la categoria. Por favor, intente nuevamente.'
            ], 500);
        }
    }
    public function checkProductStock($id) {
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
            return response()->json($producto, 200); // Removed the array brackets
        } catch (\Throwable $th) {
            Log::error(`Error el producto con id: ` + $id, [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
}
