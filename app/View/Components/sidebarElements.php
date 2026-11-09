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
    public $lista, $linkglobal, $titulo, $modulo;
    public function __construct($lista, $linkglobal, $titulo, $modulo = null)
    {
        // Con modulo: solo se muestran las opciones permitidas al rol (config/permisos.php)
        $this->lista = $modulo ? \App\Helpers\PermisoHelper::filtrarLista($modulo, $lista) : $lista;
        $this->modulo=$modulo;
        $this->linkglobal=$linkglobal;
        $this->titulo=$titulo;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function shouldRender()
    {
        return count($this->lista) > 0;
    }

    public function render()
    {
        return view('components.sidebar-elements');
    }
}
