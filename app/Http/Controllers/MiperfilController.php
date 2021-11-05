<?php

namespace App\Http\Controllers;

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
        
       
        return view('client.miperfil.index',compact('usuario','planes'));
    }
    public function calendario(Plane $plan, User $usuario){
       $coleccion=collect();
       $planes=$usuario->planes->where('id',$plan->id)->sortBy(function($col) {return $col;})->take(5);
       foreach($planes as $dias){
        $menusemanal=Almuerzo::where('dia',$this->saber_dia($dias->pivot->start))->first();  
        
        $coleccion->push(['detalle'=>$dias->pivot->detalle,'dia'=>$this->saber_dia($dias->pivot->start),'id'=>$dias->pivot->id,'fecha'=>date('d-M', strtotime($dias->pivot->start)),'detalle'=>$dias->pivot->detalle,
                        'sopa'=>$menusemanal->sopa,'ensalada'=>$menusemanal->ensalada,'ejecutivo'=>$menusemanal->ejecutivo,
                        'dieta'=>$menusemanal->dieta,'vegetariano'=>$menusemanal->vegetariano,'carbohidrato_1'=>$menusemanal->carbohidrato_1,
                        'carbohidrato_2'=>$menusemanal->carbohidrato_2,'carbohidrato_3'=>$menusemanal->carbohidrato_3,'jugo'=>$menusemanal->jugo
                        ]);
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
        if($request[$plato] && $request[$carbohidrato])
        {
            $array=array('SOPA'=>$request->sopa,'PLATO'=>$request[$plato],'CARBOHIDRATO'=>$request[$carbohidrato],'JUGO'=>$request->jugo);
           
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
}
