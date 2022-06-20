<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use App\Models\Almuerzo;
use App\Helpers\CreateList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MiperfilController extends Controller
{

    public function index(){
        $usuario='';
        $planes='';
        if(auth()->user()!=null)
        {
            $usuario=User::find(auth()->user()->id);
            $planes=CreateList::crearlistaplan($usuario->id);
            
        }
        $fotoMenu=Almuerzo::find(1);
       
        return view('client.miperfil.index',compact('usuario','planes','fotoMenu'));
    }
    public function calendario(Plane $plan, User $usuario){
       $coleccion=collect();
       $fechaactual=Carbon::now()->format('y-m-d');
       $fechalimite=date("y-m-d", strtotime("next sunday"));
      
       $array=array();
       $lunes=false;
       $planes=$usuario->planesPendientes->where('id',$plan->id)->sortBy(function($col) {return $col;})->take(5);
       $menusemanal="";
       foreach($planes as $dias){
           
            if(date('y-m-d', strtotime($dias->pivot->start))<=$fechalimite && date('y-m-d', strtotime($dias->pivot->start))>=$fechaactual)
            {
             $menusemanal=Almuerzo::where('dia',$this->saber_dia($dias->pivot->start))->first();  
         
             $coleccion->push([
                 'detalle'=>$dias->pivot->detalle,
                 'dia'=>$this->saber_dia($dias->pivot->start),
                 'id'=>$dias->pivot->id,
                 'fecha'=>date('d-M', strtotime($dias->pivot->start)),
                 'sopa'=>$menusemanal->sopa,
                 'ensalada'=>$menusemanal->ensalada,
                 'ejecutivo'=>$menusemanal->ejecutivo,
                 'dieta'=>$menusemanal->dieta,
                 'vegetariano'=>$menusemanal->vegetariano,
                 'carbohidrato_1'=>$menusemanal->carbohidrato_1,
                 'carbohidrato_2'=>$menusemanal->carbohidrato_2,
                 'carbohidrato_3'=>$menusemanal->carbohidrato_3,
                 'jugo'=>$menusemanal->jugo,
                 'envio1'=>'Para Mesa',
                 'envio2'=>'Para llevar(Paso a recoger)',
                 'envio3'=>'Delivery',
                 'empaque1'=>'Vianda',
                 'empaque2'=>'Eco-Empaque Delight',
                            ]);
             
            }
           
       }
       
        return view('client.miperfil.calendario',compact('plan','usuario','coleccion','menusemanal'));
    }
    public function saber_dia($nombredia) {
        
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }
    public function personalizardia(Request $request){
        $plato='plato'.$request->id;
        $carbohidrato='carb'.$request->id;
        $envio='envio'.$request->id;
        $empaque='empaque'.$request->id;
        if($request[$plato] && $request[$carbohidrato] && $request[$envio] && $request[$empaque])
        {
            $array=array(
            'SOPA'=>$request->sopa,
            'PLATO'=>$request[$plato],
            'ENSALADA'=>$request->ensalada,
            'CARBOHIDRATO'=>$request[$carbohidrato],
            'JUGO'=>$request->jugo,
            'ENVIO'=>$request[$envio],
            'EMPAQUE'=>$request[$empaque],
        );
           
            DB::table('plane_user')->where('id',$request->id)->update(['detalle'=>$array]);
            return back()->with('success','Dia '.$request->dia.' guardado!');
        }
        else{
            return back()->with('danger','Rellene bien los campos del dia '.$request->dia);
        }
    }
    public function editardia($idpivot){
        DB::table('plane_user')->where('id',$idpivot)->update(['detalle'=>null]);
        return back()->with('success','Ya puede editar este dia!');
    }

    public function revisarPerfil()
    {
        $usuario=User::find(auth()->user()->id);
        return view('client.miperfil.edit',compact('usuario'));
    }

    public function guardarPerfilFaltante(Request $request)
    {
        
        $request->validate([    
        'email' => 'required|email|unique:users,email,'.$request->idUsuario,
        'direccion' => 'required|min:15',
        'nacimiento'=>'required|date',
        'telf'=>'required|size:8|unique:users,telf,'.$request->idUsuario,
        'latitud'=>'required|string|min:10',
        'longitud'=>'required|string|min:10',
        ]);
        $usuario=User::find(auth()->user()->id);

        $usuario->fill($request->all());
        $usuario->save();
        return back()->with('success','Gracias! Ya tienes tu perfil completo y actualizado');
    }
}
