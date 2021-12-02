<?php

namespace App\Http\Livewire\Admin\Caja;

use Livewire\Component;
use Chartisan\PHP\Chartisan;
use Charts;
class Reportes extends Component
{
    public function render()
    {
        
        $chart=Chartisan::build()
            ->labels(['First', 'Second', 'Third'])
            ->dataset('Sample', [1, 2, 3])
            ->dataset('Sample 2', [3, 2, 1]);
        return view('livewire.admin.caja.reportes',compact('chart'))
        ->extends('admin.master')
        ->section('content');
    }
}
