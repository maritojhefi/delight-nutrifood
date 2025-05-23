<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\User;
use App\Models\Saldo;
use Livewire\Component;
use Livewire\WithPagination;

class SaldosComponent extends Component
{
    use WithPagination;
    
    public $search;
    public $usuarioSeleccionado = null, $ventaSeleccionada = null;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function seleccionarUsuario($userId)
    {
        $this->usuarioSeleccionado = User::with('saldosVigentes')->find($userId);
    }

    public function cerrarDetalle()
    {
        $this->usuarioSeleccionado = null;
    }

    public function anularSaldo(Saldo $saldo, User $user)
    {
        $saldo->anulado = !$saldo->anulado;
        $saldo->save();
        
        $mensaje = $saldo->anulado 
            ? "El saldo fue anulado!"
            : "El saldo vuelve a estar activo!";
            
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => $mensaje
        ]);
    }
    public function verSaldoDetalleVenta(Saldo $saldo)
    {
        $this->ventaSeleccionada = $saldo->venta;
    }
    private function obtenerClientes($query)
    {
        return $query->has('saldosVigentes')
            ->when($this->search, function($query) {
                return $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy('saldo', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        $clientesConExcedente = $this->obtenerClientes(User::saldoAFavor());
        $clientesConDeuda = $this->obtenerClientes(User::saldoADeuda());
        
        return view('livewire.admin.usuarios.saldos-component', compact('clientesConExcedente', 'clientesConDeuda'))
            ->extends('admin.master')
            ->section('content');
    }
}
