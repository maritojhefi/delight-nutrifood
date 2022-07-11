<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersPlanesTotalExport implements FromCollection//, WithStyles//, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $coleccion,$diaSeleccionado;
    public function __construct($coleccion,$diaSeleccionado)
    {
        $this->coleccion=$coleccion;
        $this->diaSeleccionado=$diaSeleccionado;
    }
    // public function headings(): array
    // {
    //     return [
           

    //         'Sopa',
    //         'Ensalada',
            
    //         'Platos',
    //         'Carbohidratos',
    //         'Jugo',
    //         'Empaque',
    //         'Envio',
    //     ];
    // }
    public function collection()
    {
        $coleccion=$this->coleccion;

        $total=collect();
        $total->push([
            
            'sopa'=>$coleccion->pluck('SOPA')->countBy(),
            
            'plato'=>$coleccion->pluck('PLATO')->countBy(),
            'carbohidrato'=>$coleccion->pluck('CARBOHIDRATO')->countBy(),
            'jugo'=>$coleccion->pluck('JUGO')->countBy(),
            'ensalada'=>$coleccion->pluck('ENSALADA')->countBy(),
            
            'empaque'=>$coleccion->pluck('EMPAQUE')->countBy(),
            'envio'=>$coleccion->pluck('ENVIO')->countBy()
        ]);
        //dd($total);
         $resumen=collect();
        // $saberDia=WhatsappAPIHelper::saber_dia($this->diaSeleccionado);
        // $menu=Almuerzo::where('dia',$saberDia)->first();
        
        // $array=['SOPA','SIN SOPA','EJECUTIVO','DIETA','VEGGIE',$menu->carbohidrato_1,$menu->carbohidrato_2,$menu->carbohidrato_3,'JUGOS','SIN JUGOS','ENSALADA','SIN ENSALADA','VIANDA','ECO EMPAQUE','DELIVERY','NINGUNO','RECOGER','PARA MESA'];
        $cont=0;
        
        foreach($total as $reporte)
        {
           
            foreach($reporte as $categoria=>$obj)
            {
                $resumen->push([
                    '------'.$categoria.'------'
                  ]);
                foreach ($obj as $nombre=>$key) {
                    if($nombre!='')
                    {
                    
                        $resumen->push([
                            $nombre=> $nombre.' : '.$key
                          ]);
                          $cont++;
                    }
                  
                }
                $resumen->push([
                    '------'
                  ]);
            }
        
           
            
        }
        //dd($resumen);
        //dd($total);
        return $resumen;
    }
   
}
