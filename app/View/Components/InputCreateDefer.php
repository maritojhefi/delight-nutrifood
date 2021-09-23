<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputCreateDefer extends Component
{
    public $lista;
    public function __construct($lista)
    {
        $this->lista=$lista;
    }
    public function render()
    {
        return view('components.input-create-defer');
    }
}
