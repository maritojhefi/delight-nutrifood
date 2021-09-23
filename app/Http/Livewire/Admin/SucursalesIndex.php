<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Sucursale;

class SucursalesIndex extends Component
{
    public $nombre, $direccion, $telefono;
   
 
    protected $rules = [
        'nombre' => 'required|min:5',
        'direccion' => 'required|min:5',
        'telefono' => 'required|integer|min:7',
        
    ];
 
   
    public function submit()
    {
        
        if($this->validate())
        {
            Sucursale::create([
                'nombre' => $this->nombre,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                
            ]);
             $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Sucursal: ".$this->nombre." creada satisfactoriamente!!"
            ]);
            $this->reset();
        }
        else
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Hay errores en el formulario"
            ]);
        }
 
 
        
       
    }

    public function eliminar(Sucursale $sucursal)
    {
       

        try {
            $sucursal->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Sucursal: ".$sucursal->nombre." eliminada con exito!"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"No se puede eliminar este registro porque esta vinculado a otros"
            ]);
        }
    }
   
    public function render()
    {
        $sucursales=Sucursale::all();
        return view('livewire.admin.sucursales-index',compact('sucursales'))
        ->extends('admin.master')
        ->section('content');
    }
}
