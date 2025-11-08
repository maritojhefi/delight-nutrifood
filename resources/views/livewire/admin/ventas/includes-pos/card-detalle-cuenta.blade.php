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
        <center style="font-size: 10px" class="mt-0">Creado por:
            <strong>{{ Str::words($cuenta->usuario->name, 1, '') }}</strong>
            <span>{{ GlobalHelper::timeago($cuenta->created_at) }}</span>
            <a class="" href="#" wire:loading>
                <small class="spinner-border spinner-border-sm letra10" role="status" aria-hidden="true"></small>
            </a>
        </center>
        <h4 class="d-flex justify-content-between align-items-center m-0">
            {{-- <strong class="m-3 text-muted" style="font-size: 12px"><i class="fa fa-send"></i>
                #{{ $cuenta->id }}</strong> <br> --}}


            @if ($cuenta->cliente)
                <a href="#" data-bs-toggle="modal" data-bs-target="#planesusuario"><span
                        class=" letra12 p-1 text-secondary" style="cursor: pointer;text-decoration: underline;"><i
                            class="fa fa-user"></i> {{ Str::before($cuenta->cliente->name, ' ') }} </span></a>
            @else
                @if ($cuenta->usuario_manual)
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                            class=" letra12 p-1 text-info" style="cursor: pointer;text-decoration: underline;"><i
                                class="fa fa-edit"></i> {{ $cuenta->usuario_manual }} </span></a>
                @else
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalClientes"><span
                            class="p-1 letra10 nav-link" style="text-decoration: underline;">Sin usuario</span></a>
                @endif
            @endif

            <a href="#" onclick="cambiarTipoEntrega('{{ $cuenta->tipo_entrega }}')"><span
                    class="letra12 text-black" style="cursor: pointer;text-decoration: underline;">
                    @switch($cuenta->tipo_entrega)
                        @case('mesa')
                            <i class="fa fa-table"></i> Para: <strong>Mesa</strong>
                        @break

                        @case('delivery')
                            <i class="fa fa-truck"></i> Para: <strong>Delivery</strong>
                        @break

                        @default
                            <i class="fa fa-bolt"></i> <strong>Venta Rapida</strong>
                        @break
                    @endswitch
                </span></a>
            <span class="text-muted letra10">{{ $itemsCuenta }} items</span>
        </h4>
        <div class="row text-center my-1">
            @isset($cuenta->cliente)
                @if ($cuenta->cliente->saldo > 0)
                    <small class="m-0 px-2 bg-warning text-white w-100"><i class="flaticon-091-warning"></i>
                        DEUDA DEL CLIENTE : {{ $cuenta->cliente->saldo }} Bs</small>
                @elseif($cuenta->cliente->saldo < 0)
                    <small class="m-0 px-2 bg-primary text-white w-100"><i class="flaticon-008-check"></i>
                        A FAVOR DEL CLIENTE : {{ abs((int) $cuenta->cliente->saldo) }} Bs</small>
                @endif
            @endisset
        </div>



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
                                <div class="col">
                                    <a href="#" wire:click="verDetalleItemPOS('{{ $item['pivot_id'] }}')">
                                        <h6 class="my-0" style="font-size:12px">
                                            <small 
                                                style="text-decoration: underline; cursor: pointer;" 
                                                class="@isset($productoapuntado) {{ $item['nombre'] == $productoapuntado->nombre ? 'text-success' : '' }} @endisset"
                                            >
                                                {{ Str::limit($item['nombre'], 40, '...') }} <i class="fa fa-info-circle text-info ml-1"></i>
                                            </small>
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
                                <strong style="font-size:14px; white-space: nowrap;">{{ $item['subtotal'] }}
                                    Bs</strong>
                                @if ($item['tiene_descuentos'])
                                    <i class="fa fa-info-circle text-info ml-1"
                                        onclick="mostrarDetalleDescuentos('{{ $item['detalle'] }}', '{{ $item['nombre'] }}', {{ $item['precio_original'] }}, {{ $item['subtotal'] }}, {{ $item['cantidad'] }})"
                                        style="cursor: pointer; font-size: 16px; flex-shrink: 0;"
                                        title="Ver detalles de descuentos"></i>
                                @endif
                            </div>
                            @if (!$cuenta->pagado)
                                <div class="row mt-0">
                                    <div x-data="{ open: false }">
                                        <span style="cursor: pointer;line-height: 10px;font-size: 9px;" class=" mt-0"
                                            @click="open = ! open; $nextTick(() => $refs.cantidadInput.focus())"
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
                @if (isset($totalAdicionales) && $totalAdicionales > 0)
                    <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                        <small>Total Adicionales</small>
                        <span class="text-success">+ {{ $totalAdicionales }} Bs</span>
                    </li>
                @endif
                @if (isset($descuentoConvenio) && $descuentoConvenio > 0)
                    <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                        <small>Descuento Convenio</small>
                        <span class="text-danger">- {{ $descuentoConvenio }} Bs</span>
                    </li>
                @endif
                @if (isset($descuentoProductos) && $descuentoProductos > 0)
                    <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                        <small>Descuento Productos</small>
                        <span class="text-danger">- {{ $descuentoProductos }} Bs</span>
                    </li>
                @endif
                <li class="list-group-item d-flex justify-content-between p-0 m-0" style="font-size:12px">
                    <small>Descuento Manual</small>
                    @if (!$cuenta->pagado)
                        <div x-data="{ open: false }">
                            <span style="cursor: pointer;"
                                @click="open = ! open; $nextTick(() => $refs.descuentoInput.focus())"
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
                    @if (isset($cuenta) && $cuenta->descuento > 0)
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

    <script>
        let mesasDisponiblesGlobal = [];
        let clientesDisponiblesGlobal = [];
        let debounceTimerCambio;
        let clienteSeleccionadoCambioId = null;
        let mesaSeleccionadaId = null;

        // Escuchar mesas disponibles
        window.addEventListener('mesasDisponibles', event => {
            mesasDisponiblesGlobal = event.detail.mesas;
            if (typeof actualizarListaMesas === 'function') {
                actualizarListaMesas();
            }
        });

        // Escuchar clientes encontrados
        window.addEventListener('clientesEncontrados', event => {
            clientesDisponiblesGlobal = event.detail.clientes;
            if (typeof actualizarListaClientesCambio === 'function') {
                actualizarListaClientesCambio();
            }
        });

        window.cambiarTipoEntrega = function(tipoActual = 'recoger') {
            clienteSeleccionadoCambioId = null;
            mesaSeleccionadaId = null;
            clientesDisponiblesGlobal = [];
            mesasDisponiblesGlobal = [];

            // Si el tipo actual es 'reserva', lo tratamos como 'recoger' para el autoseleccionado
            const tipoParaSeleccionar = tipoActual === 'reserva' ? 'recoger' : tipoActual;

            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                allowOutsideClick: false,
                title: '<i class="fa fa-exchange"></i> Cambiar Tipo de Entrega',
                html: `
                <div class="text-start">
                    <label class="form-label fw-bold mb-3">Selecciona el tipo de entrega:</label>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioMesa" value="mesa" ${tipoParaSeleccionar === 'mesa' ? 'checked' : ''}>
                            <label class="btn btn-outline-primary w-100 py-3" for="radioMesa">
                                <i class="fa fa-table fa-2x d-block mb-2"></i>
                                <strong>Mesa</strong>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioDelivery" value="delivery" ${tipoParaSeleccionar === 'delivery' ? 'checked' : ''}>
                            <label class="btn btn-outline-secondary w-100 py-3" for="radioDelivery">
                                <i class="fa fa-truck fa-2x d-block mb-2"></i>
                                <strong>Delivery</strong>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioRecoger" value="recoger" ${tipoParaSeleccionar === 'recoger' ? 'checked' : ''}>
                            <label class="btn btn-outline-success w-100 py-3" for="radioRecoger">
                                <i class="fa fa-bolt fa-2x d-block mb-2"></i>
                                <strong>Venta Rápida</strong>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioReservaCambio" value="reserva">
                            <label class="btn btn-outline-info w-100 py-3" for="radioReservaCambio">
                                <i class="flaticon-088-time fa-2x d-block mb-2"></i>
                                <strong>Reserva</strong>
                            </label>
                        </div>
                    </div>

                    <!-- Contenedor dinámico para campos adicionales -->
                    <div id="camposAdicionales" class="border-top pt-3 mt-2">
                        <div class="text-muted text-center py-2">
                            <i class="fa fa-check-circle"></i> Venta Rápida no requiere datos adicionales
                        </div>
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Cambiar Tipo de Entrega',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                width: '600px',
                didOpen: () => {
                    const confirmBtn = document.querySelector('.swal2-confirm');
                    const camposAdicionales = document.getElementById('camposAdicionales');

                    const radioMesa = document.getElementById('radioMesa');
                    const radioDelivery = document.getElementById('radioDelivery');
                    const radioRecoger = document.getElementById('radioRecoger');
                    const radioReserva = document.getElementById('radioReservaCambio');

                    function actualizarCamposAdicionales() {
                        const tipoSeleccionado = document.querySelector(
                            'input[name="tipoEntregaCambio"]:checked').value;
                        clienteSeleccionadoCambioId = null;
                        mesaSeleccionadaId = null;

                        switch (tipoSeleccionado) {
                            case 'mesa':
                                camposAdicionales.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0">Buscar Cliente (Opcional):</label>
                                    <button type="button" id="limpiarClienteMesa" class="btn btn-sm btn-outline-secondary" style="display: none;">
                                        <i class="fa fa-times"></i> Limpiar
                                    </button>
                                </div>
                                <input type="text" id="busquedaClienteMesa" class="form-control mb-2" 
                                       placeholder="Escribe el nombre o email del cliente... (Opcional)"
                                       autocomplete="off">
                                <div id="resultadosClientesMesa" class="border rounded mb-3" style="max-height: 150px; overflow-y: auto; background-color: white;">
                                    <div class="text-muted text-center py-2">Opcional: Escribe para buscar clientes...</div>
                                </div>
                                
                                <label class="form-label fw-bold">Selecciona la Mesa:</label>
                                <div id="listaMesas" class="border rounded p-2" style="max-height: 300px; overflow-y: auto; background-color: white;">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                        <div>Cargando mesas...</div>
                                    </div>
                                </div>
                            `;
                                confirmBtn.disabled = true;
                                @this.call('obtenerMesasDisponibles');

                                setTimeout(() => {
                                    const inputBusquedaMesa = document.getElementById(
                                        'busquedaClienteMesa');
                                    const btnLimpiarCliente = document.getElementById(
                                        'limpiarClienteMesa');
                                    const resultadosDiv = document.getElementById(
                                        'resultadosClientesMesa');

                                    if (inputBusquedaMesa) {
                                        inputBusquedaMesa.addEventListener('input', function() {
                                            this.style.borderColor = '';
                                            this.style.backgroundColor = '';
                                            clienteSeleccionadoCambioId = null;
                                            if (btnLimpiarCliente) btnLimpiarCliente.style
                                                .display = 'none';
                                            buscarClientesCambio(this.value);
                                        });
                                    }

                                    if (btnLimpiarCliente) {
                                        btnLimpiarCliente.addEventListener('click', function() {
                                            clienteSeleccionadoCambioId = null;
                                            if (inputBusquedaMesa) {
                                                inputBusquedaMesa.value = '';
                                                inputBusquedaMesa.style.borderColor = '';
                                                inputBusquedaMesa.style.backgroundColor =
                                                '';
                                            }
                                            if (resultadosDiv) {
                                                resultadosDiv.innerHTML =
                                                    '<div class="text-muted text-center py-2">Opcional: Escribe para buscar clientes...</div>';
                                            }
                                            this.style.display = 'none';
                                        });
                                    }
                                }, 100);
                                break;

                            case 'delivery':
                                camposAdicionales.innerHTML = `
                                <label class="form-label fw-bold">Buscar Cliente (Obligatorio):</label>
                                <input type="text" id="busquedaClienteCambio" class="form-control mb-2" 
                                       placeholder="Escribe el nombre o email del cliente..."
                                       autocomplete="off">
                                <div id="resultadosClientesCambio" class="border rounded" style="max-height: 200px; overflow-y: auto; background-color: white;">
                                    <div class="text-muted text-center py-2">Escribe para buscar clientes...</div>
                                </div>
                            `;
                                confirmBtn.disabled = true;

                                setTimeout(() => {
                                    const inputBusqueda = document.getElementById(
                                        'busquedaClienteCambio');
                                    if (inputBusqueda) {
                                        inputBusqueda.focus();
                                        inputBusqueda.addEventListener('input', function() {
                                            this.style.borderColor = '';
                                            this.style.backgroundColor = '';
                                            clienteSeleccionadoCambioId = null;
                                            confirmBtn.disabled = true;
                                            buscarClientesCambio(this.value);
                                        });
                                    }
                                }, 100);
                                break;

                            case 'reserva':
                                camposAdicionales.innerHTML = `
                                <label class="form-label fw-bold">Buscar Cliente (Obligatorio):</label>
                                <input type="text" id="busquedaClienteReservaCambio" class="form-control mb-3" 
                                       placeholder="Escribe el nombre o email del cliente..."
                                       autocomplete="off">
                                <div id="resultadosClientesReservaCambio" class="border rounded mb-3" style="max-height: 150px; overflow-y: auto; background-color: white;">
                                    <div class="text-muted text-center py-2">Escribe para buscar clientes...</div>
                                </div>
                                
                                <label class="form-label fw-bold">Tipo de Entrega (Obligatorio):</label>
                                <select id="tipoEntregaReserva" class="form-select mb-3">
                                    <option value="">Selecciona el tipo de entrega...</option>
                                    <option value="mesa">Mesa</option>
                                    <option value="recoger">Para Recoger</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                                
                                <div id="camposRequeridos"></div>
                                
                                <div class="border-top pt-3 mt-2">
                                    <label class="form-label fw-bold">Fecha de Reserva:</label>
                                    <div class="btn-group w-100 mb-3" role="group">
                                        <input type="radio" class="btn-check" name="fechaReservaCambio" id="radioHoyCambio" value="hoy" checked>
                                        <label class="btn btn-outline-primary" for="radioHoyCambio">
                                            <i class="fa fa-calendar-day"></i> Hoy
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="fechaReservaCambio" id="radioMananaCambio" value="manana">
                                        <label class="btn btn-outline-primary" for="radioMananaCambio">
                                            <i class="fa fa-calendar-plus"></i> Mañana
                                        </label>
                                    </div>
                                    
                                    <label class="form-label fw-bold">Hora de Reserva:</label>
                                    <select id="horaReservaCambio" class="form-select">
                                        <option value="">Selecciona una hora</option>
                                    </select>
                                </div>
                            `;
                                confirmBtn.disabled = true;

                                setTimeout(() => {
                                    const tipoEntregaSelect = document.getElementById(
                                        'tipoEntregaReserva');
                                    const camposRequeridos = document.getElementById(
                                        'camposRequeridos');
                                    const selectHora = document.getElementById('horaReservaCambio');
                                    const radioHoy = document.getElementById('radioHoyCambio');
                                    const radioManana = document.getElementById(
                                    'radioMananaCambio');
                                    const inputBusquedaClienteReserva = document.getElementById(
                                        'busquedaClienteReservaCambio');

                                    let tipoEntregaReservaSeleccionado = '';

                                    // Inicializar búsqueda de cliente
                                    if (inputBusquedaClienteReserva) {
                                        inputBusquedaClienteReserva.focus();
                                        inputBusquedaClienteReserva.addEventListener('input',
                                            function() {
                                                this.style.borderColor = '';
                                                this.style.backgroundColor = '';
                                                clienteSeleccionadoCambioId = null;
                                                confirmBtn.disabled = true;
                                                buscarClientesCambio(this.value);
                                            });
                                    }

                                    tipoEntregaSelect.addEventListener('change', function() {
                                        tipoEntregaReservaSeleccionado = this.value;
                                        mesaSeleccionadaId = null;

                                        switch (this.value) {
                                            case 'mesa':
                                                camposRequeridos.innerHTML = `
                                                <label class="form-label fw-bold">Selecciona la Mesa:</label>
                                                <div id="listaMesasReserva" class="border rounded p-2 mb-3" style="max-height: 250px; overflow-y: auto; background-color: white;">
                                                    <div class="text-center py-3">
                                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                                        <div>Cargando mesas...</div>
                                                    </div>
                                                </div>
                                            `;
                                                @this.call('obtenerMesasDisponibles');
                                                break;

                                            case 'delivery':
                                                camposRequeridos.innerHTML = `
                                                <div class="alert alert-info mb-3">
                                                    <i class="fa fa-info-circle"></i> Delivery - La entrega será enviada al cliente seleccionado.
                                                </div>
                                            `;
                                                break;

                                            case 'recoger':
                                                camposRequeridos.innerHTML = `
                                                <div class="alert alert-info mb-3">
                                                    <i class="fa fa-info-circle"></i> Reserva para recoger en local.
                                                </div>
                                            `;
                                                break;

                                            default:
                                                camposRequeridos.innerHTML = '';
                                        }

                                        verificarFormularioReservaCambio();
                                    });

                                    // Listener para actualizar lista de mesas cuando se carguen
                                    window.addEventListener('mesasDisponibles', function handler(
                                        event) {
                                        const listaMesas = document.getElementById(
                                            'listaMesasReserva');
                                        if (listaMesas && tipoEntregaReservaSeleccionado ===
                                            'mesa') {
                                            actualizarListaMesasReserva();
                                        }
                                    });

                                    window.actualizarListaMesasReserva = function() {
                                        const listaMesas = document.getElementById(
                                            'listaMesasReserva');
                                        if (!listaMesas) return;

                                        if (mesasDisponiblesGlobal.length === 0) {
                                            listaMesas.innerHTML =
                                                '<div class="text-muted text-center py-2">No hay mesas disponibles</div>';
                                            return;
                                        }

                                        listaMesas.innerHTML = '<div class="row g-2">' +
                                            mesasDisponiblesGlobal.map(mesa => `
                                            <div class="col-4">
                                                <div class="mesa-item-reserva card text-center p-2 ${mesa.ocupada && !mesa.es_actual ? 'mesa-ocupada-cambio' : ''} ${mesa.es_actual ? 'mesa-actual' : ''}" 
                                                     data-mesa-id="${mesa.id}"
                                                     style="cursor: ${mesa.ocupada && !mesa.es_actual ? 'not-allowed' : 'pointer'}; border: 2px solid ${mesa.es_actual ? '#28a745' : mesa.ocupada ? '#dc3545' : '#e9ecef'};">
                                                    <i class="fa fa-table fa-2x ${mesa.ocupada && !mesa.es_actual ? 'text-danger' : mesa.es_actual ? 'text-success' : 'text-primary'}"></i>
                                                    <div><strong>Mesa ${mesa.numero}</strong></div>
                                                    ${mesa.capacidad ? `<small class="text-muted">${mesa.capacidad} personas</small>` : ''}
                                                    ${mesa.es_actual ? '<div class="badge badge-success mt-1">Actual</div>' : ''}
                                                    ${mesa.ocupada && !mesa.es_actual ? '<div class="badge badge-danger mt-1">Ocupada</div>' : ''}
                                                </div>
                                            </div>
                                        `).join('') +
                                            '</div>';

                                        document.querySelectorAll('.mesa-item-reserva').forEach(
                                            item => {
                                                const mesaId = parseInt(item.getAttribute(
                                                    'data-mesa-id'));
                                                const mesa = mesasDisponiblesGlobal.find(
                                                    m => m
                                                    .id === mesaId);

                                                if (!mesa.ocupada || mesa.es_actual) {
                                                    item.addEventListener('click',
                                                    function() {
                                                        document.querySelectorAll(
                                                                '.mesa-item-reserva'
                                                                )
                                                            .forEach(m => {
                                                                m.style
                                                                    .borderColor =
                                                                    m
                                                                    .querySelector(
                                                                        '.badge-success'
                                                                    ) ?
                                                                    '#28a745' :
                                                                    '#e9ecef';
                                                                m.style
                                                                    .backgroundColor =
                                                                    '';
                                                            });

                                                        this.style.borderColor =
                                                            '#007bff';
                                                        this.style.backgroundColor =
                                                            '#e7f3ff';
                                                        mesaSeleccionadaId = mesaId;
                                                        verificarFormularioReservaCambio
                                                            ();
                                                    });

                                                    item.addEventListener('mouseover',
                                                        function() {
                                                            if (!mesa.ocupada || mesa
                                                                .es_actual) {
                                                                this.style.transform =
                                                                    'scale(1.05)';
                                                            }
                                                        });

                                                    item.addEventListener('mouseout',
                                                        function() {
                                                            this.style.transform =
                                                                'scale(1)';
                                                        });
                                                }
                                            });
                                    }

                                    function actualizarOpcionesHoraCambio() {
                                        const fechaSeleccionada = document.querySelector(
                                            'input[name="fechaReservaCambio"]:checked').value;
                                        selectHora.innerHTML =
                                            '<option value="">Selecciona una hora</option>';

                                        const ahora = new Date();
                                        const horaActual = ahora.getHours() + ahora.getMinutes() /
                                            60;

                                        let opciones = [];

                                        if (fechaSeleccionada === 'hoy') {
                                            const horaInicio = Math.ceil(horaActual * 2) / 2;
                                            opciones = generarOpcionesHora(horaInicio, 22);
                                        } else {
                                            opciones = generarOpcionesHora(8, 22);
                                        }

                                        if (opciones.length === 0) {
                                            selectHora.innerHTML =
                                                '<option value="">No hay horarios disponibles</option>';
                                        } else {
                                            opciones.forEach(opcion => {
                                                const option = document.createElement(
                                                    'option');
                                                option.value = opcion.valor;
                                                option.textContent = opcion.display;
                                                selectHora.appendChild(option);
                                            });
                                        }

                                        verificarFormularioReservaCambio();
                                    }

                                    function verificarFormularioReservaCambio() {
                                        const tipoEntregaSelect = document.getElementById(
                                            'tipoEntregaReserva');
                                        const tieneHora = selectHora && selectHora.value !== '';
                                        const tieneTipoEntrega = tipoEntregaSelect &&
                                            tipoEntregaSelect
                                            .value !== '';
                                        const tieneCliente = clienteSeleccionadoCambioId !== null;

                                        // El cliente es SIEMPRE obligatorio para reservas
                                        if (!tieneCliente || !tieneTipoEntrega || !tieneHora) {
                                            confirmBtn.disabled = true;
                                            return;
                                        }

                                        let cumpleRequisitos = false;

                                        switch (tipoEntregaSelect.value) {
                                            case 'mesa':
                                                cumpleRequisitos = mesaSeleccionadaId !== null;
                                                break;
                                            case 'delivery':
                                            case 'recoger':
                                                cumpleRequisitos = true;
                                                break;
                                        }

                                        confirmBtn.disabled = !cumpleRequisitos;
                                    }

                                    if (radioHoy) radioHoy.addEventListener('change',
                                        actualizarOpcionesHoraCambio);
                                    if (radioManana) radioManana.addEventListener('change',
                                        actualizarOpcionesHoraCambio);
                                    if (selectHora) selectHora.addEventListener('change',
                                        verificarFormularioReservaCambio);

                                    window.verificarFormularioReservaCambio =
                                        verificarFormularioReservaCambio;
                                    actualizarOpcionesHoraCambio();
                                }, 100);
                                break;

                            case 'recoger':
                            default:
                                camposAdicionales.innerHTML = `
                                <div class="text-success text-center py-3">
                                    <i class="fa fa-check-circle fa-2x mb-2"></i>
                                    <div><strong>Venta Rápida no requiere datos adicionales</strong></div>
                                </div>
                            `;
                                confirmBtn.disabled = false;
                                break;
                        }
                    }

                    // Listeners para cambio de tipo
                    radioMesa.addEventListener('change', actualizarCamposAdicionales);
                    radioDelivery.addEventListener('change', actualizarCamposAdicionales);
                    radioRecoger.addEventListener('change', actualizarCamposAdicionales);
                    radioReserva.addEventListener('change', actualizarCamposAdicionales);

                    // Ejecutar inicialmente para cargar los campos del tipo preseleccionado
                    actualizarCamposAdicionales();

                    // Funciones auxiliares
                    window.actualizarListaMesas = function() {
                        const listaMesas = document.getElementById('listaMesas');
                        if (!listaMesas) return;

                        if (mesasDisponiblesGlobal.length === 0) {
                            listaMesas.innerHTML =
                                '<div class="text-muted text-center py-2">No hay mesas disponibles</div>';
                            return;
                        }

                        listaMesas.innerHTML = '<div class="row g-2">' +
                            mesasDisponiblesGlobal.map(mesa => `
                            <div class="col-4">
                                <div class="mesa-item-cambio card text-center p-2 ${mesa.ocupada && !mesa.es_actual ? 'mesa-ocupada-cambio' : ''} ${mesa.es_actual ? 'mesa-actual' : ''}" 
                                     data-mesa-id="${mesa.id}"
                                     style="cursor: ${mesa.ocupada && !mesa.es_actual ? 'not-allowed' : 'pointer'}; border: 2px solid ${mesa.es_actual ? '#28a745' : mesa.ocupada ? '#dc3545' : '#e9ecef'};">
                                    <i class="fa fa-table fa-2x ${mesa.ocupada && !mesa.es_actual ? 'text-danger' : mesa.es_actual ? 'text-success' : 'text-primary'}"></i>
                                    <div><strong>Mesa ${mesa.numero}</strong></div>
                                    ${mesa.capacidad ? `<small class="text-muted">${mesa.capacidad} personas</small>` : ''}
                                    ${mesa.es_actual ? '<div class="badge badge-success mt-1">Actual</div>' : ''}
                                    ${mesa.ocupada && !mesa.es_actual ? '<div class="badge badge-danger mt-1">Ocupada</div>' : ''}
                                </div>
                            </div>
                        `).join('') +
                            '</div>';

                        document.querySelectorAll('.mesa-item-cambio').forEach(item => {
                            const mesaId = parseInt(item.getAttribute('data-mesa-id'));
                            const mesa = mesasDisponiblesGlobal.find(m => m.id === mesaId);

                            if (!mesa.ocupada || mesa.es_actual) {
                                item.addEventListener('click', function() {
                                    document.querySelectorAll('.mesa-item-cambio')
                                        .forEach(
                                            m => {
                                                m.style.borderColor = m.querySelector(
                                                        '.badge-success') ? '#28a745' :
                                                    '#e9ecef';
                                                m.style.backgroundColor = '';
                                            });

                                    this.style.borderColor = '#007bff';
                                    this.style.backgroundColor = '#e7f3ff';
                                    mesaSeleccionadaId = mesaId;
                                    confirmBtn.disabled = false;
                                });

                                item.addEventListener('mouseover', function() {
                                    if (!mesa.ocupada || mesa.es_actual) {
                                        this.style.transform = 'scale(1.05)';
                                    }
                                });

                                item.addEventListener('mouseout', function() {
                                    this.style.transform = 'scale(1)';
                                });
                            }
                        });
                    };

                    window.actualizarListaClientesCambio = function() {
                        const listaResultados = document.getElementById('resultadosClientesCambio') ||
                            document.getElementById('resultadosClientesReservaCambio') ||
                            document.getElementById('resultadosClientesMesa');

                        if (!listaResultados) return;

                        if (clientesDisponiblesGlobal.length === 0) {
                            listaResultados.innerHTML =
                                '<div class="text-muted text-center py-2">No se encontraron clientes</div>';
                            return;
                        }

                        listaResultados.innerHTML = clientesDisponiblesGlobal.map(cliente => `
                        <div class="cliente-item-cambio p-2 border-bottom" style="cursor: pointer; transition: background-color 0.2s;"
                             data-cliente-id="${cliente.id}"
                             onmouseover="this.style.backgroundColor='#f0f0f0'"
                             onmouseout="this.style.backgroundColor='white'">
                            <div><strong>${cliente.name}</strong></div>
                            <div class="text-muted" style="font-size: 0.85em;">${cliente.email}</div>
                        </div>
                    `).join('');

                        document.querySelectorAll('.cliente-item-cambio').forEach(item => {
                            item.addEventListener('click', function() {
                                const clienteId = this.getAttribute('data-cliente-id');
                                const clienteNombre = this.querySelector('strong')
                                    .textContent;
                                seleccionarClienteCambio(clienteId, clienteNombre);
                            });
                        });
                    };

                    function seleccionarClienteCambio(id, nombre) {
                        clienteSeleccionadoCambioId = id;

                        const inputBusqueda = document.getElementById('busquedaClienteCambio') ||
                            document.getElementById('busquedaClienteReservaCambio') ||
                            document.getElementById('busquedaClienteMesa');
                        const listaResultados = document.getElementById('resultadosClientesCambio') ||
                            document.getElementById('resultadosClientesReservaCambio') ||
                            document.getElementById('resultadosClientesMesa');

                        if (inputBusqueda) {
                            inputBusqueda.value = nombre;
                            inputBusqueda.style.borderColor = '#28a745';
                            inputBusqueda.style.backgroundColor = '#e8f5e9';
                        }

                        if (listaResultados) {
                            listaResultados.innerHTML =
                                '<div class="text-success text-center py-2"><i class="fa fa-check-circle"></i> Cliente seleccionado</div>';
                        }

                        const tipoActual = document.querySelector('input[name="tipoEntregaCambio"]:checked')
                            .value;

                        // Mostrar botón de limpiar para mesa
                        if (tipoActual === 'mesa') {
                            const btnLimpiarCliente = document.getElementById('limpiarClienteMesa');
                            if (btnLimpiarCliente) {
                                btnLimpiarCliente.style.display = 'inline-block';
                            }
                        }

                        if (tipoActual === 'delivery') {
                            confirmBtn.disabled = false;
                        } else if (tipoActual === 'reserva' && typeof verificarFormularioReservaCambio ===
                            'function') {
                            verificarFormularioReservaCambio();
                        }
                        // Para mesa, el cliente es opcional, no afecta el estado del botón
                    }

                    function buscarClientesCambio(termino) {
                        clearTimeout(debounceTimerCambio);

                        if (termino.length < 2) {
                            clientesDisponiblesGlobal = [];
                            actualizarListaClientesCambio();
                            return;
                        }

                        debounceTimerCambio = setTimeout(() => {
                            @this.set('user', termino);
                            @this.call('buscarClientes');
                        }, 500);
                    }

                    window.seleccionarClienteCambio = seleccionarClienteCambio;
                    window.buscarClientesCambio = buscarClientesCambio;
                },
                preConfirm: () => {
                    const tipoSeleccionado = document.querySelector(
                            'input[name="tipoEntregaCambio"]:checked')
                        .value;
                    let resultado = {
                        tipo: tipoSeleccionado
                    };

                    switch (tipoSeleccionado) {
                        case 'mesa':
                            if (!mesaSeleccionadaId) {
                                Swal.showValidationMessage('Debe seleccionar una mesa');
                                return false;
                            }
                            resultado.mesaId = mesaSeleccionadaId;
                            // Cliente opcional para mesa
                            if (clienteSeleccionadoCambioId) {
                                resultado.clienteId = clienteSeleccionadoCambioId;
                            }
                            break;

                        case 'delivery':
                            if (!clienteSeleccionadoCambioId) {
                                Swal.showValidationMessage('Debe seleccionar un cliente para delivery');
                                return false;
                            }
                            resultado.clienteId = clienteSeleccionadoCambioId;
                            break;

                        case 'reserva':
                            // Validar cliente (OBLIGATORIO para reservas)
                            if (!clienteSeleccionadoCambioId) {
                                Swal.showValidationMessage('Debe seleccionar un cliente para la reserva');
                                return false;
                            }

                            const tipoEntregaReservaSelect = document.getElementById('tipoEntregaReserva');
                            if (!tipoEntregaReservaSelect || !tipoEntregaReservaSelect.value) {
                                Swal.showValidationMessage('Debe seleccionar el tipo de entrega');
                                return false;
                            }

                            const tipoEntregaReserva = tipoEntregaReservaSelect.value;

                            // Validar mesa si el tipo es mesa
                            if (tipoEntregaReserva === 'mesa' && !mesaSeleccionadaId) {
                                Swal.showValidationMessage('Debe seleccionar una mesa');
                                return false;
                            }

                            const selectHora = document.getElementById('horaReservaCambio');
                            if (!selectHora || !selectHora.value) {
                                Swal.showValidationMessage('Debe seleccionar una hora');
                                return false;
                            }

                            const fechaSeleccionada = document.querySelector(
                                'input[name="fechaReservaCambio"]:checked').value;
                            const hora = selectHora.value;

                            const ahora = new Date();
                            let fechaReserva = new Date();

                            if (fechaSeleccionada === 'manana') {
                                fechaReserva.setDate(fechaReserva.getDate() + 1);
                            }

                            const [horas, minutos] = hora.split(':');
                            fechaReserva.setHours(parseInt(horas), parseInt(minutos), 0, 0);

                            const fechaHoraFormateada = fechaReserva.getFullYear() + '-' +
                                String(fechaReserva.getMonth() + 1).padStart(2, '0') + '-' +
                                String(fechaReserva.getDate()).padStart(2, '0') + ' ' +
                                String(fechaReserva.getHours()).padStart(2, '0') + ':' +
                                String(fechaReserva.getMinutes()).padStart(2, '0') + ':00';

                            // El tipo real es el seleccionado en el select
                            resultado.tipo = tipoEntregaReserva;
                            resultado.esReserva = true;
                            resultado.clienteId = clienteSeleccionadoCambioId; // Cliente SIEMPRE incluido

                            // Agregar mesa solo si el tipo es mesa
                            if (tipoEntregaReserva === 'mesa') {
                                resultado.mesaId = mesaSeleccionadaId;
                            }

                            resultado.fechaHora = fechaHoraFormateada;
                            break;

                        case 'recoger':
                            // No requiere validaciones adicionales
                            break;
                    }

                    return resultado;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('cambiarTipoEntregaVenta',
                        result.value.tipo,
                        result.value.mesaId || null,
                        result.value.clienteId || null,
                        result.value.fechaHora || null
                    );
                }
                // Limpiar variables
                @this.set('user', '');
            });
        }

        // Estilos adicionales
        const style = document.createElement('style');
        style.textContent = `
        .mesa-ocupada-cambio {
            opacity: 0.5;
            filter: grayscale(50%);
        }
        .mesa-actual {
            background-color: #d4edda !important;
        }
        .mesa-item-cambio {
            transition: all 0.2s ease;
        }
        
        /* Estilos para SweetAlert con fondo blanco */
        .swal-fondo-blanco {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-title {
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-html-container {
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-content {
            color: #000000 !important;
        }
        .swal-fondo-blanco label,
        .swal-fondo-blanco .form-label {
            color: #000000 !important;
        }
    `;
        document.head.appendChild(style);
    </script>
</x-card-col>
