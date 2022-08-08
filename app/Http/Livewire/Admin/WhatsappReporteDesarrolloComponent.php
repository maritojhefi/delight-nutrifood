<?php

namespace App\Http\Livewire\Admin;

use App\Models\Plane;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class WhatsappReporteDesarrolloComponent extends Component
{
    public function render()
    {
        $reporte = DB::table('plane_user')
        ->select('plane_user.*','users.name','users.telf','planes.nombre')
        ->where('plane_user.estado', Plane::ESTADODESARROLLO)
        ->where(function ($query) {
            $query->orWhereDate('start', Carbon::today()->addDay())
            ->orWhereDate('start', Carbon::today())
            ->orWhereDate('start', Carbon::today()->subDay());
        })
        ->leftJoin('users','users.id','plane_user.user_id')
        ->leftJoin('planes','planes.id','plane_user.plane_id')
        ->get();

        $reporteFinalizados = DB::table('plane_user')
        ->select('plane_user.*','users.name','users.telf','planes.nombre')
        ->where('plane_user.whatsapp', true)
        ->where(function ($query) {
            $query->orWhereDate('start', Carbon::today()->addDay())
            ->orWhereDate('start', Carbon::today())
            ->orWhereDate('start', Carbon::today()->subDay());
        })
        ->leftJoin('users','users.id','plane_user.user_id')
        ->leftJoin('planes','planes.id','plane_user.plane_id')
        ->get();
       // dd($reporte);
        return view('livewire.admin.whatsapp-reporte-desarrollo-component',compact('reporte','reporteFinalizados'))
        ->extends('admin.master')
        ->section('content');
    }
}
