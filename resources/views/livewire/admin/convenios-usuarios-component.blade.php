<div class="col-lg-12 cv-ui" id="vinculosPage">
    @php
        $paletaAvatares = ['#0E8A74', '#2563EB', '#7C3AED', '#DB2777', '#EA580C', '#0891B2', '#65A30D', '#9333EA'];
        $maxAvatares = 5;
    @endphp

    <div class="card cl-card">
        <div class="cl-header">
            <div>
                <h4 class="cl-title">Convenios y vínculos</h4>
                <p class="cl-sub">Administra qué clientes reciben el descuento de cada convenio</p>
            </div>
            <div class="cl-header-actions">
                <div class="cv-search cl-search">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3.5-3.5"></path>
                    </svg>
                    <input type="search" placeholder="Buscar convenio…" aria-label="Buscar convenios"
                        wire:model.debounce.750ms="buscar">
                </div>
            </div>
        </div>

        <div class="cl-table" role="table" aria-label="Convenios y clientes vinculados">
            <div class="cl-row cl-row-vinc cl-row-head" role="row">
                <div role="columnheader">Convenio</div>
                <div role="columnheader">Descuento</div>
                <div role="columnheader">Clientes vinculados</div>
                <div role="columnheader">Vigencia</div>
                <div role="columnheader" class="cl-right">Acciones</div>
            </div>

            @forelse ($convenios as $convenio)
                @php
                    $clientes = $convenio->usuarios;
                    $nClientes = $clientes->count();
                @endphp
                <div class="cl-row cl-row-vinc" role="row" wire:key="vinculo-{{ $convenio->id }}">
                    {{-- Convenio --}}
                    <div class="cl-cell cl-cell-main" role="cell">
                        <span class="cl-name">{{ $convenio->nombre_convenio }}</span>
                        <span class="cl-meta">
                            <i class="fa fa-tag"></i>
                            @php $nProd = count(json_decode($convenio->productos_afectados, true) ?: []); @endphp
                            {{ $nProd }} {{ $nProd === 1 ? 'producto' : 'productos' }}
                        </span>
                    </div>

                    {{-- Descuento --}}
                    <div class="cl-cell" role="cell" data-label="Descuento">
                        @include('admin.partials.convenio-descuento', ['convenio' => $convenio])
                    </div>

                    {{-- Clientes --}}
                    <div class="cl-cell" role="cell" data-label="Clientes vinculados">
                        @if ($nClientes === 0)
                            <span class="cl-muted">Sin clientes vinculados</span>
                        @else
                            <button type="button" class="cl-clients" title="Ver clientes del convenio"
                                wire:click="verUsuarios('{{ $convenio->id }}')"
                                onclick="seleccionarConvenio('{{ $convenio->id }}')">
                                <span class="cl-avatars" aria-hidden="true">
                                    @foreach ($clientes->take($maxAvatares) as $cliente)
                                        @php
                                            $partes = preg_split('/\s+/', trim($cliente->name));
                                            $ini = mb_strtoupper(
                                                mb_substr($partes[0] ?? '', 0, 1) . mb_substr($partes[1] ?? '', 0, 1),
                                            );
                                        @endphp
                                        <span class="cl-av" title="{{ $cliente->name }}"
                                            style="background: {{ $paletaAvatares[$cliente->id % count($paletaAvatares)] }}">{{ $ini }}</span>
                                    @endforeach
                                    @if ($nClientes > $maxAvatares)
                                        <span class="cl-av cl-av-more">+{{ $nClientes - $maxAvatares }}</span>
                                    @endif
                                </span>
                                <span class="cl-clients-text">
                                    <strong>{{ $nClientes }} {{ $nClientes === 1 ? 'cliente' : 'clientes' }}</strong>
                                    <small>Ver lista</small>
                                </span>
                            </button>
                        @endif
                    </div>

                    {{-- Vigencia --}}
                    <div class="cl-cell" role="cell" data-label="Vigencia">
                        @include('admin.partials.convenio-vigencia', ['convenio' => $convenio])
                    </div>

                    {{-- Acciones --}}
                    <div class="cl-cell cl-actions" role="cell">
                        @if ($nClientes > 0)
                            <button type="button" class="cl-icon-btn" title="Ver clientes"
                                aria-label="Ver clientes de {{ $convenio->nombre_convenio }}"
                                wire:click="verUsuarios('{{ $convenio->id }}')"
                                onclick="seleccionarConvenio('{{ $convenio->id }}')">
                                <i class="fa fa-eye"></i>
                            </button>
                        @endif
                        <button type="button" class="btn btn-primary cl-btn-vincular"
                            aria-label="Vincular clientes a {{ $convenio->nombre_convenio }}"
                            wire:click="agregarUsuarios({{ $convenio->id }})"
                            onclick="VinculoUI.preparar(@js($convenio->nombre_convenio))">
                            <i class="fa fa-user-plus me-1"></i> Vincular
                        </button>
                    </div>
                </div>
            @empty
                <div class="cl-empty">
                    @if ($buscar)
                        <strong>Sin resultados</strong>
                        <span>No hay convenios que coincidan con “{{ $buscar }}”.</span>
                    @else
                        <strong>Todavía no hay convenios</strong>
                        <span>Créalos desde Convenios › Crear para poder vincular clientes.</span>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="cl-footer">
            <span class="cl-muted">
                Mostrando {{ $convenios->count() }} de {{ $convenios->total() }}
                {{ $convenios->total() === 1 ? 'registro' : 'registros' }}
            </span>
            <div>{{ $convenios->links() }}</div>
        </div>
    </div>

    {{-- Modal: vincular clientes al convenio --}}
    <div class="modal fade cv-ui" id="usuariosModal" tabindex="-1" aria-labelledby="cvTitulo" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content cv-modal">
                <div class="cv-head">
                    <div class="cv-head-text">
                        <h5 class="cv-title" id="cvTitulo">Vincular clientes</h5>
                        <p class="cv-subtitle">
                            Convenio <span class="cv-chip" id="cvNombreConvenio">—</span>
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="cv-body" wire:ignore>
                    <div class="cv-toolbar">
                        <div class="cv-search">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>
                            <input type="search" id="cvBuscar" autocomplete="off"
                                placeholder="Buscar por nombre o correo…" aria-label="Buscar clientes">
                        </div>
                        <div class="cv-segment" role="tablist" aria-label="Filtro">
                            <button type="button" role="tab" class="is-active" data-filtro="todos">
                                Todos <span id="cvCountTodos">0</span>
                            </button>
                            <button type="button" role="tab" data-filtro="seleccionados">
                                Seleccionados <span id="cvCountSel">0</span>
                            </button>
                        </div>
                    </div>

                    <div class="cv-bulk">
                        <span id="cvInfoLista" class="cv-muted"></span>
                        <div class="cv-bulk-actions">
                            <button type="button" class="cv-link" id="cvSelVisibles">Seleccionar visibles</button>
                            <span class="cv-dot" aria-hidden="true">·</span>
                            <button type="button" class="cv-link cv-link-danger" id="cvLimpiar">Quitar todos</button>
                        </div>
                    </div>

                    <div class="cv-list" id="cvLista" role="listbox" aria-multiselectable="true"
                        aria-label="Clientes disponibles"></div>
                </div>

                @error('usuarios_seleccionados')
                    <div class="px-4 pb-2"><small class="text-danger">{{ $message }}</small></div>
                @enderror

                <div class="cv-foot">
                    <div class="cv-resumen" wire:ignore>
                        <strong id="cvResumenTotal">0 clientes</strong>
                        <span id="cvResumenCambios"></span>
                    </div>
                    <div class="cv-foot-actions">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" id="cvGuardar"
                            onclick="VinculoUI.guardar()" wire:loading.attr="disabled" wire:target="vincularUsuarios">
                            <span wire:loading.remove wire:target="vincularUsuarios">Guardar cambios</span>
                            <span wire:loading wire:target="vincularUsuarios">Guardando…</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="usuariosListaModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuarios dentro del convenio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="buscarUsuarioModal" class="form-control"
                            placeholder="Buscar por nombre o teléfono">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive-md m-0 p-0 letra12">
                            <thead class="m-0 p-0">
                                <tr class="m-0 p-0">
                                    <th class="m-0 p-0"><strong>Nombre</strong></th>
                                    <th class="m-0 p-0"><strong>Teléfono</strong></th>
                                    <th class="m-0 p-0"><strong>Fecha Agregado</strong></th>
                                    <th class="m-0 p-0"><strong>Acciones</strong></th>
                                </tr>
                            </thead>
                            <tbody id="listaUsuariosBody" class="m-0 p-0">
                                <!-- Los usuarios se insertarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        @include('admin.partials.ui-kit')
    @endpush

    @push('scripts')
        <script>
            let convenioSeleccionado = null;

            function seleccionarConvenio(convenioId) {
                convenioSeleccionado = convenioId;
            }

            function eliminarUsuarioConvenio(usuarioId) {
                Swal.fire({
                    title: "Desvincular usuario",
                    text: "Esta seguro que desea desvincular al usuario del convenio?. Esta acción no se puede revertir.",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si!. Desvincular",
                    cancelButtonText: "No!, Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('eliminar-usuario-convenio', convenioSeleccionado, usuarioId);
                    }
                });
            }

            /**
             * Selector de clientes para vincular al convenio.
             * Reemplaza el select2: misma fuente de datos (endpoint usuarios-disponibles)
             * y mismo método Livewire (vincularUsuarios), solo cambia la presentación.
             */
            const VinculoUI = (() => {
                const LIMITE_RENDER = 300;
                const PALETA = ['#0E8A74', '#2563EB', '#7C3AED', '#DB2777', '#EA580C', '#0891B2', '#65A30D', '#9333EA'];
                const URL_DISPONIBLES = @js(route('convenio.usuarios.disponibles', '__ID__'));

                let todos = [];
                let seleccion = new Set();
                let inicial = new Set();
                let filtro = 'todos';
                let termino = '';
                let componente = null;

                const el = (id) => document.getElementById(id);

                const escapar = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[c]));

                const normalizar = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

                const iniciales = (nombre) => nombre.trim().split(/\s+/).slice(0, 2).map((p) => p[0] || '').join('').toUpperCase();

                // El endpoint devuelve "Nombre (correo)"; se separa solo para mostrarlo mejor
                const parsear = (u) => {
                    const m = String(u.text).match(/^(.*?)\s*\(([^()]*)\)\s*$/);
                    const nombre = m ? m[1] : String(u.text);
                    const email = m ? m[2] : '';
                    return {
                        id: String(u.id),
                        nombre,
                        email,
                        busqueda: normalizar(nombre + ' ' + email),
                        color: PALETA[Number(u.id) % PALETA.length],
                        ini: iniciales(nombre),
                    };
                };


                function visibles() {
                    const q = normalizar(termino);
                    return todos.filter((u) =>
                        (filtro === 'todos' || seleccion.has(u.id)) &&
                        (!q || u.busqueda.includes(q))
                    );
                }

                function filaHTML(u) {
                    const sel = seleccion.has(u.id);
                    let tag = '';
                    if (sel && !inicial.has(u.id)) tag = '<span class="cv-tag cv-tag-nuevo">Nuevo</span>';
                    if (!sel && inicial.has(u.id)) tag = '<span class="cv-tag cv-tag-quitar">Se quitará</span>';
                    return `
                        <button type="button" class="cv-item${sel ? ' is-selected' : ''}" role="option"
                                aria-selected="${sel}" data-id="${escapar(u.id)}">
                            <span class="cv-avatar" style="background:${u.color}">${escapar(u.ini)}</span>
                            <span class="cv-info">
                                <span class="cv-name">${escapar(u.nombre)}</span>
                                <span class="cv-email">${escapar(u.email)}</span>
                            </span>
                            ${tag}
                            <span class="cv-check" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                            </span>
                        </button>`;
                }

                function render() {
                    const lista = el('cvLista');
                    const items = visibles();

                    if (todos.length === 0) {
                        lista.innerHTML = `<div class="cv-state"><strong>No hay clientes disponibles</strong>
                            Todos los clientes ya pertenecen a otro convenio.</div>`;
                    } else if (items.length === 0) {
                        lista.innerHTML = filtro === 'seleccionados' && !termino
                            ? `<div class="cv-state"><strong>Ningún cliente seleccionado</strong>
                                Marca clientes desde la pestaña “Todos”.</div>`
                            : `<div class="cv-state"><strong>Sin resultados</strong>
                                No hay clientes que coincidan con “${escapar(termino)}”.</div>`;
                    } else {
                        let html = items.slice(0, LIMITE_RENDER).map(filaHTML).join('');
                        if (items.length > LIMITE_RENDER) {
                            html += `<div class="cv-more">Mostrando ${LIMITE_RENDER} de ${items.length}. Refina la búsqueda para ver más.</div>`;
                        }
                        lista.innerHTML = html;
                    }

                    el('cvCountTodos').textContent = todos.length;
                    el('cvCountSel').textContent = seleccion.size;
                    el('cvInfoLista').textContent = termino
                        ? `${items.length} resultado${items.length === 1 ? '' : 's'}`
                        : `${items.length} cliente${items.length === 1 ? '' : 's'}`;

                    const agregados = [...seleccion].filter((id) => !inicial.has(id)).length;
                    const quitados = [...inicial].filter((id) => !seleccion.has(id)).length;
                    el('cvResumenTotal').textContent = `${seleccion.size} cliente${seleccion.size === 1 ? '' : 's'} vinculado${seleccion.size === 1 ? '' : 's'}`;
                    const cambios = [];
                    if (agregados) cambios.push(`<span class="cv-tag cv-tag-nuevo">+${agregados}</span>`);
                    if (quitados) cambios.push(`<span class="cv-tag cv-tag-quitar">−${quitados}</span>`);
                    el('cvResumenCambios').innerHTML = cambios.join(' ');
                }

                function mostrarCargando() {
                    el('cvLista').innerHTML = Array.from({ length: 6 }, () => `
                        <div class="cv-skel">
                            <i style="width:36px;height:36px;border-radius:50%"></i>
                            <div style="flex:1">
                                <i style="width:45%;height:10px;margin-bottom:6px"></i>
                                <i style="width:65%;height:8px"></i>
                            </div>
                        </div>`).join('');
                    el('cvCountTodos').textContent = '0';
                    el('cvCountSel').textContent = '0';
                    el('cvInfoLista').textContent = 'Cargando clientes…';
                    el('cvResumenTotal').textContent = '—';
                    el('cvResumenCambios').innerHTML = '';
                }

                function cambiarFiltro(nuevo) {
                    filtro = nuevo;
                    document.querySelectorAll('#usuariosModal .cv-segment button').forEach((b) => {
                        const activo = b.dataset.filtro === nuevo;
                        b.classList.toggle('is-active', activo);
                        b.setAttribute('aria-selected', activo);
                    });
                    render();
                }

                function reiniciar() {
                    todos = [];
                    seleccion = new Set();
                    inicial = new Set();
                    termino = '';
                    el('cvBuscar').value = '';
                    cambiarFiltro('todos');
                }

                function cargar(convenioId) {
                    mostrarCargando();
                    fetch(URL_DISPONIBLES.replace('__ID__', encodeURIComponent(convenioId)), {
                            headers: { 'Accept': 'application/json' }
                        })
                        .then((r) => {
                            if (!r.ok) throw new Error('HTTP ' + r.status);
                            return r.json();
                        })
                        .then((data) => {
                            todos = (data.usuariosDisponibles || []).map(parsear)
                                .sort((a, b) => a.nombre.localeCompare(b.nombre, 'es'));
                            seleccion = new Set((data.usuariosSeleccionados || []).map(String));
                            inicial = new Set(seleccion);
                            render();
                            el('cvBuscar').focus();
                        })
                        .catch(() => {
                            el('cvLista').innerHTML = `<div class="cv-state"><strong>No se pudieron cargar los clientes</strong>
                                Cierra el modal e inténtalo de nuevo.</div>`;
                            el('cvInfoLista').textContent = '';
                        });
                }

                function init(wire) {
                    componente = wire;
                    window.cvAplicarColorTema(el('usuariosModal'));
                    window.cvAplicarColorTema(el('vinculosPage'));

                    let t;
                    el('cvBuscar').addEventListener('input', (e) => {
                        clearTimeout(t);
                        t = setTimeout(() => {
                            termino = e.target.value.trim();
                            render();
                        }, 120);
                    });

                    document.querySelectorAll('#usuariosModal .cv-segment button')
                        .forEach((b) => b.addEventListener('click', () => cambiarFiltro(b.dataset.filtro)));

                    el('cvLista').addEventListener('click', (e) => {
                        const item = e.target.closest('.cv-item');
                        if (!item) return;
                        const id = item.dataset.id;
                        seleccion.has(id) ? seleccion.delete(id) : seleccion.add(id);
                        const scroll = el('cvLista').scrollTop;
                        render();
                        el('cvLista').scrollTop = scroll;
                        el('cvLista').querySelector(`.cv-item[data-id="${CSS.escape(id)}"]`)?.focus();
                    });

                    el('cvSelVisibles').addEventListener('click', () => {
                        visibles().slice(0, LIMITE_RENDER).forEach((u) => seleccion.add(u.id));
                        render();
                    });

                    el('cvLimpiar').addEventListener('click', () => {
                        seleccion.clear();
                        render();
                    });

                    window.addEventListener('abrirModalUsuarios', () => {
                        jQuery('#usuariosModal').modal('show');
                        cargar(componente.convenio_id);
                    });

                    jQuery('#usuariosModal').on('hidden.bs.modal', reiniciar);
                }

                return {
                    init,
                    preparar(nombreConvenio) {
                        reiniciar();
                        el('cvNombreConvenio').textContent = nombreConvenio || '—';
                        mostrarCargando();
                    },
                    guardar() {
                        // Envía la selección diferida junto con la llamada: una sola petición
                        componente.set('usuarios_seleccionados', [...seleccion], true);
                        componente.call('vincularUsuarios');
                    },
                };
            })();

            document.addEventListener('livewire:load', function() {
                if (typeof jQuery === 'undefined') {
                    console.error('jQuery no está disponible');
                    return;
                }

                VinculoUI.init(@this);

                Livewire.on('cerrarModalUsuarios', function() {
                    jQuery('#usuariosModal').modal('hide');
                });

                Livewire.on('notificar', function(event) {
                    Swal.fire({
                        text: event.mensaje,
                        icon: event.tipo,
                    });
                });

                const usuariosModal = new bootstrap.Modal(document.getElementById('usuariosListaModal'));
                let usuariosActuales = [];

                Livewire.on('mostrar-usuarios', function(arrayUsuarios) {
                    // Guardar los usuarios para búsquedas posteriores
                    usuariosActuales = arrayUsuarios;

                    // Corregir la condición - era = en lugar de ==
                    if (jQuery('.modal.show').length === 0) {
                        // Limpiar lista anterior
                        const listaBody = document.getElementById('listaUsuariosBody');
                        listaBody.innerHTML = '';
                    }
                    // Llenar la tabla con los nuevos usuarios
                    renderizarUsuarios(arrayUsuarios);
                    // Mostrar el modal
                    usuariosModal.show();
                });

                // Función para renderizar usuarios filtrados
                function renderizarUsuarios(usuarios) {
                    const listaBody = document.getElementById('listaUsuariosBody');
                    listaBody.innerHTML = '';
                    usuarios.forEach(usuario => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
            <td class="m-0 p-1">${usuario.nombre}</td>
            <td class="m-0 p-1">${usuario.telf}</td>
            <td class="m-0 p-1">${usuario.fecha_creacion} - ${usuario.hora_creacion}<br>(${usuario.hace_tiempo})</td>
            <td class="m-0 p-1"><button type="button" class="btn btn-rounded btn-danger btn-xs" onclick="eliminarUsuarioConvenio(${usuario.id})"><i class="fa fa-trash"></i></button></td>
        `;
                        listaBody.appendChild(row);
                    });
                }

                // Búsqueda en el modal de usuarios
                document.getElementById('buscarUsuarioModal')?.addEventListener('input', function(e) {
                    const terminoBusqueda = e.target.value.toLowerCase();
                    if (terminoBusqueda === '') {
                        renderizarUsuarios(usuariosActuales);
                        return;
                    }
                    const usuariosFiltrados = usuariosActuales.filter(usuario => {
                        return usuario.nombre.toLowerCase().includes(terminoBusqueda) ||
                            (usuario.telf && usuario.telf.toLowerCase().includes(terminoBusqueda));
                    });
                    renderizarUsuarios(usuariosFiltrados);
                });

                // Limpiar el input de búsqueda y la tabla al cerrar el modal
                document.getElementById('usuariosListaModal')?.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('buscarUsuarioModal').value = '';
                    document.getElementById('listaUsuariosBody').innerHTML = '';
                });
            });
        </script>
    @endpush
</div>
