<?php

namespace App\Http\Livewire\Admin\Usuarios;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Asistencia;
use Illuminate\Support\Facades\DB;

class AsistenciaPersonal extends Component
{
    public $search;
    public function render()
    {
        $asistencias=DB::table('contrato_user')
        ->select('contrato_user.*','users.name','contratos.hora_entrada','contratos.hora_salida')
        ->leftjoin('contratos','contratos.id','contrato_user.contrato_id')
        ->leftjoin('users','users.id','contrato_user.user_id')
        ->orderBy('contrato_user.created_at','desc')
        ->get();
        if($this->search)
        {
            $search=$this->search;
            $asistencias = collect($asistencias)->filter(function ($item) use ($search) {
                return false !== stristr($item->name, $search);
            });
        }
        //dd($asistencias);
        return view('livewire.admin.usuarios.asistencia-personal',compact('asistencias'))
        ->extends('admin.master')
        ->section('content');
    }
}
