<?php

namespace App\Http\Livewire\Admin\Usuarios;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use Livewire\Component;
use App\Helpers\CreateList;
use Illuminate\Support\Facades\DB;

class Planes extends Component
{
    public $user, $seleccionado;
    public $lista;
    
    public function seleccionar($id){
        $user=User::find($id);
         $this->seleccionado=$user;
         $this->lista=CreateList::crearlistaplan($this->seleccionado->id);
        
        
     }
     public function agregar(Plane $plan)
    {
        $user=User::find($this->seleccionado->id);
        $registro = DB::table('plane_user')->where('user_id',$user->id)->where('plane_id',$plan->id)->get();
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
            $ahora = Carbon::now();
            $diassumados = $ahora->add($plan->dias, 'day');//no se usa
            $formateado=$diassumados->format('Y-m-d');//no se usa
            $user->planes()->attach($plan->id);
            
            DB::table('plane_user')->where('user_id',$user->id)
            ->where('plane_id',$plan->id)
            ->update(['start'=>$ahora,'end'=>$ahora,'title'=>$plan->nombre]);
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Se agrego satisfactoriamente!"
            ]);
            $this->seleccionado=$user;
            $this->lista=CreateList::crearlistaplan($this->seleccionado->id);
        }
     
    }

    public function eliminar(Plane $plan)
    {
        $user=User::find($this->seleccionado->id);
        $user->planes()->detach($plan->id);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'warning',
            'message'=>"Se elimino el plan de este usuario!"
        ]);
        $this->seleccionado=$user;
        $this->lista=CreateList::crearlistaplan($this->seleccionado->id);
    }
    public function adicionar(Plane $plan)
    {
       DB::table('plane_user')
       ->where('plane_id',$plan->id)
       ->where('user_id',$this->seleccionado->id)
       ->increment('restante',1);
        $this->seleccionar($this->seleccionado->id);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se sumo 1 unidad a este plan!"
        ]);
    }
    public function restar(Plane $plan)
    {
        DB::table('plane_user')
       ->where('plane_id',$plan->id)
       ->where('user_id',$this->seleccionado->id)
       ->decrement('restante',1);
        $this->seleccionar($this->seleccionado->id);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se resto 1 unidad a este plan!"
        ]);
    }
    public function render()
    {
        $usuarios=[];
        if($this->user!=null && $this->user!="")
        {
            $usuarios=User::where('name','LIKE','%'.$this->user.'%')->take(3)->get();
        }
        $planes=Plane::all();
        return view('livewire.admin.usuarios.planes',compact('usuarios','planes'))
        ->extends('admin.master')
        ->section('content');
       
    }
}
