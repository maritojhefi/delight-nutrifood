<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use Livewire\Component;
use App\Models\Almuerzo;
use App\Helpers\GlobalHelper;
use App\Exports\UsersPlanesExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CocinaDespachePlanes extends Component
{
    public $menuHoy;
    public $reporte;
    public $fechaSeleccionada, $cambioFecha = false;
    public $search;
    public $estadoBuscador = "NOMBRE", $estadoColor = "success";
    public $dieta_cant, $ejecutivo_cant, $carbohidrato_1_cant, $carbohidrato_2_cant, $carbohidrato_3_cant, $vegetariano_cant;
    public function cambiarCantidad($variable)
    {
        switch ($variable) {
            case 'dieta_cant':
                $cantidad = $this->dieta_cant;
                break;
            case 'vegetariano_cant':
                $cantidad = $this->vegetariano_cant;
                break;
            case 'ejecutivo_cant':
                $cantidad = $this->ejecutivo_cant;
                break;
            case 'carbohidrato_1_cant':
                $cantidad = $this->carbohidrato_1_cant;
                break;
            case 'carbohidrato_2_cant':
                $cantidad = $this->carbohidrato_2_cant;
                break;
            case 'carbohidrato_3_cant':
                $cantidad = $this->carbohidrato_3_cant;
                break;
            default:
                # code...
                break;
        }
        DB::table('almuerzos')->where('id', $this->menuHoy->id)->update([$variable => $cantidad]);
        GlobalHelper::actualizarCarbosDisponibilidad();
    }
    public function cambiarEstadoPlato($variable)
    {

        switch ($variable) {
            case 'ejecutivo_estado':
                $this->menuHoy->ejecutivo_estado = $this->menuHoy->ejecutivo_estado == true ? false : true;
                if (!$this->menuHoy->ejecutivo_estado) {
                    $this->menuHoy->ejecutivo_cant = 0;
                }
                $this->menuHoy->save();
                break;
            case 'dieta_estado':
                $this->menuHoy->dieta_estado = $this->menuHoy->dieta_estado == true ? false : true;
                if (!$this->menuHoy->dieta_estado) {
                    $this->menuHoy->dieta_cant = 0;
                }
                $this->menuHoy->save();
                break;
            case 'vegetariano_estado':
                $this->menuHoy->vegetariano_estado = $this->menuHoy->vegetariano_estado == true ? false : true;
                if (!$this->menuHoy->vegetariano_estado) {
                    $this->menuHoy->vegetariano_cant = 0;
                }
                $this->menuHoy->save();
                break;
            case 'carbohidrato_1_estado':
                $this->menuHoy->carbohidrato_1_estado = $this->menuHoy->carbohidrato_1_estado == true ? false : true;
                if (!$this->menuHoy->carbohidrato_1_estado) {
                    $this->menuHoy->carbohidrato_1_cant = 0;
                }
                $this->menuHoy->save();
                break;
            case 'carbohidrato_2_estado':
                $this->menuHoy->carbohidrato_2_estado = $this->menuHoy->carbohidrato_2_estado == true ? false : true;
                if (!$this->menuHoy->carbohidrato_2_estado) {
                    $this->menuHoy->carbohidrato_2_cant = 0;
                }
                $this->menuHoy->save();
                break;
            case 'carbohidrato_3_estado':
                $this->menuHoy->carbohidrato_3_estado = $this->menuHoy->carbohidrato_3_estado == true ? false : true;
                if (!$this->menuHoy->carbohidrato_3_estado) {
                    $this->menuHoy->carbohidrato_3_cant = 0;
                }
                $this->menuHoy->save();
                break;
            default:
                // Código adicional para casos no manejados
                break;
        }

        GlobalHelper::actualizarCarbosDisponibilidad();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se actualizo correctamente!"
        ]);
    }
    public function cambiarDisponibilidad()
    {

        $fecha = date('Y-m-d');
        $resultado = $this->saber_dia($fecha);
        $this->menuHoy = Almuerzo::withoutGlobalScope('diasActivos')->where('dia', $resultado)->first();
        // dd($this->menuHoy);
        $this->ejecutivo_cant = $this->menuHoy->ejecutivo_cant;
        $this->dieta_cant = $this->menuHoy->dieta_cant;
        $this->vegetariano_cant = $this->menuHoy->vegetariano_cant;
        $this->carbohidrato_1_cant = $this->menuHoy->carbohidrato_1_cant;
        $this->carbohidrato_2_cant = $this->menuHoy->carbohidrato_2_cant;
        $this->carbohidrato_3_cant = $this->menuHoy->carbohidrato_3_cant;
    }
    public function cambiarEstadoBuscador()
    {
        if ($this->estadoBuscador == 'NOMBRE') {
            $this->estadoBuscador = "SEGUNDO";
            $this->estadoColor = "info";
        } else if ($this->estadoBuscador == 'SEGUNDO') {
            $this->estadoBuscador = "CARBO";
            $this->estadoColor = "warning";
        } else if ($this->estadoBuscador == 'CARBO') {
            $this->estadoBuscador = "NOMBRE";
            $this->estadoColor = "success";
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
        DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINADESPACHADO]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se despacho este plan!"
        ]);
    }
    public function despacharSopa($id)
    {
        // dd($id);
        $registro = DB::table('plane_user')->where('id', $id)->first();
        if ($registro->cocina == Plane::COCINASOLOSEGUNDO) {
            $this->confirmarDespacho($id);
        } else {
            DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINASOLOSOPA]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Se despacho la sopa de este plan"
            ]);
        }
    }
    public function despacharSegundo($id)
    {
        $registro = DB::table('plane_user')->where('id', $id)->first();
        $detalle = json_decode($registro->detalle);
        if ($registro->cocina == Plane::COCINASOLOSOPA || $detalle->SOPA == '') {
            $this->confirmarDespacho($id);
        } else {
            DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINASOLOSEGUNDO]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Se despacho el segundo de este plan"
            ]);
        }
    }
    public function restablecerPlan($id)
    {

        DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINAESPERA]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se restablecio el plan, ahora se encuentra en espera"
        ]);
    }
    public function restarColecciones(Collection $collectionA, Collection $collectionB): Collection
    {
        $result = $collectionA->mapWithKeys(function ($value, $key) use ($collectionB) {
            $valueB = $collectionB->get($key, 0);
            return [$key => $value - $valueB];
        });

        return $result;
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
            ->where('plane_user.title', '!=', 'feriado')
            //->where('plane_user.detalle','!=',null)
            ->whereIn('plane_user.estado', [Plane::ESTADOFINALIZADO, Plane::ESTADOPENDIENTE, Plane::ESTADOPERMISO])
            ->get();            //dd($pens);
        // Suponiendo que estas funciones ya existen
        $coleccion = GlobalHelper::armarColeccionReporteDiarioVista($pens, $this->fechaSeleccionada);
        $coleccionEspera = $coleccion->whereIn('COCINA', ['espera', 'solo-sopa', 'solo-segundo']);
        $coleccionSopa = $coleccion->whereIn('COCINA', ['solo-sopa']);
        $coleccionSegundo = $coleccion->whereIn('COCINA', ['solo-segundo']);
        $coleccionDespachado = $coleccion->where('COCINA', 'despachado');
        $this->reporte = $coleccion;
        $totalEspera = collect();

        $conteoSopaEspera = $coleccionEspera->pluck('SOPA')->countBy();
        $conteoSopaSopa = $coleccionSopa->pluck('SOPA')->countBy();
        $conteoPlatoEspera = $coleccionEspera->pluck('PLATO')->countBy();
        $conteoPlatoSegundo = $coleccionSegundo->pluck('PLATO')->countBy();
        $conteoCarbohidratoEspera = $coleccionEspera->pluck('CARBOHIDRATO')->countBy();
        $conteoCarbohidratoSegundo = $coleccionSegundo->pluck('CARBOHIDRATO')->countBy();
        $conteoEmpaqueEspera = $coleccionEspera->pluck('EMPAQUE')->countBy();
        $conteoEmpaqueSegundo = $coleccionSegundo->pluck('EMPAQUE')->countBy();
        $conteoEnvioEspera = $coleccionEspera->pluck('ENVIO')->countBy();
        $conteoEnvioSegundo = $coleccionSegundo->pluck('ENVIO')->countBy();
        // dd($conteoSopaEspera, $conteoSopaSopa);
        $totalEspera->push([
            'sopa' => $this->restarColecciones($conteoSopaEspera, $conteoSopaSopa),
            'plato' => $this->restarColecciones($conteoPlatoEspera, $conteoPlatoSegundo),
            'carbohidrato' => $this->restarColecciones($conteoCarbohidratoEspera, $conteoCarbohidratoSegundo),
            'empaque' => $this->restarColecciones($conteoEmpaqueEspera, $conteoEmpaqueSegundo),
            'envio' => $this->restarColecciones($conteoEnvioEspera, $conteoEnvioSegundo),
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

        return view('livewire.admin.almuerzos.cocina-despache-planes', compact('usuarios', 'coleccion', 'totalEspera', 'totalDespachado'))
            ->extends('admin.master')
            ->section('content');
    }
}
