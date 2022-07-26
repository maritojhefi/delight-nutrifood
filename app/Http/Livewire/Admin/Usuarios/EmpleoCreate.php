<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\Contrato;
use App\Models\User;
use Livewire\Component;

class EmpleoCreate extends Component
{
    public $lunes=true, $martes=true, $miercoles=true, $jueves=true, $viernes=true, $sabado=false, $domingo=false;
    public $sueldo, $fecha_inicio, $modalidad="mensual", $observacion, $user_id,$hora_entrada, $hora_salida;
    public $seleccionado;
    protected $rules = [
        'lunes'=>'required',
            'martes'=>'required',
            'miercoles'=>'required',
            'jueves'=>'required',
            'viernes'=>'required',
            'sabado'=>'required',
            'domingo'=>'required',
            'hora_entrada'=>'required',
            'hora_salida'=>'required',
            'fecha_inicio'=>'required',
            'modalidad'=>'required',
            'user_id'=>'required',
            'sueldo'=>'required',
            'observacion'=>''
    ];
    public function seleccionar(Contrato $contrato)
    {
        // dd($contrato);
        $this->seleccionado=$contrato;
    }
    public function delete()
    {
        $this->seleccionado->delete();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Contrato eliminado!"
        ]);
        $this->reset('seleccionado');
    }
    public function crear()
    {
        $this->validate();
        $contrato = new Contrato();
        $contrato->fill($this->validate());
        $contrato->save();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Nuevo contrato creado!"
        ]);
        $this->reset();
    }
    public function render()
    {
        $contratos=Contrato::all();
        $usuarios=User::doesnthave('contrato')->where('role_id','!=',4)->get();
        return view('livewire.admin.usuarios.empleo-create',compact('usuarios','contratos'))
            ->extends('admin.master')
            ->section('content');
    }
}
