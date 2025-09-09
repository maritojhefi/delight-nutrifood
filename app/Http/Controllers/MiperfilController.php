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
use App\Events\CocinaPedidoEvent;
use Illuminate\Support\Facades\DB;
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
    public function calendario(Plane $plan, User $usuario)
    {
        if ($usuario->id != auth()->user()->id) {

            return back();
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
        foreach ($planes as $dias) {

            if (date('y-m-d', strtotime($dias->pivot->start)) <= $fechalimite && date('y-m-d', strtotime($dias->pivot->start)) >= $fechaactual) {

                $menusemanal = Almuerzo::where('dia', $this->saber_dia($dias->pivot->start))->first();
                if (!$menusemanal) {
                    continue;
                }
                $coleccion->push([
                    'detalle' => $dias->pivot->detalle,
                    'estado' => $dias->pivot->estado,
                    'dia' => $this->saber_dia($dias->pivot->start),
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
                ]);
            }
        }

        return view('client.miperfil.calendario', compact('plan', 'usuario', 'coleccion', 'menusemanal', 'estadoMenu'));
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
            return back()->with('error', 'El menu se encuentra cerrado!');
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
            return back()->with('success', 'Dia ' . $request->dia . ' guardado!');
        } else {
            return back()->with('error', 'Este dia ya no se encuentra en su plan!');
        }
    }
    public function editardia($idpivot)
    {
        $dia = DB::table('plane_user')->where('id', $idpivot)->first();
        if ($dia->estado == "pendiente") {
            DB::table('plane_user')->where('id', $idpivot)->update(['detalle' => null, 'whatsapp' => false]);
            return back()->with('success', 'Ya puede editar este dia!');
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
        if ($user->foto) {
            $filename = $user->foto;
        } else {
            $filename = time() . "." . $request->foto->extension();
        }
        //$this->foto->move(public_path('imagenes'),$filename);
        $request->foto->storeAs('perfil', $filename, 'public_images');
        //comprimir la foto
        $img = Image::make('imagenes/perfil/' . $filename);
        $img->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->rotate(0);
        $img->save('imagenes/productos/' . $filename);
        $user->foto = $filename;
        $user->save();
        return back()->with('actualizado', 'Se actualizo tu foto de perfil!');
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
