<?php

namespace App\Http\Livewire\Admin\Usuarios;

use Livewire\Component;

class Personal extends Component
{
    public function render()
    {
        return view('livewire.admin.usuarios.personal')
        ->extends('admin.master')
        ->section('content');
    }
}
