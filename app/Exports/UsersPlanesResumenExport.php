<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersPlanesResumenExport implements FromCollection, WithHeadings, WithStyles,WithColumnWidths
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
    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 35, 
            'E' => 35, 
            'F' => 35,            
        ];
    }
    public function headings(): array
    {
        return [
            
            
            'Plan',
            'Nombre',
            'Ensalada',
            'Sopa',
            'Platos',
            'Carbohidratos',
            'Jugo',
            'Envio',
            'Empaque',
            'Estado'
           
        ];
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
                    $sopaCustom='';
                    $det['SOPA']==''?$sopaCustom='Sin Sopa':$sopaCustom=$det['SOPA'];

                    $saberDia=WhatsappAPIHelper::saber_dia($this->fechaSeleccionada);
                    $menu=Almuerzo::where('dia',$saberDia)->first();
                    $tipoSegundo='';
                    $tipoCarbo='';
                    if($det['PLATO']==$menu->ejecutivo)$tipoSegundo='EJECUTIVO';
                    if($det['PLATO']==$menu->dieta)$tipoSegundo='DIETA';
                    if($det['PLATO']==$menu->vegetariano)$tipoSegundo='VEGGIE';
                    
                    $coleccion->push([
                        
                        'PLAN'=>$lista->nombre,
                        'NOMBRE'=>$lista->name,
                        'ENSALADA'=>1,
                        'SOPA'=>$sopaCustom,
                        'PLATO'=>$det['PLATO'],
                        'CARBOHIDRATO'=>$det['CARBOHIDRATO'],
                        'JUGO'=>1,
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
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')
            ->getFont()
            ->setBold(true);
    }
}
