<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Adicionale;
use App\Models\Subcategoria;
use Livewire\WithPagination;

class Adicionales extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nombre, $precio, $adicional;
   
 
    protected $rules = [
        'nombre' => 'required|unique:adicionales,nombre',
        'precio' => 'required|numeric',
        
        
    ];
 
    public function editar(Adicionale $adicional)
    {
        $this->adicional=$adicional;
        $this->nombre=$adicional->nombre;
        $this->precio=$adicional->precio;
    }
    public function guardarEdit()
    {
        $this->validate([
            'nombre' => 'required|unique:adicionales,nombre,'.$this->adicional->id,
            'precio' => 'required|numeric',
        ]);
        $this->adicional->nombre=$this->nombre;
        $this->adicional->precio=$this->precio;
        $this->adicional->save();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Adicional actualizado!!"
        ]);
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
 
        Adicionale::create([
            'nombre' => $this->nombre,
            'precio' => $this->precio,
          
            
        ]);
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Adicional: ".$this->nombre." creada satisfactoriamente!!"
        ]);
        $this->reset();
    }

    public function eliminar(Adicionale $adic)
    {
       

        try {
            $adic->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Adicional : ".$adic->nombre." eliminado"
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
        $adicionales=Adicionale::paginate(5);
        $subcategorias=Subcategoria::all();
        return view('livewire.admin.productos.adicionales',compact('adicionales','subcategorias'))
        ->extends('admin.master')
        ->section('content');
    }
  
}
