<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Models\Caja;
use Livewire\Component;
use Livewire\WithPagination;

class ReporteVentas extends Component
{
    use WithPagination;
    public $cajaSeleccionada, $ventasCaja, $totalIngreso, $saldosPagadosArray,$totalDescuentos, $totalSaldoExcedentes, $totalPuntos, $acumuladoPorMetodoPago;
    public $totalSaldosPagados;
    protected $paginationTheme = 'bootstrap';
    public function mount() {}
    public function buscarCaja(Caja $caja)
    {
        $this->cajaSeleccionada = $caja;
        $this->ventasCaja = $caja->ventas;
        $acumuladoPorMetodoPago = [];
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
        $this->acumuladoPorMetodoPago = $acumuladoPorMetodoPago;
        $this->totalDescuentos = floatval($this->ventasCaja->sum('total_descuento'));
        $this->totalIngreso = floatval($this->ventasCaja->sum('total_pagado'));
        $this->totalSaldoExcedentes = floatval($this->ventasCaja->where('a_favor_cliente',true)->sum('saldo_monto'));
        $this->totalPuntos = floatval($this->ventasCaja->sum('puntos'));
        $this->saldosPagadosArray = $this->cajaSeleccionada->saldosPagadosSinVenta;
        $this->totalSaldosPagados = floatval($this->cajaSeleccionada->saldosPagadosSinVenta->sum('monto'));
    }
    public function cambiarCaja()
    {
        $this->reset();
    }
    public function render()
    {
        $cajas = Caja::orderBy('created_at', 'DESC')->paginate(10);
        return view('livewire.admin.caja.reporte-ventas', compact('cajas'))
            ->extends('admin.master')
            ->section('content');
    }
}
