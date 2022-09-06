<?php

namespace App\Http\Livewire\Admin\Caja;

use Charts;
use App\Models\Caja;
use App\Models\Saldo;
use Livewire\Component;
use App\Models\Producto;
use Chartisan\PHP\Chartisan;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Historial_venta;
use App\Imports\ProductosImport;
use Maatwebsite\Excel\Facades\Excel;

class Reportes extends Component
{
    use WithPagination;
   
    protected $paginationTheme = 'bootstrap';
    
    public $ventasHoy,$saldosHoy, $resumen, $lista, $cajaactiva,$reporteGeneral=true;

    public function resetCaja()
    {
        $this->reset('cajaactiva');
    }
    public function cambiarReporte()
    {
        $this->reporteGeneral==true?$this->reporteGeneral=false:$this->reporteGeneral=true;
    }
    public function buscarCaja(Caja $caja)
    {
            $this->cajaactiva=$caja;
            $coleccion=collect();
            $personalizado=collect();
            $ventas=Historial_venta::where('caja_id',$caja->id)->get();
            $saldos=Saldo::where('caja_id',$caja->id)->get();
            $this->ventasHoy=$ventas;
            $this->saldosHoy=$saldos;
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
    public function render()
    {
       $cajas=Caja::orderBy('created_at','DESC')->paginate(10);
        return view('livewire.admin.caja.reportes',compact('cajas'))
        ->extends('admin.master')
        ->section('content');
    }
}
