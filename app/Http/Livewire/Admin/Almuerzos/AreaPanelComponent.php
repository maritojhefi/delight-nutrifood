<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use Livewire\Component;
use App\Models\AreaDespacho;

class AreaPanelComponent extends Component
{
    public $area;

    public function mount($area = null)
    {
        // Si no se proporciona área, usar 'nutribar' por defecto para mantener compatibilidad
        if ($area === null) {
            $this->area = 'nutribar';
        } else {
            // Validar que el área existe y está activa
            $areaDespacho = AreaDespacho::where('codigo_area', $area)
                ->where('activo', true)
                ->first();

            if ($areaDespacho) {
                $this->area = $area;
            } else {
                // Si el área no existe o no está activa, usar 'nutribar' por defecto
                $this->area = 'nutribar';
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.almuerzos.area-panel-component')
            ->with('area', $this->area)
            ->extends('admin.master')
            ->section('content');
    }
}
