<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use App\Models\User;
use Livewire\Component;

class ReporteSemanal extends Component
{
    public function saber_dia($nombredia) {
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
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
        $distribuido=$coleccion->groupBy('DIA');
        //dd($distribuido);
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
       
        return view('livewire.admin.almuerzos.reporte-semanal',compact('distribuido','total'))
        ->extends('admin.master')
        ->section('content');
    }
}
