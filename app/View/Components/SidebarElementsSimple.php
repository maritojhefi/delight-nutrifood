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
    public $titulo, $url;
    public function __construct($titulo, $url)
    {
        $this->titulo = $titulo;
        $this->url = $url;
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
