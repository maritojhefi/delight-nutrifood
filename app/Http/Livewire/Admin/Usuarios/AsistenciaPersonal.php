<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Charts\EmpleadosChart;
use App\Charts\SampleChart;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Asistencia;
use Illuminate\Support\Facades\DB;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class AsistenciaPersonal extends Component
{
    public $search, $empleadoSeleccionado, $reporteFin, $reporteInicio;
    public function seleccionarEmpleado(User $empleado)
    {
        $this->empleadoSeleccionado = $empleado;
    }
    public function render()
    {
       
        // $chart = new EmpleadosChart;
        // $chart->labels(['One', 'Two', 'Three', 'Four']);
        // $chart->dataset('My dataset', 'line', [1, 2, 3, 4]);
        // $chart->dataset('My dataset 2', 'line', [4, 3, 2, 1]);
        $empleados = User::with('contrato')->has('contrato')->get();
        //dd($asistencias);
        return view('livewire.admin.usuarios.asistencia-personal', compact('empleados'))
            ->extends('admin.master')
            ->section('content');
    }
}
