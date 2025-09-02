<div class="row">
    @if ($cajaSeleccionada)

        <div class="col-12 col-md-9 p-0 ps-3">
            <div class="col-12 pe-1">
                <div class="card p-0 bordeado">
                    <div class="card-header py-2">
                        <strong>Listado de ventas <a href="#" wire:click="cambiarCaja"
                                class="badge badge-sm badge-warning">Cambiar caja <i
                                    class="flaticon-075-reload"></i></a></strong>
                        <div class="dropdown ms-auto">
                            Filtrar por:
                            <a href="#" class="mx-2 badge badge-xs light badge-info p-1 py-0 letra14"
                                data-bs-toggle="dropdown" aria-expanded="false"> <span>
                                    {{ $cajeroSeleccionado ? Str::limit($cajeroSeleccionado->name, 15) : 'Todos' }}
                                    <i class="flaticon-075-reload"></i></span></a>
                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                <li class="dropdown-item" wire:click="resetCajero()">
                                    <a href="javascript:void(0);">
                                        Todos
                                    </a>
                                </li>
                                @foreach ($cajeros as $cajero)
                                    <li class="dropdown-item" wire:click="seleccionarCajero({{ $cajero->id }})">
                                        <a href="javascript:void(0);">
                                            {{ $cajero->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
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
                                                                class="popover-text">{{ $venta->saldoSituacion() }}</span></span>
                                                    </td>
                                                @elseif ($venta->a_favor_cliente === 0)
                                                    <td class="p-0"><span
                                                            class="text-warning popover-container">{{ floatval($venta->saldo_monto) }}
                                                            Bs <i class="flaticon-001-arrow-down"></i><span
                                                                class="popover-text">{{ $venta->saldoSituacion() }}</span></span>
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
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mb-3">
                                <div class="table-responsive">
                                    <div style="max-height: 450px !important; overflow-y: auto;">
                                        <table class="table p-0 m-0 letra12">
                                            <thead>
                                                <tr>
                                                    <th class="text-white bg-primary">Producto</th>
                                                    <th class="text-white bg-primary">Cant.</th>
                                                    <th class="text-white bg-primary">Monto Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cantidadProductosVendidos = 0;
                                                    $montoTotalProductosVendidos = 0;
                                                @endphp
                                                @foreach ($cajaSeleccionada->arrayProductosVendidos($cajeroSeleccionado ? $cajeroSeleccionado->id : null) as $pro)
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
                                                    @php
                                                        $cantidadProductosVendidos += $pro->cantidad_total;
                                                        $montoTotalProductosVendidos += $pro->suma_total;
                                                    @endphp
                                                @endforeach
                                                <tr style="background-color: #20c99745; font-weight: bold;">
                                                    <td class="py-1">
                                                        <strong>Total</strong>
                                                    </td>
                                                    <td class="py-1">
                                                        <strong>{{ $cantidadProductosVendidos }}</strong>
                                                    </td>
                                                    <td class="py-1">
                                                        <strong>{{ $montoTotalProductosVendidos }} Bs</strong>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <div class="row" style="height: 400px; overflow: hidden;" id="contenedor-grafico">
                                    <!-- Establecemos el tamaño del contenedor y nos aseguramos de que la imagen no se salga -->
                                    <img src="{{ $cajaSeleccionada->urlGraficoProductosVendidos($cajeroSeleccionado ? $cajeroSeleccionado->id : null) }}" class="img-graficos"
                                        style="width: 100%; height: 95%; object-fit: cover;" alt="">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- NUEVA SECCIÓN: Ventas por Categoría -->
            <div class="col-12 pe-1">
                <div class="card p-0 bordeado">
                    <div class="card-header py-2">
                        <strong>Ventas por Categoría</strong>
                        <strong>{{ App\Helpers\GlobalHelper::fechaFormateada(2, $cajaSeleccionada->created_at) }}</strong>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mb-3">
                                <div class="table-responsive">
                                    <div style="max-height: 450px !important; overflow-y: auto;">
                                        <table class="table p-0 m-0 letra12">
                                            <thead>
                                                <tr>
                                                    <th class="text-white bg-primary">Categoría</th>
                                                    <th class="text-white bg-primary">Cant.</th>
                                                    <th class="text-white bg-primary">Monto Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cantidadVentasPorCategoria = 0;
                                                    $montoTotalVentasPorCategoria = 0;
                                                @endphp
                                                @foreach ($cajaSeleccionada->arrayVentasPorCategoria($cajeroSeleccionado ? $cajeroSeleccionado->id : null) as $cat)
                                                    <tr>
                                                        <td class="py-1">
                                                            <span class="float-start">
                                                                <i class="fa fa-stop"
                                                                    style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion($loop->index) }}"></i>
                                                                <strong>{{ $cat->nombre_categoria }}</strong>
                                                            </span>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ $cat->cantidad_total }}</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ $cat->suma_total }} Bs</strong>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $cantidadVentasPorCategoria += $cat->cantidad_total;
                                                        $montoTotalVentasPorCategoria += $cat->suma_total;
                                                    @endphp
                                                @endforeach
                                                <tr style="background-color: #20c99745; font-weight: bold;">
                                                    <td class="py-1">
                                                        <strong>Total</strong>
                                                    </td>
                                                    <td class="py-1">
                                                        <strong>{{ $cantidadVentasPorCategoria }}</strong>
                                                    </td>
                                                    <td class="py-1">
                                                        <strong>{{ $montoTotalVentasPorCategoria }} Bs</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <div class="row" style="height: 400px; overflow: hidden;" id="contenedor-grafico">
                                    <!-- Gráfico de ventas por categoría generado por QuickChart -->
                                    <img src="{{ $cajaSeleccionada->urlGraficoVentasPorCategoria($cajeroSeleccionado ? $cajeroSeleccionado->id : null) }}" class="img-graficos"
                                        style="width: 100%; height: 95%; object-fit: cover;"
                                        alt="Gráfico de ventas por categoría">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12 col-md-3 p-0 pe-3">
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
                                <span class="fs-14 d-block mb-1 text-primary">Ingresos por metodo</span>
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
                        <div class="media event-card p-1 px-2 rounded align-items-center m-1">
                            <i class="fa fa-user fs-30 me-3"></i>
                            <div class="media-body event-size">
                                <span class="fs-14 d-block mb-1 text-primary">Ingresos por cajero</span>
                                @foreach ($acumuladoPorCajero as $id => $data)
                                    <strong>{{ floatval($data['monto']) }} Bs</strong><span class="letra12">
                                        ({{ $data['nombre'] }})
                                    </span><br>
                                @endforeach

                            </div>
                        </div>
                        <div class="row" style="height: 250px; overflow: hidden;" id="contenedor-grafico">
                            <!-- Establecemos el tamaño del contenedor y nos aseguramos de que la imagen no se salga -->
                            <img src="{{ $cajaSeleccionada->generarGraficoIngresosPorCajero() }}"
                                style="width: 100%; height: 100%; object-fit: cover;" alt="" class="img-graficos">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- SECCIÓN TEMPORAL DE DEPURACIÓN - ELIMINAR DESPUÉS DE VERIFICAR -->
        <div class="col-12 pe-1">
            <div class="card p-0 bordeado">
                <div class="card-header py-2">
                    <strong>🔍 DEPURACIÓN - Verificar Totales</strong>
                </div>
                <div class="card-body py-2">
                    @php
                        $debug = $cajaSeleccionada->debugTotalesGrafico($cajeroSeleccionado ? $cajeroSeleccionado->id : null);
                    @endphp
                    <div class="row">
                        <div class="col-6">
                            <h6>Totales del Sistema (CORRECTOS):</h6>
                            <ul class="list-unstyled">
                                <li><strong>Total Ingreso Absoluto:</strong> {{ number_format($debug['totalIngresoAbsoluto'], 2) }} Bs</li>
                                <li><strong>Ingreso POS:</strong> {{ number_format($debug['totalIngresoPOS'], 2) }} Bs</li>
                                <li><strong>Saldos Pagados:</strong> {{ number_format($debug['totalSaldosPagados'], 2) }} Bs</li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <h6>Totales del Gráfico:</h6>
                            <ul class="list-unstyled">
                                <li><strong>Total Categorías:</strong> {{ number_format($debug['totalGraficoCategorias'], 2) }} Bs</li>
                                <li><strong>Total Productos:</strong> {{ number_format($debug['totalGraficoProductos'], 2) }} Bs</li>
                                <li><strong>Diferencia Categorías:</strong> <span class="badge badge-{{ $debug['diferenciaCategorias'] == 0 ? 'success' : 'danger' }}">{{ number_format($debug['diferenciaCategorias'], 2) }} Bs</span></li>
                                <li><strong>Diferencia Productos:</strong> <span class="badge badge-{{ $debug['diferenciaProductos'] == 0 ? 'success' : 'danger' }}">{{ number_format($debug['diferenciaProductos'], 2) }} Bs</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <h6>Análisis de Errores:</h6>
                            <ul class="list-unstyled">
                                <li><strong>Error Categorías:</strong> {{ number_format($debug['porcentajeErrorCategorias'], 2) }}%</li>
                                <li><strong>Error Productos:</strong> {{ number_format($debug['porcentajeErrorProductos'], 2) }}%</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        @include('livewire.admin.caja.includes.modal-detalle-venta')
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
                                            <li><span class="float-start"><i class="fa fa-stop"
                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion(0) }}"></i>
                                                    Ingresos Ventas:</span> <br>
                                                <strong class="">{{ $caja->ingresoVentasPOS() }} Bs</strong>
                                            </li>
                                            <li><span class="float-start"><i class="fa fa-stop"
                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion(1) }}"></i>
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
                                    <div class="col-6" style="height: 200px; overflow: hidden;" id="contenedor-grafico">
                                        <!-- Establecemos el tamaño del contenedor y nos aseguramos de que la imagen no se salga -->
                                        <img src="{{ $caja->urlGraficoComposicionIngresos() }}" class="img-graficos"
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
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('imprimir-recibo-local', (rawbytes) => {
                const decodedBytes = Uint8Array.from(atob(rawbytes), c => c.charCodeAt(0));

                if (!qz.websocket.isActive()) {
                    qz.websocket.connect().then(() => {
                        iniciarImpresion(decodedBytes);
                    }).catch((err) => {
                        console.error("Error al conectar con QZ Tray:", err);
                    });
                } else {
                    iniciarImpresion(decodedBytes);
                }
            });
        });

        function iniciarImpresion(decodedBytes) {
            qz.printers.find().then(printers => {
                const impresora58 = printers.find(nombre => nombre.toLowerCase().includes('58'));
                if (!impresora58) {
                    Toast.fire({
                        title: "No se encontró ninguna impresora conectada.",
                        icon: "error"
                    });
                    return; // IMPORTANTE: evitar seguir si no hay impresora
                }
                const config = qz.configs.create(impresora58);
                Toast.fire({
                    title: "Impresora seleccionada no activa, imprimiendo en la impresora local.",
                    icon: "success"
                });
                return qz.print(config, [{
                    type: 'raw',
                    format: 'hex',
                    data: bytesToHex(decodedBytes)
                }]);
            }).catch(err => {
                console.error('Error al imprimir:', err);
            });
        }

        function bytesToHex(bytes) {
            return Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
        }
    </script>
@endpush