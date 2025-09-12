
@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Categorias Eco-Tienda" cabecera="appkit" />    
    <div class="card card-style">
        <div class="content">
            <h4>Estas son nuestras categorias!</h4>
            <p>
                Encuentra lo que mas te gusta!
            </p>
            {{-- BARRA DE BUSQUEDA --}}
            <div class="mb-0 input-style input-style-2 has-icon input-required ">
                <i class="input-icon fa fa-search color-theme"></i>
                <input type="text" 
                    id="category-search" 
                    class="form-control" 
                    placeholder="Buscar categorías..."
                    autocomplete="off">
                <label for="category-search" class="color-highlight font-10 font-900">BUSCAR CATEGORÍA</label>
            </div>
        </div>
    </div>

    {{-- CONTENEDOR DE CATEGORIAS --}}
    <div class="content mb-0" id="categories-container">
        @foreach($subcategorias as $subcategoria)
        <button 
            data-bs-toggle="modal" 
            data-bs-target="#categorizedProductsModal" 
            data-category-id="{{$subcategoria->id}}"
            data-category-name="{{$subcategoria->nombre}}"
            data-card-height="120" 
            class="category-card card card-style w-100 text-start mb-4 mx-0 hover-grow-s" 
            style="height: 120px;overflow: hidden">
            <div class="d-flex flex-row align-items-center gap-4"> 
                <div class="subcategory-card-image-lg card mb-0">
                    <img src="{{asset($subcategoria->rutaFoto())}}"
                        style="background-color: white;"
                        />
                    <div class="card-overlay rounded-0 dark-mode-tint opacity-70"></div>
                </div>
                <div class="d-flex flex-column w-75">
                    <h4 class="category-name me-1">{{$subcategoria->nombre}}</h4>
                    <p class="mt-n2 font-12 color-highlight mb-0">Delight</p>
                </div>
            </div>
        </button>
        @endforeach
    </div>

    {{-- MENSAJE DE SIN-RESULTADOS --}}
    <div id="no-results-message" class="card card-style" style="display: none;">
        <div class="content text-center">
            <i class="fa fa-search fa-3x color-theme mb-3"></i>
            <h5>No se encontraron categorías</h5>
            <p class="text-muted">
                No hay categorías que coincidan con "<span id="search-term"></span>". 
                Intenta con otros términos de búsqueda.
            </p>
        </div>
    </div>

    {{-- MODAL PRODUCTOS CATEGORIZADOS --}}
    <div class="modal fade" id="categorizedProductsModal" tabindex="-1" aria-labelledby="categorizedProductsModalLabel" aria-hidden="true" style="z-index: 9999">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mx-2 mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="categorizer-title" class="mb-0 ms-0 align-self-center text-uppercase">Todos los productos de esta categoria!</h4>
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

    <x-menu-adicionales-producto :isUpdate="false"/>
@endsection

@push('scripts')
{{-- FUNCIONALIDAD AGREGAR AL CARRITO --}}
<script> 
    // $(document).ready(function() {
    //     $(document).on('click', '.add-to-cart', addToCartHandler);
    // });
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
{{-- FUNCION DE BUSQUEDA  --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('category-search');
        const categoriesContainer = document.getElementById('categories-container');
        const noResultsMessage = document.getElementById('no-results-message');
        const searchTermSpan = document.getElementById('search-term');
        const categoryCards = document.querySelectorAll('.category-card');
        
        let searchTimeout;

        function performSearch(searchTerm) {
            // Texto ingresado normalizado
            const normalizedSearch = searchTerm.toLowerCase().trim();
            let visibleCount = 0;

            categoryCards.forEach(card => {
                const categoryName = card.getAttribute('data-category-name');
                const normalizedCategoryName = categoryName.toLowerCase();
                
                // Si el valor ingresado en el input coincide con el nombre de la categoria
                if (normalizedCategoryName.includes(normalizedSearch)) {
                    // Hacer el card visible
                    card.style.display = 'block';
                    visibleCount++;
                    
                    // Transicion de opacidad para estilizado
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.transition = 'opacity 0.3s ease-in-out';
                        card.style.opacity = '1';
                    }, 50);
                } else {
                    // De no coincidir, ocultar el card
                    card.style.display = 'none';
                }
            });

            // Mostrar u ocultar el mensaje de no encontrados
            if (visibleCount === 0 && normalizedSearch !== '') {
                searchTermSpan.textContent = searchTerm;
                noResultsMessage.style.display = 'block';
                categoriesContainer.style.display = 'none';
            } else {
                noResultsMessage.style.display = 'none';
                categoriesContainer.style.display = 'block';
            }
        }

        function handleSearch() {
            // Termino a buscarse
            const searchTerm = searchInput.value;
            
            // Limpiar el timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            // Aregado de delay para evitar busqueda instantanea con cada input
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm);
            }, 300);
        }

        // Funcion para limpiar el valor en busqueda
        function clearSearch() {
            searchInput.value = '';
            performSearch('');
            searchInput.focus();
        }

        // Llamado a handleSearch con cada input nuevo a la barra de busqueda
        searchInput.addEventListener('input', handleSearch);
        
        // Limpiar la busqueda precionando esc
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
        });
    });
</script>
{{-- SCRIPT CONTROL DEL MODAL PRODUCTOS CATEGORIZADOS [] --}}
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
@endpush