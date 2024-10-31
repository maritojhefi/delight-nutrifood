<div>
    @livewire('admin.pedidos-realtime-component')
    <div class="card col-12 letra12 bordeado">
        <div class="card-header">
            <div class="row ">
                <div class="col-sm-6 d-flex">
                    <a href="#" wire:click="cambiarDisponibilidad" data-bs-toggle="modal"
                        data-bs-target="#modalDisponibilidad">
                        <span class="badge badge-pill badge-primary">Disponibilidad</span>
                    </a>
                    <div class="m-1">
                        <div wire:loading class="spinner-border" style="width: 20px;height:20px" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <strong>Viendo fecha : {{ date_format(date_create($fechaSeleccionada), 'd-M') }} </strong>

                </div>
                <div class="col-sm-4">
                    <h4 class="letra12">Planes por despachar</h4>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6 col-md-3 col-lg-5">
                            <div class="input-group input-{{ $estadoColor }}">
                                <a href="#" wire:click="cambiarEstadoBuscador"
                                    class="input-group-text p-0 m-0 letra12"
                                    style="height: 30px">{{ $estadoBuscador }}</a>
                                <input type="text" class="form-control form-control-sm p-0 m-0" style="height: 30px"
                                    wire:model.debounce.500ms="search">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-4"><input type="date"
                                class="form-control bordeado p-0 m-0 ps-1" style="height: 30px"
                                wire:model="fechaSeleccionada" wire:change="cambioDeFecha"></div>
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
            <div class="table-responsive letra14" style="">
                <table class="table table-responsive-sm" style="table-layout: auto;">
                    <th style="">
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
                    </th>
                    <tbody style="">
                        @foreach ($coleccion->whereIn('COCINA', ['espera', 'solo-sopa', 'solo-segundo']) as $lista)
                            <tr class="
                                @if ($lista['ENVIO'] == 'a.- Delivery') table-primary
                                @elseif($lista['ENVIO'] == 'b.- Para llevar(Paso a recoger)') table-info
                                @elseif($lista['ENVIO'] == 'c.- Para Mesa') table-success @endif"
                                style="border-color:#211d1d !important">
                                <td style="border-color:#211d1d !important">{!! $lista['ESTADO'] == 'permiso'
                                    ? '<a href="javascript:void(0)" class="text-primary"><strong>PERMISO</strong> </a>'
                                    : $loop->iteration !!}</td>
                                <td style="border-color:#211d1d !important"><small>
                                        @if ($lista['ESTADO'] == 'permiso')
                                            <del class="text-muted">{{ Str::limit($lista['NOMBRE'], 25) }}</del>
                                        @else
                                            <a href="#" data-toggle="modal"
                                                data-target="#modalCocina{{ $lista['ID'] }}">{{ Str::limit($lista['NOMBRE'], 25) }}</a>
                                        @endif

                                    </small>
                                </td>
                                @if ($lista['COCINA'] == 'solo-sopa')
                                    <td style="border-color:#211d1d !important"><small><a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1"><i
                                                    class="fa fa-check"></i></a> </small></td>
                                @else
                                    <td style="border-color:#211d1d !important">
                                        <small>{{ $lista['SOPA'] != '' ? 'SI' : '' }}</small>
                                    </td>
                                @endif

                                @if ($lista['COCINA'] == 'solo-segundo')
                                    <td style="border-color:#211d1d !important"><small><a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1"><i
                                                    class="fa fa-check"></i></a> </small></td>
                                @else
                                    <td style="border-color:#211d1d !important"><small>{{ $lista['PLATO'] }}</small>
                                    </td>
                                @endif

                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</small>
                                </td>
                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['EMPAQUE'], 15) }}</small>
                                </td>
                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['ENVIO'], 15) }}</small>
                                </td>
                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['PLAN'], 25) }}</small>
                                </td>
                            </tr>
                            <div wire:ignore.self class="modal fade" id="modalCocina{{ $lista['ID'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content m-0 p-0">
                                        <div class="modal-header m-0 p-2">
                                            <h5 class="modal-title mx-auto">
                                                Plan de: <strong>{{ $lista['NOMBRE'] }}</strong></h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body m-0 p-0">
                                            <ul class="list-group  m-0 p-0">
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed py-2">
                                                    <div>
                                                        <small class="text-muted">Nombre del plan</small>
                                                    </div>
                                                    <span class="">
                                                        <h6 class="my-0">{{ $lista['PLAN'] }}</h6>
                                                    </span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed  py-2">
                                                    <div>
                                                        <small class="text-muted">Sopa</small>
                                                    </div>
                                                    <h6 class="my-0 {{ $lista['SOPA'] != '' ? '' : 'text-danger' }}">
                                                        {{ $lista['SOPA'] != '' ? $lista['SOPA'] : 'SIN SOPA' }}
                                                        @if ($lista['SOPA'] != '')
                                                            <span class="letra10 p-2">
                                                                @if ($lista['COCINA'] == 'espera' || $lista['COCINA'] == 'solo-segundo')
                                                                    <a href="#"
                                                                        wire:click="despacharSopa({{ $lista['ID'] }})"
                                                                        data-dismiss="modal"><span
                                                                            class="badge badge-xs  badge-primary">Despachar
                                                                        </span></a>
                                                                @else
                                                                    <del class="text-danger"><span
                                                                            class="text-black">Despachado</span></del>
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>


                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed  py-2">
                                                    <div>

                                                        <small class="text-muted">Segundo</small>

                                                    </div>
                                                    @if ($lista['PLATO'] != '')
                                                        <h6
                                                            class="my-0 {{ $lista['PLATO'] != '' ? '' : 'text-danger' }}">
                                                            {{ $lista['PLATO'] != '' ? $lista['PLATO'] : 'DESCONOCIDO' }}
                                                            <span class="letra10 p-2">
                                                                @if ($lista['COCINA'] == 'espera' || $lista['COCINA'] == 'solo-sopa')
                                                                    <a href="#"
                                                                        wire:click="despacharSegundo({{ $lista['ID'] }})"
                                                                        data-dismiss="modal"><span
                                                                            class="badge badge-xs  badge-primary">
                                                                            Despachar</span>
                                                                    </a>
                                                                @else
                                                                    <del class="text-danger"><span
                                                                            class="text-black">Despachado</span></del>
                                                                @endif
                                                            </span>
                                                        </h6>
                                                    @endif
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed  py-2">
                                                    <div>

                                                        <small class="text-muted">Carbohidrato</small>
                                                    </div>
                                                    <h6
                                                        class="my-0 {{ $lista['CARBOHIDRATO'] != '' ? '' : 'text-danger' }}">
                                                        {{ $lista['CARBOHIDRATO'] != '' ? $lista['CARBOHIDRATO'] : 'SIN CARBOHIDRATO' }}
                                                    </h6>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between  py-2">
                                                    <span>Envio</span>
                                                    <strong>{{ $lista['ENVIO'] }}</strong>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between  py-2">
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
                <hr>
                <center style="" class="letra14"><strong>DESPACHADOS</strong></center>
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
