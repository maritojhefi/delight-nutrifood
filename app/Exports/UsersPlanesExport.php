<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersPlanesExport implements WithMultipleSheets, WithStyles,WithColumnWidths
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
            'A' => 30,
            'B' => 30,   
            'E' => 25, 
            'F' => 25,          
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
                    $det['SOPA']==''?$sopaCustom='0':$sopaCustom='1';

                    $saberDia=WhatsappAPIHelper::saber_dia($this->fechaSeleccionada);
                    $menu=Almuerzo::where('dia',$saberDia)->first();
                    $tipoSegundo='';
                    $tipoCarbo='';
                    if($det['PLATO']==$menu->ejecutivo)$tipoSegundo='EJECUTIVO';
                    if($det['PLATO']==$menu->dieta)$tipoSegundo='DIETA';
                    if($det['PLATO']==$menu->vegetariano)$tipoSegundo='VEGGIE';
                    $coleccion->push([
                        
                        'NOMBRE'=>$lista->name,
                        'SOPA'=>$sopaCustom,
                        
                        'PLATO'=>$tipoSegundo,
                        'CARBOHIDRATO'=>$det['CARBOHIDRATO'],
                        
                        'ENVIO'=>$det['ENVIO'],
                        'ENSALADA'=>1,
                        'EMPAQUE'=>$det['EMPAQUE'],
                        'JUGO'=>1,
                        'ESTADO'=>$lista->estado
                    ]);
                }
                else
                {
                    $coleccion->push([
                       
                        
                        'NOMBRE'=>$lista->name,
                        
                        'SOPA'=>'',
                        'PLATO'=>'',
                        'CARBOHIDRATO'=>'',
                        
                        'ENVIO'=>'',
                        'ENSALADA'=>'',
                        'EMPAQUE'=>'',
                        'JUGO'=>'',
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
                    $det['SOPA']==''?$sopaCustom='0':$sopaCustom='1';

                    $saberDia=WhatsappAPIHelper::saber_dia($this->fechaSeleccionada);
                    $menu=Almuerzo::where('dia',$saberDia)->first();
                    $tipoSegundo='';
                    $tipoCarbo='';
                    if($det['PLATO']==$menu->ejecutivo)$tipoSegundo='EJECUTIVO';
                    if($det['PLATO']==$menu->dieta)$tipoSegundo='DIETA';
                    if($det['PLATO']==$menu->vegetariano)$tipoSegundo='VEGGIE';
                    $coleccion->push([
                        
                        'NOMBRE'=>$lista->name,
                        'SOPA'=>$sopaCustom,
                        
                        'PLATO'=>$tipoSegundo,
                        'CARBOHIDRATO'=>$det['CARBOHIDRATO'],
                        
                        'ENVIO'=>$det['ENVIO'],
                        'ENSALADA'=>1,
                        'EMPAQUE'=>$det['EMPAQUE'],
                        'JUGO'=>1,
                        'ESTADO'=>$lista->estado
                    ]);
                }
                else
                {
                    $coleccion->push([
                        'NOMBRE'=>$lista->name,
                        
                        'SOPA'=>'',
                        'PLATO'=>'',
                        'CARBOHIDRATO'=>'',
                        
                        'ENVIO'=>'',
                        'ENSALADA'=>'',
                        'EMPAQUE'=>'',
                        'JUGO'=>'',
                        'ESTADO'=>$lista->estado
                    ]);
                }
                
                
                
                
            }
            //dd($coleccion);
        $this->coleccion=$coleccion;
        $sheets = [new UsersPlanesResumenExport($this->fechaSeleccionada),new UsersPlanesTotalExport($this->coleccion)];

       

        return $sheets;
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')
            ->getFont()
            ->setBold(true);
            
    }
}
