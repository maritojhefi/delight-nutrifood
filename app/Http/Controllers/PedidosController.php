<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use function React\Promise\all;

class PedidosController extends Controller
{
    public function index()
    {
        $productos=Producto::all();
        $categorias=Categoria::all();
        $subcategorias=Subcategoria::all();
        return view('pedidos.inicio.index',compact('productos','subcategorias','categorias'));
    }
}
