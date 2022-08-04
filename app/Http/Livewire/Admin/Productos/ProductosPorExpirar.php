<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductosPorExpirar extends Component
{
    public $search,$seleccionado,$nuevoPrecio;
    public $cantidad,$fecha;
    public function seleccionar(Producto $producto)
    {
        $this->seleccionado=$producto;
        $this->nuevoPrecio=$this->seleccionado->descuento;
    }
    public function actualizarCantidad($id)
    {
        //dd($id);
        DB::table('producto_sucursale')->where('id',$id)->update(['cantidad'=>$this->cantidad]);
        $this->reset('cantidad');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se cambio la cantidad de stock correctamente"
        ]);
    }
    public function actualizarFecha($id)
    {
        //dd($id);
        DB::table('producto_sucursale')->where('id',$id)->update(['fecha_venc'=>$this->fecha]);
        $this->reset('fecha');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se cambio la fecha de vencimiento correctamente"
        ]);
    }
    public function eliminarStock($id)
    {
        DB::table('producto_sucursale')->where('id',$id)->delete();
        $this->reset('fecha','cantidad');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'warning',
            'message'=>"Se elimino este registro"
        ]);
    }
    public function cambiar()
    {
        $this->seleccionado->descuento=$this->nuevoPrecio;
        $this->seleccionado->save();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se cambio el precio de descuento correctamente"
        ]);
        $this->reset('nuevoPrecio');
    }
    public function render()
    {
        //$productos=Producto::has('sucursale')->has('producto_sucursale')->orderBy('fecha_venc','desc')->paginate(10);

        $productos=DB::table('producto_sucursale')
        ->select('producto_sucursale.*','producto_sucursale.id as folioStock','productos.*')
        ->leftjoin('productos','productos.id','producto_sucursale.producto_id')->orderBy('fecha_venc','asc')->get();
        if($this->search)
        {
            $search=$this->search;
            $productos = collect($productos)->filter(function ($item) use ($search) {
                // replace stristr with your choice of matching function
                
                return false !== stristr($item->nombre, $search);
            });
        }

        return view('livewire.admin.productos.productos-por-expirar',compact('productos'))
        ->extends('admin.master')
        ->section('content');
    }
}
