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
                <div class="col-sm-6">
                    <h4>Planes por despachar</h4>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-6 col-md-3 col-lg-5">
                        <div class="input-group input-{{ $estadoColor }}">
                            <a href="#" wire:click="cambiarEstadoBuscador"
                                class="input-group-text">{{ $estadoBuscador }}</a>
                            <input type="text" class="form-control" wire:model.debounce.500ms="search">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div wire:loading class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="table-responsive" style="padding:5px">
                <table class="table table-responsive-sm" style="table-layout: auto;">
                    <thead style="padding:5px">
                        <tr>
                            <td></td>
                            <td></td>
                            @php
                                $colores = collect([
                                    'warning',
                                    'success',
                                    'danger',
                                    'primary',
                                    'secondary',
                                    'info',
                                    'dark',
                                ]);
                                $cont = 1;
                            @endphp
                            @foreach ($totalEspera[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                @if ($cont == 1)
                                                    <strong
                                                        class="badge badge-lg badge-{{ $colores->random() }} ">{{ $cantidad }}</strong><br>
                                                @elseif ($cont == 4)
                                                    <strong
                                                        class="badge badge-lg badge-{{ $colores->random() }} ">{{ Str::limit($nombre, 8) }}:{{ $cantidad }}</strong><br>
                                                @else
                                                    <strong
                                                        class="badge badge-lg badge-{{ $colores->random() }} ">{{ Str::limit($nombre, 15) }}:{{ $cantidad }}</strong><br>
                                                @endif
                                            @endif
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
                    <tbody style="padding:5px">
                        @foreach ($coleccion->where('COCINA', 'espera') as $lista)
                            <tr class="
                                @if ($lista['ENVIO'] == 'a.- Delivery') table-primary
                                @elseif($lista['ENVIO'] == 'b.- Para llevar(Paso a recoger)') table-info
                                @elseif($lista['ENVIO'] == 'c.- Para Mesa') table-success @endif"
                                style="padding:5px">
                                <td style="padding:5px">{!! $lista['ESTADO'] == 'permiso'
                                    ? '<a href="javascript:void(0)" class="badge badge-rounded badge-outline-primary">PERMISO</a>'
                                    : $loop->iteration !!}</td>
                                <td style="padding:5px;"><small>
                                        @if ($lista['ESTADO'] == 'permiso')
                                            <del>{{ Str::limit($lista['NOMBRE'], 25) }}</del>
                                        @else
                                            <a href="#" data-toggle="modal"
                                                data-target="#modalCocina{{ $lista['ID'] }}">{{ Str::limit($lista['NOMBRE'], 25) }}</a>
                                        @endif

                                    </small>
                                </td>
                                <td style="padding:5px"><small>{{ $lista['SOPA'] != '' ? 'SI' : '' }}</small></td>
                                <td style="padding:5px"><small>{{ $lista['PLATO'] }}</small></td>
                                <td style="padding:5px"><small>{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</small>
                                </td>
                                <td style="padding:5px"><small>{{ Str::limit($lista['EMPAQUE'], 15) }}</small></td>
                                <td style="padding:5px"><small>{{ Str::limit($lista['ENVIO'], 15) }}</small></td>
                                <td><small>{{ Str::limit($lista['PLAN'], 25) }}</small></td>
                            </tr>
                            <div wire:ignore.self class="modal fade" id="modalCocina{{ $lista['ID'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Despachando pedido para
                                                {{ $lista['NOMBRE'] }}</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary"
                                                wire:click="confirmarDespacho({{ $lista['ID'] }})"
                                                data-dismiss="modal">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                <h4>DESPACHADOS</h4>
                <table class="table table-responsive-sm" style="table-layout: auto;">
                    <thead style="padding:5px">
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
                    <tbody style="padding:5px">
                        @foreach ($coleccion->where('COCINA', 'despachado') as $lista)
                            <tr class="table-danger" style="padding:5px">
                                <td style="padding:5px">{{ $loop->iteration }}</td>
                                <td style="padding:5px">{{ Str::limit($lista['NOMBRE'], 20) }}</td>
                                <td style="padding:5px">{{ $lista['SOPA'] != '' ? 'SI' : '' }}</td>
                                <td style="padding:5px">{{ $lista['PLATO'] }}</td>
                                <td style="padding:5px">{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</td>
                                <td style="padding:5px">{{ $lista['EMPAQUE'] }}</td>
                                <td style="padding:5px">{{ $lista['ENVIO'] }}</td>
                            </tr>
                            <div wire:ignore.self class="modal fade" id="modalCocina{{ $lista['ID'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Despachando pedido para
                                                {{ $lista['NOMBRE'] }}</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary"
                                                wire:click="confirmarDespacho({{ $lista['ID'] }})"
                                                data-dismiss="modal">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            @foreach ($totalDespachado[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                <span
                                                    class="badge badge-pill badge-lg badge-{{ $colores->random() }}">{{ Str::limit($nombre, '15') }}:{{ $cantidad }}</span><br>
                                            @endif
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
                        <!-- Repeat similar blocks for other menu items -->
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
