<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\User;
use App\Models\Saldo;
use Livewire\Component;


class SaldosComponent extends Component
{
    public $search;
    public function anularSaldo(Saldo $saldo,User $user)
    {
        
        if ($saldo->anulado) {
            if ($saldo->es_deuda) {
                $user->saldo = $user->saldo + $saldo->monto;
            } else {
                $user->saldo = $user->saldo - $saldo->monto;
            }
            $saldo->anulado = false;
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "El saldo vuelve a estar activo!"
            ]);
        } else {
            if ($saldo->es_deuda) {
                $user->saldo = $user->saldo - $saldo->monto;
            } else {
                $user->saldo = $user->saldo + $saldo->monto;
            }
            $saldo->anulado = true;
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "El saldo fue anulado!"
            ]);
        }
        
        $user->save();
        $saldo->save();
        
    }
    public function render()
    {
        if ($this->search) {
            $usuarios=User::has('saldos')->where('name','LIKE','%'.$this->search.'%')->where('saldo','!=',0)->get();
        }
        else
        {
            $usuarios=User::has('saldos')->where('saldo','!=',0)->get();
        }
        
        return view('livewire.admin.usuarios.saldos-component',compact('usuarios'))
        ->extends('admin.master')
        ->section('content');
    }
}
