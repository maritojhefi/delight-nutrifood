<?php

namespace App\Http\Livewire\Admin\Ventas;

use App\Models\ReciboImpreso;
use Livewire\Component;

class ProspectosComponent extends Component
{
    public function render()
    {
        $prospectos=ReciboImpreso::orderBy('fecha','desc')->paginate(10);
        return view('livewire.admin.ventas.prospectos-component',compact('prospectos'))
            ->extends('admin.master')
            ->section('content');
    }
}
