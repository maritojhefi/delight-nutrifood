<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\PerfilPunto;
use Livewire\WithPagination;

class PuntosPerfilesComponent extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    // Propiedades para el CRUD
    public $search = '';
    public $perfil_id;
    public $nombre;
    public $porcentaje;
    public $bono;
    public $showModal = false;
    public $isEdit = false;

    // Propiedades para el modal de usuarios
    public $showModalUsuarios = false;
    public $perfilSeleccionado = null;
    public $searchUsuariosDisponibles = '';
    public $searchUsuariosAsignados = '';
    public $usuariosDisponibles = [];
    public $usuariosAsignados = [];

    protected $listeners = ['eliminar-perfil' => 'eliminarPerfil'];

    // Reglas de validación
    protected $rules = [
        'nombre' => 'required|string|min:3|max:255',
        'porcentaje' => 'required|numeric|min:0|max:100',
        'bono' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
        'porcentaje.required' => 'El porcentaje es obligatorio.',
        'porcentaje.numeric' => 'El porcentaje debe ser un número.',
        'porcentaje.min' => 'El porcentaje no puede ser menor a 0.',
        'porcentaje.max' => 'El porcentaje no puede ser mayor a 100.',
        'bono.required' => 'El bono es obligatorio.',
        'bono.numeric' => 'El bono debe ser un número.',
        'bono.min' => 'El bono no puede ser menor a 0.',
    ];

    public function render()
    {
        $perfiles = PerfilPunto::when($this->search, function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        })->paginate(10);

        return view('livewire.admin.puntos-perfiles-component', compact('perfiles'))->extends('admin.master')->section('content');
    }

    // Método para crear nuevo perfil
    public function crearNuevo()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    // Método para editar perfil
    public function editarPerfil($id)
    {
        $perfil = PerfilPunto::findOrFail($id);
        $this->perfil_id = $perfil->id;
        $this->nombre = $perfil->nombre;
        $this->porcentaje = $perfil->porcentaje;
        $this->bono = $perfil->bono;
        $this->isEdit = true;
        $this->showModal = true;
    }

    // Método para guardar (crear o actualizar)
    public function guardar()
    {
        $this->validate();

        if ($this->isEdit) {
            $perfil = PerfilPunto::findOrFail($this->perfil_id);
            $perfil->update([
                'nombre' => $this->nombre,
                'porcentaje' => $this->porcentaje,
                'bono' => $this->bono,
            ]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Perfil actualizado exitosamente.',
            ]);
        } else {
            PerfilPunto::create([
                'nombre' => $this->nombre,
                'porcentaje' => $this->porcentaje,
                'bono' => $this->bono,
            ]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Perfil creado exitosamente.',
            ]);
        }

        $this->cerrarModal();
    }

    // Método para eliminar perfil
    public function eliminarPerfil($id)
    {
        $perfil = PerfilPunto::findOrFail($id);
        $perfil->delete();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Perfil eliminado exitosamente.',
        ]);
    }

    // Método para cerrar modal
    public function cerrarModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // Método para resetear formulario
    private function resetForm()
    {
        $this->perfil_id = null;
        $this->nombre = '';
        $this->porcentaje = '';
        $this->bono = '';
        $this->resetErrorBag();
    }

    // Método para abrir modal de usuarios
    public function agregarUsuarios($perfilId)
    {
        $this->perfilSeleccionado = PerfilPunto::findOrFail($perfilId);
        $this->cargarUsuarios();
        $this->showModalUsuarios = true;
        $this->emit('abrirModalUsuarios');
    }

    // Método para cargar usuarios disponibles y asignados
    public function cargarUsuarios()
    {
        if (!$this->perfilSeleccionado) {
            return;
        }

        // Usuarios con role_id = 4 (disponibles)
        $usuariosDisponiblesQuery = User::where('role_id', 4)->select('id', 'name', 'email', 'telf');

        if ($this->searchUsuariosDisponibles) {
            $usuariosDisponiblesQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchUsuariosDisponibles . '%')->orWhere('email', 'like', '%' . $this->searchUsuariosDisponibles . '%');
            });
        }

        // Excluir usuarios que ya están asignados a este perfil
        $usuariosAsignadosIds = $this->perfilSeleccionado->usuarios()->pluck('users.id')->toArray();
        if (!empty($usuariosAsignadosIds)) {
            $usuariosDisponiblesQuery->whereNotIn('id', $usuariosAsignadosIds);
        }

        $this->usuariosDisponibles = $usuariosDisponiblesQuery->get();

        // Usuarios ya asignados al perfil
        $usuariosAsignadosQuery = $this->perfilSeleccionado->usuarios();

        if ($this->searchUsuariosAsignados) {
            $usuariosAsignadosQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchUsuariosAsignados . '%')->orWhere('email', 'like', '%' . $this->searchUsuariosAsignados . '%');
            });
        }

        $this->usuariosAsignados = $usuariosAsignadosQuery->get();
    }

    // Método para agregar usuario al perfil
    public function agregarUsuarioAlPerfil($userId)
    {
        $user = User::findOrFail($userId);
        $this->perfilSeleccionado->usuarios()->attach($userId);

        $this->cargarUsuarios();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Usuario {$user->name} agregado al perfil exitosamente.",
        ]);
    }

    // Método para quitar usuario del perfil
    public function quitarUsuarioDelPerfil($userId)
    {
        $user = User::findOrFail($userId);
        $this->perfilSeleccionado->usuarios()->detach($userId);

        $this->cargarUsuarios();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Usuario {$user->name} removido del perfil exitosamente.",
        ]);
    }

    // Método para cerrar modal de usuarios
    public function cerrarModalUsuarios()
    {
        $this->showModalUsuarios = false;
        $this->perfilSeleccionado = null;
        $this->searchUsuariosDisponibles = '';
        $this->searchUsuariosAsignados = '';
        $this->usuariosDisponibles = [];
        $this->usuariosAsignados = [];
    }

    // Métodos para actualizar búsquedas
    public function updatedSearchUsuariosDisponibles()
    {
        $this->cargarUsuarios();
    }

    public function updatedSearchUsuariosAsignados()
    {
        $this->cargarUsuarios();
    }
}
