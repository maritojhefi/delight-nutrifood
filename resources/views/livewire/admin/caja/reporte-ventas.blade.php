@if ($cajaSeleccionada)
    <div class="row">
        <div class="col-9 pe-1">
            <div class="card p-0 bordeado">
                <div class="card-header">
                    <strong>Listado de ventas <a href="#" wire:click="cambiarCaja"
                            class="badge badge-sm badge-warning">Cambiar caja <i
                                class="flaticon-075-reload"></i></a></strong>
                </div>
                <div class="card-body">
                    <table class="table p-0 m-0 letra12">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Método</th>
                                <th>Puntos</th>
                                <th>Subtotal</th>
                                <th>Total Descuentos</th>
                                <th>Monto saldo</th>
                                <th>Total Cobrado</th>
                                <th><i class="fa fa-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ventasCaja as $venta)
                                <tr>
                                    <td>
                                        @if ($venta->cliente)
                                            <strong>{{ Str::limit($venta->cliente->name, 20) }}</strong>
                                        @else
                                            <span class="text-muted">Desconocido</span>
                                        @endif

                                    </td>
                                    <td>
                                        @foreach ($venta->metodosPagos as $metPago)
                                            <span class="rounded-circle popover-container"
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
                                                <span class="popover-text">{{ $metPago->nombre_metodo_pago }}</span>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>{{ floatval($venta->puntos) }} Pts</td>
                                    <td>{{ floatval($venta->subtotal + $venta->descuento_productos + $venta->descuento_manual) }}
                                        Bs</td>
                                    <td>{{ floatval($venta->total_descuento) }} Bs
                                        @if (floatval($venta->total_descuento) != 0)
                                            <span class="popover-container"><i
                                                    class="flaticon-050-info fs-20 text-primary"></i>
                                                <span class="popover-text">Productos: {{ $venta->descuento_productos }}
                                                    Bs
                                                    <br>
                                                    Manual: {{ $venta->descuento_manual }} Bs
                                                </span>
                                            </span>
                                        @endif
                                    </td>

                                    @if ($venta->a_favor_cliente === 1)
                                        <td><span
                                                class="text-info popover-container">{{ floatval($venta->saldo_monto) }}
                                                Bs <i class="flaticon-003-arrow-up"></i><span class="popover-text">A
                                                    favor del cliente</span></span>
                                        </td>
                                    @elseif ($venta->a_favor_cliente === 0)
                                        <td><span
                                                class="text-warning popover-container">{{ floatval($venta->saldo_monto) }}
                                                Bs <i class="flaticon-001-arrow-down"></i><span class="popover-text">A
                                                    deuda del cliente</span></span>
                                        </td>
                                    @elseif ($venta->a_favor_cliente === null)
                                        <td><span class="text-dark">{{ floatval($venta->saldo_monto) }} Bs</span></td>
                                    @else
                                        <td><span class="text-muted">{{ floatval($venta->saldo_monto) }} Bs</span></td>
                                    @endif
                                    <td><strong> {{ floatval($venta->total_pagado) }} Bs</strong></td>
                                    <td><span class="badge badge-xxs badge-info py-0 px-1 m-0"><i
                                                class="fa fa-list"></i></span></td>
                                </tr>
                            @endforeach
                            <tr class="letra14">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>{{ $totalIngreso }} Bs</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-3 ps-1">
            <div class="card p-0 bordeado">
                <div class="card-header">
                    <strong>Resumen general</strong>
                </div>
                <div class="card-body p-1">
                    <div class="media event-card p-1 px-2 rounded align-items-center m-1">
                        <i class="flaticon-017-clipboard fs-30 me-3"></i>
                        <div class="media-body event-size">
                            <span class="fs-14 d-block mb-1 text-primary">Total ingreso</span>
                            <span class="fs-18 font-w500 event-size-1">{{ $totalIngreso }} Bs
                                <span>({{ $totalIngreso - $totalSaldo }} +{{ $totalSaldo }})</span></span>
                        </div>
                    </div>
                    <div class="media event-card p-1 px-2 rounded align-items-center m-1">
                        <i class="flaticon-381-id-card fs-30 me-3"></i>
                        <div class="media-body event-size">
                            <span class="fs-14 d-block mb-1 text-primary">Metodos de pago</span>
                            @foreach ($acumuladoPorMetodoPago as $metodo => $monto)
                                <span class="fs-18 font-w500 event-size-1 letra12">{{ $metodo }} :
                                    {{ floatval($monto) }} Bs</span><br>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-xl-4 col-lg-12 col-xxl-6 col-sm-12">
        <div class="row m-0 p-0" style="">
            <div class="card m-0 p-0">

                <div class="card-header">
                    Listado de cajas
                </div>
                <div class="card-body">
                    @foreach ($cajas as $caja)
                        <div class="media pb-3 border-bottom mb-3 align-items-center">
                            <a href="#" wire:click="buscarCaja(877)">
                                <div class="media-image me-2">
                                    <img src="{{ asset('logodelight.png') }}" alt="">
                                </div>
                            </a>
                            <div class="media-body"><a href="#" wire:click="buscarCaja({{ $caja->id }})">
                                    <h6 class="fs-16 mb-0">{{ $caja->created_at->format('d-M-Y') }} <span
                                            class="badge badge-outline-primary badge-xxs letra10 py-1">{{ $caja->sucursale->nombre }}</span>
                                    </h6>
                                </a>
                                <div class="d-flex"><a href="#" wire:click="buscarCaja({{ $caja->id }})">
                                    </a><a href="#" wire:click="buscarCaja({{ $caja->id }})"
                                        class="fs-14 me-auto text-secondary"><span
                                            class="fs-14 me-auto text-secondary"><i class="fa fa-ticket me-1"></i>Ver
                                            Detalle</span></a>
                                    <strong class="fs-14 text-nowrap ">{{ floatval($caja->acumulado) }} Bs
                                    </strong>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
                {{ $cajas->links() }}
            </div>
        </div>
    </div>
@endif
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
@endpush
