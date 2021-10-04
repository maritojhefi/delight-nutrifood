<?php

namespace App\Http\Livewire\Admin;

use DNS1D;
use DNS2D;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Stock_producto;
use Illuminate\Support\Facades\DB;

class StockProductos extends Component
{
    public $sucursal;
    public $search;
    public $prodlisto;
    public $cantidad,$fecha_venc;
    public $stock;
   
    protected $rules = [
        'cantidad' => 'required|integer',
        'fecha_venc' => 'required|date',
        
        
    ];
 
    public function seleccionar(Producto $prod)
    {
        $this->prodlisto=$prod;
        $registro = DB::table('producto_sucursale')
        ->where('producto_id',$this->prodlisto->id)
        ->where('sucursale_id',$this->sucursal)
        ->get();

        $this->stock=$registro->pluck('cantidad')->sum();
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
        DB::beginTransaction();
        $sucursal=Sucursale::find($this->sucursal);
        $sucursal->productos()->attach($this->prodlisto->id);
        
        $registro = DB::table('producto_sucursale')->where('producto_id',$this->prodlisto->id)->where('sucursale_id',$this->sucursal)->get()->last();
        
        DB::table('producto_sucursale')
        ->where('id', $registro->id)
        ->increment('cantidad',$this->cantidad);  

        DB::table('producto_sucursale')
        ->where('id', $registro->id)
        ->update(['fecha_venc'=>$this->fecha_venc]); 
       DB::commit();
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se agregaron ".$this->cantidad." productos de ".$this->prodlisto->nombre
        ]);
        $this->reset(['prodlisto','cantidad','fecha_venc']);
       
    }
    public function render()
    {
      
        $sucursales=Sucursale::all();
        $productos=Producto::where('codigoBarra',$this->search)->orWhere('nombre','LIKE','%'.$this->search.'%')->take(3)->get();
        
        return view('livewire.admin.stock-productos',compact('sucursales','productos'))
        ->extends('admin.master')
        ->section('content');
    }
}
