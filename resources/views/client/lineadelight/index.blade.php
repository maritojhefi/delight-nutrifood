@extends('client.master')
@section('content')
    {{-- CABECERA --}}
    <x-cabecera-pagina titulo="Linea Delight" cabecera="appkit" />
    {{-- FUNCIONALIDAD DE BUSQUEDA --}}
    <x-barra-busqueda-productos tipo='lineadelight' />
    {{-- <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
        <div class="content mt-2">
            <div class="search-box bg-theme color-theme rounded-m shadow-l">
                <i class="fa fa-search"></i>
                @php
                    $productoRandom = $productos->random();
                @endphp
                <input type="text" class="border-0" placeholder="Busca por ejm: {{ $productoRandom->nombre }}"
                    data-search="">
                <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
            </div>
            <div class="search-results disabled-search-list mt-3">
                <div class="card card-style mx-0 px-2 p-0 mb-0">
                    @foreach ($productos as $item)
                        <a href="{{ route('delight.detalleproducto', $item->id) }}" class="d-flex py-2"
                            data-filter-item="{{ Str::of($item->nombre)->lower() }}"
                            data-filter-name="{{ Str::of($item->nombre)->lower() }}">
                            <div class="align-self-center">
                                <i class="fa fa-search"></i>
                            </div>
                            <div class="align-self-center ps-2">
                                <span
                                    class="color-theme font-15 d-block mb-0">{{ Str::limit(ucfirst(strtolower($item->nombre)), 35, '...') }}</span>
                            </div>
                            <div class="ms-auto text-center align-self-center pe-2">
                                <h5 class="line-height-xs font-16 font-600 mb-0">{{ $item->precio }} Bs<sup
                                        class="font-11"></sup></h5>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="search-no-results disabled mt-4">
            <div class="card card-style">
                <div class="content">
                    <h1>Ups!</h1>
                    <p>
                        No existen coincidencias <span class="fa-fw select-all fas"></span>
                    </p>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- CONTENIDO DE LA PAGINA --}}
    <div class="content mb-0">
        {{-- SLIDER INICIO LINEADELIGHT --}}
        <div class="my-4 splide single-slider slider-has-arrows slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="single-slider-1" style="visibility: visible">
            <div class="splide__track" id="single-slider-1-track">
                <div class="splide__list" id="single-slider-1-list">
                    {{-- LISTADO DE ELEMENTOS A RENDERIZARSE --}}
                    
                    {{-- ITEM POPULARES --}}
                    <div class="splide__slide mx-2 is-active is-visible" id="single-slider-populares" style="width: 320px;">
                        <a href='#' data-bs-toggle="modal" data-bs-target="#popularProductsModal" data-category-id="000" data-category-name="Nuestros productos mas populares!" data-card-height="200" class="card mb-0 shadow-l rounded-m" style=" background-color: #FF5A5A;">
                            <div class="card-center mt-n4 d-flex flex-column align-items-center">
                                <i class="fa fa-apple-alt fa-7x text-white"></i>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">PRODUCTOS POPULARES</h2>
                                <p class="under-heading color-white">Descubre nuestros articulos mas vendidos.</p>
                            </div>
                            <div class="card-overlay dark-mode-tint"></div>
                        </a>
                    </div>

                    {{-- ITEM PLANES Y PAQUETES --}}
                    <div class="splide__slide mx-2 is-active is-visible" id="single-slider-planes" style="width: 320px;">
                        <a href="{{ route('categoria.planes') }}" data-card-height="200" class="card mb-0 shadow-l rounded-m" style="background-color: #4ECDC4;">
                            <div class="card-center mt-n4 d-flex flex-column align-items-center">
                                <i class="fa fa-calendar fa-7x text-white"></i>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">PLANES Y PAQUETES</h2>
                                <p class="under-heading color-white">Encuentra la opcion mas apropiada para ti.</p>
                            </div>
                            <div class="card-overlay dark-mode-tint"></div>
                        </a>
                    </div>

                    {{-- TAGS --}}
                    @foreach ($tags as $tag)
                    <div class="splide__slide mx-2 is-active is-visible" id="single-slider-{{$tag->nombre}}" style="width: 320px;">
                        <div data-card-height="200" data-tag-id="{{$tag->id}}" data-tag-nombre="{{$tag->nombre}}"  class="productos-tag-trigger card mb-0 shadow-l rounded-m" style="background-image: url({{asset('imagenes/delight/mesa_tags.jpg')}});">
                            <div class="card-center mt-n4 d-flex flex-column align-items-center">
                                <i data-lucide="{{$tag->icono}}" class="lucide-icon text-white" style="width: 5rem; height: 5rem;"></i>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">{{$tag->nombre}}</h2>
                                <p class="under-heading color-white">Productos {{strtolower($tag->nombre)}}</p>
                            </div>
                            <div class="card-overlay dark-mode-tint light-mode-tint"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        {{-- <button id="menu-prueba-btn" class="btn bg-highlight">Prueba modal condijcional</button> --}}

        {{-- SLIDER HORARIOS --}}
        <div class="splide topic-slider slider-no-arrows slider-no-dots pb-2 splide--loop splide--ltr splide--draggable" id="topic-slider-1" style="visibility: visible;">
            <div class="splide__track" id="topic-slider-1-track" style="padding-left: 15px; padding-right: 40px;">
                <div class="splide__list" id="topic-slider-1-list" style="transform: translateX(-866.592px);">
                    @foreach ($horarios as $horario)
                        <div class="splide__slide" id="topic-slider-1-{{$horario->nombre}}" style="width: 108.333px;">
                            <h1 class="font-16 d-block"><button class="time-btn opacity-50" data-time="{{$horario->nombre}}">{{ucfirst($horario->nombre)}}</button></h1>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- SLIDER SUBCATEGORIAS --}}
        <x-slider-doble-subcategorias/>

        {{-- SLIDER PRODUCTOS MAS VENDIDOS --}}
        <div id="best-selling-container" class="my-4">
            <x-slider-productos :productos="$masVendidos" tag="popular" :title="'Los mas vendidos'" />
        </div>

        {{-- SLIDER PRODUCTOS NUEVOS --}}
        <div id="recent-container" class="my-4">
            <x-slider-productos :productos="$masRecientes" tag="recent" title="Novedades" orientation="right" />
        </div>
    </div>

    <x-divider-manzana class="mb-4"/>

    {{-- CARD PRODUCTOS PUNTUADOS --}}
    @if ($conMasPuntos->count() > 0)
    <x-seccion-gana-puntos :productos="$conMasPuntos" />
    @endif

    {{-- MODAL PRODUCTOS CATEGORIZADOS --}}
    <div class="modal fade" id="categorizedProductsModal" tabindex="-1" aria-labelledby="categorizedProductsModalLabel" style="z-index: 1051">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="categorizer-title" class="mb-0 align-self-center text-uppercase">Todos los productos de esta categoria!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body mt-0 pt-0 px-3 d-flex flex-column justify-content-center align-items-center">
                    <div class="w-100" style="min-width: 300px;">
                        <div class="content" id="listado-productos-categoria">
                            <!-- Contenedor items individuales-->
                            <div id="cart-summary-items" class="item-producto-categoria">
                                <p class="text-muted"><span class="font-bold">Ups!</span> Parece que aun no hay productos agregados a esta categoria, regresa mas tarde.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PRODUCTOS POPULARES --}}
    <div class="modal fade" id="popularProductsModal" tabindex="-1" aria-labelledby="popularProductsModalLabel" aria-hidden="true" style="z-index: 1051">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="popular-modal-title" class="mb-0 align-self-center text-uppercase">Nuestros productos mas populares!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 px-3 mt-0 d-flex flex-column" id="listado-productos-populares">
                        <!-- Contenedor items individuales-->
                        @if($masVendidos->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <i class="fa fa-question-circle fa-5x mb-3"></i>
                                <p>Parece que no hay productos disponibles en esta categoria, intenta mas tarde.</p>
                            </div>
                        @else
                            @foreach ($masVendidos as $masVendido)
                                <x-producto-card :producto="$masVendido" />
                            @endforeach
                        @endif
                </div>
            </div>
        </div>
    </div>

    <x-modal-listado-productos identificador="menu-listado-productos" />
    <x-menu-adicionales-producto :isUpdate="false"/>
@endsection

@push('scripts')
{{-- SCRIPT CONTROL DE AGREGAR AL CARRITO --}}
<script> 
    $(document).ready(function() {
        $(document).on('click', '.agregar-unidad', addToCartHandler);

        $(document).on('click', '.menu-adicionales-btn', function() {
            const productoId = $(this).data('producto-id');
            console.log("Product ID:", productoId); 
            openDetallesMenu(productoId);
        });
    });

    async function addToCartHandler() {
        const product_Id = $(this).data('producto-id');
        const product_nombre = $(this).data('producto-nombre')
        
        try {
            const result = await carritoStorage.addToCart(product_Id, 1);
            if (result.success) {
                console.log("Producto  agregado con exito al carrito.")
            } else {
                console.log(`Error al agregar el producto ${product_nombre} al carrito.`)
            }
        } catch (error) {
            console.error('Error agregando el producto al carrito:', error);
        }
    }
</script>
{{-- SCRIPT CONTROL DE SLIDER --}}
<script>
    const subcategoriasPorHorario = @json($horariosData);
    const horarios = @json($horarios);

    document.addEventListener('DOMContentLoaded', function () {
        // Determinacion del horario actual basado en la hora del dia
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        const currentTimeInMinutes = currentHour * 60 + currentMinute;
        
        let defaultTime = '';

        // Iterar sobre los horarios para encontrar el rango que incluye la hora actual
        for (const horario of horarios) {
            const startTime = horario.hora_inicio.split(':');
            const endTime = horario.hora_fin.split(':');
            
            const startMinutes = parseInt(startTime[0]) * 60 + parseInt(startTime[1]);
            const endMinutes = parseInt(endTime[0]) * 60 + parseInt(endTime[1]);
            
            // Manejar casos donde el horario cruze la medianoche (ejm: 19:00 - 03:59)
            if (startMinutes > endMinutes) {
                // El rango cruza la medianoche
                if (currentTimeInMinutes >= startMinutes || currentTimeInMinutes <= endMinutes) {
                    defaultTime = horario.nombre;
                    break;
                }
            } else {
                // Rango normal dentro del mismo dia
                if (currentTimeInMinutes >= startMinutes && currentTimeInMinutes <= endMinutes) {
                    defaultTime = horario.nombre;
                    break;
                }
            }
        }

        // Inicializacion de slider para horarios
        new Splide('#topic-slider-1', {
            type: 'loop',
            perPage: 3,
            arrows: false,
        }).mount();

        // Inicializacion del slider para categorias
        const doubleSlider = new Splide('#double-slider-1', {
            type: 'loop',
            perPage: 2,
            arrows: false,
            autoplay: true,
            interval: 3000,
        });

        // Items/Categorias por defecto a renderizarse
        const defaultItems = subcategoriasPorHorario[defaultTime];
        updateSliderContent(defaultItems);
        doubleSlider.mount();

        // Determinacion del boton activo
        const buttons = document.querySelectorAll('.time-btn');
        buttons.forEach(btn => {
            if (btn.getAttribute('data-time') === defaultTime) {
                btn.classList.add('is-active');
                btn.classList.remove('opacity-50');
            } else {
                btn.classList.add('opacity-50');
            }
        });

        function updateSliderContent(items) {
            const list = document.getElementById('double-slider-1-list');

            // Actualizar contenido del slider
            list.innerHTML = '';
            items.forEach(item => {
                const formattedName = item.nombre.charAt(0).toUpperCase() + item.nombre.slice(1).toLowerCase();

                list.innerHTML += `
                    <div class="splide__slide hover-grow-s" style="width: 12rem;">
                        <div class="card mx-3 mb-0 card-style bg-20"
                            data-bs-toggle="modal" 
                            data-bs-target="#categorizedProductsModal" 
                            data-category-id="${item.id}"
                            data-category-name="${item.nombre}"
                            style="height: 14rem; background-image: url('${item.foto}');">
                            <div class="card-bottom">
                                <h3 class="color-white font-18 font-600 mb-3 mx-3">${formattedName}</h3>
                            </div>
                            <div class="card-overlay bg-gradient"></div>
                        </div>
                    </div>
                `;
            });

            // Refrescar el slider para mostrar los nuevos items
            doubleSlider.refresh();

            // Reiniciar el indice del slider al primer elemento
            doubleSlider.go(0);
        }

        // Control seleccion del horario
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const time = btn.getAttribute('data-time');
                const items = subcategoriasPorHorario[time];

                // Remover clase activa de todos los botones
                buttons.forEach(b => {
                    b.classList.add('opacity-50');
                    b.classList.remove('is-active');
                });

                // Acivar el boton clickeado
                btn.classList.remove('opacity-50');
                btn.classList.add('is-active');

                // Actualizar el contenido del slider
                updateSliderContent(items);
            });
        });
    });
</script>
{{-- SCRIPT CONTROL DEL MODAL PRODUCTOS CATEGORIZADOS [LINEA-DELGIHT] --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        productsModal = new bootstrap.Modal(document.getElementById('categorizedProductsModal'), {
            focus: true
        });

        const modalElement = document.getElementById('categorizedProductsModal');

        modalElement.addEventListener('show.bs.modal', async function (event) {
            const triggerElement = event.relatedTarget; // Elemento que activo el modal
            const categoriaId = triggerElement.getAttribute('data-category-id');
            const categoryName = triggerElement.getAttribute('data-category-name');
            const categoryTitle = document.getElementById('categorizer-title');

            if (!categoriaId) {
                console.error('No category ID found in the trigger element');
                return;
            }

            showLoadingState();

            if (categoryName) {
                categoryTitle.textContent = `${categoryName}`;
            }

            try {
                const categorizedProducts = await ProductoService.getProductosCategoria(categoriaId);
                // console.log("Productos categorizados: ", categorizedProducts);
                renderProductItems(categorizedProducts.data);
                reinitializeLucideIcons();
            } catch (error) {
                    console.error(`Error al obtener productos para la categoria con ID ${categoriaId}`, error);
                    showErrorState();
            }
        });
    });

    const renderProductItems = (categorizedProducts) => {
        const container = document.getElementById("listado-productos-categoria");
        const isDisabled = false;
        const cantidadInicial = 0;
        container.innerHTML = '';

        const renderProductCard = (item, formattedName) => {
            container.innerHTML += `
                <div class="col-12">
                    <div data-card-height="140" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
                        <div class="d-flex flex-row gap-2"> 
                            <a href="${item.url_detalle}" class="product-card-image">
                                <img src="${item.imagen}"
                                    style="background-color: white;min-width: 130px" />
                            </a>
                            <div class="d-flex flex-column w-100 flex-grow-1 justify-content-center me-2">
                                <h4 class="me-1 font-20" style="max-height: 3rem;overflow: hidden">${formattedName.length > 50 ? formattedName.substring(0, 50) + '...' : formattedName}</h4>
                                ${renderTagsRow(item)}
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    ${renderPriceSection(item)}
                                    <div class="d-flex flex-row gap-1">
                                        <button ruta="${item.url_detalle}" class="btn px-1 copiarLink rounded-s bg-red-light font-900">
                                            <i class="fa fa-link"></i>
                                        </button>
                                        ${renderActionButton(item)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        const renderActionButton = (item) => {
            if (!item.tiene_stock) {
                return `
                    <button class="btn btn-xs  rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
                        <div class="d-flex flex-row align-items-center gap-1">
                            <i class="fa fa-ban"></i>
                            <span class="font-10">Sin Stock</span>
                        </div>
                    </button>
                `;
            }
            
            return `
                <button
                    class="${item.tiene_adicionales ? "menu-adicionales-btn":"agregar-unidad"} btn rounded-s px-1 shadow-l bg-highlight font-900 text-uppercase"
                    data-producto-id="${item.id}"
                    data-producto-nombre="${item.nombre}"
                >
                    <div class="d-flex flex-row align-items-center gap-1">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="font-10">Añadir</span>
                    </div>
                </button>
            `;
        }

        const renderPriceSection = (productoPrecio) => {
            const tieneDescuento = productoPrecio.precio_original;

            if (tieneDescuento) {
                return `
                    <div class="d-flex flex-column m-0 justify-content-center w-100">
                        <p class="font-10 m-0"><del>Bs. ${productoPrecio.precio_original}</del></p>
                        <p class="font-17 font-weight-bolder color-highlight mb-0">Bs. ${productoPrecio.precio}</p>
                    </div>
                `;
            }
            
            return `<p class="font-17 font-weight-bolder color-highlight mb-0">Bs. ${productoPrecio.precio}</p>`;
        }

        const renderTagsRow = (item) => {
            if (item.tags && item.tags.length > 0) {
                return `
                    <div class="tags-container d-flex flex-row align-items-center justify-content-start gap-2">
                    ${item.tags.map(tag => `
                        <button popovertarget="poppytag-${item.id}-${tag.id}" popoveraction="toggle" style="anchor-name: --tag-btn-${item.id}-${tag.id};">
                            <i data-lucide="${tag.icono}" class="lucide-icon" style="width:1.5rem;height:1.5rem;"></i>
                        </button>
                        <div popover
                            id="poppytag-${item.id}-${tag.id}"
                            class="tag-info-popover bg-white bg-dtheme-blue p-2 rounded-2 shadow-lg border"
                            style="position-anchor: --tag-btn-${item.id}-${tag.id}; max-width:250px;">
                            <p class="color-theme">${tag.nombre}</p>
                        </div>
                    `).join('')}
                    </div>
                `;
            }
            return '';
        }

        if (categorizedProducts.length === 0) {
            container.innerHTML = `
                <div id="cart-summary-items" class="item-producto-categoria mb-3">
                    <p class="text-muted"><span>Ups!</span> Parece que aun no hay productos agregados a esta categoria, regresa mas tarde.</p>
                </div>`;
        }

        categorizedProducts.forEach(item => {
            // Condicionar el renderizado en el caso de que el producto disponga de un descuento
            // En el caso de disponer de descuento, se muestra el precio descontado, con el precio original tachado
            const formattedName = item.nombre.charAt(0).toUpperCase() + item.nombre.slice(1).toLowerCase();

            renderProductCard(item,formattedName);
        });
    }

    

    const showErrorState = () => {
        const container = document.getElementById("listado-productos-categoria");
        container.innerHTML = `
            <div id="cart-summary-items" class="item-producto-categoria mb-3">
                <p class="text-danger">
                    <span class="font-bold">Error!</span> 
                    No se pudieron cargar los productos. Por favor, intenta de nuevo.
                </p>
            </div>
        `;
    };

    const showLoadingState = () => {
    const container = document.getElementById("listado-productos-categoria");
    container.innerHTML = `
            <div class="d-flex justify-content-center align-items-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <span class="ms-3">Cargando productos...</span>
            </div>
        `;
    };
</script>
{{-- SCRIPT PRUEBA DIALOG LISTADO-PRODUCTOS --}}
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        // ACTIVAR MENU LISTADO 
        // Para un elemento especifico por ID [detalle-producto]
        // const activadorMenuPrueba = document.getElementById('menu-prueba-btn');
        // if (activadorMenuPrueba) {
        //     activadorMenuPrueba.addEventListener('click', async () => {
        //         console.log("Clickado en boton prueba")
        //         // Consulta axios prueba (panes integrales id=4)
        //         const tituloPrueba = "CategoriaX";
        //         const productosPrueba = await ProductoService.getProductosCategoria(4)
        //         abrirDialogListado(productosPrueba, tituloPrueba);
        //     });
        // }

        document.querySelectorAll('.productos-tag-trigger').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const tagElement = e.currentTarget; 
                const tagId = tagElement.getAttribute('data-tag-id');
                const tagNombre = tagElement.getAttribute('data-tag-nombre');
                const productosTag = await ProductoService.getProductosTag(tagId);

                abrirDialogListado(productosTag.data, tagNombre);
            });
        });
    });
</script>
@endpush