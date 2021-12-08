<?php

namespace App\Http\Livewire\Admin\Usuarios;

use Carbon\Carbon;
use Livewire\Component;


class Asistencia extends Component
{
    public function render()
    {
        $asistencias=Asistencia::whereDate('entrada',Carbon::now())->get();
        return view('livewire.admin.usuarios.asistencia', compact('asistencias'))
        ->extends('admin.master')
        ->section('content');
    }
}
