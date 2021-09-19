<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputCreate extends Component
{
    public $lista;
    public function __construct($lista)
    {
        $this->lista=$lista;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-create');
    }
}
