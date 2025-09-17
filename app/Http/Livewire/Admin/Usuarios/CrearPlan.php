<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\Plane;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Horario;
use Livewire\WithPagination;

class CrearPlan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nombre, $producto, $detalle, $productoseleccionado;

    public $search;

    public $planSeleccionado;
    public $horarios;

    // Propiedades para el modal de horarios
    public $planIdHorario;
    public $horarioSeleccionado;
    public $showModalHorario = false;
    protected $rules = [
        'nombre' => 'required|min:5',

        'detalle' => 'required|min:5',
    ];
    public function seleccionarPlan(Plane $plan)
    {
        $this->planSeleccionado = $plan;
    }
    public function cambiarSopa()
    {
        if ($this->planSeleccionado->sopa) {
            $this->planSeleccionado->sopa = false;
        } else {
            $this->planSeleccionado->sopa = true;
        }
        $this->planSeleccionado->save();
    }
    public function cambiarSegundo()
    {
        if ($this->planSeleccionado->segundo) {
            $this->planSeleccionado->segundo = false;
        } else {
            $this->planSeleccionado->segundo = true;
        }
        $this->planSeleccionado->save();
    }
    public function cambiarCarbohidrato()
    {
        if ($this->planSeleccionado->carbohidrato) {
            $this->planSeleccionado->carbohidrato = false;
        } else {
            $this->planSeleccionado->carbohidrato = true;
        }
        $this->planSeleccionado->save();
    }
    public function cambiarEnsalada()
    {
        if ($this->planSeleccionado->ensalada) {
            $this->planSeleccionado->ensalada = false;
        } else {
            $this->planSeleccionado->ensalada = true;
        }
        $this->planSeleccionado->save();
    }
    public function cambiarJugo()
    {
        if ($this->planSeleccionado->jugo) {
            $this->planSeleccionado->jugo = false;
        } else {
            $this->planSeleccionado->jugo = true;
        }
        $this->planSeleccionado->save();
    }
    public function seleccionarproducto(Producto $prod)
    {
        $this->productoseleccionado = $prod;
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => $prod->nombre . ' seleccionado!',
        ]);
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function cambiarEditable(Plane $plane)
    {
        if ($plane->editable == true) {
            $plane->editable = false;
        } else {
            $plane->editable = true;
        }
        $plane->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se cambio el estado de este plan!!',
        ]);
    }
    public function cambiarAsignacion(Plane $plane)
    {
        if ($plane->asignado_automatico == true) {
            $plane->asignado_automatico = false;
        } else {
            $plane->asignado_automatico = true;
        }
        $plane->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se cambio el estado de este plan!!',
        ]);
    }
    public function resetproducto()
    {
        $this->reset('productoseleccionado');
    }

    public function submit()
    {
        $this->validate();

        // Execution doesn't reach here if validation fails.

        Plane::create([
            'nombre' => $this->nombre,

            'detalle' => $this->detalle,
            'producto_id' => $this->productoseleccionado->id,
        ]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Plan: ' . $this->nombre . ' creado satisfactoriamente!!',
        ]);
        $this->reset();
    }

    public function eliminar(Plane $plan)
    {
        try {
            $plan->delete();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Plan: ' . $plan->nombre . ' eliminado',
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'No se puede eliminar este registro porque esta anidado a otros',
            ]);
        }
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Métodos para manejar horarios
    public function agregarHorario($planId)
    {

        // dd($this->horarios->count());
        if ($this->horarios->count() == 0) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'No hay horarios disponibles',
            ]);
            return;
        } else {
            $this->planIdHorario = $planId;
            $plan = Plane::find($planId);
            $this->horarioSeleccionado = $plan->horario_id;
            $this->showModalHorario = true;
            $this->emit('mostrarModalHorario');
        }
    }

    public function guardarHorario()
    {
        $plan = Plane::find($this->planIdHorario);
        $plan->horario_id = $this->horarioSeleccionado;
        $plan->save();

        $this->showModalHorario = false;
        $this->reset(['planIdHorario', 'horarioSeleccionado']);

        $this->emit('cerrarModalHorario');
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Horario actualizado correctamente!',
        ]);
    }

    public function cerrarModalHorario()
    {
        $this->showModalHorario = false;
        $this->reset(['planIdHorario', 'horarioSeleccionado']);
    }
    public function render()
    {
        $productos = [];
        if ($this->producto != null && $this->producto != '') {
            $productos = Producto::where('nombre', 'LIKE', '%' . $this->producto . '%')
                ->take(5)
                ->get();
        }
        $planes = Plane::with('horario')->when($this->search, function ($query) {
            $query->where('nombre', 'LIKE', '%' . $this->search . '%');
        })->paginate(5);

        $this->horarios = Horario::orderBy('posicion')->get();
        return view('livewire.admin.usuarios.crear-plan', compact('planes', 'productos'))
            ->extends('admin.master')
            ->section('content');
    }
}
