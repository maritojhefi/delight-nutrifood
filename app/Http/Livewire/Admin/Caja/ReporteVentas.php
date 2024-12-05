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
    public $cajaSeleccionada, $ventasCaja, $totalIngresoPOS, $saldosPagadosArray, $totalDescuentos, $totalSaldoExcedentes, $totalPuntos, $acumuladoPorMetodoPago;
    public $totalSaldosPagados, $ventaSeleccionada, $metodosPagos, $totalIngresoAbsoluto;
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
        $this->saldosPagadosArray = $caja->saldosPagadosSinVenta;
        $this->acumuladoPorMetodoPago = $caja->ingresosTotalesPorMetodoPago();
        $this->totalDescuentos = $caja->totalDescuentos();
        $this->totalIngresoPOS = $caja->ingresoVentasPOS();
        $this->totalIngresoAbsoluto = $caja->totalIngresoAbsoluto();
        $this->totalSaldoExcedentes = $caja->totalSaldoExcedentes();
        $this->totalPuntos = $caja->totalPuntos();
        $this->totalSaldosPagados = $caja->totalSaldosPagadosSinVenta();
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
        $cajas = Caja::orderBy('created_at', 'DESC')->paginate(9);
        return view('livewire.admin.caja.reporte-ventas', compact('cajas'))
            ->extends('admin.master')
            ->section('content');
    }
}
