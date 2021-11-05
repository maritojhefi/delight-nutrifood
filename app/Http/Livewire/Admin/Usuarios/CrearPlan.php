<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\Plane;
use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;

class CrearPlan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nombre, $producto, $detalle, $productoseleccionado;
    
 
    protected $rules = [
        'nombre' => 'required|min:5',
       
       'detalle'=>'required|min:5'
       
    ];
 
    public function seleccionarproducto(Producto $prod)
    {
        $this->productoseleccionado=$prod;
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>$prod->nombre." seleccionado!"
        ]);
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
           
            'detalle'=>$this->detalle,
            'producto_id'=>$this->productoseleccionado->id
        ]);
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Plan: ".$this->nombre." creado satisfactoriamente!!"
        ]);
        $this->reset();
    }

    public function eliminar(Plane $plan)
    {
        
        try {
            $plan->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Plan: ".$plan->nombre." eliminado"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"No se puede eliminar este registro porque esta anidado a otros"
            ]);
        }
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $productos=[];
        if($this->producto!=null && $this->producto !="")
        {
            $productos=Producto::where('nombre','LIKE','%'.$this->producto.'%')->take(5)->get();

        }
        $planes=Plane::paginate(5);
        
        return view('livewire.admin.usuarios.crear-plan', compact('planes','productos'))
        ->extends('admin.master')
        ->section('content');
    }
}
