<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\Contrato;
use App\Models\User;
use Livewire\Component;

class EmpleoCreate extends Component
{
    public $lunes = true, $martes = true, $miercoles = true, $jueves = true, $viernes = true, $sabado = false, $domingo = false;
    public $sueldo, $fecha_inicio, $modalidad = "mensual", $observacion, $user_id, $hora_entrada, $hora_salida;
    public $seleccionado;
    protected $rules = [
        'lunes' => 'required',
        'martes' => 'required',
        'miercoles' => 'required',
        'jueves' => 'required',
        'viernes' => 'required',
        'sabado' => 'required',
        'domingo' => 'required',
        'hora_entrada' => 'required',
        'hora_salida' => 'required',
        'fecha_inicio' => 'required',
        'modalidad' => 'required',
        'user_id' => 'required',
        'sueldo' => 'required',
        'observacion' => ''
    ];
    public function expandirFecha($diaSeleccionado)
    {

        $diasSemana = [
            'lunes' => $this->lunes,
            'martes' => $this->martes,
            'miercoles' => $this->miercoles,
            'jueves' => $this->jueves,
            'viernes' => $this->viernes,
            'sabado' => $this->sabado,
            'domingo' => $this->domingo,

        ];
        if(isset($this->hora_entrada[$diaSeleccionado]) && isset($this->hora_salida[$diaSeleccionado]))
        {
            foreach ($diasSemana as $dia => $valor) {
                if ($valor) {
                    $this->hora_entrada[$dia] = $this->hora_entrada[$diaSeleccionado];
                    $this->hora_salida[$dia] = $this->hora_salida[$diaSeleccionado];
                }
            }
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Hecho!"
            ]);
        }
        else
        {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "Valores incorrectos"
            ]);
        }
      
    }
    public function seleccionar(Contrato $contrato)
    {
        // dd($contrato);
        $this->seleccionado = $contrato;
    }
    public function delete()
    {
        $this->seleccionado->delete();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Contrato eliminado!"
        ]);
        $this->reset('seleccionado');
    }
    public function crear()
    {

        $this->validate();
        $diasSemana = [
            'lunes' => $this->lunes,
            'martes' => $this->martes,
            'miercoles' => $this->miercoles,
            'jueves' => $this->jueves,
            'viernes' => $this->viernes,
            'sabado' => $this->sabado,
            'domingo' => $this->domingo,

        ];
        $diasValidados = true;
        foreach ($diasSemana as $dia => $valor) {
            if ($valor) {
                try {
                    $inicio = $this->hora_entrada[$dia];
                    $salida = $this->hora_salida[$dia];
                    if (!isset($inicio) && !isset($salida)) {
                        $this->dispatchBrowserEvent('alert', [
                            'type' => 'warning',
                            'message' => "Llene correctamente los horarios para el dia " . $dia
                        ]);
                        $diasValidados = false;
                        break;
                    }
                } catch (\Throwable $th) { {
                        $this->dispatchBrowserEvent('alert', [
                            'type' => 'warning',
                            'message' => "Llene correctamente los horarios para el dia " . $dia
                        ]);
                        $diasValidados = false;
                        break;
                    }
                }
            }
        }
        if ($diasValidados) {
            $contrato = new Contrato();
            $contrato->fill($this->validate());
            $contrato->hora_entrada = json_encode($this->hora_entrada);
            $contrato->hora_salida = json_encode($this->hora_salida);
            $contrato->save();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Nuevo contrato creado!"
            ]);
            $this->reset();
        }
    }
    public function render()
    {
        $contratos = Contrato::all();
        $usuarios = User::doesnthave('contrato')->where('role_id', '!=', 4)->get();
        return view('livewire.admin.usuarios.empleo-create', compact('usuarios', 'contratos'))
            ->extends('admin.master')
            ->section('content');
    }
}
