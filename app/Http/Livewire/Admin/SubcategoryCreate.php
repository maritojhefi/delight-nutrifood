<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Livewire\WithPagination;

class SubcategoryCreate extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nombre, $descripcion, $categoria;
    protected $listeners = ['listar' => 'render'];
 
    protected $rules = [
        'nombre' => 'required',
        'descripcion' => 'required|min:5',
        'categoria' => 'required|integer',
        
    ];
 
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
 
        Subcategoria::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'categoria_id' => $this->categoria,
            
        ]);
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Subcategoria: ".$this->nombre." creada satisfactoriamente!!"
        ]);
        $this->reset();
    }

    public function eliminar(Subcategoria $subcat)
    {
        $subcat->delete();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'warning',
            'message'=>"Subcategoria: ".$subcat->nombre." eliminado"
        ]);
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $subcategorias=Subcategoria::paginate(5);
        $categorias=Categoria::all();
        return view('livewire.admin.subcategory-create',compact('subcategorias','categorias'));
    }
}
