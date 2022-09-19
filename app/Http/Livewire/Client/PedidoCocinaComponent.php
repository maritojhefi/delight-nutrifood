<?php

namespace App\Http\Livewire\Client;

use Livewire\Component;

class PedidoCocinaComponent extends Component
{
    public $hola=2;
    public function prueba()
    {
        $this->hola=3;
    }
    public function render()
    {
        return view('livewire.client.pedido-cocina-component');
    }
}
