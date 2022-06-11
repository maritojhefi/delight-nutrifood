<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use App\Models\User;
use Livewire\Component;

class ReporteSemanal extends Component
{
    public $distribuido, $resumen, $diaSeleccionado;

    public function saber_dia($nombredia) {
        //dd(date('N', strtotime($nombredia)));
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }
    public function resumenDia($dia)
    {
        $this->diaSeleccionado=$dia;
        foreach($this->distribuido as $nombreDia=>$valores)
        {
            if($nombreDia==$dia)
            {
                $resumenDiaArray=collect();
                $resumenDiaArray->push($valores);
                //dd($resumenDiaArray);
                foreach($resumenDiaArray as $lista)
                {
                 $coleccion= collect($lista);
                 $ensalada=$coleccion->pluck('ENSALADA');
                 $resEnsalada=$ensalada->countBy(); 
                 $sopa=$coleccion->pluck('SOPA');
                 $resSopa=$sopa->countBy(); 
                 $plato=$coleccion->pluck('PLATO');
                 $resPlato=$plato->countBy(); 
                 $carbohidrato=$coleccion->pluck('CARBOHIDRATO');
                 $resCarbohidrato=$carbohidrato->countBy(); 
                 $jugo=$coleccion->pluck('JUGO');
                 $resJugo=$jugo->countBy(); 
                 $envio=$coleccion->pluck('ENVIO');
                 $resEnvio=$envio->countBy(); 
                 $empaque=$coleccion->pluck('EMPAQUE');
                 $resEmpaque=$empaque->countBy(); 
                 $this->resumen=collect();
                 $this->resumen->push([
                    'ensaladas'=>$resEnsalada,
                    'sopas'=>$resSopa,
                    'platos'=>$resPlato,
                    'carbohidratos'=>$resCarbohidrato,
                    'jugos'=>$resJugo,
                    'envios'=>$resEnvio,
                    'empaques'=>$resEmpaque,
                 ]);
                }
            }
        }
    }
    public function render()
    {
        $usuarios=User::has('planes')->get();
        
        $coleccion=collect();
        foreach($usuarios as $pensionado)
        {
            
            foreach($pensionado->planesSemana as $lista)
            {
                if($lista->pivot->detalle==null)
                {
                    $coleccion->push([
                        'NOMBRE'=>$pensionado->name,
                        'DIA'=>$this->saber_dia($lista->pivot->start),
                        'ENSALADA'=>'N/D',
                        'SOPA'=>'N/D',
                        'PLATO'=>'N/D',
                        'CARBOHIDRATO'=>'N/D',
                        'JUGO'=>'N/D',
                        'ENVIO'=>'N/D',
                        'EMPAQUE'=>'N/D'
                    ]);
                }
                else
                {
                    $detalle=$lista->pivot->detalle;
                    $det=collect(json_decode($detalle,true));
                    $coleccion->push([
                        'NOMBRE'=>$pensionado->name,
                        'DIA'=>$this->saber_dia($lista->pivot->start),
                        'ENSALADA'=>$det['ENSALADA'],
                        'SOPA'=>$det['SOPA'],
                        'PLATO'=>$det['PLATO'],
                        'CARBOHIDRATO'=>$det['CARBOHIDRATO'],
                        'JUGO'=>$det['JUGO'],
                        'ENVIO'=>$det['ENVIO'],
                        'EMPAQUE'=>$det['EMPAQUE']
                    ]);
                } 
            }
           
        }
        $this->distribuido=$coleccion->groupBy('DIA');
       // dd($this->distribuido);
        $total=collect();
        $total->push([
            
            'sopa'=>$coleccion->pluck('SOPA')->countBy(),
            'ensalada'=>$coleccion->pluck('ENSALADA')->countBy(),
            'plato'=>$coleccion->pluck('PLATO')->countBy(),
            'carbohidrato'=>$coleccion->pluck('CARBOHIDRATO')->countBy(),
            'jugo'=>$coleccion->pluck('JUGO')->countBy(),
            
            'empaque'=>$coleccion->pluck('EMPAQUE')->countBy(),
            'envio'=>$coleccion->pluck('ENVIO')->countBy()
        ]);
       
        return view('livewire.admin.almuerzos.reporte-semanal',compact('total'))
        ->extends('admin.master')
        ->section('content');
    }
}
