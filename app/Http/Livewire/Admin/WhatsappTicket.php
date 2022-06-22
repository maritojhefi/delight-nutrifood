<?php

namespace App\Http\Livewire\Admin;

use App\Models\WhatsappPlanAlmuerzo;
use Livewire\Component;

class WhatsappTicket extends Component
{
    public function render()
    {
        $tickets=WhatsappPlanAlmuerzo::all();
        return view('livewire.admin.whatsapp-ticket',compact('tickets'))
        ->extends('admin.master')
        ->section('content');
    }
}
