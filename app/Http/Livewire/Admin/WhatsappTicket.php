<?php

namespace App\Http\Livewire\Admin;

use App\Models\WhatsappLog;
use App\Models\WhatsappPlanAlmuerzo;
use Livewire\Component;
use Livewire\WithPagination;

class WhatsappTicket extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
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
        $logs=WhatsappLog::orderBy('created_at','desc')->paginate(5);
        $tickets=WhatsappPlanAlmuerzo::orderBy('updated_at','desc')->paginate(10);
        return view('livewire.admin.whatsapp-ticket',compact('tickets','logs'))
        ->extends('admin.master')
        ->section('content');
    }
}
