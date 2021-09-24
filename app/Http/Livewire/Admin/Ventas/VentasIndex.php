<?php

namespace App\Http\Livewire\Admin\Ventas;

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

    protected $rules = [
        'sucursal' => 'required|integer',
        
    ];

    public function crear(){
        $this->validate();
        Venta::create([
            'usuario_id'=>auth()->user()->id,
            'sucursale_id'=>$this->sucursal,
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
        
        try {
            $venta->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Venta eliminada"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"No se puede eliminar una venta que tiene productos agregados"
            ]);
        }
    }
    
    public function render()
    {
        $ventas=Venta::all();
        $sucursales=Sucursale::pluck('id','nombre');
        $productos=Producto::where('codigoBarra',$this->search)->orWhere('nombre','LIKE','%'.$this->search.'%')->take(5)->get();

        return view('livewire.admin.ventas.ventas-index',compact('ventas','sucursales','productos'))
        ->extends('admin.master')
        ->section('content');
    }
}
