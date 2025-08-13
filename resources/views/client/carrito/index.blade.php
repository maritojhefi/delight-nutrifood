@extends('client.master')
@section('content-comentado')
    <x-cabecera-pagina titulo="Mi carrito" cabecera="appkit" />
    <div class="card card-style">
        <div class="content">
            @foreach ($user->addCarrito as $item)
            <div class="d-flex pb-2">
                <div class="me-auto">
                    <img src="{{asset($item->pathAttachment())}}" class="rounded-m shadow-xl" width="110">
                    <a href="#" data-menu="cart-item-edit" class="color-white mt-n5 py-3 ps-2 d-block font-11"><i
                            class="fa fa-pen ps-2 pe-2"></i> </a>
                </div>
                <div class="ms-auto w-100 ps-3">
                    <h5 class="font-14 font-600 opacity-80 pb-2">{{$item->nombre()}} </h5>
                    <div class="clearfix"></div>
                    <h1 class="font-23 font-700 float-start pt-2 ">{{$item->precio()}}<sup class="font-15 opacity-50">BS</sup></h1>
                    <div class="">
                        <div
                            class="input-style float-end w-50 has-borders no-icon input-style-always-active validate-field mb-4">
                            <input type="number" class="form-control validate-number font-500 font-12"  value="{{$item->pivot->cantidad}}"
                                placeholder="1">
                            <label for="form2a" class="color-highlight">Cantidad</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach


            <div class="divider mt-3"></div>
            <h4>Resumen de pedido</h4>
            <p>
                El pedido se procesara una vez haya completado el pago
            </p>
            <div class="row mb-0">

                <div class="col-6 text-start">
                    <h4>Total</h4>
                </div>
                <div class="col-6 text-end">

                    <h4 class="font-600">{{$user->addCarrito->sum('precio')}}<sup>BS</sup></h4>
                </div>
            </div>
            <div class="divider mt-4"></div>
            <button class="btn btn-full btn-sm rounded-sm font-800 text-uppercase">Realizar pago seguro</button>
        </div>
    </div>
@endsection



@section('content')
    <x-cabecera-pagina titulo="Mi Carrito" cabecera="appkit" />
    <div class="listado-carrito card card-style">
        <div class="content cart-content d-flex flex-column justify-content-center">
        </div>
    </div>

    <div class="cart-slider-container">
        <div class="splide single-slider slider-has-arrows slider-arrows-push slider-has-dots splide--loop splide--ltr splide--draggable" id="single-slider-6">
            <div class="splide__arrows">
                <button class="splide__arrow splide__arrow--prev bg-white" type="button" aria-controls="single-slider-6-track" aria-label="Previous slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button>
                <button class="splide__arrow splide__arrow--next bg-white" type="button" aria-controls="single-slider-6-track" aria-label="Go to first slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button>
            </div>
            <div class="splide__track" id="single-slider-6-track">
                <div class="splide__list" id="single-slider-6-list" style="transform: translateX(-1520px);">
                    <div class="splide__slide splide__slide--clone" style="width: 380px;" >
                        <div data-card-height="250" class="bg-red-dark card mx-3 bg-14 rounded-m shadow-l">
                            <div class="card-top mt-4 d-flex flex-column align-items-center">
                                    <a href="/miperfil/misplanes" class="text-white">
                                        <i class="fa fa-heart fa-9x"></i>
                                    </a>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">Planes</h2>
                                <p class="under-heading color-white">Accede a planes personalizables y nos encargaremos de todo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide splide__slide--clone" style="width: 380px;">
                        <div data-card-height="250" class="bg-green-light card mx-3 bg-14 rounded-m shadow-l">
                            <div class="card-top mt-4 d-flex flex-column align-items-center">
                                    <a href="/lineadelight" class="text-white">
                                        <i class="fa fa-leaf fa-9x"></i>
                                    </a>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">Linea Delight</h2>
                                <p class="under-heading color-white">Date el gusto y obten el producto que deseas.</p>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide" id="single-slider-6-slide01" style="width: 380px;">
                        <div data-card-height="250" class="bg-brown-light card mx-3 bg-18 rounded-m shadow-l">
                            <div class="card-top mt-4 d-flex flex-column align-items-center">
                                    <a href="/productos" class="text-white">
                                        <i class="fa fa-gem fa-9x"></i>
                                    </a>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">Eco-Tienda</h2>
                                <p class="under-heading color-white">Destaca con accesorios mientras ayudas al planeta.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="splide__pagination">
                <li>
                    <button class="splide__pagination__page" type="button"
                    aria-controls="single-slider-6-slide01" aria-label="Go to slide 1"></button>
                </li>
                <li>
                    <button class="splide__pagination__page" type="button"
                    aria-controls="single-slider-6-slide02" aria-label="Go to slide 2"></button>
                </li>
                <li>
                    <button class="splide__pagination__page" type="button"
                    aria-controls="single-slider-6-slide03" aria-label="Go to slide 3"></button>
                </li>
            </ul>
        </div>
    </div>

    <!-- CONFIRM MODAL -->
    <!-- Cart Deletion Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteCartModal" tabindex="-1" aria-labelledby="confirmDeleteCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content mx-5">
                <div class="modal-header border-0 pb-0 align-self-center">
                    <h1 class="modal-title fw-bold text-danger" id="confirmDeleteCartModalLabel">
                        ¿Estas Seguro?
                        <i class="fa fa-exclamation-triangle me-2"></i>
                    </h1>
                </div>
                <div class="modal-body pt-2 pb-3">
                    <p class="text-muted text-center font-15 mb-0">
                        Todos los productos en tu carrito seran retirados.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex flex-row align-items-center justify-content-center gap-5">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn confirm-cart-deletion btn-danger" id="confirmDeleteCart">
                        <i class="fa fa-trash me-1"></i>
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- CART SUMMARY MODAL --}}
    <div class="modal fade" id="cartSummaryModal" tabindex="-1" aria-labelledby="cartSummaryModalLabel" aria-hidden="true">
        <div class="modal-dialog align-self-center">
        {{-- <div class="resumen-carrito card  bg-highlight my-4 py-4   d-flex flex-column justify-content-center align-items-center mb-auto"> --}}
            <div class="content card modal-body rounded-sm d-flex flex-column justify-content-center align-items-center" style="z-index: 10">
                <h2 class="mt-4">Resumen del pedido</h2>
                <p class="">El pedido se procesara una vez realizado el pago</p>  
                <div class="card card-style mx-2" style="min-width: 300px;">
                    
                    <div class="content resumen-carrito-detalles">
                        <!-- Contenedor resumen items individuales-->
                        <div id="cart-summary-items" class="resumen-carrito-detalles mb-3">
                            <!-- Los items se renderizan aqui por renderSummaryItems() -->
                        </div>
                        
                        <!-- Seccion de totales -->
                        <div id="cart-totals" class="cart-totals">
                            <!-- El total sera renderizado aqui por renderSummaryTotal() -->
                        </div>
                        
                        <!-- Boton de pago -->
                        <button id="checkout-btn" class="btn btn-m rounded-sm text-uppercase font-800 w-100">
                            Realizar Pago Seguro
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
<script src="{{ asset('js/carrito-service/carrito-service.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function(){
        
        const cart = JSON.parse(localStorage.getItem('cart')) || {item: []};

        // Show a loading State
        const cardContentContainer = document.querySelector('.cart-content');
        const cardSliderContainer = document.querySelector('.cart-slider-container');
        const summaryItemsContainer = document.getElementById('cart-summary-items');
        const summaryTotalContainer = document.getElementById('cart-totals');

        const emptyCartHTML = `
                    <div class="empty-cart text-center py-5">
                        <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tu carrito está vacío</h5>
                        <p class="text-muted">Agrega algunos productos para comenzar</p>
                    </div>
                `;
        
        cardContentContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Validando tu carrito...</p>
            </div`;

        try {
            const response = await getCartProductsInfo({
                sucursaleId: 1,
                items: cart.items,
            })

            // 4. Render items based on availability
            if (response.disponibles.length === 0 && 
                response.escasos.length === 0 && 
                response.agotados.length === 0) {
                cardContentContainer.innerHTML = emptyCartHTML;
                // cardSliderContainer.innerHTML = renderSlider();
                return;
            }

            let cartHTML = '';

            // Render available items
            let totalInicial = 0;
            if (response.disponibles.length > 0) {
                cartHTML += `<h5 class="mb-3">Productos Disponibles</h5>`;
                response.disponibles.forEach(producto => {
                    totalInicial += producto.precio * producto.cantidad_solicitada;
                    cartHTML += renderCartItem(producto, 'disponible');
                    summaryItemsContainer.innerHTML += renderSummaryItem(producto);
                });
            }
            // Render low stock items
            if (response.escasos.length > 0) {
                cartHTML += `<h5 class="mb-3 mt-4 text-warning">Productos con stock limitado</h5>`;
                response.escasos.forEach(producto => {
                    cartHTML += renderCartItem(producto, 'escaso');
                });
            }

            // Render out of stock items
            if (response.agotados.length > 0) {
                cartHTML += `<h5 class="mb-3">Productos agotados</h5>`;
                response.agotados.forEach(producto => {
                    cartHTML += renderCartItem(producto, 'agotado');
                });
            }

            cardContentContainer.innerHTML = cartHTML;
            cardContentContainer.innerHTML += `<button id="summary-btn" class="summary-btn btn w-30 align-self-center btn-sm rounded-sm bg-highlight font-800 text-uppercase">
                Realizar Pedido</button>`
            cardContentContainer.innerHTML += `<div class="cart-listing-footer d-flex flex-row">
                    <button class="delete-cart-btn ms-auto me-2" data-menu="menu-confirm">Eliminar Carrito</button>
                </div>`;
            summaryTotalContainer.innerHTML = renderSummaryTotal(totalInicial);

            console.log("Contenido de la respuesta: ", response);

        } catch (error) {
            console.error("Sucedio un error al obtener los productos del carrito:", error);
        }
    })

    function renderCartItem(producto, estado) {
        const isDisabled = estado !== 'disponible';
        const disabledClass = isDisabled ? 'opacity-50 pe-none' : '';
        const stockMessage = estado === 'escaso' ? 
            `<div class="alert alert-warning py-1 px-2 mb-2 small">
                Solo ${producto.stock_disponible} unidades disponibles
            </div>` : 
            (estado === 'agotado' ? 
            `<div class="alert alert-danger py-1 px-2 mb-2 small">
                Producto agotado
            </div>` : '');

        return `
            <div class="cart-item-wrapper mb-4 ${disabledClass}" data-product-id="${producto.id}">
                ${stockMessage}
                <div class="card mb-0 d-flex flex-column item-carrito-info justify-content-between p-3 bg-white rounded-sm shadow-sm border">
                    <div class="mb-0 d-flex flex-row justify-content-between">
                        <div class="d-flex flex-column item-carrito-detalles flex-grow-1 me-3" style="z-index: 10">
                            <h5 class="fw-bold text-dark mb-2 product-name">${producto.nombre}</h5>
                            <p class="text-muted mb-3 small product-description">${producto.detalle}</p>
                            
                        </div>
                        <div class="product-image-container position-relative" style="z-index: 10">
                            <img class="product-image rounded" 
                                src="${producto.imagen ?? '/imagenes/delight/default-bg-1.png'}" 
                                alt="${producto.nombre}"
                                data-product-id="${producto.id}"
                                onerror="this.onerror=null; this.src='/imagenes/delight/default-bg-1.png';">
                            <button class="btn btn-danger delete-item-btn position-absolute" 
                                    type="button" 
                                    data-product-id="${producto.id}"
                                    title="Eliminar producto">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="item-overlay rounded-sm card-overlay opacity-60"></div>
                    </div>
                    <div class="d-flex flex-row justify-content-between align-items-center mt-2">
                        <p class="fw-bold mb-0 text-success fs-5 product-price" data-price="${producto.precio}">
                            Bs. ${producto.precio.toFixed(2)}
                        </p>
                        <div class="quantity-controls bg-light border rounded d-flex align-items-center" style="min-width: 120px;">
                            <button class="btn btn-sm btn-outline-secondary border-0 px-2 py-1 qty-decrease" 
                                    type="button" 
                                    data-product-id="${producto.id}"
                                    title="Disminuir cantidad"
                                    ${isDisabled ? 'disabled' : ''}>
                                <i class="fa fa-minus"></i>
                            </button>
                            <span id="item-${producto.id}-qty" 
                                class="px-3 fw-semibold product-quantity" 
                                data-product-id="${producto.id}">
                                ${producto.cantidad_solicitada}
                            </span>
                            <button class="btn btn-sm btn-outline-secondary border-0 px-2 py-1 qty-increase" 
                                    type="button" 
                                    data-product-id="${producto.id}"
                                    title="Aumentar cantidad"
                                    ${isDisabled ? 'disabled' : ''}>
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function renderSummaryItem(producto) {
        const itemSubtotal = (producto.precio * producto.cantidad_solicitada).toFixed(2);

    return `
        <div class="mb-2">
            <div class="item-name text-truncate fw-semibold mb-2" title="${producto.nombre}">
                <p class="mb-0 text-dark">${producto.nombre}</p>
            </div>
            <div class="d-flex flex-row justify-content-between align-items-center">
                <small class="text-muted d-flex align-items-center">
                    <span class="quantity-badge badge bg-secondary me-2">${producto.cantidad_solicitada}</span>
                    <span>× Bs. ${producto.precio.toFixed(2)}</span>
                </small>
                <div class="item-subtotal fw-bold text-success">
                    <p class="mb-0 fs-6">Bs. ${itemSubtotal}</p>
                </div>
            </div>
            <div class="divider mt-1 mb-0"></div>
        </div>
        `;
    }

    function renderSummaryTotal(total) {
        return `<div class="d-flex flex-row justify-content-between align-items-center mb-3 pt-2 text-black">
            <p class="fw-bold fs-5 mb-0">Total:</span>
            <p id="cart-final-total" class="fw-bold fs-5 mb-0">Bs. ${total.toFixed(2)}</span>
            </div>
        `;
    }
</script>
<script>
    // // Initialize cart manager when DOM is loaded
    // document.addEventListener('DOMContentLoaded', function() {
    // // Replace the basic CartManager with EnhancedCartManager
    //     window.cartManager = new EnhancedCartManager();
        
    //     // Bind checkout button
    //     const checkoutBtn = document.getElementById('checkout-btn');
    //     if (checkoutBtn) {
    //         checkoutBtn.addEventListener('click', function() {
    //             window.cartManager.processCheckout();
    //         });
    //     }
        
    //     // Initial render
    //     window.cartManager.renderCartSummary();
        
    //     // Expose useful methods for debugging
    //     window.getCheckoutData = () => window.cartManager.getCheckoutData();
    // });
    
    // // Additional cart-specific functionality
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Update the cart summary display
    //     const originalUpdateCartUI = window.cartManager.updateCartUI;
    //     window.cartManager.updateCartUI = function() {
    //         originalUpdateCartUI.call(this);
            
    //         // Update cart total display
    //         const totalElement = document.getElementById('cart-total');
    //         if (totalElement) {
    //             totalElement.textContent = `Bs. ${this.getCartTotal().toFixed(2)}`;
    //         }
            
    //         // Update item count display
    //         const countElement = document.getElementById('cart-item-count');
    //         if (countElement) {
    //             countElement.textContent = this.getItemCount();
    //         }
            
    //         // Show/hide empty cart message
    //         const cartContent = document.querySelector('.content');
    //         const cartItems = document.querySelectorAll('.cart-item-wrapper');
            
    //         if (cartItems1.length === 0) {
    //             cartContent.innerHTML = `
    //                 <div class="empty-cart text-center py-5">
    //                     <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
    //                     <h5 class="text-muted">Tu carrito está vacío</h5>
    //                     <p class="text-muted">Agrega algunos productos para comenzar</p>
    //                 </div>
    //             `;
    //         }
    //     };
    // });

    // Eliminacion del Carrito
    document.addEventListener('DOMContentLoaded', function() {
        const cart = JSON.parse(localStorage.getItem('cart')) || {item: []};
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteCartModal'));
        const confirmDeleteBtn = document.getElementById('confirmDeleteCart');

        // Move modal to body (if needed)
        const modalElement = document.getElementById('confirmDeleteCartModal');
        if (modalElement && modalElement.parentNode !== document.body) {
            document.body.appendChild(modalElement);
        }

        const deleteEntireCart = () => {
            // Clear cart items from localStorage
            localStorage.removeItem('cart');
            // Clear cart items from the UI
            const cartContent = document.querySelector('.cart-content');
            if (cartContent) {
                cartContent.innerHTML = `<div class="empty-cart text-center py-5">
                        <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tu carrito está vacío</h5>
                        <p class="text-muted">Agrega algunos productos para comenzar</p>
                    </div>` 
            } 
        }

        // Event delegation: Listen for clicks on the document (or a closer static parent)
        document.addEventListener('click', function(e) {
            // Check if the clicked element is the delete button (or a child of it)
            if (e.target.closest('.delete-cart-btn')) {
                e.preventDefault();
                confirmModal.show();
            }
        });

        // Handle confirmation
        confirmDeleteBtn.addEventListener('click', function() {
            confirmModal.hide();
            deleteEntireCart();
            showSuccessMessage('Carrito eliminado exitosamente');
        });
    });

    document.addEventListener('DOMContentLoaded', function() { 
        const summaryModal = new bootstrap.Modal(document.getElementById('cartSummaryModal'));
        const checkoutBtn = document.getElementById('summary-btn');

        const modalElement = document.getElementById('cartSummaryModal');
        if (modalElement && modalElement.parentNode !== document.body) {
            document.body.appendChild(modalElement);
        }

        // Actualizar el resumen del carrito

        document.addEventListener('click', function(e) {
            if (e.target.closest('.summary-btn')) {
                e.preventDefault();
                summaryModal.show();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {

        const cart = JSON.parse(localStorage.getItem('cart')) || { items: [] };
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-item-btn')) {
                e.preventDefault();
                const button = e.target.closest('.delete-item-btn');
                const productId = button.getAttribute('data-product-id');
                const cartItemWrapper = button.closest('.cart-item-wrapper');
                
                console.log("Delete button clicked for product ID:", productId);

                // Remover producto del carrito en localStorage
                cart.items = cart.items.filter(item => item.id.toString() !== productId);
                
                // Actualizar localStorage
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Retirar del DOM
                if (cartItemWrapper) {
                    cartItemWrapper.remove();
                    // showSuccessMessage('Producto eliminado del carrito');
                    
                    // Actualizar totales 
                    if (typeof updateCartTotals === 'function') {
                        updateCartTotals();
                    }
                }
            }
        });
    });
</script>
@endpush