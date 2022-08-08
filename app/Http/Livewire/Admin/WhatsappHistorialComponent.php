<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\WhatsappHistorial;

class WhatsappHistorialComponent extends Component
{
    public function render()
    {
        $whatsapps = WhatsappHistorial::paginate(10);
        return view('livewire.admin.whatsapp-historial-component', compact('whatsapps'))
            ->extends('admin.master')
            ->section('content');;
    }
}
