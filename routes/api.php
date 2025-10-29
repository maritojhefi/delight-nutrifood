<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Saldo;
use App\Models\Almuerzo;
use App\Models\WhatsappLog;
use Illuminate\Http\Request;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use App\Helpers\AdminTicketsHelper;
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

// ========================================
// API REST DE VENTAS
// ========================================
Route::middleware('auth')->group(function () {

    // Rutas principales de ventas
    Route::apiResource('ventas', App\Http\Controllers\Api\VentaController::class);

    // Verificar estado de venta (para multi-usuario)
    Route::get('ventas/{venta}/check', function (App\Models\Venta $venta) {
        return response()->json([
            'exists' => true,
            'pagado' => $venta->pagado,
            'updated_at' => $venta->updated_at,
        ]);
    });

    // Rutas específicas de ventas
    Route::patch('ventas/{venta}/descuento', [App\Http\Controllers\Api\VentaController::class, 'updateDescuento']);
    Route::patch('ventas/{venta}/cliente', [App\Http\Controllers\Api\VentaController::class, 'updateCliente']);
    Route::patch('ventas/{venta}/usuario-manual', [App\Http\Controllers\Api\VentaController::class, 'updateUsuarioManual']);
    Route::post('ventas/{venta}/enviar-cocina', [App\Http\Controllers\Api\VentaController::class, 'enviarCocina']);
    Route::post('ventas/{venta}/cobrar', [App\Http\Controllers\Api\VentaController::class, 'cobrar']);
    Route::post('ventas/{venta}/cerrar', [App\Http\Controllers\Api\VentaController::class, 'cerrar']);

    // Rutas para productos en ventas
    Route::post('ventas/{venta}/productos', [App\Http\Controllers\Api\ProductoVentaController::class, 'store']);
    Route::delete('ventas/{venta}/productos/eliminar-uno', [App\Http\Controllers\Api\ProductoVentaController::class, 'eliminarUno']);
    Route::delete('ventas/{venta}/productos', [App\Http\Controllers\Api\ProductoVentaController::class, 'destroy']);
    Route::post('ventas/{venta}/productos/adicional', [App\Http\Controllers\Api\ProductoVentaController::class, 'agregarAdicional']);
    Route::delete('ventas/{venta}/productos/item', [App\Http\Controllers\Api\ProductoVentaController::class, 'eliminarItem']);
    Route::patch('ventas/{venta}/productos/observacion', [App\Http\Controllers\Api\ProductoVentaController::class, 'guardarObservacion']);
    Route::post('ventas/{venta}/productos/desde-plan', [App\Http\Controllers\Api\ProductoVentaController::class, 'agregarDesdeplan']);

    // Rutas para saldos
    Route::post('ventas/{venta}/saldos', [App\Http\Controllers\Api\SaldoController::class, 'store']);
    Route::patch('saldos/{saldo}/anular', [App\Http\Controllers\Api\SaldoController::class, 'anular']);
    Route::get('ventas/{venta}/saldos/maximo-descuento', [App\Http\Controllers\Api\SaldoController::class, 'maximoDescuento']);
    Route::post('ventas/{venta}/saldos/validar-descuento', [App\Http\Controllers\Api\SaldoController::class, 'validarDescuento']);
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
                        if (date('H') <= 23 && date('H') >= 18) {
                            AdminTicketsHelper::calcular($numeroOrigen, $numero, $idConversacion, 'noche');
                        } else if (date('H') < 10) {
                            AdminTicketsHelper::calcular($numeroOrigen, $numero, $idConversacion, 'dia');
                        } else {
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
