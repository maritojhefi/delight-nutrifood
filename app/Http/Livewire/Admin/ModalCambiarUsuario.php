<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use App\Models\Plane;
use Livewire\Component;

class ModalCambiarUsuario extends Component
{
    public $usuario,$search,$plan;

    
    public function render()
    {
        //dd($this->usuario);
        if($this->search)
        {
            $usuarios=User::where('name','LIKE','%'.$this->search.'%')->get();
        }
        else
        {
            $usuarios=User::has('planes')->take(5)->get();
        }
        $planes=Plane::all();
        //dd($usuarios);
        return view('livewire.admin.modal-cambiar-usuario',compact('usuarios','planes'));
    }
}
