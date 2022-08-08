<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use App\Models\Almuerzo;
use App\Models\SwitchPlane;
use Illuminate\Http\Request;
use App\Helpers\GlobalHelper;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UsuariosController extends Controller
{
    
    public function editarPlanUsuario($plan, $usuario){
        $plan=Plane::find((int)$plan);
        $usuario=User::find((int)$usuario);
       // dd($usuario);
       $coleccion=collect();
       $fechaactual=Carbon::now()->format('y-m-d');
       $fechalimite=date("y-m-d", strtotime("next sunday"));
      //dd($fechalimite);
       $array=array();
       $lunes=false;
       $planes=$usuario->planesPendientes->where('id',$plan->id)->sortBy(function($col) {return $col;});
       $estadoMenu=SwitchPlane::find(1);
       $menusemanal="";
       foreach($planes as $dias){
           
            if(date('y-m-d', strtotime($dias->pivot->start))<=$fechalimite && date('y-m-d', strtotime($dias->pivot->start))>=$fechaactual)
            {
            
             $menusemanal=Almuerzo::where('dia',WhatsappAPIHelper::saber_dia($dias->pivot->start))->first();  
         
             $coleccion->push([
                 'detalle'=>$dias->pivot->detalle,
                 'dia'=>WhatsappAPIHelper::saber_dia($dias->pivot->start),
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
                 'envio1'=>Plane::ENVIO1,
                 'envio2'=>Plane::ENVIO2,
                 'envio3'=>Plane::ENVIO3,
                 'empaque1'=>'Vianda',
                 'empaque2'=>'Empaque Bio(apto/microondas)',
                            ]);
             
            }
           
       }
      
        return view('client.miperfil.calendario',compact('plan','usuario','coleccion','menusemanal','estadoMenu'));
    }
    public function saldo()
    {
        $usuario=User::find(auth()->user()->id);
        return view('client.miperfil.saldo',compact('usuario'));
    }
    public function detalleplan($id, $planid)
    {
        $usuario = User::find($id);
        $plan = Plane::find($planid);
        return view('admin.usuarios.detalleplan', compact('usuario', 'plan'));
    }
    public function editar($id)
    {
        $evento = DB::table('plane_user')->where('id', $id)->first();

        return response()->json($evento);
    }
    public function borrar($id)
    {
        //$evento = DB::table('plane_user')->where('id', $id)->first();
        $evento = DB::table('plane_user')->where('id', $id)->delete();
        return response()->json('si');
        // if($evento->detalle==null)
        // {
        //     $evento = DB::table('plane_user')->where('id', $id)->delete();
           
        // }
        // else
        // {
        //     return response()->json('no');
        // }

        
    }
    public function archivar($id)
    {
       $registro= DB::table('plane_user')->select('plane_user.*')->where('id', $id)->first();
       DB::table('plane_user')->where('plane_id',$registro->plane_id)->where('user_id',$registro->user_id)->where('estado',Plane::ESTADOFINALIZADO)->whereDate('start','<',Carbon::parse($registro->start)->addDay())->update(['estado'=>Plane::ESTADOARCHIVADO,'color'=>Plane::COLORARCHIVADO]);
       return 'exito';
    }
    public function agregar(Request $request)
    {
       
        $feriados = DB::table('plane_user')->select('start')->where('title', 'feriado')->get();
        $listafechas = array();
        foreach (json_decode($feriados, true) as $fecha) {
            foreach ($fecha as $fe) {
                array_push($listafechas, $fe);
            }
        }
        $excluirSabados=false;
        if(isset($request->sabados))
        {
            $excluirSabados=true;
        }
        if($request->dias==null || $request->dias=="" || $request->dias==0)
        {
            $dias = $this->getDiasHabiles($request->start, $request->end, $listafechas);
            foreach ($dias as $dia) {
                if($excluirSabados)
                {
                    $saberDia = WhatsappAPIHelper::saber_dia($dia);
                    if($saberDia!="Sabado")
                    {
                        DB::table('plane_user')->insert([
    
                            'start' => $dia,
                            'end' => $dia,
                            'title' => $request->plan,
                            'plane_id' => $request->idplan,
                            'user_id' => $request->iduser
                        ]);
                    }
                }
                else
                {
                    DB::table('plane_user')->insert([
    
                        'start' => $dia,
                        'end' => $dia,
                        'title' => $request->plan,
                        'plane_id' => $request->idplan,
                        'user_id' => $request->iduser
                    ]);
                }
                
            }
        }
        else
        {
            $fin=Carbon::parse(Carbon::create($request->start)->addDays($request->dias*2))->format('Y-m-d');
            $dias = $this->getDiasHabiles($request->start, $fin, $listafechas);
            $contador=0;
            //dd($request->start);
            foreach ($dias as $dia) {
                if($excluirSabados)
                {
                    $saberDia = WhatsappAPIHelper::saber_dia($dia);
                    if($saberDia!="Sabado")
                    {
                        DB::table('plane_user')->insert([
    
                            'start' => $dia,
                            'end' => $dia,
                            'title' => $request->plan,
                            'plane_id' => $request->idplan,
                            'user_id' => $request->iduser
                        ]);
                        $contador++;
                    }
                }
                else
                {
                    DB::table('plane_user')->insert([
    
                        'start' => $dia,
                        'end' => $dia,
                        'title' => $request->plan,
                        'plane_id' => $request->idplan,
                        'user_id' => $request->iduser
                    ]);
                    $contador++;
                }
                
                if($contador==$request->dias)
                {
                    break;
                }
            }
           
        }
        
    }
    public function feriado(Request $request)
    {

        $dias = $this->getDiasHabiles($request->start, $request->end, []);
        $contador = 0;
        foreach ($dias as $dia) {
            $siexiste = DB::table('plane_user')->where('start', $dia)->where('title', 'feriado')->get();
            if ($siexiste->count() != 0) {
            } else {
                DB::table('plane_user')->insert([
                    'color' => Plane::COLORFERIADO,
                    'start' => $dia,
                    'end' => $dia,
                    'title' => 'feriado',
                    'estado'=>Plane::ESTADOFERIADO

                ]);
            }

            $getPlanesCoincidentes = DB::table('plane_user')->where('start', $dia)->where('title', '!=', 'feriado')->get();
            foreach ($getPlanesCoincidentes as $planCoincidente) {
                DB::beginTransaction();
                $extraerUltimo = DB::table('plane_user')
                    ->where('user_id', $planCoincidente->user_id)
                    ->where('title', '!=', 'feriado')
                    ->orderBy('start', 'DESC')
                    ->first();

                $ultimaFecha = $extraerUltimo->start;
                $fechaParaAgregar =  $this->diaSiguienteAlUltimo($ultimaFecha);
                DB::table('plane_user')->insert([

                    'start' => $fechaParaAgregar,
                    'end' => $fechaParaAgregar,
                    'title' => $planCoincidente->title,
                    'plane_id' => $planCoincidente->plane_id,
                    'user_id' => $planCoincidente->user_id
                ]);
                DB::table('plane_user')->where('id', $planCoincidente->id)->delete();
                DB::commit();
                $contador++;
            }
        }
        return response()->json($contador);
    }
    public function diaSiguienteAlUltimo($ultimaFecha)
    {

        $saberDia = WhatsappAPIHelper::saber_dia($ultimaFecha);
        if ($saberDia == 'Sabado') {
            $fechaParaAgregar = Carbon::parse(Carbon::create($ultimaFecha)->addDays(2))->format('Y-m-d');
        } else {
            $fechaParaAgregar = Carbon::parse(Carbon::create($ultimaFecha)->addDays(1))->format('Y-m-d');
        }
        //dd($fechaParaAgregar);
        $siExisteFeriado = DB::table('plane_user')->where('start', $fechaParaAgregar)->where('title', 'feriado')->first();
        while ($siExisteFeriado) {
            $fechaParaAgregar = Carbon::parse(Carbon::create($fechaParaAgregar)->addDays(1))->format('Y-m-d');
            $siExisteFeriado = DB::table('plane_user')->where('start', $fechaParaAgregar)->where('title', 'feriado')->first();
        }
        return $fechaParaAgregar;
    }
    public function permiso($id,$todos)
    {
        $evento = DB::table('plane_user')->where('id', $id)->first();
        $coincidentes=DB::table('plane_user')->where('plane_id', $evento->plane_id)->where('user_id',$evento->user_id)->where('start',$evento->start)->where('estado',Plane::ESTADOPENDIENTE)->get();
        if($coincidentes->count()>1 && $todos==0)
        {
            return 'varios';
        }
        
        $extraerUltimo = DB::table('plane_user')
            ->where('user_id', $evento->user_id)
            ->where('plane_id', $evento->plane_id)
            ->where('title', '!=', 'feriado')
            ->orderBy('start', 'DESC')
            ->first();
         $fechaParaAgregar=$this->diaSiguienteAlUltimo($extraerUltimo->start);
         $saberDia=WhatsappAPIHelper::saber_dia($fechaParaAgregar);
         if($saberDia=="Domingo")
         {
            $fechaParaAgregar=Carbon::parse($fechaParaAgregar)->addDay();
         }
         //dd($saberDia);
         if($todos==1)
         {
            foreach($coincidentes as $plan)
            {
               DB::table('plane_user')->insert([
   
                   'start' => $fechaParaAgregar,
                   'end' => $fechaParaAgregar,
                   'title' => $plan->title,
                   'plane_id' => $plan->plane_id,
                   'user_id' => $plan->user_id
               ]);  
               $evento = DB::table('plane_user')->where('id', $plan->id)->update(['estado' => Plane::ESTADOPERMISO, 'color' => Plane::COLORPERMISO,'detalle'=>null]);
            } 
         }
         else if($todos==2 || $todos==0)
         {
            DB::table('plane_user')->insert([
   
                'start' => $fechaParaAgregar,
                'end' => $fechaParaAgregar,
                'title' => $evento->title,
                'plane_id' => $evento->plane_id,
                'user_id' => $evento->user_id
            ]);  
            $evento = DB::table('plane_user')->where('id', $id)->update(['estado' => Plane::ESTADOPERMISO, 'color' => Plane::COLORPERMISO,'detalle'=>null]);
         }
         
         
        return response()->json($evento);
    }
    public function quitarpermiso($id)
    {
        $evento = DB::table('plane_user')->where('id', $id)->update(['estado' => Plane::ESTADOPENDIENTE, 'color' => Plane::COLORPENDIENTE]);
        return response()->json($evento);
    }
    public function mostrar($idplan, $iduser)
    {

        $eventos = DB::table('plane_user')->where('plane_id', $idplan)->where('user_id', $iduser)->get();
        $feriados = DB::table('plane_user')->where('title', 'feriado')->get();

        foreach ($feriados as $feriado) {
            $eventos->push($feriado);
        }


        return response()->json($eventos);
    }



    public function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array())
    {
        // Convirtiendo en timestamp las fechas
        $fechainicio = strtotime($fechainicio);
        $fechafin = strtotime($fechafin);

        // Incremento en 1 dia
        $diainc = 24 * 60 * 60;

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
