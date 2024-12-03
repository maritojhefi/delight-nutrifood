<div wire:ignore.self class="modal fade" id="basicModal">
    <div class="modal-dialog" role="document">
        @if ($modoImpresion)
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Ajustes de Impresion
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h5>
                </div>
                <div class="modal-body pb-0 mb-0">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Observacion</label>
                        <div class="col-sm-9">
                            <textarea class="form-control form-control-sm bordeado" wire:model="observacionRecibo"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Fecha
                            Personalizada</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm bordeado m-0 p-0 px-1"
                                style="height: 35px" wire:model="fechaRecibo">
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" wire:model="checkMetodoPagoPersonalizado">
                        <label class="form-check-label" for="check1">Agregar Metodo de Pago</label>
                    </div>
                    @if ($checkMetodoPagoPersonalizado)
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label col-form-label-sm">Metodo</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm bordeado m-0 p-0 px-1"
                                    style="height: 35px" wire:model="metodoRecibo">
                            </div>


                        </div>
                    @endif
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" wire:model="checkClientePersonalizado">
                        <label class="form-check-label" for="check1">Agregar Cliente
                            Personalizado</label>
                    </div>

                    @if ($checkClientePersonalizado)
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label col-form-label-sm">Cliente</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm bordeado m-0 p-0 px-1"
                                    style="height: 35px" wire:model="clienteRecibo">
                            </div>
                            @isset($cuenta->cliente)
                                <div class="alert alert-warning alert-dismissible fade show text-sm">

                                    <strong>Atencion!</strong> Se reemplazara el nombre de
                                    <strong>{{ $cuenta->cliente->name }} </strong> por lo añadido a este
                                    campo
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                    </button>
                                </div>
                            @endisset

                        </div>
                    @endif
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" wire:model="checkTelefonoPersonalizado">
                        <label class="form-check-label" for="check1">Agregar Telefono</label>
                    </div>
                    @if ($checkTelefonoPersonalizado)
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label col-form-label-sm">Telefono</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm bordeado m-0 p-0 px-1"
                                    style="height: 35px" wire:model="telefonoRecibo">
                            </div>

                            <div class="letra12 alert alert-info alert-dismissible fade show text-sm mb-0">

                                <strong>Importante!</strong>Este numero no se imprimira, solo se
                                guardara en
                                prospectos dentro del sistema.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-dark btn-xxs" onclick="atrasModalImpresion()">Atras</a>
                    <a href="#" class="btn btn-outline-info btn-xxs" onclick="descargarPDFFile()">Descargar
                        PDF <i class="fa fa-file"></i></a>
                    <a href="#" class="btn btn-outline-success btn-xxs" onclick="imprimirReciboApi()">Imprimir <i
                            class="fa fa-print"></i></a>
                </div>
            </div>
        @else
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de la cuenta
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body m-1 p-0 px-2">
                    @isset($cuenta->cliente)
                        <center class="text-muted" style="font-size: 12px">Cliente:
                            {{ $cuenta->cliente->name }} </center>
                    @endisset
                    <ul class="list-group" style="border: 2px solid #20c996b3;">
                        <li class="list-group-item d-flex justify-content-between lh-condensed m-0 py-0">
                            <div>
                                <h6 class="my-0">Subtotal</h6>

                            </div>
                            <span class="text-muted">{{ $subtotal }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed m-0 py-0">
                            <div>
                                <h6 class="my-0">Descuento por Productos</h6>

                            </div>
                            <span class="text-muted">{{ $descuentoProductos }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed m-0 py-0">
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


                    <div class="content">
                        @if ($cuenta->ventaHistorial)
                            <div class="card-body py-3 letra12">
                                <ul class="d-flex align-items-center mb-1">
                                    <li><a href="javascript:void(0);"><i class="fa fa-gear"></i></a>
                                    </li>
                                    <li><a href="javascript:void(0);" class="ms-2">Estado:</a></li>
                                    <li><strong><a href="javascript:void(0);"
                                                class="mx-2 badge badge-sm badge-dark">Pagado</a></strong></li>
                                </ul>
                                @if ($cuenta->ventaHistorial->metodosPagos)

                                    @foreach ($cuenta->ventaHistorial->metodosPagos as $metodo)
                                        <ul class="d-flex align-items-center mb-1">
                                            <li><a href="javascript:void(0);"><img src="{{ $metodo->imagen }}"
                                                        class="rounded-circle" style="width: 35px;height:35px"
                                                        alt=""></a>
                                            </li>
                                            <li><a href="javascript:void(0);"
                                                    class="ms-2">{{ $metodo->nombre_metodo_pago }}</a></li>
                                            <li><strong><a href="javascript:void(0);"
                                                        class="mx-2">{{ $metodo->pivot->monto . ' Bs' }}</a></strong>
                                            </li>
                                        </ul>
                                    @endforeach
                                    @if ($cuenta->ventaHistorial->saldo_monto > 0)
                                        <ul class="d-flex align-items-center mb-1">
                                            <li><a href="javascript:void(0);"><i class="fa fa-tag"></i></a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="ms-2">Saldo creado: </a></li>
                                            <li><strong><a href="javascript:void(0);"
                                                        class="mx-2">{{ $cuenta->ventaHistorial->saldo_monto }}
                                                        Bs</a></strong>
                                                <span
                                                    class="letra10">{{ $cuenta->ventaHistorial->a_favor_cliente ? '(A favor del cliente)' : '(Deuda nueva del cliente)' }}</span>
                                            </li>
                                        </ul>
                                    @endif


                                @endif

                            </div>
                        @else
                            @if (isset($metodosPagos))
                                {{-- VERSION 2 PARA TIPOS DE PAGO --}}
                                @include('livewire.admin.ventas.includes-pos.metodos-pagos-v2')
                            @else
                                {{-- VERSION 1 PARA TIPOS DE PAGO  --}}
                                @include('livewire.admin.ventas.includes-pos.metodos-pagos-v1')
                            @endif
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    @if (!$cuenta->pagado)
                        @if (!$cuenta->cliente)
                            <button type="button" class="btn btn-primary p-2 my-0 " wire:loading.attr="disabled"
                                onclick="alertCobrarVenta('{{ $totalAcumuladoMetodos }}','{{ $subtotalConDescuento }}')"
                                {{ $subtotalConDescuento == $totalAcumuladoMetodos ? '' : 'disabled' }}>
                                Marcar como pagado</button>
                        @else
                            <button type="button" class="btn btn-primary p-2 my-0 " wire:loading.attr="disabled"
                                onclick="alertCobrarVenta('{{ $totalAcumuladoMetodos }}','{{ $subtotalConDescuento }}','{{ $cuenta->cliente->name }}')">Marcar
                                como pagado</button>
                        @endif
                    @else
                        <button type="button" class="btn btn-dark p-2 my-0 " wire:loading.attr="disabled"
                            onclick="sendFinalizarVenta()" data-bs-dismiss="modal">Finalizar y cerrar venta</button>
                    @endif

                    <button type="button" class="btn btn-outline-warning btn-xxs p-2 my-0"
                        {{ $cuenta->pagado ? '' : 'disabled' }} wire:click="modalImpresion"><span>Imprimir <i
                                class="fa fa-print"></i></span></button>
                    @if ($cuenta->cocina && !$cuenta->despachado_cocina)
                        <button type="button" disabled class="btn btn-info btn-xxs p-2 my-0"><span>Enviado a cocina
                                <i class="fa fa-send"></i></span></button>
                    @elseif($cuenta->despachado_cocina)
                        <button type="button" disabled class="btn btn-success btn-xxs p-2 my-0"><span>Despachado <i
                                    class="fa fa-thumbs-up"></i></span></button>
                    @else
                        <button wire:loading.remove wire:target='imprimir' type="button"
                            class="btn btn-outline-info btn-xxs p-2 my-0" wire:click="imprimirCocina"><span>Enviar a
                                cocina <i class="fa fa-send"></i></span></button>
                    @endif

                </div>
            </div>
        @endif

    </div>
</div>
