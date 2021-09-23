<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RolesIndex extends Component
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
 
        Role::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            
            
        ]);
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Rol: ".$this->nombre." creado satisfactoriamente!!"
        ]);
        $this->reset();
    }

    public function eliminar(Role $rol)
    {
        
        try {
            $rol->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Categoria: ".$rol->nombre." eliminado"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"No se puede eliminar este registro porque esta anidado con otros registros"
            ]);
        }
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $roles=Role::paginate(5);
        return view('livewire.admin.usuarios.roles-index',compact('roles'))
        ->extends('admin.master')
        ->section('content');
    }
}
