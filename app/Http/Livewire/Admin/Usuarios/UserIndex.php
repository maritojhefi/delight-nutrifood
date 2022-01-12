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
    public $searchUser, $userEdit;
    public $name, $email, $password, $search, $password_confirmation, $rol, $cumpleano, $direccion;
    public $nameE, $emailE, $passwordE, $passwordE_confirmation, $cumpleanoE, $direccionE;
    
    public function updatingSearchUser()
    {
        $this->resetPage();
    }
    protected $rules = [
        'name' => 'required|min:5|unique:users,name',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'rol'=>'required|integer',
       
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function edit(User $user)
    {
        $this->userEdit=$user;
        $this->nameE=$user->name;
        $this->emailE=$user->email;
        $this->passwordE=null;
       
        $this->cumpleanoE=$user->nacimiento;
        $this->direccionE=$user->direccion;
        $this->reset('passwordE_confirmation');
    }

    public function copiarTexto($id)
    {
    
        $this->dispatchBrowserEvent('copiarTexto',[
            'id'=>$id,
            'ag'=>'ads',
        ]);

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Link copiado!"
        ]);
    }
    public function update()
    {
        $this->validate([
            'nameE' => 'required|min:5|unique:users,name,'.$this->userEdit->id,
            'emailE' => 'required|email|unique:users,email,'.$this->userEdit->id,
            'passwordE' => 'required|min:8|confirmed',
        ]);
        $this->userEdit->name=$this->nameE;
        $this->userEdit->email=$this->emailE;
        $this->userEdit->password=$this->passwordE;
        $this->userEdit->nacimiento=$this->cumpleanoE;
        $this->userEdit->direccion=$this->direccionE;
        $this->userEdit->save();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Usuario: ".$this->nameE." actualizado!"
        ]);
        $this->reset('passwordE_confirmation');
       
    }
    public function submit()
    {
        $this->validate();
 
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
        if($this->searchUser)
        {
            $usuarios=User::where('name','LIKE','%'.$this->searchUser.'%')->paginate(5);
        }
        else
        {
            $usuarios=User::paginate(5);
        }
        
        $roles=Role::all();
        return view('livewire.admin.usuarios.user-index',compact('usuarios','roles'))
        ->extends('admin.master')
        ->section('content');
    }
}
