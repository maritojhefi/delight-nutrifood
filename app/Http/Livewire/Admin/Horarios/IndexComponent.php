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
        $this->selectedSubcategoriaIds = array_values(
            array_filter($this->selectedSubcategoriaIds, function ($sid) use ($id) {
                return (int) $sid !== $id;
            }),
        );
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
        
        // Asignar la siguiente posición disponible
        $maxPosicion = Horario::max('posicion') ?? 0;
        $nuevaPosicion = $maxPosicion + 1;
        
        Horario::create([
            'nombre' => $this->nombre,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'posicion' => $nuevaPosicion,
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
        $this->validate(
            [
                'nombre' => 'required|string|min:3|max:255|unique:horarios,nombre,' . $this->horario->id,
                'hora_inicio' => 'required',
                'hora_fin' => 'required|after:hora_inicio',
            ],
            $this->messages,
        );
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
            
            // Reorganizar posiciones después de eliminar
            $this->reorganizarPosicionesDespuesDeEliminar();
            
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
                ->orderBy('posicion', 'ASC')
                ->get();
        } else {
            $horarios = Horario::orderBy('posicion', 'ASC')->get();
        }
        $subcategorias = Subcategoria::when($this->subcategoriaSearch, function ($q) {
            $q->where('nombre', 'LIKE', '%' . $this->subcategoriaSearch . '%');
        })
            ->orderBy('nombre')
            ->get();
        return view('livewire.admin.horarios.index-component', compact('horarios', 'subcategorias'))->extends('admin.master')->section('content');
    }

    private function haySolapamiento(string $inicio, string $fin, ?int $excluirId = null): bool
    {
        $query = Horario::where('hora_inicio', '<', $fin)->where('hora_fin', '>', $inicio);
        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }
        return $query->exists();
    }

    public function subirPosicion($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Horario no encontrado',
            ]);
            return;
        }

        // Obtener el total de horarios para validar límites
        $totalHorarios = Horario::count();
        
        // No permitir subir más allá del total de registros
        if ($horario->posicion >= $totalHorarios) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'No se puede subir más la posición. Ya está en la posición máxima.',
            ]);
            return;
        }

        // Buscar el horario que está en la posición siguiente
        $horarioSiguiente = Horario::where('posicion', $horario->posicion + 1)->first();
        
        if ($horarioSiguiente) {
            // Intercambiar posiciones
            $horarioSiguiente->posicion = $horario->posicion;
            $horario->posicion = $horario->posicion + 1;
            
            $horarioSiguiente->save();
            $horario->save();
            
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Posición del horario '{$horario->nombre}' aumentada correctamente",
            ]);
        }
    }

    public function bajarPosicion($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Horario no encontrado',
            ]);
            return;
        }

        // No permitir bajar más allá de la posición 1
        if ($horario->posicion <= 1) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'No se puede bajar más la posición. Ya está en la posición mínima.',
            ]);
            return;
        }

        // Buscar el horario que está en la posición anterior
        $horarioAnterior = Horario::where('posicion', $horario->posicion - 1)->first();
        
        if ($horarioAnterior) {
            // Intercambiar posiciones
            $horarioAnterior->posicion = $horario->posicion;
            $horario->posicion = $horario->posicion - 1;
            
            $horarioAnterior->save();
            $horario->save();
            
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Posición del horario '{$horario->nombre}' disminuida correctamente",
            ]);
        }
    }

    /**
     * Inicializar posiciones de todos los horarios
     * Útil para cuando se agregan nuevos horarios o se reorganiza
     */
    public function inicializarPosiciones()
    {
        $horarios = Horario::orderBy('posicion', 'ASC')->get();
        $posicion = 1;
        
        foreach ($horarios as $horario) {
            $horario->posicion = $posicion;
            $horario->save();
            $posicion++;
        }
        
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Posiciones de todos los horarios han sido inicializadas correctamente',
        ]);
    }

    /**
     * Reorganizar posiciones después de eliminar un horario
     * Asegura que las posiciones sean consecutivas del 1 al N
     */
    private function reorganizarPosicionesDespuesDeEliminar()
    {
        $horarios = Horario::orderBy('posicion', 'ASC')->get();
        $posicion = 1;
        
        foreach ($horarios as $horario) {
            if ($horario->posicion != $posicion) {
                $horario->posicion = $posicion;
                $horario->save();
            }
            $posicion++;
        }
    }

    /**
     * Mover un horario a una posición específica
     * Útil para reorganización manual
     */
    public function moverAPosicion($id, $nuevaPosicion)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Horario no encontrado',
            ]);
            return;
        }

        $totalHorarios = Horario::count();
        
        // Validar que la nueva posición esté dentro de los límites
        if ($nuevaPosicion < 1 || $nuevaPosicion > $totalHorarios) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => "La posición debe estar entre 1 y {$totalHorarios}",
            ]);
            return;
        }

        $posicionActual = $horario->posicion;
        
        if ($posicionActual == $nuevaPosicion) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'info',
                'message' => 'El horario ya está en esa posición',
            ]);
            return;
        }

        // Si se mueve hacia arriba (posición menor)
        if ($nuevaPosicion < $posicionActual) {
            // Mover todos los horarios entre la nueva posición y la actual hacia abajo
            Horario::whereBetween('posicion', [$nuevaPosicion, $posicionActual - 1])
                ->increment('posicion');
        } else {
            // Si se mueve hacia abajo (posición mayor)
            // Mover todos los horarios entre la actual y la nueva posición hacia arriba
            Horario::whereBetween('posicion', [$posicionActual + 1, $nuevaPosicion])
                ->decrement('posicion');
        }

        // Asignar la nueva posición al horario
        $horario->posicion = $nuevaPosicion;
        $horario->save();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Horario '{$horario->nombre}' movido a la posición {$nuevaPosicion}",
        ]);
    }
}
