<?php

namespace App\Http\Livewire\Admin;

use DNS1D;
use DNS2D;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;

class StockProductos extends Component
{
    public $sucursal;
    public $search;
    public $prodlisto;
    public function seleccionar(Producto $prod)
    {
        $this->prodlisto=$prod;
    }
    public function render()
    {
      
        $sucursales=Sucursale::all();
        $productos=Producto::where('codigoBarra',$this->search)->orWhere('nombre','LIKE','%'.$this->search.'%')->take(5)->get();
        
        return view('livewire.admin.stock-productos',compact('sucursales','productos'))
        ->extends('admin.master')
        ->section('content');
    }
}
