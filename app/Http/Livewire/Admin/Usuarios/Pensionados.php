<?php

namespace App\Http\Livewire\Admin\Usuarios;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Pensione;
use Illuminate\Support\Facades\Validator;

class Pensionados extends Component
{

    public $user, $dias, $fecha;
    public $seleccionado;
    

    public function seleccionar($id){
       $user=User::find($id);
        $this->seleccionado=$user;
       
    }

    public function agregardias()
    {
        if($this->dias!=null && $this->dias !="")
        {
            $siexiste=Pensione::where('user_id',$this->seleccionado->id)->get()->first();
            $fecha_actual = date("Y-m-d");
            
            if($siexiste!=null)
            {
               
               $siexiste->fecha_venc=date("Y-m-d",strtotime($siexiste->fecha_venc."+ ".($this->dias)." days"));
             
               $siexiste->save();
              
            }
            else
            {
              $siexiste=  Pensione::create([
                    'user_id' => $this->seleccionado->id,
                    'fecha_venc' => date("Y-m-d",strtotime($fecha_actual."+ ".$this->dias." days")),   
                ]);
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Nuevo pensionado agregado!"
                ]);
            } 
            $this->seleccionar($siexiste->user_id);
        }
        else
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Agregue una cantidad!"
            ]);
        }
       
    }

    public function agregarfecha()
    {
        if($this->fecha!=null && $this->fecha !="")
        {
            $siexiste=Pensione::where('user_id',$this->seleccionado->id)->get()->first();
            if($siexiste!=null)
            {
               $siexiste->fecha_venc=$this->fecha;
               $siexiste->save();
               $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Agregado por fecha!"
                ]);
            }
            else
            {
               $siexiste= Pensione::create([
                    'user_id' => $this->seleccionado->id,
                    'fecha_venc' => $this->fecha,   
                ]);
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Nuevo pensionado agregado!"
                ]);
            }
            $this->seleccionar($siexiste->user_id);
            
        }
        else
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Seleccione una fecha!"
            ]);
        }
       
    }
    public function render()
    {
        $usuarios=[];
        if($this->user!=null && $this->user!="")
        {
            $usuarios=User::where('name','LIKE','%'.$this->user.'%')->take(3)->get();
        }
        
        return view('livewire.admin.usuarios.pensionados',compact('usuarios'))
        ->extends('admin.master')
        ->section('content');
    }
}
