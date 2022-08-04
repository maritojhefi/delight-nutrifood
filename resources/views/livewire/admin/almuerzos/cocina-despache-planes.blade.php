<div>
    <div class="card">
        <div class="card-header">

            <span>Fecha {{ date_format(date_create($fechaSeleccionada), 'd-M') }} </span>

            <h4>Planes por despachar</h4>
            <div class="col-sm-6 col-md-3 col-lg-5">
                <div class="input-group input-{{ $estadoColor }}">
                    <a href="#" wire:click="cambiarEstadoBuscador" class="input-group-text">{{ $estadoBuscador }}</a>
                    <input type="text" class="form-control" wire:model.debounce.500ms="search">
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <div wire:loading class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            {{-- <a href="#" wire:click="cambiarDisponibilidad"
                data-bs-toggle="modal" data-bs-target="#modalDisponibilidad"><span
                    class="badge badge-pill badge-primary">Cambiar Disponibilidad</span></a> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive " style="padding:5px">
                <table class=" table table-striped table-responsive-sm">
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
                        @foreach ($coleccion->where('COCINA', 'espera') as $lista)
                            <tr class="" style="padding:5px">

                                <td style="padding:5px">{{ $loop->iteration }}</td>

                                <td style="padding:5px"> <a href="#" data-toggle="modal"
                                        data-target="#modalCocina{{ $lista['ID'] }}">{{ Str::limit($lista['NOMBRE'], 20) }}
                                    </a>
                                </td>

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

                            @foreach ($totalEspera[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '')
                                                <span
                                                    class="badge badge-pill badge-primary light">{{ Str::limit($nombre, '15') }}:{{ $cantidad }}</span><br>
                                            @endif
                                        @endforeach
                                </th>
                            @endforeach
                            </small>

                        </tr>


                    </tbody>



                </table>
                <h4>DESPACHADOS</h4>
                <table class="table table-responsive-sm">
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

                                <td style="padding:5px"> {{ Str::limit($lista['NOMBRE'], 20) }}

                                </td>

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
                                            @if ($nombre != '')
                                                <span
                                                    class="badge badge-pill badge-primary light">{{ Str::limit($nombre, '15') }}:{{ $cantidad }}</span><br>
                                            @endif
                                        @endforeach
                                </th>
                            @endforeach
                            </small>

                        </tr>


                    </tbody>
                </table>


                <div class="d-flex justify-content-center">
                    <h4>{{ $search ? 'Encontrados' : 'Planes para este dia' }} : {{ $coleccion->count() }}</h4>
                    {{-- <a href="#" wire:click="exportarexcel" class="badge badge-success pill light">Exportar</a> --}}
                </div>
            </div>
        </div>
    </div>





</div>
