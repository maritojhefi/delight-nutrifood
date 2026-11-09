<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarElementsSimple extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $titulo, $url, $modulo;
    public function __construct($titulo, $url, $modulo = null)
    {
        $this->titulo = $titulo;
        $this->url = $url;
        $this->modulo = $modulo;
    }

    public function shouldRender()
    {
        return !$this->modulo || \App\Helpers\PermisoHelper::puede($this->modulo, $this->url);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar-elements-simple');
    }
}
