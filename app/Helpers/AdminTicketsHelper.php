<?php

namespace App\Helpers;

use App\Models\WhatsappPlanAlmuerzo;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;



class AdminTicketsHelper
{
    public static function calcular($telefono,$numero)
    {
        $buscarUsuario = DB::table('users')->select(
            'users.*',
            'whatsapp_plan_almuerzos.id as idwhatsapp',
            'whatsapp_plan_almuerzos.*'
            )
            ->leftjoin('whatsapp_plan_almuerzos', 'whatsapp_plan_almuerzos.cliente_id', 'users.id')
            ->where('users.telf', $telefono)  
            ->first();
        if($buscarUsuario)
        {
            if($buscarUsuario->paso_segundo==false)
            {
                DB::table('whatsapp_plan_almuerzos')->where('id',$buscarUsuario->idwhatsapp)->update(['paso_segundo'=>true]);
                AdminTicketsHelper::seleccionarMenu($numero);
            }
            elseif($buscarUsuario->paso_carbohidrato==false)
            {
                DB::table('whatsapp_plan_almuerzos')->where('id',$buscarUsuario->idwhatsapp)->update(['paso_carbohidrato'=>true]);
            }
            elseif($buscarUsuario->paso_metodo_envio==false)
            {
                DB::table('whatsapp_plan_almuerzos')->where('id',$buscarUsuario->idwhatsapp)->update(['paso_metodo_envio'=>true]);
            }
            elseif($buscarUsuario->paso_metodo_empaque==false)
            {
                DB::table('whatsapp_plan_almuerzos')->where('id',$buscarUsuario->idwhatsapp)->update(['paso_metodo_empaque'=>true]);
            }
        }
        else
        {
            WhatsappAPIHelper::enviarMensajePersonalizado('0c3982f00ec0416081a7b98e5d294e59', 'text', 'Hola! No te encuentras registrado en ningun plan, probablemente se modifico tu plan o no te encuentras registrado en ninguno, contactate con soporte para mas informacion!');
    
        }
        dd($buscarUsuario);
    }

    public static function seleccionarMenu($numero)
    {
        switch ($numero) {
            case '1':
                $buscarUsuario = DB::table('users')->select(
                    'users.*',
                    'plane_user.*',
                    'whatsapp_plan_almuerzos.*'
                )
                    ->leftjoin('plane_user', 'plane_user.user_id', 'users.id')
                    ->leftjoin('whatsapp_plan_almuerzos', 'whatsapp_plan_almuerzos.cliente_id', 'users.id')
                    ->where('users.telf', $numeroOrigen)->first();
                if ($buscarUsuario) {
                } else {
                    WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Tu plan para este dia cambio de estado y ya no se encuentra disponible, contactate al +59178227629 para mas detalles y consultas.');
                    WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'No te olvides que estas conversando con un asistente virtual, no contesta a respuestas personalizadas');
                }
                break;
            case '2':

                break;
            case '3':

                break;
            case '0':

                break;
            default:
                WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Marca una respuesta correcta');

                break;
        }
    }
    
}
