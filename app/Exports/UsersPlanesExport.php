<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersPlanesExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $fechaSeleccionada;
    public $coleccion;
    public function __construct($fechaSeleccionada)
    {
            $this->fechaSeleccionada=$fechaSeleccionada;
    }
    public function collection()
    {
        $coleccion=collect();
        $pens=DB::table('plane_user')->select('plane_user.*','users.name','planes.editable','planes.nombre')
        ->leftjoin('users','users.id','plane_user.user_id')
        ->leftjoin('planes','planes.id','plane_user.plane_id')
        ->whereDate('plane_user.start',$this->fechaSeleccionada)
        //->where('planes.editable',1)
        ->get();   
           
            foreach($pens as $lista)
            {
                //dd($lista);
                $detalle=$lista->detalle;
                if($detalle!=null)
                {
                    $det=collect(json_decode($detalle,true));
                
                
                    $coleccion->push([
                       
                        'PLAN'=>$lista->nombre,
                        'NOMBRE'=>$lista->name,
                        'ENSALADA'=>$det['ENSALADA'],
                        'SOPA'=>$det['SOPA'],
                        'PLATO'=>$det['PLATO'],
                        'CARBOHIDRATO'=>$det['CARBOHIDRATO'],
                        'JUGO'=>$det['JUGO'],
                        'ENVIO'=>$det['ENVIO'],
                        'EMPAQUE'=>$det['EMPAQUE'],
                        'ESTADO'=>$lista->estado
                    ]);
                }
                else
                {
                    $coleccion->push([
                       
                        'PLAN'=>$lista->nombre,
                        'NOMBRE'=>$lista->name,
                        'ENSALADA'=>'',
                        'SOPA'=>'',
                        'PLATO'=>'',
                        'CARBOHIDRATO'=>'',
                        'JUGO'=>'',
                        'ENVIO'=>'',
                        'EMPAQUE'=>'',
                        'ESTADO'=>$lista->estado
                    ]);
                }
                
                
                
                
            }
            
       $this->coleccion=$coleccion;
       return $coleccion;
    }
    public function sheets(): array
    {
        $coleccion=collect();
        $pens=DB::table('plane_user')->select('plane_user.*','users.name','planes.editable','planes.nombre')
        ->leftjoin('users','users.id','plane_user.user_id')
        ->leftjoin('planes','planes.id','plane_user.plane_id')
        ->whereDate('plane_user.start',$this->fechaSeleccionada)
        //->where('planes.editable',1)
        ->get();            
            foreach($pens as $lista)
            {
                //dd($lista);
                $detalle=$lista->detalle;
                if($detalle!=null)
                {
                    $det=collect(json_decode($detalle,true));
                    $sopaCustom='';
                    $det['SOPA']==''?$sopaCustom='Sin Sopa':$sopaCustom=$det['SOPA'];
                    $coleccion->push([
                        'ID'=>$lista->id,
                        'PLAN'=>$lista->nombre,
                        'NOMBRE'=>$lista->name,
                        'ENSALADA'=>$det['ENSALADA'],
                        'SOPA'=>$sopaCustom,
                        'PLATO'=>$det['PLATO'],
                        'CARBOHIDRATO'=>$det['CARBOHIDRATO'],
                        'JUGO'=>$det['JUGO'],
                        'ENVIO'=>$det['ENVIO'],
                        'EMPAQUE'=>$det['EMPAQUE'],
                        'ESTADO'=>$lista->estado
                    ]);
                }
                else
                {
                    $coleccion->push([
                        'ID'=>$lista->id,
                        'PLAN'=>$lista->nombre,
                        'NOMBRE'=>$lista->name,
                        'ENSALADA'=>'',
                        'SOPA'=>'',
                        'PLATO'=>'',
                        'CARBOHIDRATO'=>'',
                        'JUGO'=>'',
                        'ENVIO'=>'',
                        'EMPAQUE'=>'',
                        'ESTADO'=>$lista->estado
                    ]);
                }
                
                
                
                
            }
            //dd($coleccion);
        $this->coleccion=$coleccion;
        $sheets = [new UsersPlanesResumenExport($this->fechaSeleccionada),new UsersPlanesTotalExport($this->coleccion)];

       

        return $sheets;
    }
}