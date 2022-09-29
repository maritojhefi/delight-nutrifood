<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Tutoriale;
use Livewire\WithPagination;

class TutorialesComponent extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    public $tipo;
    public $titulo,$descripcion,$url;
    public $search;
    public function resetTipo()
    {
        $this->reset('tipo');
    }
    public function delete(Tutoriale $tutorial)
    {
        $tutorial->delete();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => "Se elimino el tutorial"
        ]);
    }
    public function elegirTipo($tipo)
    {
        $this->reset();
        $this->tipo=$tipo;
    }
    public function submit()
    {
        if($this->tipo=='youtube')
        {
            $array=$this->validate([
                'titulo'=>'required|max:35',
                'descripcion'=>'required',
                'url'=>'required|url',
                'tipo'=>'required'
            ]);
        }
        else
        {
            $this->titulo="Video desde ".$this->tipo;
            $this->descripcion="Video desde ".$this->tipo;
            $array=$this->validate([
                'titulo'=>'required|max:35',
                'descripcion'=>'required',
                'url'=>'required',
                'tipo'=>'required'
            ]);
        }
        Tutoriale::create($array);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se agrego un nuevo video!"
        ]);
        $this->reset();
    }
    public function render()
    {
        if($this->search)
        {
            $tutoriales=Tutoriale::where('titulo','LIKE','%'.$this->search.'%')->orWhere('descripcion','LIKE','%'.$this->search.'%')->paginate(5);
        }
        else
        {
            $tutoriales=Tutoriale::paginate(5);
        }
        
        return view('livewire.admin.tutoriales-component',compact('tutoriales'))
        ->extends('admin.master')
        ->section('content');
    }
}
