<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use Livewire\Component;

class NutriBarPanelComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.almuerzos.nutri-bar-panel-component')
            ->extends('admin.master')
            ->section('content');
    }
}
