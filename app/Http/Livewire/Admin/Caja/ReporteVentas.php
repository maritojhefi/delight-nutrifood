<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Models\Caja;
use Livewire\Component;
use App\Models\MetodoPago;
use Livewire\WithPagination;
use App\Models\Historial_venta;
use Illuminate\Support\Facades\DB;

class ReporteVentas extends Component
{
    use WithPagination;
    public $cajaSeleccionada, $ventasCaja, $totalIngreso, $saldosPagadosArray, $totalDescuentos, $totalSaldoExcedentes, $totalPuntos, $acumuladoPorMetodoPago;
    public $totalSaldosPagados, $ventaSeleccionada, $metodosPagos;
    protected $paginationTheme = 'bootstrap';
    protected $listeners =  [
        'cambiarMetodo' => 'cambiarMetodo',

    ];
    public function cambiarMetodo($id, $pivot)
    {
        DB::table('historial_venta_metodo_pago')->where('id', $pivot)->update(['metodo_pago_id' => $id]);
        $this->ventaSeleccionada = Historial_venta::find($this->ventaSeleccionada->id);
        $this->cajaSeleccionada = Caja::find($this->cajaSeleccionada->id);
        $this->buscarCaja($this->cajaSeleccionada);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se actualizo el metodo de pago!"
        ]);
        // dd($id, $pivot);
    }
    public function mount()
    {
        $this->metodosPagos = MetodoPago::where('activo', true)->get();
    }
    public function buscarCaja(Caja $caja)
    {
        $this->cajaSeleccionada = $caja;
        $this->ventasCaja = $caja->ventas;
        $acumuladoPorMetodoPago = [];
        //sumando de ventas
        foreach ($this->ventasCaja as $ven) {
            foreach ($ven->metodosPagos as $met) {
                $monto = $met->pivot->monto;
                $nombre = $met->nombre_metodo_pago;
                if (isset($acumuladoPorMetodoPago[$nombre])) {
                    $acumuladoPorMetodoPago[$nombre] += $monto;
                } else {
                    $acumuladoPorMetodoPago[$nombre] = $monto;
                }
            }
        }
        $this->saldosPagadosArray = $this->cajaSeleccionada->saldosPagadosSinVenta;
        //sumando de saldos entrantes pagados
        foreach ($this->saldosPagadosArray as $saldo) {
            // dd($saldo);
            foreach ($saldo->metodosPagos as $metSaldo) {
                $monto = $metSaldo->pivot->monto;
                $nombre = $metSaldo->nombre_metodo_pago;
                if (isset($acumuladoPorMetodoPago[$nombre])) {
                    $acumuladoPorMetodoPago[$nombre] += $monto;
                } else {
                    $acumuladoPorMetodoPago[$nombre] = $monto;
                }
            }
        }
        $this->acumuladoPorMetodoPago = $acumuladoPorMetodoPago;
        $this->totalDescuentos = floatval($this->ventasCaja->sum('total_descuento'));
        $this->totalIngreso = floatval($this->ventasCaja->sum('total_pagado'));
        $this->totalSaldoExcedentes = floatval($this->ventasCaja->where('a_favor_cliente', true)->sum('saldo_monto'));
        $this->totalPuntos = floatval($this->ventasCaja->sum('puntos'));

        $this->totalSaldosPagados = floatval($this->cajaSeleccionada->saldosPagadosSinVenta->sum('monto'));
    }
    public function seleccionarVenta(Historial_venta $venta)
    {
        $this->ventaSeleccionada = $venta;
    }
    public function cambiarCaja()
    {
        $this->resetExcept('metodosPagos');
    }
    public function render()
    {
        $cajas = Caja::orderBy('created_at', 'DESC')->paginate(10);
        return view('livewire.admin.caja.reporte-ventas', compact('cajas'))
            ->extends('admin.master')
            ->section('content');
    }
}
