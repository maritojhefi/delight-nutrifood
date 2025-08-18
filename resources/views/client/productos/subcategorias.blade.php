@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Categorias Eco-Tienda" cabecera="appkit" />    
    <div class="card card-style">
        <div class="content">
            <h4>Estas son nuestras categorias!</h4>
            <p>
                Encuentra lo que mas te gusta!
            </p>
        </div>
    </div>
    <div class="content mb-0">
        @foreach($subcategorias as $subcategoria)
        <button 
        {{-- href="{{ route('delight.listar.productos.subcategoria', $subcategoria->id) }}"  --}}
        data-bs-toggle="modal" 
        data-bs-target="#categorizedProductsModal" 
        data-category-id="{{$subcategoria->id}}"
        data-category-name="{{$subcategoria->nombre}}"
        data-card-height="120" class="card card-style w-100 text-start mb-4 mx-0 hover-grow-s" style="height: 120px;overflow: hidden">
            <div class="d-flex flex-row align-items-center gap-4"> 
                <div class="subcategory-card-image-lg card mb-0">
                    {{-- <img src="{{ asset($subcategoria->rutaFoto()) }}" class="" style="background-color: white; border: ; " />  --}}
                    <img src="{{asset($subcategoria->rutaFoto())}}" 
                                    onerror="this.src='/imagenes/delight/default-bg-1.png';" 
                                    style="background-color: white;" />
                    <div class="card-overlay rounded-0 dark-mode-tint opacity-70"></div>
                </div>
                <div class="d-flex flex-column" style="max-width: 300px">
                    <h4 class="">{{$subcategoria->nombre}}</h4>
                    <p class="mt-n2 font-12 color-highlight mb-0">Delight</p>
                </div>
            </div>
        </button>
        @endforeach
    </div>

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
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
            productsModal = new bootstrap.Modal(document.getElementById('subcategoriesModal'), {
                focus: true
            });

            const modalElement = document.getElementById('subcategoriesModal');

            modalElement.addEventListener('show.bs.modal', async function (event) {
                const triggerElement = event.relatedTarget; // Elemento que activo el modal
            });
        });
</script> --}}
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
                                    onerror="this.src='/imagenes/delight/default-bg-1.png';" 
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
                    <p class="text-muted"><span>Ups!</span> Parece que aun ni hay productos agregados a esta categoria, regresa mas tarde.</p>
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
@endpush