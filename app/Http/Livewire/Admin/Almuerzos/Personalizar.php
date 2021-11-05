<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use Livewire\Component;
use App\Models\Almuerzo;

class Personalizar extends Component
{
    public $sopa, $ensalada, $ejecutivo, $dieta, $vegetariano, $carbohidrato_1, $carbohidrato_2, $carbohidrato_3, $jugo;
    public $seleccionado;
    protected $rules = [
        'sopa' => 'required',
        'ensalada'  => 'required|min:5',
        'ejecutivo'=>'required',
        'dieta'=>'required',
        'vegetariano'=>'required',
        'carbohidrato_1'=>'required',
        'carbohidrato_2'=>'required',
        'carbohidrato_3'=>'required',
        'jugo'=>'required|min:4'
        
    ];
 
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function editar(Almuerzo $almuerzo){
        $this->seleccionado=$almuerzo;
        $this->sopa=$almuerzo->sopa;
        $this->ensalada=$almuerzo->ensalada;
        $this->ejecutivo=$almuerzo->ejecutivo;
        $this->dieta=$almuerzo->dieta;
        $this->vegetariano=$almuerzo->vegetariano;
        $this->carbohidrato_1=$almuerzo->carbohidrato_1;
        $this->carbohidrato_2=$almuerzo->carbohidrato_2;
        $this->carbohidrato_3=$almuerzo->carbohidrato_3;
        $this->jugo=$almuerzo->jugo;

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se selecciono dia ".$almuerzo->dia
        ]);
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function actualizar(){
        $this->validate();
        $almuerzo = Almuerzo::find($this->seleccionado->id);
        
        $almuerzo->update([
            'sopa'=>$this->sopa,
            'ensalada'=>$this->ensalada,
            'ejecutivo'=>$this->ejecutivo,
            'dieta'=>$this->dieta,
            'vegetariano'=>$this->vegetariano,
            'carbohidrato_1'=>$this->carbohidrato_1,
            'carbohidrato_2'=>$this->carbohidrato_2,
            'carbohidrato_3'=>$this->carbohidrato_3,
            'jugo'=>$this->jugo,
        ]);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"El dia ".$almuerzo->dia." fue actualizado!"
        ]);
    }
    public function render()
    {
        $almuerzos=Almuerzo::all();
        return view('livewire.admin.almuerzos.personalizar',compact('almuerzos'))
        ->extends('admin.master')
        ->section('content');
    }
}
