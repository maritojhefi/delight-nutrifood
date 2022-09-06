<?php

namespace App\Http\Livewire\Admin\Caja;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\Saldo;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Historial_venta;

class CajaDiaria extends Component
{
    public $entrada ,$estadoCaja, $lista, $resumen, $sucursalSeleccionada, $cajaactiva, $ventasHoy,$saldosHoy,$reporteGeneral=true;
    
    public function cambiarReporte()
    {
        $this->reporteGeneral==true?$this->reporteGeneral=false:$this->reporteGeneral=true;
    }
    public function alterarCaja()
    {
        if($this->cajaactiva->estado=="abierto")
        {
            $this->cajaactiva->estado="cerrado";
            $this->estadoCaja=false;
        }
        else
        {
            $this->cajaactiva->estado="abierto";
           $this->estadoCaja=true;
        }
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"La caja de esta sucursal ahora se encuentra ".$this->cajaactiva->estado
        ]);
        
        
        $this->cajaactiva->save();
    }
    public function submit()
    {
        $this->validate([
            'entrada'=>'required|numeric'
        ]);
        Caja::create([
            'estado' => 'abierto',
            'entrada' => $this->entrada,
            'sucursale_id'=>$this->sucursalSeleccionada
        ]);
        $this->reset('entrada');
        $this->buscarCaja();
    }

    public function resetSucursal(){
        $this->reset('sucursalSeleccionada');
    }
    public function buscarCaja()
    {
        $this->cajaactiva=Caja::where('sucursale_id',$this->sucursalSeleccionada)->whereDate('created_at',Carbon::today())->first();
       
        if($this->cajaactiva!=null)
        { 
            if($this->cajaactiva->estado=='abierto')
            {
                $this->estadoCaja=true;
            }
            else if($this->cajaactiva->estado=='cerrado')
            {
                $this->estadoCaja=false;
            }
            $coleccion=collect();
            $personalizado=collect();
            $ventas=Historial_venta::where('caja_id',$this->cajaactiva->id)->get();
            $saldos=Saldo::where('caja_id',$this->cajaactiva->id)->get();
            $this->saldosHoy=$saldos;
            $this->ventasHoy=$ventas;
             foreach($ventas as $list)
                 {
                    if($list->productos!=null)
                    {
                       foreach($list->productos as $lista)
                       {
                        $coleccion->prepend(['nombre'=>$lista->nombre,'cantidad'=>$lista->pivot->cantidad]);     
                       }
                    }
                 }
            $agrupado=$coleccion->groupBy('nombre');
            $total=0;
            foreach($agrupado as $nombre=>$cantidad)
            {
                $producto=Producto::where('nombre',$nombre)->first();
                $subtotal=($producto->descuento!=null?$producto->descuento:$producto->precio)*$cantidad->sum('cantidad');
                    $personalizado->prepend([
                        'nombre'=>$nombre,
                        'cantidad'=>$cantidad->sum('cantidad'),
                        'precio'=>$producto->descuento!=null?$producto->descuento:$producto->precio,
                        'subtotal'=>$subtotal,
                        'id'=>$producto->id]);
                $total=$total+$subtotal;
            }
            $this->resumen=$total;
            $ordenado=$personalizado->sortByDesc('cantidad');
            $this->lista= $ordenado;
            
        }
        else
        {
            $this->estadoCaja=null; 
        }
    }
    public function render()
    {
        
        $sucursales=Sucursale::all();
        return view('livewire.admin.caja.caja-diaria',compact('sucursales'))
        ->extends('admin.master')
        ->section('content');
    }
}
