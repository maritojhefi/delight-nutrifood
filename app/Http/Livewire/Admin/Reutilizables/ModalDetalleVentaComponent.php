<?php

namespace App\Http\Livewire\Admin\Reutilizables;

use App\Models\Caja;
use App\Models\Venta;
use Livewire\Component;
use App\Models\MetodoPago;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Helpers\CustomPrint;
use App\Models\ReciboImpreso;
use App\Models\Historial_venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ModalDetalleVentaComponent extends Component
{
    protected $listeners = [
        'cambiarMetodo' => 'cambiarMetodo',
        'imprimir' => 'imprimirReciboCliente',
        'descargarPDF' => 'descargarPDF',
        'cerrarModalDetalle' => 'cerrarModalDetalle',
    ];
    public $ventaSeleccionada;
    public $metodosPagos;
    public $cajaSeleccionada;
    public $ventasCaja;
    public $saldosPagadosArray;
    public $acumuladoPorMetodoPago;
    public $acumuladoPorCajero;
    public $totalDescuentos;
    public $totalIngresoPOS;
    public $totalIngresoAbsoluto;
    public $totalSaldoExcedentes;
    public $totalPuntos;
    public $totalSaldosPagados;
    public $cajeroSeleccionado;

    public function mount($ventaSeleccionada)
    {
        $this->ventaSeleccionada = $ventaSeleccionada;
        $this->cajaSeleccionada = Caja::find($ventaSeleccionada->caja_id);
        $this->metodosPagos = MetodoPago::where('activo', true)->get();
    }
    public function updatedVentaSeleccionada($value)
    {
        // Si necesitas recargar relaciones
        $this->ventaSeleccionada = Venta::with(['productos', 'metodosPagos', 'usuario'])
            ->find($value['id']);
    }

    public function cerrarModalDetalle()
    {
        $this->ventaSeleccionada = null;
        $this->metodosPagos = null;
        $this->cajaSeleccionada = null;
        $this->ventasCaja = null;
        $this->saldosPagadosArray = null;
        $this->acumuladoPorMetodoPago = null;
        $this->acumuladoPorCajero = null;
        $this->totalDescuentos = null;
        $this->totalIngresoPOS = null;
        $this->totalIngresoAbsoluto = null;
        $this->totalSaldoExcedentes = null;
        $this->totalPuntos = null;
        $this->totalSaldosPagados = null;
        $this->cajeroSeleccionado = null;

        $this->emit('modal-cerrado-detalle');
        $this->render();
    }

    public function descargarPDF()
    {
        // dd($this->ventaSeleccionada);
        $resultado = CreateList::crearListaHistorico($this->ventaSeleccionada);
        $listacuenta = $resultado[0];

        // dd($resultado, $this->ventaSeleccionada, $this->ventaSeleccionada->subtotal);

        $data = [
            'cuenta' => $this->ventaSeleccionada,
            'nombreCliente' => isset($this->ventaSeleccionada->cliente->name) ? Str::limit($this->ventaSeleccionada->cliente->name, '20', '') : 'Anonimo',
            'listaCuenta' => $listacuenta,
            'subtotal' => $this->ventaSeleccionada->subtotal,
            'descuentoProductos' => $resultado[4],
            'otrosDescuentos' => $this->ventaSeleccionada->descuento,
            'valorSaldo' => $this->ventaSeleccionada->saldo_monto,
            'metodo' => isset($this->ventaSeleccionada->metodosPagos) ? $this->ventaSeleccionada->metodosPagos : null,
            'observacion' => null,
            'fecha' => date('d-m-Y H:i:s'),
        ];

        // dd($listacuenta, $resultado, $data);
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
        $recibo = CustomPrint::imprimirReciboVenta(isset($this->ventaSeleccionada->cliente->name) ? Str::limit($this->ventaSeleccionada->cliente->name, '20', '') : 'Anonimo', $listacuenta, $this->ventaSeleccionada->subtotal, $this->ventaSeleccionada->saldo_monto, $resultado[4], $this->ventaSeleccionada->descuento, date('d-m-Y H:i:s'), null, $metodosPagosRecibo, $this->ventaSeleccionada);
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
    public function render()
    {
        return view('livewire.admin.reutilizables.modal-detalle-venta-component');
    }
}
