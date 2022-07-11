@php
            $balanceSaldo = $cajaactiva->saldos->where('es_deuda', false)->sum('monto');
        @endphp
        <div class="col-xl-8 col-lg-12 col-xxl-5 col-sm-12">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Caja <span
                                class="badge badge-primary">{{ $cajaactiva->created_at->format('d-M-Y') }}</span></h4>


                        <a href="#" wire:click="resetCaja"><span class="badge light badge-pill badge-danger">Cambiar
                                de caja</span></a>
                    </div>
                    <div class="card-body">
                        <div class="">

                            <div class="widget-stat card bg-primary">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalVentas">
                                    <div class="card-body  p-4">
                                        <div class="media">
                                            <span class="me-3">
                                                <i class="flaticon-381-calendar-1"></i>
                                            </span>
                                            <div class="media-body text-white text-end">
                                                <p class="mb-1">Ventas Totales</p>
                                                <h3 class="text-white">{{ $ventasHoy->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="widget-stat card">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalSaldos">
                                    <div class="card-body p-4">
                                        <div class="media ai-icon">
                                            <span class="me-3 bgl-primary text-primary">
                                                <!-- <i class="ti-user"></i> -->
                                                <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30"
                                                    height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-user">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </span>
                                            <div class="media-body">
                                                <p class="mb-1">Balance de saldos</p>
                                                <h4 class="mb-0">{{ $cajaactiva->saldos->count() }}</h4>
                                                <span class="badge badge-primary">Registros</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="widget-stat card">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetalle">
                                    <div class="card-body  p-4">
                                        <div class="media ai-icon">
                                            <span class="me-3 bgl-danger text-danger">
                                                <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg" width="30"
                                                    height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-dollar-sign">
                                                    <line x1="12" y1="1" x2="12" y2="23">
                                                    </line>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg>
                                            </span>
                                            <div class="media-body">
                                                <p class="mb-1">Ingresos</p>
                                                <h4 class="mb-0">
                                                    {{ $ventasHoy->sum('total') - $ventasHoy->sum('descuento') - $ventasHoy->sum('saldo') + $balanceSaldo }}
                                                </h4>
                                                <span class="badge badge-danger">BS</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-xxl-7 col-sm-12">
            <div class="card overflow-hidden">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Productos vendidos</h4><span class="badge badge-pill badge-primary">Total
                            items:
                            {{ $lista->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><strong>Producto</strong></th>
                                        <th><strong>Cantidad</strong></th>
                                        <th><strong>Subtotal</strong></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lista as $item)
                                        <tr>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td><span class="badge light badge-info">{{ $item['cantidad'] }}</span></td>
                                            <td><span class="badge light badge-warning">{{ $item['subtotal'] }}
                                                    Bs</span>
                                            </td>



                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div class="row  mx-auto">
                            <div class="col">

                            </div>
                            <div class="col">

                            </div>
                            <div class="col">
                                <span class="badge badge-pill badge-lg badge-info m-2">Total bruto(sin
                                    adicionales):{{ $resumen }} Bs</span>
                            </div>

                        </div>
                    </div>


                </div>

            </div>
        </div>



        <div wire:ignore.self class="modal fade" id="modalVentas" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#" wire:click="cambiarReporte"
                            class="badge badge-{{ $reporteGeneral ? 'success' : 'warning' }} light">{{ $reporteGeneral ? 'Cambia a reporte por Cajeros' : 'Cambiar a reporte general' }}</a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($reporteGeneral)
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-sm">
                                    <thead>
                                        <tr>

                                            <th>Cliente</th>

                                            <th>Metodo</th>
                                            <th>Puntos</th>
                                            <th>Subtotal</th>
                                            <th>A saldo</th>
                                            <th>Descuento</th>

                                            <th>Total Cobrado</th>
                                            <th>Detalle</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($ventasHoy)
                                            @foreach ($ventasHoy as $item)
                                                <tr>
                                                    @if ($item->cliente)
                                                        <td>{{ Str::words($item->cliente->name, 1, '') }}</td>
                                                    @else
                                                        <td>S/N</td>
                                                    @endif

                                                    <td><span class="badge badge-warning light">{{ $item->tipo }}</span>
                                                    </td>
                                                    <td>{{ $item->puntos }} pts</td>
                                                    <td class="color-primary">{{ $item->total }} Bs</td>
                                                    <td>{{ $item->saldo }} Bs</td>
                                                    <td>{{ $item->descuento }} Bs
                                                    </td>


                                                    <td class="color-primary" style="background-color: rgb(31, 224, 159)">
                                                        {{ $item->total - $item->descuento - $item->saldo }} Bs</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-success light sharp"
                                                                data-bs-toggle="dropdown">
                                                                <svg width="20px" height="20px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24"
                                                                            height="24"></rect>
                                                                        <circle fill="#000000" cx="5" cy="12"
                                                                            r="2"></circle>
                                                                        <circle fill="#000000" cx="12" cy="12"
                                                                            r="2"></circle>
                                                                        <circle fill="#000000" cx="19" cy="12"
                                                                            r="2"></circle>
                                                                    </g>
                                                                </svg>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @foreach ($item->productos as $prod)
                                                                    <small class="m-1">{{ $prod->nombre }} :
                                                                        {{ $prod->pivot->cantidad }}</small><br>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @if ($item->usuario)
                                                        <td>{{ Str::words($item->usuario->name, 1, '') }}</td>
                                                    @else
                                                        <td>S/N</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td><span class="badge badge-xs badge-info">Resumen</span></td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ $ventasHoy->sum('total') }} Bs</td>
                                                <td>{{ $ventasHoy->sum('saldo') }} Bs</td>
                                                <td>{{ $ventasHoy->sum('descuento') }} Bs</td>
                                                <td style="background-color: rgb(31, 224, 159)">
                                                    {{ $ventasHoy->sum('total') - $ventasHoy->sum('descuento') - $ventasHoy->sum('saldo') }}
                                                    Bs</td>
                                            </tr>
                                        @endisset



                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-sm">
                                    <thead>
                                        <tr>

                                            <th>Usuario</th>

                                            <th># Ventas</th>
                                            <th>Puntos</th>
                                            <th>Subtotal</th>
                                            <th>A saldo</th>
                                            <th>Descuento</th>

                                            <th>Total Cobrado</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ventasHoy->groupBy('usuario_id') as $item)
                                            <tr>
                                                <td>{{ Str::limit($item[0]->usuario->name, 15, '') }}</td>
                                                <td>{{ $item->count() }} </td>
                                                <td>{{ $item->sum('puntos') }}</td>
                                                <td>{{ $item->sum('total') }} Bs</td>
                                                <td>{{ $item->sum('saldo') }} Bs</td>
                                                <td>{{ $item->sum('descuento') }} Bs</td>

                                                <td style="background-color: rgb(31, 224, 159)">
                                                    {{ $item->sum('total') - $item->sum('descuento') - $item->sum('saldo') }}
                                                    Bs</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><span class="badge badge-xxs badge-info">Resumen</span></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $ventasHoy->sum('total') }} Bs</td>
                                            <td>{{ $ventasHoy->sum('saldo') }} Bs</td>
                                            <td>{{ $ventasHoy->sum('descuento') }} Bs</td>

                                            <td style="background-color: rgb(31, 224, 159)">
                                                {{ $ventasHoy->sum('total') - $ventasHoy->sum('descuento') - $ventasHoy->sum('saldo') }}
                                                Bs</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>

                </div>
            </div>
        </div>
        @isset($ventasHoy)
            <div class="modal fade" id="modalDetalle" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Mas Detalles de esta caja</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body pb-0">
                                <p>Ventas por cada metodo de pago</p>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>En efectivo</strong>
                                        <span
                                            class="mb-0">{{ $ventasHoy->where('tipo', 'efectivo')->sum('total') - $ventasHoy->where('tipo', 'efectivo')->sum('saldo') - $ventasHoy->where('tipo', 'efectivo')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Tarjeta</strong>
                                        <span
                                            class="mb-0">{{ $ventasHoy->where('tipo', 'tarjeta')->sum('total') - $ventasHoy->where('tipo', 'tarjeta')->sum('saldo') - $ventasHoy->where('tipo', 'tarjeta')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Banco Bisa</strong>
                                        <span
                                            class="mb-0">{{ $ventasHoy->where('tipo', 'banco-bisa')->sum('total') - $ventasHoy->where('tipo', 'banco-bisa')->sum('saldo') - $ventasHoy->where('tipo', 'banco-bisa')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Banco Mercantil</strong>
                                        <span
                                            class="mb-0">{{ $ventasHoy->where('tipo', 'banco-mercantil')->sum('total') - $ventasHoy->where('tipo', 'banco-mercantil')->sum('saldo') - $ventasHoy->where('tipo', 'banco-mercantil')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Banco Sol</strong>
                                        <span
                                            class="mb-0">{{ $ventasHoy->where('tipo', 'banco-sol')->sum('total') - $ventasHoy->where('tipo', 'banco-sol')->sum('saldo') - $ventasHoy->where('tipo', 'banco-sol')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer pt-0 pb-0 text-center">
                                <div class="row">
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1 text-primary">{{ $ventasHoy->sum('total') }} Bs</h3>
                                        <span>Total Bruto</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1 text-primary">{{ $ventasHoy->sum('descuento') }} Bs</h3>
                                        <span>Descuentos acumulados</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1 text-primary">{{ $ventasHoy->sum('saldo') }} Bs</h3>
                                        <span>Saldos acumulados</span>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1 text-primary">{{ $balanceSaldo }} Bs</h3>
                                        <span>Saldos pagados</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3 border-end">
                                    </div>
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1 text-primary">
                                            {{ $ventasHoy->sum('total') - $ventasHoy->sum('descuento') - $ventasHoy->sum('saldo') + $balanceSaldo }}
                                            Bs</h3>
                                        <span>Total con descuentos/saldos</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        @endisset