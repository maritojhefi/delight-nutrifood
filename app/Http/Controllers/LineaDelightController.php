<?php

namespace App\Http\Controllers;

use App\Models\Plane;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class LineaDelightController extends Controller
{
    public function index()
    {

        $productos = Producto::select('productos.*')
            ->leftjoin('subcategorias', 'subcategorias.id', 'productos.subcategoria_id')
            ->leftjoin('categorias', 'categorias.id', 'subcategorias.categoria_id')
            ->where('productos.estado', 'activo')
            ->where('categorias.nombre','!=', 'ECO-TIENDA')
            ->get();


        $subcategorias = Subcategoria::has('productos')->where('categoria_id', '!=',1)->inRandomOrder()->get();
        $enDescuento = $productos->where('descuento', '!=', null)->where('descuento', '!=', 0)->shuffle();
        $conMasPuntos = $productos->where('puntos', '!=', null)->where('puntos', '!=', 0)->shuffle()->take(10);
        return view('client.lineadelight.index', compact('subcategorias', 'enDescuento', 'conMasPuntos','productos'));
        
    }
    public function categoriaPlanes()
    {
        $subcategoria=Subcategoria::find(1);
        return view('client.lineadelight.planes',compact('subcategoria'));
    }
}
