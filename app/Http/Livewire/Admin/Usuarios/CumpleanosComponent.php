<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;

class CumpleanosComponent extends Component
{
    public $search;
    public function render()
    {
        $usuarios = User::select('*')
            ->with('role')
            ->where('nacimiento', '!=', null)
            ->selectRaw('DATEDIFF(CONCAT(YEAR(CURDATE()), "-", MONTH(nacimiento), "-", DAY(nacimiento)), CURDATE()) AS days_until_birthday')
            ->whereRaw('DATE_FORMAT(nacimiento, "%m-%d") >= DATE_FORMAT(CURDATE(), "%m-%d")')
            ->orderByRaw('DATE_FORMAT(nacimiento, "%m-%d")');
            

        if ($this->search != null && $this->search != '') {
            $usuarios = $usuarios->where('name', 'LIKE', '%' . $this->search . '%')->orWhereHas('role', function ($query) {
                $query->where('nombre', 'LIKE', '%' . $this->search . '%');
            });
        }
        $usuarios = $usuarios->get();
        return view('livewire.admin.usuarios.cumpleanos-component', compact('usuarios'))
            ->extends('admin.master')
            ->section('content');
    }
}
