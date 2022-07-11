<?php

namespace App\Exports;

use App\Helpers\GlobalHelper;
use App\Models\User;
use App\Models\Plane;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersPlanesResumenExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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
            'B' => 5,
            'C' => 15,
            'F' => 5,
            'H' => 5,
            'D' => 20
        ];
    }
    public function headings(): array
    {
        return [
            'Nombre',
            'Sopa',
            'Platos',
            'Carbohidratos',
            'Envio',
            'Ensalada',
            'Empaque',
            'Jugo',
            'Estado'

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
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')
            ->getFont()
            ->setBold(true);
    }
}
