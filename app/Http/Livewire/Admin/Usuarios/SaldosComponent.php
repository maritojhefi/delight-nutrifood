<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;


class SaldosComponent extends Component
{
    public $search;
    public function render()
    {
        if ($this->search) {
            $usuarios=User::has('saldos')->where('name','LIKE','%'.$this->search.'%')->get();
        }
        else
        {
            $usuarios=User::has('saldos')->get();
        }
        
        return view('livewire.admin.usuarios.saldos-component',compact('usuarios'))
        ->extends('admin.master')
        ->section('content');
    }
}
