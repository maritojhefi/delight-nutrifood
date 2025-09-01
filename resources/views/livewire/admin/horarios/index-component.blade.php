<div>
    <div class="row">
        <div class="form-head mb-1 d-flex flex-wrap align-items-center">
            <div class="me-auto">
                <h2 class="font-w600 mb-0">Listado de horarios
                    <div wire:loading class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </h2>
            </div>
            <div class="input-group search-area2 d-xl-inline-flex mb-2 me-lg-4 me-md-2">
                <button class="input-group-text"><i class="flaticon-381-search-2 text-primary"></i></button>
                <input type="text" class="form-control" placeholder="Buscar..." wire:model.debounce.700ms="search">
            </div>
            <div class="col-3">
                <a href="javascript:void(0);" class="btn btn-primary btn-lg btn-block rounded text-white"
                    data-bs-toggle="modal" data-bs-target="#modalHorario" wire:click="crearNuevo"><i
                        class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>

        @if ($horarios->isNotEmpty() && $alerta == true)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible alert-alt solid fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"
                            wire:click="cerrarAlerta">
                        </button>
                        <i class="fa fa-exclamation-triangle fa-beat-fade me-1"></i> Los horarios que no tengan asociada
                        una subcategoría <strong>no serán mostrados!</strong>.
                    </div>
                </div>
            </div>
        @endif

        @if ($horarios->isNotEmpty())
            @foreach ($horarios as $horario)
                <div class="col-xl-6">
                    <div class="card" wire:key="horario-{{ $horario->id }}">
                        <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ $horario->nombre }}
                                <span class="badge badge-rounded badge-outline-primary badge-sm">
                                    Posición {{ $horario->posicion }}
                                </span>
                                @if ($horario->posicion == 1)
                                    <span class="badge badge-rounded badge-success badge-sm ms-1">
                                        <i class="fa fa-star"></i> Primero
                                    </span>
                                @elseif($horario->posicion == $horarios->count())
                                    <span class="badge badge-rounded badge-warning badge-sm ms-1">
                                        <i class="fa fa-flag-checkered"></i> Último
                                    </span>
                                @endif
                            </h5>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info light sharp"
                                    data-bs-toggle="dropdown">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                            <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                            <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                        </g>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalSubcategorias"
                                        wire:click="editarSubCategorias('{{ $horario->id }}')">Agregar/editar
                                        Subcategorías</a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalHorario" wire:click="editar({{ $horario->id }})"
                                        wire:key="editar-{{ $horario->id }}">Editar</a>
                                    @if ($horario->subcategorias->count() > 0)
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            onclick="eliminarHorarioInvalido()"
                                            wire:key="eliminar-{{ $horario->id }}">Eliminar</a>
                                    @else
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            onclick="eliminarHorario('{{ $horario->id }}', {{ $horario->subcategorias->count() }})"
                                            wire:key="eliminar-{{ $horario->id }}">Eliminar</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card overflow-hidden bg-primary position-relative"
                            style="margin-left: 15%; margin-right: 15%; margin-bottom: 0px; height: 114px !important;">
                            <i class="fa fa-clock-o position-absolute translate-middle"
                                style="font-size: 8rem; opacity: 0.2; color: white; z-index: 0; left: 72% !important; top: 4% !important; transform: rotate(45deg) !important;"></i>
                            <div class="card-body row position-relative" style="z-index: 1;">


                                <div class="col-5 text-center">
                                    <span class="badge light badge-info"><i class="fa fa-clock-o me-1"></i>Inicio</span>
                                    <div class="fw-bold text-white">
                                        {{ GlobalHelper::fechaFormateada(9, $horario->hora_inicio) }}</div>
                                </div>


                                <div class="col-2 text-center d-flex justify-content-center align-items-center">
                                    <button
                                        class="btn btn-sm btn-info btn-rounded {{ $horario->posicion <= 1 ? 'disabled' : '' }}"
                                        wire:click="bajarPosicion('{{ $horario->id }}')"
                                        {{ $horario->posicion <= 1 ? 'disabled' : '' }}
                                        title="{{ $horario->posicion <= 1 ? 'Ya está en la posición mínima' : 'Bajar posición' }}">
                                        <i class="fa fa-arrow-up"></i>
                                    </button>
                                    <button
                                        class="btn btn-sm btn-danger btn-rounded {{ $horario->posicion >= $horarios->count() ? 'disabled' : '' }}"
                                        wire:click="subirPosicion('{{ $horario->id }}')"
                                        {{ $horario->posicion >= $horarios->count() ? 'disabled' : '' }}
                                        title="{{ $horario->posicion >= $horarios->count() ? 'Ya está en la posición máxima' : 'Subir posición' }}">
                                        <i class="fa fa-arrow-down"></i>
                                    </button>
                                </div>

                                <div class="col-5 text-center">
                                    <span class="badge light badge-primary"><i
                                            class="fa fa-clock-o me-1"></i>Fin</span>
                                    <div class="fw-bold text-white">
                                        {{ GlobalHelper::fechaFormateada(9, $horario->hora_fin) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row"
                            style="margin-left: 5%; margin-right: 5%; margin-bottom: 10px; margin-top: 10px;">
                            <div class="bootstrap-badge">
                                <strong>Subcategorías :</strong>
                                @php $subs = $horario->subcategorias; @endphp
                                @if ($subs->count() > 0)
                                    @foreach ($subs->take(4) as $subcategoria)
                                        <a href="javascript:void(0)"
                                            class="badge badge-rounded badge-outline-primary">{{ $subcategoria->nombre }}</a>
                                    @endforeach
                                    @if ($subs->count() > 4)
                                        <a href="javascript:void(0)"
                                            class="badge badge-rounded badge-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#modalSubcategorias"
                                            wire:click="editarSubCategorias('{{ $horario->id }}')">Ver más
                                            (+{{ $subs->count() - 4 }})
                                        </a>
                                    @endif
                                @else
                                    <small class="text-danger"> <i class="fa fa-exclamation-triangle"></i> No hay
                                        subcategorías asignadas</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-12 text-center">
                <small>Mostrando {{ $horarios->count() }} registros</small>
            </div>
        @else
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">No hay horarios</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add Order -->
    <div wire:ignore.self class="modal fade" id="modalHorario">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $horario ? 'Editar horario' : 'Agregar horario' }}</h5>
                    <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal"></a>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="guardar">
                        <div class="mb-3">
                            <label class="text-black font-w500">Nombre del horario</label>
                            <input type="text" class="form-control" wire:model.defer="nombre">
                            @error('nombre')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="text-black font-w500">Hora de Inicio</label>
                            <input class="form-control mb-3" type="time" wire:model.defer="hora_inicio">
                            @error('hora_inicio')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="text-black font-w500">Hora de Fin</label>
                            <input type="time" class="form-control" wire:model.defer="hora_fin">
                            @error('hora_fin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalSubcategorias">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Subcategorías</h5>
                    <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal"></a>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <input type="text" class="form-control" placeholder="Buscar subcategoría por nombre"
                                wire:model.debounce.500ms="subcategoriaSearch">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong>Disponibles</strong></div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($subcategorias as $sub)
                                        @if (!in_array($sub->id, $selectedSubcategoriaIds ?? []))
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1">
                                                <span>{{ $sub->nombre }}</span>
                                                <button type="button" class="btn btn-xs btn-outline-primary"
                                                    wire:click="agregarSubcategoria('{{ $sub->id }}')">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong>Asignadas</strong></div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @forelse (($selectedSubcategoriaIds ?? []) as $sid)
                                        @php $s = $subcategorias->firstWhere('id', $sid); @endphp
                                        @if ($s)
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1">
                                                <span>{{ $s->nombre }}</span>
                                                <button type="button" class="btn btn-xs btn-outline-danger"
                                                    wire:click="quitarSubcategoria('{{ $s->id }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @empty
                                        <span>No hay subcategorías asignadas</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" wire:click="guardarSubcategorias">Guardar</button>
                </div>
            </div>
        </div>
    </div>


</div>

<style>
    .btn.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn.disabled:hover {
        transform: none !important;
    }

    .btn:not(.disabled):hover {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }

    .btn-info.btn-rounded,
    .btn-danger.btn-rounded {
        transition: all 0.2s ease;
        margin: 0 2px;
    }
</style>

<script>
    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    Livewire.on('close-modal-horario', function() {
        var el = document.getElementById('modalHorario');
        if (!el) return;
        var modal = bootstrap.Modal.getInstance(el);
        if (!modal) {
            modal = new bootstrap.Modal(el);
        }
        modal.hide();
    });

    function eliminarHorario(id, count) {
        Swal.fire({
            title: 'Eliminar horario',
            html: 'Esta seguro que desea eliminar este horario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('eliminar-horario', id);
            }
        });
    };

    Livewire.on('close-modal-subcategorias', function() {
        var el = document.getElementById('modalSubcategorias');
        if (!el) return;
        var modal = bootstrap.Modal.getInstance(el);
        if (!modal) {
            modal = new bootstrap.Modal(el);
        }
        modal.hide();
    });

    function eliminarHorarioInvalido() {
        Swal.fire({
            title: 'Eliminar horario',
            text: 'El horario tiene subcategorías asociadas, no se puede eliminar, primero debe eliminar las subcategorías asociadas',
            icon: 'warning',
        });
    }
</script>
