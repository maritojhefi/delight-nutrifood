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
            $productos=Producto::where('estado','activo')->get();
            session(['productos' => $productos]);
        }
        $subcategorias=Subcategoria::inRandomOrder()->get();
        $enDescuento=$productos->where('descuento','!=',null)->where('descuento','!=',0)->shuffle();
        //dd($enDescuento);
        $conMasPuntos=$productos->where('puntos','!=',null)->where('puntos','!=',0)->shuffle();
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
        $almuerzos=Almuerzo::all();
        $galeria=GaleriaFotos::all();
        return view('client.productos.menusemanal',compact('galeria','almuerzos'));
    }
}
