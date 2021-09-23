<?php

namespace App\Http\Livewire\Admin\Ventas;

use Livewire\Component;

class VentasIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.ventas.ventas-index')
        ->extends('admin.master')
        ->section('content');
    }
}
