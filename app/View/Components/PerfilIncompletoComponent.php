<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PerfilIncompletoComponent extends Component
{
    /**
     * Booleano indicando si el perfil está completo.
     *
     * @var bool
     */
    public $estaCompleto;

    /**
     * Nueva instancia del componente.
     *
     * @param bool $estaCompleto
     * @return void
     */
    public function __construct(bool $estaCompleto = false) 
    {
        $this->estaCompleto = $estaCompleto;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.perfil-incompleto-component');
    }
}