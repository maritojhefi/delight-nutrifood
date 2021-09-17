<?php

namespace App\View\Components;

use Illuminate\View\Component;

class sidebarElements extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $lista, $linkglobal, $titulo;
    public function __construct($lista, $linkglobal, $titulo)
    {
        $this->lista=$lista;
        $this->linkglobal=$linkglobal;
        $this->titulo=$titulo;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar-elements');
    }
}
