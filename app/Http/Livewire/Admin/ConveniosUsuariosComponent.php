<?php

namespace App\Http\Livewire\Admin;

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

    protected $listeners = ['usuariosVinculados' => 'render'];

    public function agregarUsuarios($convenioId)
    {
        $this->convenio_id = $convenioId;
        $this->cargarUsuariosDisponibles();
        $this->cargarUsuariosVinculados(); // Nueva función
        $this->emit('abrirModalUsuarios');
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
        $this->dispatchBrowserEvent('notificar', [
            'tipo' => 'success',
            'mensaje' => 'Vinculación de usuarios actualizada correctamente.',
        ]);
    }

    public function render()
    {
        $convenios = Convenio::when($this->buscar, function ($query) {
            $query->where('nombre_convenio', 'like', '%' . $this->buscar . '%');
        })->paginate(10);

        return view('livewire.admin.convenios-usuarios-component', compact('convenios'))->extends('admin.master')->section('content');
    }
}
