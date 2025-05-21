<?php

namespace App\Http\Livewire\Admin\Caja;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\User;
use Livewire\Component;
use App\Models\MetodoPago;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Helpers\CustomPrint;
use Livewire\WithPagination;
use App\Models\ReciboImpreso;
use App\Models\Historial_venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReporteVentas extends Component
{
    use WithPagination;
    public $cajaSeleccionada, $ventasCaja, $totalIngresoPOS, $saldosPagadosArray, $totalDescuentos, $totalSaldoExcedentes, $totalPuntos, $acumuladoPorMetodoPago, $acumuladoPorCajero;
    public $totalSaldosPagados,
        $ventaSeleccionada,
        $metodosPagos,
        $totalIngresoAbsoluto,
        $cajeroSeleccionado = null;
    public $cajeros;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'cambiarMetodo' => 'cambiarMetodo',
        'imprimir' => 'imprimirReciboCliente',
        'descargarPDF' => 'descargarPDF',
    ];

    public function cambiarMetodo($id, $pivot)
    {
        DB::table('historial_venta_metodo_pago')
            ->where('id', $pivot)
            ->update(['metodo_pago_id' => $id]);
        $this->ventaSeleccionada = Historial_venta::find($this->ventaSeleccionada->id);
        $this->cajaSeleccionada = Caja::find($this->cajaSeleccionada->id);
        $this->buscarCaja($this->cajaSeleccionada->id);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se actualizo el metodo de pago!',
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
        return view('livewire.admin.caja.reporte-ventas', compact('cajas'))->extends('admin.master')->section('content');
    }

    public function descargarPDF()
    {
        $resultado = CreateList::crearListaHistorico($this->ventaSeleccionada);
        $listacuenta = $resultado[0];
        $data = [
            'nombreCliente' => isset($this->ventaSeleccionada->cliente->name) ? Str::limit($this->ventaSeleccionada->cliente->name, '20', '') : 'Anonimo',
            'listaCuenta' => $listacuenta,
            'subtotal' => $this->ventaSeleccionada->subtotal,
            'descuentoProductos' => $resultado[4],
            'otrosDescuentos' => $this->ventaSeleccionada->descuento,
            'valorSaldo' => $this->ventaSeleccionada->saldo,
            'metodo' => isset($this->ventaSeleccionada->metodosPagos) ? $this->ventaSeleccionada->metodosPagos : null,
            'observacion' => null,
            'fecha' => date('d-m-Y H:i:s'),
        ];

        dd($listacuenta, $resultado, $data);
        $pdf = Pdf::loadView('pdf.recibo-nuevo', $data)->output();
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $data['nombreCliente'] . '-' . date('d-m-Y-H:i:s') . '.pdf');
    }
    public function imprimirReciboCliente()
    {
        $resultado = CreateList::crearListaHistorico($this->ventaSeleccionada);
        $listacuenta = $resultado[0];
        $metodosPagosRecibo = null;
        if ($this->ventaSeleccionada && $this->ventaSeleccionada->metodosPagos->isNotEmpty()) {
            $metodosPagosRecibo = $this->ventaSeleccionada->metodosPagos;
        } else {
            $metodosPagosRecibo = null;
        }
        $recibo = CustomPrint::imprimirReciboVenta(isset($this->ventaSeleccionada->cliente->name) ? Str::limit($this->ventaSeleccionada->cliente->name, '20', '') : 'Anonimo', $listacuenta, $this->ventaSeleccionada->subtotal, $this->ventaSeleccionada->saldo, $resultado[4], $this->ventaSeleccionada->descuento, date('d-m-Y H:i:s'), null, $metodosPagosRecibo);
        $respuesta = CustomPrint::imprimir($recibo, $this->ventaSeleccionada->sucursale->id_impresora);
        if ($this->ventaSeleccionada->sucursale->id_impresora) {
            if ($respuesta == true) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Se imprimio el recibo correctamente',
                ]);
                ReciboImpreso::create([
                    'observacion' => null,
                    'cliente' => $this->ventaSeleccionada->cliente->cliente,
                    'telefono' => null,
                    'fecha' => date('Y-m-d H:i:s'),
                    'metodo' => null,
                ]);
            } elseif ($respuesta == false) {
                $stringCode = CustomPrint::getStringImpresion($recibo);
                $base64Encoded = base64_encode($stringCode);
                $this->emit('imprimir-recibo-local', $base64Encoded);
            }
        } else {
            $stringCode = CustomPrint::getStringImpresion($recibo);
            $base64Encoded = base64_encode($stringCode);
            $this->emit('imprimir-recibo-local', $base64Encoded);
        }
    }
}
