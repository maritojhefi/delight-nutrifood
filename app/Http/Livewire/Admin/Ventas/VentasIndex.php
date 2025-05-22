<?php

namespace App\Http\Livewire\Admin\Ventas;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\User;
use App\Models\Plane;
use App\Models\Saldo;
use App\Models\Venta;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Adicionale;
use Mike42\Escpos\Printer;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Helpers\CustomPrint;
use App\Models\ReciboImpreso;
use Mike42\Escpos\EscposImage;
use App\Models\Historial_venta;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\CocinaPedidoEvent;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Livewire\Admin\PedidosRealtimeComponent;
use App\Models\MetodoPago;
use App\Models\Subcategoria;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class VentasIndex extends Component
{
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
    public $descuentoProductos, $subtotal, $subtotalConDescuento, $descuentoSaldo = 0, $saldoSobranteCheck = false, $maxDescuentoSaldo;
    public $subcategoriaSeleccionada;
    protected $rules = [
        'sucursal' => 'required|integer',
    ];
    protected $listeners = [
        'cobrar' => 'cobrar',
        'cerrarVenta' => 'cerrarVenta',
        'imprimir' => 'imprimir',
        'descargarPDF' => 'descargarPDF',
        'modalResumen' => 'modalResumen',
    ];
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
                    $this->reset('totalAcumuladoMetodos','metodosSeleccionados');
                    $this->actualizarLista($this->cuenta);
                }else
                {
                    $this->descuentoSaldo = 0;
                    $this->actualizarLista($this->cuenta);
                }
                break;
            case 'descuentoSaldo':
                if ($this->descuentoSaldo > $this->maxDescuentoSaldo) {
                    $this->descuentoSaldo = $this->maxDescuentoSaldo;
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'info',
                        'message' => 'El máximo que puede ingresar es de '.$this->descuentoSaldo.' Bs',
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
        $this->cuenta->cocina = true;
        $this->cuenta->cocina_at = Carbon::now();
        $this->cuenta->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se envio a cocina!',
        ]);
        // event(new CocinaPedidoEvent('Se actualizo la lista'));
    }
    public function addUsuarioManual()
    {
        if ($this->userManual != '') {
            $this->cuenta->usuario_manual = $this->userManual;
            $this->cuenta->save();
            $this->cuenta = Venta::find($this->cuenta->id);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Hecho!',
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Formato Incorrecto',
            ]);
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
            //$printer->text("DELIGHT" . "\n");
            $img = EscposImage::load(public_path('delight_logo.jpg'));
            $printer->bitImageColumnFormat($img);
            $printer->setTextSize(1, 1);
            $printer->text('Nutri-Food/Eco-Tienda' . "\n");
            $printer->feed(1);
            $printer->text("'NUTRIENDO HABITOS!'" . "\n");
            $printer->feed(1);
            $printer->text('Contacto : 78227629' . "\n" . 'Campero e/15 de abril y Madrid' . "\n");
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
        DB::table('producto_venta')
            ->where('producto_id', $idprod)
            ->where('venta_id', $this->cuenta->id)
            ->update(['observacion' => $this->observacion]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Guardado',
        ]);
    }
    public function crear()
    {
        $this->validate();

        Venta::create([
            'usuario_id' => auth()->user()->id,
            'sucursale_id' => $this->sucursal,
            'cliente_id' => $this->cliente,
        ]);
        $this->reset(['user', 'cliente', 'sucursal']);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Nueva venta creada ',
        ]);
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
        $this->cuenta->cliente_id = $cliente->id;
        $this->cuenta->usuario_manual = null;
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se asigno a esta venta el cliente: ' . $cliente->name,
        ]);
        $this->cuenta->save();
        $this->cuenta->cliente = $cliente;
        $this->cliente = $cliente->id;
        $this->reset('user');
        $this->actualizarlista($this->cuenta);
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

    public function actualizaradicionales($idproducto, $operacion)
    {
        $registro = DB::table('producto_venta')->where('producto_id', $idproducto)->where('venta_id', $this->cuenta->id)->get();

        if ($registro->count() != 0) {
            $listaadicionales = $registro[0]->adicionales;
            if ($listaadicionales == null) {
                $string = '{"1":[]}';
                DB::table('producto_venta')
                    ->where('producto_id', $idproducto)
                    ->where('venta_id', $this->cuenta->id)
                    ->update(['adicionales' => $string]);
            } else {
                $json = json_decode($listaadicionales, true);

                $cantidad = collect($json)->count();
                if ($operacion == 'sumar') {
                    $string[] = [];
                    //dd($string);
                    array_push($json, $string[0]);
                    //dd($json);
                    DB::table('producto_venta')
                        ->where('producto_id', $idproducto)
                        ->where('venta_id', $this->cuenta->id)
                        ->update(['adicionales' => $json]);
                } elseif ($operacion == 'muchos') {
                    $string[] = [];
                    for ($i = 0; $i < $this->cantidadespecifica; $i++) {
                        array_push($json, $string[0]);
                    }

                    DB::table('producto_venta')
                        ->where('producto_id', $idproducto)
                        ->where('venta_id', $this->cuenta->id)
                        ->update(['adicionales' => $json]);
                } elseif ($registro[0]->cantidad > 0) {
                    foreach ($json[$cantidad] as $pos => $adic) {
                        $adicional = Adicionale::where('nombre', key($adic))->first();
                        if ($adicional && $adicional->contable) {
                            $adicional->increment('cantidad');
                            $adicional->save();
                            GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
                        }
                    }
                    unset($json[$cantidad]);
                    $string = json_encode($json);
                    DB::table('producto_venta')
                        ->where('producto_id', $idproducto)
                        ->where('venta_id', $this->cuenta->id)
                        ->update(['adicionales' => $string]);
                }
            }
        }
    }

    public function actualizarlista($cuenta)
    {
        $resultado = CreateList::crearlista($cuenta);
        $this->listacuenta = $resultado[0];
        DB::table('ventas')
            ->where('id', $cuenta->id)
            ->update(['total' => $resultado[1] - $resultado[4], 'puntos' => $resultado[3]]);

        $this->subtotal = $resultado[1];
        $this->cuenta->puntos = $resultado[3];
        $this->descuentoProductos = $resultado[4];
        $this->subtotalConDescuento = $this->subtotal - $this->descuentoProductos - $this->descuentoSaldo - $this->cuenta->descuento;
        $this->itemsCuenta = $resultado[2];
        $this->reset(['adicionales', 'productoapuntado']);
        $this->saldo = false;
        $this->saldoRestante = 0;
    }

    public function agregaradicional(Adicionale $adicional, $item)
    {
        if ($this->cuenta->pagado == true) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'La venta ya ha sido pagada, no se puede modificar',
            ]);
            return false;
        }
        //dd($this->productoapuntado);
        if (isset($this->itemseleccionado)) {
            if ($this->productoapuntado->medicion == 'unidad') {
                if ($adicional->contable == true && $adicional->cantidad == 0) {
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'warning',
                        'message' => 'No existe stock para ' . $adicional->nombre,
                    ]);
                } else {
                    $pivot = DB::table('producto_venta')->where('producto_id', $this->productoapuntado->id)->where('venta_id', $this->cuenta->id)->first();
                    $string = $pivot->adicionales;
                    $array = json_decode($string, true);
                    for ($i = 1; $i <= $item; $i++) {
                        if ($i == $item) {
                            array_push($array[$i], [$adicional->nombre => $adicional->precio]);
                            //dd($array[$i]);
                        }
                    }
                    $lista = json_encode($array);
                    DB::table('producto_venta')
                        ->where('producto_id', $this->productoapuntado->id)
                        ->where('venta_id', $this->cuenta->id)
                        ->update(['adicionales' => $lista]);
                    // $this->dispatchBrowserEvent('alert', [
                    //     'type' => 'success',
                    //     'message' => "Agregado!"
                    // ]);
                    if ($adicional->contable) {
                        GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'reducir');
                        $adicional->decrement('cantidad');
                    }

                    $adicional->save();
                    $resultado = CreateList::crearlista($this->cuenta);
                    $this->listacuenta = $resultado[0];
                    DB::table('ventas')
                        ->where('id', $this->cuenta->id)
                        ->update(['total' => $resultado[1]]);
                    $this->subtotal = $resultado[1];
                    $this->cuenta->puntos = $resultado[3];
                    $this->itemsCuenta = $resultado[2];
                }
            }

            $this->mostraradicionales($this->productoapuntado);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Seleccione un item',
            ]);
        }
    }
    public function eliminarItem()
    {
        if ($this->cuenta->pagado == true) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'La venta ya ha sido pagada, no se puede modificar',
            ]);
            return false;
        }
        $anterior = $this->array;
        $posicion = $this->itemseleccionado;
        // Eliminar el elemento en la posición específica
        if (count($this->array) == 1) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'No puede eliminar el unico item disponible',
            ]);
        } else {
            foreach ($this->array[$posicion] as $pos => $adic) {
                $adicional = Adicionale::where('nombre', key($adic))->first();
                if ($adicional && $adicional->contable) {
                    $adicional->increment('cantidad');
                    $adicional->save();
                    GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
                }
            }
            // dd('asasd');
            unset($this->array[$posicion]);
            // Reorganizar las claves para que no queden huecos
            // dd($this->array,$posicion);
            $this->array = array_values($this->array);

            // Volver a numerar las claves a partir de 1
            $nuevoArray = array_combine(range(1, count($this->array)), array_values($this->array));
            // dd($anterior,$this->array);

            DB::table('producto_venta')
                ->where('producto_id', $this->productoapuntado->id)
                ->where('venta_id', $this->cuenta->id)
                ->update(['adicionales' => $nuevoArray]);
            DB::table('producto_venta')->where('producto_id', $this->productoapuntado->id)->where('venta_id', $this->cuenta->id)->decrement('cantidad');

            $producto = $this->productoapuntado;
            $venta = Venta::find($this->cuenta->id);
            $this->actualizarlista($venta);
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
        if ($this->cuenta->pagado == true) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'La venta ya ha sido pagada, no se puede modificar',
            ]);
            return false;
        }
        if ($producto->contable == true) {
            $resultado = $this->actualizarstock($producto, 'sumar', 1);
            if ($resultado == null) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'warning',
                    'message' => 'No se puede agregar porque no existe stock para este producto',
                ]);
            } else {
                $cuenta = Venta::find($this->cuenta->id);
                $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->get();

                if ($registro->count() == 0) {
                    $cuenta->productos()->attach($producto->id);
                } else {
                    DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', 1);
                }
                //actualiza lista de adicionales en el atributo
                if ($producto->medicion == 'unidad') {
                    $this->actualizaradicionales($producto->id, 'sumar');
                }

                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Se agrego 1 ' . $producto->nombre . ' a esta venta',
                ]);
                $this->actualizarlista($cuenta);
            }
        } else {
            $cuenta = Venta::find($this->cuenta->id);
            $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->get();

            if ($registro->count() == 0) {
                $cuenta->productos()->attach($producto->id);
            } else {
                DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', 1);
            }
            //actualiza lista de adicionales en el atributo
            if ($producto->medicion == 'unidad') {
                $this->actualizaradicionales($producto->id, 'sumar');
            }

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Se agrego 1 ' . $producto->nombre . ' a esta venta',
            ]);
            $this->actualizarlista($cuenta);
        }
    }

    public function adicionarvarios(Producto $producto)
    {
        if ($this->cuenta->pagado == true) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'La venta ya ha sido pagada, no se puede modificar',
            ]);
            return false;
        }
        if ($this->cantidadespecifica != null) {
            if ($producto->contable == true) {
                $resultado = $this->actualizarstock($producto, 'sumarvarios', $this->cantidadespecifica);
                if ($resultado == false) {
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'warning',
                        'message' => 'No se puede agregar porque no existe stock suficiente para este producto',
                    ]);
                } elseif ($resultado == null) {
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'warning',
                        'message' => 'Este producto no tiene stock',
                    ]);
                } else {
                    $cuenta = Venta::find($this->cuenta->id);
                    DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', $this->cantidadespecifica);
                    $this->actualizarlista($cuenta);

                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'success',
                        'message' => 'Se agrego ' . $this->cantidadespecifica . ' ' . $producto->nombre . '(s) a esta venta',
                    ]);
                    if ($producto->medicion == 'unidad') {
                        $this->actualizaradicionales($producto->id, 'muchos');
                    }
                    $this->reset('cantidadespecifica');
                }
            } else {
                $cuenta = Venta::find($this->cuenta->id);
                DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', $this->cantidadespecifica);
                $this->actualizarlista($cuenta);

                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Se agrego ' . $this->cantidadespecifica . ' ' . $producto->nombre . '(s) a esta venta',
                ]);
                if ($producto->medicion == 'unidad') {
                    $this->actualizaradicionales($producto->id, 'muchos');
                }
                $this->reset('cantidadespecifica');
            }
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Fije una cantidad',
            ]);
        }
    }

    public function eliminaruno(Producto $producto)
    {
        if ($this->cuenta->pagado == true) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'La venta ya ha sido pagada, no se puede modificar',
            ]);
            return false;
        }
        if ($producto->contable == true) {
            $this->actualizarstock($producto, 'restar', 1);
        }
        $cuenta = Venta::find($this->cuenta->id);
        $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->get();
        if (isset($registro[0])) {
            if ($registro[0]->cantidad == 1) {
                $cuenta->productos()->detach($producto->id);
            } else {
                DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->decrement('cantidad', 1);
            }
            $this->actualizarlista($cuenta);
            $this->actualizaradicionales($producto->id, 'restar');
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Se elimino 1 ' . $producto->nombre . ' de esta venta',
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Ocurrio un error al eliminar este producto, intente nuevamente.',
            ]);
        }
    }

    public function eliminarproducto(Producto $producto)
    {
        if ($this->cuenta->pagado == true) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'La venta ya ha sido pagada, no se puede modificar',
            ]);
            return false;
        }
        $cuenta = Venta::find($this->cuenta->id);
        $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->first();
        if (isset($registro)) {
            $arrayAdicionales = json_decode($registro->adicionales);

            if (is_null($arrayAdicionales)) {
                $todoVacio = true;
            } else {
                // Convertir el objeto en un array
                $array = (array) $arrayAdicionales;

                // Verificar si todos los elementos están vacíos
                $todoVacio = empty(array_filter($array, function ($item) {
                    return !empty($item); // Filtra los items que no están vacíos
                }));
            }

            if ($todoVacio) {
                if ($producto->contable == true) {
                    $this->actualizarstock($producto, 'restar', $registro->cantidad);
                }
                $cuenta->productos()->detach($producto->id);

                $this->actualizarlista($cuenta);
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Se elimino a ' . $producto->nombre . ' de esta venta',
                ]);
                if ($producto->subcategoria->categoria->nombre != 'ECO-TIENDA') {
                    //revisa si es de cocina/panaderia el producto para que actualice en la vista de cocina
                    // event(new CocinaPedidoEvent("Se actualizo la mesa " . $this->cuenta->id));
                }
            } else {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'warning',
                    'message' => 'El producto ' . $producto->nombre . ' tiene adicionales personalizados, eliminelos primero',
                ]);
            }
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'El producto ' . $producto->nombre . ' no existe en esta venta',
            ]);
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
        $this->reset('tipocobro', 'metodosSeleccionados', 'totalAcumuladoMetodos','descuentoSaldo','saldoSobranteCheck');
        $this->saldoRestante = 0;
        $this->saldo = false;
        $this->emit('focusInputBuscador');
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
        if ($this->cuenta->pagado == true) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Esta venta ya se encuentra pagada!',
            ]);
            return false;
        }
        if (!$this->cuenta->cliente && $this->totalAcumuladoMetodos != $this->subtotalConDescuento) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Los metodos de pago no equivalen al monto total de la venta',
            ]);
            return false;
        }
        // $this->validate(['tipocobro' => 'required']);
        $cajaactiva = Caja::where('sucursale_id', $this->cuenta->sucursale->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($cajaactiva != null) {
            if ($cajaactiva->estado == 'abierto') {
                try {
                    //inicio de transaccion de cobranza
                    DB::beginTransaction();
                    DB::table('cajas')
                        ->where('id', $cajaactiva->id)
                        ->increment('acumulado', $this->subtotal - $this->cuenta->descuento - $this->cuenta->saldo - $this->descuentoProductos);
                    $cuenta = Venta::find($this->cuenta->id);
                    //revisar si hay saldo y si es a favor o en deuda del cliente
                    // dd($this->totalAcumuladoMetodos,$this->subtotalConDescuento,$this->descuentoSaldo);
                    if($this->descuentoSaldo > 0){
                        $saldoAFavorCliente = false;
                        $montoSaldo = $this->descuentoSaldo;
                    }
                    else if ($this->totalAcumuladoMetodos === $this->subtotalConDescuento) {
                        $saldoAFavorCliente = null;
                        $montoSaldo = 0;
                    } else if ($this->totalAcumuladoMetodos < $this->subtotalConDescuento) {
                        $saldoAFavorCliente = false;
                        $montoSaldo = $this->subtotalConDescuento - $this->totalAcumuladoMetodos;
                    } else {
                        $saldoAFavorCliente = true;
                        $montoSaldo = $this->totalAcumuladoMetodos - $this->subtotalConDescuento;
                    }
                    
                    $cuentaguardada = Historial_venta::create([
                        'caja_id' => $cajaactiva->id,
                        'usuario_id' => auth()->user()->id,
                        'sucursale_id' => $this->cuenta->sucursale_id,
                        'cliente_id' => $this->cuenta->cliente_id,
                        'total' => $this->subtotal - $this->descuentoProductos,
                        'puntos' => $this->cuenta->puntos,
                        'descuento' => $this->cuenta->descuento,
                        'tipo' => $this->tipocobro ?? 'N/A',
                        'saldo' => (float) $this->valorSaldo,
                        //nuevos campos v2 de ventas
                        'subtotal' => (float) $this->subtotal,
                        'total_pagado' => (float) $this->totalAcumuladoMetodos,
                        'total_a_pagar' => (float) $this->subtotalConDescuento,
                        'descuento_productos' => (float) $this->descuentoProductos,
                        'descuento_saldo' => (float) $this->descuentoSaldo,
                        'descuento_manual' => $this->cuenta->descuento,
                        'total_descuento' => (float) $this->descuentoProductos + $this->cuenta->descuento,
                        'saldo_monto' => (float) $montoSaldo,
                        'a_favor_cliente' => $saldoAFavorCliente,
                    ]);

                    $productos = $cuenta->productos;
                    if ($this->cuenta->cliente_id != null) {
                        DB::table('users')->where('id', $this->cuenta->cliente_id)->increment('puntos', $this->cuenta->puntos);
                    }
                    if ($montoSaldo > 0) {
                        Saldo::create([
                            'user_id' => $this->cuenta->cliente_id,
                            'historial_venta_id' => 1,
                            'historial_ventas_id' => $cuentaguardada->id,
                            'caja_id' => $cajaactiva->id,
                            'monto' => $montoSaldo,
                            'es_deuda' => $saldoAFavorCliente ? false : true,
                            'atendido_por' => auth()->user()->id,
                        ]);
                        
                    }
                    
                    //guardar en pivot metodos de pago
                    foreach ($this->metodosSeleccionados as $codigo => $data) {
                        if ($data['activo'] == true && isset($data['valor']) && is_numeric($data['valor'])) {
                            $metodo = MetodoPago::where('codigo', $codigo)->first();
                            $cuentaguardada->metodosPagos()->attach($metodo->id, ['monto' => $data['valor']]);
                        }
                    }
                    // dd($this->listacuenta);
                    foreach ($productos as $prod) {
                        foreach ($this->listacuenta as $lista) {
                            if ($lista['id'] == $prod->id) {
                                $prodLista = $lista;
                                break;
                            }
                        }
                        $cuentaguardada->productos()->attach($prod->id, [
                            'cantidad' => $prod->pivot->cantidad,
                            'adicionales' => $prod->pivot->adicionales,
                            'precio_subtotal' => $prodLista['subtotal'],
                            'precio_unitario' => $prodLista['precio'],
                            'descuento_producto' => $prodLista['descuento_producto'],
                        ]);
                    }
                    $cuenta->historial_venta_id = $cuentaguardada->id;
                    $cuenta->pagado = true;
                    $cuenta->save();

                    $this->cuenta = $cuenta;
                    // $cuenta->productos()->detach();
                    // $cuenta->delete();

                    // $this->reset('cuenta');

                    DB::commit();
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'success',
                        'message' => 'Esta venta ahora se encuentra pagada!',
                    ]);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'error',
                        'message' => 'Error:' . $th->getMessage(),
                    ]);
                }
            } else {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'La caja se encuentra cerrada!',
                ]);
            }
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Aun no se abrio la caja de hoy!',
            ]);
        }
    }

    public function cerrarVenta()
    {
        if ($this->cuenta->cocina == true && $this->cuenta->despachado_cocina == false) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Aún no se despacho el pedido desde cocina.',
            ]);
            return false;
        }
        if ($this->cuenta->pagado == true) {
            $cuenta = Venta::find($this->cuenta->id);
            $cuenta->productos()->detach();
            $cuenta->delete();
            $this->reset('cuenta');
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Se finalizó esta venta',
            ]);
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Primero marque como pagado esta venta',
            ]);
        }
    }
    public function editardescuento()
    {
        DB::table('ventas')
            ->where('id', $this->cuenta->id)
            ->update(['descuento' => $this->descuento]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Descuento actualizado!',
        ]);
        $cuenta = Venta::find($this->cuenta->id);
        $this->cuenta = $cuenta;
        $this->reset('descuento');
        $this->actualizarlista($cuenta);
    }
    public function descargarPDF()
    {
        $data = [
            // Aquí puedes pasar las variables necesarias para la vista Blade
            'nombreCliente' => !$this->checkClientePersonalizado ? (isset($this->cuenta->cliente->name) ? Str::limit($this->cuenta->cliente->name, '20', '') : 'Anonimo') : $this->clienteRecibo,
            'listaCuenta' => $this->listacuenta,
            'subtotal' => $this->cuenta->total + $this->descuentoProductos,
            'descuentoProductos' => $this->descuentoProductos,
            'otrosDescuentos' => $this->cuenta->descuento,
            'valorSaldo' => isset($this->valorSaldo) ? $this->valorSaldo : 0,
            'metodo' => $this->checkMetodoPagoPersonalizado ? $this->metodoRecibo : '',
            'observacion' => isset($this->observacionRecibo) ? $this->observacionRecibo : null,
            'fecha' => isset($this->fechaRecibo) ? $this->fechaRecibo : date('d-m-Y H:i:s'),
        ];
        // dd($data);
        $pdf = Pdf::loadView('pdf.recibo-nuevo', $data)->output();
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $data['nombreCliente'] . '-' . date('d-m-Y-H:i:s') . '.pdf');
    }
    public function imprimir()
    {
        //QrCode::format('png')->size(150)->generate('https://delight-nutrifood.com/miperfil', public_path() . '/qrcode.png');
        if ($this->cuenta->sucursale->id_impresora) {
            $recibo = CustomPrint::imprimirReciboVenta(!$this->checkClientePersonalizado ? (isset($this->cuenta->cliente->name) ? Str::limit($this->cuenta->cliente->name, '20', '') : null) : $this->clienteRecibo, $this->listacuenta, $this->cuenta->total + $this->descuentoProductos, isset($this->valorSaldo) ? $this->valorSaldo : 0, $this->descuentoProductos, $this->cuenta->descuento, isset($this->fechaRecibo) ? $this->fechaRecibo : date('d-m-Y H:i:s'), isset($this->observacionRecibo) ? $this->observacionRecibo : null, $this->checkMetodoPagoPersonalizado ? $this->metodoRecibo : '');
            $respuesta = CustomPrint::imprimir($recibo, $this->cuenta->sucursale->id_impresora);
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
        $ventas = Venta::orderBy('created_at', 'asc')->get();
        $usuarios = collect();
        $sucursales = Sucursale::pluck('id', 'nombre');
        $this->sucursal = $sucursales->first();
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
        return view('livewire.admin.ventas.ventas-index', compact('ventas', 'sucursales', 'subcategorias', 'productos', 'usuarios'))->extends('admin.master')->section('content');
    }
}
