<?php

namespace App\Http\Livewire\Admin\Ventas;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\Mesa;
use App\Models\User;
use App\Models\Plane;
use App\Models\Saldo;
use App\Models\Venta;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Adicionale;
use App\Models\MetodoPago;
use Mike42\Escpos\Printer;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Helpers\CustomPrint;
use App\Models\Subcategoria;
use App\Helpers\GlobalHelper;
use App\Models\ReciboImpreso;
use Mike42\Escpos\EscposImage;
use App\Models\Historial_venta;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\CocinaPedidoEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Livewire\Admin\PedidosRealtimeComponent;

// Services
use App\Services\Ventas\Contracts\SaldoServiceInterface;
use App\Services\Ventas\Contracts\StockServiceInterface;
use App\Services\Ventas\Contracts\VentaServiceInterface;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Services\Ventas\Contracts\ProductoVentaServiceInterface;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class VentasIndex extends Component
{
    // Services inyectados
    private VentaServiceInterface $ventaService;
    private ProductoVentaServiceInterface $productoVentaService;
    private SaldoServiceInterface $saldoService;
    private StockServiceInterface $stockService;
    private CalculadoraVentaServiceInterface $calculadoraService;

    public $metodosPagos,
        $metodosSeleccionados = [],
        $totalAcumuladoMetodos = 0;
    public $sucursal;
    public $cuenta;
    public $search;
    public $itemsCuenta;
    public $listacuenta;
    public $user;
    public $cliente;
    public $cantidadespecifica;
    public $adicionales;
    public $productoapuntado;
    public $itemseleccionado;
    public $array;

    public $userManual;
    public $tipocobro;
    public $descuento, $observacion;
    //variables para recibo
    public $modoImpresion = false;
    public $fechaRecibo, $observacionRecibo, $clienteRecibo, $checkClientePersonalizado, $checkMetodoPagoPersonalizado, $metodoRecibo, $checkTelefonoPersonalizado, $telefonoRecibo;
    //variables para crear Cliente
    public $name, $cumpleano, $email, $direccion, $password, $password_confirmation;
    public $saldo,
        $valorSaldo = 0,
        $deshabilitarBancos = false,
        $saldoRestante = 0,
        $verVistaSaldo = false;
    public $montoSaldo, $detalleSaldo, $tipoSaldo;
    public $descuentoProductos,
        $subtotal,
        $subtotalConDescuento,
        $descuentoSaldo = 0,
        $saldoSobranteCheck = false,
        $maxDescuentoSaldo,
        $descuentoConvenio,
        $totalAdicionales;
    public $subcategoriaSeleccionada;
    protected $rules = [
        'sucursal' => 'required|integer',
    ];
    protected $listeners = [
        'cobrar' => 'cobrar',
        'cerrarVenta' => 'cerrarVenta',
        'imprimir' => 'imprimirReciboCliente',
        'descargarPDF' => 'descargarPDF',
        'modalResumen' => 'modalResumen',
        'crearVentaDelivery' => 'crearVentaDelivery',
        'crearVentaReserva' => 'crearVentaReserva',
        'cambiarTipoEntregaVenta' => 'cambiarTipoEntregaVenta',
        'agregarProductoConAdicionales' => 'agregarProductoConAdicionales',
    ];
    public function boot(
        VentaServiceInterface $ventaService,
        ProductoVentaServiceInterface $productoVentaService,
        SaldoServiceInterface $saldoService,
        StockServiceInterface $stockService,
        CalculadoraVentaServiceInterface $calculadoraService
    ) {
        $this->ventaService = $ventaService;
        $this->productoVentaService = $productoVentaService;
        $this->saldoService = $saldoService;
        $this->stockService = $stockService;
        $this->calculadoraService = $calculadoraService;
    }

    public function mount()
    {
        $this->metodosPagos = MetodoPago::where('activo', true)->get();
    }
    public function updated($atributo)
    {
        if (str_starts_with($atributo, 'metodosSeleccionados.')) {
            // dd($atributo);
            $this->totalAcumuladoMetodos = 0; // Reinicia el acumulador
            foreach ($this->metodosSeleccionados as $metodoSelec) {
                if (isset($metodoSelec['activo']) && $metodoSelec['activo'] === true && isset($metodoSelec['valor']) && is_numeric($metodoSelec['valor'])) {
                    $this->totalAcumuladoMetodos += (float) $metodoSelec['valor'];
                }
            }
        }
        if (str_starts_with($atributo, 'metodosSeleccionados.') && str_ends_with($atributo, '.activo')) {
            // Cuenta los métodos seleccionados que tienen 'activo' como true
            $activos = collect($this->metodosSeleccionados)->filter(function ($metodo) {
                return isset($metodo['activo']) && $metodo['activo'] === true;
            });

            if ($activos->count() == 1) {
                // Obtén el código del primer método activo
                $codigoMetodoActivo = $activos->keys()->first();
                $this->totalAcumuladoMetodos = $this->subtotalConDescuento;
                // Asigna $subtotalConDescuento al valor correspondiente
                $this->metodosSeleccionados[$codigoMetodoActivo]['valor'] = $this->subtotalConDescuento;
            }
            //focus al input del check
            $cadena = $atributo;
            $partes = explode('.', $cadena); // Divide la cadena por '.'
            $segundoTexto = $partes[1]; // Obtiene el segundo elemento
            $this->emit('focusInput', $segundoTexto);
        }

        switch ($atributo) {
            // case 'saldoRestante':
            //     $this->controlarEntrante();
            //     break;
            // case 'valorSaldo':
            //     $this->controlarSaldo();
            //     break;
            case 'search':
                $this->reset('subcategoriaSeleccionada');
                break;
            case 'saldoSobranteCheck':
                if ($this->saldoSobranteCheck == true) {
                    $this->descuentoSaldo = min($this->subtotalConDescuento, abs((int) $this->cuenta->cliente->saldo));
                    $this->maxDescuentoSaldo = $this->descuentoSaldo;
                    $this->reset('totalAcumuladoMetodos', 'metodosSeleccionados');
                    $this->actualizarLista($this->cuenta);
                } else {
                    $this->descuentoSaldo = 0;
                    $this->actualizarLista($this->cuenta);
                }
                break;
            case 'descuentoSaldo':
                if ($this->descuentoSaldo > $this->maxDescuentoSaldo) {
                    $this->descuentoSaldo = $this->maxDescuentoSaldo;
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'info',
                        'message' => 'El máximo que puede ingresar es de ' . $this->descuentoSaldo . ' Bs',
                    ]);
                }
                $this->actualizarLista($this->cuenta);
                break;
            default:
                # code...
                break;
        }
    }
    public function modalResumen()
    {
        $this->modoImpresion = false;
    }
    public function modalImpresion()
    {
        $this->modoImpresion = true;
    }
    public function imprimirCocina()
    {
        $response = $this->ventaService->enviarACocina($this->cuenta);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->cuenta = $response->data;
        }
    }
    public function addUsuarioManual()
    {
        $response = $this->ventaService->agregarUsuarioManual($this->cuenta, $this->userManual);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->cuenta = $response->data;
        }
    }
    public function imprimirSaldo(Saldo $saldo)
    {
        if ($this->cuenta->sucursale->id_impresora) {
            $nombre_impresora = 'POS-582';
            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $img = EscposImage::load(public_path(GlobalHelper::getValorAtributoSetting('logo')));
            $printer->bitImageColumnFormat($img);
            $printer->setTextSize(1, 1);
            $printer->text(GlobalHelper::getValorAtributoSetting('nombre_empresa') . "\n");
            $printer->feed(1);
            $printer->text("'" . strtoupper(GlobalHelper::getValorAtributoSetting('slogan')) . "!'" . "\n");
            $printer->feed(1);
            $printer->text('Contacto : ' . GlobalHelper::getValorAtributoSetting('telefono') . "\n" . GlobalHelper::getValorAtributoSetting('direccion') . ' ' . "\n");
            if (isset($this->cuenta->cliente->name)) {
                $printer->text('Cliente: ' . Str::limit($this->cuenta->cliente->name, '20', '') . "\n");
            }
            $printer->setTextSize(2, 2);
            $printer->text("--------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 2);

            $printer->text('Monto: ' . floatval($saldo->monto) . " Bs\n");
            $printer->feed(1);
            $printer->text($saldo->es_deuda ? 'A saldo pendiente del cliente' : 'A favor del cliente' . " \n");
            $printer->feed(1);
            if ($saldo->detalle) {
                $printer->setTextSize(1, 1);
                $printer->text('Detalle: ' . $saldo->detalle . "\n");
            }

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->feed(2);
            $printer->setTextSize(2, 2);
            $printer->text('TOTAL PAGADO' . "\n" . ' Bs ' . $saldo->monto . "\n");
            $printer->feed(1);
            $printer->setTextSize(1, 1);

            $printer->text("--------\n");

            $img = EscposImage::load(public_path('qrcode.png'));
            $printer->bitImageColumnFormat($img);

            $printer->setTextSize(1, 1);
            $printer->text("Ingresa a nuestra plataforma!\n");
            $printer->feed(1);
            $printer->text("Gracias por tu compra\n");
            $printer->text("Vuelve pronto!\n");
            $printer->feed(1);
            $printer->text(date('Y-m-d H:i:s') . "\n");
            $printer->feed(3);
            $respuesta = CustomPrint::imprimir($printer, $this->cuenta->sucursale->id_impresora);
            if ($respuesta == true) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Se imprimio el recibo correctamente',
                ]);
            } elseif ($respuesta == false) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'La impresora no esta conectada',
                ]);
            }
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'La sucursal no tiene una impresora activa',
            ]);
        }
    }
    public function registrarSaldo()
    {
        $this->validate([
            'tipoSaldo' => 'required|integer',
            'montoSaldo' => 'required|numeric|min:0',
            'detalleSaldo' => 'required',
        ]);
        $cajaactiva = Caja::where('sucursale_id', $this->cuenta->sucursale->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        $saldoCreado = Saldo::create([
            'detalle' => $this->detalleSaldo,
            'historial_venta_id' => 1,
            'caja_id' => $cajaactiva->id,
            'es_deuda' => false,
            'monto' => $this->montoSaldo,
            'user_id' => $this->cuenta->cliente->id,
            'atendido_por' => auth()->user()->id,
            'tipo' => $this->tipoSaldo,
        ]);
        $saldoCreado->metodosPagos()->attach($this->tipoSaldo, ['monto' => $this->montoSaldo]);
        // DB::table('users')
        //     ->where('id', $this->cuenta->cliente->id)
        //     ->decrement('saldo', $this->montoSaldo);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se edito el saldo a favor de este cliente!',
        ]);
        $this->reset('montoSaldo', 'detalleSaldo', 'tipoSaldo');
        $this->cuenta = Venta::find($this->cuenta->id);
    }
    public function verSaldo()
    {
        if ($this->verVistaSaldo) {
            $this->verVistaSaldo = false;
        } else {
            $this->verVistaSaldo = true;
        }
    }
    public function actualizarSaldo()
    {
        if ($this->saldo) {
            $this->valorSaldo = $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos;
            $this->tipocobro = 'efectivo';
            $this->emit('cambiarCheck');
            $this->deshabilitarBancos = true;
        } else {
            $this->valorSaldo = 0;
            $this->deshabilitarBancos = false;
            $this->saldoRestante = 0;
        }
    }
    public function controlarEntrante()
    {
        if ($this->saldoRestante > $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos) {
            $this->saldoRestante = $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos;
            $this->valorSaldo = $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos - $this->saldoRestante;
        } else {
            $this->valorSaldo = $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos - $this->saldoRestante;
        }
    }
    public function controlarSaldo()
    {
        if ($this->valorSaldo > $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos) {
            $this->valorSaldo = $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos;
            $this->tipocobro = false;
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'El saldo no debe ser mayor al monto a cobrar',
            ]);
        } else {
            $this->deshabilitarBancos = false;
            $this->saldoRestante = $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos - $this->valorSaldo;
        }
        if ($this->valorSaldo == $this->subtotal - $this->cuenta->descuento - $this->descuentoProductos) {
            $this->tipocobro = 'efectivo';
            $this->emit('cambiarCheck');
            $this->deshabilitarBancos = true;
            $this->saldoRestante = 0;
        }
    }
    public function guardarObservacion($idprod)
    {
        $producto = Producto::find($idprod);
        $response = $this->productoVentaService->guardarObservacion($this->cuenta, $producto, $this->observacion);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);
    }
    public function crear()
    {
        $this->validate();

        $response = $this->ventaService->crearVenta(auth()->user()->id, $this->sucursal, $this->cliente);
        $this->seleccionar($response->data->id);
        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->reset(['user', 'cliente', 'sucursal']);
        }
    }

    public function abrirVentaConMesa($mesaId, $tipo)
    {
        try {
            // $mesa = Mesa::findOrFail($mesaId);

            $response = $this->ventaService->crearVenta(auth()->user()->id, $this->sucursal, null, $mesaId, $tipo);

            $this->dispatchBrowserEvent('alert', [
                'type' => $response->type,
                'message' => $response->message,
            ]);
            // dd($response->data);
            $this->seleccionar($response->data->id);
            if ($response->success) {
                $this->reset(['user', 'cliente', 'sucursal']);
                // Cerrar el modal automáticamente
                $this->dispatchBrowserEvent('cerrarModal', ['modalId' => 'modalSeleccionarMesa']);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al abrir la venta: ' . $e->getMessage(),
            ]);
        }
    }

    public function buscarClientes()
    {
        if ($this->user != null && strlen($this->user) >= 2) {
            $clientes = User::where('name', 'LIKE', '%' . $this->user . '%')
                ->orWhere('email', 'LIKE', '%' . $this->user . '%')
                ->take(10)
                ->get(['id', 'name', 'email']);

            $this->dispatchBrowserEvent('clientesEncontrados', ['clientes' => $clientes]);
        } else {
            $this->dispatchBrowserEvent('clientesEncontrados', ['clientes' => []]);
        }
    }

    public function obtenerMesasDisponibles()
    {
        $mesas = Mesa::where('sucursale_id', $this->sucursal)
            ->with(['venta' => function ($query) {
                $query->where('pagado', false);
            }])
            ->get()
            ->map(function ($mesa) {
                $ventaActiva = $mesa->venta;
                $mesaOcupada = $ventaActiva && !$ventaActiva->pagado;

                return [
                    'id' => $mesa->id,
                    'numero' => $mesa->numero,
                    'capacidad' => $mesa->capacidad,
                    'ocupada' => $mesaOcupada,
                    'es_actual' => $this->cuenta && $this->cuenta->mesa_id === $mesa->id,
                ];
            });

        $this->dispatchBrowserEvent('mesasDisponibles', ['mesas' => $mesas]);
    }

    public function crearVentaDelivery($clienteId)
    {
        try {
            $cliente = User::find($clienteId);

            if (!$cliente) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Cliente no encontrado',
                ]);
                return;
            }

            $response = $this->ventaService->crearVenta(
                auth()->user()->id,
                $this->sucursal,
                $clienteId,
                null,
                'delivery'
            );

            $this->seleccionar($response->data->id);
            $this->dispatchBrowserEvent('alert', [
                'type' => $response->type,
                'message' => $response->message,
            ]);

            if ($response->success) {
                $this->reset(['user', 'cliente', 'sucursal']);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al crear venta delivery: ' . $e->getMessage(),
            ]);
        }
    }

    public function crearVentaReserva($tipoEntrega, $mesaId = null, $clienteId = null, $fechaHora = null)
    {
        try {
            // Validaciones según el tipo de entrega
            if ($tipoEntrega === 'mesa' && !$mesaId) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Debe seleccionar una mesa',
                ]);
                return;
            }

            if ($tipoEntrega === 'delivery' && !$clienteId) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Debe seleccionar un cliente para delivery',
                ]);
                return;
            }

            if (!$fechaHora) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Debe seleccionar fecha y hora de reserva',
                ]);
                return;
            }

            $response = $this->ventaService->crearVenta(
                auth()->user()->id,
                $this->sucursal,
                $clienteId,
                $mesaId,
                $tipoEntrega,  // El tipo real (mesa, delivery, recoger)
                $fechaHora     // La fecha de reserva
            );

            $this->seleccionar($response->data->id);
            $this->dispatchBrowserEvent('alert', [
                'type' => $response->type,
                'message' => $response->message . ' (Reservada para ' . date('d/m/Y H:i', strtotime($fechaHora)) . ')',
            ]);

            if ($response->success) {
                $this->reset(['user', 'cliente', 'sucursal']);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al crear venta reserva: ' . $e->getMessage(),
            ]);
        }
    }

    public function cambiarTipoEntregaVenta($tipoEntrega, $mesaId = null, $clienteId = null, $fechaHora = null)
    {
        try {
            if (!$this->cuenta) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'No hay una venta seleccionada',
                ]);
                return;
            }

            // Validaciones según el tipo de entrega
            if ($tipoEntrega === 'mesa' && !$mesaId) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Debe seleccionar una mesa',
                ]);
                return;
            }

            if ($tipoEntrega === 'delivery' && !$clienteId) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Debe seleccionar un cliente para delivery',
                ]);
                return;
            }

            // Verificar si la mesa está ocupada
            if ($tipoEntrega === 'mesa' && $mesaId) {
                $mesa = Mesa::find($mesaId);
                if ($mesa->venta && $mesa->venta->id !== $this->cuenta->id && !$mesa->venta->pagado) {
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'error',
                        'message' => 'La mesa seleccionada ya está ocupada',
                    ]);
                    return;
                }
            }

            // Actualizar la venta
            $this->cuenta->tipo_entrega = $tipoEntrega;
            $this->cuenta->mesa_id = $tipoEntrega === 'mesa' ? $mesaId : null;
            $this->cuenta->cliente_id = $clienteId;
            $this->cuenta->reservado_at = $fechaHora; // Si es null, se limpia la reserva
            $this->cuenta->save();

            $mensajeTipo = [
                'mesa' => 'Mesa',
                'delivery' => 'Delivery',
                'recoger' => 'Venta Rápida',
            ];

            $mensaje = 'Tipo de entrega cambiado a: ' . ($mensajeTipo[$tipoEntrega] ?? $tipoEntrega);

            if ($fechaHora) {
                $mensaje .= ' (Reservada para ' . date('d/m/Y H:i', strtotime($fechaHora)) . ')';
            }

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => $mensaje,
            ]);

            // Refrescar la cuenta
            $this->cuenta = Venta::find($this->cuenta->id);
            $this->actualizarlista($this->cuenta);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al cambiar tipo de entrega: ' . $e->getMessage(),
            ]);
        }
    }
    public function seleccionaritem($numero)
    {
        /*  foreach($this->productoapuntado->ventas->where('id','7') as $asd)
        {
            dd($asd->pivot->cantidad);
        }*/

        $this->itemseleccionado = $numero;
    }
    public function cambiarClienteACuenta(User $cliente)
    {
        $response = $this->ventaService->cambiarClienteVenta($this->cuenta, $cliente);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->cuenta = $response->data;
            $this->cliente = $cliente->id;
            $this->reset('user');
            $this->actualizarlista($this->cuenta);
        }
    }
    public function cargarObservacion(Producto $prod)
    {
        //dd($prod);
        $observacion = $prod->ventas;

        foreach ($observacion->where('id', $this->cuenta->id) as $lista) {
            $this->observacion = $lista->pivot->observacion;

            break;
        }
    }

    public function crearCliente()
    {
        $this->validate([
            'name' => 'required|min:5|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role_id' => 4,
            'nacimiento' => $this->cumpleano,
            'direccion' => $this->direccion,
        ]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Nuevo cliente: ' . $this->name . ' creado!',
        ]);
        $this->resetExcept(['metodosPagos']);
    }
    public function mostraradicionales(Producto $producto)
    {
        if ($producto->medicion == 'unidad') {
            $adicionales = $producto->subcategoria->adicionales;
            $this->adicionales = $adicionales;
            $venta = DB::table('producto_venta')->where('venta_id', $this->cuenta->id)->where('producto_id', $producto->id)->first();
            $this->array = json_decode($venta->adicionales, true);
            if (isset($this->productoapuntado) && $producto->id == $this->productoapuntado->id) {
                $this->reset('observacion');
            } else {
                $this->reset('observacion', 'itemseleccionado');
            }
            $this->productoapuntado = $producto;
        } else {
            $this->reset(['adicionales', 'productoapuntado']);
        }
    }

    public function seleccionarcliente($id, $name)
    {
        $this->cliente = $id;
    }

    public function actualizarCuenta()
    {
        $this->actualizarlista($this->cuenta);
    }
    public function actualizarlista($cuenta)
    {
        $calculos = $this->calculadoraService->calcularVenta($cuenta, $this->descuentoSaldo);

        $this->listacuenta = $calculos->listaCuenta;
        $this->subtotal = $calculos->subtotal;
        $this->itemsCuenta = $calculos->itemsCuenta;
        $this->descuentoProductos = $calculos->descuentoProductos;
        $this->descuentoConvenio = $calculos->descuentoConvenio;
        $this->subtotalConDescuento = $calculos->subtotalConDescuento;
        $this->totalAdicionales = $calculos->totalAdicionales;

        $this->cuenta->puntos = $calculos->puntos;

        // Actualizar totales en BD
        $this->calculadoraService->actualizarTotalesVenta($cuenta);

        // $this->reset(['adicionales']);
        $this->saldo = false;
        $this->saldoRestante = 0;
    }

    public function agregaradicional(Adicionale $adicional, $item)
    {
        if (!isset($this->itemseleccionado)) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Seleccione un item',
            ]);
            return;
        }

        $response = $this->productoVentaService->agregarAdicional(
            $this->cuenta,
            $this->productoapuntado,
            $adicional,
            $item
        );

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {

            $this->actualizarlista($this->cuenta);
            $this->mostraradicionales($this->productoapuntado);
            // dd($this->productoapuntado);

        }
    }
    public function eliminarItem()
    {
        $response = $this->productoVentaService->eliminarItem(
            $this->cuenta,
            $this->productoapuntado,
            $this->itemseleccionado
        );

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $producto = $this->productoapuntado;
            $this->actualizarlista(Venta::find($this->cuenta->id));
            $this->mostraradicionales($producto);
        }
    }

    public function actualizarstock(Producto $producto, $operacion, $cant)
    {
        $consulta = DB::table('producto_sucursale')->where('producto_id', $producto->id)->where('sucursale_id', $this->cuenta->sucursale_id)->orderBy('fecha_venc', 'asc')->get();
        $stock = $consulta->where('cantidad', '!=', 0)->first();
        $cantidadtotal = $consulta->pluck('cantidad');
        $sumado = $cantidadtotal->sum();

        if ($consulta == null) {
            return null;
        } else {
            switch ($operacion) {
                case 'sumar':
                    if ($stock == null) {
                        return null;
                    } else {
                        $restado = $stock->cantidad - $cant;
                        DB::table('producto_sucursale')
                            ->where('id', $stock->id)
                            ->update(['cantidad' => $restado]);
                    }

                    break;
                case 'restar':
                    $consultarestar = $consulta->sortByDesc('fecha_venc');

                    foreach ($consultarestar as $array) {
                        $espacio = $array->max - $array->cantidad;

                        if ($espacio != 0) {
                            if ($espacio >= $cant) {
                                DB::table('producto_sucursale')->where('id', $array->id)->increment('cantidad', $cant);
                                break;
                            } else {
                                $cant = $cant - $espacio;
                                DB::table('producto_sucursale')
                                    ->where('id', $array->id)
                                    ->update(['cantidad' => $array->max]);
                            }
                        }
                    }
                    break;
                case 'sumarvarios':
                    if ($sumado > $cant) {
                        foreach ($consulta as $array) {
                            if ($array->cantidad > $cant) {
                                DB::table('producto_sucursale')->where('id', $array->id)->decrement('cantidad', $cant);
                                break;
                            } else {
                                $cant = $cant - $array->cantidad;
                                DB::table('producto_sucursale')
                                    ->where('id', $array->id)
                                    ->update(['cantidad' => 0]);
                            }
                        }
                    } else {
                        return false;
                    }
                    break;
            }
            return true;
        }
    }

    public function adicionar(Producto $producto)
    {
        // Verificar si el producto tiene adicionales
        if ($this->productoTieneAdicionales($producto)) {
            // Mostrar SweetAlert para seleccionar adicionales
            $this->mostrarSweetAlertAdicionales($producto);
        } else {
            // Agregar producto directamente
            $this->agregarProductoDirecto($producto);
        }
    }

    private function productoTieneAdicionales(Producto $producto): bool
    {
        return $producto->subcategoria &&
            $producto->subcategoria->adicionalesGrupo()->isNotEmpty();
    }

    private function mostrarSweetAlertAdicionales(Producto $producto)
    {
        $adicionalesConGrupos = $producto->subcategoria->adicionalesGrupo();
        $grupos = $this->organizarAdicionalesPorGrupos($adicionalesConGrupos);

        $this->dispatchBrowserEvent('mostrarSweetAlertAdicionales', [
            'producto' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precioReal(),
                'imagen' => $producto->pathAttachment(),
            ],
            'grupos' => $grupos
        ]);
    }

    private function organizarAdicionalesPorGrupos($adicionalesConGrupos)
    {
        $grupos = [];

        foreach ($adicionalesConGrupos as $adicional) {
            $grupoId = $adicional->grupo_id;
            $grupoNombre = $adicional->nombre_grupo;
            $esObligatorio = (bool) $adicional->es_obligatorio;
            $maximoSeleccionable = $adicional->maximo_seleccionable;

            if (!isset($grupos[$grupoId])) {
                $grupos[$grupoId] = [
                    'id' => $grupoId,
                    'nombre' => $grupoNombre,
                    'es_obligatorio' => $esObligatorio,
                    'maximo_seleccionable' => $maximoSeleccionable,
                    'adicionales' => []
                ];
            }

            $grupos[$grupoId]['adicionales'][] = [
                'id' => $adicional->id,
                'nombre' => $adicional->nombre,
                'precio' => (float) $adicional->precio,
                'contable' => (bool) $adicional->contable,
                'cantidad' => $adicional->cantidad
            ];
        }

        return array_values($grupos);
    }

    public function agregarProductoConAdicionales($productoId, $adicionalesSeleccionados, $cantidad = 1)
    {
        try {
            $producto = Producto::find($productoId);
            $adicionales = Adicionale::whereIn('id', $adicionalesSeleccionados)->get();

            $response = $this->productoVentaService->agregarProductoCliente(
                $this->cuenta,
                $producto,
                $adicionales,
                $cantidad
            );

            $this->dispatchBrowserEvent('alert', [
                'type' => $response->type,
                'message' => $response->message,
            ]);

            if ($response->success) {
                $this->actualizarlista(Venta::find($this->cuenta->id));
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al agregar producto: ' . $e->getMessage(),
            ]);
        }
    }

    private function agregarProductoDirecto(Producto $producto)
    {
        $response = $this->productoVentaService->agregarProducto($this->cuenta, $producto);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->actualizarlista(Venta::find($this->cuenta->id));
        }
    }

    public function adicionarvarios(Producto $producto)
    {
        if ($this->cantidadespecifica == null) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Fije una cantidad',
            ]);
            return;
        }

        $response = $this->productoVentaService->agregarProducto($this->cuenta, $producto, $this->cantidadespecifica);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->actualizarlista(Venta::find($this->cuenta->id));
            $this->reset('cantidadespecifica');
        }
    }

    public function eliminaruno(Producto $producto)
    {
        $response = $this->productoVentaService->eliminarUnoProducto($this->cuenta, $producto);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->actualizarlista(Venta::find($this->cuenta->id));
        }
    }

    public function eliminarproducto(Producto $producto)
    {
        $response = $this->productoVentaService->eliminarProductoCompleto($this->cuenta, $producto);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->actualizarlista(Venta::find($this->cuenta->id));
        }
    }

    public function seleccionar($venta)
    {
        $venta = Venta::find($venta);
        if (!$venta) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Esta venta ya no existe',
            ]);
            return false;
        }
        $this->cuenta = $venta;
        $this->reset('tipocobro', 'metodosSeleccionados', 'totalAcumuladoMetodos', 'descuentoSaldo', 'saldoSobranteCheck', 'adicionales', 'productoapuntado');
        $this->saldoRestante = 0;
        $this->saldo = false;
        $this->emit('focusInputBuscador', $this->cuenta->id);
        $this->actualizarlista($venta);
        $this->reset('clienteRecibo', 'fechaRecibo', 'checkClientePersonalizado', 'modoImpresion', 'observacionRecibo', 'checkMetodoPagoPersonalizado', 'metodoRecibo');
    }

    public function eliminar(Venta $venta)
    {
        $venta->delete();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => 'Venta eliminada',
        ]);
        if ($this->cuenta != null) {
            if ($venta->id == $this->cuenta->id) {
                $this->resetExcept(['metodosPagos']);
            }
        }
    }
    public function agregardesdeplan($user, $plan, $producto)
    {
        $planusuario = DB::table('plane_user')->where('user_id', $user)->where('plane_id', $plan)->decrement('restante', 1);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se resto una unidad al plan',
        ]);

        $ventaactual = Venta::find($this->cuenta->id);
        $productoplan = Producto::find($producto);
        if ($productoplan->descuento != null || $productoplan->descuento != '') {
            $ventaactual->descuento = $ventaactual->descuento + $productoplan->descuento;
        } else {
            $ventaactual->descuento = $ventaactual->descuento + $productoplan->precio;
        }

        $ventaactual->update();
        $this->adicionar($productoplan);
        $ventaactual = Venta::find($this->cuenta->id);
        $this->cuenta = $ventaactual;
    }

    public function cobrar()
    {

        $response = $this->ventaService->cobrarVenta(
            $this->cuenta,
            $this->metodosSeleccionados,
            $this->totalAcumuladoMetodos,
            $this->subtotalConDescuento,
            $this->descuentoSaldo
        );


        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->cuenta = $response->data;
        }
    }

    public function cerrarVenta()
    {
        $response = $this->ventaService->cerrarVenta($this->cuenta);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->reset('cuenta');
        }
    }
    public function editardescuento()
    {
        $response = $this->ventaService->editarDescuento($this->cuenta, $this->descuento);

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->cuenta = $response->data;
            $this->reset('descuento');
            $this->actualizarlista($this->cuenta);
        }
    }
    public function descargarPDF()
    {
        $metodosPagosRecibo = null;
        if ($this->cuenta->ventaHistorial && $this->cuenta->ventaHistorial->metodosPagos->isNotEmpty()) {
            $metodosPagosRecibo = $this->cuenta->ventaHistorial->metodosPagos;
        } else {
            $metodosPagosRecibo = null;
        }
        // dd($this->cuenta->ventaHistorial);

        $data = [
            'cuenta' => $this->cuenta->ventaHistorial,
            // Aquí puedes pasar las variables necesarias para la vista Blade
            'nombreCliente' => !$this->checkClientePersonalizado ? (isset($this->cuenta->cliente->name) ? Str::limit($this->cuenta->cliente->name, '20', '') : 'Anonimo') : $this->clienteRecibo,
            'listaCuenta' => $this->listacuenta,
            'subtotal' => $this->cuenta->total + $this->descuentoProductos,
            'descuentoProductos' => $this->descuentoProductos,
            'otrosDescuentos' => $this->cuenta->descuento,
            'valorSaldo' => $this->cuenta->ventaHistorial->saldo_monto,
            'metodo' => isset($metodosPagosRecibo) ? $metodosPagosRecibo : null,
            'observacion' => isset($this->observacionRecibo) ? $this->observacionRecibo : null,
            'fecha' => isset($this->fechaRecibo) ? $this->fechaRecibo : date('d-m-Y H:i:s'),
        ];
        // dd($data);
        $pdf = Pdf::loadView('pdf.recibo-nuevo', $data)->output();
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $data['nombreCliente'] . '-' . date('d-m-Y-H:i:s') . '.pdf');
    }
    public function imprimirReciboCliente()
    {
        $metodosPagosRecibo = null;
        if ($this->cuenta->ventaHistorial && $this->cuenta->ventaHistorial->metodosPagos->isNotEmpty()) {
            $metodosPagosRecibo = $this->cuenta->ventaHistorial->metodosPagos;
        } else {
            $metodosPagosRecibo = null;
        }
        $recibo = CustomPrint::imprimirReciboVenta(!$this->checkClientePersonalizado ? (isset($this->cuenta->cliente->name) ? Str::limit($this->cuenta->cliente->name, '20', '') : null) : $this->clienteRecibo, $this->listacuenta, $this->cuenta->total + $this->descuentoProductos, $this->cuenta->ventaHistorial->saldo_monto, $this->descuentoProductos, $this->cuenta->descuento, isset($this->fechaRecibo) ? $this->fechaRecibo : date('d-m-Y H:i:s'), isset($this->observacionRecibo) ? $this->observacionRecibo : null, $metodosPagosRecibo, $this->cuenta->ventaHistorial);
        $respuesta = CustomPrint::imprimir($recibo, $this->cuenta->sucursale->id_impresora);
        if ($this->cuenta->sucursale->id_impresora) {
            if ($respuesta == true) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Se imprimio el recibo correctamente',
                ]);
                ReciboImpreso::create([
                    'observacion' => $this->observacionRecibo,
                    'cliente' => $this->cuenta->cliente,
                    'telefono' => $this->telefonoRecibo,
                    'fecha' => isset($this->fechaRecibo) ? Carbon::parse($this->fechaRecibo)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
                    'metodo' => $this->metodoRecibo,
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

    public function cambiarPrioridad(Producto $producto, $prioridad)
    {
        $producto->prioridad = $prioridad;
        $producto->save();
    }
    public function anularSaldo(Saldo $saldo)
    {
        if ($saldo->anulado) {

            $saldo->anulado = false;
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'El saldo vuelve a estar activo!',
            ]);
        } else {

            $saldo->anulado = true;
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'El saldo fue anulado!',
            ]);
        }
        $this->cuenta = Venta::where('cliente_id', $this->cuenta->cliente->id)->first();
        $saldo->save();
    }
    public function seleccionarSubcategoria($id)
    {
        Subcategoria::find($id)->increment('interacciones');
        $this->reset('search');
        $this->subcategoriaSeleccionada = $id;
        $this->emit('scrollToSubcategoria', $id);
    }
    public function render()
    {
        if (isset($this->ventaSeleccionada)) {
            $this->ventaSeleccionada->fresh();
        }

        $ventas = Venta::orderByRaw('reservado_at IS NULL')
            ->orderBy('reservado_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        $usuarios = collect();
        $sucursales = Sucursale::pluck('id', 'nombre');
        $this->sucursal = $sucursales->first();
        $mesas = Mesa::where('sucursale_id', $this->sucursal)->get();
        $productos = Producto::where('estado', 'activo')
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $subQuery) {
                    $subQuery->where('codigoBarra', $this->search)->orWhere('nombre', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->when(isset($this->subcategoriaSeleccionada), function (Builder $query) {
                $query->where('subcategoria_id', $this->subcategoriaSeleccionada);
            })
            ->orderBy('prioridad', 'desc')
            ->take(15)
            ->get();

        $subcategorias = Subcategoria::when(isset($this->search), function ($query) {
            $query->where('nombre', 'LIKE', '%' . $this->search . '%');
        })
            ->orderBy('interacciones', 'desc')
            ->get();

        if ($this->user != null) {
            $usuarios = User::where('name', 'LIKE', '%' . $this->user . '%')
                ->orWhere('email', 'LIKE', '%' . $this->user . '%')
                ->take(5)
                ->get();
        }
        return view('livewire.admin.ventas.ventas-index', compact('mesas', 'ventas', 'sucursales', 'subcategorias', 'productos', 'usuarios'))->extends('admin.master')->section('content');
    }
}
