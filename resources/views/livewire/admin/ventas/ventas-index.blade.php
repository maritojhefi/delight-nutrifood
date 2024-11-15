<div class="row m-0 p-0">
    <x-card-col tamano="3">
        <div class="mb-2">
            <center class="card-intro-title p-2 " style="font-size:15px">Ventas Pendientes</center>

            <div class="">

                @foreach ($ventas as $item)
                    <div class="alert alert-{{ $item->productos->count() > 0 ? 'success' : 'danger' }}@isset($cuenta) {{ $item->id == $cuenta->id ? 'solid alert-alt' : '' }} @endisset alert-dismissible fade show p-2 pt-1"
                        style="line-height: 0px">
                        <a href="#" class="p-0 m-0" style="line-height: 20px"
                            wire:click="seleccionar('{{ $item->id }}')">
                            <div wire:loading wire:target="seleccionar({{ $item->id }})"
                                class="spinner-grow spinner-grow-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <small class="letra10"> #{{ $item->id }}</small>
                            <strong
                                class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset ">{{ $item->total }}Bs
                            </strong>
                            @isset($item->usuario_manual)
                                <br>
                                <span class="p-0 m-0"
                                    style="font-size:10px;line-height: 10px">{{ Str::limit($item->usuario_manual, 35) }}</span>
                            @endisset

                            @isset($item->cliente)
                                <br>
                                <span class="p-0 m-0"
                                    style="font-size:10px;line-height: 10px">{{ Str::limit($item->cliente->name, 35) }}</span>
                            @endisset
                        </a>
                        @if ($item->productos->count() == 0)
                            <a href="#" class="float-end badge badge-danger badge-pill"
                                wire:click="eliminar('{{ $item->id }}')">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif
                    </div>
                @endforeach

                <!-- checkbox -->



                <div x-data="{ count: 0 }">
                    <div x-data="{ open: false, count: 'abrir' }">
                        <button class="btn light btn-xs btn-outline-warning p-1 px-2 m-0"
                            @click="open = ! open, count='cerrar'">
                            <template x-if="open">
                                <div>CERRAR</div>
                            </template>
                            <template x-if="!open">
                                <div>ABRIR NUEVA VENTA</div>
                            </template>
                        </button>

                        <div x-show="open" @click.outside="open = false">
                            <div class="row">
                                {{-- <div class="mb-3 col-md-6 mt-2">
                                    <label class="form-label">Sucursal</label>
                                    <select
                                        class="form-control form-control-sm  form-white @error($sucursal) is-invalid @enderror"
                                        wire:model="sucursal">

                                        @foreach ($sucursales as $nombre => $id)
                                            <option value="{{ $id }}">{{ $nombre }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="mb-3 col-md-12 mt-2" style="font-size: 12px">
                                    <label class="form-label">Buscar cliente</label><button data-bs-toggle="modal"
                                        data-bs-target="#modalNuevoCliente"
                                        class="badge badge-xs light badge-success float-end">Añadir <i
                                            class="fa fa-plus"></i></button>
                                    <input type="text" class="form-control  form-control-sm"
                                        style="border-style: solid;border-color:rgb(14, 178, 79);
                            border-width: 1px;border-radius:15px; display: inline-block;height:20px"
                                        placeholder="Buscar cliente (Opcional)" wire:model.debounce.1000ms='user'>

                                    <span class="badge light badge-info float-center" wire:loading
                                        wire:target='user'>Cargando...
                                    </span>
                                </div>

                                @foreach ($usuarios as $item)
                                    <a href="#" class="m-1" style="font-size: 12px;"
                                        wire:click="seleccionarcliente('{{ $item->id }}','{{ $item->name }}')">
                                        @if ($item->id == $cliente)
                                            <strong class="badge  badge-outline-success ">{{ $item->name }} <i
                                                    class="fa fa-check"></i>
                                            </strong>
                                        @else
                                            <span class="badge light badge-dark"><small>{{ $item->name . ' ' }}</small>
                                                <i class="fa fa-user-plus"></i>
                                            </span>
                                        @endif

                                    </a>
                                @endforeach
                            </div>
                            <button type="button" wire:click="crear" class="btn btn-primary btn-sm m-0 p-1">Abrir
                                Venta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card-col>
    @isset($cuenta)
        <x-card-col tamano="3">
            @php
                $esCumple = false;
            @endphp

            @isset($cuenta->cliente)
                @php
                    if (isset($cuenta->cliente->nacimiento)) {
                        $fechaNacimiento = Carbon\Carbon::parse($cuenta->cliente->nacimiento);
                        $hoy = Carbon\Carbon::now();

                        // Verificar si el mes y el día coinciden
                        if ($fechaNacimiento->month == $hoy->month && $fechaNacimiento->day == $hoy->day) {
                            $esCumple = true;
                        }
                    }

                @endphp
            @endisset
            <div {!! $esCumple == true
                ? ' style="background-image:url(' .
                    asset('images/cumple.gif') .
                    ');background-position: top;background-repeat: no-repeat;"'
                : '' !!}>
                <center style="font-size: 10px" class="mt-0">Creado por: {{ Str::limit($cuenta->usuario->name, 25) }}
                </center>
                <h4 class="d-flex justify-content-between align-items-center m-0">
                    <strong class="m-3 text-muted" style="font-size: 12px">Venta #{{ $cuenta->id }}</strong> <br>
                    <a class="" href="#" wire:loading>
                        <small class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></small>
                    </a>
                    @if ($cuenta->cliente)
                        <a href="#" data-bs-toggle="modal" data-bs-target="#planesusuario"><span
                                class="badge  badge-success p-1"
                                style="font-size: 11px">{{ Str::before($cuenta->cliente->name, ' ') }} <i
                                    class="fa fa-user"></i></span></a>
                    @else
                        @if ($cuenta->usuario_manual)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                                    class="badge  badge-warning p-1"
                                    style="font-size: 11px">{{ $cuenta->usuario_manual }}</span></a>
                        @else
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                                    class="badge  badge-danger p-1" style="font-size: 11px">Sin usuario</span></a>
                        @endif
                    @endif


                    <span class="badge badge-info badge-xs badge-pill p-1">{{ $itemsCuenta }} items</span>
                </h4>
                @if ($esCumple)
                    <div class="d-flex">
                        <span class="badge badge-outline-primary p-1 mx-auto" style="font-size: 11px"><i
                                class="fa fa-gift"></i> Hoy
                            es
                            cumpleaños de
                            {{ Str::before($cuenta->cliente->name, ' ') }}</span>
                    </div>
                @endif
                <hr class=" p-0 mt-1 mb-1">
                @if (count($listacuenta) > 0)
                    <ul class="list-group" style="overflow-y: auto;max-height:300px;overflow-x: hidden" wire:loading.remove
                        wire:target="seleccionar">
                        @foreach ($listacuenta as $item)
                            <li class="list-group-item d-flex justify-content-between lh-condensed m-0 p-1">
                                <div class="">
                                    <div class="row">
                                        <div class="col"><a href="#" data-toggle="modal"
                                                data-target="#modalAdicionales{{ $item['id'] }}"
                                                wire:click="mostraradicionales('{{ $item['id'] }}')">
                                                <h6 class="my-0" style="font-size:12px"><small
                                                        class="@isset($productoapuntado) {{ $item['nombre'] == $productoapuntado->nombre ? 'text-success' : '' }} @endisset">{{ Str::limit($item['nombre'], 40, '...') }}</small>
                                                </h6>
                                            </a>
                                        </div>
                                    </div>

                                    <small class="text-muted">
                                        <div class="row">
                                            <div class="col">
                                                <a href="#" wire:click="adicionar('{{ $item['id'] }}')"><span
                                                        class="badge badge-xs light badge-success"><i
                                                            class="fa fa-plus"></i></span></a>
                                                <strong style="font-size:10px">{{ $item['cantidad'] }}
                                                    {{ $item['medicion'] }}(s)</strong>
                                                <a href="#" wire:click="eliminaruno('{{ $item['id'] }}')"> <span
                                                        class="badge badge-xs light badge-danger"><i
                                                            class="fa fa-minus"></i></span></a>
                                                <a href="#" class="btn btn-danger shadow btn-xs p-0  px-1"
                                                    wire:click="eliminarproducto('{{ $item['id'] }}')"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </div>

                                    </small>
                                </div>
                                <div>
                                    <div class="row">
                                        <strong class="" style="font-size:14px">{{ $item['subtotal'] }} Bs</strong>

                                    </div>
                                    <div class="row">
                                        <div x-data="{ open: false }">
                                            <button @click="open = ! open"
                                                class="badge badge-xs light badge-info">Añadir</button>

                                            <div x-show="open" @click.outside="open = false">

                                                <div class="input-group input-primary" style="width: 50px; height: 30px;">
                                                    <input type="text" wire:model.lazy="cantidadespecifica"
                                                        class="form-control" placeholder=""
                                                        style="height: 30px; width: 30px; font-size: 12px; padding: 2px;"
                                                        value="{{ $item['cantidad'] }}">
                                                    <a href="#" class="input-group-text m-0 p-0"
                                                        wire:click="adicionarvarios('{{ $item['id'] }}')"
                                                        style="min-width:0px;height: 30px; width: 20px !important; font-size: 8px; padding: 2px;"><i
                                                            class="fa fa-save"></i></a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>



                                </div>


                            </li>
                            <div wire:ignore.self class="modal fade" id="modalAdicionales{{ $item['id'] }}"
                                tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            @isset($productoapuntado)
                                                <div class="row">
                                                    <div class="col-lg-6 col-xl-3">
                                                        <div class="list-group mb-4 p-0">
                                                            @for ($i = 1; $i <= count($array); $i++)
                                                                <a href="#"
                                                                    class="list-group-item list-group-item-action p-1 d-block
                                                                        {{ $itemseleccionado == $i ? 'active' : '' }}"
                                                                    wire:click="seleccionaritem('{{ $i }}')"
                                                                    style="border-style: solid;border-color:rgb(14, 178, 79);
                                                            border-width: 1px;border-radius:15px;font-size:14px;line-height:12px">
                                                                    <strong class="mx-auto">Item #
                                                                        {{ $i }}</strong> <span wire:loading
                                                                        wire:target="seleccionaritem('{{ $i }}')"
                                                                        class="spinner-border spinner-border-sm ml-2 text-primary"
                                                                        role="status" aria-hidden="true"></span><br>
                                                                    @foreach ($array[$i] as $posicion => $adic)
                                                                        @foreach ($adic as $nombre => $precio)
                                                                            <small class=""
                                                                                style="font-size: 10px;line-height:10px">-{{ $nombre }}{{ $precio == 0 ? '' : ' : ' . $precio . ' Bs' }}</small><br>
                                                                        @endforeach
                                                                    @endforeach
                                                                </a>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-xl-8">

                                                        @isset($itemseleccionado)
                                                            @if ($adicionales->count() > 0)
                                                                <div class="d-flex justify-content-between">
                                                                    <span style="font-size: 12px">Adicionales para
                                                                        {{ $item['nombre'] }}:</span>
                                                                    <a href="#" wire:click="eliminarItem()"
                                                                        class="badge badge-outline-danger"><span>Eliminar item <i
                                                                                class="fa fa-trash"></i></span></a>
                                                                </div>
                                                                <div class="list-group mb-4 p-1 ">
                                                                    @foreach ($adicionales as $item)
                                                                        <a href="#"
                                                                            wire:click="agregaradicional('{{ $item->id }}', '{{ $itemseleccionado }}')"
                                                                            class="list-group-item list-group-item-action p-1"
                                                                            style="border-style: solid;border-color:rgba(14, 178, 80, 0.354);
                                                            border-width: 1px;font-size:12px"><strong
                                                                                class="letra14">{{ $item->contable ? $item->cantidad : '' }}</strong>
                                                                            {{ $item->nombre }}
                                                                            <span class="letra10">({{ $item->precio }} Bs)</span>
                                                                            <span wire:loading
                                                                                wire:target="agregaradicional('{{ $item->id }}', '{{ $itemseleccionado }}')"
                                                                                class="spinner-border spinner-border-sm"
                                                                                role="status" aria-hidden="true"></span>
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span>No existe adicionales para este producto</span>
                                                            @endif
                                                        @endisset

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group" style="font-size: 12px">
                                                            <span>Agregar observacion para
                                                                <strong>{{ $productoapuntado->nombre }}</strong></span>
                                                            <textarea id="my-textarea" wire:model.defer="observacion" class="form-control"
                                                                style="border-style: solid;border-color:rgb(14, 178, 79);
                                                            border-width: 1px;border-radius:15px;"
                                                                name="" rows="5">{{ $this->observacion }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <br>
                                                        <button class="btn btn-success btn-sm"
                                                            wire:click="guardarObservacion({{ $productoapuntado->id }})">Guardar</button>
                                                    </div>

                                                </div>
                                            @endisset
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </ul>
                @else
                    <center style="font-size: 12px"><strong> Aún no hay productos añadidos</strong></center>
                @endif
                <hr class=" p-0 mt-1 mb-1">
                @if (count($listacuenta) > 0)
                    <center style="font-size: 10px" class="mt-0">Resumen
                    </center>
                    <ul class="list-group p-0 m-0">
                        <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                            <small>Subtotal</small>
                            <strong>{{ $subtotal }} Bs</strong>

                        </li>
                        <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                            <small>Descuento Productos</small>
                            <strong>{{ $descuentoProductos }} Bs</strong>

                        </li>
                        <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                            <small>Descuento Manual</small>

                            <div x-data="{ open: false }">
                                <button @click="open = ! open"
                                    class="badge badge-xs light badge-secondary">Editar</button>

                                <div x-show="open" @click.outside="open = false">
                                    <div class="input-group input-primary" style="width: 50px; height: 30px;">
                                        <input type="text" wire:model.lazy="descuento" class="form-control"
                                            placeholder=""
                                            style="height: 30px; width: 30px; font-size: 12px; padding: 2px;">
                                        <a href="#" class="input-group-text m-0 p-0" wire:click="editardescuento"
                                            style="min-width:0px;height: 30px; width: 20px !important; font-size: 8px; padding: 2px;"><i
                                                class="fa fa-save"></i></a>
                                    </div>


                                </div>
                            </div>
                            <strong>{{ $cuenta->descuento }} Bs</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:15px">
                            <span>Total a pagar</span>
                            <strong>{{ $subtotal - $cuenta->descuento - $descuentoProductos }}
                                Bs</strong>

                        </li>
                    </ul>
                    <center class="text-muted" style="font-size:10px">Puntos en esta venta: {{ $cuenta->puntos }}
                    </center>
                @endif





                @if ($subtotal != 0)
                    <div class="row m-2">
                        <button class="btn btn-xs btn-warning" data-bs-toggle="modal" data-bs-target="#basicModal"
                            wire:click="actualizarSaldo">Cobrar
                            Cuenta</button>

                    </div>
                    <div wire:ignore.self class="modal fade" id="basicModal">
                        <div class="modal-dialog" role="document">
                            @if ($modoImpresion)
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Ajustes de Impresion
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label col-form-label-sm">Observacion</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control form-control-sm" wire:model="observacionRecibo"></textarea>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label col-form-label-sm">Fecha
                                                Personalizada</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control form-control-sm"
                                                    wire:model="fechaRecibo">
                                            </div>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" class="form-check-input"
                                                wire:model="checkMetodoPagoPersonalizado">
                                            <label class="form-check-label" for="check1">Agregar Metodo de Pago</label>
                                        </div>
                                        @if ($checkMetodoPagoPersonalizado)
                                            <div class="mb-3 row">
                                                <label class="col-sm-3 col-form-label col-form-label-sm">Metodo</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm"
                                                        wire:model="metodoRecibo">
                                                </div>


                                            </div>
                                        @endif
                                        <div class="form-check mb-2">
                                            <input type="checkbox" class="form-check-input"
                                                wire:model="checkClientePersonalizado">
                                            <label class="form-check-label" for="check1">Agregar Cliente
                                                Personalizado</label>
                                        </div>

                                        @if ($checkClientePersonalizado)
                                            <div class="mb-3 row">
                                                <label class="col-sm-3 col-form-label col-form-label-sm">Cliente</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm"
                                                        wire:model="clienteRecibo">
                                                </div>
                                                @isset($cuenta->cliente)
                                                    <div class="alert alert-warning alert-dismissible fade show text-sm">

                                                        <strong>Atencion!</strong> Se reemplazara el nombre de
                                                        <strong>{{ $cuenta->cliente->name }} </strong> por lo añadido a este
                                                        campo
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="btn-close">
                                                        </button>
                                                    </div>
                                                @endisset

                                            </div>
                                        @endif
                                        <div class="form-check mb-2">
                                            <input type="checkbox" class="form-check-input"
                                                wire:model="checkTelefonoPersonalizado">
                                            <label class="form-check-label" for="check1">Agregar Telefono</label>
                                        </div>
                                        @if ($checkTelefonoPersonalizado)
                                            <div class="mb-3 row">
                                                <label class="col-sm-3 col-form-label col-form-label-sm">Telefono</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm"
                                                        wire:model="telefonoRecibo">
                                                </div>

                                                <div class="alert alert-info alert-dismissible fade show text-sm">

                                                    <strong>Importante!</strong>Este numero no se imprimira, solo se
                                                    guardara en
                                                    prospectos dentro del sistema.
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="btn-close">
                                                    </button>
                                                </div>


                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-warning btn-sm" wire:click="modalResumen">Atras</button>
                                        <button class="btn btn-info btn-sm" wire:click="descargarPDF">Descargar PDF <i
                                                class="fa fa-file"></i></button>
                                        <button class="btn btn-success btn-sm" wire:click="imprimir">Imprimir <i
                                                class="fa fa-print"></i></button>

                                    </div>
                                </div>
                            @else
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detalle de la cuenta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body m-1 p-0">
                                        @isset($cuenta->cliente)
                                            <center class="text-muted" style="font-size: 12px">Cliente:
                                                {{ $cuenta->cliente->name }} </center>
                                        @endisset
                                        <ul class="list-group" style="border: 2px solid #20c996b3;">
                                            <li
                                                class="list-group-item d-flex justify-content-between lh-condensed m-0 py-0">
                                                <div>
                                                    <h6 class="my-0">Subtotal</h6>

                                                </div>
                                                <span class="text-muted">{{ $subtotal }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between lh-condensed m-0 py-0">
                                                <div>
                                                    <h6 class="my-0">Descuento por Productos</h6>

                                                </div>
                                                <span class="text-muted">{{ $descuentoProductos }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between lh-condensed m-0 py-0">
                                                <div>
                                                    <h6 class="my-0">Descuento</h6>

                                                </div>
                                                <span class="text-muted">{{ $cuenta->descuento }}</span>
                                            </li>


                                            <li class="list-group-item d-flex justify-content-between active m-0 py-0">
                                                <span>Total (BS)</span>
                                                <strong>{{ $subtotal - $cuenta->descuento - $descuentoProductos }}</strong>
                                            </li>
                                        </ul>


                                        <center class="text-muted">Puntos en esta venta: {{ $cuenta->puntos }}</center>

                                        <span class="badge light badge-warning">Tipo de pago: </span>
                                        @isset($tipocobro)
                                            <span class="badge light badge-success">{{ $tipocobro }}</span>
                                        @endisset

                                        @push('scripts')
                                            <script>
                                                Livewire.on('cambiarCheck', cambiar => {
                                                    document.getElementById("check-efectivo").checked = true;

                                                })
                                            </script>
                                        @endpush
                                        <div class="content">
                                            <div class="m-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gridRadios"
                                                        id="check-efectivo" wire:model="tipocobro" value="efectivo">
                                                    <label class="form-check-label">
                                                        Efectivo
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input " type="radio" name="gridRadios"
                                                        wire:model="tipocobro" value="tarjeta">
                                                    <label class="form-check-label">
                                                        Tarjeta
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input "
                                                        {{ $deshabilitarBancos ? 'disabled' : '' }} type="radio"
                                                        name="gridRadios" wire:model="tipocobro" value="banco-sol">
                                                    <label class="form-check-label">
                                                        Banco Sol
                                                    </label>
                                                </div>
                                                {{-- <div class="form-check disabled">
                                        <input class="form-check-input " {{ $deshabilitarBancos ? 'disabled' : '' }}
                                            type="radio" name="gridRadios" wire:model="tipocobro"
                                            value="banco-bisa">
                                        <label class="form-check-label">
                                            Banco Bisa
                                        </label>
                                    </div> --}}
                                                <div class="form-check disabled">
                                                    <input class="form-check-input " type="radio" name="gridRadios"
                                                        wire:model="tipocobro" value="banco-bnb">
                                                    <label class="form-check-label">
                                                        Banco BNB
                                                    </label>
                                                </div>
                                            </div>

                                            @isset($cuenta->cliente->name)
                                                <div class="form-check disabled">
                                                    <input class="form-check-input" type="checkbox" name="checkbox"
                                                        wire:model="saldo" wire:change="actualizarSaldo">
                                                    <label class="form-check-label">
                                                        A deuda
                                                    </label>
                                                </div>
                                                @if ($saldo == true)
                                                    <div class="row d-flex">
                                                        <div id="saldo" class="col-4 mx-auto">
                                                            <div class="input-group input-group-sm mb-3 input-success">
                                                                <span class="input-group-text">Bs</span>
                                                                <input type="number"
                                                                    wire:model.debounce.500ms="saldoRestante"
                                                                    wire:change="controlarEntrante" class="form-control">
                                                            </div>

                                                        </div>
                                                        <div id="saldo" class="col-4 mx-auto">
                                                            <div class="input-group input-group-sm mb-3 input-info">
                                                                <span class="input-group-text">Saldo</span>
                                                                <input type="number" wire:model.debounce.500ms="valorSaldo"
                                                                    wire:change="controlarSaldo" class="form-control">
                                                            </div>

                                                        </div>

                                                    </div>

                                                    @if ($saldoRestante == 0)
                                                        <div class="alert alert-success notification p-0 my-0">
                                                            <p class="notificaiton-title mb-2"><strong>Correcto!</strong> Se
                                                                agregara
                                                                el total de
                                                                <strong>{{ $subtotal - $cuenta->descuento - $descuentoProductos }}
                                                                    Bs</strong>
                                                                al saldo por cobrar de
                                                                <strong>{{ $cuenta->cliente->name }}!</strong>
                                                            </p>

                                                        </div>
                                                    @elseif($saldoRestante != 0)
                                                        <div class="alert alert-warning notification p-0 my-0">
                                                            <p class="notificaiton-title mb-2"><strong>Atencion!</strong> Estas
                                                                agregando <strong>{{ $valorSaldo }} Bs</strong> al saldo de
                                                                <strong>{{ $cuenta->cliente->name }}</strong> y cobrando
                                                                <strong>{{ $saldoRestante }} Bs</strong> por el metodo
                                                                <strong>"{{ $tipocobro }}"</strong>
                                                            </p>

                                                        </div>
                                                    @endif
                                                @endif
                                            @endisset


                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @if (!$cuenta->pagado)
                                            <button type="button" class="btn btn-primary p-2 my-0 "
                                                wire:loading.attr="disabled" wire:click="cobrar"
                                                {{ $tipocobro ? '' : 'disabled' }}>Marcar como pagado</button>
                                        @else
                                            <button type="button" class="btn btn-success p-2 my-0 "
                                                wire:loading.attr="disabled" wire:click="cerrarVenta"
                                                data-bs-dismiss="modal">Cerrar venta</button>
                                        @endif


                                        <button type="button" class="btn btn-warning btn-sm p-2 my-0"
                                            {{ $cuenta->pagado ? '' : 'disabled' }}
                                            wire:click="modalImpresion"><span>Imprimir</span></button>
                                        <button wire:loading.remove wire:target='imprimir' type="button"
                                            class="btn btn-info btn-sm p-2 my-0"
                                            wire:click="imprimirCocina"><span>Cocina</span></button>
                                        <button wire:loading wire:target='imprimir' type="button" disabled
                                            class="btn btn-warning btn-sm"
                                            wire:click="imprimir"><span>Espere...</span></button>



                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif
            </div>

        </x-card-col>

        <x-card-col tamano="6">
            <input type="search" wire:model.debounce.750ms="search" style="border: 2px solid #20c996b3;height:30px"
                class="form-control mt-2" placeholder="Busca productos y categorias">
            <ul class=" m-1" role="tablist"
                style="white-space: nowrap; overflow-x: auto; overflow-y: hidden; overflow-x: hidden; display: flex; flex-wrap: nowrap; -webkit-overflow-scrolling: touch;">
                <div class="nav-container" style="overflow-x: auto; white-space: nowrap;">
                    @foreach ($subcategorias as $subcategoria)
                        <a href="#"
                            class="nav-item m-0 p-0 mb-2 letra14 {{ $subcategoriaSeleccionada == $subcategoria->id ? 'selected bg-primary' : '' }}"
                            role="presentation"
                            style="border-style: solid;border-color:rgb(14, 178, 79);
                            border-width: 1px;border-radius:15px; display: inline-block;"
                            wire:click="seleccionarSubcategoria({{ $subcategoria->id }})"
                            data-subcategoria-id="{{ $subcategoria->id }}">
                            <div href="#my-posts" data-bs-toggle="tab" class="nav-link m-0 p-1 "
                                style="font-size: 10px; white-space: nowrap;" aria-selected="true" role="tab">
                                {{ Str::limit($subcategoria->nombre, 15) }}
                            </div>
                        </a>
                    @endforeach
                </div>




            </ul>

            <div class="row mt-2 p-2" style="max-height: 500px; overflow-y: auto;overflow-x: hidden;">
                <center style="font-size: 12px">Productos encontrados: {{ $productos->count() }}</center>
                @foreach ($productos as $item)
                    @php
                        $total = 0;
                    @endphp

                    @foreach ($item->sucursale->where('id', $cuenta->sucursale_id) as $relacion)
                        @isset($relacion)
                            @php
                                $total += $relacion->pivot->cantidad;
                            @endphp
                        @endisset
                    @endforeach


                    <div class="card-body product-grid-card col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-3 m-0 p-1"
                        style="border-style: solid;{{ $total == 0 && $item->contable == true ? 'border-color:red;' : 'border-color:rgb(14, 178, 79);' }}
                        border-width: 2px;border-radius:15px">
                        <div class="new-arrival-product m-0 p-0">
                            <div wire:click="adicionar('{{ $item->id }}')" class="new-arrivals-img-contnent mx-auto"
                                style="width: 100px;height:100px">
                                <img class="img-fluid rounded" src="{{ asset($item->pathAttachment()) }}"
                                    alt="">
                            </div>
                            <div wire:click="adicionar('{{ $item->id }}')"
                                class="new-arrival-content text-center mt-1">
                                <h4 class="p-0 m-0" style="font-size:10px">{{ Str::limit($item->nombre, 40) }}
                                    @if ($item->puntos != 0 && $item->puntos != null)
                                        <small class="">({{ $item->puntos }}pts)</small>
                                    @endif
                                </h4>
                                @if ($item->descuento != 0)
                                    <del class="discount" style="font-size:11px">{{ $item->precio }} Bs</del>
                                    <span class="price" style="font-size:13px">{{ $item->descuento }} Bs</span>
                                @else
                                    <span class="price" style="font-size:13px">{{ $item->precio }} Bs</span>
                                @endif

                            </div>
                            <div class="row ">
                                <div class="col-6">
                                    <a href="#">
                                        @switch($item->prioridad)
                                            @case(1)
                                                <span wire:click="cambiarPrioridad('{{ $item->id }}','2')"
                                                    class="badge badge-xs light badge-dark"><i class="fa fa-high"></i>
                                                    |</span>
                                            @break

                                            @case(2)
                                                <span wire:click="cambiarPrioridad('{{ $item->id }}','3')"
                                                    class="badge badge-xs light badge-info">||</span>
                                            @break

                                            @case(3)
                                                <span wire:click="cambiarPrioridad('{{ $item->id }}','1')"
                                                    class="badge badge-xs light badge-success">|||</span>
                                            @break

                                            @default
                                        @endswitch
                                    </a>


                                </div>
                                <div class="col-6">
                                    @if ($item->contable == true)
                                        <div class="float-end">
                                            <span
                                                class="badge {{ $total == 0 ? 'badge-danger text-white' : 'badge-outline-dark text-dark' }} badge-xs badge-pill p-1  letra14"
                                                style="line-height: 8px">{{ $total }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="float-end">
                                            <span class="badge badge-primary badge-xs badge-pill p-1"
                                                style="line-height: 8px"><i class="fa fa-check"></i>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </x-card-col>

    @endisset



    <div wire:ignore.self class="modal fade" id="planesusuario">
        <div class="modal-dialog">
            <div class="modal-content">
                @isset($cuenta->cliente)
                    <div class="modal-header">
                        <h5 class="modal-title col-5">Saldo : {{ $cuenta->cliente->saldo }} Bs </h5>
                        <a href="#" wire:click="verSaldo"
                            class="btn btn-xxs btn-warning col-5">{{ $verVistaSaldo ? 'Ver Planes' : 'Descontar Saldo' }}</a>
                        <button type="button" class="btn-close col-2" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($verVistaSaldo)
                            <div class="card m-0 ">
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label class="col-lg-4 col-form-label" for="validationCustom01">Monto (Bs)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-6">
                                            <input type="number"
                                                class="form-control @error($montoSaldo) is-invalid @enderror"
                                                step="any" wire:model.lazy="montoSaldo"
                                                placeholder="Ingrese el monto que paga el cliente" required="">

                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-lg-4 col-form-label" for="validationCustom01">Metodo
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-6">
                                            <select name="" id="" wire:model="tipoSaldo"
                                                class="form-control @error($tipoSaldo) is-invalid @enderror">
                                                <option value="">Seleccione uno</option>
                                                <option value="efectivo">Efectivo</option>
                                                <option value="tarjeta">Tarjeta</option>
                                                {{-- <option value="banco-sol">Banco Sol</option> --}}
                                                <option value="banco-bnb">Banco BNB</option>
                                                {{-- <option value="banco-mercantil">Banco Mercantil</option> --}}
                                            </select>

                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-lg-4 col-form-label" for="validationCustom02">Detalle <span
                                                class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control @error($detalleSaldo) is-invalid @enderror" cols="30" rows="10"
                                                wire:model.lazy="detalleSaldo"></textarea>

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-success" wire:click="registrarSaldo">Registrar</button>
                                    </div>
                                </div>
                                <h4>Registro de saldos:{{ $cuenta->cliente->saldos->count() }}</h4>
                                <ul class="list-group mt-3 " style="overflow-y: auto;max-height:300px;overflow-x: hidden">
                                    @foreach ($cuenta->cliente->saldos->sortByDesc('created_at') as $saldo)
                                        @if ($saldo->es_deuda)
                                            <li class="list-group-item d-flex justify-content-between active">
                                                <div class="text-white">
                                                    <h6 class="my-0 text-white">DEUDA POR COMPRA</h6>
                                                    <small>{{ $saldo->created_at->format('d-M') }}<a href="#"
                                                            class="badge badge-warning badge-xs">{{ App\Helpers\WhatsappAPIHelper::timeago($saldo->created_at) }}</a></small>
                                                </div>
                                                <span class="text-white">{{ $saldo->monto }} Bs <a href="#"
                                                        wire:click="imprimirSaldo({{ $saldo->id }})"
                                                        class="badge badge-warning"><i class="fa fa-print"></i></a>
                                                    @if ($saldo->anulado)
                                                        <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                            class="badge badge-danger"><i class="fa fa-close"></i>
                                                            Anulado</a>
                                                    @else
                                                        <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                            class="badge badge-warning"><i class="fa fa-check"></i>
                                                            Activo</a>
                                                    @endif
                                                </span>

                                            </li>
                                        @else
                                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                                <div>
                                                    <h6 class="my-0">PAGO A FAVOR DEL CLIENTE</h6>
                                                    <small class="text-muted">{{ $saldo->created_at->format('d-M') }}
                                                        <a href="#"
                                                            class="badge badge-warning badge-xs">{{ App\Helpers\WhatsappAPIHelper::timeago($saldo->created_at) }}</a></small><br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($saldo->detalle, 25) }}</small>
                                                </div>
                                                <span class="text-muted">{{ $saldo->monto }} Bs <a href="#"
                                                        wire:click="imprimirSaldo({{ $saldo->id }})"
                                                        class="badge badge-primary"><i class="fa fa-print"></i></a>
                                                    @if ($saldo->anulado)
                                                        <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                            class="badge badge-danger"><i class="fa fa-close"></i>
                                                            Anulado</a>
                                                    @else
                                                        <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                            class="badge badge-warning"><i class="fa fa-check"></i>
                                                            Activo</a>
                                                    @endif
                                                </span>
                                            </li>
                                        @endif
                                    @endforeach

                                </ul>
                                <ul class="list-group mt-3 ">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Saldo </span>
                                        <strong>{{ $cuenta->cliente->saldo }} Bs</strong>
                                    </li>
                                </ul>



                            </div>
                        @else
                            @foreach ($cuenta->cliente->planes->groupBy('nombre') as $nombre => $item)
                                <div class="card m-0 ">
                                    <div class="card-body px-4 py-3 py-lg-2">
                                        <div class="row align-items-center">
                                            <div class="col-xl-3 col-xxl-12 col-lg-12 my-2">
                                                <strong class="mb-0 fs-14">{{ Str::limit($nombre, 20, '') }}</strong>
                                                <a href="{{ route('detalleplan', [$cuenta->cliente->id, $item[0]->id]) }}"
                                                    class="badge badge-info">Ir <i class="fa fa-arrow-right"></i></a>
                                            </div>
                                            <div class="col-xl-7 col-xxl-12 col-lg-12">
                                                <div class="row align-items-center">
                                                    <div class="col-xl-4 col-md-4 col-sm-4 my-2">
                                                        <div class="media align-items-center style-2">

                                                            <div class="media-body ml-1">
                                                                <p class="mb-0 fs-12">Ultima fecha</p>
                                                                @php
                                                                    $ultimaFecha = $item->sortBy('pivot.start')->last();
                                                                @endphp
                                                                <h5 class="mb-0   fs-22">
                                                                    @if ($ultimaFecha)
                                                                        {{ date_format(date_create($ultimaFecha->pivot->start), 'd-M') }}
                                                                    @else
                                                                        N/A
                                                                    @endif

                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4 col-sm-4 my-2">
                                                        <div class="media align-items-center style-2">
                                                            <span class="me-3 fa fa-shield text-warning">

                                                            </span>
                                                            <div class="media-body ml-1">
                                                                <p class="mb-0 fs-12">Permisos</p>
                                                                <h4 class="mb-0 font-w600  fs-22">
                                                                    {{ $item->where('pivot.estado', 'permiso')->count() }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4 col-sm-4 my-2">
                                                        <div class="media align-items-center style-2">
                                                            <span class="me-3 fa fa-check text-success">

                                                            </span>
                                                            <div class="media-body ml-1">
                                                                <p class="mb-0 fs-12">Restantes</p>
                                                                <h4 class="mb-0 font-w600 fs-22">
                                                                    {{ $item->where('pivot.start', '>', date('Y-m-d'))->where('pivot.estado', 'pendiente')->count() }}
                                                                    <svg class="ml-2" width="12" height="6"
                                                                        viewBox="0 0 12 6" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M0 6L6 2.62268e-07L12 6" fill="#13B497">
                                                                        </path>
                                                                    </svg>
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif



                    </div>


                @endisset
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalNuevoCliente">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Creando nuevo cliente <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <x-input-create-custom-function funcion="crearCliente" boton="Crear Cliente" :lista="[
                        'Nombre' => ['name', 'text'],
                        'Correo' => ['email', 'email'],
                        'Nacimiento' => ['cumpleano', 'date', '(opcional)'],
                        'Direccion' => ['direccion', 'text', '(opcional)'],
                        'Contraseña' => ['password', 'password'],
                    ]">

                    </x-input-create-custom-function>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalClientes">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Enlazar usuario a esta cuenta
                    <span class="badge light badge-info" wire:loading wire:target='user'> Cargando...</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-6 mt-2">
                            <label for="">Buscar usuario</label>
                            <input type="text" class="form-control  form-control-sm" placeholder="Buscar Usuario"
                                wire:model.debounce.1000ms='user'>
                            <br>
                            @foreach ($usuarios as $item)
                                <a href="#" class=""
                                    wire:click="cambiarClienteACuenta({{ $item->id }})"><small>{{ $item->name }}
                                    </small><span class="badge light badge-primary"> <i
                                            class="fa fa-plus"></i></span></a>
                                <hr>
                            @endforeach
                        </div>

                        <div class="mb-3 col-md-6 mt-2">
                            <label for=""> Agregar Referencia</label>
                            <input type="text" class="form-control  form-control-sm"
                                placeholder="Agregar una referencia auxiliar" wire:model.lazy="userManual">
                            <button class="btn btn-success btn-xs" wire:click="addUsuarioManual">Confirmar</button>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>


</div>
@push('css')
    <style>
        /* Ancho del scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            /* Puedes ajustar este valor */
            height: 5px;
            /* Si también deseas estilizar el scrollbar horizontal */
        }

        /* Fondo del scrollbar */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color de fondo del track */
        }

        /* Estilo del thumb (la parte que se mueve) */
        ::-webkit-scrollbar-thumb {
            background: #20c997;
            /* Color del thumb */
            border-radius: 5px;
            /* Bordes redondeados */
        }

        /* Cambiar el color del thumb al pasar el mouse */
        ::-webkit-scrollbar-thumb:hover {
            background: #20c997;
            /* Color al pasar el mouse */
        }


        @media (max-width: 768px) {
            ::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }

            ::-webkit-scrollbar-thumb {
                background: #20c997;
                border-radius: 5px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #20c997;
            }

            html {
                scrollbar-width: thin;
                scrollbar-color: #20c997 #20c997;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        console.log('aquio');

        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('scrollToSubcategoria', (subcategoriaId) => {
                setTimeout(() => {
                    console.log(subcategoriaId);
                    const element = document.querySelector(
                        `[data-subcategoria-id="${subcategoriaId}"]`);
                    if (element) {
                        element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });


                    }
                }, 100);

            });


        });
    </script>
@endpush
