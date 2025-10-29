<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use App\Models\Almuerzo;
use App\Models\SwitchPlane;
use Illuminate\Http\Request;
use App\Helpers\GlobalHelper;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class UsuariosController extends Controller
{

    public function editarPlanUsuario($plan, $usuario, Request $request)
    {
        $plan = Plane::find((int)$plan);
        $usuario = User::find((int)$usuario);
        // dd($usuario);

        $idPedidoEditar = $request->query('pedido');

        if ($idPedidoEditar !== null) {
            $dia = DB::table('plane_user')->where('id', $idPedidoEditar)->first();
            if ($dia && $dia->estado == "pendiente") {
                DB::table('plane_user')->where('id', $idPedidoEditar)->update(['detalle' => null, 'whatsapp' => false]);
            }
        }

        $coleccion = collect();
        $fechaactual = Carbon::now()->format('y-m-d');
        $fechalimite = date("y-m-d", strtotime("next sunday"));
        //dd($fechalimite);
        $array = array();
        $lunes = false;
        $planes = $usuario->planesPendientes->where('id', $plan->id)->sortBy(function ($col) {
            return $col;
        });
        $estadoMenu = SwitchPlane::find(1);
        $menusemanal = "";

        $primer_dia = null;
        $esProximo = true;

        foreach ($planes as $dias) {

            if (WhatsappAPIHelper::saber_dia($dias->pivot->start) == 'Domingo') {
                continue;
            }
            if (date('y-m-d', strtotime($dias->pivot->start)) <= $fechalimite && date('y-m-d', strtotime($dias->pivot->start)) >= $fechaactual) {
                $dia = WhatsappAPIHelper::saber_dia($dias->pivot->start);
                $menusemanal = Almuerzo::where('dia', $dia)->first();
                if (!$menusemanal) {
                    continue;
                }

                // Marcar el primer conjunto de dias como proximo para ser resaltados
                if ($primer_dia === null) {
                    $primer_dia = $dia;
                } else if ($primer_dia !== $dia) {
                    $esProximo = false;
                }
                $proximo_value = $esProximo;

                $coleccion->push([
                    'detalle' => $dias->pivot->detalle,
                    'estado' => $dias->pivot->estado,
                    'dia' => WhatsappAPIHelper::saber_dia($dias->pivot->start),
                    'id' => $dias->pivot->id,
                    'fecha' => date('d-M', strtotime($dias->pivot->start)),
                    'sopa' => $menusemanal->sopa,
                    'ensalada' => $menusemanal->ensalada,
                    'ejecutivo' => $menusemanal->ejecutivo,
                    'ejecutivo_tiene_carbo' => $menusemanal->ejecutivo_tiene_carbo,
                    'ejecutivo_estado' => ($menusemanal->ejecutivo_estado) ? true : false,
                    'dieta' => $menusemanal->dieta,
                    'dieta_tiene_carbo' => $menusemanal->dieta_tiene_carbo,
                    'dieta_estado' => ($menusemanal->dieta_estado) ? true : false,
                    'vegetariano' => $menusemanal->vegetariano,
                    'vegetariano_tiene_carbo' => $menusemanal->vegetariano_tiene_carbo,
                    'vegetariano_estado' => ($menusemanal->vegetariano_estado) ? true : false,
                    'carbohidrato_1' => $menusemanal->carbohidrato_1,
                    'carbohidrato_1_estado' => ($menusemanal->carbohidrato_1_estado) ? true : false,
                    'carbohidrato_2' => $menusemanal->carbohidrato_2,
                    'carbohidrato_2_estado' => ($menusemanal->carbohidrato_2_estado) ? true : false,
                    'carbohidrato_3' => $menusemanal->carbohidrato_3,
                    'carbohidrato_3_estado' => ($menusemanal->carbohidrato_3_estado) ? true : false,
                    'jugo' => $menusemanal->jugo,
                    'envio1' => Plane::ENVIO1,
                    'envio2' => Plane::ENVIO2,
                    'envio3' => Plane::ENVIO3,
                    'empaque1' => 'Vianda',
                    'empaque2' => 'Empaque Bio(apto/microondas)',
                    'proximo' => $proximo_value, // Assign the dynamically determined value
                ]);
            }
        }

        return view('client.miperfil.calendario', compact('plan', 'usuario', 'coleccion', 'menusemanal', 'estadoMenu', 'idPedidoEditar'));
    }
    public function saldo()
    {
        $usuario = User::find(auth()->user()->id);
        // SALDO PENDIENTE
        $saldosPendientes = $usuario->saldos->where('anulado', false)->where('liquidado', null)->sortBy('created_at');
        // HISTORIAL DE SALDOS
        $saldosHistorial = $usuario->saldos->where('anulado', false)->sortByDesc('created_at');
        return view('client.miperfil.saldo', compact('usuario', 'saldosPendientes', 'saldosHistorial'));
    }
    public function saldoHistorial(Request $request): JsonResponse
    {
        $limite = $request->input('limite', 10); // Records per page, default 10
        $pagina = $request->input('pagina', 1);  // Requested page number, default 1

        $limite = (int) $limite;
        $pagina = (int) $pagina;

        $usuario = User::find(auth()->user()->id);

        // CONSULTA HISTORIAL
        $query = $usuario->saldos()
            ->where('anulado', false)
            ->orderByDesc('created_at');

        // REGISTROS PARA LA PAGINA DESEADA
        $saldosPaginados = $query->paginate($limite, ['*'], 'pagina', $pagina);

        $infoPagina = [
            "registros_totales" => $saldosPaginados->total(),
            "pagina_actual" => $saldosPaginados->currentPage(),
            "cantidad_pagina" => $saldosPaginados->perPage(),
            "saldos" => $saldosPaginados->items(),
        ];

        return response()->json($infoPagina, Response::HTTP_OK);
    }
    public function detalleplan($id, $planid)
    {
        $usuario = User::find($id);
        $plan = Plane::find($planid);
        return view('admin.usuarios.detalleplan', compact('usuario', 'plan'));
    }
    public function editar($id)
    {
        $evento = DB::table('plane_user')->where('id', $id)->first();

        return response()->json($evento);
    }
    public function borrar($id)
    {
        //$evento = DB::table('plane_user')->where('id', $id)->first();
        $evento = DB::table('plane_user')->where('id', $id)->delete();
        return response()->json('si');
        // if($evento->detalle==null)
        // {
        //     $evento = DB::table('plane_user')->where('id', $id)->delete();

        // }
        // else
        // {
        //     return response()->json('no');
        // }


    }
    public function archivar($id)
    {
        $registro = DB::table('plane_user')->select('plane_user.*')->where('id', $id)->first();
        DB::table('plane_user')->where('plane_id', $registro->plane_id)->where('user_id', $registro->user_id)->where('estado', Plane::ESTADOFINALIZADO)->whereDate('start', '<', Carbon::parse($registro->start)->addDay())->update(['estado' => Plane::ESTADOARCHIVADO, 'color' => Plane::COLORARCHIVADO]);
        return 'exito';
    }
    public function agregar(Request $request)
    {

        $feriados = DB::table('plane_user')->select('start')->where('title', 'feriado')->get();
        $listafechas = array();
        foreach (json_decode($feriados, true) as $fecha) {
            foreach ($fecha as $fe) {
                array_push($listafechas, $fe);
            }
        }
        $excluirSabados = false;
        if (isset($request->sabados)) {
            $excluirSabados = true;
        }
        if ($request->dias == null || $request->dias == "" || $request->dias == 0) {
            $dias = $this->getDiasHabiles($request->start, $request->end, $listafechas);
            foreach ($dias as $dia) {
                if ($excluirSabados) {
                    $saberDia = WhatsappAPIHelper::saber_dia($dia);
                    if ($saberDia != "Sabado") {
                        DB::table('plane_user')->insert([

                            'start' => $dia,
                            'end' => $dia,
                            'title' => $request->plan,
                            'plane_id' => $request->idplan,
                            'user_id' => $request->iduser
                        ]);
                    }
                } else {
                    DB::table('plane_user')->insert([

                        'start' => $dia,
                        'end' => $dia,
                        'title' => $request->plan,
                        'plane_id' => $request->idplan,
                        'user_id' => $request->iduser
                    ]);
                }
            }
        } else {
            $fin = Carbon::parse(Carbon::create($request->start)->addDays($request->dias * 2))->format('Y-m-d');
            $dias = $this->getDiasHabiles($request->start, $fin, $listafechas);
            $contador = 0;
            //dd($request->start);
            foreach ($dias as $dia) {
                if ($excluirSabados) {
                    $saberDia = WhatsappAPIHelper::saber_dia($dia);
                    if ($saberDia != "Sabado") {
                        DB::table('plane_user')->insert([

                            'start' => $dia,
                            'end' => $dia,
                            'title' => $request->plan,
                            'plane_id' => $request->idplan,
                            'user_id' => $request->iduser
                        ]);
                        $contador++;
                    }
                } else {
                    DB::table('plane_user')->insert([

                        'start' => $dia,
                        'end' => $dia,
                        'title' => $request->plan,
                        'plane_id' => $request->idplan,
                        'user_id' => $request->iduser
                    ]);
                    $contador++;
                }

                if ($contador == $request->dias) {
                    break;
                }
            }
        }
    }
    public function feriado(Request $request)
    {

        $dias = $this->getDiasHabiles($request->start, $request->end, []);
        // dd($dias);
        $contador = 0;
        foreach ($dias as $dia) {
            $siexiste = DB::table('plane_user')->where('start', $dia)->where('title', 'feriado')->get();
            if ($siexiste->count() != 0) {
            } else {
                DB::table('plane_user')->insert([
                    'color' => Plane::COLORFERIADO,
                    'start' => $dia,
                    'end' => $dia,
                    'title' => 'feriado',
                    'estado' => Plane::ESTADOFERIADO

                ]);
            }

            $getPlanesCoincidentes = DB::table('plane_user')->where('start', $dia)->where('title', '!=', 'feriado')->get();
            foreach ($getPlanesCoincidentes as $planCoincidente) {
                DB::beginTransaction();
                $extraerUltimo = DB::table('plane_user')
                    ->where('plane_id', $planCoincidente->plane_id)
                    ->where('user_id', $planCoincidente->user_id)
                    ->where('title', '!=', 'feriado')
                    ->orderBy('start', 'DESC')
                    ->first();

                $ultimaFecha = $extraerUltimo->start;
                $fechaParaAgregar =  $this->diaSiguienteAlUltimo($ultimaFecha, $planCoincidente->plane_id, $planCoincidente->user_id);
                DB::table('plane_user')->insert([

                    'start' => $fechaParaAgregar,
                    'end' => $fechaParaAgregar,
                    'title' => $planCoincidente->title,
                    'plane_id' => $planCoincidente->plane_id,
                    'user_id' => $planCoincidente->user_id
                ]);
                DB::table('plane_user')->where('id', $planCoincidente->id)->delete();
                DB::commit();
                $contador++;
            }
        }
        return response()->json($contador);
    }
    public function diaSiguienteAlUltimo($ultimaFecha, $planeId = null, $userId = null)
    {
        // Determinar si el plan del usuario incluye sábados
        $planIncluyeSabados = false;
        if ($planeId && $userId) {
            // Revisar si existen registros del plan en días sábado
            $registrosEnSabado = DB::table('plane_user')
                ->where('plane_id', $planeId)
                ->where('user_id', $userId)
                ->where('title', '!=', 'feriado')
                ->get();

            foreach ($registrosEnSabado as $registro) {
                if (WhatsappAPIHelper::saber_dia($registro->start) == 'Sabado') {
                    $planIncluyeSabados = true;
                    break;
                }
            }
        }

        $saberDia = WhatsappAPIHelper::saber_dia($ultimaFecha);
        if ($saberDia == 'Sabado') {
            $fechaParaAgregar = Carbon::parse(Carbon::create($ultimaFecha)->addDays(2))->format('Y-m-d');
        } else {
            $fechaParaAgregar = Carbon::parse(Carbon::create($ultimaFecha)->addDays(1))->format('Y-m-d');
        }

        // Si el día siguiente cae en sábado y el plan no incluye sábados, saltar al lunes
        if (WhatsappAPIHelper::saber_dia($fechaParaAgregar) == 'Sabado' && !$planIncluyeSabados) {
            $fechaParaAgregar = Carbon::parse(Carbon::create($fechaParaAgregar)->addDays(2))->format('Y-m-d');
        }

        //dd($fechaParaAgregar);
        $siExisteFeriado = DB::table('plane_user')->where('start', $fechaParaAgregar)->where('title', 'feriado')->first();
        while ($siExisteFeriado) {
            $fechaParaAgregar = Carbon::parse(Carbon::create($fechaParaAgregar)->addDays(1))->format('Y-m-d');
            if (WhatsappAPIHelper::saber_dia($fechaParaAgregar) == 'Domingo') {
                $fechaParaAgregar = Carbon::parse(Carbon::create($fechaParaAgregar)->addDays(1))->format('Y-m-d');
            }
            // Si cae en sábado y el plan no incluye sábados, saltar al lunes
            if (WhatsappAPIHelper::saber_dia($fechaParaAgregar) == 'Sabado' && !$planIncluyeSabados) {
                $fechaParaAgregar = Carbon::parse(Carbon::create($fechaParaAgregar)->addDays(2))->format('Y-m-d');
            }

            $siExisteFeriado = DB::table('plane_user')->where('start', $fechaParaAgregar)->where('title', 'feriado')->first();
        }
        return $fechaParaAgregar;
    }
    public function permiso($id, $todos)
    {
        $evento = DB::table('plane_user')->where('id', $id)->first();
        $coincidentes = DB::table('plane_user')->where('plane_id', $evento->plane_id)->where('user_id', $evento->user_id)->where('start', $evento->start)->where('estado', Plane::ESTADOPENDIENTE)->get();
        if ($coincidentes->count() > 1 && $todos == 0) {
            return 'varios';
        }

        $extraerUltimo = DB::table('plane_user')
            ->where('user_id', $evento->user_id)
            ->where('plane_id', $evento->plane_id)
            ->where('title', '!=', 'feriado')
            ->orderBy('start', 'DESC')
            ->first();
        $fechaParaAgregar = $this->diaSiguienteAlUltimo($extraerUltimo->start, $evento->plane_id, $evento->user_id);
        $saberDia = WhatsappAPIHelper::saber_dia($fechaParaAgregar);
        if ($saberDia == "Domingo") {
            $fechaParaAgregar = Carbon::parse($fechaParaAgregar)->addDay();
        }
        //dd($saberDia);
        if ($todos == 1) {
            foreach ($coincidentes as $plan) {
                DB::table('plane_user')->insert([

                    'start' => $fechaParaAgregar,
                    'end' => $fechaParaAgregar,
                    'title' => $plan->title,
                    'plane_id' => $plan->plane_id,
                    'user_id' => $plan->user_id
                ]);
                $evento = DB::table('plane_user')->where('id', $plan->id)->update(['estado' => Plane::ESTADOPERMISO, 'color' => Plane::COLORPERMISO, 'detalle' => null]);
            }
        } else if ($todos == 2 || $todos == 0) {
            DB::table('plane_user')->insert([

                'start' => $fechaParaAgregar,
                'end' => $fechaParaAgregar,
                'title' => $evento->title,
                'plane_id' => $evento->plane_id,
                'user_id' => $evento->user_id
            ]);
            $evento = DB::table('plane_user')->where('id', $id)->update(['estado' => Plane::ESTADOPERMISO, 'color' => Plane::COLORPERMISO, 'detalle' => null]);
        }


        return response()->json($evento);
    }

    public function permisoVarios(Request $request)
    {
        $fecha = $request->fecha; // string
        $cantidad = $request->cantidad;
        $planId = $request->planId;

        $fechaSeleccionada = Carbon::parse($fecha)->startOfDay();
        $fechaHoy = Carbon::now();
        $horaActual = $fechaHoy->hour;

        // No permitir fechas pasadas
        if ($fechaSeleccionada->lessThan($fechaHoy->startOfDay())) {
            return response()->json([
                'error' => 'No se pueden asignar permisos en fechas pasadas.'
            ], 400);
        }

        // Condición: si la fecha seleccionada es hoy y ya pasó de las 9AM → fallar
        if ($fechaSeleccionada->isSameDay($fechaHoy) && $horaActual >= 9) {
            return response()->json([
                'error' => 'Ya no se pueden solicitar permisos después de las 9AM.'
            ], 400);
        }

        // Obtener los eventos "pendientes" permisibles
        $eventosPermisibles = DB::table('plane_user')
            ->whereDate('start', $fechaSeleccionada)
            ->where('estado', 'pendiente')
            ->where('user_id', auth()->id())
            ->where('plane_id', $planId)
            ->limit($cantidad)
            ->get();

        if ($eventosPermisibles->isEmpty()) {
            return response()->json(['error' => 'No hay eventos permisibles'], 404);
        }

        $permisibleComparacion = $eventosPermisibles->first();

        // Calcular fecha siguiente
        $extraerUltimo = DB::table('plane_user')
            ->where('user_id', $permisibleComparacion->user_id)
            ->where('plane_id', $permisibleComparacion->plane_id)
            ->where('title', '!=', 'feriado')
            ->orderBy('start', 'DESC')
            ->first();

        $fechaParaAgregar = $this->diaSiguienteAlUltimo(
            $extraerUltimo->start,
            $permisibleComparacion->plane_id,
            $permisibleComparacion->user_id
        );

        $saberDia = WhatsappAPIHelper::saber_dia($fechaParaAgregar);
        if ($saberDia == "Domingo") {
            $fechaParaAgregar = Carbon::parse($fechaParaAgregar)->addDay();
        }

        $idsActualizados = [];

        foreach ($eventosPermisibles as $permisible) {
            // Crear nuevos registros "pendientes" para el usuario tras su ultimo día
            DB::table('plane_user')->insert([
                'start' => $fechaParaAgregar,
                'end' => $fechaParaAgregar,
                'title' => $permisible->title,
                'plane_id' => $permisible->plane_id,
                'user_id' => $permisible->user_id
            ]);

            // Actualizar los registros pendientes seleccionados a "permisos"
            DB::table('plane_user')
                ->where('id', $permisible->id)
                ->update([
                    'estado' => Plane::ESTADOPERMISO,
                    'color' => Plane::COLORPERMISO,
                    'detalle' => null
                ]);

            $idsActualizados[] = $permisible->id;
        }

        return response()->json([
            'success' => true,
            'cantidad' => count($idsActualizados),
            'ids_actualizados' => $idsActualizados,
            'fecha_original' => $fecha,
            'fecha_agregada' => $fechaParaAgregar
        ]);
    }

    public function deshacerPermisosVarios(Request $request)
    {
        $fecha = $request->fecha; // string
        $cantidad = $request->cantidad;
        $planId = $request->planId;

        $fechaSeleccionada = Carbon::parse($fecha)->startOfDay();
        $fechaHoy = Carbon::now();
        $horaActual = $fechaHoy->hour;

        // Condición: si la fecha seleccionada es hoy y ya pasó de las 9AM → fallar
        if ($fechaSeleccionada->isSameDay($fechaHoy) && $horaActual >= 9) {
            return response()->json([
                'error' => 'Ya no se pueden deshacer permisos después de las 9AM.'
            ], 400);
        }

        // No permitir fechas pasadas
        if ($fechaSeleccionada->lessThan($fechaHoy->startOfDay())) {
            return response()->json([
                'error' => 'No se pueden deshacer permisos de días anteriores.'
            ], 400);
        }

        // Obtener permisos de la fecha solicitada
        $permisosRemovibles = DB::table('plane_user')
            ->whereDate('start', $fechaSeleccionada)
            ->where('estado', 'permiso')
            ->where('user_id', auth()->id())
            ->where('plane_id', $planId)
            ->orderBy('start', 'ASC')
            ->limit($cantidad)
            ->get();

        // En caso de no haber permisos removibles -> fallar
        if ($permisosRemovibles->isEmpty()) {
            return response()->json(['error' => 'No hay permisos que puedan deshacerse'], 404);
        }

        // Eliminar los últimos "pendientes" del usuario (más recientes)
        $pendientesAEliminar = DB::table('plane_user')
            ->where('user_id', auth()->id())
            ->where('plane_id', $planId)
            ->whereNotIn('estado', [Plane::ESTADOFINALIZADO, Plane::ESTADOFERIADO])
            ->orderBy('start', 'DESC')
            ->limit($permisosRemovibles->count())
            ->pluck('id'); // solo obtener IDs


        if ($pendientesAEliminar->isNotEmpty()) {
            DB::table('plane_user')->whereIn('id', $pendientesAEliminar)->delete();
        }

        // Actualizar los permisos removibles a pendientes
        $idsActualizados = [];
        foreach ($permisosRemovibles as $removible) {
            DB::table('plane_user')
                ->where('id', $removible->id)
                ->update([
                    'estado' => Plane::ESTADOPENDIENTE,
                    'color' => Plane::COLORPENDIENTE,
                    'detalle' => null
                ]);

            $idsActualizados[] = $removible->id;
        }

        return response()->json([
            'success' => true,
            'cantidad_actualizados' => count($idsActualizados),
            'cantidad_eliminados' => $pendientesAEliminar->count(),
            'ids_actualizados' => $idsActualizados,
            'fecha_original' => $fechaSeleccionada->toDateString(),
        ]);
    }



    public function quitarpermiso($id)
    {
        $evento = DB::table('plane_user')->where('id', $id)->update(['estado' => Plane::ESTADOPENDIENTE, 'color' => Plane::COLORPENDIENTE]);
        return response()->json($evento);
    }
    public function mostrar($idplan, $iduser)
    {

        $eventos = DB::table('plane_user')->where('plane_id', $idplan)->where('user_id', $iduser)->get();
        $feriados = DB::table('plane_user')->where('title', 'feriado')->get();

        foreach ($feriados as $feriado) {
            $eventos->push($feriado);
        }

        // Log::debug("Informacion de eventos obtenida: ", [$eventos]);


        return response()->json($eventos);
    }

    // public function contarPedidosDisponiblesPlan($idplan, $iduser) 
    // {
    //     // Get all events for the user and plan
    //     $eventos = DB::table('plane_user')
    //         ->where('plane_id', $idplan)
    //         ->where('user_id', $iduser)
    //         ->get();
        
    //     // Get feriados separately

    //     $fechaMinima = $eventos->min('start');
    //     $fechaMaxima = $eventos->max('end');

    //     // Obtemer las fechas feriadas desde el primer dia de registro del plan hasta el ultimo dia del mes del final del plan
    //     $feriados = DB::table('plane_user')
    //         ->where('title', 'feriado')
    //         ->get();

    //     $feriados = DB::table('plane_user')
    //         ->where('title', 'feriado')
    //         // Filter feriados whose 'start' date is between the plan's min start and max end.
    //         ->whereBetween('start', [$fechaMinima, $fechaMaxima])
    //         ->get();

    //     // Group events by date and count them
    //     $eventosPorDia = $eventos->groupBy('start')->map(function ($eventosDelDia, $fecha) {
    //         return [
    //             'id' => 'count_' . $fecha, // Unique identifier
    //             'start' => $fecha,
    //             'end' => $fecha,
    //             'title' => $eventosDelDia->count() . ' disponibles', // "X disponibles"
    //             'color' => '#F7843A',
    //             'eventos' => $eventosDelDia->toArray(), // Keep original events for reference if needed
    //             'tipo' => 'contador'
    //         ];
    //     })->values(); // Reset keys to get a clean array

    //     // Add feriados to the collection
    //     foreach ($feriados as $feriado) {
    //         $eventosPorDia->push([
    //             'id' => $feriado->id,
    //             'start' => $feriado->start,
    //             'end' => $feriado->end,
    //             'title' => $feriado->title,
    //             'color' => $feriado->color ?? '#FF0000',
    //             'tipo' => 'feriado'
    //         ]);
    //     }

    //     $meses = // Filter the existing months from the availables dates in $eventosPorDia, and store the days inside too, like a month["numero"=>10,"nombre"=>"Octubre","dias"=>$filteredEventosPorDia]


    //     // Log::debug("Información de eventos agrupados obtenida: ", [$eventosPorDia]);

    //     return response()->json([
    //         "dias" => $eventosPorDia,
    //     ]);
    // }

    public function contarPedidosDisponiblesPlan($idplan, $iduser) 
    {
        // Get all events for the user and plan
        $pedidos = DB::table('plane_user')
            ->where('plane_id', $idplan)
            ->where('user_id', $iduser)
            ->get();
        
        $fechaMinima = $pedidos->min('start');
        $fechaMaxima = $pedidos->max('end');

        // Obtener los dias feriados en el rango del plan del usuario
        $feriados = DB::table('plane_user')
            ->where('title', 'feriado')
            ->whereBetween('start', [$fechaMinima, $fechaMaxima])
            ->get();

        // Agrupar por fecha y contar pedidos
        $pedidosPorDia = $pedidos->groupBy('start')->map(function ($pedidosDelDia, $fecha) {
            // Determine tipo segun jerarquia de estados permiso > pendiente > finalizado
            $tipoFinal = 'contador'; // default
            
            // Revisar si existe un pedido con permiso
            $tienePermiso = $pedidosDelDia->contains('estado', 'permiso');
            
            if ($tienePermiso) {
                // Establecer el tipo de dia como 'permiso'
                $tipoFinal = 'permiso';
            } else {
                // Revisar si existen pedidos pendientes
                $tienePendiente = $pedidosDelDia->contains('estado', 'pendiente');
                
                if ($tienePendiente) {
                    // Establecer el tipo de dia como 'pendiente'
                    $tipoFinal = 'pendiente';
                } else {
                    // Revisar si todos los pedidos para el dia estan finalizados
                    $todosFinalizado = $pedidosDelDia->every(function ($evento) {
                        return $evento->estado === 'finalizado';
                    });
                    
                    if ($todosFinalizado) {
                        // Establecer el tipo de dia como 'finalizado'
                        $tipoFinal = 'finalizado';
                    }
                }
            }

            return [
                'id' => 'count_' . $fecha,
                'start' => $fecha,
                'end' => $fecha,
                'title' => $pedidosDelDia->count() . ' disponibles',
                'color' => '#F7843A',
                'eventos' => $pedidosDelDia->toArray(),
                'tipo' => $tipoFinal,
            ];
        })->values();

        // Agregar dias feriados
        foreach ($feriados as $feriado) {
            $pedidosPorDia->push([
                'id' => $feriado->id,
                'start' => $feriado->start,
                'end' => $feriado->end,
                'title' => $feriado->title,
                'color' => $feriado->color ?? '#FF0000',
                'tipo' => 'feriado'
            ]);
        }

        // Get current date for flag
        $currentDate = now()->format('Y-m-d');
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Group events by month and extract available months
        $meses = $pedidosPorDia->groupBy(function ($evento) {
            return Carbon::parse($evento['start'])->format('Y-m');
        })->map(function ($diasDelMes, $yearMonth) use ($currentDate, $currentMonth, $currentYear) {
            $date = Carbon::parse($yearMonth . '-01');
            $month = $date->month;
            $year = $date->year;
            
            // Check if this month contains the current date
            $currentDayFlag = null;
            if ($month == $currentMonth && $year == $currentYear) {
                $currentDayFlag = $currentDate;
            }
            
            return [
                'numero' => $month,
                'anio' => $year,
                'nombre' => ucfirst($date->locale('es')->monthName), // Spanish month name
                'dias' => $diasDelMes->values()->toArray(),
                'currentDayFlag' => $currentDayFlag // null if not current month, date string if current month
            ];
        })->values(); // Reset keys to get clean array

        return response()->json([
            "meses" => $meses,
            // "dias" => $pedidosPorDia, // Keep this if you still need it
        ]);
    }



    public function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array())
    {
        // Convirtiendo en timestamp las fechas
        $fechainicio = strtotime($fechainicio);
        $fechafin = strtotime($fechafin);

        // Incremento en 1 dia
        $diainc = 24 * 60 * 60;

        // Arreglo de dias habiles, inicianlizacion
        $diashabiles = array();

        // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
        for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
            // Si el dia indicado, no es sabado o domingo es habil
            if (!in_array(date('N', $midia), array(7))) { // DOC: http://www.php.net/manual/es/function.date.php
                // Si no es un dia feriado entonces es habil
                if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                    array_push($diashabiles, date('Y-m-d', $midia));
                }
            }
        }

        return $diashabiles;
    }
}
