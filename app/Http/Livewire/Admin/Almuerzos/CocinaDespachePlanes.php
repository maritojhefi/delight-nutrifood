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
use App\Events\RefreshListaPensionadosEvent;

class CocinaDespachePlanes extends Component
{
    public $menuHoy;
    public $reporte;
    public $fechaSeleccionada, $cambioFecha = false;
    public $search;
    public $estadoBuscador = "NOMBRE", $estadoColor = "success";
    public $planSeleccionado = null;
    public $planesSeleccionados = [];
    public $filtroInicializado = false; // Bandera para controlar la auto-selección inicial
    public $dieta_cant, $ejecutivo_cant, $carbohidrato_1_cant, $carbohidrato_2_cant, $carbohidrato_3_cant, $vegetariano_cant, $sopa_cant;
    protected $listeners = [
        'echo:pensionados,RefreshListaPensionadosEvent' => 'actualizarListaPensionados'
    ];

    // Método que se ejecuta cuando se actualiza planSeleccionado
    public function updatedPlanSeleccionado($value)
    {
        // Si se selecciona un plan válido, agregarlo al array
        if ($value !== '' && $value !== null && is_numeric($value)) {
            // Verificar que el plan no esté ya en el array
            if (!in_array($value, $this->planesSeleccionados)) {
                $this->planesSeleccionados[] = $value;
            }
            // Resetear el select a la opción por defecto
            $this->planSeleccionado = '';
            // Marcar que el usuario ha interactuado manualmente con los filtros
            $this->filtroInicializado = true;
        }
    }

    // Método para remover un plan del filtro
    public function removerPlan($planId)
    {
        $this->planesSeleccionados = array_values(array_filter($this->planesSeleccionados, function ($id) use ($planId) {
            return $id != $planId;
        }));
        // Marcar que el usuario ha interactuado manualmente con los filtros
        $this->filtroInicializado = true;
    }

    // Método para limpiar todos los filtros de planes
    public function limpiarFiltrosPlanes()
    {
        $this->planesSeleccionados = [];
        // Marcar que el usuario ha interactuado manualmente con los filtros
        $this->filtroInicializado = true;
    }
    public function actualizarListaPensionados($data)
    {
        $mensaje = $data['mensaje'];
        $estadoMensaje = $data['estadoMensaje'];
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => $estadoMensaje,
            'message' => $mensaje
        ]);
        $this->dispatchBrowserEvent('reproducirTexto', [
            'texto' => $mensaje,
            'voz' => 'Spanish Latin American Female',
            'opciones' => [
                'rate' => 1.2,
            ]
        ]);
    }
    public function cambiarCantidad($variable, $cantidad)
    {
        // Actualizar la propiedad del componente
        switch ($variable) {
            case 'dieta_cant':
                $this->dieta_cant = $cantidad;
                break;
            case 'vegetariano_cant':
                $this->vegetariano_cant = $cantidad;
                break;
            case 'ejecutivo_cant':
                $this->ejecutivo_cant = $cantidad;
                break;
            case 'carbohidrato_1_cant':
                $this->carbohidrato_1_cant = $cantidad;
                break;
            case 'carbohidrato_2_cant':
                $this->carbohidrato_2_cant = $cantidad;
                break;
            case 'carbohidrato_3_cant':
                $this->carbohidrato_3_cant = $cantidad;
                break;
            case 'sopa_cant':
                $this->sopa_cant = $cantidad;
                break;
        }

        // Actualizar en la base de datos
        DB::table('almuerzos')->where('id', $this->menuHoy->id)->update([$variable => $cantidad]);
        GlobalHelper::actualizarCarbosDisponibilidad();

        // Recargar el modelo para tener los datos actualizados
        $this->menuHoy->refresh();
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
            case 'sopa_estado':
                $this->menuHoy->sopa_estado = $this->menuHoy->sopa_estado == true ? false : true;
                if (!$this->menuHoy->sopa_estado) {
                    $this->menuHoy->sopa_cant = 0;
                }
                $this->menuHoy->save();
                break;
            default:
                // Código adicional para casos no manejados
                break;
        }

        GlobalHelper::actualizarCarbosDisponibilidad();
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => "Se actualizo correctamente!"
        ]);

        // Actualizar el Sweet Alert con los nuevos datos
        $this->dispatchBrowserEvent('actualizarDisponibilidad', [
            'menu' => [
                'ejecutivo' => $this->menuHoy->ejecutivo,
                'ejecutivo_cant' => $this->menuHoy->ejecutivo_cant,
                'ejecutivo_estado' => $this->menuHoy->ejecutivo_estado,
                'dieta' => $this->menuHoy->dieta,
                'dieta_cant' => $this->menuHoy->dieta_cant,
                'dieta_estado' => $this->menuHoy->dieta_estado,
                'vegetariano' => $this->menuHoy->vegetariano,
                'vegetariano_cant' => $this->menuHoy->vegetariano_cant,
                'vegetariano_estado' => $this->menuHoy->vegetariano_estado,
                'carbohidrato_1' => $this->menuHoy->carbohidrato_1,
                'carbohidrato_1_cant' => $this->menuHoy->carbohidrato_1_cant,
                'carbohidrato_1_estado' => $this->menuHoy->carbohidrato_1_estado,
                'carbohidrato_2' => $this->menuHoy->carbohidrato_2,
                'carbohidrato_2_cant' => $this->menuHoy->carbohidrato_2_cant,
                'carbohidrato_2_estado' => $this->menuHoy->carbohidrato_2_estado,
                'carbohidrato_3' => $this->menuHoy->carbohidrato_3,
                'carbohidrato_3_cant' => $this->menuHoy->carbohidrato_3_cant,
                'carbohidrato_3_estado' => $this->menuHoy->carbohidrato_3_estado,
                'sopa' => $this->menuHoy->sopa,
                'sopa_cant' => $this->menuHoy->sopa_cant,
                'sopa_estado' => $this->menuHoy->sopa_estado,
            ]
        ]);
    }
    public function cambiarDisponibilidad()
    {

        $fecha = date('Y-m-d');
        $resultado = $this->saber_dia($fecha);
        $this->menuHoy = Almuerzo::withoutGlobalScope('diasActivos')->where('dia', $resultado)->first();

        if (!$this->menuHoy) {
            $this->dispatchBrowserEvent('mostrarDisponibilidad', ['menu' => null]);
            return;
        }

        // dd($this->menuHoy);
        $this->ejecutivo_cant = $this->menuHoy->ejecutivo_cant;
        $this->dieta_cant = $this->menuHoy->dieta_cant;
        $this->vegetariano_cant = $this->menuHoy->vegetariano_cant;
        $this->carbohidrato_1_cant = $this->menuHoy->carbohidrato_1_cant;
        $this->carbohidrato_2_cant = $this->menuHoy->carbohidrato_2_cant;
        $this->carbohidrato_3_cant = $this->menuHoy->carbohidrato_3_cant;
        $this->sopa_cant = $this->menuHoy->sopa_cant;

        $this->dispatchBrowserEvent('mostrarDisponibilidad', [
            'menu' => [
                'ejecutivo' => $this->menuHoy->ejecutivo,
                'ejecutivo_cant' => $this->menuHoy->ejecutivo_cant,
                'ejecutivo_estado' => $this->menuHoy->ejecutivo_estado,
                'dieta' => $this->menuHoy->dieta,
                'dieta_cant' => $this->menuHoy->dieta_cant,
                'dieta_estado' => $this->menuHoy->dieta_estado,
                'vegetariano' => $this->menuHoy->vegetariano,
                'vegetariano_cant' => $this->menuHoy->vegetariano_cant,
                'vegetariano_estado' => $this->menuHoy->vegetariano_estado,
                'carbohidrato_1' => $this->menuHoy->carbohidrato_1,
                'carbohidrato_1_cant' => $this->menuHoy->carbohidrato_1_cant,
                'carbohidrato_1_estado' => $this->menuHoy->carbohidrato_1_estado,
                'carbohidrato_2' => $this->menuHoy->carbohidrato_2,
                'carbohidrato_2_cant' => $this->menuHoy->carbohidrato_2_cant,
                'carbohidrato_2_estado' => $this->menuHoy->carbohidrato_2_estado,
                'carbohidrato_3' => $this->menuHoy->carbohidrato_3,
                'carbohidrato_3_cant' => $this->menuHoy->carbohidrato_3_cant,
                'carbohidrato_3_estado' => $this->menuHoy->carbohidrato_3_estado,
                'sopa' => $this->menuHoy->sopa,
                'sopa_cant' => $this->menuHoy->sopa_cant,
                'sopa_estado' => $this->menuHoy->sopa_estado,
            ]
        ]);
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
        // Resetear los planes seleccionados cuando cambia la fecha
        $this->planSeleccionado = null;
        $this->planesSeleccionados = [];
        // Permitir que se auto-seleccione nuevamente para la nueva fecha
        $this->filtroInicializado = false;
    }

    public function confirmarDespacho($id)
    {
        //dd($id);
        DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINADESPACHADO, 'despachado_at' => Carbon::now()]);
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => "Se despachó este plan!"
        ]);
        $this->dispatchBrowserEvent('reproducirTexto', [
            'texto' => "Se despachó este plan",
            'voz' => 'Spanish Latin American Female',
            'opciones' => [
                'rate' => 1.2,
                'pitch' => 1,
                'volume' => 1
            ]
        ]);
    }
    public function despacharSopa($id)
    {
        // dd($id);
        $registro = DB::table('plane_user')->where('id', $id)->first();
        if ($registro->cocina == Plane::COCINASOLOSEGUNDO) {
            $this->confirmarDespacho($id);
        } else {
            DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINASOLOSOPA, 'sopa_despachada_at' => Carbon::now()]);
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'success',
                'message' => "Se despachó la sopa de este plan"
            ]);
            $this->dispatchBrowserEvent('reproducirTexto', [
                'texto' => "Se despachó la sopa de este plan",
                'voz' => 'Spanish Latin American Female',
                'opciones' => [
                    'rate' => 1.2,
                    'pitch' => 1,
                    'volume' => 1
                ]
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
            DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINASOLOSEGUNDO, 'segundo_despachado_at' => Carbon::now()]);
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'success',
                'message' => "Se despachó el segundo de este plan"
            ]);
            $this->dispatchBrowserEvent('reproducirTexto', [
                'texto' => "Se despachó el segundo de este plan",
                'voz' => 'Spanish Latin American Female',
                'opciones' => [
                    'rate' => 1.2,
                    'pitch' => 1,
                    'volume' => 1
                ]
            ]);
        }
    }
    public function restablecerPlan($id)
    {

        DB::table('plane_user')->where('id', $id)->update(['cocina' => Plane::COCINAESPERA, 'despachado_at' => null, 'sopa_despachada_at' => null, 'segundo_despachado_at' => null]);
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => "Se restablecio el plan, ahora se encuentra en espera"
        ]);
        $this->dispatchBrowserEvent('reproducirTexto', [
            'texto' => "Se restableció el plan, se encuentra nuevamente en espera",
            'voz' => 'Spanish Latin American Female',
            'opciones' => [
                'rate' => 1.2,
                'pitch' => 1,
                'volume' => 1
            ]
        ]);
    }

    public function marcarComoIngresado($itemId)
    {
        try {
            // Actualizar el registro en plane_user
            $actualizado = DB::table('plane_user')
                ->where('id', $itemId)
                ->update(['cliente_ingresado' => true, 'cliente_ingresado_at' => Carbon::now()]);

            if ($actualizado) {
                $registro = DB::table('plane_user')->where('id', $itemId)->first();
                $usuario = User::find($registro->user_id);

                event(new RefreshListaPensionadosEvent('Cliente ' . $usuario->name . ' ha ingresado', 'success'));

                // Despachar evento para actualizar la lista en tiempo real
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Cliente marcado como ingresado correctamente'
                ]);

                // Despachar evento para que el modal se abra después de marcar ingreso
                $this->dispatchBrowserEvent('clienteMarcadoYAbrirModal', [
                    'itemId' => $itemId
                ]);
            } else {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'warning',
                    'message' => 'No se encontró el registro para actualizar'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al marcar como ingresado: ' . $e->getMessage()
            ]);
        }
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

        $pens = DB::table('plane_user')->select('plane_user.*', 'users.name', 'planes.editable', 'planes.nombre', 'planes.id as plane_id', 'horarios.hora_inicio', 'horarios.hora_fin')
            ->leftjoin('users', 'users.id', 'plane_user.user_id')
            ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
            ->leftjoin('horarios', 'horarios.id', 'planes.horario_id')
            ->whereDate('plane_user.start', $this->fechaSeleccionada)
            ->where('plane_user.title', '!=', 'feriado')
            //->where('plane_user.detalle','!=',null)
            ->whereIn('plane_user.estado', [Plane::ESTADOFINALIZADO, Plane::ESTADOPENDIENTE, Plane::ESTADOPERMISO])
            ->get();            //dd($pens);
        // Suponiendo que estas funciones ya existen
        $coleccion = GlobalHelper::armarColeccionReporteDiarioVista($pens, $this->fechaSeleccionada);

        // Crear colección de planes únicos con sus horarios directamente desde $pens
        $planesColeccion = collect();
        $planesIdsUnicos = $pens->pluck('plane_id')->unique()->filter();
        foreach ($planesIdsUnicos as $planeId) {
            $plan = $pens->firstWhere('plane_id', $planeId);
            if ($plan && $plan->nombre) {
                $planesColeccion->push((object)[
                    'id' => $plan->plane_id,
                    'nombre' => $plan->nombre,
                    'hora_inicio' => $plan->hora_inicio,
                    'hora_fin' => $plan->hora_fin,
                ]);
            }
        }

        // // Seleccionar automáticamente el plan según la hora actual solo en la primera carga
        // // Solo si el usuario no ha interactuado manualmente con los filtros
        // if (!$this->filtroInicializado && $this->planSeleccionado === null && empty($this->planesSeleccionados)) {
        //     $horaActual = Carbon::now()->format('H:i:s');
        //     $planEncontrado = false;

        //     foreach ($planesColeccion as $plan) {
        //         if ($plan->hora_inicio && $plan->hora_fin) {
        //             try {
        //                 $horaInicio = Carbon::parse($plan->hora_inicio)->format('H:i:s');
        //                 $horaFin = Carbon::parse($plan->hora_fin)->format('H:i:s');
        //                 // Verificar si la hora actual está dentro del rango
        //                 if ($horaActual >= $horaInicio && $horaActual <= $horaFin) {
        //                     // Agregar el plan encontrado al array de planes seleccionados
        //                     $this->planesSeleccionados[] = $plan->id;
        //                     $planEncontrado = true;
        //                     break;
        //                 }
        //             } catch (\Exception $e) {
        //                 // Si hay error al parsear las horas, continuar con el siguiente plan
        //                 continue;
        //             }
        //         }
        //     }
        //     // Si no se encontró ningún plan activo, seleccionar el primero
        //     if (!$planEncontrado && $planesColeccion->count() > 0) {
        //         $this->planesSeleccionados[] = $planesColeccion->first()->id;
        //     }
        // }

        // Limpiar planes seleccionados que ya no existen en la colección actual
        if (!empty($this->planesSeleccionados)) {
            $planesExistentes = $planesColeccion->pluck('id')->toArray();
            $this->planesSeleccionados = array_values(array_filter($this->planesSeleccionados, function ($planId) use ($planesExistentes) {
                return in_array($planId, $planesExistentes);
            }));
        }

        // Filtrar colección por planes seleccionados
        // Si hay planes en el array, filtrar por esos planes
        if (!empty($this->planesSeleccionados)) {
            $coleccion = $coleccion->filter(function ($item) {
                return isset($item['PLAN_ID']) && in_array($item['PLAN_ID'], $this->planesSeleccionados);
            })->values();
        }

        // Ordenar dando prioridad a los clientes ingresados (los que ingresaron primero van primero)
        $coleccion = $coleccion->sortBy(function ($item) {
            // Si tiene CLIENTE_INGRESADO_AT, usar esa fecha para ordenar ascendentemente
            // Si no tiene, usar una fecha muy futura para que vaya al final
            return $item['CLIENTE_INGRESADO_AT'] ?? '9999-12-31 23:59:59';
        })->values();

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

        return view('livewire.admin.almuerzos.cocina-despache-planes', compact('usuarios', 'coleccion', 'totalEspera', 'totalDespachado', 'planesColeccion'))
            ->extends('admin.master')
            ->section('content');
    }
}
