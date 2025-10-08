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
        <center style="font-size: 10px" class="mt-0">Creado por: {{ Str::words($cuenta->usuario->name, 1, '') }}
            <a class="" href="#" wire:loading>
                <small class="spinner-border spinner-border-sm letra10" role="status" aria-hidden="true"></small>
            </a>
        </center>
        <h4 class="d-flex justify-content-between align-items-center m-0">
            {{-- <strong class="m-3 text-muted" style="font-size: 12px"><i class="fa fa-send"></i>
                #{{ $cuenta->id }}</strong> <br> --}}


            @if ($cuenta->cliente)
                <a href="#" data-bs-toggle="modal" data-bs-target="#planesusuario"><span
                        class="badge  badge-dark light letra10 p-1">{{ Str::before($cuenta->cliente->name, ' ') }} <i
                            class="fa fa-user"></i></span></a>
            @else
                @if ($cuenta->usuario_manual)
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                            class="badge letra10 badge-info light p-1">{{ $cuenta->usuario_manual }}</span></a>
                @else
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                            class="p-1 letra10">Sin usuario</span></a>
                @endif
            @endif


            <span class="text-muted letra10">{{ $itemsCuenta }} items</span>
        </h4>
        @isset($cuenta->cliente)
            @if ($cuenta->cliente->saldo > 0)
                <small class="m-0 px-2 bg-warning text-white"><i class="flaticon-091-warning"></i>
                    DEUDA DEL CLIENTE : {{ $cuenta->cliente->saldo }} Bs</small>
            @elseif($cuenta->cliente->saldo < 0)
                <small class="m-0 px-2 bg-primary text-white"><i class="flaticon-008-check"></i>
                    A FAVOR DEL CLIENTE : {{ abs((int) $cuenta->cliente->saldo) }} Bs</small>
            @endif
        @endisset


        @if ($esCumple)
            <div class="d-flex">
                <span class="badge badge-outline-primary p-1 mx-auto" style="font-size: 11px"><i class="fa fa-gift"></i>
                    Hoy
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
                                        @if (!$cuenta->pagado)
                                            <a href="#" wire:click="adicionar('{{ $item['id'] }}')"><span
                                                    class="badge badge-xs light badge-success"><i
                                                        class="fa fa-plus"></i></span></a>
                                        @endif

                                        <strong style="font-size:10px">{{ $item['cantidad'] }}
                                            {{ $item['medicion'] }}(s)</strong>
                                        @if (!$cuenta->pagado)
                                            <a href="#" wire:click="eliminaruno('{{ $item['id'] }}')"> <span
                                                    class="badge badge-xs light badge-danger"><i
                                                        class="fa fa-minus"></i></span></a>
                                            <a href="#" class="btn btn-danger shadow btn-xs p-0  px-1"
                                                wire:click="eliminarproducto('{{ $item['id'] }}')"><i
                                                    class="fa fa-trash"></i></a>
                                        @endif

                                    </div>
                                </div>

                            </small>
                        </div>
                        <div>
                            <div class="d-flex align-items-center" style="line-height: 10px; white-space: nowrap;">
                                <strong style="font-size:14px; white-space: nowrap;">{{ $item['subtotal'] }} Bs</strong>
                                @if($item['tiene_descuentos'])
                                    <i class="fa fa-info-circle text-info ml-1" 
                                       onclick="mostrarDetalleDescuentos('{{ $item['detalle'] }}', '{{ $item['nombre'] }}', {{ $item['precio_original'] }}, {{ $item['subtotal'] }}, {{ $item['cantidad'] }})"
                                       style="cursor: pointer; font-size: 16px; flex-shrink: 0;" 
                                       title="Ver detalles de descuentos"></i>
                                @endif
                            </div>
                            @if (!$cuenta->pagado)
                                <div class="row mt-0">
                                    <div x-data="{ open: false }">
                                        <span style="cursor: pointer;line-height: 10px;font-size: 9px;" class=" mt-0" @click="open = ! open; $nextTick(() => $refs.cantidadInput.focus())"
                                            class="">Agregar <i class="fa fa-plus"></i></span>

                                        <div x-show="open" @click.outside="open = false">

                                            <div class="input-group input-dark" style="width: 50px; height: 30px;">
                                                <input type="text" wire:model.lazy="cantidadespecifica"
                                                    class="form-control" placeholder="" x-ref="cantidadInput"
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
                            @endif


                        </div>


                    </li>
                    <div wire:ignore.self class="modal fade" id="modalAdicionales{{ $item['id'] }}" tabindex="-1"
                        role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                @if(isset($totalAdicionales) && $totalAdicionales > 0)
                <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                    <small>Total Adicionales</small>
                    <span class="text-success">+ {{ $totalAdicionales }} Bs</span>
                </li>
                @endif
                @if(isset($descuentoConvenio) && $descuentoConvenio > 0)
                <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                    <small>Descuento Convenio</small>
                    <span class="text-danger">- {{ $descuentoConvenio }} Bs</span>
                </li>
                @endif
                @if(isset($descuentoProductos) && $descuentoProductos > 0)
                <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                    <small>Descuento Productos</small>
                    <span class="text-danger">- {{ $descuentoProductos }} Bs</span>
                </li>
                @endif
                <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                    <small>Descuento Manual</small>
                    @if (!$cuenta->pagado)
                        <div x-data="{ open: false }">
                            <span style="cursor: pointer;" @click="open = ! open; $nextTick(() => $refs.descuentoInput.focus())"
                                class=""><i class="fa fa-edit"></i> Editar</span>

                            <div x-show="open" @click.outside="open = false">
                                <div class="input-group input-dark" style="width: 50px; height: 30px;">
                                    <input type="text" wire:model.lazy="descuento" class="form-control"
                                        placeholder="" x-ref="descuentoInput"
                                        style="height: 30px; width: 30px; font-size: 12px; padding: 2px;">
                                    <a href="#" class="input-group-text m-0 p-0" wire:click="editardescuento"
                                        style="min-width:0px;height: 30px; width: 20px !important; font-size: 8px; padding: 2px;"><i
                                            class="fa fa-save"></i></a>
                                </div>


                            </div>
                        </div>
                    @endif
                    @if(isset($cuenta) && $cuenta->descuento > 0)
                    <span class="text-danger">- {{ $cuenta->descuento }} Bs</span>
                    @endif
                </li>

                <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:15px">
                    @if (!$cuenta->pagado)
                        <span>Total a pagar</span>
                    @else
                        <span>Total</span>
                    @endif

                    <strong>{{ $subtotalConDescuento }}
                        Bs</strong>

                </li>
            </ul>
            <center class="text-muted" style="font-size:10px">Puntos en esta venta: {{ $cuenta->puntos }}
            </center>
        @endif

        @if ($subtotal != 0)
            <div class="row m-2">
                @if ($cuenta->pagado)
                    <button class="btn btn-xs btn-dark light" data-bs-toggle="modal"
                        data-bs-target="#basicModal">Finalizar venta</button>
                @else
                    <button class="btn btn-xs btn-warning" wire:click="actualizarCuenta" data-bs-toggle="modal"
                        data-bs-target="#basicModal">Cobrar
                        Cuenta</button>
                @endif


            </div>
            @include('livewire.admin.ventas.includes-pos.modal-cobranza')
        @endif
    </div>


</x-card-col>
