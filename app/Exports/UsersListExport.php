<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersListExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'C' => 15,
            'D' => 20,
            'E' => 15,
            'F' => 20,
            'G' => 15,
            'H' => 15
        ];
    }
    public function headings(): array
    {
        return [
            'Nombre',
            'Correo',
            'Telefono',
            'Direccion',
            'Fecha Nacimiento',
            'Saldo',
            'Puntos',
            'Rol'
        ];
    }
    public function collection()
    {
        $usuarios = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.name', 'users.email', 'users.telf', 'users.direccion', 'users.nacimiento', 'users.saldo', 'users.puntos', 'roles.nombre as rol_nombre')
            ->get();

        return $usuarios;
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')
            ->getFont()
            ->setBold(true);
    }
}
