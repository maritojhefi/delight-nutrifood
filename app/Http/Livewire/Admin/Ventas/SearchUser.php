<?php

namespace App\Http\Livewire\Admin\Ventas;

use App\Models\User;
use Livewire\Component;

class SearchUser extends Component
{
    public $user;
    public function render()
    {
        $usuarios=User::where('name','LIKE','%'.$this->user.'%')->take(3)->get();
        return view('livewire.admin.ventas.search-user',compact('usuarios'));
    }
}
