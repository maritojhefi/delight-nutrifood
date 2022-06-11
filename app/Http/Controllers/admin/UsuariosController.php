<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Plane;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UsuariosController extends Controller
{
    public function detalleplan($id, $planid)
    {
        $usuario=User::find($id);
        $plan=Plane::find($planid);
        return view('admin.usuarios.detalleplan', compact('usuario','plan'));
    }
    public function editar($id)
    {
        $evento=DB::table('plane_user')->where('id',$id)->first();
        
        return response()->json($evento);
    }
    public function borrar($id)
    {
       $evento= DB::table('plane_user')->where('id',$id)->delete();
       return response()->json($evento);
    }
    public function agregar(Request $request)
    {
        
        $feriados=DB::table('plane_user')->select('start')->where('title','feriado')->get();
        $listafechas=array();
        foreach(json_decode($feriados,true) as $fecha)
        {
            foreach($fecha as $fe)
            {
                array_push($listafechas, $fe);
            }
        }
        $dias=$this->getDiasHabiles($request->start,$request->end,$listafechas);
        foreach($dias as $dia){
            DB::table('plane_user')->insert([
                
                'start' => $dia,
                'end'=>$dia,
                'title'=>$request->plan,
                'plane_id'=>$request->idplan,
                'user_id'=>$request->iduser
            ]);
        }
    }
    public function feriado(Request $request)
    {
       
        $dias=$this->getDiasHabiles($request->start,$request->end,[]);
        
        foreach($dias as $dia){
            $siexiste=DB::table('plane_user')->where('start',$dia)->where('title','feriado')->get();
            if($siexiste->count()!=0)
            {
               
            }
            else{
                DB::table('plane_user')->insert([
                    'color' => '#c01222',
                    'start' => $dia,
                    'end'=>$dia,
                    'title'=>'feriado',
                   
                ]);
            }
            
        }
    }
    public function permiso($id)
    {
       $evento= DB::table('plane_user')->where('id',$id)->update(['estado'=>'permiso','color'=>'#A314CD']);
       return response()->json($evento);
    }
    public function quitarpermiso($id)
    {
       $evento= DB::table('plane_user')->where('id',$id)->update(['estado'=>'pendiente','color'=>'#20C995']);
       return response()->json($evento);
    }
    public function mostrar($idplan,$iduser)
    {
        
        $eventos=DB::table('plane_user')->where('plane_id',$idplan)->where('user_id',$iduser)->get();
        $feriados=DB::table('plane_user')->where('title','feriado')->get();
        
        foreach($feriados as $feriado)
        {
            $eventos->push($feriado);
        }
       
        
        return response()->json($eventos);
    }



    public function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array()) {
        // Convirtiendo en timestamp las fechas
        $fechainicio = strtotime($fechainicio);
        $fechafin = strtotime($fechafin);
       
        // Incremento en 1 dia
        $diainc = 24*60*60;
       
        // Arreglo de dias habiles, inicianlizacion
        $diashabiles = array();
       
        // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
        for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
                // Si el dia indicado, no es sabado o domingo es habil
                if (!in_array(date('N', $midia), array(7))) { // DOC: http://www.php.net/manual/es/function.date.php
                        // Si no es un dia feriado entonces es habil
                        if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                                array_push($diashabiles, date('Y-m-d', $midia));
                        }
                }
        }
       
        return $diashabiles;
    }
}
