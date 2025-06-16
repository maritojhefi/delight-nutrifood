<div class="row">

    @empty($cajaactiva)
        <div class="col-xl-4 col-lg-12 col-xxl-6 col-sm-12">
            <div class="row" style="margin: 0px">
                <div class="card">
                    <div class="card">
                        <div class="card-header">
                            Listado de cajas
                        </div>
                        <div class="card-body">
                            @foreach ($cajas as $item)
                                <div class="media pb-3 border-bottom mb-3 align-items-center">
                                    <a href="#" wire:click='buscarCaja({{ $item->id }})'>
                                        <div class="media-image me-2">
                                            <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}" alt="">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="fs-16 mb-0">{{ $item->created_at->format('d-M-Y') }} <span
                                                    class="badge badge-primary badge-xxs">{{ $item->sucursale->nombre }}</span>
                                            </h6>
                                            <div class="d-flex">
                                                <a href="#" wire:click='buscarCaja({{ $item->id }})'
                                                    class="fs-14 me-auto text-secondary"><span
                                                        class="fs-14 me-auto text-secondary"><i
                                                            class="fa fa-ticket me-1"></i>Ver Detalle</span></a>
                                                <span
                                                    class="fs-14 text-nowrap ">{{ $item->ventas->sum('total') - $item->ventas->sum('saldo') - $item->ventas->sum('descuento') }}
                                                    Bs</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{ $cajas->links() }}
                </div>
            </div>
        </div>


    @endempty

    @isset($cajaactiva)
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
                @if (!$imprimiendo)
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
                                                            <td>{{ Str::words($item->cliente->name, 1, '') }} <a
                                                                    href="#"
                                                                    wire:click="modoImpresion({{ $item->id }})"
                                                                    class="badge badge-xs badge-dark"><i
                                                                        class="fa fa-print"></i></a></td>
                                                        @else
                                                            <td>S/N <a href="#"
                                                                    wire:click="modoImpresion({{ $item->id }})"
                                                                    class="badge badge-xs badge-dark"><i
                                                                        class="fa fa-print"></i></a> </td>
                                                        @endif

                                                        <td>
                                                            @php
                                                                // $metodos = ['Efectivo' => 'efectivo', 'Bisa' => 'banco-bisa', 'Sol' => 'banco-sol', 'Mercantil' => 'banco-mercantil', 'Tarjeta' => 'tarjeta'];
                                                                $metodos = [
                                                                    'Efectivo' => 'efectivo',
                                                                    'BNB' => 'banco-bnb',
                                                                    'Tarjeta' => 'tarjeta',
                                                                ];
                                                            @endphp

                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-warning light sharp"
                                                                    data-bs-toggle="dropdown">
                                                                    {{ $item->tipo }}
                                                                </button>
                                                                <a class="dropdown-menu" href="#">
                                                                    @foreach ($metodos as $titulo => $valor)
                                                                        <small class="m-1"
                                                                            style="font-size:12px important!"
                                                                            wire:click="cambiarMetodo('{{ $item->id }}','{{ $valor }}')">{{ $titulo }}</small><br>
                                                                    @endforeach
                                                                </a>
                                                            </div>
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
                                                                            <rect x="0" y="0" width="24" height="24">
                                                                            </rect>
                                                                            <circle fill="#000000" cx="5"
                                                                                cy="12" r="2"></circle>
                                                                            <circle fill="#000000" cx="12"
                                                                                cy="12" r="2"></circle>
                                                                            <circle fill="#000000" cx="19"
                                                                                cy="12" r="2"></circle>
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

                                                    <!-- Modal -->
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
                                                        <div class="row">
                                                            <div class="col-6">
                                                                {{ $item->sum('total') - $item->sum('descuento') - $item->sum('saldo') }}
                                                                Bs</div>
                                                            <div class="col-6">
                                                                <div class="dropdown">
                                                                    <button type="button"
                                                                        class="btn btn-success light sharp"
                                                                        data-bs-toggle="dropdown">
                                                                        <i class="fa fa-list"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        @foreach ($item->groupBy('tipo') as $prod)
                                                                            {{ $prod->sum('total') - $prod->sum('descuento') - $prod->sum('saldo') }} Bs
                                                                            <strong>{{ $prod[0]->tipo }}</strong><br>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
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
                @else
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
                                    <input type="date" class="form-control form-control-sm" wire:model="fechaRecibo">
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
                                <input type="checkbox" class="form-check-input" wire:model="checkClientePersonalizado">
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
                                <input type="checkbox" class="form-check-input" wire:model="checkTelefonoPersonalizado">
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
                            <button class="btn btn-warning btn-sm" wire:click="atras">Atras</button>
                            <button class="btn btn-info btn-sm" wire:click="descargarPDF">Descargar PDF <i
                                    class="fa fa-file"></i></button>
                            <button class="btn btn-success btn-sm" wire:click="imprimir">Imprimir</button>
                        </div>
                    </div>
                @endif

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
                                            class="mb-0">{{ $saldosHoy->where('tipo', 'efectivo')->sum('monto') + $ventasHoy->where('tipo', 'efectivo')->sum('total') - $ventasHoy->where('tipo', 'efectivo')->sum('saldo') - $ventasHoy->where('tipo', 'efectivo')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Tarjeta</strong>
                                        <span
                                            class="mb-0">{{ $saldosHoy->where('tipo', 'tarjeta')->sum('monto') + $ventasHoy->where('tipo', 'tarjeta')->sum('total') - $ventasHoy->where('tipo', 'tarjeta')->sum('saldo') - $ventasHoy->where('tipo', 'tarjeta')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Banco Bisa</strong>
                                        <span
                                            class="mb-0">{{ $saldosHoy->where('tipo', 'banco-bisa')->sum('monto') + $ventasHoy->where('tipo', 'banco-bisa')->sum('total') - $ventasHoy->where('tipo', 'banco-bisa')->sum('saldo') - $ventasHoy->where('tipo', 'banco-bisa')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Banco Mercantil</strong>
                                        <span
                                            class="mb-0">{{ $saldosHoy->where('tipo', 'banco-mercantil')->sum('monto') + $ventasHoy->where('tipo', 'banco-mercantil')->sum('total') - $ventasHoy->where('tipo', 'banco-mercantil')->sum('saldo') - $ventasHoy->where('tipo', 'banco-mercantil')->sum('descuento') }}
                                            Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Banco Sol</strong>
                                        <span
                                            class="mb-0">{{ $saldosHoy->where('tipo', 'banco-sol')->sum('monto') + $ventasHoy->where('tipo', 'banco-sol')->sum('total') - $ventasHoy->where('tipo', 'banco-sol')->sum('saldo') - $ventasHoy->where('tipo', 'banco-sol')->sum('descuento') }}
                                            Bs</span>
                                    </li>

                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong>Banco BNB</strong>
                                        <span
                                            class="mb-0">{{ $saldosHoy->where('tipo', 'banco-bnb')->sum('monto') + $ventasHoy->where('tipo', 'banco-bnb')->sum('total') - $ventasHoy->where('tipo', 'banco-bnb')->sum('saldo') - $ventasHoy->where('tipo', 'banco-bnb')->sum('descuento') }}
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
    @endisset

    @isset($cajaactiva)
        <div class="modal fade" id="modalSaldos" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4>Reporte de saldos para esta caja ({{ $balanceSaldo }} Bs)</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><strong>Usuario</strong></th>
                                        <th><strong>Monto</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Cajero</strong></th>
                                        <th><strong>Metodo</strong></th>
                                        <th><strong>Detalle</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cajaactiva->saldos as $item)
                                        <tr>
                                            <td>{{ Str::limit($item->usuario->name, 25) }}</td>
                                            <td>{{ $item->monto }} Bs</td>
                                            <td class="{{ $item->es_deuda ? 'text-danger' : 'text-success' }}">
                                                {{ $item->es_deuda ? 'DEUDA' : 'A FAVOR DE CLIENTE' }}</td>
                                            <td>{{ Str::limit($item->atendidoPor->name, 25) }}</td>
                                            <td>{{ Str::limit($item->tipo, 25) }}</td>
                                            <td>{{ Str::limit($item->detalle, 30) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th>TOTAL</th>
                                        <th>{{ $balanceSaldo }} Bs</th>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endisset
</div>
