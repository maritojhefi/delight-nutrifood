<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $name, $email, $password, $search, $password_confirmation, $rol, $cumpleano, $direccion;

    protected $rules = [
        'name' => 'required|min:5|unique:users,name',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
        'rol'=>'required|integer',
       
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
 
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role_id'=> $this->rol,
            'nacimiento'=>$this->cumpleano,
            'direccion'=>$this->direccion
            
        ]);
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Nuevo usuario: ".$this->name." creado!"
        ]);
        $this->reset();
    }

    public function eliminar(User $user)
    {
       

        try {
            $user->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Usuario: ".$user->name." eliminado"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"No se puede eliminar este registro porque esta vinculado a otros"
            ]);
        }
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $usuarios=User::paginate(5);
        $roles=Role::all();
        return view('livewire.admin.usuarios.user-index',compact('usuarios','roles'))
        ->extends('admin.master')
        ->section('content');
    }
}
