<?php

namespace App\Http\Livewire\Admin;

use App\Models\WhatsappPlanAlmuerzo;
use Livewire\Component;

class WhatsappTicket extends Component
{
    public function eliminar(WhatsappPlanAlmuerzo $ticket)
    {
        $ticket->delete();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Ticket borrado"
        ]);
    }
    public function render()
    {
        $tickets=WhatsappPlanAlmuerzo::all();
        return view('livewire.admin.whatsapp-ticket',compact('tickets'))
        ->extends('admin.master')
        ->section('content');
    }
}
