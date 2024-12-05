<div class="row">
    @if ($cajaSeleccionada)
        <div class="row">
            <div class="col-9 p-0">
                <div class="col-12 pe-1">
                    <div class="card p-0 bordeado">
                        <div class="card-header py-2">
                            <strong>Listado de ventas <a href="#" wire:click="cambiarCaja"
                                    class="badge badge-sm badge-warning">Cambiar caja <i
                                        class="flaticon-075-reload"></i></a></strong>
                            <strong>{{ App\Helpers\GlobalHelper::fechaFormateada(2, $cajaSeleccionada->created_at) }}</strong>
                        </div>
                        <div class="card-body py-2">
                            <div class="table-responsive">
                                <div style="max-height: 300px !important; overflow-y: auto;">
                                    <table class="table p-0 m-0 letra12 table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-white bg-primary"><i class="fa fa-gear"></i></th>
                                                <th class="text-white bg-primary">Cliente</th>
                                                <th class="text-white bg-primary">Método</th>
                                                <th class="text-white bg-primary">Puntos</th>
                                                <th class="text-white bg-primary">Subtotal</th>
                                                <th class="text-white bg-primary">Total Descuentos</th>
                                                <th class="text-white bg-primary">Monto saldo</th>
                                                <th class="text-white bg-primary">Total Cobrado</th>
                                                <th class="text-white bg-primary">Hora</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ventasCaja as $venta)
                                                <tr>
                                                    <td class="p-0"><a href="#"
                                                            wire:click="seleccionarVenta({{ $venta->id }})"
                                                            data-bs-target="#modalDetalleVenta" data-bs-toggle="modal"
                                                            class="badge badge-xxs badge-info py-0 px-1 m-0"><i
                                                                class="fa fa-list"></i></a></td>
                                                    <td class="p-0">
                                                        @if ($venta->cliente)
                                                            <strong>{{ Str::limit($venta->cliente->name, 20) }}</strong>
                                                        @else
                                                            <span class="text-muted">Desconocido</span>
                                                        @endif

                                                    </td>
                                                    <td class="p-0">
                                                        @foreach ($venta->metodosPagos as $metPago)
                                                            <span class="rounded-circle popover-container m-0 p-0"
                                                                style="
                                                        width: 25px;
                                                        height: 25px;
                                                        display: inline-block;
                                                        background-image: url({{ $metPago->imagen }});
                                                        background-size: cover;
                                                        background-position: center;
                                                        background-repeat: no-repeat;
                                                    "
                                                                alt="">
                                                                <span
                                                                    class="popover-text">{{ $metPago->nombre_metodo_pago }}
                                                                    :
                                                                    {{ $metPago->pivot->monto }} Bs</span>
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                    <td class="p-0">{{ floatval($venta->puntos) }} Pts</td>
                                                    <td class="p-0">{{ floatval($venta->subtotal) }}
                                                        Bs</td>
                                                    <td class="p-0">{{ floatval($venta->total_descuento) }} Bs
                                                        @if (floatval($venta->total_descuento) != 0)
                                                            <span class="popover-container"><i
                                                                    class="flaticon-050-info fs-20 text-primary"></i>
                                                                <span class="popover-text">Productos:
                                                                    {{ $venta->descuento_productos }}
                                                                    Bs
                                                                    <br>
                                                                    Manual: {{ $venta->descuento_manual }} Bs
                                                                </span>
                                                            </span>
                                                        @endif
                                                    </td>

                                                    @if ($venta->a_favor_cliente === 1)
                                                        <td class="p-0"><span
                                                                class="text-info popover-container">{{ floatval($venta->saldo_monto) }}
                                                                Bs <i class="flaticon-003-arrow-up"></i><span
                                                                    class="popover-text">A
                                                                    favor del cliente</span></span>
                                                        </td>
                                                    @elseif ($venta->a_favor_cliente === 0)
                                                        <td class="p-0"><span
                                                                class="text-warning popover-container">{{ floatval($venta->saldo_monto) }}
                                                                Bs <i class="flaticon-001-arrow-down"></i><span
                                                                    class="popover-text">A
                                                                    deuda del cliente</span></span>
                                                        </td>
                                                    @elseif ($venta->a_favor_cliente === null)
                                                        <td class="p-0"><span
                                                                class="text-dark">{{ floatval($venta->saldo_monto) }}
                                                                Bs</span>
                                                        </td>
                                                    @else
                                                        <td class="p-0"><span
                                                                class="text-muted">{{ floatval($venta->saldo_monto) }}
                                                                Bs</span>
                                                        </td>
                                                    @endif
                                                    <td class="p-0"><strong> {{ floatval($venta->total_pagado) }}
                                                            Bs</strong>
                                                    </td>
                                                    <td>{{ App\Helpers\GlobalHelper::fechaFormateada(9, $venta->created_at) }}
                                                    </td>

                                                </tr>
                                            @endforeach
                                            <tr class="letra14">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><strong>{{ $totalIngresoPOS }} Bs</strong></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-12 pe-1">
                    <div class="card p-0 bordeado">
                        <div class="card-header py-2">
                            <strong>Saldos pagados y anticipos </strong>
                            <strong>{{ App\Helpers\GlobalHelper::fechaFormateada(2, $cajaSeleccionada->created_at) }}</strong>
                        </div>
                        <div class="card-body py-2">
                            <div class="table-responsive">
                                <div class="" style="max-height: 300px !important; overflow-y: auto;">
                                    <table class="table p-0 m-0 letra12">
                                        <thead>
                                            <tr>
                                                <th class="text-white bg-primary">Cliente</th>
                                                <th class="text-white bg-primary">Método</th>
                                                <th class="text-white bg-primary">Monto</th>
                                                <th class="text-white bg-primary">Detalle</th>
                                                <th class="text-white bg-primary">Hora</th>
                                                <th class="text-white bg-primary">Atendido por</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($saldosPagadosArray as $saldo)
                                                <tr>
                                                    <td class="py-1"><strong>{{ $saldo->usuario->name }}</strong>
                                                    </td>
                                                    <td class="py-1">
                                                        @foreach ($saldo->metodosPagos as $metPago)
                                                            <span class="rounded-circle popover-container m-0 p-0"
                                                                style="
                                                    width: 25px;
                                                    height: 25px;
                                                    display: inline-block;
                                                    background-image: url({{ $metPago->imagen }});
                                                    background-size: cover;
                                                    background-position: center;
                                                    background-repeat: no-repeat;
                                                "
                                                                alt="">
                                                                <span
                                                                    class="popover-text">{{ $metPago->nombre_metodo_pago }}
                                                                    :
                                                                    {{ $metPago->pivot->monto }} Bs</span>
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                    <td class="py-1">
                                                        <span class="text-info popover-container"><strong>{{ floatval($saldo->monto) }}
                                                                Bs</strong> <i class="flaticon-003-arrow-up"></i><span
                                                                class="popover-text">A
                                                                favor del cliente</span></span>
                                                    </td>
                                                    <td><span
                                                            class="popover-container">{{ Str::limit($saldo->detalle, 15) }}
                                                            <span class="popover-text">{{ $saldo->detalle }}</span>
                                                        </span></td>
                                                    <td>{{ App\Helpers\GlobalHelper::fechaFormateada(9, $saldo->created_at) }}
                                                        ({{ App\Helpers\GlobalHelper::timeago($saldo->created_at) }})
                                                    </td>
                                                    <td class="py-1">{{ $saldo->atendidoPor->name }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 pe-1">
                    <div class="card p-0 bordeado">
                        <div class="card-header py-2">
                            <strong>Productos vendidos</strong>
                            <strong>{{ App\Helpers\GlobalHelper::fechaFormateada(2, $cajaSeleccionada->created_at) }}</strong>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-5">
                                    <div class="table-responsive">
                                        <div style="max-height: 300px !important; overflow-y: auto;">
                                            <table class="table p-0 m-0 letra12">
                                                <thead>
                                                    <tr>
                                                        <th class="text-white bg-primary">Producto</th>
                                                        <th class="text-white bg-primary">Cant.</th>
                                                        <th class="text-white bg-primary">Monto Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($cajaSeleccionada->arrayProductosVendidos() as $pro)
                                                        <tr>
                                                            <td class="py-1"><span class="float-start"><i
                                                                        class="fa fa-stop "
                                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion($loop->index) }}"></i>
                                                                    <strong>{{ $pro->nombre }}</strong></span>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ $pro->cantidad_total }}</strong>
                                                            <td class="py-1"><strong>{{ $pro->suma_total }}
                                                                    Bs</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-7">
                                    <div class="row" style="height: 450px; overflow: hidden;">
                                        <!-- Establecemos el tamaño del contenedor y nos aseguramos de que la imagen no se salga -->
                                        <img src="{{ $cajaSeleccionada->urlGraficoProductosVendidos() }}"
                                            style="width: 100%; height: 100%; object-fit: cover;" alt="">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 p-0">
                <div class="col-12 ps-1">
                    <div class="card p-0 bordeado">
                        <div class="card-header py-2">
                            <strong>Resumen general</strong>

                        </div>
                        <div class="card-body py-2 px-1">
                            <div class="media event-card p-1 px-2 rounded align-items-center m-1">
                                <i class="flaticon-017-clipboard fs-30 me-3"></i>
                                <div class="media-body event-size">
                                    <span class="fs-14 d-block mb-1 text-primary">Ingresos</span>
                                    <span class="fs-18 letra14 event-size-1">
                                        <strong>{{ $totalIngresoPOS - $totalSaldoExcedentes }} Bs</strong><span
                                            class="letra10">
                                            (Ventas)</span><br>
                                        <strong>{{ $totalSaldoExcedentes }} Bs</strong><span class="letra10">
                                            (Excedentes de ventas)</span><br>
                                        <strong>{{ $totalSaldosPagados }} Bs</strong><span class="letra10">
                                            (Saldos pagados y anticipos)</span><br>
                                        <hr class="m-0 p-0">
                                        <strong>{{ $totalIngresoAbsoluto }} Bs</strong><span class="letra10">
                                            (Total Ingreso)</span>
                                    </span>
                                </div>
                            </div>
                            <div class="media event-card p-1 px-2 rounded align-items-center m-1">
                                <i class="flaticon-381-id-card fs-30 me-3"></i>
                                <div class="media-body event-size">
                                    <span class="fs-14 d-block mb-1 text-primary">Metodos de pago</span>
                                    @foreach ($acumuladoPorMetodoPago as $metodo => $monto)
                                        <strong>{{ floatval($monto) }} Bs</strong><span class="letra12">
                                            ({{ $metodo }})
                                        </span><br>
                                    @endforeach

                                </div>
                            </div>
                            <div class="row" style="height: 250px; overflow: hidden;">
                                <!-- Establecemos el tamaño del contenedor y nos aseguramos de que la imagen no se salga -->
                                <img src="{{ $cajaSeleccionada->generarGraficoIngresosPorMetodoPago() }}"
                                    style="width: 100%; height: 100%; object-fit: cover;" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modalDetalleVenta">
            <div class="modal-dialog modal-lg">
                <div class="modal-content letra14">
                    <div class="modal-header">
                        @isset($ventaSeleccionada)
                            <strong>Atendido por: {{ $ventaSeleccionada->usuario->name }}</strong>
                            <strong>Hora de venta:
                                {{ App\Helpers\GlobalHelper::fechaFormateada(6, $ventaSeleccionada->created_at) }}</strong>
                        @endisset
                    </div>
                    <center wire:loading>
                        <div class="spinner-border mb-3" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </center>


                    <div class="modal-body mt-0 pt-0 " wire:loading.remove>
                        @isset($ventaSeleccionada)
                            <div class="table-responsive">
                                <table class="table p-0 m-0 letra12 ">
                                    <thead>
                                        <tr>
                                            <th class="py-1 text-white bg-primary">Producto</th>
                                            <th class="py-1 text-white bg-primary">Cant.</th>
                                            <th class="py-1 text-white bg-primary">Unit.</th>
                                            <th class="py-1 text-white bg-primary">Subt.</th>
                                            {{-- <th class="py-1 text-white bg-primary">Desc.</th> --}}
                                            <th class="py-1 text-white bg-primary">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ventaSeleccionada->productos as $producto)
                                            <tr>
                                                <td class="py-1">{{ Str::limit($producto->nombre, 30) }}</td>
                                                <td class="py-1">{{ $producto->pivot->cantidad }}</td>
                                                <td class="py-1">
                                                    {{ $producto->pivot->precio_unitario ?? $producto->precio }} Bs</td>
                                                <td class="py-1">
                                                    {{ $producto->pivot->precio_subtotal ?? $producto->precio * $producto->pivot->cantidad }}
                                                    Bs
                                                </td>
                                                {{-- <td class="py-1">{{ $producto->pivot->descuento_producto ?? 0 }} Bs</td> --}}
                                                <td class="py-1">
                                                    <strong>{{ $producto->pivot->precio_subtotal ?? $producto->precio * $producto->pivot->cantidad - $producto->pivot->descuento_producto }}
                                                        Bs</strong>

                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="py-1 text-primary"><span>Total original</span></td>
                                            <td class="py-1 text-primary"><strong>{{ $ventaSeleccionada->subtotal }}
                                                    Bs</strong></td>
                                        </tr>
                                        <tr>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="py-1"><span>Descuento productos</span></td>
                                            <td class="py-1"><span>-{{ $ventaSeleccionada->descuento_productos }}
                                                    Bs</span></td>
                                        </tr>
                                        <tr>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="py-1"><span>Descuento manual</span></td>
                                            <td class="py-1"><span>-{{ $ventaSeleccionada->descuento_manual }} Bs</span>
                                            </td>
                                        </tr>
                                        @if ($ventaSeleccionada->saldo_monto != 0 && $ventaSeleccionada->saldo_monto != null)
                                            <tr>
                                                {{-- <td></td> --}}
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                @if ($ventaSeleccionada->a_favor_cliente)
                                                    <td class="py-1 text-info">
                                                        <span class="letra14 ">A favor cliente <i
                                                                class="flaticon-003-arrow-up"></i></span>
                                                    </td>
                                                @else
                                                    <td class="py-1 text-warning">
                                                        <span class="letra14 ">A deuda <i
                                                                class="flaticon-001-arrow-down"></i></span>
                                                    </td>
                                                @endif

                                                <td
                                                    class="py-1 {{ $ventaSeleccionada->a_favor_cliente ? 'text-info' : 'text-warning' }}">
                                                    <strong
                                                        class="letra14">{{ $ventaSeleccionada->a_favor_cliente ? '+' : '-' }}{{ $ventaSeleccionada->saldo_monto }}
                                                        Bs</strong>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="py-1 "><strong class="letra14">Total Pagado</strong></td>
                                            <td class="py-1 "><strong
                                                    class="letra14">{{ $ventaSeleccionada->total_pagado }} Bs</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endisset
                            </table>
                        </div>
                        <div class="card-body py-3 letra12">
                            <ul class="d-flex align-items-center mb-1">

                                <li><a href="javascript:void(0);" class="ms-2">Metodos de pago:</a></li>

                            </ul>
                            @isset($ventaSeleccionada->metodosPagos)
                                @foreach ($ventaSeleccionada->metodosPagos as $metodo)
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
                                        <li>
                                            <div class="dropdown ms-auto">
                                                <a href="#" class="mx-2 badge badge-xs badge-info p-1 py-0 letra10"
                                                    data-bs-toggle="dropdown" aria-expanded="false"> <strong>Cambiar
                                                        <i class="flaticon-075-reload"></i></strong></a>
                                                <ul class="dropdown-menu dropdown-menu-end" style="">

                                                    @foreach ($metodosPagos as $met)
                                                        <li class="dropdown-item"
                                                            onclick="cambiarMetodo('{{ $met->nombre_metodo_pago }}',{{ $met->id }},{{ $metodo->pivot->id }})">
                                                            <a href="javascript:void(0);"><img src="{{ $met->imagen }}"
                                                                    class="rounded-circle" style="width: 35px;height:35px"
                                                                    alt="">
                                                                {{ $met->nombre_metodo_pago }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                @endforeach
                            @endisset

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="row m-0 p-0" style="">

                @foreach ($cajas as $caja)
                    <div class="col-12 col-sm-4 px-1 m-0 py-0">
                        <div class="card py-0 bordeado">
                            <div class="card-body py-2">
                                <div class="row">

                                    <div class="col-6 letra14">
                                        <strong>{{ App\Helpers\GlobalHelper::fechaFormateada(2, $caja->created_at) }}</strong>
                                        <ul class="mt-2 text-center">
                                            <li><span class="float-start"><i class="fa fa-stop" style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion(0) }}"></i>
                                                    Ingresos Ventas:</span> <br>
                                                <strong class="">{{ $caja->ingresoVentasPOS() }} Bs</strong>
                                            </li>
                                            <li><span class="float-start"><i class="fa fa-stop" style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion(1) }}"></i>
                                                    Ingresos Saldos:</span> <br>
                                                <strong class="">{{ $caja->totalSaldosPagadosSinVenta() }}
                                                    Bs</strong>
                                            </li>
                                            <li><span class="float-start"><i class="fa fa-stop text-success"></i>
                                                    Total Ingresos:</span> <br>
                                                <strong class="">{{ $caja->totalIngresoAbsoluto() }} Bs</strong>
                                            </li>

                                        </ul>
                                        <a href="#" wire:click="buscarCaja({{ $caja->id }})">
                                            <span class="badge badge-primary badge-xxs letra14 py-1">Ver
                                                detalles</span>
                                        </a>
                                    </div>
                                    <div class="col-6" style="height: 200px; overflow: hidden;">
                                        <!-- Establecemos el tamaño del contenedor y nos aseguramos de que la imagen no se salga -->
                                        <img src="{{ $caja->urlGraficoComposicionIngresos() }}"
                                            style="width: 100%; height: 100%; object-fit: cover;" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $cajas->links() }}

        </div>
    @endif
</div>
@push('css')
    <style>
        .table {
            border-collapse: collapse;
            /* Asegura que los bordes no se dupliquen */
            width: 100%;
            /* Hace que la tabla ocupe el ancho completo */
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            /* Agrega un borde gris claro */
            text-align: center;
            /* Opcional: centra el contenido */
            padding: 8px;
            /* Espaciado interno */
        }

        .table thead th {
            background-color: #f2f2f2;
            /* Fondo para la cabecera */
        }
    </style>
    <style>
        /* Ancho del scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
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
                width: 7px;
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
        function cambiarMetodo(nombre, id, pivotId) {
            console.log(pivotId);

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Cambiaras al metodo ' + nombre,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar evento a Livewire
                    Livewire.emit('cambiarMetodo', id, pivotId);
                }
            });
        }
    </script>
@endpush
