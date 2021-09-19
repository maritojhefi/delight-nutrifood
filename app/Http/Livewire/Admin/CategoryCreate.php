<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;

class CategoryCreate extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nombre, $descripcion;
    
 
    protected $rules = [
        'nombre' => 'required',
        'descripcion' => 'required|min:5',
        
        
    ];
 
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
 
        Categoria::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            
            
        ]);
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Categoria: ".$this->nombre." creada satisfactoriamente!!"
        ]);
        $this->reset();
    }

    public function eliminar(Categoria $subcat)
    {
        if($subcat->subcategorias)
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"No se puede eliminar este registro porque esta anidado con otros registros"
            ]);
        }
        else
        {
            $subcat->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Categoria: ".$subcat->nombre." eliminado"
            ]);
        }
        
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $categorias=Categoria::paginate(5);
        return view('livewire.admin.category-create',compact('categorias'));
    }
}
