<div class="row">
    @if ($mostrarSubcategoria)
        <div class="row d-flex justify-content-center">
            <x-card-col tamano="6">
                <div class="card-body">
                    <h4 class="card-intro-title" wire:loading.remove>Busque una subcategoria</h4>
                    <h4 class="card-intro-title" wire:loading>Buscando...</h4>
                    <input type="text" class="form-control" wire:model.debounce.500ms="searchSub">
                    <div style="overflow-y: scroll;height:400px;">
                        @foreach ($subcategorias as $item)
                            <div class="media pb-3 border-bottom mb-3 align-items-center">
                                <a href="#" wire:click="seleccionado('{{ $item->id }}')">
                                    <div class="media-body">
                                        <h6 class="fs-16 mb-0">{{ $item->nombre }}</h6>
                                        <div class="d-flex">
                                            <span class="fs-14 text-nowrap">Adicionales</span>
                                            <span class="fs-14 me-auto text-secondary"><i
                                                    class="fa fa-ticket ms-1 me-2"></i><strong>{{ $item->adicionales->count() }}</strong></span>
                                        </div>
                                        <div class="d-flex">
                                            <span class="fs-14 text-nowrap">Grupos</span>
                                            <span class="fs-14 me-auto text-info"><i
                                                    class="fa fa-object-group ms-1 me-2"></i><strong>{{ $item->gruposConAdicionalesEnSubcategoria->count() }}</strong></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-card-col>
        </div>
    @endif
    @isset($subcategoria)
        <x-card-col tamano="8">
            <div class="card-body" id="field-you-want-to-focus">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <button class="btn light btn-info btn-sm" wire:click="mostrarSubcategoria">
                            <i class="fa fa-arrow-left me-2"></i> Volver
                        </button>
                    </div>
                    <div class="text-center">
                        <h4 class="card-intro-title mb-0">Adicionales para {{ $subcategoria->nombre }}</h4>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearGrupo">
                            <i class="fa fa-plus"></i> Nuevo Grupo
                        </button>
                    </div>
                </div>
                <!-- Adicionales sin grupo -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Adicionales sin grupo</h6>
                    <div id="adicionales-sin-grupo" class="sortable-container border rounded p-2" style="min-height: 50px;">
                        @foreach ($adicionalesConGrupos as $item)
                            @if ($item->grupo_id == null)
                                <div class="adicional-item" data-adicionale-id="{{ $item->id }}"
                                    style="cursor: move; display: inline-block; border-radius: 15px;">
                                    <a href="#" class="badge badge-xl badge-outline-dark">
                                        {{ $item->nombre }}
                                        <i class="ms-2 fa fa-trash text-danger" wire:click="eliminar('{{ $item->id }}')"
                                            onclick="event.stopPropagation();"></i>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Grupos de adicionales -->
                @foreach ($gruposAdicionales as $grupo)
                    <div class="grupo-container mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">
                                    Grupo : <strong>{{ $grupo->nombre_grupo }}</strong> <small class="text-muted">(Máx
                                        seleccionable: {{ $grupo->maximo_seleccionable }})</small>
                                    <br>
                                    @if ($grupo->es_obligatorio)
                                        <small class="text-warning">Obligatorio</small>
                                    @else
                                        <small class="text-dark">Opcional</small>
                                    @endif
                                </h6>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="text-muted me-2">
                                    <strong id="contador-{{ $grupo->id }}">0</strong> Adicionales
                                </div>
                                <span style="cursor: pointer;" wire:click="iniciarEdicionGrupo({{ $grupo->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalCrearGrupo" class="popover-container">
                                    <i class="fa fa-edit fs-25 text-info ms-2"></i>
                                    <span class="popover-text">Editar Grupo</span>
                                </span>
                                <span style="cursor: pointer;" wire:click="confirmarEliminacionGrupo({{ $grupo->id }})"
                                    class="popover-container">
                                    <i class="fa fa-trash fs-20 text-danger ms-2"></i>
                                    <span class="popover-text">Eliminar Grupo</span>
                                </span>
                            </div>
                        </div>
                        <div id="grupo-{{ $grupo->id }}" class="sortable-container border rounded p-2 grupo-drop-zone"
                            data-grupo-id="{{ $grupo->id }}" data-maximo="{{ $grupo->maximo_seleccionable }}"
                            style="min-height: 50px;">
                            @foreach ($adicionalesConGrupos as $item)
                                @if ($item->grupo_id == $grupo->id)
                                    <div class="adicional-item" data-adicionale-id="{{ $item->id }}"
                                        style="cursor: move; display: inline-block; border-radius: 15px;">
                                        <a href="#" class="badge badge-xl badge-outline-primary">
                                            {{ $item->nombre }}
                                            <i class="ms-2 fa fa-times text-danger"
                                                wire:click="quitarDeGrupo('{{ $item->id }}')"
                                                onclick="event.stopPropagation();"></i>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Modal para crear/editar grupo -->
                <div class="modal fade" wire:ignore.self id="modalCrearGrupo" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    @if ($grupoEditando)
                                        Editar Grupo de Adicionales
                                    @else
                                        Crear Nuevo Grupo de Adicionales
                                    @endif
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    wire:click="cancelarEdicionGrupo"></button>
                            </div>
                            <div class="modal-body">
                                <form wire:submit.prevent="crearGrupoAdicional">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre del Grupo</label>
                                        <input type="text" class="form-control" wire:model="nombreGrupoAdicional"
                                            placeholder="Ej: Salsas, Bebidas, etc.">
                                        @error('nombreGrupoAdicional')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Máximo Seleccionable</label>
                                        <input type="number" class="form-control" wire:model="maximoSeleccionable"
                                            min="1" value="1">
                                        @error('maximoSeleccionable')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="esObligatorio"
                                                id="esObligatorio">
                                            <label class="form-check-label" for="esObligatorio">
                                                Grupo Obligatorio
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                                            wire:click="cancelarEdicionGrupo">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            @if ($grupoEditando)
                                                Actualizar Grupo
                                            @else
                                                Crear Grupo
                                            @endif
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card-col>
        <x-card-col tamano="4">
            <div class="card-body">
                <h4 class="card-intro-title">Agregar nuevo</h4>
                <input type="text" class="form-control" wire:model.debounce.750ms="search"
                    placeholder="Busque adicionales">
                <ul class="index-chart-point-list">
                    @foreach ($adicionales as $item)
                        <a href="#" wire:click="agregar('{{ $item->id }}')"><span
                                class="badge light badge-success m-2"><i class="fa fa-plus"></i>
                                {{ $item->nombre }}({{ $item->precio }} Bs) </span></a>
                    @endforeach

                </ul>
            </div>
        </x-card-col>
    @endisset
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            let sortableInstances = [];

            // Función para inicializar SortableJS
            function inicializarSortable() {
                // Limpiar instancias existentes
                sortableInstances.forEach(instance => {
                    if (instance && instance.destroy) {
                        instance.destroy();
                    }
                });
                sortableInstances = [];

                // Contenedor de adicionales sin grupo
                const sinGrupo = document.getElementById('adicionales-sin-grupo');
                if (sinGrupo) {
                    const sortableSinGrupo = new Sortable(sinGrupo, {
                        group: {
                            name: 'adicionales',
                            pull: true, // Permite sacar elementos
                            put: true // Permite poner elementos
                        },
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        sort: true, // Permite reordenar dentro del contenedor
                        onAdd: function(evt) {
                            // Cuando un elemento se agrega desde un grupo hacia "sin grupo"
                            const adicionaleId = evt.item.dataset.adicionaleId;
                            @this.call('quitarDeGrupo', adicionaleId);
                        },
                        onRemove: function(evt) {
                            // Cuando un elemento se remueve hacia un grupo
                            const adicionaleId = evt.item.dataset.adicionaleId;
                            const grupoId = evt.to.dataset.grupoId;
                            if (grupoId) {
                                @this.call('actualizarGrupoAdicional', adicionaleId, grupoId);
                            }
                        }
                    });
                    sortableInstances.push(sortableSinGrupo);
                }

                // Contenedores de grupos - Cada grupo es una instancia independiente de SortableJS
                document.querySelectorAll('.grupo-drop-zone').forEach(container => {
                    const sortableGrupo = new Sortable(container, {
                        group: {
                            name: 'adicionales',
                            pull: true, // Permite sacar elementos
                            put: true // Permite poner elementos
                        },
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        sort: true, // Permite reordenar dentro del mismo grupo
                        onAdd: function(evt) {
                            const adicionaleId = evt.item.dataset.adicionaleId;
                            const grupoId = evt.to.dataset.grupoId;

                            // Actualizar contador
                            actualizarContador(grupoId);

                            // Actualizar en base de datos solo si viene de otro contenedor
                            if (evt.from !== evt.to) {
                                @this.call('actualizarGrupoAdicional', adicionaleId, grupoId);
                            }
                        },
                        onRemove: function(evt) {
                            const grupoId = evt.from.dataset.grupoId;
                            const adicionaleId = evt.item.dataset.adicionaleId;
                            const destinoId = evt.to.dataset.grupoId;

                            // Actualizar contador del grupo origen
                            actualizarContador(grupoId);

                            // Si va hacia "sin grupo", quitar del grupo
                            if (!destinoId) {
                                @this.call('quitarDeGrupo', adicionaleId);
                            }
                        },
                        onUpdate: function(evt) {
                            // Cuando se reordena dentro del mismo grupo
                            const grupoId = evt.from.dataset.grupoId;
                            actualizarContador(grupoId);
                            console.log('Reordenado dentro del grupo:', grupoId);
                        }
                    });
                    sortableInstances.push(sortableGrupo);
                });

                // Actualizar contadores iniciales
                actualizarContadores();
            }

            // Función para actualizar contadores
            function actualizarContadores() {
                document.querySelectorAll('.grupo-drop-zone').forEach(container => {
                    const grupoId = container.dataset.grupoId;
                    actualizarContador(grupoId);
                });

                // Actualizar contador de adicionales sin grupo
                const sinGrupoContainer = document.getElementById('adicionales-sin-grupo');
                if (sinGrupoContainer) {
                    const cantidadSinGrupo = sinGrupoContainer.children.length;
                    console.log('Adicionales sin grupo:', cantidadSinGrupo);
                }
            }

            function actualizarContador(grupoId) {
                const container = document.getElementById('grupo-' + grupoId);
                const contador = document.getElementById('contador-' + grupoId);
                if (container && contador) {
                    const cantidad = container.children.length;
                    contador.textContent = cantidad;

                    // Siempre mostrar en color azul (sin límites)
                    contador.className = '';
                }
            }

            // Eventos de Livewire
            window.livewire.on('change-focus-other-field', function() {
                document.getElementById("field-you-want-to-focus").focus();
            });

            // Reinicializar SortableJS cuando Livewire actualice el DOM
            window.livewire.on('reinicializar-sortable', function() {
                setTimeout(() => {
                    inicializarSortable();
                }, 100);
            });

            // Inicializar cuando se carga la página
            document.addEventListener('DOMContentLoaded', function() {
                inicializarSortable();
            });

            // Reinicializar después de cada actualización de Livewire
            document.addEventListener('livewire:load', function() {
                inicializarSortable();
            });

            document.addEventListener('livewire:update', function() {
                setTimeout(() => {
                    inicializarSortable();
                }, 100);
            });
            Livewire.on('confirmar-eliminacion', function(data) {
                Swal.fire({
                    title: 'Eliminar grupo',
                    html: 'Esta seguro que desea eliminar este grupo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Livewire.emit('eliminar-grupo-adicional', data.grupoId);
                        @this.call('eliminarGrupo', data.grupoId);
                    }
                });
            });
            Livewire.on('cerrarModalCrearEditarGrupo', function() {
                $('#modalCrearGrupo').modal('hide');
            });
        </script>

        <style>
            .sortable-ghost {
                opacity: 0.4;
            }

            .sortable-chosen {
                transform: rotate(5deg);
            }

            .sortable-drag {
                opacity: 0.8;
            }

            .grupo-drop-zone {
                transition: background-color 0.3s ease;
            }

            .grupo-drop-zone.drag-over {
                background-color: #e3f2fd;
                border-color: #2196f3 !important;
            }

            .adicional-item {
                transition: all 0.3s ease;
            }

            .adicional-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .grupo-drop-zone {
                background-color: #f8f9fa;
                border: 2px dashed #dee2e6;
                transition: all 0.3s ease;
            }

            .grupo-drop-zone:hover {
                border-color: #007bff;
                background-color: #e3f2fd;
            }

            .grupo-drop-zone.drag-over {
                border-color: #28a745 !important;
                background-color: #d4edda !important;
                border-style: solid !important;
            }

            .grupo-container {
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
                background-color: #ffffff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .grupo-container:hover {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }
        </style>
    @endpush
</div>
