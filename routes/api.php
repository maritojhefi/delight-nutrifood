<?php

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


Route::post('/pruebas/webhook', function (Request $request) {


    $json = json_decode(json_encode($request->all()));
    //$cuerpo = $json->message->content;
    WhatsappLog::create([
        'titulo' => 'webhook delight',
        'log' => json_encode($request->all())
    ]);
});



Route::post('/circuito/delight/planes', function (Request $request) {

    try {

        $json = json_decode(json_encode($request->all()));
        if (isset($json->message->from)) {//si viene de un cliente y no de la api o numero origen wp business
            $contenido = $json->message->content->text;
            $idConversacion = $json->conversation->id;
            $numeroDestino = $json->message->to;
            $numeroOrigen = $json->message->from;
            $estadoMensaje = $json->message->status; //si es pending algo genero error, received es correcto
            $tipo = $json->message->type;
            
            if ($tipo == 'text') {//si es texto lo que envia
                preg_match_all('!\d+!', $contenido, $matches); //matches es un array que obtiene numeros dentro del cuerpo del mensaje recibido
                if (count($matches[0]) > 0) {
                    foreach ($matches[0] as $numero) {
                        switch ($numero) {
                            case '1':
                                $usuarioCliente = WhatsappPlanAlmuerzo::where('cliente_id', '!=', null)->first();
                                break;
                            case '0':

                                break;
                            default:
                                WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Marca una respuesta correcta');

                                break;
                        }
                    }
                } else {
                    WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Marca una respuesta correcta');
                }
            }
            else
            {
                WhatsappAPIHelper::enviarMensajePersonalizado($idConversacion, 'text', 'Envia una respuesta valida');
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
