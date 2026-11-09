<div class="col-lg-12 cv-ui" id="conveniosPage">
    @php
        // Nombres de todos los productos de la página en una sola consulta (evita una consulta por chip
        // y no rompe la vista si algún producto fue eliminado)
        $productosPorConvenio = $convenios->getCollection()->mapWithKeys(function ($c) {
            $ids = json_decode($c->productos_afectados, true);
            return [$c->id => is_array($ids) ? array_values(array_unique(array_map('strval', $ids))) : []];
        });
        $nombresProductos = \App\Models\Producto::whereIn('id', $productosPorConvenio->flatten()->unique()->all())
            ->pluck('nombre', 'id');
        $maxChips = 3;
    @endphp

    <div class="card cl-card">
        <div class="cl-header">
            <div>
                <h4 class="cl-title">Convenios</h4>
                <p class="cl-sub">{{ $convenios->total() }} {{ $convenios->total() === 1 ? 'convenio registrado' : 'convenios registrados' }}</p>
            </div>
            <div class="cl-header-actions">
                <div class="cv-search cl-search">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3.5-3.5"></path>
                    </svg>
                    <input type="search" placeholder="Buscar por nombre, tipo, monto o producto…"
                        aria-label="Buscar convenios" wire:model.debounce.750ms="buscar">
                </div>
                <button type="button" class="btn btn-primary cl-btn-nuevo" data-bs-toggle="modal"
                    data-bs-target="#basicModal" wire:click="crearNuevo">
                    <i class="fa fa-plus me-2"></i>Nuevo convenio
                </button>
            </div>
        </div>

        <div class="cl-table" role="table" aria-label="Lista de convenios">
            <div class="cl-row cl-row-head" role="row">
                <div role="columnheader">Convenio</div>
                <div role="columnheader">Descuento</div>
                <div role="columnheader">Productos</div>
                <div role="columnheader">Vigencia</div>
                <div role="columnheader" class="cl-right">Acciones</div>
            </div>

            @forelse ($convenios as $convenio)
                @php
                    $ids = $productosPorConvenio[$convenio->id] ?? [];
                    $nClientes = $convenio->usuarios->count();
                @endphp
                <div class="cl-row" role="row" wire:key="convenio-{{ $convenio->id }}">
                    {{-- Convenio --}}
                    <div class="cl-cell cl-cell-main" role="cell">
                        <span class="cl-name">{{ $convenio->nombre_convenio }}</span>
                        <span class="cl-meta">
                            <i class="fa fa-users"></i>
                            {{ $nClientes }} {{ $nClientes === 1 ? 'cliente' : 'clientes' }}
                        </span>
                    </div>

                    {{-- Descuento --}}
                    <div class="cl-cell" role="cell" data-label="Descuento">
                        @include('admin.partials.convenio-descuento', ['convenio' => $convenio])
                    </div>

                    {{-- Productos --}}
                    <div class="cl-cell" role="cell" data-label="Productos">
                        @if (count($ids) === 0)
                            <span class="cl-muted" title="Sin productos: en el punto de venta se muestra para aplicarlo como descuento manual">Informativo (sin productos)</span>
                        @else
                            <div class="cl-products">
                                <span class="cl-products-count">{{ count($ids) }}
                                    {{ count($ids) === 1 ? 'producto' : 'productos' }}</span>
                                <div class="cl-chips">
                                    @foreach (array_slice($ids, 0, $maxChips) as $pid)
                                        @php $nombre = $nombresProductos[$pid] ?? null; @endphp
                                        <span class="cl-chip {{ $nombre ? '' : 'is-missing' }}"
                                            title="{{ $nombre ?? 'Producto eliminado (#' . $pid . ')' }}">
                                            {{ $nombre ?? 'Eliminado #' . $pid }}
                                        </span>
                                    @endforeach
                                    @if (count($ids) > $maxChips)
                                        <details class="cl-more">
                                            <summary>
                                                <span class="cl-more-open">+{{ count($ids) - $maxChips }} más</span>
                                                <span class="cl-more-close">Ver menos</span>
                                            </summary>
                                            <div class="cl-chips cl-chips-extra">
                                                @foreach (array_slice($ids, $maxChips) as $pid)
                                                    @php $nombre = $nombresProductos[$pid] ?? null; @endphp
                                                    <span class="cl-chip {{ $nombre ? '' : 'is-missing' }}"
                                                        title="{{ $nombre ?? 'Producto eliminado (#' . $pid . ')' }}">
                                                        {{ $nombre ?? 'Eliminado #' . $pid }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Vigencia --}}
                    <div class="cl-cell" role="cell" data-label="Vigencia">
                        @include('admin.partials.convenio-vigencia', ['convenio' => $convenio])
                    </div>

                    {{-- Acciones --}}
                    <div class="cl-cell cl-actions" role="cell">
                        <button type="button" class="cl-icon-btn" title="Editar convenio"
                            aria-label="Editar {{ $convenio->nombre_convenio }}" data-bs-toggle="modal"
                            data-bs-target="#basicModal" wire:click="editarConvenio({{ $convenio->id }})">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button type="button" class="cl-icon-btn is-danger" title="Eliminar convenio"
                            aria-label="Eliminar {{ $convenio->nombre_convenio }}"
                            wire:click="confirmarEliminacion('{{ $convenio->id }}')">
                            <i class="fa fa-trash"></i>
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
                        <span>Crea el primero con el botón “Nuevo convenio”.</span>
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


    {{-- Modal crear / editar convenio --}}
    @php
        $hoy = \Carbon\Carbon::now()->toDateString();
        $errorProductos = $errors->first('productos_afectados') ?: $errors->first('productos_afectados.*');
    @endphp
    <div class="modal fade cv-ui" id="basicModal" tabindex="-1" aria-labelledby="cpTitulo" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <form class="modal-content cv-modal" onsubmit="event.preventDefault(); ConvenioUI.guardar();" novalidate>
                <div class="cv-head">
                    <div class="cv-head-text">
                        <h5 class="cv-title" id="cpTitulo">{{ $is_editing ? 'Editar convenio' : 'Nuevo convenio' }}</h5>
                        <p class="cv-subtitle">
                            {{ $is_editing ? 'Modifica las condiciones y los productos con descuento.' : 'Define el descuento y los productos a los que aplica. Sin productos, el convenio es informativo.' }}
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="cv-body">
                    <div class="cv-grid">
                        {{-- Columna izquierda: datos del convenio --}}
                        <div>
                            <div class="cv-field">
                                <label class="cv-label" for="cpNombre">Nombre del convenio</label>
                                <input id="cpNombre" type="text" autocomplete="off"
                                    class="cv-input @error('nombre_convenio') is-invalid @enderror"
                                    placeholder="Ej. Empresa ACME" wire:model.defer="nombre_convenio">
                                @error('nombre_convenio')
                                    <span class="cv-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="cv-field">
                                <span class="cv-label">Tipo de descuento</span>
                                <div class="cv-types" role="radiogroup" aria-label="Tipo de descuento">
                                    <label class="cv-type {{ $tipo_descuento === 'porcentaje' ? 'is-active' : '' }}">
                                        <input type="radio" name="cp_tipo" value="porcentaje"
                                            wire:model="tipo_descuento">
                                        <span class="cv-type-icon">%</span>
                                        <span>
                                            <strong>Porcentaje</strong>
                                            <small>Sobre el precio del producto</small>
                                        </span>
                                    </label>
                                    <label class="cv-type {{ $tipo_descuento === 'fijo' ? 'is-active' : '' }}">
                                        <input type="radio" name="cp_tipo" value="fijo" wire:model="tipo_descuento">
                                        <span class="cv-type-icon">Bs</span>
                                        <span>
                                            <strong>Monto fijo</strong>
                                            <small>Se descuenta por unidad</small>
                                        </span>
                                    </label>
                                </div>
                                @error('tipo_descuento')
                                    <span class="cv-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="cv-field">
                                <label class="cv-label" for="cpValor">Valor del descuento</label>
                                <div class="cv-affix">
                                    <input id="cpValor" type="number" step="0.01" min="0" inputmode="decimal"
                                        class="cv-input @error('valor_descuento') is-invalid @enderror"
                                        placeholder="0.00" wire:model.defer="valor_descuento">
                                    <span>{{ $tipo_descuento === 'porcentaje' ? '%' : 'Bs' }}</span>
                                </div>
                                @error('valor_descuento')
                                    <span class="cv-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="cv-field">
                                <label class="cv-label" for="cpFecha">Fecha límite</label>
                                <input id="cpFecha" type="date" min="{{ $hoy }}" required
                                    class="cv-input @error('fecha_limite') is-invalid @enderror"
                                    wire:model.defer="fecha_limite">
                                @error('fecha_limite')
                                    <span class="cv-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Columna derecha: productos afectados --}}
                        <div class="cv-field">
                            <span class="cv-label">
                                Productos con descuento (opcional)
                                <small id="cpContador" wire:ignore>0 seleccionados</small>
                            </span>

                            <div wire:ignore>
                                <div class="cv-chips" id="cpChips" aria-live="polite"></div>

                                <div class="cv-toolbar">
                                    <div class="cv-search">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="11" cy="11" r="7"></circle>
                                            <path d="m20 20-3.5-3.5"></path>
                                        </svg>
                                        <input type="search" id="cpBuscar" autocomplete="off"
                                            placeholder="Buscar producto…" aria-label="Buscar productos">
                                    </div>
                                    <div class="cv-segment" role="tablist" aria-label="Filtro de productos">
                                        <button type="button" role="tab" class="is-active" data-filtro="todos">
                                            Todos <span id="cpCountTodos">0</span>
                                        </button>
                                        <button type="button" role="tab" data-filtro="seleccionados">
                                            Elegidos <span id="cpCountSel">0</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="cv-bulk">
                                    <span id="cpInfoLista" class="cv-muted"></span>
                                    <div class="cv-bulk-actions">
                                        <button type="button" class="cv-link" id="cpSelVisibles">Seleccionar visibles</button>
                                        <span class="cv-dot" aria-hidden="true">·</span>
                                        <button type="button" class="cv-link cv-link-danger" id="cpLimpiar">Quitar todos</button>
                                    </div>
                                </div>

                                <div class="cv-list cv-list-sm" id="cpLista" role="listbox"
                                    aria-multiselectable="true" aria-label="Productos"></div>
                            </div>

                            @if ($errorProductos)
                                <span class="cv-error">{{ $errorProductos }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="cv-foot">
                    <div class="cv-resumen">
                        @if ($is_editing)
                            <span>Los cambios se aplican a las ventas abiertas al recalcularse.</span>
                        @endif
                    </div>
                    <div class="cv-foot-actions">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled"
                            wire:target="submit">
                            <span wire:loading.remove wire:target="submit">
                                {{ $is_editing ? 'Guardar cambios' : 'Crear convenio' }}
                            </span>
                            <span wire:loading wire:target="submit">Guardando…</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('css')
        @include('admin.partials.ui-kit')
    @endpush

    @push('scripts')
        <script>
            /**
             * Selector de productos del convenio.
             * Reemplaza el select2: recibe los mismos datos del evento 'iniciar-librerias'
             * y entrega los ids a 'productos_afectados' antes de llamar a submit().
             */
            const ConvenioUI = (() => {
                const LIMITE_RENDER = 300;
                const PALETA = ['#0E8A74', '#2563EB', '#7C3AED', '#DB2777', '#EA580C', '#0891B2', '#65A30D', '#9333EA'];

                let todos = [];
                let porId = new Map();
                let seleccion = new Set();
                let filtro = 'todos';
                let termino = '';
                let cargado = false;
                let componente = null;

                const el = (id) => document.getElementById(id);

                const escapar = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[c]));

                const normalizar = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

                const iniciales = (nombre) => nombre.replace(/[^\p{L}\p{N}\s]/gu, ' ').trim().split(/\s+/)
                    .slice(0, 2).map((p) => p[0] || '').join('').toUpperCase() || '·';

                // El backend envía "Nombre - Bs. 12.00"; se separa solo para mostrarlo mejor
                const parsear = (id, texto) => {
                    const m = String(texto).match(/^(.*)\s-\sBs\.\s*([\d.,]+)\s*$/);
                    const nombre = m ? m[1].trim() : String(texto);
                    return {
                        id: String(id),
                        nombre,
                        precio: m ? m[2] : '',
                        busqueda: normalizar(nombre),
                        color: PALETA[Number(id) % PALETA.length],
                        ini: iniciales(nombre),
                    };
                };

                function visibles() {
                    const q = normalizar(termino);
                    return todos.filter((p) =>
                        (filtro === 'todos' || seleccion.has(p.id)) &&
                        (!q || p.busqueda.includes(q))
                    );
                }

                function filaHTML(p) {
                    const sel = seleccion.has(p.id);
                    return `
                        <button type="button" class="cv-item${sel ? ' is-selected' : ''}" role="option"
                                aria-selected="${sel}" data-id="${escapar(p.id)}">
                            <span class="cv-avatar cv-avatar-sq" style="background:${p.color}">${escapar(p.ini)}</span>
                            <span class="cv-info"><span class="cv-name">${escapar(p.nombre)}</span></span>
                            ${p.precio ? `<span class="cv-price">Bs ${escapar(p.precio)}</span>` : ''}
                            <span class="cv-check" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                            </span>
                        </button>`;
                }

                function renderChips() {
                    const chips = el('cpChips');
                    if (seleccion.size === 0) {
                        chips.innerHTML = '<span class="cv-placeholder">Sin productos: el convenio será informativo. En el punto de venta se mostrará para aplicarlo como descuento manual.</span>';
                        return;
                    }
                    chips.innerHTML = [...seleccion].map((id) => {
                        const p = porId.get(id);
                        const nombre = p ? p.nombre : `Producto #${id}`;
                        return `<span class="cv-chip-sel" title="${escapar(nombre)}">
                                    <span>${escapar(nombre)}</span>
                                    <button type="button" data-quitar="${escapar(id)}" aria-label="Quitar ${escapar(nombre)}">×</button>
                                </span>`;
                    }).join('');
                }

                function render() {
                    const lista = el('cpLista');
                    const items = visibles();

                    if (todos.length === 0) {
                        lista.innerHTML = `<div class="cv-state"><strong>No hay productos</strong>
                            No se encontraron productos registrados.</div>`;
                    } else if (items.length === 0) {
                        lista.innerHTML = filtro === 'seleccionados' && !termino
                            ? `<div class="cv-state"><strong>Ningún producto elegido</strong>
                                Márcalos desde la pestaña “Todos”.</div>`
                            : `<div class="cv-state"><strong>Sin resultados</strong>
                                No hay productos que coincidan con “${escapar(termino)}”.</div>`;
                    } else {
                        let html = items.slice(0, LIMITE_RENDER).map(filaHTML).join('');
                        if (items.length > LIMITE_RENDER) {
                            html += `<div class="cv-more">Mostrando ${LIMITE_RENDER} de ${items.length}. Refina la búsqueda para ver más.</div>`;
                        }
                        lista.innerHTML = html;
                    }

                    el('cpCountTodos').textContent = todos.length;
                    el('cpCountSel').textContent = seleccion.size;
                    el('cpContador').textContent = `${seleccion.size} seleccionado${seleccion.size === 1 ? '' : 's'}`;
                    el('cpInfoLista').textContent = termino
                        ? `${items.length} resultado${items.length === 1 ? '' : 's'}`
                        : `${items.length} producto${items.length === 1 ? '' : 's'}`;
                    renderChips();
                }

                function mostrarCargando() {
                    el('cpLista').innerHTML = Array.from({ length: 5 }, () => `
                        <div class="cv-skel">
                            <i style="width:36px;height:36px;border-radius:10px"></i>
                            <div style="flex:1"><i style="width:55%;height:10px"></i></div>
                            <i style="width:48px;height:10px"></i>
                        </div>`).join('');
                    el('cpChips').innerHTML = '';
                    el('cpCountTodos').textContent = '0';
                    el('cpCountSel').textContent = '0';
                    el('cpContador').textContent = '';
                    el('cpInfoLista').textContent = 'Cargando productos…';
                }

                function cambiarFiltro(nuevo) {
                    filtro = nuevo;
                    document.querySelectorAll('#basicModal .cv-segment button').forEach((b) => {
                        const activo = b.dataset.filtro === nuevo;
                        b.classList.toggle('is-active', activo);
                        b.setAttribute('aria-selected', activo);
                    });
                    if (cargado) render();
                }

                function alternar(id) {
                    seleccion.has(id) ? seleccion.delete(id) : seleccion.add(id);
                    const scroll = el('cpLista').scrollTop;
                    render();
                    el('cpLista').scrollTop = scroll;
                }

                function init(wire) {
                    componente = wire;
                    window.cvAplicarColorTema(el('basicModal'));
                    window.cvAplicarColorTema(el('conveniosPage'));

                    let t;
                    el('cpBuscar').addEventListener('input', (e) => {
                        clearTimeout(t);
                        t = setTimeout(() => {
                            termino = e.target.value.trim();
                            if (cargado) render();
                        }, 120);
                    });
                    // Enter en el buscador no debe enviar el formulario
                    el('cpBuscar').addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') e.preventDefault();
                    });

                    document.querySelectorAll('#basicModal .cv-segment button')
                        .forEach((b) => b.addEventListener('click', () => cambiarFiltro(b.dataset.filtro)));

                    el('cpLista').addEventListener('click', (e) => {
                        const item = e.target.closest('.cv-item');
                        if (!item) return;
                        alternar(item.dataset.id);
                        el('cpLista').querySelector(`.cv-item[data-id="${CSS.escape(item.dataset.id)}"]`)?.focus();
                    });

                    el('cpChips').addEventListener('click', (e) => {
                        const btn = e.target.closest('[data-quitar]');
                        if (btn) alternar(btn.dataset.quitar);
                    });

                    el('cpSelVisibles').addEventListener('click', () => {
                        visibles().slice(0, LIMITE_RENDER).forEach((p) => seleccion.add(p.id));
                        render();
                    });

                    el('cpLimpiar').addEventListener('click', () => {
                        seleccion.clear();
                        render();
                    });

                    jQuery('#basicModal').on('show.bs.modal', () => {
                        if (!cargado) mostrarCargando();
                    });

                    jQuery('#basicModal').on('hidden.bs.modal', () => {
                        cargado = false;
                        todos = [];
                        porId = new Map();
                        seleccion = new Set();
                        termino = '';
                        el('cpBuscar').value = '';
                        cambiarFiltro('todos');
                        componente.resetearCampos();
                    });
                }

                return {
                    init,
                    cargar(productos, seleccionados) {
                        todos = Object.entries(productos || {}).map(([id, texto]) => parsear(id, texto))
                            .sort((a, b) => a.nombre.localeCompare(b.nombre, 'es'));
                        porId = new Map(todos.map((p) => [p.id, p]));
                        seleccion = new Set((seleccionados || []).map(String));
                        termino = '';
                        el('cpBuscar').value = '';
                        cargado = true;
                        cambiarFiltro('todos');
                    },
                    guardar() {
                        // Se envía la selección diferida junto con submit(): una sola petición
                        componente.set('productos_afectados', [...seleccion], true);
                        componente.call('submit');
                    },
                };
            })();

            const escaparHTML = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            Livewire.on('iniciar-librerias', (productos, seleccionados) => {
                ConvenioUI.cargar(productos, seleccionados);
            });

            Livewire.on('sweet-detalles-productos-eliminar', (usuarios, convenioId) => {
                var usersAffected = usuarios;
                let htmlContent = '<p>¿Estás seguro de eliminar el convenio?</p>';

                if (Array.isArray(usersAffected) && usersAffected.length > 0) {
                    htmlContent += `
            <p>Al eliminar el convenio también eliminarás a los usuarios registrados en él:</p>
            <ul style="text-align: left; margin: 10px 0 0 20px;">
                ${usersAffected.map(user => `<li> - ${escaparHTML(user)}</li>`).join('')}
            </ul>
        `;
                } else {
                    htmlContent += '<p>Esta acción no se puede deshacer.</p>';
                }
                Swal.fire({
                    title: 'Confirmar eliminación',
                    html: htmlContent,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('eliminar-convenio', convenioId);
                    }
                });
            });

            Livewire.on('mostrar-notificacion', (event) => {
                Toast.fire({
                    title: event.mensaje,
                    icon: event.icono
                });

            });
            Livewire.on('cerrar-modal', (event) => {
                $('.modal').modal('hide');

            });

            document.addEventListener('livewire:load', function() {
                ConvenioUI.init(@this);
            });
        </script>
    @endpush

</div>
