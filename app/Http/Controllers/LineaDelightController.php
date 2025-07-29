<?php

namespace App\Http\Controllers;

use App\Models\Plane;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Log;

class LineaDelightController extends Controller
{
    public function index()
    {
        try {
        $productos = Producto::select('productos.*')
            ->join('subcategorias', 'subcategorias.id', 'productos.subcategoria_id')
            ->join('categorias', 'categorias.id', 'subcategorias.categoria_id')
            ->where('productos.estado', 'activo')
            ->whereIn('categorias.nombre', ['Cocina', 'Panaderia/Reposteria'])
            ->get();        
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error validating cart items', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }

        $subcategorias = Subcategoria::has('productos')
            ->whereIn('categoria_id', [2,3])
            ->orderBy('subcategorias.nombre')
            ->get();

        $enDescuento = $productos->where('descuento', '!=', null)
            ->where('descuento', '!=', 0)
            ->shuffle();

        $conMasPuntos = $productos->where('puntos', '!=', null)
            ->where('puntos', '!=', 0)
            ->shuffle()
            ->take(10);

        return view('client.lineadelight.index', compact('subcategorias', 'enDescuento', 'conMasPuntos', 'productos'));
    }
    public function categoriaPlanes()
    {
        $subcategoria=Subcategoria::find(1);

        //dd($subcategoria->productos());
        return view('client.lineadelight.planes',compact('subcategoria'));
    }
}
