<?php

namespace App\Http\Livewire\Admin\Ventas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReciboImpreso;

class ProspectosComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $prospectos=ReciboImpreso::orderBy('fecha','desc')->paginate(10);
        return view('livewire.admin.ventas.prospectos-component',compact('prospectos'))
            ->extends('admin.master')
            ->section('content');
    }
}
