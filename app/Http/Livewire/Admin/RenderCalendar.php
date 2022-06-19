<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class RenderCalendar extends Component
{
    public $usuario;
    public $plan;

    
    public function render()
    {
        return view('livewire.admin.render-calendar');
    }
}
