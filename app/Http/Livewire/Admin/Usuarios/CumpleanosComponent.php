<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;

class CumpleanosComponent extends Component
{
    public function render()
    {
        $usuarios = User::select('*')
            ->selectRaw('DATEDIFF(CONCAT(YEAR(CURDATE()), "-", MONTH(nacimiento), "-", DAY(nacimiento)), CURDATE()) AS days_until_birthday')
            ->whereRaw('DATE_FORMAT(nacimiento, "%m-%d") >= DATE_FORMAT(CURDATE(), "%m-%d")')
            ->orderByRaw('DATE_FORMAT(nacimiento, "%m-%d")')
            ->where('nacimiento', '!=', null)
            ->get();
        //     ->selectRaw('DATEDIFF(CONCAT(YEAR(CURDATE()), "-", MONTH(fecha_nacimiento), "-", DAY(fecha_nacimiento)), CURDATE()) AS days_until_birthday')
        // ->whereRaw('DATE_FORMAT(fecha_nacimiento, "%m-%d") >= DATE_FORMAT(CURDATE(), "%m-%d")')
        // ->orderByRaw('DATE_FORMAT(fecha_nacimiento, "%m-%d")')
        return view('livewire.admin.usuarios.cumpleanos-component', compact('usuarios'))
            ->extends('admin.master')
            ->section('content');
    }
}
