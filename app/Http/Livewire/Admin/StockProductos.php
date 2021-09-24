<?php

namespace App\Http\Livewire\Admin;

use DNS1D;
use DNS2D;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Stock_producto;

class StockProductos extends Component
{
    public $sucursal;
    public $search;
    public $prodlisto;
    public $cantidad,$fecha_venc;
   
    protected $rules = [
        'cantidad' => 'required|integer',
        'fecha_venc' => 'required|date',
        
        
    ];
 
    public function seleccionar(Producto $prod)
    {
        $this->prodlisto=$prod;
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
     for ($i=0; $i <$this->cantidad ; $i++) { 
        Stock_producto::create([
        'fecha_venc' => $this->fecha_venc,
        'fecha_entrada'=>Carbon::now(),
        'usuario_id' => auth()->user()->id,
        'sucursale_id'=>$this->sucursal,
        'producto_id'=>$this->prodlisto->id
        
    ]);
 }
        
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se agregaron ".$this->cantidad." productos de ".$this->prodlisto->nombre
        ]);
       
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
