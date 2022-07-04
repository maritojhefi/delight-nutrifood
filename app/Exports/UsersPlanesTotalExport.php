<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersPlanesTotalExport implements FromCollection//, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $coleccion;
    public function __construct($coleccion)
    {
        $this->coleccion=$coleccion;
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
            
            
            'empaque'=>$coleccion->pluck('EMPAQUE')->countBy(),
            'envio'=>$coleccion->pluck('ENVIO')->countBy()
        ]);
        $resumen=collect();
        foreach($total as $reporte)
        {
            foreach($reporte as $obj)
            {
                foreach ($obj as $nombre=>$key) {
                    if($nombre!='')
                    {
                        $resumen->push([
                            $nombre=> $nombre.' : '.$key
                          ]);
                    }
                    
                }
                
            }
           
            
        }
        //dd($resumen);
        //dd($total);
        return $resumen;
    }
}
