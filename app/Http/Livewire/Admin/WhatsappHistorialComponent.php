<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WhatsappHistorial;

class WhatsappHistorialComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $whatsapps = WhatsappHistorial::orderBy('created_at','desc')->paginate(10);
        return view('livewire.admin.whatsapp-historial-component', compact('whatsapps'))
            ->extends('admin.master')
            ->section('content');
    }
}
