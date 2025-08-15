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
            <div id="container-state-disponible" class="d-flex flex-column justify-content-center"></div>
            <div id="container-state-escaso" class="d-flex flex-column justify-content-center"></div>
            <div id="container-state-agotado" class="d-flex flex-column justify-content-center"></div>
        </div>
    </div>

    <div class="cart-slider-container">
        <div class="splide single-slider slider-has-dots splide--loop splide--ltr splide--draggable" id="single-slider-6">
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

    {{-- SUMMARY WARNING MODAL --}}
{{-- <div id="stock-warning" class="menu menu-box-modal bg-yellow-dark rounded-m" tabindex="-1" aria-hidden="true" data-menu-height="310" data-menu-width="350" style="display: none; width: 350px; height: 310px;">        <h1 class="text-center mt-4"><i class="fa fa-3x fa-times-circle scale-box color-white shadow-xl rounded-circle"></i></h1>
        <h1 class="text-center mt-3 text-uppercase color-white font-700">Oooops!</h1>
        <p class="boxed-text-l color-white opacity-70">x
             Parece que tienes productos agotados o con poco stock en tu carrito.<br> Por favor, verifica que tus productos esten disponibles.
        </p>
        <a href="#" class="close-menu btn btn-m btn-center-l button-s shadow-l rounded-s text-uppercase font-600 bg-white color-black">Hmmm, Check again?</a>
    </div> --}}

    {{-- SUMMARY WARNING MODAL --}}
<div class="modal fade" id="stock-warning" tabindex="-1" aria-labelledby="stockWarningLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered d-flex justify-content-center rounded-m">
        <div class="modal-content" style="max-width: 350px">
            <div class="modal-body text-center p-4 rounded-m">
                <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 250px;">
                    <div class="mb-3">
                        <i class="fa fa-3x fa-apple-alt color-red-dark shadow-xl rounded-circle"></i>
                    </div>
                    <h2 class="text-uppercase font-700 mb-3">Oooops!</h2>
                    <p class="opacity-70 mb-4 text-center">
                        Parece que tienes productos agotados o con poco stock en tu carrito.<br> 
                        Por favor, verifica que tus productos esten disponibles.
                    </p>
                    <button type="button" class="btn btn-center-l button-s shadow-l rounded-s text-uppercase font-600 bg-highlight color-black hover-grow-s" data-bs-dismiss="modal">
                        Entiendo
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

        // Obtener el carrito
        const cart = carritoStorage.getCart();
        // console.log("Contenido del carrito: ", cart)
        const cardContentContainer = document.querySelector('.cart-content');
        const cardSliderContainer = document.querySelector('.cart-slider-container');
        const summaryItemsContainer = document.getElementById('cart-summary-items');
        const summaryTotalContainer = document.getElementById('cart-totals');
        const availableItemsContainer = document.getElementById('container-state-disponible');
        const limitedItemsContainer = document.getElementById('container-state-escaso');
        const unavailableItemsContainer = document.getElementById('container-state-agotado');

        const emptyCartHTML = `
                    <div class="empty-cart text-center py-5">
                        <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tu carrito está vacío</h5>
                        <p class="text-muted">Agrega algunos productos para comenzar</p>
                    </div>
                `;

        // cardContentContainer.innerHTML = `
        //     <div class="text-center py-5">
        //         <div class="spinner-border text-primary" role="status">
        //             <span class="visually-hidden">Cargando...</span>
        //         </div>
        //         <p class="mt-2">Validando tu carrito...</p>
        //     </div>`;

        try {
            const response = await getCartProductsInfo({
                sucursaleId: 1,
                items: cart.items,
            })

            console.log("Contenido response: ", response);

            const disponiblesArray = Object.values(response.disponibles || {});
            const escasosArray = Object.values(response.escasos || {});
            const agotadosArray = Object.values(response.agotados || {});


            if (disponiblesArray.length === 0 &&
                escasosArray.length === 0 &&
                agotadosArray.length === 0) {
                console.log("Aparentemente response esta vacio");
                cardContentContainer.innerHTML = emptyCartHTML;
                // cardSliderContainer.innerHTML = renderSlider();
                return;
            }

            let cartHTML = '';
            let totalInicial = 0;
            console.log("antes del renderizado")


            // Renderizar items disponibles
            if (disponiblesArray.length > 0) {
                // const stateContainer = renderProductStateContainer('disponible')
                // cartHTML += stateContainer;
                availableItemsContainer.innerHTML += `<h5 class="mb-3 color-highlight" id="label-disponible">Productos Disponibles</h5>`;
                // cartHTML += `<h5 class="mb-3 color-highlight" id="label-disponible">Productos Disponibles</h5>`;

                disponiblesArray.forEach(producto => {
                    totalInicial += producto.precio * producto.cantidad_solicitada;
                    availableItemsContainer.innerHTML += renderCartItem(producto, 'disponible', producto.cantidad_solicitada);
                    summaryItemsContainer.innerHTML += renderSummaryItem(producto);
                });
            }
            // Renderizar items escasos
            if (escasosArray.length > 0) {
                limitedItemsContainer.innerHTML += `<h5 class="mb-3 text-warning" id="label-escaso">Productos con stock limitado</h5>`;
                
                escasosArray.forEach(producto => {
                    limitedItemsContainer.innerHTML += renderCartItem(producto, 'escaso', producto.cantidad_solicitada);
                });
            }
            // Renderizar items agotados
            if (agotadosArray.length > 0) {
                unavailableItemsContainer.innerHTML += `<h5 class="mb-3 text-danger" id="label-agotado">Productos agotados</h5>`;
                agotadosArray.forEach(producto => {
                    unavailableItemsContainer.innerHTML += renderCartItem(producto, 'agotado', producto.cantidad_solicitada);
                });
            }

            // cardContentContainer.innerHTML = cartHTML;
            cardContentContainer.innerHTML += `<button id="showSummaryButton" class="summary-btn btn w-30 align-self-center btn-sm rounded-sm bg-highlight font-800 text-uppercase">
                Realizar Pedido</button>`
            cardContentContainer.innerHTML += `<div class="cart-listing-footer d-flex flex-row">
                    <button class="delete-cart-btn ms-auto me-2" data-menu="menu-confirm">Eliminar Carrito</button>
                </div>`;
            summaryTotalContainer.innerHTML = renderSummaryTotal(totalInicial);

        } catch (error) {
            console.error("Sucedio un error al obtener los productos del carrito:", error);
        }
    })

    const renderProductStateContainer = (estado) => {
        return `<div id={container-state-${estado}}>
            </div>`
    }

    const renderCartItem = (producto, estado, cantidad) => {
        const isDisabled = estado !== 'disponible';
        const isLowStock = estado === 'escaso';
        const isUnavailable = estado === 'agotado';
        const disabledClass = '';
        const disabledOverlay = isUnavailable ? '<div class="card-overlay rounded-sm dark-mode-tint light-mode-tint"></div>' : '';

        // const disabledClass = '';


        let stockMessage = '';
        let actionButton = ''
        if (isLowStock && producto.stock_disponible !== "INFINITO") {
            stockMessage = `<div class="alert alert-warning py-1 px-2 mt-2 mb-0 small">
                Solo existen ${producto.stock_disponible} unidades disponibles de las ${producto.cantidad_solicitada} solicitadas.
            </div>`;
            actionButton = `<button data-product-id="${producto.id}" class="qty-fixer btn-s  rounded bg-highlight" style="z-index: 10;">
                Actualizar
                </button>`;
            } else if (isUnavailable) {
            actionButton = `<button data-product-id="${producto.id}" class="unavailable-fixer btn-s  rounded bg-red-dark" style="z-index: 10;">
                Eliminar
                </button>`;
        }

        return `
            <div class="cart-item-wrapper mb-4 ${disabledClass}" data-product-id="${producto.id}" data-product-state="${estado}" id="cart-item-wrapper-${producto.id}">
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
                            ${(isUnavailable) ? '':
                            `<button class="btn btn-xxs  bg-gray-dark opacity-100 delete-item-btn position-absolute"
                                    type="button"
                                    data-product-id="${producto.id}"
                                    title="Eliminar producto"
                                    style="top: 0.1rem; right: 0.01rem; z-index: 20;"
                                    >
                                <i class="fa fa-times"></i>
                            </button>`}
                        </div>
                        <div class="item-overlay rounded-sm card-overlay opacity-60"></div>
                    </div>
                    <div class="d-flex flex-row justify-content-between align-items-center mt-2" style="color: none !important">
                        <p class="fw-bold mb-0 text-success fs-5 product-price" data-price="${producto.precio}">
                            Bs. ${producto.precio.toFixed(2)}
                        </p>
                        ${(isLowStock || isUnavailable) ? actionButton : 
                        `<div class="quantity-controls bg-light border rounded d-flex align-items-center" style="min-width: 120px;">
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
                                ${cantidad}
                            </span>
                            <button class="btn btn-sm btn-outline-secondary border-0 px-2 py-1 qty-increase"
                                    type="button"
                                    data-product-id="${producto.id}"
                                    title="Aumentar cantidad"
                                    ${isDisabled ? 'disabled' : ''}>
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>`
                        }
                    </div>
                    ${stockMessage}
                    ${disabledOverlay}
                </div>
            </div>
        `;
    }

    const renderSummaryItem = (producto) => {
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

    const renderSummaryTotal = (total) => {
        return `<div class="d-flex flex-row justify-content-between align-items-center mb-3 pt-2 text-black">
            <p class="fw-bold fs-5 mb-0">Total:</span>
            <p id="cart-final-total" class="fw-bold fs-5 mb-0">Bs. ${total.toFixed(2)}</span>
            </div>
        `;
    }

    document.addEventListener('DOMContentLoaded', async function() {

        document.addEventListener('click', async function(e) {
            // Handle a click on the increase button.
            if (e.target.closest('.qty-increase')) {
                await handleProductIncrease(e);
            }

            // Handle a click on the decrease button.
            if (e.target.closest('.qty-decrease')) {
                await handleProductDecrease(e);
            }

            if (e.target.closest('.summary-btn')) {
                await handleSummaryCheck(e);
            }

            if (e.target.closest('.qty-fixer')) {
                await handleUpdateLimitedProduct(e);
            }
        });
    });



    const handleSummaryCheck = async(e) => {
        e.preventDefault();

        const cartToCheck = carritoStorage.getCart();
        const summaryCheckButton = e.target.closest('.summary-btn');
        const summaryModal = new bootstrap.Modal(document.getElementById('cartSummaryModal'));
        const warningModal = new bootstrap.Modal(document.getElementById('stock-warning'));
        // const checkoutBtn = document.getElementById('summary-btn');

        const modalElement = document.getElementById('cartSummaryModal');
        if (modalElement && modalElement.parentNode !== document.body) {
            document.body.appendChild(modalElement);
        }

        try {
            const cartCheckResponse = await getCartProductsInfo({
                sucursaleId: 1,
                items: cartToCheck.items,
            })

            console.log("Respuesta Summary Check:", cartCheckResponse);
            const escasosArray = Object.values(cartCheckResponse.escasos || {});
            const agotadosArray = Object.values(cartCheckResponse.agotados || {});
            if (escasosArray.length == 0 && agotadosArray.length == 0) {
                // Continuar con el resumen
                summaryModal.show();
                return;
            } else {
                // Renderizar modal de curar carrito
                console.log("No pasaste el vibe check")
                warningModal.show()
            }
        } catch (error) {
            console.error("Error validanto carrito para resumen: ",error);
        }
    }

    // // Control para el modal de resumen pedido
    // const handleCartSummaryModal = () => {
    //     // Inicializar el modal de resumen
    //     const summaryModal = new bootstrap.Modal(document.getElementById('cartSummaryModal'));
    //     // const checkoutBtn = document.getElementById('summary-btn');

    //     const modalElement = document.getElementById('cartSummaryModal');
    //     if (modalElement && modalElement.parentNode !== document.body) {
    //         document.body.appendChild(modalElement);
    //     }

    //     // Renderizar el modal de resumen al realizar click
    //     document.addEventListener('click', function(e) {
    //         if (e.target.closest('.summary-btn')) {
    //             e.preventDefault();
    //             summaryModal.show();
    //         }
    //     });
    // }

    const handleUpdateLimitedProduct = async (e) => {
        e.preventDefault();

        const quantityFixButton = e.target.closest('.qty-fixer');
        const productToFixId = quantityFixButton.getAttribute('data-product-id');
        const productInfo = await ProductoService.getProduct(productToFixId);
        const availableItemsContainer = document.getElementById('container-state-disponible')
        console.log("ProductInfo obtained to be passed onto RenderCartItem: ", productInfo);

        // Actualizar el valor al maximo disponible en el carrito
        const updateResponse = await carritoStorage.updateProductToMax(productToFixId);
        // Usar un getCartProductInfo
        if (updateResponse.success == true && updateResponse.quantity >= 1) {
            removeItemWrapper(productToFixId);
            availableItemsContainer.innerHTML += renderCartItem(productInfo,'disponible', updateResponse.quantity);
            // noti de ajuste o algo
        } else if (updateResponse.success == true && updateResponse.quantity == 0) {
            removeItemWrapper(productToFixId);  
            renderCartItem(productInfo,'agotado', 0)
        }
    }

    const handleProductIncrease = async (e) => {
        e.preventDefault();
        const button = e.target.closest('.qty-increase');
        const productToIncreaseId = button.getAttribute('data-product-id');
        const quantitySpan = document.getElementById(`item-${productToIncreaseId}-qty`);

        const IncreaseAttemp = await carritoStorage.addToCart(productToIncreaseId, 1, false, true);

        console.log("Respuesta intento: ", IncreaseAttemp);
        if (IncreaseAttemp.success === true) {
            console.log("Realizando tras success");
            quantitySpan.textContent = IncreaseAttemp.newQuantity;
        } else {
            console.error("No se pudo incrementar el valor del producto: ", productToIncreaseId);
        }
    }

    const handleProductDecrease = async(e) => {
        e.preventDefault();
        const button = e.target.closest('.qty-decrease');
        const productToDecreaseId = button.getAttribute('data-product-id');
        const quantitySpan = document.getElementById(`item-${productToDecreaseId}-qty`);

        // Check if quantity is 1 or less before proceeding
        if (parseInt(quantitySpan.textContent, 10) <= 1) return;

        console.log("Product to decrease ID:", productToDecreaseId)

        const DecreaseAttemp = await carritoStorage.substractFromCart(productToDecreaseId, 1);

        console.log("Respuesta intento: ", DecreaseAttemp);
        if (DecreaseAttemp.success === true) {
            quantitySpan.textContent = DecreaseAttemp.newQuantity;
        } else {
            console.error("No se pudo reducir el valor del producto: ", productToDecreaseId);
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // handleCartSummaryModal();
        handleCartDeletion();
        document.addEventListener('click', handleProductRemoval);
    });

    // // Control para el modal de resumen pedido
    // const handleCartSummaryModal = () => {
    //     // Inicializar el modal de resumen
    //     const summaryModal = new bootstrap.Modal(document.getElementById('cartSummaryModal'));
    //     // const checkoutBtn = document.getElementById('summary-btn');

    //     const modalElement = document.getElementById('cartSummaryModal');
    //     if (modalElement && modalElement.parentNode !== document.body) {
    //         document.body.appendChild(modalElement);
    //     }

    //     // Renderizar el modal de resumen al realizar click
    //     document.addEventListener('click', function(e) {
    //         if (e.target.closest('.summary-btn')) {
    //             e.preventDefault();
    //             summaryModal.show();
    //         }
    //     });
    // }

    // Control para la eliminacion del carrito
    const handleCartDeletion = () => {
        // Obtener el modal de confirmacion de eliminacion
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteCartModal'));
        // Obtener el boton de confirmacion de eliminacion
        const confirmDeleteBtn = document.getElementById('confirmDeleteCart');

        // Revelar el modal de confirmacion al hacer click en el boton de eliminacion
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-cart-btn')) {
                e.preventDefault();
                confirmModal.show();
            }
        });

        // Manejo del click de confirmacion dentro del modal
        confirmDeleteBtn.addEventListener('click', function() {
            // Limpiar el Carrito
            carritoStorage.emptyCart();
            // Renderizado del Carrito Vacio
            renderEmptyCart();
            // Actualizar el contador
            carritoStorage.updateCartCounterEX();
            // Ocultar el modal y mostrar mensaje de exito de ser necesario
            confirmModal.hide();
        });
    }

    // Control para retirar productos del carrito
    const handleProductRemoval = (e) => {
        const button = e.target.closest('.delete-item-btn, .unavailable-fixer');
        if (!button) return; // nothing matched

        e.preventDefault();
        // Filtrar el objeto a remover
        const productId = button.getAttribute('data-product-id');

        // Remover el producto del carrito
        carritoStorage.removeProduct(productId);

        // Remover el elemento renderizado
        removeItemWrapper(productId);

        // Revisar si el carrito esta vacio
        const remainingItems = document.querySelectorAll('.cart-item-wrapper');
        if (remainingItems.length === 0) {
            // De estar vacio, renderizar el carrito vacio
            renderEmptyCart();
        }

        // Actualizar el contador del carrito
        carritoStorage.updateCartCounterEX();
    }

    const removeItemWrapper = (productId) => {
        // Localizar el wrapper a remover
        const wrapperToRemove = document.getElementById(`cart-item-wrapper-${productId}`);
        // Obtener el estado del wrapper/producto
        const wrapperState = wrapperToRemove.getAttribute('data-product-state');
        // De existir el elemento, retirarlo
        if (wrapperToRemove) {
            wrapperToRemove.remove();
        }

        // Revisar si existen elementos del mismo estado
        const remainingItemsWithState = document.querySelectorAll(`.cart-item-wrapper[data-product-state="${wrapperState}"]`);
        if (remainingItemsWithState.length === 0) {
            // De no existir, elminar el label correspondiente
            const labelToRemove = document.getElementById(`label-${wrapperState}`);
            if (labelToRemove) {
                labelToRemove.remove();
            }
        }
    }

       // Renderizardo de Carrito Vacio
    const renderEmptyCart = () => {
        const cartContent = document.querySelector('.cart-content');
        if (cartContent) {
            cartContent.innerHTML = `<div class="empty-cart text-center py-5">
                    <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tu carrito está vacío</h5>
                    <p class="text-muted">Agrega algunos productos para comenzar</p>
                </div>`
        }
    };
</script>
@endpush