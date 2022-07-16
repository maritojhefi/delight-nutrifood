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
    public function index(){
        
        if (session()->missing('producstos')) {
            $productos=Producto::where('estado','activo')->get();
            session(['productos' => $productos]);
        }
        $subcategorias=Subcategoria::all();
        $enDescuento=$productos->where('descuento','!=',null);
        return view('client.productos.index',compact('subcategorias','enDescuento'));
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
