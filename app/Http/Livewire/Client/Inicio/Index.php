<?php

namespace App\Http\Livewire\Client\Inicio;

use App\Models\Venta;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Subcategoria;
use App\Models\Historial_venta;

class Index extends Component
{
    public function toast(){
        $this->dispatchBrowserEvent('toast',[
            'toastid'=>'notification-1',
            
        ]);
        dd($this);
    }
    public function render()
    {
        if (session()->missing('productos')) {
            $productos=Producto::all();
            session(['productos' => $productos]);
        }
       
        if (session()->missing('subcategorias')) {
            $subcategorias=Subcategoria::all();
            session(['subcategorias' => $subcategorias]);
        }
       /* if (session()->missing('masvendidos')) {
            $masvendidos=Historial_venta::with('historial_venta_producto')->get();
            dd($masvendidos);
            session(['masvendidos' => $masvendidos]);
        }*/
        
        return view('livewire.client.inicio.index')
        ->extends('client.master')
        ->section('content');
    }
}
