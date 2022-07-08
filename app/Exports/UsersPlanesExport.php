<?php

namespace App\Exports;

use App\Helpers\GlobalHelper;
use App\Models\User;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersPlanesExport implements WithMultipleSheets, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $fechaSeleccionada;
    public $coleccion;
    public function __construct($fechaSeleccionada)
    {
        $this->fechaSeleccionada = $fechaSeleccionada;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'E' => 25,
            'F' => 25,
            'H' => 25
        ];
    }
    public function collection()
    {
       
        $pens = DB::table('plane_user')->select('plane_user.*', 'users.name', 'planes.editable', 'planes.nombre')
            ->leftjoin('users', 'users.id', 'plane_user.user_id')
            ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
            ->whereDate('plane_user.start', $this->fechaSeleccionada)
            //->where('planes.editable',1)
            ->get();

            $coleccion=GlobalHelper::armarColeccionReporteDiario($pens,$this->fechaSeleccionada);
        $this->coleccion = $coleccion;
        return $coleccion;
    }
    public function sheets(): array
    {
        
        $pens = DB::table('plane_user')->select('plane_user.*', 'users.name', 'planes.editable', 'planes.nombre')
            ->leftjoin('users', 'users.id', 'plane_user.user_id')
            ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
            ->whereDate('plane_user.start', $this->fechaSeleccionada)
            //->where('planes.editable',1)
            ->get();
        $coleccion=GlobalHelper::armarColeccionReporteDiario($pens,$this->fechaSeleccionada);
        $this->coleccion = $coleccion;
        $sheets = [new UsersPlanesResumenExport($this->fechaSeleccionada), new UsersPlanesTotalExport($this->coleccion,$this->fechaSeleccionada)];



        return $sheets;
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')
            ->getFont()
            ->setBold(true);
    }
}
