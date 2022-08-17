<?php

use App\Helpers\AdminTicketsHelper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Almuerzo;
use Illuminate\Http\Request;
use App\Helpers\WhatsappAPIHelper;
use App\Models\WhatsappLog;
use Illuminate\Support\Facades\DB;
use App\Models\WhatsappPlanAlmuerzo;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pruebas', function (Request $request) {

    // $clientesConPlan = DB::table('plane_user')->select(
    //     'plane_user.*',
    //     'users.name',
    //     'planes.nombre'
    // )->leftjoin('users', 'users.id', 'plane_user.user_id')
    //     ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
    //     ->where('estado', 'pendiente')
    //     ->where('plane_user.detalle',null)
    //     ->whereDate('start', '2022-06-23')->get();
    //     dd($clientesConPlan);
    AdminTicketsHelper::calcular('75140175','0','0c3982f00ec0416081a7b98e5d294e59','dia');
});
Route::post('/pruebas/webhook', function (Request $request) {

    //AdminTicketsHelper::calcular('75140175','1');
    $json = json_decode(json_encode($request->all()));
    //$cuerpo = $json->message->content;
    WhatsappLog::create([
        'titulo' => 'webhook delight',
        'log' => json_encode($request->all())
    ]);
});

Route::get('/pruebas/mensaje', function () {
    if (date('H') <= 23 && date('H') >= 18)
    {
       dd('noche');
    }
    else if (date('H') < 10)
    {
        dd('dia');
    }
    else
    {
        dd(date('H')); //WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Hola! estas fuera del horario de atencion virtual, intentalo mas tarde!');

    }
    
});


Route::post('/circuito/delight/planes', function (Request $request) {

    try {

        $json = json_decode(json_encode($request->all()));
        if (isset($json->message->from)) { //si viene de un cliente y no de la api o numero origen wp business
            $contenido = $json->message->content->text;
            $idConversacion = $json->conversation->id;
            $numeroDestino = $json->message->to;
            $numeroOrigen = substr($json->message->from, -8);
            $estadoMensaje = $json->message->status; //si es pending algo genero error, received es correcto
            $tipo = $json->message->type;

            if ($tipo == 'text') { //si es texto lo que envia
                preg_match_all('!\d+!', $contenido, $matches); //matches es un array que obtiene numeros dentro del cuerpo del mensaje recibido
                if (count($matches[0]) > 0) {
                    foreach ($matches[0] as $numero) {
                        if (date('H') <= 23 && date('H') >= 18)
                        {
                            AdminTicketsHelper::calcular($numeroOrigen,$numero,$idConversacion,'noche');
                        }
                        else if (date('H') < 16)
                        {
                            AdminTicketsHelper::calcular($numeroOrigen,$numero,$idConversacion,'dia');
                        }
                        else
                        {
                            WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Hola! estas fuera del horario de atencion virtual, intentalo mas tarde!');

                        }
                        
                        break;
                    }
                } else {
                    WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Solo puedes responder con numeros que correspondan a una de las opciones propuestas...');
                }
            } else {
                WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Para interactuar con el asistente solo puedes responder con mensajes de texto');
            }
        }
    } catch (\Throwable $th) {
        WhatsappLog::create([
            'titulo' => 'mensaje catch',
            'log' => $th->getMessage()
        ]);
        WhatsappLog::create([
            'titulo' => 'webhook delight catch',
            'log' => json_encode($request->all())
        ]);
    }
});
