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
        
        // Process main product image and URL
        $producto->imagen = $producto->imagen 
            ? asset('imagenes/productos/' . $producto->imagen) 
            : asset('imagenes/delight/default-bg-1.png');
        $producto->url_detalle = route('delight.detalleproducto', $producto->id);

        // Get similar products (excluding current product) and process them
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
        
        if (session()->missing('producstos')) {
            $productos=Producto::select('productos.*')
            ->leftjoin('subcategorias','subcategorias.id','productos.subcategoria_id')
            ->leftjoin('categorias','categorias.id','subcategorias.categoria_id')
            ->where('productos.estado','activo')
            ->where('categorias.nombre','ECO-TIENDA')
            ->get();
            session(['productos' => $productos]);
        }
        $subcategorias=Subcategoria::has('productos')->where('categoria_id',1)->inRandomOrder()->get();
        $enDescuento=$productos->where('descuento','!=',null)->where('descuento','!=',0)->shuffle();
        //dd($enDescuento);
        $conMasPuntos=$productos->where('puntos','!=',null)->where('puntos','!=',0)->shuffle()->take(10);
        return view('client.productos.index',compact('subcategorias','enDescuento','conMasPuntos'));
    }
    public function detalleproducto($id){
       $producto=Producto::findOrFail($id);
       $nombrearray=Str::of($producto->nombre)->explode(' ');
       //dd($nombrearray);
       return view('client.productos.detalleproducto',compact('producto','nombrearray'));
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
}
