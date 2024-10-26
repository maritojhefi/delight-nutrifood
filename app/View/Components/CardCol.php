<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardCol extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $tamano;
    public function __construct($tamano)
    {
        $this->tamano = $tamano;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-col');
    }
}
