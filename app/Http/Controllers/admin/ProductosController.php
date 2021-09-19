<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function resumen(){
        return view('admin.inicio.resumen');
    }
    public function listar(){
        return view('admin.productos.index');
    }
    public function crear(){
        return view('admin.productos.create');
    }
    public function categoria(){
        return view('admin.productos.category');
    }
    public function subcategoria(){
        return view('admin.productos.subcategory');
    }
}
