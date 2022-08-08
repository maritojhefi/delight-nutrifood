<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Helpers\GlobalHelper;
use App\Exports\UsersPlanesExport;
use App\Models\Plane;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CocinaDespachePlanes extends Component
{
    public $menuHoy, $reporte;
    public $fechaSeleccionada, $cambioFecha = false;
    public $search;
    public $estadoBuscador = "NOMBRE",$estadoColor="success";

    public function cambiarEstadoBuscador()
    {
        if ($this->estadoBuscador == 'NOMBRE')
        {
            $this->estadoBuscador = "SEGUNDO";
            $this->estadoColor="info";
        } 
        else if ($this->estadoBuscador == 'SEGUNDO')
        {
            $this->estadoBuscador = "CARBO";
            $this->estadoColor="warning";
        } 
        else if ($this->estadoBuscador == 'CARBO')
        {
            $this->estadoBuscador = "NOMBRE";
            $this->estadoColor="success";
        } 
       
        //dd($this->estadoBuscador);
    }
    public function saber_dia($nombredia)
    {
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }
    public function cambioDeFecha()
    {
        $this->cambioFecha = true;
    }
   
    public function confirmarDespacho($id)
    {
        //dd($id);
        DB::table('plane_user')->where('id',$id)->update(['cocina'=>Plane::COCINADESPACHADO]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se despacho este plan!"
        ]);
    }
    public function render()
    {

        $usuarios = User::has('planes')->get();
        
        $fecha = date('Y-m-d');
        if ($this->cambioFecha == false) {
            $this->fechaSeleccionada = Carbon::now()->format('Y-m-d');
        }

        $pens = DB::table('plane_user')->select('plane_user.*', 'users.name', 'planes.editable', 'planes.nombre')
            ->leftjoin('users', 'users.id', 'plane_user.user_id')
            ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
            ->whereDate('plane_user.start', $this->fechaSeleccionada)
            ->where('plane_user.title','!=','feriado')
            ->where('plane_user.detalle','!=',null)
            ->whereIn('plane_user.estado',[Plane::ESTADOFINALIZADO,Plane::ESTADOPENDIENTE])
            ->get();            //dd($pens);
        $coleccion=GlobalHelper::armarColeccionReporteDiarioVista($pens,$this->fechaSeleccionada);
        $coleccionEspera=$coleccion->where('COCINA','espera');
        $coleccionDespachado=$coleccion->where('COCINA','despachado');
        $this->reporte = $coleccion;
        $totalEspera = collect();
        $totalEspera->push([

            'sopa' => $coleccionEspera->pluck('SOPA')->countBy(),
            'plato' => $coleccionEspera->pluck('PLATO')->countBy(),
            'carbohidrato' => $coleccionEspera->pluck('CARBOHIDRATO')->countBy(),
            // 'ensalada'=>$coleccion->pluck('ENSALADA')->countBy(),
            // 'jugo'=>$coleccion->pluck('JUGO')->countBy(),

            'empaque' => $coleccionEspera->pluck('EMPAQUE')->countBy(),
            'envio' => $coleccionEspera->pluck('ENVIO')->countBy()
        ]);
        $totalDespachado = collect();
        $totalDespachado->push([

            'sopa' => $coleccionDespachado->pluck('SOPA')->countBy(),
            'plato' => $coleccionDespachado->pluck('PLATO')->countBy(),
            'carbohidrato' => $coleccionDespachado->pluck('CARBOHIDRATO')->countBy(),
            // 'ensalada'=>$coleccion->pluck('ENSALADA')->countBy(),
            // 'jugo'=>$coleccion->pluck('JUGO')->countBy(),

            'empaque' => $coleccionDespachado->pluck('EMPAQUE')->countBy(),
            'envio' => $coleccionDespachado->pluck('ENVIO')->countBy()
        ]);
        //dd($total);
        if ($this->search != null || $this->search != '') {
            $search = $this->search;
            switch ($this->estadoBuscador) {
                case 'NOMBRE':
                    $coleccion = collect($coleccion)->filter(function ($item) use ($search) {
                        // replace stristr with your choice of matching function
                        
                        return false !== stristr($item['NOMBRE'], $search);
                    });
                    //dd($coleccion);
                    break;
                case 'SEGUNDO':
                    $coleccion = $coleccion->filter(function ($item) use ($search) {
                        // replace stristr with your choice of matching function
        
                        return false !== stristr($item['PLATO'], $search);
                    });
                    break;
                case 'CARBO':
                    $coleccion = $coleccion->filter(function ($item) use ($search) {
                        // replace stristr with your choice of matching function
        
                        return false !== stristr($item['CARBOHIDRATO'], $search);
                    });
                    break;
                case 'ESTADO':
                    $coleccion = $coleccion->filter(function ($item) use ($search) {
                        // replace stristr with your choice of matching function
        
                        return false !== stristr($item['ESTADO'], $search);
                    });
                    break;
                default:
                    # code...
                    break;
            }
        }
        //dd($coleccion);
        
        return view('livewire.admin.almuerzos.cocina-despache-planes',compact('usuarios', 'coleccion', 'totalEspera','totalDespachado'))
            ->extends('admin.master')
            ->section('content');
    }
}
