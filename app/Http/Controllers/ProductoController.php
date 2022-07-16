<?php

namespace App\Http\Controllers;

use App\Models\Almuerzo;
use App\Models\GaleriaFotos;
use App\Models\Producto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(){
        
        if (session()->missing('producstos')) {
            $productos=Producto::all();
            session(['productos' => $productos]);
        }
        
        return view('client.productos.index');
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
