<?php

namespace App\Http\Livewire\Admin\Usuarios;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Asistencia extends Component
{
    public function render()
    {
        $asistencias=DB::table('contrato_user')
        ->select('contrato_user.*','users.name','contratos.hora_entrada','contratos.hora_salida')
        ->leftjoin('contratos','contratos.id','contrato_user.contrato_id')
        ->leftjoin('users','users.id','contrato_user.user_id')
        ->get();
        dd($asistencias);
        return view('livewire.admin.usuarios.asistencia', compact('asistencias'))
        ->extends('admin.master')
        ->section('content');
    }
}
