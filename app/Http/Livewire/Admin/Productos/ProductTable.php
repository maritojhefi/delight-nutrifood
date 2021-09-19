<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;

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
        $productos=Producto::where('nombre','LIKE','%'.$this->buscar.'%')->orWhere('estado',$this->buscar)->orderBy('created_at','asc')->paginate(5);
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
    public function eliminar(Producto $prod)
    {
        $prod->delete();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'warning',
            'message'=>"Producto: ".$prod->nombre." eliminado"
        ]);
    }
}
