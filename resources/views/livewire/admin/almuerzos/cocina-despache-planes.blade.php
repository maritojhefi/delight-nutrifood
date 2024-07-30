<div>
    @livewire('admin.pedidos-realtime-component')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-6">
                    <a href="#" wire:click="cambiarDisponibilidad" data-bs-toggle="modal"
                        data-bs-target="#modalDisponibilidad">
                        <span class="badge badge-pill badge-primary">Disponibilidad</span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <span>Fecha {{ date_format(date_create($fechaSeleccionada), 'd-M') }} </span>
                </div>
                <div class="col-sm-4">
                    <h4>Planes por despachar</h4>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6 col-md-3 col-lg-5">
                            <div class="input-group input-{{ $estadoColor }}">
                                <a href="#" wire:click="cambiarEstadoBuscador"
                                    class="input-group-text">{{ $estadoBuscador }}</a>
                                <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-4"><input type="date" class="form-control"
                                wire:model="fechaSeleccionada" wire:change="cambioDeFecha"></div>
                    </div>


                </div>
                <div class="d-flex justify-content-center">
                    <div wire:loading class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-12">
                <select class="form-control text-center bg-info text-white">
                    <option value="">asdasdsa</option>
                </select>
            </div>
           
        </div> --}}
        <div class="">
            <div class="table-responsive" style="">
                <table class="table table-responsive-sm" style="table-layout: auto;">
                    <thead style="">
                        <tr>
                            <td></td>
                            <td></td>
                            @php
                                $colores = ['warning', 'success', 'danger', 'primary', 'secondary', 'info', 'dark'];
                                $totalColores = count($colores);
                                $cont = 1;
                                $contColor = 0;
                            @endphp
                            @foreach ($totalEspera[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                @php
                                                    // Calcular el índice del color basado en la iteración
                                                    $colorIndex = $contColor % $totalColores;
                                                    $color = $colores[$colorIndex];
                                                @endphp
                                                @if ($cont == 1)
                                                    <small
                                                        class="text-{{ $color }}">{{ $cantidad }}</small><br>
                                                @elseif ($cont == 4)
                                                    <strong
                                                        class="text-{{ $color }}">{{ Str::limit($nombre, 8) }}:{{ $cantidad }}</strong><br>
                                                @else
                                                    <strong
                                                        class="text-{{ $color }}">{{ Str::limit($nombre, 15) }}:{{ $cantidad }}</strong><br>
                                                @endif
                                            @endif
                                            @php
                                                $contColor++;
                                            @endphp
                                        @endforeach
                                    </small></th>
                                @php
                                    $cont++;
                                @endphp
                            @endforeach

                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sopa</th>
                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            <th>Empaque</th>
                            <th>Envio</th>
                            <th>Plan</th>
                        </tr>
                    </thead>
                    <tbody style="">
                        @foreach ($coleccion->whereIn('COCINA', ['espera', 'solo-sopa', 'solo-segundo']) as $lista)
                            <tr class="
                                @if ($lista['ENVIO'] == 'a.- Delivery') table-primary
                                @elseif($lista['ENVIO'] == 'b.- Para llevar(Paso a recoger)') table-info
                                @elseif($lista['ENVIO'] == 'c.- Para Mesa') table-success @endif"
                                style="">
                                <td style="">{!! $lista['ESTADO'] == 'permiso'
                                    ? '<a href="javascript:void(0)" class="badge badge-rounded badge-outline-primary">PERMISO</a>'
                                    : $loop->iteration !!}</td>
                                <td style=";"><small>
                                        @if ($lista['ESTADO'] == 'permiso')
                                            <del>{{ Str::limit($lista['NOMBRE'], 25) }}</del>
                                        @else
                                            <a href="#" data-toggle="modal"
                                                data-target="#modalCocina{{ $lista['ID'] }}">{{ Str::limit($lista['NOMBRE'], 25) }}</a>
                                        @endif

                                    </small>
                                </td>
                                @if ($lista['COCINA'] == 'solo-sopa')
                                    <td style=""><small><a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1"><i
                                                    class="fa fa-check"></i></a> </small></td>
                                @else
                                    <td style=""><small>{{ $lista['SOPA'] != '' ? 'SI' : '' }}</small></td>
                                @endif

                                @if ($lista['COCINA'] == 'solo-segundo')
                                    <td><small><a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1"><i
                                                    class="fa fa-check"></i></a> </small></td>
                                @else
                                    <td style=""><small>{{ $lista['PLATO'] }}</small></td>
                                @endif

                                <td style=""><small>{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</small>
                                </td>
                                <td style=""><small>{{ Str::limit($lista['EMPAQUE'], 15) }}</small></td>
                                <td style=""><small>{{ Str::limit($lista['ENVIO'], 15) }}</small></td>
                                <td><small>{{ Str::limit($lista['PLAN'], 25) }}</small></td>
                            </tr>
                            <div wire:ignore.self class="modal fade" id="modalCocina{{ $lista['ID'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Plan de: <strong>{{ $lista['NOMBRE'] }}</strong></h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group mb-3">
                                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                                    <div>
                                                        <h6 class="my-0">{{ $lista['PLAN'] }}</h6>
                                                        <small class="text-muted">Nombre del plan</small>
                                                    </div>
                                                    <span class=""><i class="fa fa-info"></i></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                                    <div>
                                                        <h6
                                                            class="my-0 {{ $lista['SOPA'] != '' ? '' : 'text-danger' }}">
                                                            {{ $lista['SOPA'] != '' ? $lista['SOPA'] : 'SIN SOPA' }}
                                                        </h6>
                                                        <small class="text-muted">Sopa</small>
                                                    </div>
                                                    @if ($lista['SOPA'] != '')
                                                        <span class="">
                                                            @if ($lista['COCINA'] == 'espera' || $lista['COCINA'] == 'solo-segundo')
                                                                <a href="#"
                                                                    wire:click="despacharSopa({{ $lista['ID'] }})"
                                                                    data-dismiss="modal"><span
                                                                        class="badge badge-xs  badge-primary">Despachar
                                                                        sopa</span></a>
                                                            @else
                                                                <del class="text-danger"><span
                                                                        class="text-black">Despachado</span></del>
                                                            @endif
                                                        </span>
                                                    @endif

                                                </li>
                                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                                    <div>
                                                        <h6
                                                            class="my-0 {{ $lista['PLATO'] != '' ? '' : 'text-danger' }}">
                                                            {{ $lista['PLATO'] != '' ? $lista['PLATO'] : 'DESCONOCIDO' }}
                                                        </h6>
                                                        <small class="text-muted">Segundo</small>

                                                    </div>
                                                    @if ($lista['PLATO'] != '')
                                                        <span class="">
                                                            @if ($lista['COCINA'] == 'espera' || $lista['COCINA'] == 'solo-sopa')
                                                                <a href="#"
                                                                    wire:click="despacharSegundo({{ $lista['ID'] }})"
                                                                    data-dismiss="modal"><span
                                                                        class="badge badge-xs  badge-primary">
                                                                        Despachar segundo</span>
                                                                </a>
                                                            @else
                                                                <del class="text-danger"><span
                                                                        class="text-black">Despachado</span></del>
                                                            @endif
                                                        </span>
                                                    @endif
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed">
                                                    <div>
                                                        <h6
                                                            class="my-0 {{ $lista['CARBOHIDRATO'] != '' ? '' : 'text-danger' }}">
                                                            {{ $lista['CARBOHIDRATO'] != '' ? $lista['CARBOHIDRATO'] : 'SIN CARBOHIDRATO' }}
                                                        </h6>
                                                        <small class="text-muted">Carbohidrato</small>
                                                    </div>
                                                    <span class=""><i class="fa fa-utensils"></i></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span>Envio</span>
                                                    <strong>{{ $lista['ENVIO'] }}</strong>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span>Empaque</span>
                                                    <strong>{{ $lista['EMPAQUE'] }}</strong>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-xxs mx-auto"
                                                wire:click="restablecerPlan({{ $lista['ID'] }})"
                                                data-dismiss="modal">Restablecer</button>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                wire:click="confirmarDespacho({{ $lista['ID'] }})"
                                                data-dismiss="modal">Despachar todo</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                <h4>DESPACHADOS</h4>
                <table class="table table-responsive-sm" style="table-layout: auto;">
                    <thead style="">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sopa</th>
                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            <th>Empaque</th>
                            <th>Envio</th>
                        </tr>
                    </thead>
                    <tbody style="">
                        @foreach ($coleccion->where('COCINA', 'despachado') as $lista)
                            <tr class="table-danger" style="">
                                <td style="">{{ $loop->iteration }}</td>
                                <td style="">{{ Str::limit($lista['NOMBRE'], 20) }}</td>
                                <td style="">{{ $lista['SOPA'] != '' ? 'SI' : '' }}</td>
                                <td style="">{{ $lista['PLATO'] }}</td>
                                <td style="">{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</td>
                                <td style="">{{ $lista['EMPAQUE'] }}</td>
                                <td style="">{{ $lista['ENVIO'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            @php
                                $colores = ['warning', 'success', 'danger', 'primary', 'secondary', 'info', 'dark'];
                                $totalColores = count($colores);
                                $cont = 0;
                            @endphp
                            @foreach ($totalDespachado[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                @php
                                                    // Calcular el índice del color basado en la iteración
                                                    $colorIndex = $cont % $totalColores;
                                                    $color = $colores[$colorIndex];
                                                @endphp
                                                <small
                                                    class="text-{{ $color }}">{{ Str::limit($nombre, 15) }}:{{ $cantidad }}</small><br>
                                            @endif
                                            @php
                                                $cont++;
                                            @endphp
                                        @endforeach
                                    </small></th>
                            @endforeach

                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <h4>{{ $search ? 'Encontrados' : 'Planes para este dia' }} : {{ $coleccion->count() }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalDisponibilidad">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menu de Hoy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @isset($menuHoy)
                        <div>
                            <div class="row m-2">
                                <div class="col-md-2">
                                    @if ($menuHoy->ejecutivo_estado)
                                        <input type="number" wire:model.lazy="ejecutivo_cant"
                                            wire:change="cambiarCantidad('ejecutivo_cant')"
                                            class="form-control form-control-sm" style="padding: 0px; height: 12px;">
                                    @endif
                                </div>
                                <div class="col">
                                    <span class="badge badge-pill badge-primary">{{ $menuHoy->ejecutivo }}</span>
                                </div>
                                <a href="#" wire:click="cambiarEstadoPlato('ejecutivo_estado')" class="col">
                                    <span
                                        class="badge badge-pill badge-{{ $menuHoy->ejecutivo_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->ejecutivo_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                                </a>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-2">
                                    @if ($menuHoy->dieta_estado)
                                        <input type="number" wire:model.lazy="dieta_cant"
                                            wire:change="cambiarCantidad('dieta_cant')"
                                            class="form-control form-control-sm" style="padding: 0px; height: 12px;">
                                    @endif
                                </div>
                                <div class="col">
                                    <span class="badge badge-pill badge-primary">{{ $menuHoy->dieta }}</span>
                                </div>
                                <a href="#" wire:click="cambiarEstadoPlato('dieta_estado')" class="col">
                                    <span
                                        class="badge badge-pill badge-{{ $menuHoy->dieta_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->dieta_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                                </a>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-2">
                                    @if ($menuHoy->vegetariano_estado)
                                        <input type="number" wire:model.lazy="vegetariano_cant"
                                            wire:change="cambiarCantidad('vegetariano_cant')"
                                            class="form-control form-control-sm" style="padding: 0px; height: 12px;">
                                    @endif
                                </div>
                                <div class="col">
                                    <span class="badge badge-pill badge-primary">{{ $menuHoy->vegetariano }}</span>
                                </div>
                                <a href="#" wire:click="cambiarEstadoPlato('vegetariano_estado')" class="col">
                                    <span
                                        class="badge badge-pill badge-{{ $menuHoy->vegetariano_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->vegetariano_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                                </a>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-2">
                                    @if ($menuHoy->carbohidrato_1_estado)
                                        <input type="number" wire:model.lazy="carbohidrato_1_cant"
                                            wire:change="cambiarCantidad('carbohidrato_1_cant')"
                                            class="form-control form-control-sm" style="padding: 0px; height: 12px;">
                                    @endif
                                </div>
                                <div class="col">
                                    <span class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_1 }}</span>
                                </div>
                                <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_1_estado')"
                                    class="col">
                                    <span
                                        class="badge badge-pill badge-{{ $menuHoy->carbohidrato_1_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_1_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                                </a>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-2">
                                    @if ($menuHoy->carbohidrato_2_estado)
                                        <input type="number" wire:model.lazy="carbohidrato_2_cant"
                                            wire:change="cambiarCantidad('carbohidrato_2_cant')"
                                            class="form-control form-control-sm" style="padding: 0px; height: 12px;">
                                    @endif
                                </div>
                                <div class="col">
                                    <span class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_2 }}</span>
                                </div>
                                <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_2_estado')"
                                    class="col">
                                    <span
                                        class="badge badge-pill badge-{{ $menuHoy->carbohidrato_2_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_2_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                                </a>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-2">
                                    @if ($menuHoy->carbohidrato_3_estado)
                                        <input type="number" wire:model.lazy="carbohidrato_3_cant"
                                            wire:change="cambiarCantidad('carbohidrato_3_cant')"
                                            class="form-control form-control-sm" style="padding: 0px; height: 12px;">
                                    @endif
                                </div>
                                <div class="col">
                                    <span class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_3 }}</span>
                                </div>
                                <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_3_estado')"
                                    class="col">
                                    <span
                                        class="badge badge-pill badge-{{ $menuHoy->carbohidrato_3_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_3_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                                </a>
                            </div>
                        </div>

                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@push('css')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            margin: 0 !important;
            padding: 0 !important;
            border: 1px solid #ddd;
            text-align: center;
            /* Esto es opcional, solo para mostrar las celdas de la tabla */
        }

        th {
            background-color: #f2f2f2;
            /* Esto es opcional para darle un color de fondo a los encabezados */
        }

        table {
            font-size: 15px !important;
        }
    </style>
@endpush
