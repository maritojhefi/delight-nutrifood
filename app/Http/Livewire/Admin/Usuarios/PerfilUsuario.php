<?php

namespace App\Http\Livewire\Admin\Usuarios;

use Livewire\Component;

class PerfilUsuario extends Component
{
    public function render()
    {
        return view('livewire.admin.usuarios.perfil-usuario')
        ->extends('admin.master')
        ->section('content');
    }
}
