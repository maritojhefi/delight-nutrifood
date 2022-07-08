<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersPlanesTotalExport implements FromCollection//, WithStyles//, WithHeadings
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
            'jugo'=>$coleccion->pluck('JUGO')->countBy(),
            'ensalada'=>$coleccion->pluck('ENSALADA')->countBy(),
            
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
