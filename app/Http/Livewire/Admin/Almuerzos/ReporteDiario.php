<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use Livewire\Component;
use App\Models\Almuerzo;
use App\Exports\UsersPlanesExport;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteDiario extends Component
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
            $this->estadoBuscador = "ESTADO";
            $this->estadoColor="primary";
        } 
        else if ($this->estadoBuscador == 'ESTADO')
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
    public function cambiarEstadoPlato($variable)
    {
        switch ($variable) {
            case 'ejecutivo_estado':
                $this->menuHoy->ejecutivo_estado = $this->menuHoy->ejecutivo_estado == true ? false : true;
                $this->menuHoy->save();
                break;
            case 'dieta_estado':
                $this->menuHoy->dieta_estado = $this->menuHoy->dieta_estado == true ? false : true;
                $this->menuHoy->save();
                break;
            case 'vegetariano_estado':
                $this->menuHoy->vegetariano_estado = $this->menuHoy->vegetariano_estado == true ? false : true;
                $this->menuHoy->save();
                break;
            case 'carbohidrato_1_estado':
                $this->menuHoy->carbohidrato_1_estado = $this->menuHoy->carbohidrato_1_estado == true ? false : true;
                $this->menuHoy->save();
                break;
            case 'carbohidrato_2_estado':
                $this->menuHoy->carbohidrato_2_estado = $this->menuHoy->carbohidrato_2_estado == true ? false : true;
                $this->menuHoy->save();
                break;
            case 'carbohidrato_3_estado':
                $this->menuHoy->carbohidrato_3_estado = $this->menuHoy->carbohidrato_3_estado == true ? false : true;
                $this->menuHoy->save();
                break;

            default:
                # code...
                break;
        }
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se actualizo correctamente!"
        ]);
    }
    public function finalizarTodos()
    {
        DB::table('plane_user')->whereDate('start', $this->fechaSeleccionada)->where('estado', Plane::ESTADOPENDIENTE)->update(['estado' => Plane::ESTADOFINALIZADO, 'color' => Plane::COLORFINALIZADO]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se finalizaron todos los registros para el dia seleccionado!"
        ]);
    }
    public function cambiarDisponibilidad()
    {
        $fecha = date('Y-m-d');
        $resultado = $this->saber_dia($fecha);
        $this->menuHoy = Almuerzo::where('dia', $resultado)->first();
    }
    public function cambiarEstado($id)
    {
        DB::table('plane_user')->where('id', $id)->update(['estado' => Plane::ESTADOFINALIZADO, 'color' => Plane::COLORFINALIZADO]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se despacho este pedido!"
        ]);
    }
    public function exportarexcel()
    {
        $data = $this->reporte;
        // Excel::create('metricas-visitas-y-consultas-propiedades-experto-', function ($excel) use ($data) {
        //     $excel->sheet('Hoja1', function ($sheet) use ($data) {
        //         $sheet->fromArray($data, null, 'A1', true);
        //     });
        // }, 'UTF-8')->export('xlsx');
        return Excel::download(new UsersPlanesExport($this->fechaSeleccionada), 'reporte-diario-' . $this->fechaSeleccionada . '.xlsx');
    }


    public function cambiarAPendiente($id)
    {
        DB::table('plane_user')->where('id', $id)->update(['estado' => Plane::ESTADOPENDIENTE, 'color' => Plane::COLORPENDIENTE]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => "Este pedido vuelve a estar pendiente"
        ]);
    }
    public function render()
    {
        $usuarios = User::has('planes')->get();
        $coleccion = collect();
        $fecha = date('Y-m-d');
        if ($this->cambioFecha == false) {
            $this->fechaSeleccionada = Carbon::now()->format('Y-m-d');
        }

        $pens = DB::table('plane_user')->select('plane_user.*', 'users.name', 'planes.editable', 'planes.nombre')
            ->leftjoin('users', 'users.id', 'plane_user.user_id')
            ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
            ->whereDate('plane_user.start', $this->fechaSeleccionada)
            //->where('planes.editable',1)
            ->get();            //dd($pens);
        foreach ($pens as $lista) {
            //dd($lista);
            $detalle = $lista->detalle;
            if ($detalle != null) {
                $det = collect(json_decode($detalle, true));
                $sopaCustom = '';
                $det['SOPA'] == '' ? $sopaCustom = 'Sin Sopa' : $sopaCustom = $det['SOPA'];

                $saberDia = WhatsappAPIHelper::saber_dia($this->fechaSeleccionada);
                $menu = Almuerzo::where('dia', $saberDia)->first();
                $tipoSegundo = '';
                $tipoCarbo = '';
                if ($det['PLATO'] == $menu->ejecutivo) $tipoSegundo = 'EJECUTIVO';
                if ($det['PLATO'] == $menu->dieta) $tipoSegundo = 'DIETA';
                if ($det['PLATO'] == $menu->vegetariano) $tipoSegundo = 'VEGGIE';


                $coleccion->push([
                    'ID' => $lista->id,
                    'NOMBRE' => $lista->name,
                    'ENSALADA' => $det['ENSALADA'],
                    'SOPA' => $det['SOPA'],
                    'PLATO' => $tipoSegundo,
                    'CARBOHIDRATO' => $det['CARBOHIDRATO'],
                    'JUGO' => $det['JUGO'],
                    'ENVIO' => $det['ENVIO'],
                    'EMPAQUE' => $det['EMPAQUE'],
                    'ESTADO' => $lista->estado,
                    'PLAN' => $lista->nombre
                ]);
            } else {
                $coleccion->push([
                    'ID' => $lista->id,
                    'NOMBRE' => $lista->name,
                    'ENSALADA' => '',
                    'SOPA' => '',
                    'PLATO' => '',
                    'CARBOHIDRATO' => '',
                    'JUGO' => '',
                    'ENVIO' => '',
                    'EMPAQUE' => '',
                    'ESTADO' => $lista->estado,
                    'PLAN' => $lista->nombre
                ]);
            }
        }

        $this->reporte = $coleccion;
        $total = collect();
        $total->push([

            'sopa' => $coleccion->pluck('SOPA')->countBy(),
            
            'plato' => $coleccion->pluck('PLATO')->countBy(),
            'carbohidrato' => $coleccion->pluck('CARBOHIDRATO')->countBy(),
            'ensalada'=>$coleccion->pluck('ENSALADA')->countBy(),
            'jugo'=>$coleccion->pluck('JUGO')->countBy(),

            'empaque' => $coleccion->pluck('EMPAQUE')->countBy(),
            'envio' => $coleccion->pluck('ENVIO')->countBy()
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
           // dd($coleccion);

            

           


            
        }
        //dd($coleccion);
        return view('livewire.admin.almuerzos.reporte-diario', compact('usuarios', 'coleccion', 'total'))
            ->extends('admin.master')
            ->section('content');
    }
}
