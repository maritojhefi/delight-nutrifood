<?php

namespace App\Http\Controllers;

use App\Models\Almuerzo;
use App\Models\Categoria;
use App\Models\GaleriaFotos;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
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
       $producto=Producto::find($id);
       $nombrearray=Str::of($producto->nombre)->explode(' ');
       //dd($nombrearray);
       return view('client.productos.detalleproducto',compact('producto','nombrearray'));

    }
    public function menusemanal()
    {
        $subcategorias=Subcategoria::has('productos')->inRandomOrder()->get();
        $almuerzos=Almuerzo::all();
        $galeria=GaleriaFotos::inRandomOrder()->all();
        return view('client.productos.menusemanal',compact('galeria','almuerzos','subcategorias'));
    }
}
