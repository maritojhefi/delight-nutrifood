<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Adicionale;
use App\Models\Subcategoria;
use Illuminate\Support\Facades\DB;

class AgregarAdicional extends Component
{   
    public $subcategoria;
    public $search;
    public $searchSub;

    public function seleccionado(Subcategoria $sub)
    {
        $this->subcategoria=$sub;
    }
    public function agregar(Adicionale $ad)
    {
        $sub=Subcategoria::find($this->subcategoria->id);
        $registro = DB::table('adicionale_subcategoria')->where('subcategoria_id',$sub->id)->where('adicionale_id',$ad->id)->get();
        //dd($registro);
        if($registro->count()!=0)
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Ya se encuentra agregado!"
            ]);
        }
        else
        {
            $sub->adicionales()->attach($ad->id);
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Se agrego satisfactoriamente!"
            ]);
            $this->subcategoria=$sub;
        }
     
    }

    public function eliminar(Adicionale $ad)
    {
        $sub=Subcategoria::find($this->subcategoria->id);
        $sub->adicionales()->detach($ad->id);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'warning',
            'message'=>"Se elimino el adicional en esta categoria!"
        ]);
        $this->subcategoria=$sub;
    }

    public function render()
    {
        $adicionales=Adicionale::where('nombre','LIKE','%'.$this->search.'%')->take(5)->get();

        $subcategorias=Subcategoria::where('nombre','LIKE','%'.$this->searchSub.'%')->get();
        return view('livewire.admin.productos.agregar-adicional',compact('subcategorias','adicionales'))
        ->extends('admin.master')
        ->section('content');
    }
}
