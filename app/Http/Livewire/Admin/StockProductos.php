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

    protected $queryString = ['search'];
   
    protected $rules = [
        'cantidad' => 'required|integer',
        'fecha_venc' => 'required|date',
        
        
    ];
 
    // public function mount($id=null,$sucursal=null)
    // {
    //     if($id || $sucursal)
    //     {
    //         $this->prodlisto = Producto::find($id);
    //         $this->sucursal = $sucursal;
    //         dd($this->sucursal);
    //     }
        
    // }
    public function seleccionar(Producto $prod)
    {
        $this->prodlisto=$prod;
        $registro = DB::table('producto_sucursale')
        ->where('producto_id',$this->prodlisto->id)
        ->where('sucursale_id',$this->sucursal)
        ->get();

        $this->stock=$registro->pluck('cantidad')->sum();
    }
    public function eliminarStock($id)
    {
        //dd($id);
        DB::table('producto_sucursale')->where('id',$id)->delete();
        $this->prodlisto=Producto::find($this->prodlisto->id);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se borro este registro de stock!"
        ]);
        // $this->dispatchBrowserEvent('cerrarModal',[
        //     'id'=>'modalEliminar'.$id
            
        // ]);
        
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
        ->update(['fecha_venc'=>$this->fecha_venc,'usuario_id'=>auth()->user()->id,'cantidad'=>$this->cantidad,'max'=>$this->cantidad]); 
        DB::table('productos')->where('id',$this->prodlisto->id)->update(['contable'=>1]);
        DB::commit();
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se agregaron ".$this->cantidad." productos de ".$this->prodlisto->nombre
        ]);
        $this->reset(['cantidad','fecha_venc']);
        $this->prodlisto=Producto::find($this->prodlisto->id);
       
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
