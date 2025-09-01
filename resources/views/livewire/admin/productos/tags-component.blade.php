<div>
    <!-- Mensajes de éxito/error -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="form-head mb-1 d-flex flex-wrap align-items-center">
            <div class="me-auto">
                <h2 class="font-w600 mb-0">Listado de tags
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
                    wire:click="crearNuevo"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>

        @if ($tags->isNotEmpty() && $alerta == true)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible alert-alt solid fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"
                            wire:click="cerrarAlerta">
                        </button>
                        <i class="fa fa-exclamation-triangle fa-beat-fade me-1"></i> Los tags que no tengan asociado
                        ningún producto <strong>no serán mostrados!</strong>.
                    </div>
                </div>
            </div>
        @endif

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body svg-area">
                    @if ($tags->isNotEmpty())
                        <div class="row">
                            @foreach ($tags as $tag)
                                <div class="col-xl-2 col-lg-3 col-xxl-3 col-md-4 col-sm-6 col-12 m-b30">
                                    <div class="svg-icons-ov style-1">
                                        <div class="dropdown" style="position: absolute; right: 10%; top: 10%;">
                                            <button type="button" class="btn btn-sm btn-info light sharp"
                                                data-bs-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <circle fill="#000000" cx="5" cy="12" r="2">
                                                        </circle>
                                                        <circle fill="#000000" cx="12" cy="12" r="2">
                                                        </circle>
                                                        <circle fill="#000000" cx="19" cy="12" r="2">
                                                        </circle>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"
                                                    wire:click="editarProductos('{{ $tag->id }}')">Agregar/editar
                                                    Productos</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#modalTag" wire:click="editar({{ $tag->id }})"
                                                    wire:key="editar-{{ $tag->id }}">Editar</a>
                                                @if ($tag->productos->count() > 0)
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        onclick="eliminarTagInvalido()"
                                                        wire:key="eliminar-{{ $tag->id }}">Eliminar</a>
                                                @else
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        onclick="eliminarTag('{{ $tag->id }}', {{ $tag->productos->count() }})"
                                                        wire:key="eliminar-{{ $tag->id }}">Eliminar</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="svg-icons-prev mt-5">
                                            <i data-lucide="{{ $tag->icono }}"></i>
                                        </div>
                                        <div class="svg-classname">
                                            <a href="javascript:void(0)"
                                                class="badge badge-rounded badge-primary text-white">{{ $tag->nombre }}</a>
                                        </div>

                                        <div class="row"
                                            style="margin-left: 5%; margin-right: 5%; margin-bottom: 10px; margin-top: 10px;">
                                            <div class="bootstrap-badge">

                                                @php $prods = $tag->productos; @endphp
                                                @if ($prods->count() > 0)
                                                    <a href="javascript:void(0)"
                                                        class="badge badge-rounded badge-outline-info text-info"
                                                        wire:click="editarProductos('{{ $tag->id }}')">
                                                        Productos
                                                        ({{ $prods->count() }} asignados)
                                                    </a>
                                                @else
                                                    <small class="text-danger"> <i
                                                            class="fa fa-exclamation-triangle"></i>
                                                        Sin productos</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">No hay tags</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Paginación -->
        <div class="col-12 d-flex justify-content-center">
            {{ $tags->links() }}
        </div>

        <div class="col-12 text-center">
            <small>Mostrando {{ $tags->count() }} registros</small>
        </div>
    </div>

    <!-- Modal para crear/editar tags -->
    <div class="modal fade" id="modalTag" tabindex="-1" aria-labelledby="modalTagLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTagLabel">
                        {{ $editing ? 'Editar Tag' : 'Crear Nuevo Tag' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="cerrarModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="guardar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nombre" class="form-label">Nombre del Tag *</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" wire:model.defer="nombre"
                                        placeholder="Ingrese el nombre del tag">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="icono" class="form-label">Icono *</label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @error('icono') is-invalid @enderror" id="icono"
                                            wire:model="icono" placeholder="Seleccione un icono" readonly>
                                        <button type="button" class="btn btn-outline-secondary"
                                            id="btnSelectorIconos">
                                            <i class="fa fa-search"></i> Seleccionar
                                        </button>
                                    </div>
                                    @error('icono')
                                        <div class="invalid-feedback" style="display: block !important;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Vista previa del icono -->
                        @if ($icono)
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Vista previa del icono:</label>
                                        <div class="icon-preview" wire:key="icono-{{ $icono }}">
                                            <i data-lucide="{{ $icono }}"
                                                style="width: 32px; height: 32px;"></i>
                                            <span class="ms-2">{{ $icono }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardar"
                        onclick="guardarEditarButton()">
                        {{ $editing ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gestionar productos del tag -->
    @if ($modalProductos)

        <div class="modal fade show" id="modalProductos" tabindex="-1" aria-labelledby="modalProductosLabel"
            aria-hidden="false" wire:ignore.self style="display: block;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProductosLabel">
                            Gestionar Productos del Tag
                            @if ($productosDelTag && $productosDelTag->count() > 0)
                                <span class="badge bg-primary ms-2">{{ $productosDelTag->count() }} productos</span>
                            @endif
                            @if ($searchProductos && strlen($searchProductos) >= 2)
                                <span class="badge bg-info ms-2">Buscando: "{{ $searchProductos }}"</span>
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalProductos"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- tag seleccionado -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="text-muted">Tag seleccionado: <strong>{{ $tagSeleccionado->nombre }}</strong></p>
                            </div>
                        </div>

                        <!-- Buscador de productos -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Buscar productos..."
                                        wire:model.debounce.750ms="searchProductos"
                                        wire:keydown.enter="buscarProductos">
                                    @if ($searchProductos)
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="limpiarBusqueda">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                                @if ($searchProductos && strlen($searchProductos) < 2)
                                    <small class="text-muted">Escriba al menos 2 caracteres para buscar</small>
                                @endif
                                @if ($searchProductos && strlen($searchProductos) >= 2)
                                    <small class="text-info">
                                        <i class="fa fa-info-circle"></i>
                                        Presione Enter o haga clic en Buscar para ejecutar la búsqueda
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <!-- Productos disponibles -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Productos Disponibles</h6>
                                        <span class="badge bg-secondary">{{ $productosDisponibles->count() }}
                                            productos</span>
                                    </div>
                                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                        @if ($productosDisponibles->isNotEmpty())
                                            <div class="list-group">
                                                @foreach ($productosDisponibles as $producto)
                                                    <div
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $producto->nombre }}</strong>
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $producto->subcategoria->nombre ?? 'Sin categoría' }}</small>
                                                        </div>
                                                        <button class="btn btn-sm btn-success"
                                                            wire:click="agregarProducto({{ $producto->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="agregarProducto">
                                                            <span wire:loading.remove wire:target="agregarProducto">
                                                                <i class="fa fa-plus"></i>
                                                            </span>
                                                            <span wire:loading wire:target="agregarProducto">
                                                                <div class="spinner-border spinner-border-sm"
                                                                    role="status">
                                                                    <span class="sr-only">Agregando...</span>
                                                                </div>
                                                            </span>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            @if ($searchProductos && strlen($searchProductos) >= 2)
                                                <div class="text-center">
                                                    <p class="text-muted">No se encontraron productos con
                                                        "{{ $searchProductos }}"</p>
                                                    <button class="btn btn-sm btn-outline-primary"
                                                        wire:click="limpiarBusqueda">
                                                        <i class="fa fa-times"></i> Limpiar búsqueda
                                                    </button>
                                                </div>
                                            @elseif ($searchProductos && strlen($searchProductos) < 2)
                                                <p class="text-muted text-center">Escriba al menos 2 caracteres para
                                                    buscar</p>
                                            @else
                                                <p class="text-muted text-center">No hay productos disponibles para
                                                    agregar</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Productos del tag -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Productos del Tag</h6>
                                    </div>
                                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                        @if ($productosDelTag && count($productosDelTag) > 0)
                                            <div class="list-group">
                                                @foreach ($productosDelTag as $producto)
                                                    <div
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $producto->nombre }}</strong>
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $producto->subcategoria->nombre ?? 'Sin categoría' }}</small>
                                                        </div>
                                                        <button class="btn btn-sm btn-danger"
                                                            wire:click="quitarProducto({{ $producto->id }})"
                                                            wire:loading.attr="disabled" wire:target="quitarProducto">
                                                            <span wire:loading.remove wire:target="quitarProducto">
                                                                <i class="fa fa-minus"></i>
                                                            </span>
                                                            <span wire:loading wire:target="quitarProducto">
                                                                <div class="spinner-border spinner-border-sm"
                                                                    role="status">
                                                                    <span class="sr-only">Quitando...</span>
                                                                </div>
                                                            </span>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted text-center">No hay productos en este tag</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="cerrarModalProductos">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal selector de iconos -->
    <div class="modal fade" id="modalSelectorIconos" tabindex="-1" aria-labelledby="modalSelectorIconosLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSelectorIconosLabel">Seleccionar Icono</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <input type="text" class="form-control" id="searchIconos"
                                placeholder="Buscar iconos...">
                        </div>
                    </div>
                    <div class="row" id="iconosContainer">
                        <!-- Los iconos se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos para el modal de productos */
        .modal.show {
            display: block !important;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }

        .svg-icons-ov.style-1 {
            padding: 15px !important;
            flex-direction: column !important;
            cursor: pointer !important;
        }

        .svg-icons-ov.style-1:hover {
            background-color: #20c99615 !important;
            border: 1px solid #20c997 !important;
        }


        .svg-icons-ov.style-1:hover .svg-icons-prev {
            color: #20c997 !important;
            animation: pulse 1s infinite;
        }



        .svg-icons-ov {
            -webkit-transition: all 0.5s !important;
            -ms-transition: all 0.5s !important;
            transition: all 0.5s !important;
            align-items: center !important;
            padding: 15px 15px 75px !important;
            position: relative !important;
            margin-bottom: 10px !important;
            border: 1px solid #E6E6E6 !important;
            border-radius: 5px !important;
            margin-bottom: 30px !important;
            display: flex !important;
        }

        .svg-icons-ov.style-1 .svg-icons-prev {
            display: inline-block !important;
            width: 3.125rem !important;
            height: 3.125rem !important;
            line-height: 3.263rem !important;
            text-align: center !important;
            background-color: var(--rgba-primary-1) !important;
            border-radius: 50% !important;
            margin-bottom: 0.688rem !important;
            margin-right: 0 !important;
        }

        .svg-icons-ov .svg-icons-prev {
            margin-right: 15px !important;
        }

        .svg-icons-prev svg {
            width: 40px !important;
            height: 40px !important;
        }


        .tag-actions {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .icon-preview {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .icono-item {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .icono-item:hover {
            background-color: #f8f9fa;
            border-color: #000000;
        }

        .icono-item.selected {
            background-color: #000000;
            color: white;
            border-color: #000000;
        }

        .icono-item svg {
            color: currentColor;
        }

        .icono-item:hover svg {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        .icon-preview svg {
            color: #000000;
        }

        .tag-actions .btn {
            opacitstyle8;
            style transition: opacity 0.3s ease;
        }

        .tag-actions .btn:hover {
            opacity: 1;
        }

        .svg-icons-ov:hover .tag-actions .btn {
            opacity: 1;
        }
    </style>


    <!-- CDN de Lucide Icons -->
    @push('footer')
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
        <script>
            // console.log('Lucide Icons cargados');
            document.addEventListener('DOMContentLoaded', function() {
                lucide.createIcons();
            });
        </script>
    @endpush
    <script>
        function guardarEditarButton() {
            setTimeout(() => {
                if (window.lucide && window.lucide.createIcons) {
                    window.lucide.createIcons();
                }
            }, 1000);
        }

        document.addEventListener("livewire:load", () => {
            Livewire.on('renderizar-icono-modal-creacion-listado', function() {
                console.log('Renderizando icono modal creacion listado');
                if (window.lucide && window.lucide.createIcons) {
                    window.lucide.createIcons();
                }
            });
            document.querySelectorAll(".modal").forEach(modal => {
                modal.addEventListener("shown.bs.modal", () => {
                    // Contar todos los backdrops en la página
                    const backdrops = document.querySelectorAll(".modal-backdrop");

                    if (backdrops.length > 1) {
                        // Mantener solo el último y eliminar los demás
                        for (let i = 0; i < backdrops.length - 1; i++) {
                            backdrops[i].remove();
                        }
                    }
                    console.log(
                        `Modal abierto: ${modal.id}, backdrops actuales: ${backdrops.length}`);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            let iconosDisponibles = [];
            let iconosFiltrados = [];

            // Elementos DOM
            const searchIconosInput = document.getElementById('searchIconos');
            const iconosContainer = document.getElementById('iconosContainer');
            const btnSelectorIconos = document.getElementById('btnSelectorIconos');

            // Lista completa de iconos de Lucide (versión actual)
            const listaIconosLucide = [
                'a-arrow-down', 'a-arrow-up', 'a-large-small', 'accessibility', 'activity', 'air-vent',
                'airplay', 'alarm-check', 'alarm-clock', 'alarm-clock-check', 'alarm-clock-minus',
                'alarm-clock-off', 'alarm-clock-plus', 'alarm-minus', 'alarm-plus', 'alarm-smoke',
                'album', 'alert-circle', 'alert-octagon', 'alert-triangle', 'align-center',
                'align-center-horizontal', 'align-center-vertical', 'align-end-horizontal',
                'align-end-vertical', 'align-horizontal-distribute-center',
                'align-horizontal-distribute-end', 'align-horizontal-distribute-start',
                'align-horizontal-justify-center', 'align-horizontal-justify-end',
                'align-horizontal-justify-start', 'align-horizontal-space-around',
                'align-horizontal-space-between', 'align-justify', 'align-left', 'align-right',
                'align-start-horizontal', 'align-start-vertical', 'align-vertical-distribute-center',
                'align-vertical-distribute-end', 'align-vertical-distribute-start',
                'align-vertical-justify-center', 'align-vertical-justify-end',
                'align-vertical-justify-start', 'align-vertical-space-around',
                'align-vertical-space-between', 'ampersand', 'ampersands', 'anchor', 'angry',
                'annoyed', 'antenna', 'aperture', 'app-window', 'apple', 'archive', 'archive-restore',
                'armchair', 'arrow-big-down', 'arrow-big-down-dash', 'arrow-big-left',
                'arrow-big-left-dash', 'arrow-big-right', 'arrow-big-right-dash', 'arrow-big-up',
                'arrow-big-up-dash', 'arrow-down', 'arrow-down-circle', 'arrow-down-from-line',
                'arrow-down-left', 'arrow-down-left-from-circle', 'arrow-down-right',
                'arrow-down-right-from-circle', 'arrow-down-to-dot', 'arrow-down-to-line',
                'arrow-left', 'arrow-left-circle', 'arrow-left-from-line', 'arrow-left-right',
                'arrow-left-to-line', 'arrow-right', 'arrow-right-circle', 'arrow-right-from-line',
                'arrow-right-left', 'arrow-right-to-line', 'arrow-up', 'arrow-up-circle',
                'arrow-up-down', 'arrow-up-from-dot', 'arrow-up-from-line', 'arrow-up-left',
                'arrow-up-left-from-circle', 'arrow-up-right', 'arrow-up-right-from-circle',
                'arrow-up-to-line', 'asterisk', 'at-sign', 'atom', 'audio-lines', 'audio-waveform',
                'award', 'axe', 'axis-3d', 'baby', 'backpack', 'badge', 'badge-alert',
                'badge-cent', 'badge-check', 'badge-dollar-sign', 'badge-euro', 'badge-help',
                'badge-indian-rupee', 'badge-info', 'badge-japanese-yen', 'badge-minus',
                'badge-percent', 'badge-plus', 'badge-pound-sterling', 'badge-russian-ruble',
                'badge-swiss-franc', 'badge-x', 'baggage-claim', 'ban', 'banana', 'banknote',
                'bar-chart', 'bar-chart-2', 'bar-chart-3', 'bar-chart-4', 'bar-chart-big',
                'bar-chart-horizontal', 'bar-chart-horizontal-big', 'bar-chart-vertical',
                'bar-chart-vertical-big', 'baseline', 'bath', 'battery', 'battery-charging',
                'battery-full', 'battery-low', 'battery-medium', 'battery-warning', 'beaker',
                'bean', 'bean-off', 'bed', 'bed-double', 'bed-single', 'beef', 'beer', 'bell',
                'bell-dot', 'bell-electric', 'bell-minus', 'bell-off', 'bell-plus', 'bell-ring',
                'bike', 'binary', 'biohazard', 'bird', 'bitcoin', 'blinds', 'bluetooth',
                'bluetooth-connected', 'bluetooth-searching', 'bold', 'bolt', 'bomb', 'bone',
                'book', 'book-a', 'book-audio', 'book-check', 'book-copy', 'book-dashed',
                'book-down', 'book-headphones', 'book-heart', 'book-image', 'book-key',
                'book-lock', 'book-marked', 'book-minus', 'book-open', 'book-open-check',
                'book-open-text', 'book-plus', 'book-text', 'book-type', 'book-up', 'book-up-2',
                'book-user', 'book-x', 'bookmark', 'bookmark-check', 'bookmark-minus',
                'bookmark-plus', 'bookmark-x', 'boom-box', 'bot', 'box', 'box-select', 'boxes',
                'braces', 'brackets', 'brain', 'brain-circuit', 'brain-cog', 'briefcase',
                'briefcase-business', 'briefcase-medical', 'bring-to-front', 'brush', 'bug',
                'bug-off', 'bug-play', 'building', 'building-2', 'bus', 'cable', 'cable-car',
                'cake', 'calculator', 'calendar', 'calendar-1', 'calendar-2', 'calendar-check',
                'calendar-check-2', 'calendar-clock', 'calendar-days', 'calendar-fold',
                'calendar-heart', 'calendar-minus', 'calendar-off', 'calendar-plus',
                'calendar-range', 'calendar-search', 'calendar-x', 'calendar-x-2', 'camera',
                'camera-off', 'candy', 'candy-off', 'cannabis', 'car', 'car-front', 'car-taxi-front',
                'caravan', 'carrot', 'case-lower', 'case-sensitive', 'case-upper', 'cassette-tape',
                'cast', 'castle', 'cat', 'cctv', 'check', 'check-check', 'chef-hat', 'cherry',
                'chevron-down', 'chevron-first', 'chevron-last', 'chevron-left', 'chevron-right',
                'chevron-up', 'chevrons-down', 'chevrons-down-up', 'chevrons-left',
                'chevrons-left-right', 'chevrons-right', 'chevrons-right-left', 'chevrons-up',
                'chevrons-up-down', 'chrome', 'church', 'cigarette', 'cigarette-off', 'circle',
                'circle-alert', 'circle-arrow-down', 'circle-arrow-left', 'circle-arrow-out-down-left',
                'circle-arrow-out-down-right', 'circle-arrow-out-up-left', 'circle-arrow-out-up-right',
                'circle-arrow-right', 'circle-arrow-up', 'circle-check', 'circle-check-big',
                'circle-chevron-down', 'circle-chevron-left', 'circle-chevron-right',
                'circle-chevron-up', 'circle-dashed', 'circle-divide', 'circle-dollar-sign',
                'circle-dot', 'circle-dot-dashed', 'circle-ellipsis', 'circle-equal',
                'circle-fading-plus', 'circle-gauge', 'circle-help', 'circle-minus', 'circle-off',
                'circle-parking', 'circle-parking-off', 'circle-pause', 'circle-percent',
                'circle-play', 'circle-plus', 'circle-power', 'circle-slash', 'circle-slash-2',
                'circle-stop', 'circle-user', 'circle-user-round', 'circle-x', 'circuit-board',
                'citrus', 'clapperboard', 'clipboard', 'clipboard-check', 'clipboard-copy',
                'clipboard-list', 'clipboard-minus', 'clipboard-paste', 'clipboard-pen',
                'clipboard-pen-line', 'clipboard-plus', 'clipboard-type', 'clipboard-x',
                'clock', 'clock-1', 'clock-10', 'clock-11', 'clock-12', 'clock-2', 'clock-3',
                'clock-4', 'clock-5', 'clock-6', 'clock-7', 'clock-8', 'clock-9', 'clock-alert',
                'clock-arrow-down', 'clock-arrow-up', 'cloud', 'cloud-cog', 'cloud-download',
                'cloud-drizzle', 'cloud-fog', 'cloud-hail', 'cloud-lightning', 'cloud-moon',
                'cloud-moon-rain', 'cloud-off', 'cloud-rain', 'cloud-rain-wind', 'cloud-snow',
                'cloud-sun', 'cloud-sun-rain', 'cloud-upload', 'cloudy', 'clover', 'club',
                'code', 'code-2', 'codepen', 'codesandbox', 'coffee', 'cog', 'coins', 'columns',
                'columns-2', 'columns-3', 'columns-4', 'combine', 'command', 'compass', 'component',
                'computer', 'concierge-bell', 'construction', 'contact', 'contact-2', 'container',
                'contrast', 'cookie', 'cooking-pot', 'copy', 'copy-check', 'copy-minus',
                'copy-plus', 'copy-slash', 'copy-x', 'copyleft', 'copyright', 'corner-down-left',
                'corner-down-right', 'corner-left-down', 'corner-left-up', 'corner-right-down',
                'corner-right-up', 'corner-up-left', 'corner-up-right', 'cpu', 'creative-commons',
                'credit-card', 'croissant', 'crop', 'cross', 'crosshair', 'crown', 'crown-off',
                'crypto', 'cup-soda', 'curly-braces', 'currency', 'cylinder', 'database',
                'database-backup', 'database-zap', 'delete', 'dessert', 'diameter', 'diamond',
                'diamond-minus', 'diamond-percent', 'diamond-plus', 'dice-1', 'dice-2', 'dice-3',
                'dice-4', 'dice-5', 'dice-6', 'dices', 'diff', 'disc', 'disc-2', 'disc-3',
                'disc-album', 'divide', 'dna', 'dna-off', 'dock', 'dog', 'dollar-sign', 'donut',
                'door-closed', 'door-open', 'dot', 'download', 'download-cloud', 'drafting-compass',
                'drama', 'dribbble', 'drill', 'droplet', 'droplets', 'drum', 'drumstick',
                'dumbbell', 'ear', 'ear-off', 'earth', 'earth-lock', 'eclipse', 'egg', 'egg-fried',
                'egg-off', 'ellipsis', 'ellipsis-vertical', 'equal', 'equal-not', 'eraser',
                'euro', 'expand', 'external-link', 'eye', 'eye-off', 'facebook', 'factory',
                'fan', 'fast-forward', 'feather', 'fence', 'ferris-wheel', 'figma', 'file',
                'file-archive', 'file-audio', 'file-audio-2', 'file-axis-3d', 'file-badge',
                'file-badge-2', 'file-box', 'file-chart-column', 'file-chart-line', 'file-chart-pie',
                'file-check', 'file-check-2', 'file-clock', 'file-code', 'file-code-2',
                'file-cog', 'file-diff', 'file-digit', 'file-down', 'file-heart', 'file-image',
                'file-input', 'file-json', 'file-json-2', 'file-key', 'file-key-2', 'file-line-chart',
                'file-lock', 'file-lock-2', 'file-minus', 'file-minus-2', 'file-music',
                'file-output', 'file-pen', 'file-pen-line', 'file-plus', 'file-plus-2',
                'file-question', 'file-scan', 'file-search', 'file-search-2', 'file-sliders',
                'file-spreadsheet', 'file-stack', 'file-symlink', 'file-terminal', 'file-text',
                'file-type', 'file-type-2', 'file-up', 'file-user', 'file-video', 'file-video-2',
                'file-volume', 'file-volume-2', 'file-warning', 'file-x', 'file-x-2', 'files',
                'film', 'filter', 'filter-x', 'fingerprint', 'fire-extinguisher', 'fish',
                'fish-off', 'flag', 'flag-off', 'flag-triangle-left', 'flag-triangle-right',
                'flame', 'flame-kindling', 'flashlight', 'flashlight-off', 'flask-conical',
                'flask-conical-off', 'flask-round', 'flip-horizontal', 'flip-horizontal-2',
                'flip-vertical', 'flip-vertical-2', 'flower', 'flower-2', 'focus', 'fold-horizontal',
                'fold-vertical', 'folder', 'folder-archive', 'folder-check', 'folder-clock',
                'folder-closed', 'folder-code', 'folder-cog', 'folder-dot', 'folder-down',
                'folder-git', 'folder-git-2', 'folder-heart', 'folder-input', 'folder-kanban',
                'folder-key', 'folder-lock', 'folder-minus', 'folder-open', 'folder-output',
                'folder-pen', 'folder-plus', 'folder-root', 'folder-search', 'folder-search-2',
                'folder-symlink', 'folder-sync', 'folder-tree', 'folder-up', 'folder-x',
                'folders', 'footprints', 'forklift', 'form-input', 'forward', 'frame', 'framer',
                'frown', 'fuel', 'fullscreen', 'function-square', 'gallery-horizontal',
                'gallery-horizontal-end', 'gallery-thumbnails', 'gallery-vertical',
                'gallery-vertical-end', 'gamepad', 'gamepad-2', 'gantt-chart', 'gauge',
                'gauge-circle', 'gavel', 'gem', 'ghost', 'gift', 'git-branch', 'git-branch-plus',
                'git-commit', 'git-compare', 'git-fork', 'git-graph', 'git-merge', 'git-pull-request',
                'git-pull-request-closed', 'git-pull-request-draft', 'glass-water', 'glasses',
                'globe', 'globe-2', 'globe-lock', 'goal', 'grab', 'graduation-cap', 'grape',
                'grid', 'grid-2x2', 'grid-3x3', 'grip', 'grip-horizontal', 'grip-vertical',
                'group', 'hammer', 'hand', 'hand-coins', 'hand-heart', 'hand-helping', 'hand-metal',
                'hand-platter', 'handshake', 'hard-drive', 'hard-drive-download', 'hard-drive-upload',
                'hard-hat', 'hash', 'haze', 'hdmi-port', 'heading', 'heading-1', 'heading-2',
                'heading-3', 'heading-4', 'heading-5', 'heading-6', 'headphones', 'headphones-off',
                'heart', 'heart-crack', 'heart-handshake', 'heart-off', 'heart-pulse', 'heater',
                'hexagon', 'highlighter', 'history', 'home', 'hop', 'hop-off', 'hospital',
                'hourglass', 'house', 'house-plus', 'ice-cream', 'ice-cream-2', 'ice-cream-bowl',
                'image', 'image-down', 'image-minus', 'image-off', 'image-plus', 'import',
                'inbox', 'indent', 'indian-rupee', 'infinity', 'info', 'inspection-panel',
                'instagram', 'italic', 'iteration-ccw', 'iteration-cw', 'japanese-yen',
                'joystick', 'kanban', 'key', 'key-round', 'key-square', 'keyboard', 'keyboard-music',
                'lamp', 'lamp-ceiling', 'lamp-desk', 'lamp-floor', 'lamp-wall-down', 'lamp-wall-up',
                'land-plot', 'landmark', 'languages', 'laptop', 'laptop-2', 'lasso', 'lasso-select',
                'laugh', 'layers', 'layers-2', 'layers-3', 'layout', 'layout-dashboard',
                'layout-grid', 'layout-list', 'layout-panel-left', 'layout-panel-top',
                'layout-template', 'leaf', 'leafy-green', 'lectern', 'library', 'library-big',
                'life-buoy', 'ligature', 'lightbulb', 'lightbulb-off', 'line-chart', 'link',
                'link-2', 'link-2-off', 'link-off', 'list', 'list-checks', 'list-collapse',
                'list-end', 'list-filter', 'list-minus', 'list-music', 'list-ordered',
                'list-plus', 'list-restart', 'list-start', 'list-tree', 'list-video', 'list-x',
                'loader', 'loader-2', 'loader-circle', 'locate', 'locate-fixed', 'locate-off',
                'lock', 'lock-keyhole', 'lock-keyhole-open', 'lock-open', 'log-in', 'log-out',
                'lollipop', 'luggage', 'magnet', 'mail', 'mail-check', 'mail-minus', 'mail-open',
                'mail-plus', 'mail-question', 'mail-search', 'mail-warning', 'mail-x', 'mailbox',
                'mails', 'map', 'map-pin', 'map-pin-off', 'map-pinned', 'martini', 'maximize',
                'maximize-2', 'medal', 'megaphone', 'megaphone-off', 'meh', 'memory-stick',
                'menu', 'merge', 'message-circle', 'message-circle-code', 'message-circle-dashed',
                'message-circle-heart', 'message-circle-more', 'message-circle-off',
                'message-circle-plus', 'message-circle-question', 'message-circle-reply',
                'message-circle-warning', 'message-circle-x', 'message-square', 'message-square-code',
                'message-square-dashed', 'message-square-diff', 'message-square-dot',
                'message-square-heart', 'message-square-more', 'message-square-off',
                'message-square-plus', 'message-square-question', 'message-square-reply',
                'message-square-share', 'message-square-text', 'message-square-warning',
                'message-square-x', 'messages-square', 'mic', 'mic-2', 'mic-off', 'microscope',
                'microwave', 'milestone', 'milk', 'milk-off', 'minimize', 'minimize-2',
                'minus', 'monitor', 'monitor-check', 'monitor-cog', 'monitor-dot', 'monitor-down',
                'monitor-off', 'monitor-pause', 'monitor-play', 'monitor-smartphone',
                'monitor-speaker', 'monitor-stop', 'monitor-up', 'monitor-x', 'moon', 'moon-star',
                'mountain', 'mountain-snow', 'mouse', 'mouse-pointer', 'mouse-pointer-2',
                'mouse-pointer-click', 'move', 'move-3d', 'move-diagonal', 'move-diagonal-2',
                'move-down', 'move-down-left', 'move-down-right', 'move-horizontal',
                'move-left', 'move-right', 'move-up', 'move-up-left', 'move-up-right',
                'move-vertical', 'music', 'music-2', 'music-3', 'music-4', 'navigation',
                'navigation-2', 'navigation-2-off', 'navigation-off', 'network', 'newspaper',
                'nfc', 'notebook', 'notebook-pen', 'notebook-tabs', 'notebook-text', 'notepad',
                'notepad-text', 'nut', 'nut-off', 'octagon', 'octagon-alert', 'octagon-minus',
                'octagon-plus', 'octagon-x', 'option', 'orbit', 'outdent', 'package', 'package-2',
                'package-check', 'package-minus', 'package-open', 'package-plus', 'package-search',
                'package-x', 'paint-bucket', 'paint-roller', 'paintbrush', 'paintbrush-2',
                'palette', 'panel-bottom', 'panel-bottom-close', 'panel-bottom-in', 'panel-bottom-open',
                'panel-bottom-out', 'panel-left', 'panel-left-close', 'panel-left-in',
                'panel-left-open', 'panel-left-out', 'panel-right', 'panel-right-close',
                'panel-right-in', 'panel-right-open', 'panel-right-out', 'panel-top', 'panel-top-close',
                'panel-top-in', 'panel-top-open', 'panel-top-out', 'paperclip', 'parentheses',
                'parking-circle', 'parking-circle-off', 'parking-meter', 'parking-square',
                'parking-square-off', 'party-popper', 'pause', 'paw-print', 'pc-case', 'pen',
                'pen-line', 'pen-off', 'pen-tool', 'pencil', 'pencil-line', 'pencil-off',
                'pencil-ruler', 'percent', 'person-standing', 'phone', 'phone-call', 'phone-forwarded',
                'phone-incoming', 'phone-missed', 'phone-off', 'phone-outgoing', 'phone-pe',
                'pi', 'piano', 'pickaxe', 'picture-in-picture', 'picture-in-picture-2',
                'pie-chart', 'piggy-bank', 'pilcrow', 'pilcrow-left', 'pilcrow-right', 'pill',
                'pin', 'pin-off', 'pipette', 'pizza', 'plane', 'plane-landing', 'plane-takeoff',
                'play', 'play-square', 'plug', 'plug-2', 'plug-zap', 'plug-zap-2', 'plus',
                'pocket', 'pocket-knife', 'podcast', 'pointer', 'pointer-off', 'popcorn',
                'popsicle', 'pound-sterling', 'power', 'power-off', 'presentation', 'printer',
                'printer-check', 'projector', 'puzzle', 'pyramid', 'qr-code', 'quote', 'rabbit',
                'radar', 'radiation', 'radio', 'radio-receiver', 'radio-tower', 'radius',
                'rail-symbol', 'rainbow', 'rat', 'ratio', 'receipt', 'receipt-cent', 'receipt-euro',
                'receipt-indian-rupee', 'receipt-japanese-yen', 'receipt-pound-sterling',
                'receipt-russian-ruble', 'receipt-swiss-franc', 'receipt-text', 'rectangle-ellipsis',
                'rectangle-horizontal', 'rectangle-vertical', 'recycle', 'redo', 'redo-2',
                'redo-dot', 'refresh-ccw', 'refresh-ccw-dot', 'refresh-cw', 'refresh-cw-dot',
                'refrigerator', 'regex', 'remove-formatting', 'repeat', 'repeat-1', 'repeat-2',
                'replace', 'replace-all', 'reply', 'reply-all', 'rewind', 'ribbon', 'rocket',
                'rocking-chair', 'roller-coaster', 'rotate-3d', 'rotate-ccw', 'rotate-ccw-square',
                'rotate-cw', 'rotate-cw-square', 'route', 'route-off', 'router', 'rows',
                'rss', 'ruler', 'russian-ruble', 'sailboat', 'salad', 'sandwich', 'satellite',
                'satellite-dish', 'save', 'save-all', 'scale', 'scale-3d', 'scaling', 'scan',
                'scan-barcode', 'scan-eye', 'scan-face', 'scan-heart', 'scan-line', 'scan-qr-code',
                'scan-search', 'scan-text', 'scatter-chart', 'school', 'scissors',
                'scissors-line-dashed',
                'screen-share', 'screen-share-off', 'scroll', 'scroll-text', 'search', 'search-check',
                'search-code', 'search-large', 'search-slash', 'search-x', 'send', 'send-horizontal',
                'send-to-back', 'separator-horizontal', 'separator-vertical', 'server', 'server-cog',
                'server-crash', 'server-off', 'settings', 'settings-2', 'shapes', 'share',
                'share-2', 'sheet', 'shell', 'shield', 'shield-alert', 'shield-ban', 'shield-check',
                'shield-close', 'shield-off', 'shield-plus', 'shield-question', 'shield-x',
                'ship', 'ship-wheel', 'shirt', 'shopping-bag', 'shopping-basket', 'shopping-cart',
                'shovel', 'shower-head', 'shrink', 'shrub', 'shuffle', 'sidebar', 'sidebar-close',
                'sidebar-open', 'sidebar-split', 'sigma', 'signal', 'signal-high', 'signal-low',
                'signal-medium', 'signal-zero', 'signature', 'siren', 'skip-back', 'skip-forward',
                'skull', 'slack', 'slash', 'slice', 'sliders', 'sliders-horizontal', 'sliders-vertical',
                'smartphone', 'smartphone-charging', 'smartphone-nfc', 'smile', 'smile-plus',
                'snail', 'snowflake', 'sofa', 'soup', 'space', 'spade', 'sparkle', 'sparkles',
                'speaker', 'speech', 'spell-check', 'spell-check-2', 'spline', 'split', 'spray-can',
                'sprout', 'square', 'square-activity', 'square-arrow-down', 'square-arrow-down-left',
                'square-arrow-down-right', 'square-arrow-left', 'square-arrow-out-down-left',
                'square-arrow-out-down-right', 'square-arrow-out-up-left', 'square-arrow-out-up-right',
                'square-arrow-right', 'square-arrow-up', 'square-arrow-up-left',
                'square-arrow-up-right',
                'square-asterisk', 'square-bottom-dashed-scissors', 'square-check', 'square-check-big',
                'square-chevron-down', 'square-chevron-left', 'square-chevron-right',
                'square-chevron-up', 'square-code', 'square-dashed', 'square-dashed-bottom',
                'square-dashed-bottom-code', 'square-divide', 'square-dot', 'square-equal',
                'square-function', 'square-gantt-chart', 'square-kanban', 'square-library',
                'square-m', 'square-menu', 'square-minus', 'square-mouse-pointer', 'square-parking',
                'square-parking-off', 'square-pen', 'square-percent', 'square-pi', 'square-pilcrow',
                'square-play', 'square-plus', 'square-power', 'square-radical', 'square-round',
                'square-scissors', 'square-sigma', 'square-slash', 'square-split-horizontal',
                'square-split-vertical', 'square-square', 'square-stack', 'square-terminal',
                'square-user', 'square-user-round', 'square-x', 'squircle', 'squirrel', 'stamp',
                'star', 'star-half', 'star-off', 'step-back', 'step-forward', 'stethoscope',
                'sticker', 'sticky-note', 'store', 'stretch-horizontal', 'stretch-vertical',
                'strikethrough', 'subscript', 'subtitles', 'sun', 'sun-dim', 'sun-medium',
                'sun-moon', 'sun-snow', 'sunrise', 'sunset', 'superscript', 'swatch-book',
                'swiss-franc', 'switch-camera', 'sword', 'swords', 'syringe', 'table', 'table-2',
                'table-cells-merge', 'table-cells-split', 'table-columns', 'table-of-contents',
                'table-properties', 'table-rows', 'tablet', 'tablet-smartphone', 'tablets',
                'tag', 'tags', 'tally-1', 'tally-2', 'tally-3', 'tally-4', 'tally-5', 'target',
                'tent', 'tent-tree', 'terminal', 'terminal-square', 'test-tube', 'test-tube-diagonal',
                'test-tubes', 'text', 'text-cursor', 'text-cursor-input', 'text-quote', 'text-search',
                'text-select', 'theater', 'thermometer', 'thermometer-snowflake', 'thermometer-sun',
                'thumbs-down', 'thumbs-up', 'ticket', 'ticket-check', 'ticket-minus', 'ticket-percent',
                'ticket-plus', 'ticket-slash', 'ticket-x', 'timer', 'timer-off', 'timer-reset',
                'toggle-left', 'toggle-right', 'toilet', 'tornado', 'torus', 'touchpad', 'touchpad-off',
                'tower-control', 'toy-brick', 'tractor', 'traffic-cone', 'train', 'train-front',
                'train-front-tunnel', 'train-track', 'tram-front', 'trash', 'trash-2', 'tree-deciduous',
                'tree-pine', 'trees', 'trello', 'trending-down', 'trending-up', 'triangle',
                'triangle-alert', 'triangle-right', 'trophy', 'truck', 'turtle', 'tv', 'tv-2',
                'twitch', 'twitter', 'type', 'umbrella', 'umbrella-off', 'underline', 'undo',
                'undo-2', 'undo-dot', 'unfold-horizontal', 'unfold-vertical', 'ungroup',
                'university', 'unlink', 'unlink-2', 'unlock', 'unlock-keyhole', 'unplug',
                'upload', 'upload-cloud', 'usb', 'user', 'user-2', 'user-check', 'user-check-2',
                'user-cog', 'user-cog-2', 'user-minus', 'user-minus-2', 'user-pen', 'user-plus',
                'user-plus-2', 'user-round', 'user-round-check', 'user-round-cog', 'user-round-minus',
                'user-round-plus', 'user-round-search', 'user-round-x', 'user-search', 'user-x',
                'user-x-2', 'users', 'users-2', 'users-round', 'utensils', 'utensils-crossed',
                'utility-pole', 'variable', 'vault', 'vegan', 'venetian-mask', 'vibrate',
                'vibrate-off', 'video', 'video-off', 'videotape', 'view', 'voicemail', 'volume',
                'volume-1', 'volume-2', 'volume-x', 'vote', 'wallet', 'wallet-2', 'wallet-cards',
                'wallpaper', 'wand', 'wand-2', 'warehouse', 'washing-machine', 'watch', 'waves',
                'waypoints', 'webcam', 'webhook', 'weight', 'wheat', 'wheat-off', 'whole-word',
                'wifi', 'wifi-high', 'wifi-low', 'wifi-off', 'wifi-zero', 'wind', 'wind-arrow-down',
                'wine', 'wine-off', 'workflow', 'worm', 'wrap-text', 'wrench', 'x', 'x-circle',
                'x-octagon', 'x-square', 'youtube', 'zap', 'zap-off', 'zoom-in', 'zoom-out'
            ];

            // Inicializar iconos
            function inicializarIconos() {
                iconosDisponibles = [...listaIconosLucide];
                iconosFiltrados = [...iconosDisponibles];
                console.log('Iconos inicializados:', iconosDisponibles.length);
            }

            // Renderizar iconos
            function renderizarIconos() {
                if (!iconosContainer) return;

                if (iconosFiltrados.length === 0) {
                    iconosContainer.innerHTML =
                        '<div class="col-12 text-center"><p>No se encontraron iconos</p></div>';
                    return;
                }

                let html = '';
                iconosFiltrados.forEach(icono => {
                    html += `
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6 mb-3">
                            <div class="icono-item text-center p-2 border rounded" data-icono="${icono}" title="${icono}">
                                <i data-lucide="${icono}" class="mx-auto" style="width: 24px; height: 24px;"></i>
                                <div class="mt-1 small text-truncate">${icono}</div>
                            </div>
                        </div>
                    `;
                });

                iconosContainer.innerHTML = html;

                // Agregar event listeners a los iconos
                document.querySelectorAll('.icono-item').forEach(item => {
                    item.addEventListener('click', function() {
                        seleccionarIcono(this.getAttribute('data-icono'));
                    });
                });

                // Crear iconos de Lucide DESPUÉS de renderizar el HTML
                setTimeout(() => {
                    if (window.lucide && window.lucide.createIcons) {
                        window.lucide.createIcons();
                    }
                }, 100);
            }

            // Seleccionar icono
            function seleccionarIcono(nombreIcono) {
                Livewire.emit('seleccionar-icono', nombreIcono);
                const modalSelector = bootstrap.Modal.getInstance(document.getElementById('modalSelectorIconos'));
                if (modalSelector) modalSelector.hide();

                setTimeout(() => {
                    const existingBackdrop = document.querySelector('.modal-backdrop');
                    if (!existingBackdrop) {
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                }, 500);
            }

            // Event listeners
            if (searchIconosInput) {
                searchIconosInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    iconosFiltrados = iconosDisponibles.filter(icono =>
                        icono.toLowerCase().includes(searchTerm)
                    );
                    renderizarIconos();
                });
            }

            if (btnSelectorIconos) {
                btnSelectorIconos.addEventListener('click', function() {
                    const modalSelector = new bootstrap.Modal(document.getElementById(
                        'modalSelectorIconos'));
                    modalSelector.show();

                    // Si aún no se han inicializado los iconos, inicializarlos
                    setTimeout(() => {
                        inicializarIconos();
                        renderizarIconos();
                    }, 1000);
                });
            }

            // Cargar iconos cuando se abra el modal selector
            document.getElementById('modalSelectorIconos')?.addEventListener('show.bs.modal', function() {
                setTimeout(() => {
                    inicializarIconos();
                    renderizarIconos();
                }, 1000);
            });

            // Eventos del navegador para el modal principal
            window.addEventListener('openModal', () => {
                const modal = new bootstrap.Modal(document.getElementById('modalTag'));
                modal.show();
            });

            window.addEventListener('closeModal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalTag'));
                if (modal) {
                    modal.hide();
                }

                // Limpiar backdrop de manera segura después de cerrar
                setTimeout(() => {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => {
                        if (backdrop && backdrop.parentNode) {
                            backdrop.parentNode.removeChild(backdrop);
                        }
                    });

                    // Remover clases del body
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';

                    if (window.lucide && window.lucide.createIcons) {
                        window.lucide.createIcons();
                    } else {
                        console.log('Lucide no está disponible');
                    }


                }, 150);
            });

            // Eventos del navegador para el modal de productos
            window.addEventListener('openModalProductos', () => {
                const modal = new bootstrap.Modal(document.getElementById('modalProductos'));
                modal.show();
            });

            window.addEventListener('closeModalProductos', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalProductos'));
                if (modal) {
                    modal.hide();
                }

                // Limpiar backdrop de manera segura después de cerrar
                setTimeout(() => {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => {
                        if (backdrop && backdrop.parentNode) {
                            backdrop.parentNode.removeChild(backdrop);
                        }
                    });

                    // Remover clases del body
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                }, 150);
            });

            // Inicializar iconos al cargar la página
            inicializarIconos();
        });

        // Funciones globales para Livewire
        function openModal() {
            const modal = new bootstrap.Modal(document.getElementById('modalTag'));
            modal.show();
        }

        function closeModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTag'));

            console.log('Cerrando modal');
            if (modal) {
                modal.hide();
            }

            // Limpiar backdrop de manera segura
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => {
                    if (backdrop && backdrop.parentNode) {
                        backdrop.parentNode.removeChild(backdrop);
                    }
                });

                // Remover clases del body
                document.body.classList.remove('modal-open');
                document.body.style.paddingRight = '';
            }, 150);
        }

        function eliminarTag(id) {
            Swal.fire({
                title: "Eliminar tag",
                text: "Esta seguro que desea eliminar el tag?. Esta acción no se puede revertir.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si!. Eliminar",
                cancelButtonText: "No!, Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminar-tag', id);
                }
            });
        }

        function eliminarTagInvalido() {
            Swal.fire({
                title: "Eliminar tag",
                text: "El tag tiene productos asignados, no se puede eliminar, primero debe desvincular los productos.",
                icon: "warning",
            });
        }
    </script>
</div>
