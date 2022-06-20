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
                
                break;
            case '2':

                break;
            case '3':

                break;
            case '0':

                break;
            default:

                break;
        }
    }
    
}
