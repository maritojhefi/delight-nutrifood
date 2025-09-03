<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                        <h4 class="card-title">Lista de Productos</h4>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-0">
                        <div class="input-group" style="max-height: 35px !important">
                            <button class="btn btn-secondary" type="button">Categoría</button>
                            <select name="" id="" class="form-control"
                                wire:model.lazy="categoria_select">
                                <option value="">Todos</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <input type="text" class="form-control form-control-sm bordeado" style="height: 30px"
                            placeholder="Buscar producto" wire:model.debounce.750ms="buscar">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-responsive-md m-0 p-0 letra12">
                        <thead class="m-0 p-0">
                            <tr class="m-0 p-0">
                                <th class="m-0 p-0"><strong>Nombre</strong></th>
                                <th class="m-0 p-0  "><strong>Stock</strong></th>
                                <th class="m-0 p-0"><strong>Precio</strong></th>
                                <th class="m-0 p-0  "><strong>Estado</strong></th>
                                <th class="m-0 p-0  "><strong>En Tienda</strong></th>
                                <th class="m-0 p-0  "><strong>Contable</strong></th>

                                <th class="m-0 p-0  "><strong>Subcategoria</strong></th>
                                <th class="m-0 p-0 "><strong>Unidad</strong></th>
                                <th class="m-0 p-0 "><strong>Puntos</strong></th>
                                <th class="m-0 p-0 "><strong>Tags</strong></th>
                                {{-- <th class="m-0 p-0"><strong>Codigo Barra</strong></th> --}}
                                <th class="m-0 p-0"><strong>Acciones</strong></th>
                            </tr>
                        </thead>
                        <tbody class="m-0 p-0">
                            @foreach ($productos as $item)
                                <tr>
                                    <td class="m-0 p-1">
                                        <div class="d-flex align-items-center">
                                            @if ($item->imagen)
                                                <img src="{{ asset($item->pathAttachment()) }}" class="rounded-lg me-2"
                                                width="24" alt="">@else<img
                                                    src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}"
                                                    class="rounded-lg me-2" width="24" alt="">
                                            @endif
                                            <strong class="">{{ $item->nombre }}</strong>
                                        </div>
                                    </td>
                                    <td class="m-0 p-1  d-sm"><span class="w-space-no">
                                            @if ($item->contable)
                                                <button class="badge badge-outline-info badge-xxs"
                                                    data-bs-toggle="modal" data-bs-target="#modalStock"
                                                    wire:click="verStock({{ $item->id }})">Stock:{{ $item->stock_actual }}</button>
                                            @else
                                                <span class="">No aplica</span>
                                            @endif

                                        </span>
                                    </td>
                                    @if ($item->descuento)
                                        <td class="m-0 p-1 text-truncate fw-bold">
                                            <del class=" text-danger">{{ $item->precio }}</del>
                                            <span class="">{{ $item->descuento }} Bs</span>
                                        </td>
                                    @else
                                        <td class="m-0 p-1 text-truncate fw-bold"><span
                                                class="">{{ $item->precio }}
                                                Bs</span></td>
                                    @endif
                                    <td class="m-0 p-1 d-md">
                                        <div class="form-check form-switch" >
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                    style="width: 30px !important;cursor: pointer;"
                                                   {{ $item->estado == 'activo' ? 'checked' : '' }}
                                                   wire:click="cambiarestado('{{ $item->id }}')"
                                                  >
                                           
                                        </div>
                                    </td>
                                    <td class="m-0 p-1 d-md">
                                        <button class="btn btn-sm border-0 p-2 {{ $item->estado == 'inactivo' && !$item->publico_tienda ? 'disabled' : '' }}" 
                                                wire:click="cambiarPublicoTienda('{{ $item->id }}')"
                                                title="{{ $item->estado == 'inactivo' && !$item->publico_tienda ? 'Producto inactivo - Active el producto primero' : ($item->publico_tienda ? 'Visible en tienda - Click para ocultar' : 'Oculto en tienda - Click para mostrar') }}"
                                                {{ $item->estado == 'inactivo' && !$item->publico_tienda ? 'disabled' : '' }}>
                                            <i class="fa fa-{{ $item->publico_tienda ? 'eye' : 'eye-slash' }}
                                                      text-{{ $item->estado == 'inactivo' && !$item->publico_tienda ? 'muted' : ($item->publico_tienda ? 'success' : 'dark') }}"
                                               style="font-size: 20px !important; cursor: {{ $item->estado == 'inactivo' && !$item->publico_tienda ? 'not-allowed' : 'pointer' }};"></i>
                                        </button>
                                    </td>
                                    <td class="m-0 p-1  d-lg"><a href="#"
                                            wire:click="cambiarcontable('{{ $item->id }}')"><span
                                                class="badge badge-{{ $item->contable == 0 ? 'warning' : 'success' }}">{{ $item->contable == 0 ? 'NO' : 'SI' }}</span></a>
                                    </td>

                                    <td class="m-0 p-1 d-lg">
                                        <span class="">{{ $item->subcategoria->nombre }}</span>
                                    </td>
                                    <td class="m-0 p-1">
                                        <span class="w-space-no">{{ $item->medicion }}</span>
                                    </td>
                                    <td class="m-0 p-1">
                                        <span class="w-space-no">{{ $item->puntos }}</span>
                                    </td>
                                    <td class="m-0 p-1">
                                        @if ($item->tags->count() > 0)
                                            <span class="badge badge-outline-primary badge-xs">
                                                {{ $item->tags->count() }} Tags</span>
                                        @else
                                            <span class="badge badge-outline-light badge-xs">Sin Tags</span>
                                        @endif
                                    </td>

                                    {{-- @if ($item->codigoBarra)
                                        <td class="m-0 p-1"><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->codigoBarra, 'C39+', 1, 33) }}"
                                                alt=""></td>
                                    @else
                                        <td class="m-0 p-1"><span class="badge light badge-danger">Sin codigo</span></td>
                                    @endif --}}

                                    <td class="m-0 p-1">
                                        <div class="d-flex flex-column flex-sm-row">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-info light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24"
                                                        version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="12" cy="12"
                                                                r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="19" cy="12"
                                                                r="2">
                                                            </circle>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditar"
                                                        wire:click="editarProducto({{ $item->id }})"><i
                                                            class="fa fa-pencil me-2"></i> Editar</a>
                                                    <a href="#" class="dropdown-item"
                                                        onclick="confirmarEliminacion('{{ $item->id }}', '{{ $item->nombre }}')"><i
                                                            class="fa fa-trash me-2"></i> Eliminar</a>
                                                    <a href="#" class="dropdown-item"
                                                        wire:click="editarTags('{{ $item->id }}')"
                                                        data-bs-toggle="modal" data-bs-target="#modalTags"><i
                                                            class="fa fa-tag me-2"></i> Editar Tags</a>
                                                </div>
                                            </div>
                                        </div>



                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap mt-3 px-3">
                <div class="mb-2 mb-md-0">
                    <small class="text-muted">
                        Mostrando {{ $productos->count() }} de {{ $productos->total() }} registros
                    </small>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                @isset($subcategorias)
                    <div class="modal-body">

                        <x-input-create-custom-function funcion="actualizarProducto" boton="Actualizar" :lista="[
                            'Nombre' => ['nombre', 'text'],
                            'Precio' => ['precio', 'number'],
                            'Descuento' => ['descuento', 'number', 'Precio que se cobrara'],
                            'Detalle' => ['detalle', 'textarea'],
                            'Puntos' => ['puntos', 'number'],
                            'Imagen' => ['imagen', 'file'],
                        ]">
                            <x-slot name="otrosinputs">
                                <div class="mb-3 row">
                                    <select name=""
                                        class="form-control @error($subcategoria_id) is-invalid @enderror"
                                        wire:model="subcategoria_id" id="">

                                        <option>Seleccione subcategoria ({{ $subcategorias->count() }})</option>
                                        @foreach ($subcategorias as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </x-slot>
                            @if ($imagen)
                                <img src="{{ $imagen->temporaryUrl() }}" class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </x-input-create-custom-function>
                    </div>
                @endisset


            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalStock">
        <div class="modal-dialog">

            <div class="modal-content">
                <center wire:loading>
                    <div class="spinner-border mb-3 mt-3" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </center>
                <div wire:loading.remove class="modal-header">
                    @isset($productoSeleccionado)
                        <h5 class="modal-title">{{ $productoSeleccionado->nombre }}</h5>
                    @endisset
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div wire:loading.remove class="modal-body">
                    @isset($productoSeleccionado)
                        @if ($productoSeleccionado->getStockDetallado()->count() != 0)
                            @foreach ($productoSeleccionado->getStockDetallado() as $stock)
                                <div class="card overflow-hidden bg-image-2 bg-secondary my-1">
                                    <div class="card-header  border-0 m-0 py-1">
                                        <div class="">
                                            <strong class="mb-2 letra12 text-white">
                                                {{ $stock->nombre }} </strong> -
                                            <small class="text-white letra12">Vence:
                                                {{ date('d-m-Y', strtotime($stock->pivot->fecha_venc)) }}</small>

                                            <h3 class="mb-0 fs-24  text-white">
                                                Cantidad restante: {{ $stock->pivot->cantidad }}</h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <hr>
                            <center class="letra14"><strong>Stock total :
                                    {{ $productoSeleccionado->stock_actual }}</strong></center>
                        @else
                            <div class="text-center"><span class="text-muted text-center"> No hay registro de stock para
                                    este producto</span></div>
                        @endif
                    @endisset
                </div>
                @isset($productoSeleccionado)
                    <div wire:loading.remove class="modal-footer">
                        <div class="row">
                            <div class="col mx-auto">
                                <a href="{{route('producto.expiracion',['search'=>$productoSeleccionado->nombre])}}" target="_blank" class="btn  btn-success p-1">Agregar o eliminar stock</a>
                            </div>
                            
                            {{-- <div class="col-4">
                                <input type="number" class="form-control p-1 bordeado" style="height: 30px"
                                    wire:model.defer="cantidadStock" placeholder="Cantidad">
                                @error('cantidadStock')
                                    <label class="text-danger letra10">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="col-4">
                                <input type="date" class="form-control p-1 bordeado" style="height: 30px"
                                    wire:model.defer="fechaStock" placeholder="Vencimiento">
                                @error('fechaStock')
                                    <label class="text-danger letra10">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="col-4">
                                <button wire:click="guardarStock({{ $productoSeleccionado->id }})"
                                    class="btn btn-sm btn-success p-1">Agregar stock</button>
                            </div> --}}
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalTags" tabindex="-1" aria-labelledby="modalTagsLabel" aria-hidden="true"
        wire:ignore.self style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTagsLabel">
                        Editar Tags del Producto
                        <span class="badge bg-primary ms-2">{{ $tagsProducto->count() }} tags asignados</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @isset($productoSeleccionadoTag)
                        <p class="text-muted">Producto seleccionado: <strong>{{ $productoSeleccionadoTag->nombre }}</strong></p>
                    @endisset
                    <!-- Buscador de tags -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Buscar tags..."
                                    wire:model.debounce.750ms="searchTags" wire:keydown.enter="buscarTags">
                                @if ($searchTags)
                                    <button class="btn btn-outline-secondary" type="button"
                                        wire:click="limpiarBusqueda">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                                <button class="btn btn-primary" type="button" wire:click="buscarTags">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                            @if ($searchTags && strlen($searchTags) < 2)
                                <small class="text-muted">Escriba al menos 2 caracteres para buscar</small>
                            @endif
                            @if ($searchTags && strlen($searchTags) >= 2)
                                <small class="text-info">
                                    <i class="fa fa-info-circle"></i>
                                    Presione Enter o haga clic en Buscar para ejecutar la búsqueda
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tags disponibles -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Tags Disponibles</h6>
                                    <span class="badge bg-secondary">{{ $tags->count() }}
                                        tags</span>
                                </div>
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                    @if ($tags->isNotEmpty())
                                        <div class="list-group">
                                            @foreach ($tags as $tag)
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                    <div>
                                                        <div class="btn btn-sm  d-flex align-items-center p-0"
                                                            data-bs-toggle="dropdown" aria-expanded="false"
                                                            role="button">
                                                            <span class="ticket-icon-1">
                                                                <i data-lucide="{{ $tag->icono }}"></i>
                                                            </span>
                                                            <div class="text-start ms-3 flex-1">
                                                                <span
                                                                    class="d-block text-black">{{ $tag->nombre }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-sm btn-success"
                                                        wire:click="agregarTag({{ $tag->id }})"
                                                        wire:loading.attr="disabled" wire:target="agregarTag">
                                                        <span wire:loading.remove wire:target="agregarTag">
                                                            <i class="fa fa-plus"></i>
                                                        </span>
                                                        <span wire:loading wire:target="agregarTag">
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
                                        @if ($searchTags && strlen($searchTags) >= 2)
                                            <div class="text-center">
                                                <p class="text-muted">No se encontraron tags con
                                                    "{{ $searchTags }}"</p>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    wire:click="limpiarBusqueda">
                                                    <i class="fa fa-times"></i> Limpiar búsqueda
                                                </button>
                                            </div>
                                        @elseif ($searchTags && strlen($searchTags) < 2)
                                            <p class="text-muted text-center">Escriba al menos 2 caracteres para
                                                buscar</p>
                                        @else
                                            <p class="text-muted text-center">No hay tags disponibles para
                                                agregar</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tags del producto -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Tags del Producto</h6>
                                </div>
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                    @if ($tagsProducto && count($tagsProducto) > 0)
                                        <div class="list-group">
                                            @foreach ($tagsProducto as $tag)
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                    <div>
                                                        <div class="btn btn-sm  d-flex align-items-center p-0"
                                                            data-bs-toggle="dropdown" aria-expanded="false"
                                                            role="button">
                                                            <span class="ticket-icon-1">
                                                                <i data-lucide="{{ $tag->icono }}"></i>
                                                            </span>
                                                            <div class="text-start ms-3 flex-1">
                                                                <span
                                                                    class="d-block text-black">{{ $tag->nombre }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-sm btn-danger"
                                                        wire:click="quitarTag({{ $tag->id }})"
                                                        wire:loading.attr="disabled" wire:target="quitarTag">
                                                        <span wire:loading.remove wire:target="quitarTag">
                                                            <i class="fa fa-minus"></i>
                                                        </span>
                                                        <span wire:loading wire:target="quitarTag">
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
                                        <p class="text-muted text-center">No hay tags en este producto</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info" onclick="inicializarLucideIcons()">
                        <i class="fa fa-refresh"></i> Recargar Iconos
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function confirmarEliminacion(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el producto "${nombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('eliminar', id);
                }
            });
        }
    </script>
@endpush

<!-- CDN de Lucide Icons -->
@push('footer')
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        // Inicializar Lucide Icons cuando se cargue la página
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && window.lucide.createIcons) {
                lucide.createIcons();
                console.log('✅ Lucide Icons inicializados en DOMContentLoaded');
            }
        });
    </script>
@endpush

<style>
    /* Estilos para el modal de tags */
    /* #modalTags {
        z-index: 1055;
    } */

    /* .modal-backdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
        z-index: 1050 !important;
    } */

    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(2px);
    }

    .btn-sm {
        transition: all 0.2s ease;
    }

    .btn-sm:hover {
        transform: scale(1.05);
    }
</style>

<script>
    // Función simple para inicializar Lucide Icons (como en tags-component.blade.php)
    function inicializarLucideIcons() {
        if (window.lucide && window.lucide.createIcons) {
            window.lucide.createIcons();
            console.log('✅ Lucide Icons inicializados correctamente');
        } else {
            console.warn('⚠️ Lucide no está disponible');
        }
    }

    // Inicializar cuando Livewire esté listo
    document.addEventListener('livewire:load', function() {
        // Limpiar backdrops duplicados
        setInterval(function() {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            if (backdrops.length > 1) {
                for (let i = 1; i < backdrops.length; i++) {
                    if (backdrops[i] && backdrops[i].parentNode) {
                        backdrops[i].parentNode.removeChild(backdrops[i]);
                    }
                }
            }
        }, 100);

        // Cerrar modales con tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                Livewire.emit('cerrar-modal-tags');
            }
        });

        // Escuchar evento para inicializar Lucide Icons
        window.addEventListener('inicializar-lucide-icons', function() {
            console.log('🎯 Evento recibido: inicializar-lucide-icons');
            inicializarLucideIcons();
        });

        // Inicializar iconos al cargar Livewire
        inicializarLucideIcons();
    });

    // Escuchar eventos de Livewire para reinicializar iconos (como en tags-component.blade.php)
    document.addEventListener('livewire:update', function() {
        setTimeout(function() {
            inicializarLucideIcons();
        }, 100);
    });

    // Escuchar cuando se abra el modal de tags
    document.getElementById('modalTags')?.addEventListener('shown.bs.modal', function() {
        console.log('🎯 Modal de tags abierto, inicializando iconos...');
        setTimeout(function() {
            inicializarLucideIcons();
        }, 500);
    });

    // Escuchar cuando se complete una actualización de Livewire
    document.addEventListener('livewire:updated', function() {
        inicializarLucideIcons();
    });

    // Escuchar eventos de Livewire (como en tags-component.blade.php)
    Livewire.on('renderizar-icono-modal-creacion-listado', function() {
        console.log('🎯 Evento Livewire recibido: renderizar-icono-modal-creacion-listado');
        setTimeout(function() {
            inicializarLucideIcons();
        }, 100);
    });

    // Función global para usar desde otros scripts
    window.inicializarLucideIcons = inicializarLucideIcons;
</script>
