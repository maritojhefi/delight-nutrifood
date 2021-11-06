<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use App\Models\User;
use Livewire\Component;

class ReporteDiario extends Component
{
    public function render()
    {
        $usuarios=User::has('planes')->get();
        $coleccion=collect();
        foreach($usuarios as $pensionado)
        {
            foreach($pensionado->planesHoy as $lista)
            {
                $detalle=$lista->pivot->detalle;
                $det=collect(json_decode($detalle,true));
                
                
                $coleccion->push([
                    'NOMBRE'=>$pensionado->name,
                    'ENVASE'=>$det['SOPA'],
                    'ENSALADA'=>$det['ENSALADA'],
                    'SOPA'=>$det['SOPA'],
                    'PLATO'=>$det['PLATO'],
                    'CARBOHIDRATO'=>$det['CARBOHIDRATO'],
                    'JUGO'=>$det['JUGO']
                ]);
                
                
                
            }
           
        }
        $total=collect();
        $total->push([
            'envase'=>$coleccion->pluck('ENVASE')->countBy(),
            'sopa'=>$coleccion->pluck('SOPA')->countBy(),
            'ensalada'=>$coleccion->pluck('ENSALADA')->countBy(),
            'plato'=>$coleccion->pluck('PLATO')->countBy(),
            'carbohidrato'=>$coleccion->pluck('CARBOHIDRATO')->countBy(),
            'jugo'=>$coleccion->pluck('JUGO')->countBy()
        ]);
        //dd($total);
        return view('livewire.admin.almuerzos.reporte-diario',compact('usuarios','coleccion','total'))
        ->extends('admin.master')
        ->section('content');
    }
}
