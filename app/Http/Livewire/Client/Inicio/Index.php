<?php

namespace App\Http\Livewire\Client\Inicio;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.client.inicio.index')
        ->extends('client.master')
        ->section('content');
    }
}
