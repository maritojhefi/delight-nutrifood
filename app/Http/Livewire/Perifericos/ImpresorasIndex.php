<?php

namespace App\Http\Livewire\Perifericos;

use Livewire\Component;
use App\Models\Sucursale;
use App\Models\AreaDespacho;
use Rawilk\Printing\Facades\Printing;

class ImpresorasIndex extends Component
{
    public $sucursalSeleccionada, $impresoras, $arraySecciones;

    public function seleccionarSucursal(Sucursale $sucursal)
    {
        $this->sucursalSeleccionada = $sucursal;
        $this->arraySecciones = AreaDespacho::activos()->where('sucursale_id', $sucursal->id)->get();
        $this->encontrarImpresoras();
    }

    public function resetSucursal()
    {
        $this->reset(['sucursalSeleccionada', 'arraySecciones', 'impresoras']);
    }

    public function guardarImpresora($idImpresora)
    {
        $this->sucursalSeleccionada->id_impresora = $idImpresora;
        $this->sucursalSeleccionada->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se guardo esta impresora para: " . $this->sucursalSeleccionada->nombre
        ]);
    }

    public function guardarImpresoraArea($idArea, $idImpresora)
    {
        $area = AreaDespacho::find($idArea);
        if ($area) {
            $area->id_impresora = $idImpresora;
            $area->save();
            // Actualizar el objeto directamente en la colección sin cambiar la referencia
            $areaEnColeccion = $this->arraySecciones->firstWhere('id', $idArea);
            if ($areaEnColeccion) {
                $areaEnColeccion->id_impresora = $idImpresora;
            }
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Se guardo la impresora para el área: " . $area->nombre_area
            ]);
        }
    }
    public function encontrarImpresoras()
    {
        $printers = Printing::printers();

        $impresoras = collect();
        foreach ($printers as $printer) {
            $idprinter = $printer->id();
            $nombre = $printer->name();
            $status = $printer->status();
            $isonline = $printer->isOnline();
            $impresoras->push(['idprinter' => $idprinter, 'nombre' => $nombre, 'status' => $status, 'isonline' => $isonline]);
        }
        $this->impresoras = $impresoras;
    }
    public function render()
    {
        $sucursales = Sucursale::all();
        return view('livewire.perifericos.impresoras-index', compact('sucursales'))
            ->extends('admin.master')
            ->section('content');
    }
}
