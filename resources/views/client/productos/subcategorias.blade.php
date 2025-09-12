
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
            data-subcategoria-id="{{$subcategoria->id}}"
            data-subcategoria-nombre="{{$subcategoria->nombre}}"
            data-card-height="120" 
            class="productos-subcategoria-trigger category-card card card-style w-100 text-start mb-4 mx-0 hover-grow-s" 
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

    <x-menu-adicionales-producto :isUpdate="false"/>
    <x-modal-listado-productos />
@endsection

@push('scripts')
{{-- FUNCIONALIDAD AGREGAR AL CARRITO --}}
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
<!-- SCRIPT CONTROL DEL MODAL -->
<script>
    // LISTAR PRODUCTOS POR SUBCATEGORIAS [ECO-TIENDA]
    $(document).on('click', '.productos-subcategoria-trigger', async function(e) {
        e.preventDefault();
        
        const subcategoriaId = $(this).data('subcategoria-id');
        const subcategoriaNombre = $(this).data('subcategoria-nombre');
        
        console.log("Subcategoria ID:", subcategoriaId, "Nombre Subcategoria:", subcategoriaNombre);
        
        try {
            const productosSubcategoria = await ProductoService.getProductosCategoria(subcategoriaId);
            abrirDialogListado(productosSubcategoria.data, subcategoriaNombre);
        } catch (error) {
            console.error('Error cargando los productos de la subcategoria:', error);
        }
    });
</script>
@endpush