<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductoListComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $ruta,$nombre,$foto,$precio,$id;
    public function __construct($ruta,$nombre,$foto,$precio,$id)
    {
        $this->ruta=$ruta;
        $this->nombre=$nombre;
        $this->foto=$foto;
        $this->precio=$precio;
        $this->id=$id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.producto-list-component');
    }
}
