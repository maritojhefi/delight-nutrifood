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

        @php
            // Venta de ejemplo para simular el reparto (misma fórmula que HistorialVentaObserver)
            $ppEjemplo = 100;
        @endphp

        <div class="col-lg-12 cv-ui" id="perfilesPuntosPage">
            <div class="card cl-card pp-card">
                <div class="cl-header">
                    <div>
                        <h4 class="cl-title">
                            Perfiles de puntos
                            <span wire:loading class="spinner-border spinner-border-sm text-primary ms-2"
                                role="status"><span class="sr-only">Cargando...</span></span>
                        </h4>
                        <p class="cl-sub">Definen cuánto gana quien refiere a un cliente y qué bono recibe ese cliente</p>
                    </div>
                    <div class="cl-header-actions">
                        <div class="cv-search cl-search">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>
                            <input type="search" placeholder="Buscar perfil…" aria-label="Buscar perfiles"
                                wire:model.debounce.700ms="search">
                        </div>
                        <button type="button" class="btn btn-primary cl-btn-nuevo" wire:click="crearNuevo">
                            <i class="fa fa-plus me-2"></i>Nuevo perfil
                        </button>
                    </div>
                </div>

                {{-- Explicación general del sistema de puntos --}}
                <details class="pp-guide">
                    <summary>
                        <i class="fa fa-info-circle"></i>
                        ¿Cómo funcionan los perfiles de puntos?
                        <span class="pp-guide-chevron" aria-hidden="true"></span>
                    </summary>
                    <div class="pp-guide-body">
                        <div class="pp-step">
                            <span class="pp-step-n">1</span>
                            <div>
                                <strong>El perfil pertenece al que refiere</strong>
                                <p>Cada usuario tiene un perfil y un código. Cuando un cliente nuevo se registra con
                                    ese código, el dueño del código pasa a ser su <em>referidor</em>.</p>
                            </div>
                        </div>
                        <div class="pp-step">
                            <span class="pp-step-n">2</span>
                            <div>
                                <strong>Bono: una sola vez, para el cliente nuevo</strong>
                                <p>Cuando el cliente referido verifica su cuenta recibe el <b>bono</b> del perfil de su
                                    referidor. El referidor no recibe puntos por esto.</p>
                            </div>
                        </div>
                        <div class="pp-step">
                            <span class="pp-step-n">3</span>
                            <div>
                                <strong>Porcentaje: en cada compra, se reparte</strong>
                                <p>Los puntos de cada venta del cliente referido se dividen: el <b>porcentaje</b> va al
                                    referidor y el resto al cliente. No son puntos extra: salen de los del cliente.</p>
                            </div>
                        </div>
                        <p class="pp-guide-note">
                            Solo aplica a clientes verificados. Los clientes sin referidor (o cuyo referidor no tiene
                            perfil o tiene 0%) reciben el 100% de sus puntos. El perfil marcado como
                            <b>Por defecto</b> se asigna automáticamente a cada usuario nuevo.
                        </p>
                    </div>
                </details>

                <div class="cl-table" role="table" aria-label="Perfiles de puntos">
                    <div class="cl-row pp-row cl-row-head" role="row">
                        <div role="columnheader">Perfil</div>
                        <div role="columnheader">
                            <span class="pp-th">Porcentaje
                                <span class="pp-tip">
                                    <button type="button" class="pp-info" aria-label="¿Qué es el porcentaje?">i</button>
                                    <span class="pp-pop" role="tooltip">
                                        <strong>Reparto por compra</strong>
                                        Parte de los puntos de cada compra de un cliente referido que se lleva su
                                        referidor. Se descuenta de los puntos del cliente y se redondea al entero más
                                        cercano.
                                    </span>
                                </span>
                            </span>
                        </div>
                        <div role="columnheader">
                            <span class="pp-th">Bono
                                <span class="pp-tip">
                                    <button type="button" class="pp-info" aria-label="¿Qué es el bono?">i</button>
                                    <span class="pp-pop" role="tooltip">
                                        <strong>Bienvenida, una sola vez</strong>
                                        Puntos que recibe el cliente nuevo cuando verifica su cuenta, si se registró
                                        con el código de un usuario de este perfil. El referidor no recibe el bono.
                                    </span>
                                </span>
                            </span>
                        </div>
                        <div role="columnheader">
                            <span class="pp-th">Ejemplo de reparto
                                <span class="pp-tip">
                                    <button type="button" class="pp-info" aria-label="¿Cómo leer el ejemplo?">i</button>
                                    <span class="pp-pop pp-pop-end" role="tooltip">
                                        <strong>Simulación</strong>
                                        Cómo se dividen {{ $ppEjemplo }} pts de una compra de un cliente referido por
                                        alguien con este perfil. Usa la misma fórmula y redondeo que el sistema.
                                    </span>
                                </span>
                            </span>
                        </div>
                        <div role="columnheader">Usuarios</div>
                        <div role="columnheader" class="cl-right">Acciones</div>
                    </div>

                    @forelse ($perfiles as $perfil)
                        @php
                            $pct = (float) $perfil->porcentaje;
                            $ptsReferidor = ($ppEjemplo * $pct) / 100;
                            if (fmod($ptsReferidor, 1) != 0) {
                                $ptsReferidor = round($ptsReferidor);
                            }
                            $ptsCliente = $ppEjemplo - $ptsReferidor;
                            $pctTexto = rtrim(rtrim(number_format($pct, 2), '0'), '.');
                            $bono = (float) $perfil->bono;
                            $bonoTexto = rtrim(rtrim(number_format($bono, 2), '0'), '.');
                            $nUsuarios = $perfil->usuarios->count();
                        @endphp
                        <div class="cl-row pp-row" role="row" wire:key="perfil-{{ $perfil->id }}">
                            {{-- Perfil --}}
                            <div class="cl-cell cl-cell-main" role="cell">
                                <span class="cl-name">{{ $perfil->nombre }}</span>
                                @if ($perfil->default)
                                    <span class="pp-badge pp-tip">
                                        <span tabindex="0">Por defecto</span>
                                        <span class="pp-pop" role="tooltip">
                                            Se asigna automáticamente a cada usuario nuevo que se registra.
                                        </span>
                                    </span>
                                @endif
                            </div>

                            {{-- Porcentaje --}}
                            <div class="cl-cell" role="cell" data-label="Porcentaje">
                                <span class="pp-metric">
                                    <strong>{{ $pctTexto }}%</strong>
                                    <small>al referidor</small>
                                </span>
                            </div>

                            {{-- Bono --}}
                            <div class="cl-cell" role="cell" data-label="Bono">
                                <span class="pp-metric pp-metric-bono">
                                    <strong>{{ $bonoTexto }} pts</strong>
                                    <small>al cliente nuevo</small>
                                </span>
                            </div>

                            {{-- Ejemplo --}}
                            <div class="cl-cell" role="cell" data-label="Ejemplo ({{ $ppEjemplo }} pts)">
                                @if ($pct > 0)
                                    <div class="pp-split" aria-label="De {{ $ppEjemplo }} puntos: {{ $ptsReferidor }} al referidor y {{ $ptsCliente }} al cliente">
                                        <div class="pp-bar" aria-hidden="true">
                                            <span class="pp-bar-ref" style="width: {{ min(100, max(0, $ptsReferidor)) }}%"></span>
                                            <span class="pp-bar-cli" style="width: {{ min(100, max(0, $ptsCliente)) }}%"></span>
                                        </div>
                                        <div class="pp-legend">
                                            <span><i class="pp-dot pp-dot-ref"></i>Referidor
                                                <b>{{ $ptsReferidor + 0 }}</b></span>
                                            <span><i class="pp-dot pp-dot-cli"></i>Cliente
                                                <b>{{ $ptsCliente + 0 }}</b></span>
                                        </div>
                                    </div>
                                @else
                                    <span class="cl-muted">Sin reparto: el cliente recibe el 100%</span>
                                @endif
                            </div>

                            {{-- Usuarios --}}
                            <div class="cl-cell" role="cell" data-label="Usuarios">
                                <button type="button" class="pp-users {{ $nUsuarios ? '' : 'is-empty' }}"
                                    wire:click="agregarUsuarios({{ $perfil->id }})" wire:loading.attr="disabled"
                                    wire:target="agregarUsuarios">
                                    <strong>{{ number_format($nUsuarios) }}</strong>
                                    <span>{{ $nUsuarios ? ($nUsuarios === 1 ? 'usuario' : 'usuarios') : 'Asignar usuarios' }}</span>
                                </button>
                            </div>

                            {{-- Acciones --}}
                            <div class="cl-cell cl-actions" role="cell">
                                <button type="button" class="cl-icon-btn" title="Editar perfil"
                                    aria-label="Editar {{ $perfil->nombre }}"
                                    wire:click="editarPerfil({{ $perfil->id }})">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button" class="cl-icon-btn" title="Gestionar usuarios"
                                    aria-label="Gestionar usuarios de {{ $perfil->nombre }}"
                                    wire:click="agregarUsuarios({{ $perfil->id }})">
                                    <i class="fa fa-users"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="cl-empty">
                            @if ($search)
                                <strong>Sin resultados</strong>
                                <span>No hay perfiles que coincidan con “{{ $search }}”.</span>
                            @else
                                <strong>Todavía no hay perfiles</strong>
                                <span>Crea el primero con el botón “Nuevo perfil”.</span>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if ($perfiles->hasPages())
                    <div class="cl-footer">
                        <span></span>
                        <div>{{ $perfiles->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('css')
        @include('admin.partials.ui-kit')
    @endpush

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
                                    <div class="form-text">Parte de los puntos de cada compra de un cliente referido que se
                                        lleva el referidor. Se descuenta de los puntos del cliente.</div>
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
                                    <div class="form-text">Puntos únicos que recibe el cliente nuevo al verificar su cuenta si
                                        se registró con el código de alguien de este perfil.</div>
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
            window.cvAplicarColorTema && window.cvAplicarColorTema(document.getElementById('perfilesPuntosPage'));
            Livewire.on('abrirModalUsuarios', function() {
                $('#usuariosModal').modal('show');
            });
        });
    </script>
    {{-- @endpush --}}

</div>
