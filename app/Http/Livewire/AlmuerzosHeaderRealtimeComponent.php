<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;

class AlmuerzosHeaderRealtimeComponent extends Component
{
    protected $listeners = ['echo:menu-header,RefreshMenuHeaderEvent' => 'render'];
    public function render()
    {

        $diaActual = WhatsappAPIHelper::saber_dia(Carbon::today());
        $menuDia = Almuerzo::where('dia', $diaActual)->first();
        // dd($menuDia);
        return view('livewire.almuerzos-header-realtime-component', compact('menuDia'));
    }
}
