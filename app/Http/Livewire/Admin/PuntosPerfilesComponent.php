<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\PerfilPunto;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public $perPageDisponibles = 30;
    public $perPageAsignados = 30;

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
        })->simplePaginate(30);

        // Siempre pasar las propiedades computadas a la vista
        // Los métodos computados ya manejan el caso cuando no hay perfil seleccionado
        return view('livewire.admin.puntos-perfiles-component', [
            'perfiles' => $perfiles,
            'usuariosDisponibles' => $this->usuariosDisponibles,
            'usuariosAsignados' => $this->usuariosAsignados,
        ])->extends('admin.master')->section('content');
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

    // Métodos computados para obtener usuarios disponibles y asignados
    public function getUsuariosDisponiblesProperty()
    {
        if (!$this->perfilSeleccionado) {
            return new LengthAwarePaginator([], 0, $this->perPageDisponibles, 1, [
                'path' => request()->url(),
                'pageName' => 'page_disponibles',
            ]);
        }

        // Usuarios con role_id = 4 (disponibles)
        // Incluimos TODOS los usuarios: sin perfil y con otros perfiles (para poder reasignarlos)
        $usuariosDisponiblesQuery = User::select('id', 'name', 'email', 'telf')
            ->with('perfilesPuntos');

        if ($this->searchUsuariosDisponibles) {
            $usuariosDisponiblesQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchUsuariosDisponibles . '%')
                    ->orWhere('email', 'like', '%' . $this->searchUsuariosDisponibles . '%')
                    ->orWhere('telf', 'like', '%' . $this->searchUsuariosDisponibles . '%');
            });
        }

        // Excluir usuarios que ya están asignados a este perfil
        $usuariosAsignadosIds = $this->perfilSeleccionado->usuarios()->pluck('users.id')->toArray();
        if (!empty($usuariosAsignadosIds)) {
            $usuariosDisponiblesQuery->whereNotIn('id', $usuariosAsignadosIds);
        }

        // Paginación para usuarios disponibles
        return $usuariosDisponiblesQuery->simplePaginate($this->perPageDisponibles, ['*'], 'page_disponibles');
    }

    public function getUsuariosAsignadosProperty()
    {
        if (!$this->perfilSeleccionado) {
            return new LengthAwarePaginator([], 0, $this->perPageAsignados, 1, [
                'path' => request()->url(),
                'pageName' => 'page_asignados',
            ]);
        }

        // Usuarios ya asignados al perfil
        $usuariosAsignadosQuery = $this->perfilSeleccionado->usuarios()
            ->select('users.id', 'users.name', 'users.email', 'users.telf');

        if ($this->searchUsuariosAsignados) {
            $usuariosAsignadosQuery->where(function ($query) {
                $query->where('users.name', 'like', '%' . $this->searchUsuariosAsignados . '%')
                    ->orWhere('users.email', 'like', '%' . $this->searchUsuariosAsignados . '%')
                    ->orWhere('users.telf', 'like', '%' . $this->searchUsuariosAsignados . '%');
            });
        }

        // Paginación para usuarios asignados
        return $usuariosAsignadosQuery->simplePaginate($this->perPageAsignados, ['*'], 'page_asignados');
    }

    // Método para cargar usuarios (mantenido para compatibilidad, pero ahora usa los getters)
    public function cargarUsuarios()
    {
        // Los datos se cargan automáticamente a través de los métodos computados
        // Este método se mantiene para cuando se necesite forzar una recarga
    }

    // Método para agregar usuario al perfil
    public function agregarUsuarioAlPerfil($userId)
    {
        $user = User::findOrFail($userId);

        // Verificar si el usuario ya tenía otro perfil asignado
        $teniaOtroPerfil = $user->perfilesPuntos()
            ->where('perfil_punto_id', '!=', $this->perfilSeleccionado->id)
            ->exists();

        // Usar sync para que el usuario solo tenga este perfil (elimina cualquier otro perfil previo)
        $user->perfilesPuntos()->sync([$this->perfilSeleccionado->id]);

        $mensaje = $teniaOtroPerfil
            ? "Usuario {$user->name} reasignado al perfil exitosamente."
            : "Usuario {$user->name} agregado al perfil exitosamente.";

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => $mensaje,
        ]);
    }

    // Método para quitar usuario del perfil
    public function quitarUsuarioDelPerfil($userId)
    {
        $user = User::findOrFail($userId);
        $this->perfilSeleccionado->usuarios()->detach($userId);

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
        $this->resetPage('page_disponibles');
        $this->resetPage('page_asignados');
    }

    // Métodos para actualizar búsquedas
    public function updatedSearchUsuariosDisponibles()
    {
        $this->resetPage('page_disponibles');
    }

    public function updatedSearchUsuariosAsignados()
    {
        $this->resetPage('page_asignados');
    }
}
