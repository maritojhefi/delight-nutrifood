<?php

namespace App\Http\Livewire\Client;

use Livewire\Component;

class FormularioCompletarPerfil extends Component
{
    public $usuario;

    public function guardar(){
        dd($this->usuario);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se agregaron ".$this->cantidad." productos de ".$this->prodlisto->nombre
        ]);
    }
    public function render()
    {
        
        return view('livewire.client.formulario-completar-perfil');
    }
}
