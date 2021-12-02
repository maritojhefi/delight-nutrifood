<?php

namespace App\Http\Livewire\Perifericos;

use Livewire\Component;
use App\Models\Sucursale;
use Rawilk\Printing\Facades\Printing;

class ImpresorasIndex extends Component
{
    public $sucursalSeleccionada, $impresoras;

    public function seleccionarSucursal(Sucursale $sucursal)
    {
        $this->sucursalSeleccionada=$sucursal;
       
        $this->encontrarImpresoras();
    }

    public function resetSucursal()
    {
        $this->reset('sucursalSeleccionada');
    }

    public function guardarImpresora($idImpresora)
    {
        //dd($idImpresora);
        $this->sucursalSeleccionada->id_impresora=$idImpresora;
        $this->sucursalSeleccionada->save();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se guardo esta impresora para: ".$this->sucursalSeleccionada->nombre
        ]);
    }
    public function encontrarImpresoras()
    {
        $printers = Printing::printers();
        
        $impresoras=collect();
        foreach ($printers as $printer) {
          $idprinter= $printer->id();
          $nombre= $printer->name();
          $status= $printer->status();
          $isonline=$printer->isOnline();
          $impresoras->push(['idprinter'=>$idprinter,'nombre'=>$nombre,'status'=>$status,'isonline'=>$isonline]);
        }
        $this->impresoras=$impresoras;
    }
    public function render()
    {
        $sucursales=Sucursale::all();
        return view('livewire.perifericos.impresoras-index',compact('sucursales'))
        ->extends('admin.master')
        ->section('content');
    }
}
