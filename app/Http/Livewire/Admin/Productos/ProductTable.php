<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class ProductTable extends Component
{
    use WithPagination;
    public $buscar;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['buscar'];

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $productos=Producto::where('nombre','LIKE','%'.$this->buscar.'%')->orWhere('estado',$this->buscar)->orderBy('created_at','desc')->paginate(5);
        return view('livewire.admin.productos.product-table',compact('productos'));
    }

    public function cambiarestado(Producto $producto)
    {
        
        if($producto->estado=='activo')
        {
            $producto->estado='inactivo';
            $producto->save();
        }
        else{
            $producto->estado='activo';
            $producto->save();
        }
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"El producto ".$producto->nombre." cambio de estado!"
        ]);
        
    }
    public function cambiarcontable(Producto $producto)
    {
        
        if($producto->contable==true)
        {
            $producto->contable=false;
            $producto->save();
        }
        else{
            $producto->contable=true;
            $producto->save();
        }
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se guardo el cambio!"
        ]);
        
    }
    public function eliminar(Producto $prod)
    {
        try {
            Storage::disk('public_images')->delete('productos/'.$prod->imagen);
            $prod->delete();
            
            
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Producto: ".$prod->nombre." eliminado"
            ]);
    
           
            
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Ups! no puedes borrar productos que tengan stock"
            ]);
        }
      
    }
}
