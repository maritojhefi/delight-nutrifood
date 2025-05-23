<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputCreateDefer extends Component
{
    public $lista, $posicion;
    public function __construct($lista, $posicion = 1)
    {
        $this->lista = $lista;
        $this->posicion = $posicion;
    }
    public function render()
    {
        return view('components.input-create-defer');
    }
}
