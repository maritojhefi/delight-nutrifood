<div>
    <div class="row">
        <div class="form-head mb-1 d-flex flex-wrap align-items-center">
            <div class="me-auto">
                <h2 class="font-w600 mb-0">Listado de Planes
                    <div wire:loading class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </h2>
            </div>
            <div class="input-group search-area2 d-xl-inline-flex" style="width: 40%; !important;">
                <button class="input-group-text"><i class="flaticon-381-search-2 text-primary"></i></button>
                <input type="text" class="form-control" placeholder="Buscar por nombre..."
                    wire:model.debounce.700ms="search">
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="card bordeado P-3">
                    <div class="card-body">
                        @empty($productoseleccionado)
                            <label for="" class="">Busca un producto</label>
                            <input type="text" wire:model.debounce.750ms="producto"
                                class="form-control form-control-sm p-1 bordeado mb-2" style="height: 50px">


                            <ol class="list-group list-group-numbered">
                                @foreach ($productos as $item)
                                    <li class="list-group-item list-group-item-action"
                                        wire:click="seleccionarproducto('{{ $item->id }}')" style="cursor: pointer;"><a
                                            href="#" style="font-size: 13px; line-height: 23px; font-weight: 600;"
                                            class="text-primary">{{ $item->nombre }} <i
                                                class="fa fa-check-circle fa-bounce"></i></a></li>
                                @endforeach
                            </ol>
                        @endempty

                        @isset($productoseleccionado)
                            <div class="">
                                {{-- <span
                                    class="badge light badge-lg m-3 badge-danger">{{ $productoseleccionado->nombre }}({{ $productoseleccionado->precio }}
                                    Bs)<button type="button" class="btn-close"
                                        wire:click="resetproducto()"></button></span> --}}


                                <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                                    {{ $productoseleccionado->nombre }}({{ $productoseleccionado->precio }}
                                    Bs)<button type="button" class="btn-close custom-close text-danger"
                                        data-bs-dismiss="alert" aria-label="Close" wire:click="resetproducto()"><i
                                            class="bi bi-x text-danger"></i></button> </div>

                                <div class="card-header">
                                    <h4 class="card-title">Nuevo Plan</h4>
                                </div>
                                <x-input-create :lista="[
                                    'Nombre' => ['nombre', 'text'],
                                
                                    'Detalle' => ['detalle', 'text'],
                                ]">
                                </x-input-create>
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="card bordeado p-3">
                    <div class="card-header m-0">
                        <h4 class="card-title">Planes Registrados</h4>
                    </div>
                    <div class="card-body letra12 m-0 p-0">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-responsive-md m-0 p-0 letra1">
                                    <thead class="m-2 p-3 bg-primary text-white">
                                        <tr class="m-2 p-3">
                                            <th class="m-2 p-3"><strong>Nombre</strong></th>
                                            <th class="m-2 p-3"><strong>Añadidos</strong></th>
                                            <th class="m-2 p-3"><strong>Editable</strong></th>
                                            <th class="m-2 p-3"><strong>Asignacion <br>Auto</strong></th>
                                            <th class="m-2 p-3"><strong>Horario</strong></th>
                                            <th class="m-2 p-3"><strong>Detalle</strong></th>
                                            <th class="m-2 p-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="m-0 p-0">
                                        @foreach ($planes as $item)
                                            <tr class="m-1 p-">
                                                <td class="m-1 p-1"><small
                                                        class="text-info fw-bold">{{ $item->nombre }}</small></td>
                                                <td class="m-1 p-1"><a href="#"
                                                        wire:click="seleccionarPlan({{ $item->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#modalAnadidos"
                                                        class=" badge badge-sm badge-primary"><span
                                                            class="fa fa-eye"></span>
                                                        Ver</a></td>
                                                <td class="m-1 p-1"><a href="#"
                                                        wire:click="cambiarEditable({{ $item->id }})"><span
                                                            class="badge badge-pill badge-{{ $item->editable == true ? 'success' : 'danger' }} light">{{ $item->editable == true ? 'SI' : 'NO' }}</span></a>
                                                </td>
                                                <td class="m-1 p-1"><a href="#"
                                                        wire:click="cambiarAsignacion({{ $item->id }})"><span
                                                            class="badge badge-pill badge-{{ $item->asignado_automatico == true ? 'success' : 'danger' }} light">{{ $item->asignado_automatico == true ? 'SI' : 'NO' }}</span></a>
                                                </td>
                                                <td class="m-1 p-1">
                                                    @if ($item->horario)
                                                        <span
                                                            class="badge badge-info badge-sm">{{ $item->horario->nombre }}
                                                            <br>
                                                            <small>({{ $item->horario->hora_inicio }} -
                                                                {{ $item->horario->hora_fin }})</small>
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary badge-sm">Sin horario</span>
                                                    @endif
                                                </td>
                                                <td class="m-1 p-1">{{ $item->detalle }}</td>
                                                <td class="m-1 p-1">
                                                    @if ($item->horario)
                                                        <button class="btn btn-warning btn-sm"
                                                            wire:click="agregarHorario({{ $item->id }})">
                                                            Editar <i class="fa fa-clock-o"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-info btn-sm"
                                                            wire:click="agregarHorario({{ $item->id }})">
                                                            Agregar <i class="fa fa-clock-o"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="row  mx-auto mt-2">
                        <div class="col">{{ $planes->links() }}</div>
                    </div>
                    <div class="row  mx-auto">
                        <div class="col">Mostrando {{ $planes->count() }} de {{ $planes->total() }} registros
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div wire:ignore.self class="modal fade" id="modalAnadidos" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">

                @isset($planSeleccionado)
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Habilita/deshabilita caracteristicas para: {{ $planSeleccionado->nombre }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div wire:loading class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div class="basic-list-group" wire:loading.remove>
                                <ul class="list-group">
                                    <a href="#" wire:click="cambiarSopa()">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Sopa <span
                                                class="badge badge-{{ $planSeleccionado->sopa ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->sopa ? 'SI' : 'NO' }}</span>
                                        </li>
                                    </a>
                                    <a href="#" wire:click="cambiarSegundo()">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Segundo <span
                                                class="badge badge-{{ $planSeleccionado->segundo ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->segundo ? 'SI' : 'NO' }}</span>
                                        </li>
                                    </a>
                                    <a href="#" wire:click="cambiarCarbohidrato()">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Carbohidrato <span
                                                class="badge badge-{{ $planSeleccionado->carbohidrato ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->carbohidrato ? 'SI' : 'NO' }}</span>
                                        </li>
                                    </a>
                                    <a href="#" wire:click="cambiarEnsalada()">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Ensalada <span
                                                class="badge badge-{{ $planSeleccionado->ensalada ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->ensalada ? 'SI' : 'NO' }}</span>
                                        </li>
                                    </a>
                                    <a href="#" wire:click="cambiarJugo()">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Jugo <span
                                                class="badge badge-{{ $planSeleccionado->jugo ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->jugo ? 'SI' : 'NO' }}</span>
                                        </li>
                                    </a>




                                </ul>
                            </div>
                        </div>

                    </div>
                @endisset

            </div>
        </div>
    </div>

    <!-- Modal para seleccionar/editar horarios -->
    <div wire:ignore.self class="modal fade" id="modalHorario" tabindex="-1" role="dialog"
        aria-labelledby="modalHorarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHorarioLabel">Seleccionar Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="cerrarModalHorario"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="horarioSeleccionado">Selecciona un horario:</label>
                        <select class="form-control" wire:model="horarioSeleccionado" id="horarioSeleccionado">
                            <option value="">Sin horario</option>
                            @foreach ($horarios as $horario)
                                <option value="{{ $horario->id }}">
                                    {{ $horario->nombre }} ({{ $horario->hora_inicio }} - {{ $horario->hora_fin }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="cerrarModalHorario">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarHorario">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('mostrarModalHorario', () => {
            $('#modalHorario').modal('show');
        });

        Livewire.on('cerrarModalHorario', () => {
            $('#modalHorario').modal('hide');
        });
    });
</script>
