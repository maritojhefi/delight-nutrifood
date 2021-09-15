<?php

namespace App\View\Components;

use Illuminate\View\Component;

class cabeceraPagina extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $titulo;
    public $cabecera;
    public function __construct($titulo, $cabecera)
    {
        $this->titulo=$titulo;
        $this->cabecera=$cabecera;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cabecera-pagina');
    }
}
