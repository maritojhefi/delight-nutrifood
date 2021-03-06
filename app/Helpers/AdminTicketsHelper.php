<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Plane;
use GuzzleHttp\Client;
use App\Models\Almuerzo;
use Illuminate\Support\Facades\DB;
use App\Models\WhatsappPlanAlmuerzo;
use Illuminate\Support\Facades\Artisan;



class AdminTicketsHelper
{
    public static function diaSiguienteAlUltimo($ultimaFecha)
    {

        $saberDia = WhatsappAPIHelper::saber_dia($ultimaFecha);
        if ($saberDia == 'Sabado') {
            $fechaParaAgregar = Carbon::parse(Carbon::create($ultimaFecha)->addDays(2))->format('Y-m-d');
        } else {
            $fechaParaAgregar = Carbon::parse(Carbon::create($ultimaFecha)->addDays(1))->format('Y-m-d');
        }
        //dd($fechaParaAgregar);
        $siExisteFeriado = DB::table('plane_user')->where('start', $fechaParaAgregar)->where('title', 'feriado')->first();
        while ($siExisteFeriado) {
            $fechaParaAgregar = Carbon::parse(Carbon::create($fechaParaAgregar)->addDays(1))->format('Y-m-d');
            $siExisteFeriado = DB::table('plane_user')->where('start', $fechaParaAgregar)->where('title', 'feriado')->first();
        }
        return $fechaParaAgregar;
    }
    public static function calcular($telefono, $numero, $idConversacion)
    {

        if ($numero == "3" || $numero == "2" || $numero == "1" || $numero == "0") {
            $fechaManana = Carbon::parse(Carbon::now()->addDays(1))->format('Y-m-d');
            $buscarUsuario = DB::table('users')->select(
                'users.id as idUser',
                'users.*',
                'whatsapp_plan_almuerzos.id as idwhatsapp',
                'whatsapp_plan_almuerzos.*',
                'plane_user.id as idPivot',
                'plane_user.user_id',
                'plane_user.plane_id',
                'plane_user.title'
            )
                ->leftjoin('whatsapp_plan_almuerzos', 'whatsapp_plan_almuerzos.cliente_id', 'users.id')
                ->leftjoin('plane_user', 'plane_user.user_id', 'users.id')
                ->where('users.telf', $telefono)
                ->where('plane_user.start', $fechaManana)
                ->where('plane_user.estado', 'pendiente')
                ->first();

            if ($buscarUsuario) {
                if ($buscarUsuario->idwhatsapp != null) {
                    //dd($buscarUsuario->idwhatsapp);
                    if ($numero != '0') {
                        if ($buscarUsuario->paso_segundo == false) {
                            AdminTicketsHelper::seleccionarMenu($buscarUsuario, $numero, 1);
                        } elseif ($buscarUsuario->paso_carbohidrato == false) {
                            AdminTicketsHelper::seleccionarMenu($buscarUsuario, $numero, 2);
                        } elseif ($buscarUsuario->paso_metodo_envio == false) {
                            AdminTicketsHelper::seleccionarMenu($buscarUsuario, $numero, 3);
                        } elseif ($buscarUsuario->paso_metodo_empaque == false) {
                            AdminTicketsHelper::seleccionarMenu($buscarUsuario, $numero, 4);
                        }
                    } else {
                        //dd($buscarUsuario->plane_id);
                        $extraerUltimo = DB::table('plane_user')
                            ->where('user_id', $buscarUsuario->user_id)
                            ->where('plane_id', $buscarUsuario->plane_id)
                            ->where('title', '!=', 'feriado')
                            ->orderBy('start', 'DESC')
                            ->first();
                        $fechaParaAgregar = AdminTicketsHelper::diaSiguienteAlUltimo($extraerUltimo->start);
                        DB::table('plane_user')->insert([

                            'start' => $fechaParaAgregar,
                            'end' => $fechaParaAgregar,
                            'title' => $buscarUsuario->title,
                            'plane_id' => $buscarUsuario->plane_id,
                            'user_id' => $buscarUsuario->user_id
                        ]);
                        $actualizarTicket = DB::table('whatsapp_plan_almuerzos')->where('id', $buscarUsuario->idwhatsapp)->first();
                        //dd($actualizarTicket);    
                        if ($actualizarTicket->cantidad == 1) {
                            DB::table('whatsapp_plan_almuerzos')->where('id', $actualizarTicket->id)->delete();
                            //dd($actualizarTicket);

                        } else {
                            //dd($buscarUsuario->idPivot);
                            DB::table('whatsapp_plan_almuerzos')->where('id', $actualizarTicket->id)->decrement('cantidad');
                            DB::table('whatsapp_plan_almuerzos')->where('id', $actualizarTicket->id)->update(['paso_segundo' => 0, 'paso_carbohidrato' => 0, 'paso_metodo_envio' => 0, 'paso_metodo_empaque' => 0]);
                        }
                        DB::table('plane_user')->where('id', $buscarUsuario->idPivot)->update(['estado' => Plane::ESTADOPERMISO, 'detalle' => null, 'color' => Plane::COLORPERMISO]);
                        WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Tu permiso fue aceptado y guardado! Si quieres personalizar tu semana y ver todo lo que ofrece Delight visitanos: https://delight-nutrifood.com');
                    }
                } else {

                    $devlucion = WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Hola! No te encuentras registrado en ningun plan, probablemente se modifico tu plan o no te encuentras registrado en ninguno, contactate con soporte para mas informacion!');
                    // dd($devlucion);
                }
            } else {
                WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Hola! No te encuentras registrado en ningun plan, probablemente se modifico tu plan o no te encuentras registrado en ninguno, contactate con soporte para mas informacion!');
            }
        } else {
            WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'No marcaste una respuesta correcta');
        }
    }

    public static function enviarMensajeConMenu($usuario, $paso, $menuDiaActual)
    {

        switch ($paso) {
            case '1':

                WhatsappAPIHelper::enviarTemplate('delight_carbohidrato', [$menuDiaActual->carbohidrato_1, $menuDiaActual->carbohidrato_2, $menuDiaActual->carbohidrato_3, 'Cancelar operacion y pedir permiso'], $usuario->telf, 'es');

                break;
            case '2':

                $devolucion = WhatsappAPIHelper::enviarTemplate('delight_tipo_envio', ['*1* Para Mesa   *2* Para llevar(Paso a recoger)   *3* Delivery', 'Cancelar operacion y pedir permiso'], $usuario->telf, 'es');
                // dd($devolucion);
                break;
            case '3':

                WhatsappAPIHelper::enviarTemplate('delight_empaque', ['*1* Vianda   *2* Eco-Empaque Delight', 'Cancelar operacion y pedir permiso'], $usuario->telf, 'es');
                break;
            case '4':

                $actualizarTicket = DB::table('whatsapp_plan_almuerzos')->where('cliente_id', $usuario->idUser)->first();
                //dd($actualizarTicket->id);
                $fechaManana = Carbon::parse(Carbon::now()->addDays(1))->format('Y-m-d');
                $diaPlan = WhatsappAPIHelper::saber_dia($fechaManana);
                $datosPlan = DB::table('plane_user')->where('start', $fechaManana)->where('user_id', $usuario->idUser)->first();
                $detalle = json_decode($datosPlan->detalle);
                if ($detalle->EMPAQUE != "") {
                    $devolucion = WhatsappAPIHelper::enviarTemplate('delight_pedido_listo', [$diaPlan, $detalle->SOPA, $detalle->PLATO . '(' . $detalle->CARBOHIDRATO . ')', 'Carbohidrato: *' . $detalle->CARBOHIDRATO . '*', 'Metodo: *' . $detalle->ENVIO . '* ' . ' Empaque: *' . $detalle->EMPAQUE . '*', 'Ingresa a tu perfil en nuestra pagina para personalizar toda tu semana o contactate con nosotros!'], $usuario->telf, 'es');
                    //dd($devolucion);
                } else {
                    $devolucion = WhatsappAPIHelper::enviarTemplate('delight_pedido_listo', [$diaPlan, $detalle->SOPA, $detalle->PLATO . '(' . $detalle->CARBOHIDRATO . ')', 'Carbohidrato: *' . $detalle->CARBOHIDRATO . '*', 'Metodo: *' . $detalle->ENVIO . '*', 'Ingresa a tu perfil en nuestra pagina para personalizar toda tu semana o contactate con nosotros!'], $usuario->telf, 'es');
                    //dd($devolucion);
                }
                if ($actualizarTicket->cantidad == 1) {
                    DB::table('whatsapp_plan_almuerzos')->where('id', $actualizarTicket->id)->delete();
                    //dd($actualizarTicket);

                } else {
                    DB::table('whatsapp_plan_almuerzos')->where('id', $actualizarTicket->id)->decrement('cantidad');
                    DB::table('whatsapp_plan_almuerzos')->where('id', $actualizarTicket->id)->update(['paso_segundo' => 0, 'paso_carbohidrato' => 0, 'paso_metodo_envio' => 0, 'paso_metodo_empaque' => 0]);
                    $devolucion =  WhatsappAPIHelper::enviarTemplate('delight_cantidad_planes_dia', [$diaPlan,$actualizarTicket->cantidad-1], $usuario->telf, 'es');
                    Artisan::command('whatsapp:enviarMenu');
                    // dd($devolucion);
                }
                break;
            default:
                # code...
                break;
        }
    }
    public static function seleccionarMenu($usuario, $numero, $paso)
    {
        //dd($usuario);
        if ($numero != '0') {
            $fechaManana = Carbon::parse(Carbon::now()->addDays(1))->format('Y-m-d');
            $diaPlan = WhatsappAPIHelper::saber_dia($fechaManana);
            $menuDiaActual = Almuerzo::where('dia', $diaPlan)->first();
            switch ($paso) {
                case '1':
                    if ($numero == "1") {
                        $segundo = $menuDiaActual->ejecutivo;
                    } else if ($numero == "2") {
                        $segundo = $menuDiaActual->dieta;
                    } else if ($numero == "3") {
                        $segundo = $menuDiaActual->vegetariano;
                    }

                    $array = array(
                        'SOPA' => $menuDiaActual->sopa,
                        'PLATO' => $segundo,
                        'ENSALADA' => $menuDiaActual->ensalada,
                        'CARBOHIDRATO' => '',
                        'JUGO' => $menuDiaActual->jugo,
                        'ENVIO' => '',
                        'EMPAQUE' => '',
                    );
                    $datosPlan = DB::table('plane_user')->where('start', $fechaManana)->where('user_id', $usuario->idUser)->where('estado', 'pendiente')->first();

                    DB::table('plane_user')->where('id', $datosPlan->id)->update(['detalle' => $array]);
                    DB::table('whatsapp_plan_almuerzos')->where('id', $usuario->idwhatsapp)->update(['paso_segundo' => true]);
                    AdminTicketsHelper::enviarMensajeConMenu($usuario, $paso, $menuDiaActual);
                    break;
                case '2':
                    if ($numero == "1") {
                        $carbo = $menuDiaActual->carbohidrato_1;
                    }
                    if ($numero == "2") {
                        $carbo = $menuDiaActual->carbohidrato_2;
                    }
                    if ($numero == "3") {
                        $carbo = $menuDiaActual->carbohidrato_3;
                    }

                    $datosPlan = DB::table('plane_user')->where('start', $fechaManana)->where('user_id', $usuario->idUser)->where('estado', 'pendiente')->first();
                    $array = json_decode($datosPlan->detalle);
                    $array->CARBOHIDRATO = $carbo;
                    DB::table('plane_user')->where('id', $datosPlan->id)->update(['detalle' => json_encode($array)]);
                    DB::table('whatsapp_plan_almuerzos')->where('id', $usuario->idwhatsapp)->update(['paso_carbohidrato' => true]);
                    AdminTicketsHelper::enviarMensajeConMenu($usuario, $paso, $menuDiaActual);
                    break;
                case '3':
                    if ($numero == "1") {
                        $despacho = Plane::ENVIO1;
                    }
                    if ($numero == "2") {
                        $despacho = Plane::ENVIO2;
                    }
                    if ($numero == "3") {
                        $despacho = Plane::ENVIO3;
                    }
                    $datosPlan = DB::table('plane_user')->where('start', $fechaManana)->where('user_id', $usuario->idUser)->where('estado', 'pendiente')->first();
                    $array = json_decode($datosPlan->detalle);
                    $array->ENVIO = $despacho;
                    //dd($array->ENVIO);
                    DB::table('whatsapp_plan_almuerzos')->where('id', $usuario->idwhatsapp)->update(['paso_metodo_envio' => true]);
                    if ($array->ENVIO != Plane::ENVIO1) {
                        AdminTicketsHelper::enviarMensajeConMenu($usuario, $paso, $menuDiaActual);
                        DB::table('plane_user')->where('id', $datosPlan->id)->update(['detalle' => json_encode($array)]);
                    } else {
                        DB::table('whatsapp_plan_almuerzos')->where('id', $usuario->idwhatsapp)->update(['paso_metodo_empaque' => true]);
                        DB::table('plane_user')->where('id', $datosPlan->id)->update(['detalle' => json_encode($array), 'estado' => Plane::ESTADOFINALIZADO,'color'=>Plane::COLORFINALIZADO]);
                        AdminTicketsHelper::enviarMensajeConMenu($usuario, '4', $menuDiaActual);
                    }
                    break;
                case '4':
                    $datosPlan = DB::table('plane_user')->where('start', $fechaManana)->where('user_id', $usuario->idUser)->where('estado', 'pendiente')->first();
                    
                    $array = json_decode($datosPlan->detalle);

                    if ($numero == "1") {
                        $empaque = 'Vianda';
                    } else if ($numero == "2") {
                        $empaque = 'Empaque Bio(apto/microondas)';
                    } else {
                        $empaque = "";
                    }
                   
                    $array->EMPAQUE = $empaque;

                    DB::table('plane_user')->where('id', $datosPlan->id)->update(['detalle' => json_encode($array), 'estado' => Plane::ESTADOFINALIZADO,'color'=>Plane::COLORFINALIZADO]);
                    DB::table('whatsapp_plan_almuerzos')->where('id', $usuario->idwhatsapp)->update(['paso_metodo_empaque' => true]);
                    AdminTicketsHelper::enviarMensajeConMenu($usuario, $paso, $menuDiaActual);

                    break;
                default:

                    break;
            }
        } else {
        }
    }
}
