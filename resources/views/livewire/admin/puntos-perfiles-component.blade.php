<div>
    <div class="row">
        <!-- Mensajes de éxito/error -->
        @if (session()->has('message'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="form-head mb-1 d-flex flex-wrap align-items-center">
            <div class="me-auto">
                <h2 class="font-w600 mb-0">Listado de Perfiles de Puntos
                    <div wire:loading class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </h2>
            </div>
            <div class="input-group search-area2 d-xl-inline-flex mb-2 me-lg-4 me-md-2">
                <button class="input-group-text"><i class="flaticon-381-search-2 text-primary"></i></button>
                <input type="text" class="form-control" placeholder="Buscar por nombre..."
                    wire:model.debounce.700ms="search">
            </div>
            <div class="col-3">
                <button type="button" class="btn btn-primary btn-lg btn-block rounded text-white"
                    wire:click="crearNuevo">
                    <i class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
             
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-responsive-md m-0 p-0 letra12">
                            <thead class="m-2 p-3 bg-primary text-white">
                                <tr class="m-2 p-3">
                                    <th class="m-2 p-3">Nombre</th>
                                    <th class="m-2 p-3">Porcentaje</th>
                                    <th class="m-2 p-3">Bono</th>
                                    <th class="m-2 p-3">Usuarios</th>
                                    <th class="m-2 p-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="m-0 p-0">
                                @if ($perfiles->isNotEmpty())
                                    @foreach ($perfiles as $perfil)
                                        <tr wire:key="perfil-{{ $perfil->id }}" class="m-0 p-0">
                                            <td class="m-0 p-0">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="w-space-no">{{ $perfil->nombre }}</span>
                                                </div>
                                            </td>
                                            <td class="m-0 p-0 text-center">{{ $perfil->porcentaje }}%</td>
                                            <td class="m-0 p-0 text-center">{{ $perfil->bono }} Pts</td>
                                            <td class="m-0 p-0 text-center">
                                                <button type="button"
                                                    class="mt-1 mb-1 btn btn-xxs btn-rounded {{ $perfil->usuarios->count() > 0 ? 'btn-primary' : 'btn-info' }}"
                                                    wire:loading.attr="disabled" wire:target="agregarUsuarios"
                                                    wire:click="agregarUsuarios({{ $perfil->id }})">
                                                    <span
                                                        class="btn-icon-start {{ $perfil->usuarios->count() > 0 ? 'text-primary' : 'text-info' }}"><strong>{{ $perfil->usuarios->count() }}</strong>
                                                    </span>
                                                    {!! $perfil->usuarios->count() > 0
                                                        ? 'Ver <i class="fa fa-info-circle ms-2"></i>'
                                                        : 'Agregar <i class="fa fa-plus-circle ms-2"></i>' !!}
                                                </button>
                                            </td>
                                            <td class="m-0 p-0 text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <button type="button"
                                                        class="btn btn-primary shadow btn-xs sharp me-1"
                                                        wire:click="editarPerfil({{ $perfil->id }})" title="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    {{-- <button type="button" class="btn btn-danger shadow btn-xs sharp"
                                                        onclick="eliminarPerfil({{ $perfil->id }})" title="Eliminar">
                                                        <i class="fa fa-trash"></i>
                                                    </button> --}}
                                                    <button type="button" class="btn btn-info shadow btn-xs sharp ms-1"
                                                        wire:click="agregarUsuarios({{ $perfil->id }})"
                                                        title="Agregar Usuarios">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="m-0 p-0">
                                        <td colspan="5" class="text-center">No se encontraron perfiles de puntos</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if ($perfiles->isNotEmpty())
                        <div class="row d-flex justify-content-between align-items-center flex-wrap mt-3 px-3"
                            style="justify-content: center !important;">
                           
                            <div class="col-12 d-flex justify-content-center">
                                {{ $perfiles->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para CRUD -->
    @if ($showModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEdit ? 'Editar Perfil de Puntos' : 'Nuevo Perfil de Puntos' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="cerrarModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="guardar">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="nombre" class="form-label">Nombre del Perfil <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" wire:model="nombre" placeholder="Ingrese el nombre del perfil">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="porcentaje" class="form-label">Porcentaje (%) <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('porcentaje') is-invalid @enderror" id="porcentaje"
                                        wire:model="porcentaje" placeholder="0" min="0" max="100"
                                        step="0.01">
                                    @error('porcentaje')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bono" class="form-label">Bono (Pts)<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bono') is-invalid @enderror"
                                        id="bono" wire:model="bono" placeholder="0.00" min="0"
                                        step="0.01">
                                    @error('bono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click="guardar">
                            {{ $isEdit ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para gestión de usuarios -->
    {{-- @if ($showModalUsuarios) --}}
    <div class="modal fade" style="" tabindex="-1" id="usuariosModal" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                @if ($showModalUsuarios)
                    <div class="modal-header  mt-0 mb-0 pb-2 pt-2">
                        <h5 class="modal-title">
                            <i class="fa fa-users me-2"></i>
                            Gestionar Usuarios - {{ $perfilSeleccionado->nombre ?? '' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">
                            <!-- Columna izquierda - Usuarios disponibles -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary text-white p-2">
                                        <h6 class="mb-0 text-white">
                                            <i class="fa fa-user-plus me-2"></i>
                                            Usuarios Disponibles - {{ $usuariosDisponibles->count() }} usuarios
                                            <div wire:loading class="spinner-border" role="status"
                                                style="width: 1.5rem !important; height: 1.5rem !important; margin-left: 5px;">
                                                <span class="sr-only">Loading...</span>
                                            </div>

                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <!-- Búsqueda usuarios disponibles -->
                                        <div class="mb-3">
                                            <div class="">
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Buscar usuarios disponibles..."
                                                    wire:model.debounce.500ms="searchUsuariosDisponibles">
                                            </div>
                                        </div>

                                        <!-- Lista de usuarios disponibles -->
                                        <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                                            @forelse($usuariosDisponibles as $usuario)
                                                <div class="list-group-item d-flex justify-content-between align-items-center"
                                                    style="padding: 2% !important;">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $usuario->name }} | <small
                                                                class="text-info">
                                                                <i
                                                                    class="fa fa-phone me-1"></i>{{ $usuario->telf ?? 'Sin teléfono' }}
                                                            </small></h6>
                                                        @if($usuario->perfilesPuntos->isNotEmpty())
                                                            <small class="text-warning">
                                                                <i class="fa fa-exclamation-triangle me-1"></i>
                                                                Actualmente en: {{ $usuario->perfilesPuntos->first()->nombre }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-sm btn-xxs ms-2"
                                                        wire:click="agregarUsuarioAlPerfil({{ $usuario->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="agregarUsuarioAlPerfil"
                                                        title="{{ $usuario->perfilesPuntos->isNotEmpty() ? 'Reasignar al perfil' : 'Agregar al perfil' }}">
                                                        <i class="fa fa-{{ $usuario->perfilesPuntos->isNotEmpty() ? 'exchange' : 'plus' }}"></i>
                                                    </button>
                                                </div>
                                            @empty
                                                <div class="text-center text-muted py-4">
                                                    <i class="fa fa-users fa-3x mb-3"></i>
                                                    <p>No hay usuarios disponibles</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        
                                        <!-- Paginación usuarios disponibles -->
                                        @if($usuariosDisponibles->hasPages())
                                            <div class="mt-2">
                                                {{ $usuariosDisponibles->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha - Usuarios asignados -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-info text-white p-2">
                                        <h6 class="mb-0 text-white">
                                            <i class="fa fa-user me-2"></i>
                                            Usuarios Asignados al Perfil - {{ $usuariosAsignados->count() }} usuarios
                                            <div wire:loading class="spinner-border" role="status"
                                                style="width: 1.5rem !important; height: 1.5rem !important; margin-left: 5px;">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <!-- Búsqueda usuarios asignados -->
                                        <div class="mb-3">
                                            <div class="">

                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Buscar usuarios asignados..."
                                                    wire:model.debounce.500ms="searchUsuariosAsignados">
                                            </div>
                                        </div>

                                        <!-- Lista de usuarios asignados -->
                                        <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                                            @forelse($usuariosAsignados as $usuario)
                                                <div class="list-group-item d-flex justify-content-between align-items-center"
                                                    style="padding: 2% !important;">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $usuario->name }} | <small
                                                                class="text-info">
                                                                <i
                                                                    class="fa fa-phone me-1"></i>{{ $usuario->telf ?? 'Sin teléfono' }}
                                                            </small></h6>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-xxs ms-2"
                                                        wire:click="quitarUsuarioDelPerfil({{ $usuario->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="quitarUsuarioDelPerfil"
                                                        title="Quitar del perfil">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            @empty
                                                <div class="text-center text-muted py-4">
                                                    <i class="fa fa-user-times fa-3x mb-3"></i>
                                                    <p>No hay usuarios asignados a este perfil</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        
                                        <!-- Paginación usuarios asignados -->
                                        @if($usuariosAsignados->hasPages())
                                            <div class="mt-2">
                                                {{ $usuariosAsignados->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cerrar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- @endif --}}

    {{-- @push('scripts') --}}
    <script>
        function eliminarPerfil(id) {
            Swal.fire({
                title: "Eliminar perfil",
                text: "Esta seguro que desea eliminar el perfil?. Esta acción no se puede revertir.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si!. Eliminar",
                cancelButtonText: "No!, Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminar-perfil', id);
                }
            });
        }

        document.addEventListener('livewire:load', function() {
            Livewire.on('abrirModalUsuarios', function() {
                $('#usuariosModal').modal('show');
            });
        });
    </script>
    {{-- @endpush --}}

</div>
