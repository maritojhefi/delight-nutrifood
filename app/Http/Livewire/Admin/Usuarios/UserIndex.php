<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Exports\UsersListExport;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class UserIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $searchUser, $userEdit;
    public $name, $email, $password, $search, $password_confirmation, $rol = 4, $cumpleano, $direccion, $telf, $codigo_pais = "+591";
    public $nameE, $emailE, $passwordE, $passwordE_confirmation, $cumpleanoE, $direccionE, $rolE, $telfE, $codigo_paisE;

    public function updatingSearchUser()
    {
        $this->resetPage();
    }
    protected $rules = [
        'name' => 'required|min:5|unique:users,name',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'rol' => 'required|integer',
        'telf' => 'required|min:8|unique:users,telf',
        'codigo_pais' => 'required'
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function edit(User $user)
    {

        $this->userEdit = $user;
        $this->nameE = $user->name;
        $this->telfE = $user->telf;
        $this->emailE = $user->email;
        $this->passwordE = null;
        $this->rolE = $user->role_id;
        $this->cumpleanoE = $user->nacimiento;
        $this->direccionE = $user->direccion;
        $this->codigo_paisE = $user->codigo_pais;
        $this->reset('passwordE_confirmation');
    }

    public function copiarUrlNfc($name)
    {
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Url copiada para tarjeta NFC de " . $name
        ]);
    }
    public function copiarTexto($id)
    {

        $this->dispatchBrowserEvent('copiarTexto', [
            'id' => $id,
            'ag' => 'ads',
        ]);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Link copiado!"
        ]);
    }
    public function update()
    {
        $this->validate([
            'nameE' => 'required|min:5|unique:users,name,' . $this->userEdit->id,
            'telfE' => 'required|min:8|unique:users,telf,' . $this->userEdit->id,
            'emailE' => 'required|email|unique:users,email,' . $this->userEdit->id,
            'codigo_paisE' => 'required',

        ]);
        $this->userEdit->name = $this->nameE;
        $this->userEdit->email = $this->emailE;
        $this->userEdit->telf = $this->telfE;
        $this->userEdit->codigo_pais = $this->codigo_paisE;
        if ($this->passwordE != null || $this->passwordE != '') {
            $this->validate([
                'passwordE' => 'required|min:8|confirmed',
            ]);
            $this->userEdit->password = $this->passwordE;
        }

        $this->userEdit->role_id = $this->rolE;
        $this->userEdit->nacimiento = $this->cumpleanoE;
        $this->userEdit->direccion = $this->direccionE;
        $this->userEdit->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Usuario: " . $this->nameE . " actualizado!"
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
            'role_id' => $this->rol,
            'nacimiento' => $this->cumpleano,
            'direccion' => $this->direccion,
            'telf' => $this->telf,
            'codigo_pais' => $this->codigo_pais

        ]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Nuevo usuario: " . $this->name . " creado!"
        ]);
        $this->reset();
    }

    public function eliminar(User $user)
    {


        try {
            $user->delete();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "Usuario: " . $user->name . " eliminado"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "No se puede eliminar este registro porque esta vinculado a otros"
            ]);
        }
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function descargarExcel()
    {
        return Excel::download(new UsersListExport(), 'reporte-usuarios.xlsx');
    }
    public function render()
    {
        if ($this->searchUser) {
            $usuarios = User::where('name', 'LIKE', '%' . $this->searchUser . '%')->orWhere('telf', 'LIKE', '%' . $this->searchUser . '%')->orderBy('id', 'DESC')->paginate(8)->onEachSide(1);
        } else {
            $usuarios = User::orderBy('id', 'DESC')->paginate(8)->onEachSide(1);
        }

        $roles = Role::all();
        return view('livewire.admin.usuarios.user-index', compact('usuarios', 'roles'))
            ->extends('admin.master')
            ->section('content');
    }
}
