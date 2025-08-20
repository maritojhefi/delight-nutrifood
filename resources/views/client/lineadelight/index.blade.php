@extends('client.master')
@section('content')
    {{-- CABECERA --}}
    <x-cabecera-pagina titulo="Linea Delight" cabecera="appkit" />
    {{-- FUNCIONALIDAD DE BUSQUEDA --}}
    <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
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
    </div>

    {{-- CONTENIDO DE LA PAGINA --}}
    <div class="content mb-0">
        {{-- SLIDER INICIO LINEADELIGHT --}}
        <div class="my-4 splide single-slider slider-has-arrows slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="single-slider-1" style="visibility: visible">
            <div class="splide__track" id="single-slider-1-track">
                <div class="splide__list" id="single-slider-1-list">
                    {{-- LISTADO DE ELEMENTOS A RENDERIZARSE --}}
                    
                    {{-- ITEM POPULARES --}}
                    <div class="splide__slide mx-2 is-active is-visible" id="single-slider-1-slide01" style="width: 320px;">
                        <a href='#' data-bs-toggle="modal" data-bs-target="#categorizedProductsModal" data-category-id="000" data-category-name="Nuestros productos mas populares!" data-card-height="200" class="card bg-6 mb-0 shadow-l rounded-m" style="height: 200px; background-color: #FF5A5A;">
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
                    <div class="splide__slide mx-2 is-active is-visible" id="single-slider-1-slide02" style="width: 320px;">
                        <a href="{{ route('categoria.planes') }}" data-card-height="200" class="card bg-6 mb-0 shadow-l rounded-m" style="height: 200px;background-color: #4ECDC4;">
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
                </div>
            </div>
        </div>

        {{-- SLIDER HORARIOS --}}
        <div class="splide topic-slider slider-no-arrows slider-no-dots pb-2 splide--loop splide--ltr splide--draggable" id="topic-slider-1" style="visibility: visible;">
            <div class="splide__track" id="topic-slider-1-track" style="padding-left: 15px; padding-right: 40px;">
                <div class="splide__list" id="topic-slider-1-list" style="transform: translateX(-866.592px);">
                    <div class="splide__slide" id="topic-slider-1-slide01" style="width: 108.333px;">
                        <h1 class="font-16 d-block"><button class="time-btn opacity-50" data-time="manana">Mañana</button></h1>
                    </div>
                    <div class="splide__slide" id="topic-slider-1-slide02 is-active" style="width: 108.333px;">
                        <h1 class="font-16 d-block"><button class="time-btn opacity-50" data-time="tarde">Tarde</button></h1>
                    </div>
                    <div class="splide__slide" id="topic-slider-1-slide03" style="width: 108.333px;">
                        <h1 class="font-16 d-block"><button class="time-btn opacity-50" data-time="noche">Noche</button></h1>
                    </div>
                </div>
            </div>
        </div>

        {{-- SLIDER SUBCATEGORIAS --}}
        <x-slider-doble-subcategorias/>

        {{-- SLIDER PRODUCTOS MAS VENDIDOS --}}
        <div id="best-selling-container" class="my-4">
            <x-slider-productos :productos="$masVendidos" :title="'Los mas vendidos'" />
        </div>
    </div>

    <x-divider-manzana class="mb-4"/>

    {{-- CARD PRODUCTOS PUNTUADOS --}}
    @if ($conMasPuntos->count() > 0)
        <div class="card card-style rounded-md mx-0 preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
            style="background-image: url({{ asset('imagenes/delight/default-bg-vertical.jpg') }});">
            <div class="card-body">
                <div class="mx-4 mb-0">
                    <h4 class="color-white pt-3 font-24">Gana Puntos!</h4>
                    <p class="color-white pt-1 mb-2">
                        Los productos seleccionados atribuyen puntos por cada compra realizada.
                        Mientras mas puntos, mas premios!
                    </p>
                </div>
                <div class="card card-style bg-transparent m-0 shadow-0">
                    <div class="row mb-0 p-2">
                        @foreach ($conMasPuntos as $item)
                            <div class="col-6">
                                <a href="{{ route('delight.detalleproducto', $item->id) }}"
                                    class="card card-style py-3 d-flex align-items-center hover-grow" data-menu="menu-product">
                                    <img src="{{ asset('imagenes/delight/optimal_logo.svg')}}" alt="img" width="100"
                                    class="mx-auto">
                                    <div class="p-2">
                                        <p class="mb-0 font-600 text-center">{{ Str::limit($item->nombre(), 22) }}</p>
                                    </div>
                                    <div class="divider mb-0"></div>
                                    <div class="d-flex flex-row justify-content-between gap-4 mb-0">
                                        <p class="font-600 mb-0">Bs. {{ $item->descuento ? $item->descuento : $item->precio }}</p>
                                        <p class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl mb-0">{{ $item->puntos }} Pts</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-highlight opacity-90"></div>
            <div class="card-overlay dark-mode-tint"></div>
        </div>
    @endif

    {{-- MODAL PRODUCTOS CATEGORIZADOS --}}
    <div class="modal fade" id="categorizedProductsModal" tabindex="-1" aria-labelledby="categorizedProductsModalLabel" aria-hidden="true" style="z-index: 9999">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mx-2 mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="categorizer-title" class="mb-0 ms-4 align-self-center text-uppercase">Todos los productos de esta categoria!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body mt-0 pt-0 d-flex flex-column justify-content-center align-items-center">
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
@endsection

@push('scripts')
<script src="{{ asset(path: 'js/producto/producto-service.js') }}"></script>
<script src="{{ asset('js/carrito/index.js') }}"></script>
<script> 
    $(document).ready(function() {
        $(document).on('click', '.add-to-cart', addToCartHandler);
    });

    async function addToCartHandler() {
        const product_Id = $(this).data('producto-id');
        const product_nombre = $(this).data('producto-nombre')

        console.log("ID producto a agregar: ", product_Id);
        console.log("Nombre del producto a agregar: ", product_nombre);
        try {
            const result = await addToCart(product_Id, 1);
            if (result.success) {
                showMessage('success', 'Item agregado al carrito!');
            } else {
                showMessage('error', result.message);
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            showMessage('error', 'Error al agregar el producto al carrito');
        }
    }

    function showMessage(type, text) {
        $('#message-container').html(`<div class="alert alert-${type}">${text}</div>`);
        setTimeout(() => $('#message-container').empty(), 3000);
    }
</script>
<script>
    const subcategoriasPorHorario = @json($horarios);
</script>
    {{-- SCRIPT CONTROL DE SLIDER --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hour = new Date().getHours();
        let defaultTime = '';

        if (hour >= 4 && hour < 11) {
            defaultTime = 'manana';
        } else if (hour >= 11 && hour < 19) {
            defaultTime = 'tarde';
        } else {
            defaultTime = 'noche';
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

        // items/Categorias por defecto a renderizarse
        const defaultItems = subcategoriasPorHorario[defaultTime];
        updateSliderContent(defaultItems);
        // renderProductItems(defaultItems);
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
                    <div class="splide__slide hover-grow-s" style="width: 190px;">
                        <div class="card mx-3 mb-0 card-style bg-20"
                            data-card-height="250"
                            data-bs-toggle="modal" 
                            data-bs-target="#categorizedProductsModal" 
                            data-category-id="${item.id}"
                            data-category-name="${item.nombre}"
                            style="height: 250px; background-image: url('${item.foto}');">
                            <div class="card-bottom">
                                <h3 class="color-white font-800 mb-3 pb-1 mx-3">${formattedName}</h3>
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
                renderProductItems(categorizedProducts);
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
                    <div data-card-height="120" class="card card-style mb-4 mx-0 hover-grow-s" style="height: 120px;overflow: hidden">
                        <div class="d-flex flex-row align-items-center gap-3"> 
                            <a href="${item.url_detalle}" class="product-card-image">
                                <img src="${item.imagen}" 
                                    onerror="this.src='imagenes/delight/default-bg-1.png';" 
                                    style="background-color: white;" />
                            </a>
                            <div class="d-flex flex-column w-100 gap-2" style="max-width: 260px">
                                <h4 class="me-3">${formattedName.length > 50 ? formattedName.substring(0, 50) + '...' : formattedName}</h4>
                                <div class="d-flex flex-row align-items-center justify-content-between gap-4 mb-2">
                                    ${renderPriceSection(item)}
                                    <div class="d-flex flex-row gap-2">
                                        <button ruta="${item.url_detalle}" class="btn btn-xs copiarLink rounded-s btn-full shadow-l bg-red-light font-900">
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
                    <button class="btn btn-xs me-3 rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
                        <i class="fa fa-ban"></i>
                        Sin Stock
                    </button>
                `;
            }
            
            return `
                <button
                    class="add-to-cart btn btn-xs me-3 rounded-s btn-full shadow-l bg-highlight font-900 text-uppercase"
                    data-producto-id="${item.id}"
                    data-producto-nombre="${item.nombre}"
                >
                    <i class="fa fa-shopping-cart"></i>
                    Añadir
                </button>
            `;
        }

        const renderPriceSection = (item) => {
            const hasDiscount = item.descuento && (item.descuento > 0 && item.descuento < item.precio);
            
            if (hasDiscount) {
                return `
                    <div class="d-flex flex-column">
                        <p class="font-10 mb-0 mt-n2"><del>Bs. ${item.precio}</del></p>
                        <p class="font-21 mt-n2 font-weight-bolder color-highlight mb-0">Bs. ${item.descuento}</p>
                    </div>
                `;
            }
            
            return `<p class="font-21 font-weight-bolder color-highlight mb-0">Bs. ${item.precio}</p>`;
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
            // if (item.descuento && (item.descuento > 0 && item.descuento < item.precio))  {
            //     container.innerHTML += `
            //         <div class="col-12">
            //             <div data-card-height="120" class="card card-style mb-4 mx-0 hover-grow-s" style="height: 120px;overflow: hidden">
            //                 <div class="d-flex flex-row align-items-center gap-3"> 
            //                     <a href="${item.url_detalle}" class="product-card-image">
            //                     <img src="${item.imagen}" 
            //                         onerror="this.src='imagenes/delight/default-bg-1.png';" 
            //                         style="background-color: white;" />
            //                     </a>
            //                     <div class="d-flex flex-column w-100 gap-2" style="max-width: 260px">
            //                         <h4 class="me-3">${formattedName.length > 50 ? formattedName.substring(0, 50) + '...' : formattedName}</h4>
            //                         <div class="d-flex flex-row align-items-center justify-content-between gap-4 mb-2">
            //                             <div class="d-flex flex-column">
            //                                 <p class="font-10 mb-0 mt-n2"><del>Bs. ${item.precio}</del></p>
            //                                 <p class="font-21 mt-n2 font-weight-bolder color-highlight mb-0">Bs. ${item.descuento}</p>
            //                             </div>
            //                             <div class="d-flex flex-row gap-2">
            //                                 <button ruta="${item.url_detalle}" class="btn btn-xs copiarLink rounded-s btn-full shadow-l bg-red-light font-900">
            //                                     <i class="fa fa-link"></i>
            //                                 </button>
            //                                 <button
            //                                     class="add-to-cart btn btn-xs me-3 rounded-s btn-full shadow-l bg-highlight font-900 text-uppercase"
            //                                     data-producto-id="${item.id}"
            //                                     data-producto-nombre="${item.nombre}"
            //                                     >
            //                                     <i class="fa fa-shopping-cart"></i>
            //                                     Añadir
            //                                 </button>
            //                             </div>
            //                         </div>
            //                     </div>
            //                 </div>
            //             </div>
            //         </div>
            //     `;
            // } else {
            //     container.innerHTML += `
            //         <div class="col-12">
            //             <div data-card-height="120" class="card card-style mb-4 mx-0 hover-grow-s" style="height: 120px;overflow: hidden">
            //                 <div class="d-flex flex-row align-items-center gap-3"> 
            //                     <a href="${item.url_detalle}" class="product-card-image">
            //                     <img src="${item.imagen}" 
            //                         onerror="this.src='imagenes/delight/default-bg-1.png';" 
            //                         style="background-color: white;" />
            //                     </a>
            //                     <div class="d-flex flex-column w-100 gap-2" style="max-width: 260px">
            //                         <h4 class="me-3">${formattedName.length > 50 ? formattedName.substring(0, 50) + '...' : formattedName}</h4>
            //                         <div class="d-flex flex-row align-items-center justify-content-between gap-4 mb-2">
            //                                 <p class="font-21 font-weight-bolder color-highlight mb-0">Bs. ${item.precio}</p>
            //                             <div class="d-flex flex-row gap-2">
            //                                 <button ruta="${item.url_detalle}" class="btn btn-xs copiarLink rounded-s btn-full shadow-l bg-red-light font-900">
            //                                     <i class="fa fa-link"></i>
            //                                 </button>
            //                                 <button
            //                                     class="add-to-cart btn btn-xs me-3 rounded-s btn-full shadow-l bg-highlight font-900 text-uppercase"
            //                                     data-producto-id="${item.id}"
            //                                     data-producto-nombre="${item.nombre}"
            //                                     >
            //                                     <i class="fa fa-shopping-cart"></i>
            //                                     Añadir
            //                                 </button>
            //                             </div>
            //                         </div>
            //                     </div>
            //                 </div>
            //             </div>
            //         </div>
            //     `;
            // } 
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
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        popularsModal = new bootstrap.Modal(document.getElementById('popularProductsModal'), {
            focus: true
        });
    });
</script> --}}

@endpush