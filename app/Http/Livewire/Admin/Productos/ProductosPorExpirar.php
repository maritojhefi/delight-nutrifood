<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductosPorExpirar extends Component
{
    public function render()
    {
        //$productos=Producto::has('sucursale')->has('producto_sucursale')->orderBy('fecha_venc','desc')->paginate(10);

        $productos=DB::table('producto_sucursale')
        ->select('producto_sucursale.*','productos.*')
        ->leftjoin('productos','productos.id','producto_sucursale.producto_id')->orderBy('fecha_venc','asc')->get();


        return view('livewire.admin.productos.productos-por-expirar',compact('productos'))
        ->extends('admin.master')
        ->section('content');
    }
}
