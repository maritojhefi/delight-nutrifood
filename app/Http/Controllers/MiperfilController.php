<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use App\Models\Almuerzo;
use App\Helpers\CreateList;
use App\Models\PerfilPunto;
use App\Models\SwitchPlane;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use App\Helpers\GlobalHelper;
use App\Helpers\ProcesarImagen;
use App\Events\CocinaPedidoEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class MiperfilController extends Controller
{
    public function actualizarNacimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'fecha' => 'required|date',
        ]);
        if ($validator->fails()) {
            return 'fallo';
        } else {
            $usuario = User::find(auth()->user()->id);
            $usuario->nacimiento = $request->fecha;
            $usuario->save();
            return 'exito';
        }
    }
    public function revisarWhatsappAsistente()
    {
        if (Auth::check()) {
            return auth()->user()->whatsapp_plan;
        } else {
            return false;
        }
    }
    public function cambiarEstadoWhatsappAsistente()
    {
        if (Auth::check()) {
            $user = User::find(auth()->user()->id);
            if ($user->whatsapp_plan) {
                $user->whatsapp_plan = false;
                $user->save();
                return false;
            } else {
                $user->whatsapp_plan = true;
                $user->save();
                return true;
            }
        } else {
            return null;
        }
    }
    public function menu()
    {
        return view('client.miperfil.menu');
    }
    public function misPlanes()
    {
        if (auth()->user() != null) {
            $usuario = User::find(auth()->user()->id);
            $planes = CreateList::crearlistaplan($usuario->id);
            // $subcategoria = Subcategoria::find(1);
            $planesTodos = Plane::all();
        }
        return view('client.miperfil.planes', compact('planes', 'planesTodos', 'usuario'));
    }
    public function index()
    {
        $usuario = '';
        $planes = '';
        if (auth()->user() != null) {
            $usuario = User::find(auth()->user()->id);
            $planes = CreateList::crearlistaplan($usuario->id);
        }
        $fotoMenu = Almuerzo::withoutGlobalScope('diasActivos')->find(1);
        return view('client.miperfil.index', compact('usuario', 'planes', 'fotoMenu'));
    }

    public function calendario(Plane $plan, User $usuario, Request $request)
    {
        // dd($usuario);
        // dd(auth()->user()->role_id);
        if ($usuario->id != auth()->user()->id && auth()->user()->role_id != 1) {
            return back();
        }

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
        //dd($planes);
        $menusemanal = "";
        $estadoMenu = SwitchPlane::find(1);

        $primer_dia = null;
        $esProximo = true;

        foreach ($planes as $dias) {
            if (date('y-m-d', strtotime($dias->pivot->start)) <= $fechalimite && date('y-m-d', strtotime($dias->pivot->start)) >= $fechaactual) {

                $dia = $this->saber_dia($dias->pivot->start);

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
                    'dia' => $dia,
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
    public function saber_dia($nombredia)
    {

        $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }
    public function personalizardia(Request $request)
    {
        // dd($request);
        $switcher = SwitchPlane::find(1);
        if ($switcher->activo == false) {
            return redirect()->route('calendario.cliente', [$request->plan, auth()->user()->id])
                ->with('error', 'El menu se encuentra cerrado!');
        }
        $plan = Plane::find($request->plan);
        $plato = 'plato' . $request->id;
        $carbohidrato = 'carb' . $request->id;
        $envio = 'envio' . $request->id;
        $empaque = 'empaque' . $request->id;
        if ($plan->sopa) {
            $request->validate(['sopa' => 'required']);
            $varSopa = $request->sopa;
        } else $varSopa = '';

        if ($plan->segundo) {
            $request->validate([$plato => 'required']);
            $varSegundo = $request[$plato];
        } else $varSegundo = '';

        if ($plan->carbohidrato) {
            $request->validate([$carbohidrato => 'required']);
            $varCarbo = $request[$carbohidrato];
        } else $varCarbo = '';

        if ($plan->ensalada) {
            $request->validate(['ensalada' => 'required']);
            $varEnsalada = $request->ensalada;
        } else $varEnsalada = '';

        if ($plan->jugo) {
            $request->validate(['jugo' => 'required']);
            $varJugo = $request->jugo;
        } else $varJugo = '';
        $request->validate([
            $envio => 'required',
            $empaque => 'required'
        ]);
        $array = GlobalHelper::menuDiarioArray($varSopa, $varSegundo, $varEnsalada, $varCarbo, $varJugo, $request[$envio], $request[$empaque]);


        $dia = DB::table('plane_user')->where('id', $request->id)->first();
        if ($dia->estado == "pendiente") {
            DB::table('plane_user')->where('id', $request->id)->update(['detalle' => $array]);
            $registro =  DB::table('plane_user')->where('id', $request->id)->first();
            // dd($plan, $request, $usuario);
            // dd($registro->user_id);
            return redirect()->route('calendario.cliente', [$request->plan, $registro->user_id])
                ->with('success', 'Dia ' . $request->dia . ' guardado!');
        } else {
            return redirect()->route('calendario.cliente', [$request->plan, auth()->user()->id])
                ->with('error', 'Este dia ya no se encuentra en su plan!');
        }
    }

    public function editardia($idpivot)
    {
        $dia = DB::table('plane_user')->where('id', $idpivot)->first();
        
        if ($dia->estado == "pendiente") {
            DB::table('plane_user')
                ->where('id', $idpivot)
                ->update(['detalle' => null, 'whatsapp' => false]);

            $url = route('calendario.cliente', [
                'plan' => $dia->plane_id,
                'usuario' => $dia->user_id
            ]) . '?pedido=' . $idpivot;

            return redirect($url);
            
        } else {
            return back()->with('error', 'Este dia ya no se encuentra disponible!');
        }
    }

    public function revisarPerfil()
    {
        $usuario = User::find(auth()->user()->id);
        return view('client.miperfil.edit', compact('usuario'));
    }

    public function guardarPerfilFaltante(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->idUsuario,
            'direccion' => 'required|min:15',
            'password' => 'required|string|min:5',
            'telf' => 'required|size:8|unique:users,telf,' . $request->idUsuario,
            'latitud' => 'required|string|min:10',
            'longitud' => 'required|string|min:10',
        ]);
        $usuario = User::find(auth()->user()->id);

        $usuario->fill($request->all());
        $usuario->save();
        return back()->with('success', 'Gracias! Ya tienes tu perfil completo y actualizado');
    }
    public function subirFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|mimes:jpeg,bmp,png,gif|max:10240'
        ]);

        $user = User::find(auth()->user()->id);

        try {
            // Usar el helper ProcesarImagen para procesar y guardar la imagen
            $procesarImagen = ProcesarImagen::crear($request->foto)
                ->carpeta(User::RUTA_FOTO) // Carpeta donde se guardará
                ->dimensiones(320, null) // Redimensionar a máximo 320px de ancho
                ->formato($request->foto->getClientOriginalExtension()); // Mantener formato original

            // Si el usuario ya tiene una foto, usar el mismo nombre
            if ($user->foto) {
                $nombreSinExtension = pathinfo($user->foto, PATHINFO_FILENAME);
                $procesarImagen->nombreArchivo($nombreSinExtension);
            }

            // Guardar la imagen procesada (automáticamente usa el disco correcto según el ambiente)
            $nombreArchivo = $procesarImagen->guardar();

            // Actualizar solo el nombre del archivo en la base de datos
            $user->foto = $nombreArchivo;
            $user->save();

            // Log de la ubicación donde se guardó la imagen
            $disco = GlobalHelper::discoArchivos();
            if ($disco === 's3') {
                $config = config('filesystems.disks.s3');
                $bucket = $config['bucket'] ?? '';
                $region = $config['region'] ?? 'us-east-1';
                $urlCompleta = "https://{$bucket}.s3.{$region}.amazonaws.com" . User::RUTA_FOTO . $nombreArchivo;
                Log::info("Foto de perfil guardada en S3", [
                    'usuario_id' => $user->id,
                    'nombre_archivo' => $nombreArchivo,
                    'url_s3' => $urlCompleta,
                    'disco' => $disco
                ]);
            } else {
                $rutaLocal = public_path('imagenes/perfil/' . $nombreArchivo);
                Log::info("Foto de perfil guardada en local", [
                    'usuario_id' => $user->id,
                    'nombre_archivo' => $nombreArchivo,
                    'ruta_local' => $rutaLocal,
                    'disco' => $disco
                ]);
            }

            return back()->with('actualizado', 'Se actualizó tu foto de perfil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir la foto: ' . $e->getMessage());
        }
    }



    public function enlacePatrocinador($id)
    {
        $usuario = User::find($id);
        $ruta = URL::to('/register?ref=');
        $ruta = $ruta . sprintf(PerfilPunto::CODIGO_PATROCINADOR . "%06d", $usuario->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Enlace de patrocinador copiado correctamente',
            'enlace' => $ruta,
        ], 200);
    }
}
