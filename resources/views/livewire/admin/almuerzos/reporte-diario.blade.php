<div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-9 col-md-3 col-lg-3 mb-2">
                    <h4 class="card-title">Fecha {{ date_format(date_create($fechaSeleccionada), 'd-M') }}
                        <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#exampleModal">Finalizar
                            todos</button>
                    </h4>
                </div>


                <div class="col-sm-3 col-md-3 col-lg-1 mb-2">
                    <div class="d-flex justify-content-center">
                        <div wire:loading class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3 col-lg-5">
                    <div class="input-group input-{{ $estadoColor }}">
                        <a href="#" wire:click="cambiarEstadoBuscador"
                            class="input-group-text">{{ $estadoBuscador }}</a>
                        <input type="text" class="form-control" wire:model.debounce.500ms="search">
                    </div>
                </div>


                <div class="col-sm-6 col-md-3 col-lg-3"><input type="date" class="form-control"
                        wire:model="fechaSeleccionada" wire:change="cambioDeFecha"></div>
            </div>


            {{-- <a href="#" wire:click="cambiarDisponibilidad"
                data-bs-toggle="modal" data-bs-target="#modalDisponibilidad"><span
                    class="badge badge-pill badge-primary">Cambiar Disponibilidad</span></a> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive " style="padding:5px">
                <table class="table table-responsive-sm">
                    <thead style="padding:5px">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sopa</th>

                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            <th>Ensalada</th>
                            <th>Jugo</th>
                            <th>Empaque</th>
                            <th>Envio</th>
                            <th>Plan</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody wire:loading.remove wire:target="cambioDeFecha" style="padding:5px">
                        @foreach ($coleccion as $lista)
                            <tr class="{{ $lista['ESTADO'] == 'finalizado' ? 'table-success' : '' }}{{ $lista['ESTADO'] == 'permiso' ? 'table-warning' : '' }}"
                                style="padding:5px">
                                <td style="padding:5px">
                                    @if (isset($lista['CLIENTE_INGRESADO']) && $lista['CLIENTE_INGRESADO'])
                                        <i class="fa fa-user fa-beat text-success ml-2" style="font-size: 18px;"
                                            title="Cliente ingresado"></i>
                                    @endif
                                    {{ $loop->iteration }}
                                </td>

                                <td style="padding:5px">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0)"
                                            onclick="verAlertOpciones('{{ $lista['USER_ID'] }}', '{{ $lista['PLAN_ID'] }}', '{{ $lista['ID'] }}')">
                                            <strong>{{ Str::limit($lista['NOMBRE'], 35) }}</strong>
                                        </a>

                                    </div>
                                </td>

                                <td style="padding:5px">{{ $lista['SOPA'] != '' ? 'SI' : '' }}</td>

                                <td style="padding:5px">{{ $lista['PLATO'] }}</td>
                                <td style="padding:5px">{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</td>
                                <td style="padding:5px">{{ Str::limit($lista['ENSALADA'] != '' ? 'SI' : '', 15) }}</td>
                                <td style="padding:5px">{{ sTR::limit($lista['JUGO'] != '' ? 'SI' : '', 15) }}</td>
                                <td style="padding:5px">{{ $lista['EMPAQUE'] }}</td>
                                <td style="padding:5px">{{ $lista['ENVIO'] }}</td>
                                <td style="padding:5px">{{ Str::limit($lista['PLAN'], 20) }}</td>
                                @if ($lista['ESTADO'] == 'pendiente')
                                    <td style="padding:5px"><button wire:click="cambiarEstado('{{ $lista['ID'] }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="cambiarEstado('{{ $lista['ID'] }}')"
                                            class="btn btn-info btn-sm">Pendiente</button></td>
                                @elseif($lista['ESTADO'] == 'finalizado')
                                    <td style="padding:5px"><button
                                            wire:click="cambiarAPendiente('{{ $lista['ID'] }}')"
                                            class="btn btn-success btn-sm">Finalizado</button></td>
                                @elseif($lista['ESTADO'] == 'permiso')
                                    <td style="padding:5px"><button class="btn btn-warning btn-sm"
                                            disabled>Permiso</button>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>

                            @foreach ($total[0] as $producto => $array)
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
                    <a href="#" wire:click="exportarexcel" class="badge badge-success pill light">Exportar</a>
                </div>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="modalDisponibilidad">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menu de Hoy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    @isset($menuHoy)
                        <div class="row m-2">
                            <div class="col"><span class="badge badge-pill badge-primary">{{ $menuHoy->ejecutivo }}
                                </span>
                            </div>
                            <a href="#" wire:click="cambiarEstadoPlato('ejecutivo_estado')" class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->ejecutivo_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->ejecutivo_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            <div class="col"> <span class="badge badge-pill badge-primary">{{ $menuHoy->dieta }} </span>
                            </div>
                            <a href="#" wire:click="cambiarEstadoPlato('dieta_estado')" class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->dieta_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->dieta_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            <div class="col"><span class="badge badge-pill badge-primary">{{ $menuHoy->vegetariano }}
                                </span>
                            </div>
                            <a href="#" wire:click="cambiarEstadoPlato('vegetariano_estado')" class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->vegetariano_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->vegetariano_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            <div class="col"><span class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_1 }}
                                </span></div>
                            <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_1_estado')"
                                class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->carbohidrato_1_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_1_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            <div class="col"><span
                                    class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_2 }}
                                </span></div>
                            <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_2_estado')"
                                class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->carbohidrato_2_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_2_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            <div class="col"><span
                                    class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_3 }}
                                </span></div>
                            <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_3_estado')"
                                class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->carbohidrato_3_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_3_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                    @endisset
                </div>

            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Esta seguro?</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Los usuarios ya no podran registrar sus planes para el dia
                    {{ date_format(date_create($fechaSeleccionada), 'd-M') }}
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-primary" wire:click="finalizarTodos">Confirmar</button>
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
            font-size: 12px !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function verAlertOpciones(userId, planId, itemId) {
            Swal.fire({
                title: '¿Qué deseas hacer?',
                html: '<p style="font-size: 14px;">Selecciona una opción para este cliente</p>',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-check"></i> Marcar como ingresado',
                denyButtonText: '<i class="fa fa-clipboard"></i> Ver planes',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                denyButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                customClass: {
                    confirmButton: 'btn btn-success btn-sm px-3',
                    denyButton: 'btn btn-primary btn-sm px-3',
                    cancelButton: 'btn btn-secondary btn-sm px-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Marcar como ingresado
                    Livewire.emit('marcarComoIngresado', itemId);
                } else if (result.isDenied) {
                    // Redirigir a la URL de planes del usuario
                    window.location.href = `/admin/usuarios/detalleplan/${userId}/${planId}`;
                }
            });
        }
    </script>
@endpush
