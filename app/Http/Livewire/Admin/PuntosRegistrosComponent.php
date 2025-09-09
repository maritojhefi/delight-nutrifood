<?php

namespace App\Http\Livewire\Admin;

use App\Models\Caja;
use App\Models\User;
use Livewire\Component;
use App\Models\MetodoPago;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Helpers\CustomPrint;
use Livewire\WithPagination;
use App\Models\ReciboImpreso;
use App\Models\RegistroPunto;
use App\Models\Historial_venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PuntosRegistrosComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Propiedades para controlar las vistas
    public $vistaPorUsuario = true; // true = vista por cliente, false = vista detallada
    public $search = '';

    // Propiedades para los modales
    public $showModalHistorial = false;
    public $showModalDetalle = false;
    public $clienteSeleccionado = null;
    public $registroSeleccionado = null;
    public $historialCliente = [];
    public $detalleVenta = null;
    public $detalleVentaProductos = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'vistaPorUsuario' => ['except' => true],
    ];

    public function mount()
    {
        $this->search = '';
        $this->metodosPagos = MetodoPago::where('activo', true)->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleVista()
    {
        $this->vistaPorUsuario = !$this->vistaPorUsuario;
        $this->search = ''; // Limpiar búsqueda al cambiar vista
        $this->resetPage();
    }

    public function verHistorial($clienteId)
    {
        // Cerrar cualquier modal abierto
        $this->cerrarTodosLosModales();

        $this->clienteSeleccionado = User::find($clienteId);
        $this->historialCliente = RegistroPunto::where('cliente_id', $clienteId)
            ->with(['partner', 'historialVenta.productos', 'historialVenta.cliente'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->showModalHistorial = true;

        // Debug: verificar valores antes de emitir
        Log::info('verHistorial - showModalHistorial: ' . ($this->showModalHistorial ? 'true' : 'false'));
        Log::info('verHistorial - clienteSeleccionado: ' . ($this->clienteSeleccionado ? 'exists' : 'null'));
        Log::info('verHistorial - historialCliente count: ' . ($this->historialCliente ? count($this->historialCliente) : 'null'));

        $this->emit('abrirModalHistorial');
    }
    public $ventaSeleccionada = null;
    public function verDetalleVenta($registroId)
    {
        // Cerrar cualquier modal abierto
        $this->cerrarTodosLosModales();
        $this->registroSeleccionado = RegistroPunto::with(['historialVenta.productos', 'historialVenta.cliente', 'historialVenta.usuario', 'partner'])->find($registroId);

        // Debug: verificar si tiene historial de venta
        if ($this->registroSeleccionado->historialVenta) {
            $this->detalleVenta = $this->registroSeleccionado->historialVenta;
            $this->detalleVentaProductos = $this->registroSeleccionado->historialVenta->productos;

            // Debug: verificar productos
            $count = is_object($this->detalleVentaProductos) && method_exists($this->detalleVentaProductos, 'count') ? $this->detalleVentaProductos->count() : (is_array($this->detalleVentaProductos) ? count($this->detalleVentaProductos) : 0);
            Log::info('verDetalleVenta - detalleVentaProductos count: ' . $count);

            // Emitir datos de productos al frontend
            $productos = [];
            if (is_object($this->detalleVentaProductos) && method_exists($this->detalleVentaProductos, 'map')) {
                $productos = $this->detalleVentaProductos
                    ->map(function ($producto) {
                        return [
                            'nombre' => $producto->nombre,
                            'descripcion' => $producto->descripcion ?? 'Sin descripción',
                            'cantidad' => $producto->pivot->cantidad,
                            'precio_unitario' => $producto->pivot->precio_unitario,
                            'descuento_producto' => $producto->pivot->descuento_producto,
                            'precio_subtotal' => $producto->pivot->precio_subtotal,
                        ];
                    })
                    ->toArray();
            }

            $this->emit('datosProductos', ['productos' => $productos]);
        } else {
            // Si no tiene historial de venta, crear un objeto vacío para evitar errores
            $this->detalleVenta = null;
            $this->detalleVentaProductos = [];

            // Emitir array vacío
            $this->emit('datosProductos', ['productos' => []]);
        }

        $this->ventaSeleccionada = $this->detalleVenta;

        // dd($this->ventaSeleccionada);


        if (isset($this->ventaSeleccionada)) {
            $this->cajaSeleccionada = Caja::find($this->ventaSeleccionada->caja_id);
        }

        $this->emit('abrirModalDetalle');
    }

    public function cerrarModalHistorial()
    {
        $this->showModalHistorial = false;
        $this->clienteSeleccionado = null;
        $this->historialCliente = [];
        $this->emit('cerrarModalHistorial');
    }

    public function cerrarModalDetalle()
    {
        $this->showModalDetalle = false;
        $this->registroSeleccionado = null;
        $this->detalleVenta = null;
        $this->detalleVentaProductos = [];
        $this->emit('cerrarModalDetalle');
    }

    public function cerrarTodosLosModales()
    {
        $this->showModalHistorial = false;
        $this->showModalDetalle = false;
        $this->clienteSeleccionado = null;
        $this->registroSeleccionado = null;
        $this->historialCliente = [];
        $this->detalleVenta = null;
    }

    public function testModal()
    {
        $this->showModalDetalle = true;
        $this->registroSeleccionado = (object) ['id' => 1, 'puntos_partner' => 100, 'puntos_cliente' => 200, 'total_puntos' => 300];
        $this->detalleVenta = (object) ['id' => 1, 'total' => 1000, 'puntos' => 300];
        $this->emit('abrirModalDetalle');
    }

    public function render()
    {
        if ($this->vistaPorUsuario) {
            // Vista agrupada por cliente
            $query = RegistroPunto::selectRaw(
                '
                cliente_id,
                SUM(total_puntos) as total_puntos_cliente,
                MAX(created_at) as ultima_actualizacion,
                COUNT(*) as total_registros
            ',
            )
                ->with('cliente')
                ->groupBy('cliente_id');

            if ($this->search) {
                $query->whereHas('cliente', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }

            $registros = $query->orderBy('total_puntos_cliente', 'desc')->paginate(10);
        } else {
            // Vista detallada
            $query = RegistroPunto::with(['partner', 'cliente', 'historialVenta']);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->whereHas('partner', function ($subQ) {
                        $subQ->where('name', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('cliente', function ($subQ) {
                            $subQ->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('tipo', 'like', '%' . $this->search . '%');
                });
            }

            $registros = $query->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('livewire.admin.puntos-registros-component', compact('registros'))->extends('admin.master')->section('content');
    }

    protected $listeners = [
        'cambiarMetodo' => 'cambiarMetodo',
        'imprimir' => 'imprimirReciboCliente',
        'descargarPDF' => 'descargarPDF',
    ];
    // public $ventaSeleccionada;
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

    public function verDetalleRegistro($registroId)
    {
        $this->cerrarTodosLosModales();
        $this->registroSeleccionado = RegistroPunto::with(['partner', 'cliente'])->find($registroId);
        $this->emit('abrirModalDetalleRegistro');
    }
}
