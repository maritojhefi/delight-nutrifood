<?php

namespace App\View\Components;

use Illuminate\View\Component;

class inputCreateCustomFunction extends Component
{
    public $lista, $funcion, $boton;
    public function __construct($lista, $funcion, $boton)
    {
        $this->lista=$lista;
        $this->funcion=$funcion;
        $this->boton=$boton;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-create-custom-function');
    }
}
