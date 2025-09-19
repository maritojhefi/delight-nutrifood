<?php

namespace App\Http\Livewire\Admin;

use App\Helpers\GlobalHelper;
use App\Models\User;
use Livewire\Component;
use App\Models\Convenio;
use Livewire\WithPagination;

class ConveniosUsuariosComponent extends Component
{
    use WithPagination;

    public $convenio_id;
    public $usuarios_seleccionados = [];
    public $buscar;
    public $usuariosDisponibles = [];

    protected $listeners = ['usuariosVinculados' => 'render', 'eliminar-usuario-convenio' => 'eliminarUsuarioConvenio'];

    public function agregarUsuarios($convenioId)
    {
        $this->convenio_id = $convenioId;
        $this->cargarUsuariosDisponibles();
        $this->cargarUsuariosVinculados();

        // Forzar re-render para asegurar que los datos estén disponibles
        $this->render();

        // Pequeño delay para asegurar que los datos estén disponibles
        $this->dispatchBrowserEvent('abrirModalUsuarios');
    }
    public function cargarUsuariosVinculados()
    {
        $convenio = Convenio::find($this->convenio_id);
        $this->usuarios_seleccionados = $convenio ? $convenio->usuarios->pluck('id')->toArray() : [];
    }
    public function cargarUsuariosDisponibles()
    {
        $usuariosVinculadosEsteConvenio = Convenio::find($this->convenio_id)->usuarios()->pluck('users.id');

        $this->usuariosDisponibles = User::where('role_id', 4)
            ->where(function ($query) use ($usuariosVinculadosEsteConvenio) {
                // Usuarios no vinculados a NINGÚN convenio
                $query
                    ->whereDoesntHave('convenios')
                    // O usuarios vinculados solo a ESTE convenio
                    ->orWhereIn('id', $usuariosVinculadosEsteConvenio);
            })
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')',
                ];
            })
            ->toArray();
    }

    public function vincularUsuarios()
    {
        $this->validate([
            'usuarios_seleccionados' => 'array',
            'usuarios_seleccionados.*' => [
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user && $user->role_id != 4) {
                        $fail('Solo se pueden vincular usuarios con rol específico.');
                    }
                },
            ],
        ]);

        $convenio = Convenio::findOrFail($this->convenio_id);
        $convenio->usuarios()->sync($this->usuarios_seleccionados ?? []);

        // Actualizar la lista de disponibles después de sync
        $this->cargarUsuariosDisponibles();

        $this->emit('usuariosVinculados');
        $this->emit('cerrarModalUsuarios');
        $this->emit('notificar', [
            'tipo' => 'success',
            'mensaje' => 'Vinculación de usuarios actualizada correctamente.',
        ]);
    }
    public function verUsuarios($convenioId)
    {
        $convenio = Convenio::findOrFail($convenioId);
        // Obtener usuarios relacionados con sus timestamps del pivot
        $usuariosConvenio = $convenio->usuarios->sortByDesc(function ($usuario) {
            return $usuario->pivot->created_at;
        });
        $arrayUsuarios = [];
        // Iterar sobre los usuarios y mostrar sus datos
        foreach ($usuariosConvenio as $usuario) {
            array_push($arrayUsuarios, [
                'id' => $usuario->id,
                'nombre' => $usuario->name,
                'telf' => $usuario->telf,
                'fecha_creacion' => GlobalHelper::fechaFormateada(4, $usuario->pivot->created_at),
                'hora_creacion' => GlobalHelper::fechaFormateada(9, $usuario->pivot->created_at),
                'hace_tiempo' => GlobalHelper::timeago($usuario->pivot->created_at),
            ]);
        }
        $this->emit('mostrar-usuarios', $arrayUsuarios);
    }

    public function eliminarUsuarioConvenio($convenioId, $usuarioId)
    {
        $convenio = Convenio::findOrFail($convenioId);
        $convenio->usuarios()->detach($usuarioId);
        $this->emit('notificar', [
            'tipo' => 'success',
            'mensaje' => 'Usuario eliminado del convenio correctamente.',
        ]);
        $this->verUsuarios($convenio->id);
    }

    public function getUsuariosDisponibles($convenioId)
    {
        $this->convenio_id = $convenioId;
        $this->cargarUsuariosDisponibles();
        $this->cargarUsuariosVinculados();

        return response()->json([
            'usuariosDisponibles' => $this->usuariosDisponibles,
            'usuariosSeleccionados' => $this->usuarios_seleccionados
        ]);
    }

    public function render()
    {
        $convenios = Convenio::when($this->buscar, function ($query) {
            $query->where('nombre_convenio', 'like', '%' . $this->buscar . '%');
        })->paginate(10);

        return view('livewire.admin.convenios-usuarios-component', compact('convenios'))
            ->extends('admin.master')
            ->section('content');
    }
}
