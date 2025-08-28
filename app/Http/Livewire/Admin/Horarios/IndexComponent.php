<?php

namespace App\Http\Livewire\Admin\Horarios;

use App\Models\Horario;
use App\Models\Subcategoria;
use Livewire\Component;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    public $horario;
    public $nombre;
    public $hora_inicio;
    public $hora_fin;

    protected $listeners = ['eliminar-horario' => 'eliminar'];

    // Gestión de subcategorías
    public $subcategoriaSearch;
    public $horarioSubcategorias; // Horario seleccionado para asignar subcategorías
    public $selectedSubcategoriaIds = [];

    public $alerta = true;


    protected $queryString = [
        'alerta' => ['except' => true],
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'nombre' => 'required|string|min:3|max:255|unique:horarios,nombre',
        'hora_inicio' => 'required',
        'hora_fin' => 'required|after:hora_inicio',
    ];

    protected $messages = [
        'nombre.required' => 'Debe ingresar un nombre para el horario.',
        'nombre.string' => 'El nombre del horario debe ser una cadena de texto.',
        'nombre.min' => 'El nombre del horario debe tener al menos 3 caracteres.',
        'nombre.max' => 'El nombre del horario debe tener menos de 255 caracteres.',
        'nombre.unique' => 'El nombre del horario ya existe.',
        'hora_inicio.required' => 'La hora de inicio es requerida.',
        'hora_fin.required' => 'La hora de fin es requerida.',
        'hora_fin.after' => 'La hora de fin debe ser mayor a la hora de inicio.',
    ];

    public function cerrarAlerta()
    {
        $this->alerta = false;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function crearNuevo()
    {
        $this->reset('horario', 'nombre', 'hora_inicio', 'hora_fin');
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function editar(Horario $horario)
    {
        $this->horario = $horario;
        $this->nombre = $horario->nombre;
        $this->hora_inicio = $horario->hora_inicio;
        $this->hora_fin = $horario->hora_fin;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function editarSubCategorias($id)
    {
        $horario = Horario::with('subcategorias')->find($id);
        if (!$horario) {
            return;
        }
        $this->horarioSubcategorias = $horario;
        $this->selectedSubcategoriaIds = $horario->subcategorias->pluck('id')->toArray();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function agregarSubcategoria($id)
    {
        $id = (int) $id;
        if (!in_array($id, $this->selectedSubcategoriaIds, true)) {
            $this->selectedSubcategoriaIds[] = $id;
        }
    }

    public function quitarSubcategoria($id)
    {
        $id = (int) $id;
        $this->selectedSubcategoriaIds = array_values(array_filter($this->selectedSubcategoriaIds, function ($sid) use ($id) {
            return (int) $sid !== $id;
        }));
    }

    public function guardarSubcategorias()
    {
        if (!$this->horarioSubcategorias) {
            return;
        }
        $this->horarioSubcategorias->subcategorias()->sync($this->selectedSubcategoriaIds);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Subcategorías actualizadas',
        ]);
        $this->emit('close-modal-subcategorias');
    }
    public function guardar()
    {
        if ($this->horario) {
            $this->guardarEdit();
            return;
        }
        $this->submit();
    }
    public function submit()
    {
        $this->validate();
        if ($this->haySolapamiento($this->hora_inicio, $this->hora_fin, null)) {
            $this->addError('hora_inicio', 'Existe otro horario con ese rango de tiempo.');
            $this->addError('hora_fin', 'Existe otro horario con ese rango de tiempo.');
            return;
        }
        Horario::create([
            'nombre' => $this->nombre,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
        ]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Horario creado correctamente',
        ]);
        $this->reset('horario', 'nombre', 'hora_inicio', 'hora_fin');
        $this->emit('close-modal-horario');
    }

    public function guardarEdit()
    {
        $this->validate([
            'nombre' => 'required|string|min:3|max:255|unique:horarios,nombre,' . $this->horario->id,
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
        ], $this->messages);
        if ($this->haySolapamiento($this->hora_inicio, $this->hora_fin, $this->horario->id)) {
            $this->addError('hora_inicio', 'Existe otro horario con ese rango de tiempo.');
            $this->addError('hora_fin', 'Existe otro horario con ese rango de tiempo.');
            return;
        }
        $this->horario->nombre = $this->nombre;
        $this->horario->hora_inicio = $this->hora_inicio;
        $this->horario->hora_fin = $this->hora_fin;
        $this->horario->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Horario actualizado correctamente',
        ]);
        $this->reset('horario', 'nombre', 'hora_inicio', 'hora_fin');
        $this->emit('close-modal-horario');
    }

    public function eliminar($id)
    {
        try {
            $horario = Horario::find($id);
            if ($horario->subcategorias->count() > 0) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'El horario tiene subcategorías asociadas, no se puede eliminar',
                ]);
                return;
            }
            $horario->delete();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Horario eliminado',
            ]);
            $this->reset('horarioSubcategorias', 'selectedSubcategoriaIds', 'subcategoriaSearch');
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al eliminar el horario',
            ]);
        }
    }

    public function render()
    {
        if ($this->search) {
            $horarios = Horario::where('nombre', 'LIKE', '%' . $this->search . '%')
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $horarios = Horario::orderBy('id', 'DESC')->get();
        }
        $subcategorias = Subcategoria::when($this->subcategoriaSearch, function ($q) {
            $q->where('nombre', 'LIKE', '%' . $this->subcategoriaSearch . '%');
        })
            ->orderBy('nombre')
            ->get();
        return view('livewire.admin.horarios.index-component', compact('horarios', 'subcategorias'))
            ->extends('admin.master')
            ->section('content');
    }

    private function haySolapamiento(string $inicio, string $fin, ?int $excluirId = null): bool
    {
        $query = Horario::where('hora_inicio', '<', $fin)
            ->where('hora_fin', '>', $inicio);
        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }
        return $query->exists();
    }
}
