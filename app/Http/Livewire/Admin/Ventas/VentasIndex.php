<?php

namespace App\Http\Livewire\Admin\Ventas;

use App\Models\User;
use App\Models\Venta;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Helpers\CreateList;
use Illuminate\Support\Facades\DB;

class VentasIndex extends Component
{
    public $sucursal;
    public $cuenta;
    public $search;
    public $itemsCuenta;
    public $listacuenta;
    public $user;
    public $cliente;

    protected $rules = [
        'sucursal' => 'required|integer',
        
    ];

    public function crear(){
        $this->validate();
        Venta::create([
            'usuario_id'=>auth()->user()->id,
            'sucursale_id'=>$this->sucursal,
            'cliente_id'=>$this->cliente,
        ]);
        $this->reset(['user','cliente','sucursal']);
    }

    public function seleccionarcliente($id, $name)
    {
        $this->cliente=$id;
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Usuario ".$name." seleccionado"
        ]);
    }
    public function adicionar(Producto $producto){
        $cuenta = Venta::find($this->cuenta->id);
        $cuenta->productos()->attach($producto->id); 
        
        $resultado=CreateList::crearlista($cuenta);
        $this->listacuenta=$resultado[0];
        DB::table('ventas')
        ->where('id', $cuenta->id)
        ->update(['total' => $resultado[1]]);
        $this->cuenta->total=$resultado[1];
        $this->itemsCuenta=$resultado[2];
        
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se agrego ".$producto->nombre." a esta venta"
        ]);

        
    }

    public function eliminaruno(Producto $producto){
        

        $cuenta = Venta::find($this->cuenta->id);
        $registro = DB::table('producto_venta')->where('producto_id',$producto->id)->where('venta_id',$cuenta->id)->latest()->first();
        
        $cuenta->productos()->newPivotStatementForId($producto->id)->whereId($registro->id)->delete();
        
        $resultado=CreateList::crearlista($cuenta); 
        $this->listacuenta=$resultado[0];
        DB::table('ventas')
        ->where('id', $cuenta->id)
        ->update(['total' => $resultado[1]]);
        $this->cuenta->total=$resultado[1];
        $this->itemsCuenta=$resultado[2];

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se elimino 1 ".$producto->nombre." de esta venta"
        ]);
    }

    public function eliminarproducto(Producto $producto){
        $cuenta = Venta::find($this->cuenta->id);
        $cuenta->productos()->detach($producto->id);
        
        $resultado=CreateList::crearlista($cuenta);
        $this->listacuenta=$resultado[0];
        DB::table('ventas')
        ->where('id', $cuenta->id)
        ->update(['total' => $resultado[1]]);
        $this->cuenta->total=$resultado[1];
        $this->itemsCuenta=$resultado[2];

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se elimino a ".$producto->nombre." de esta venta"
        ]);
    }
    
    public function seleccionar(Venta $venta){
        $this->cuenta=$venta;
        $listafiltrada=$venta->productos->pluck('nombre');
        
        $resultado=CreateList::crearlista($venta); 
        $this->listacuenta=$resultado[0];
        DB::table('ventas')
        ->where('id', $venta->id)
        ->update(['total' => $resultado[1]]);
        $this->cuenta->total=$resultado[1];
        $this->itemsCuenta=$resultado[2];
    }

    public function eliminar(Venta $venta)
    {
            $venta->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Venta eliminada"
            ]);
            if($this->cuenta!=null)
            {
                if($venta->id==$this->cuenta->id)
                {
                    $this->reset();
                }
            }
          
            
       
    }
    
    public function render()
    {
        $ventas=Venta::orderBy('created_at','desc')->get();
        $usuarios=collect();
        $sucursales=Sucursale::pluck('id','nombre');
        $productos=Producto::where('codigoBarra',$this->search)->orWhere('nombre','LIKE','%'.$this->search.'%')->take(5)->get();
        if($this->user!=null)
        {
            $usuarios=User::where('name','LIKE','%'.$this->user.'%')->orWhere('email','LIKE','%'.$this->user.'%')->take(3)->get();

        }
        return view('livewire.admin.ventas.ventas-index',compact('ventas','sucursales','productos','usuarios'))
        ->extends('admin.master')
        ->section('content');
    }
}
