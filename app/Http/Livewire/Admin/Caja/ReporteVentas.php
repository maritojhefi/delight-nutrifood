<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Models\Caja;
use App\Models\User;
use Livewire\Component;
use App\Models\MetodoPago;
use Livewire\WithPagination;
use App\Models\Historial_venta;
use Illuminate\Support\Facades\DB;

class ReporteVentas extends Component
{
    use WithPagination;
    public $cajaSeleccionada, $ventasCaja, $totalIngresoPOS, $saldosPagadosArray, $totalDescuentos, $totalSaldoExcedentes, $totalPuntos, $acumuladoPorMetodoPago, $acumuladoPorCajero;
    public $totalSaldosPagados, $ventaSeleccionada, $metodosPagos, $totalIngresoAbsoluto, $cajeroSeleccionado = null;
    public $cajeros;
    protected $paginationTheme = 'bootstrap';
    protected $listeners =  [
        'cambiarMetodo' => 'cambiarMetodo',

    ];
    public function cambiarMetodo($id, $pivot)
    {
        DB::table('historial_venta_metodo_pago')->where('id', $pivot)->update(['metodo_pago_id' => $id]);
        $this->ventaSeleccionada = Historial_venta::find($this->ventaSeleccionada->id);
        $this->cajaSeleccionada = Caja::find($this->cajaSeleccionada->id);
        $this->buscarCaja($this->cajaSeleccionada->id);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se actualizo el metodo de pago!"
        ]);
        // dd($id, $pivot);
    }
    public function mount()
    {
        $this->metodosPagos = MetodoPago::where('activo', true)->get();
        $this->cajeros = User::cajeros()->get();
    }
    public function resetCajero()
    {
        $this->reset('cajeroSeleccionado');
        $this->buscarCaja($this->cajaSeleccionada->id);
    }
    public function buscarCaja($cajaId)
    {
        $caja = Caja::find($cajaId);
        $this->cajaSeleccionada = $caja;
        $caja->atendidoPor = $this->cajeroSeleccionado ? $this->cajeroSeleccionado->id : null;
        $this->ventasCaja = $caja->ventas;
        $this->saldosPagadosArray = $caja->saldosPagadosSinVenta;
        $this->acumuladoPorMetodoPago = $caja->ingresosTotalesPorMetodoPago();
        $this->acumuladoPorCajero = $caja->ingresosTotalesPorCajero();
        $this->totalDescuentos = $caja->totalDescuentos();
        $this->totalIngresoPOS = $caja->ingresoVentasPOS();
        $this->totalIngresoAbsoluto = $caja->totalIngresoAbsoluto();
        $this->totalSaldoExcedentes = $caja->totalSaldoExcedentes();
        $this->totalPuntos = $caja->totalPuntos();
        $this->totalSaldosPagados = $caja->totalSaldosPagadosSinVenta();
    }
    public function seleccionarCajero(User $cajero)
    {
        $this->cajeroSeleccionado = $cajero;
        $this->buscarCaja($this->cajaSeleccionada->id);
    }
    public function seleccionarVenta(Historial_venta $venta)
    {
        $this->ventaSeleccionada = $venta;
    }
    public function cambiarCaja()
    {
        $this->resetExcept('metodosPagos', 'cajeros');
    }
    public function render()
    {
        $cajas = Caja::orderBy('created_at', 'DESC')->paginate(9);
        return view('livewire.admin.caja.reporte-ventas', compact('cajas'))
            ->extends('admin.master')
            ->section('content');
    }
}
