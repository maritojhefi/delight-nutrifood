<div class="row">
    <x-card-col4>
        <div class="mb-2">
            <h4 class="card-intro-title p-2 mt-2">Ventas Abiertas</h4>

            <div class="">

                @foreach ($ventas as $item)
                    <div class="alert alert-{{ $item->productos->count() > 0 ? 'success' : 'danger' }}@isset($cuenta) {{ $item->id == $cuenta->id ? 'solid alert-alt' : '' }} @endisset alert-dismissible fade show"
                        style="padding: 10px">
                        @if ($item->productos->count() == 0)
                            <button type="button" class="btn-close" wire:click="eliminar('{{ $item->id }}')">
                            </button>
                        @endif

                        <a href="#" wire:click="seleccionar('{{ $item->id }}')">
                            <div wire:loading wire:target="seleccionar({{ $item->id }})"
                                class="spinner-grow spinner-grow-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <small> #{{ $item->id }}</small>

                            @isset($item->cliente)
                                <span
                                    class="badge badge-xs light badge-dark">{{ Str::limit($item->cliente->name, 15) }}</span>
                            @endisset
                            @isset($item->usuario_manual)
                                <span
                                    class="badge badge-xs light badge-dark">{{ Str::limit($item->usuario_manual, 15) }}</span>
                            @endisset
                            <strong
                                class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset ">{{ $item->total }}Bs
                            </strong>
                        </a>
                    </div>
                @endforeach

                <!-- checkbox -->



                <div x-data="{ count: 0 }">
                    <div x-data="{ open: false, count: 'abrir' }">
                        <button class="btn light btn-xs btn-outline-warning" @click="open = ! open, count='cerrar'">
                            <template x-if="open">
                                <div>CERRAR</div>
                            </template>
                            <template x-if="!open">
                                <div>ABRIR NUEVA CUENTA</div>
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
                                <div class="mb-3 col-md-12 mt-2">
                                    <label class="form-label">Buscar cliente</label><button data-bs-toggle="modal"
                                        data-bs-target="#modalNuevoCliente"
                                        class="badge badge-xs light badge-success float-end">Añadir <i
                                            class="fa fa-plus"></i></button>
                                    <input type="text" class="form-control  form-control-sm"
                                        placeholder="Buscar cliente (Opcional)" wire:model.debounce.1000ms='user'>

                                    <span class="badge light badge-info float-center" wire:loading
                                        wire:target='user'>Cargando...
                                    </span>
                                </div>

                                @foreach ($usuarios as $item)
                                    <a href="#" class="m-2"
                                        wire:click="seleccionarcliente('{{ $item->id }}','{{ $item->name }}')">
                                        @if ($item->id == $cliente)
                                            <strong class="text-success">{{ $item->name }}</strong>
                                            <strong class="badge light badge-success "><i class="fa fa-check"></i>
                                            </strong>
                                        @else
                                            <small>{{ $item->name }}</small>
                                            <span class="badge light badge-dark"><i class="fa fa-user-plus"></i>
                                            </span>
                                        @endif

                                    </a>
                                @endforeach
                            </div>
                            <button type="button" wire:click="crear" class="btn btn-primary btn-sm">Crear
                                Cuenta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card-col4>
    @isset($cuenta)

        <x-card-col4>
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <small class="m-3 text-muted">Venta #{{ $cuenta->id }}</small> <br>
                <a class="btn btn-primary btn-xxs" href="#" wire:loading>
                    <small class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></small>
                </a>


                @if ($cuenta->cliente)
                    <a href="#" data-bs-toggle="modal" data-bs-target="#planesusuario"><span
                            class="badge light badge-success">{{ Str::limit($cuenta->cliente->name, 15, '...') }}</span></a>
                @else
                    @if ($cuenta->usuario_manual)
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                                class="badge light badge-warning">{{ $cuenta->usuario_manual }}</span></a>
                    @else
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                                class="badge light badge-danger">Sin usuario</span></a>
                    @endif
                @endif


                <span class="badge badge-primary badge-pill">{{ $itemsCuenta }}</span>
            </h4>
            <small>Por: {{ Str::limit($cuenta->usuario->name, 25) }}</small>
            @isset($cuenta->cliente)
                @php
                    $time = strtotime($cuenta->cliente->nacimiento);
                @endphp
                @if (date('m-d') == date('m-d', $time))
                    <div class="alert alert-danger alert-sm light alert-alt " style="padding: 10px">
                    
                        <a href="#" >
                            
                            <small> #6</small>
                            <span class="badge badge-xs light badge-dark">Jacque</span>
                            <strong class=" text-white  ">0.00Bs
                            </strong>
                        </a>
                    </div>
                @endif
            @endisset

            <ul class="list-group mb-3 " style="overflow-y: auto;max-height:300px;overflow-x: hidden" wire:loading.remove
                wire:target="seleccionar"
                @isset($cuenta->cliente) 
                @php 
                $time = strtotime($cuenta->cliente->nacimiento);
                
                @endphp 
                @if (date('m-d') == date('m-d', $time)) style="background-image:
                     url('{{ asset('images/cumple.gif') }}')" 
                @endif
                @endisset>


                @foreach ($listacuenta as $item)
                    <li class="list-group-item d-flex justify-content-between lh-condensed" style="padding: 15px">
                        <div>
                            <div class="row">
                                <div class="col"><a href="#" data-toggle="modal"
                                        data-target="#modalAdicionales{{ $item['id'] }}"
                                        wire:click="mostraradicionales('{{ $item['id'] }}')">
                                        <h6 class="my-0"><small
                                                class="@isset($productoapuntado) {{ $item['nombre'] == $productoapuntado->nombre ? 'text-success' : '' }} @endisset">{{ Str::limit($item['nombre'], 30, '...') }}</small>
                                        </h6>
                                    </a></div>
                            </div>

                            <small class="text-muted">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" wire:click="adicionar('{{ $item['id'] }}')"><span
                                                class="badge badge-xs light badge-success"><i
                                                    class="fa fa-plus"></i></span></a>
                                        <strong>{{ $item['cantidad'] }}</strong> {{ $item['medicion'] }}(s)
                                        <a href="#" wire:click="eliminaruno('{{ $item['id'] }}')"> <span
                                                class="badge badge-xs light badge-danger"><i
                                                    class="fa fa-minus"></i></span></a>
                                        <a href="#" class="btn btn-danger shadow btn-xs sharp"
                                            wire:click="eliminarproducto('{{ $item['id'] }}')"><i
                                                class="fa fa-trash"></i></a>
                                    </div>
                                </div>

                            </small>
                        </div>
                        <div>
                            <div class="row">
                                <strong class="">{{ $item['subtotal'] }} Bs</strong>

                            </div>
                            <div class="row">
                                <div x-data="{ open: false }">
                                    <button @click="open = ! open" class="badge badge-xs light badge-info">Añadir</button>

                                    <div x-show="open" @click.outside="open = false">
                                        <div class="input-group">
                                            <button class="btn btn-primary" type="button"
                                                wire:click="adicionarvarios('{{ $item['id'] }}')"
                                                style="padding: 3px;height:30px;width:30px"><i
                                                    class="fa fa-plus"></i></button>
                                            <input type="number" class="form-control"
                                                wire:model.lazy="cantidadespecifica"
                                                style="padding: 3px;height:30px;width:50px"
                                                value="{{ $item['cantidad'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>


                    </li>
                    <div wire:ignore.self class="modal fade" id="modalAdicionales{{ $item['id'] }}" tabindex="-1"
                        role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Adicionales para {{ $item['nombre'] }}</h4>
                                </div>
                                <div class="modal-body">
                                    @isset($productoapuntado)
                                        <div class="row mb-3">

                                            <div x-data="{ open: false }">
                                                <button @click="open = ! open" class="badge light badge-warning"
                                                    wire:click="mostraradicionales({{ $productoapuntado->id }})">Ver
                                                    Detalle</button>

                                                <div x-show="open" @click.outside="open = false">

                                                    <div class="table-responsive">
                                                        <table class="table table-responsive-sm" style="padding: 10px">

                                                            <tbody style="padding: 10px">
                                                                @foreach ($array as $lista)
                                                                    <tr style="padding: 10px">
                                                                        <th style="padding: 10px">#{{ $loop->iteration }}
                                                                        </th>
                                                                        @foreach ($lista as $posicion => $adic)
                                                                            <td style="padding: 10px">
                                                                                @foreach ($adic as $nombre => $precio)
                                                                                    <small
                                                                                        class="badge badge-xs light badge-warning">{{ $nombre }}
                                                                                        <label
                                                                                            class="text-dark">{{ $precio }}Bs</label></small>
                                                                                @endforeach
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach


                                                            </tbody>
                                                        </table>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-xl-3">
                                                <div class="list-group mb-4 " id="list-tab" role="tablist">

                                                    @foreach ($productoapuntado->ventas->where('id', $cuenta->id) as $agregados)
                                                        @for ($i = 1; $i <= $agregados->pivot->cantidad; $i++)
                                                            <a href="#"
                                                                class="list-group-item list-group-item-action
                                                        {{ $itemseleccionado == $i ? 'active' : '' }}"
                                                                wire:click="seleccionaritem('{{ $i }}')">
                                                                Item # {{ $i }}<span wire:loading
                                                                    wire:target="seleccionaritem('{{ $i }}')"
                                                                    class="spinner-border spinner-border-sm ml-2 text-primary"
                                                                    role="status" aria-hidden="true"></span>
                                                            </a>
                                                        @endfor
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-xl-8">
                                                <div class="list-group mb-4 " id="list-tab" role="tablist">
                                                    @isset($itemseleccionado)
                                                        @foreach ($adicionales as $item)
                                                            <a href="#"
                                                                wire:click="agregaradicional('{{ $item->id }}', '{{ $itemseleccionado }}')"
                                                                class="list-group-item list-group-item-action">{{ $item->nombre }}
                                                                ({{ $item->precio }} Bs)
                                                                <span wire:loading
                                                                    wire:target="agregaradicional('{{ $item->id }}', '{{ $itemseleccionado }}')"
                                                                    class="spinner-border spinner-border-sm" role="status"
                                                                    aria-hidden="true"></span>
                                                            </a>
                                                        @endforeach
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <span>Agregar observacion</span>
                                                    <textarea id="my-textarea" wire:model.defer="observacion" class="form-control" name="" rows="5">{{ $this->observacion }}</textarea>
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
            <ul class="list-group mb-3 border border-primary">
                <li class="list-group-item d-flex justify-content-between">
                    <small>Subtotal</small>
                    <strong>{{ $subtotal }} Bs</strong>

                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <small>Descuento Productos</small>
                    <strong>{{ $descuentoProductos }} Bs</strong>

                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <small>Descuento Manual</small>

                    <div x-data="{ open: false }">
                        <button @click="open = ! open" class="badge badge-xs light badge-secondary">Editar</button>

                        <div x-show="open" @click.outside="open = false">
                            <div class="col-md-2">
                                <input type="number" class="form-control" wire:model.lazy="descuento"
                                    style="padding: 3px;height:30px;width:80px;border-style: solid;border-color:rgb(14, 178, 79);
                                border-width: 1px;"
                                    value="{{ $item['cantidad'] }}">
                            </div>
                            <button class="btn btn-xxs btn-warning" wire:click="editardescuento"><i
                                    class="fa fa-check"></i>Guardar</button>
                        </div>
                    </div>
                    <strong>{{ $cuenta->descuento }} Bs</strong>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    <span>Total a pagar</span>
                    <strong>{{ $subtotal - $cuenta->descuento - $descuentoProductos }} Bs/<small>{{ $cuenta->puntos }}
                            pts</small></strong>

                </li>
            </ul>




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

                                                <strong>Importante!</strong>Este numero no se imprimira, solo se guardara en
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
                                <div class="modal-body">
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                            <div>
                                                <h6 class="my-0">Subtotal</h6>

                                            </div>
                                            <span class="text-muted">{{ $subtotal }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                            <div>
                                                <h6 class="my-0">Descuento por Productos</h6>

                                            </div>
                                            <span class="text-muted">{{ $descuentoProductos }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                            <div>
                                                <h6 class="my-0">Descuento</h6>

                                            </div>
                                            <span class="text-muted">{{ $cuenta->descuento }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                            <div>
                                                <h6 class="my-0">Puntos</h6>
                                                @isset($cuenta->cliente)
                                                    <small class="text-muted">Para :
                                                        {{ $cuenta->cliente->name }} </small>
                                                @endisset
                                            </div>
                                            <span class="text-muted">{{ $cuenta->puntos }}</span>
                                        </li>

                                        <li class="list-group-item d-flex justify-content-between active">
                                            <span>Total (BS)</span>
                                            <strong>{{ $subtotal - $cuenta->descuento - $descuentoProductos }}</strong>
                                        </li>
                                    </ul>
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
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="check-efectivo" wire:model="tipocobro" value="efectivo">
                                            <label class="form-check-label">
                                                Efectivo
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input " {{ $deshabilitarBancos ? 'disabled' : '' }}
                                                type="radio" name="gridRadios" wire:model="tipocobro" value="tarjeta">
                                            <label class="form-check-label">
                                                Tarjeta
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input " {{ $deshabilitarBancos ? 'disabled' : '' }}
                                                type="radio" name="gridRadios" wire:model="tipocobro"
                                                value="banco-sol">
                                            <label class="form-check-label">
                                                Banco Sol
                                            </label>
                                        </div>
                                        <div class="form-check disabled">
                                            <input class="form-check-input " {{ $deshabilitarBancos ? 'disabled' : '' }}
                                                type="radio" name="gridRadios" wire:model="tipocobro"
                                                value="banco-bisa">
                                            <label class="form-check-label">
                                                Banco Bisa
                                            </label>
                                        </div>
                                        <div class="form-check disabled">
                                            <input class="form-check-input " {{ $deshabilitarBancos ? 'disabled' : '' }}
                                                type="radio" name="gridRadios" wire:model="tipocobro"
                                                value="banco-mercantil">
                                            <label class="form-check-label">
                                                Banco Mercantil
                                            </label>
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
                                                <div class="row">
                                                    <div id="saldo" class="col-4">
                                                        <div class="input-group input-group-sm mb-3 input-success">
                                                            <span class="input-group-text">Bs</span>
                                                            <input type="number" wire:model.debounce.500ms="saldoRestante"
                                                                wire:change="controlarEntrante" class="form-control">
                                                        </div>

                                                    </div>
                                                    <div id="saldo" class="col-4">
                                                        <div class="input-group input-group-sm mb-3 input-info">
                                                            <span class="input-group-text">Saldo</span>
                                                            <input type="number" wire:model.debounce.500ms="valorSaldo"
                                                                wire:change="controlarSaldo" class="form-control">
                                                        </div>

                                                    </div>

                                                </div>

                                                @if ($saldoRestante == 0)
                                                    <div class="alert alert-success notification">
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
                                                    <div class="alert alert-warning notification">
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
                                    <button type="button" class="btn btn-warning btn-sm"
                                        wire:click="modalImpresion"><span>Imprimir</span></button>
                                    <button wire:loading.remove wire:target='imprimir' type="button"
                                        class="btn btn-info btn-sm"
                                        wire:click="imprimirCocina"><span>Cocina</span></button>
                                    <button wire:loading wire:target='imprimir' type="button" disabled
                                        class="btn btn-warning btn-sm"
                                        wire:click="imprimir"><span>Espere...</span></button>


                                    <button type="button" class="btn btn-primary" wire:click="cobrar"
                                        {{ $tipocobro ? '' : 'disabled' }} data-bs-dismiss="modal">Confirmar y cerrar
                                        venta</button>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif


        </x-card-col4>

        <x-card-col4>
            <div class="basic-list-group mt-2 mb-2">
                <ul class="list-group">
                    <li class="list-group-item active  "><input type="search" wire:model.debounce.750ms="search"
                            class="form-control" placeholder="Busca Productos"></li>
                </ul>
                <ul class="list-group" style="overflow-y: auto;max-height:350px;overflow-x: hidden">

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



                        <a wire:key="{{ $loop->index }}" href="#"
                            {{ $total == 0 && $item->contable == true ? 'disabled' : ' ' }}>
                            <li class="list-group-item {{ $total == 0 && $item->contable == true ? '' : ' border border-primary' }}"
                                wire:target="adicionar({{ $item->id }})" wire:loading.class="border-success"
                                style="padding: 10px">
                                @if ($total == 0 && $item->contable == true)
                                    <del class=" text-muted"><small>{{ Str::limit($item->nombre, 40) }}</small> </del>
                                @else
                                    <div class="row" wire:click="adicionar('{{ $item->id }}')">
                                        <div class="col-3"><img src="{{ asset($item->pathAttachment()) }}"
                                                alt="" class="me-3 rounded" height="40"></div>
                                        <div class="col-9"><small>{{ Str::limit($item->nombre, 40) }}
                                            </small><span class="spinner-border spinner-border-sm text-primary ml-2"
                                                wire:loading wire:target="adicionar({{ $item->id }})" role="status"
                                                aria-hidden="true"></span></div>
                                    </div>
                                @endif
                                <small>
                                    @if ($item->contable == true)
                                        @if ($total == 0)
                                            <span class="badge badge-xs light badge-danger mb-2">Agotado</span>
                                        @else
                                            <span wire:click="adicionar('{{ $item->id }}')"
                                                class="badge badge-xs light badge-warning mb-2">Stock
                                                :{{ $total }}</span>
                                        @endif
                                    @endif
                                </small>
                                <div class="row">

                                    <div class="col-6">
                                        @if ($item->descuento != 0)
                                            <span wire:click="adicionar('{{ $item->id }}')"
                                                class="badge badge-xs  badge-primary">{{ $item->descuento }}
                                                Bs</span>
                                            <del wire:click="adicionar('{{ $item->id }}')"
                                                class="badge badge-xs  badge-danger">{{ $item->precio }} Bs</del>
                                        @else
                                            <span wire:click="adicionar('{{ $item->id }}')"
                                                class="badge badge-xs  badge-warning">{{ $item->precio }}
                                                Bs</span>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        @if ($item->puntos != 0 && $item->puntos != null)
                                            <small class="">{{ $item->puntos }}pts</small>
                                        @endif
                                        @switch($item->prioridad)
                                            @case(1)
                                                <span wire:click="cambiarPrioridad('{{ $item->id }}','2')"
                                                    class="badge badge-xs light badge-dark"><i class="fa fa-high"></i> |</span>
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

                                    </div>
                                </div>
                            </li>
                        </a>
                    @endforeach
                </ul>
            </div>

        </x-card-col4>

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
                                                <option value="banco-sol">Banco Sol</option>
                                                <option value="banco-bisa">Banco Bisa</option>
                                                <option value="banco-mercantil">Banco Mercantil</option>
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
                                                    <small class="text-muted">{{ $saldo->created_at->format('d-M') }} <a
                                                            href="#"
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
                                                <strong class="mb-0 fs-14">{{ Str::limit($nombre, 20, '') }}</strong> <a
                                                    href="{{ route('detalleplan', [$cuenta->cliente->id, $item[0]->id]) }}"
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Enlazar usuario a esta cuenta
                    <span class="badge light badge-info" wire:loading wire:target='user'> Cargando...
                    </span>
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
                            <label for=""> Añadir Manualmente</label>
                            <input type="text" class="form-control  form-control-sm" placeholder="Agregar manual"
                                wire:model.lazy="userManual">
                            <button class="btn btn-success btn-xs" wire:click="addUsuarioManual">Confirmar</button>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>


</div>
