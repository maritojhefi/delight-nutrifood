@extends('client.master')
@section('content')
    <x-cabecera-pagina :titulo="$tiene_venta_activa ? 'Mi Pedido' : 'Mi Carrito'" cabecera="appkit" />
    <div class="listado-carrito card card-style">
        <div id="contenedor-principal" class="content cart-content d-flex flex-column justify-content-center">
            {{-- MENSAJE DE VALIDACION EN CURSO --}}
            <div id="info-validacion-carrito" class="cart-validation-info text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Validando tu {{ $venta_activa ? "pedido" : "carrito" }}...</p>
            </div>
            <!-- CONTENEDORES PEDEIDOS SEGUN ESTADO -->
            <div id="contenedor-aceptados" class="d-flex flex-column justify-content-center d-none">
                <h3>Pedidos Aceptados</h3>
                <ul id="lista-aceptados" class="list-unstyled"></ul>
            </div>
            <div id="contenedor-pendientes" class="d-flex flex-column justify-content-center d-none">
                <h3>Pedidos Pendientes</h3>
                <ul id="lista-pendientes" class="list-unstyled mb-0"></ul>
            </div>
            <div id="contenedor-opciones-pedido-cliente" class="d-none alignt-items-center justify-content-center">
                
            </div>
            {{-- CONTENEDORES DE PRODCUTOS SEGUN DISPONIBILIDAD --}}
            <div id="container-state-agotado" class="d-flex flex-column justify-content-center"></div>
            <div id="container-state-escaso" class="d-flex flex-column justify-content-center"></div>
            <div id="contenedor-productos-disponibles-carrito" class="d-flex flex-column justify-content-center"></div>
            <!-- <button id="habilitar-venta-debug">Cerrar Venta Debug</button>
            <button id="deshabilitar-venta-debug">Abrir Venta Debug</button> -->
        </div>
    </div>

    <div id="contenedor-sliders-carrito" class="cart-slider-container">
        <div class="splide single-slider slider-has-dots slider-no-arrows splide--loop splide--ltr splide--draggable" id="single-slider-6">
            <div class="splide__track" id="single-slider-6-track">
                <div class="splide__list" id="single-slider-6-list" style="transform: translateX(-1520px);">
                    <div class="splide__slide splide__slide--clone" style="width: 380px;" >
                        <div data-card-height="250" class="bg-red-dark card mx-3 bg-14 rounded-m shadow-l">
                            <div class="card-top mt-4 d-flex flex-column align-items-center">
                                    <a href="{{route("miperfil")}}" class="text-white">
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
                                    <a href="{{route("linea.delight")}}" class="text-white">
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
                                    <a href="{{route("productos")}}" class="text-white">
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
            <div class="modal-content mx-2">
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
                <div class="modal-footer border-0 pt-0 d-flex flex-row align-items-center justify-content-between px-3">
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
            <div class="content card modal-body rounded-sm d-flex flex-column gap-2 justify-content-center align-items-center" style="z-index: 10">
                <h2>Resumen del pedido</h2>
                <p class="mb-0">El pedido se procesara una vez realizado el pago</p>
                <div class="card card-style mx-2 p-4 resumen-carrito-detalles w-100 mb-0" >
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

    <!-- MODAL LISTADO ORDENES-PRODUCTO -->
    <!-- <x-modal-listado-ordenes-carrito /> -->
    <x-modal-listado-ordenes-venta />


@endsection
@push('scripts')
<script src="{{ asset('js/carrito-service/carrito-service.js') }}"></script>
<!-- REVELAR LISTADO DE ADICIONALES-PRODUCTOS -->
<!-- CARRITO -->
<script>
    $(document).on('click', '.listado-ordenes-carrito-trigger', async function(e) {
        e.preventDefault();
        
        const productoID = $(this).data('producto-id');
        const itemCarrito = carritoStorage.obtenerItemCarrito(productoID);
        const infoProducto = await CarritoService.obtenerInfoItemCarrito(itemCarrito, 1);
        // // console.log("La informacion obtenida llego del carrito")
        
        try {
            // renderizar con aviso?
            abrirDialogDetalleOrden(infoProducto.item);
        } catch (error) {
            console.error('Error abriendo el modal:', error);
        }
    });
</script>
<!-- VENTA -->
<script>
    $(document).on('click', '.listado-ordenes-trigger', async function(e) {
        e.preventDefault();
        
        const producto_venta_ID = $(this).data('producto-venta-id');
        // const itemCarrito = carritoStorage.obtenerItemCarrito(productoID);
        // const infoProducto = await CarritoService.obtenerInfoItemCarrito(itemCarrito, 1);
        const response = await VentaService.productoVenta(producto_venta_ID)
        const infoProducto = response.data;
        
        try {
            abrirDialogDetalleOrden(infoProducto);
        } catch (error) {
            console.error('Error abriendo el modal:', error);
        }
    });
</script>
<!-- CONTROL DE VENTA (Y LISTADO DE ORDENES -->
<script>
    const ventaActiva = @json($venta_activa ?? null);

    $(document).ready( async function() {

        if (ventaActiva) {
            // axios para venta activa
            await renderizarPrincipalPedidos();
        }
    });

    const renderizarPrincipalPedidos = async () => {
        const contenedorPrincipal = $('#contenedor-principal');

        // Obtener informacion venta
        const response = await VentaService.productosVenta();
        const productosVenta = response.data;

        if (productosVenta.length > 0) {
            renderizarProductosVenta(productosVenta);
        } else {
            console.log("NO hay productos en la venta")
            renderizarPedidoVacio();
        }

        const pedidosPendientes = productosVenta.filter(producto => producto.aceptado == false);

        if (pedidosPendientes.length > 0) {
            
            // Consultar con Product Owner
            // Renderizar Checkout? Una vez pagado el pedido pasara a aceptado?
            // Una vez pagado, podra continuar agregando pedidos en la misma venta?
        }
    }

    const renderizarProductosVenta = (productosVenta) => {
        const contenedorAceptados = $('#contenedor-aceptados');
        const contenedorPendientes = $('#contenedor-pendientes');
        const listaAceptados = $('#lista-aceptados');
        const listaPendientes = $('#lista-pendientes');

        const productosPendientes = productosVenta.filter(producto => producto.aceptado == false);
        const productosAceptados = productosVenta.filter(producto => producto.aceptado == true);

        if (productosPendientes.length > 0) {
            contenedorPendientes.removeClass('d-none');
            const htmlContentPendientes = productosPendientes
                .map(producto => construirCardProductoVenta(producto))
                .join('');
            listaPendientes.html(htmlContentPendientes);
            revelarOpcionesPedido(true);
        } else {
            contenedorPendientes.addClass('d-none');
        }

        if (productosAceptados.length > 0) {
            contenedorAceptados.removeClass('d-none');
            const htmlContentAceptados = productosAceptados
                .map(producto => construirCardProductoVenta(producto))
                .join('');
            listaAceptados.html(htmlContentAceptados);
        } else {
            contenedorAceptados.addClass('d-none');
        }



        listaPendientes.off('.pedidos-pendientes');

        listaPendientes 
            .on('click.pedidos-pendientes', '.borrar-pventa-btn', async function(e) {
                e.preventDefault();
                const pivotID = $(this).data('producto-venta-id');
                await eliminarPedido(pivotID);
            })
            .on('click.pedidos-pendientes', '.incrementar-simple', async function(e) {
                e.preventDefault();
                const productoIncrementarID = parseInt(this.dataset.productoId, 10);
                const productoVentaId = parseInt(this.dataset.pventaId, 10);
                await handleIncrementarProductoSimple(productoIncrementarID, productoVentaId);
            })
            .on('click.pedidos-pendientes', '.reducir-simple', async function(e) {
                e.preventDefault();
                const productoReducirID = parseInt(this.dataset.productoId, 10);
                const productoVentaId = parseInt(this.dataset.pventaId, 10);
                await handleReducirProductoSimple(productoVentaId);
            });
            
        
        
    }

    const revelarOpcionesPedido = (booleano) => {
        const contenedorOpciones = $('#contenedor-opciones-pedido-cliente');
        const htmlOpciones = `
                <button id="showSummaryButton" class="abrir-resumen-pedido btn w-30 align-self-center btn-sm rounded-sm bg-highlight font-800 text-uppercase">
                    Realizar Pedido
                </button>
            `;

        if (booleano) {
            contenedorOpciones.html(htmlOpciones);
            contenedorOpciones.removeClass('d-none');
            contenedorOpciones.addClass('d-flex');
            contenedorOpciones.on('click.pedidos-pendientes', '.abrir-resumen-pedido', async function(e) {
                e.preventDefault();
                await handleResumenPedido();
            });
        } else {
            contenedorOpciones.removeClass('d-flex');
            contenedorOpciones.addClass('d-none');
            contenedorOpciones.html('');
        }
    }
    const handleIncrementarProductoSimple = async (productoIncrementarID, productoVentaId) => {
        try {
            const respuestaIncremento = await VentaService.agregarProductoVenta(productoIncrementarID, 1);
            const cantidad = respuestaIncremento.data.cantidad;
            const precioFinal = respuestaIncremento.data.precio_final.toFixed(2);
            $(`#cantidad-pventa-${productoVentaId}`).text(cantidad);
            $(`#precio-pventa-${productoVentaId}`).text(`Bs. ${precioFinal}`);
            $(`#unidades-pventa-${productoVentaId}`).text(`Unidades: ${cantidad}`);
            $(`#pviejo-pventa-${productoVentaId}`).text(`Bs. ${respuestaIncremento.data.precio_original.toFixed(2) * cantidad}`);
        } catch (error) {
            console.log(error);
            if (error.response?.data?.stockProducto === 0) {
                mostrarToastAdvertencia("Límite alcanzado");
                return;
            }
            mostrarToastError("Ha sucedido un error al incrementar el pedido.");
        }
    }

    const handleReducirProductoSimple = async (productoVentaId) => {
        try {
            const respuestaReduccion = await VentaService.disminuirProductoVenta(productoVentaId);
            const cantidad = respuestaReduccion.data.cantidad;
            const precioFinal = respuestaReduccion.data.precio_final.toFixed(2);
            $(`#cantidad-pventa-${productoVentaId}`).text(cantidad);
            $(`#precio-pventa-${productoVentaId}`).text(`Bs. ${precioFinal}`);
            $(`#unidades-pventa-${productoVentaId}`).text(`Unidades: ${cantidad}`);
            $(`#pviejo-pventa-${productoVentaId}`).text(`Bs. ${respuestaReduccion.data.precio_original.toFixed(2) * cantidad}`);
        } catch (error) {
            console.log(error);
            if (error.response?.data.type == "warning") {
                // // mostrarToastAdvertencia("Límite alcanzado");
                return;
            }
            mostrarToastError("Ha sucedido un error al disminuir el pedido.");
        }
    }

    const eliminarPedido = async (pivotID) => {
        try {
            const response = await VentaService.eliminarPedidoCompleto(pivotID); 
            // renderizarProductosVenta(response.data);
            console.log("Respuesta tras eliminacion: ", response);
            eliminarCardProductoVenta(pivotID);
            mostrarToastSuccess("Pedido eliminado con éxito");
            if (response.data.length == 0) {
                // Ocultar listados de pedidos
                const contenedorAceptados = $('#contenedor-aceptados');
                const contenedorPendientes = $('#contenedor-pendientes');
                contenedorAceptados.removeClass('d-block');
                contenedorPendientes.addClass('d-none');
                revelarOpcionesPedido(false)
                // Renderizar PedidoVacio
                renderizarPedidoVacio();
            }
        } catch (error) {
            const serverResponse = error.response?.data; 
            
            let errorMessage = "Ha sucedido un error al eliminar el pedido."; 

            if (serverResponse && serverResponse.message) {
                errorMessage = serverResponse.message;
            } 
            
            mostrarToastError(errorMessage);
            console.error('Error al eliminar pedido:', error);
        }
    }

    const renderizarPedidoVacio = () => {
        const contenedor = $('#contenedor-principal');
        const termino = "pedido";
        const lucideIcon = "hand-platter";
        contenedor.html(construirCarritoVentaVacio(termino, lucideIcon));
        reinitializeLucideIcons();
    }


    const reemplazarCardProductoVenta = (productoVenta) => {
        const cardAntiguo = $(`#pedido-item-${productoVenta.pivot_id}`);
        const cardNuevo = construirCardProductoVenta(productoVenta);
        cardAntiguo.replaceWith(cardNuevo);
    }

    const eliminarCardProductoVenta = (pivotID) => {
        console.log("card a eliminar: ", pivotID);
        const cardEliminar = $(`#pedido-item-${pivotID}`);
        cardEliminar.remove();
    }

    const construirCardProductoVenta = (producto) => {
        return `
            <li id="pedido-item-${producto.pivot_id}">
                <div class="cart-item-wrapper mb-4" data-producto-id="${producto.id}"  data-pedido-aceptado="${producto.aceptado}">
                    <div class="card mb-0 d-flex flex-column item-carrito-info justify-content-between p-3 bg-white rounded-sm shadow-sm border">
                        <div class="mb-0 d-flex flex-row justify-content-between">
                            <div class="d-flex flex-column item-carrito-detalles flex-grow-1 me-3" style="z-index: 10">
                                <h5 class="fw-bold text-dark mb-2 product-name">${producto.nombre}</h5>
                                <small id="unidades-pventa-${producto.pivot_id}" class="color-theme">${`Unidades: ${producto.cantidad}`}</small>
                                ${producto.costo_adicionales > 0 ? `<small class="color-theme">Extras: Bs. ${producto.costo_adicionales}</small>` : ''}
                            </div>
                            <div class="product-image-container m-0" style="z-index: 10">
                                <img class="product-image rounded"
                                    src="${producto.imagen}"
                                    alt="${producto.nombre}"
                                    data-producto-id="${producto.id}">
                                ${(producto.aceptado) ? '':
                                `<button class="btn btn-xxs bg-highlight opacity-100 borrar-pventa-btn position-absolute"
                                        type="button"
                                        disabled
                                        data-producto-venta-id="${producto.pivot_id}"
                                        title="Eliminar producto"
                                        style="top: 0.1rem; right: 0.01rem; z-index: 20;"
                                        >
                                    <i class="fa fa-times"></i>
                                </button>`}
                            </div>
                            <div class="item-overlay rounded-sm card-overlay opacity-60"></div>
                        </div>
                        <div class="d-flex flex-row justify-content-between align-items-center mt-2" style="color: none !important">
                            <div>
                                ${(producto.tiene_descuento ?
                                `<del id="pviejo-pventa-${producto.pivot_id}" class="badge bg-highlight mb-1 product-price-old">Bs. ${(producto.precio_original.toFixed(2) * producto.cantidad) + producto.costo_adicionales}</del>` : ''
                                )}
                                <p id="precio-pventa-${producto.pivot_id}" class="fw-bold mb-0 text-success fs-5 product-price" data-precio="${producto.precio}">
                                    Bs. ${producto.precio_final.toFixed(2)}
                                </p>
                            </div>
                            ${renderizarBotonAccion(producto)}
                        </div>
                    </div>
                </div>
            </li>
        `
    }

    window.reemplazarCardProductoVenta = reemplazarCardProductoVenta;
    window.construirCardProductoVenta = construirCardProductoVenta;

    const renderizarBotonAccion = (prod_venta) => {
        if (prod_venta.tipo == "simple" && prod_venta.aceptado == false) {
            // De ser un producto simple y estar pendiente la orden, renderizar el stepper
            return `
                <div class="quantity-controls bg-light border rounded d-flex align-items-center">
                    <button class="btn btn-xs btn-outline-secondary border-0 reducir-simple"
                            type="button"
                            disabled
                            data-producto-id="${prod_venta.id}"
                            data-pventa-id=${prod_venta.pivot_id}
                            title="Disminuir cantidad">
                        <i class="fa fa-minus"></i>
                    </button>
                    <span id="cantidad-pventa-${prod_venta.pivot_id}"
                        class="px-1 fw-semibold product-quantity"
                        data-producto-id="${prod_venta.id}">
                        ${prod_venta.cantidad}
                    </span>
                    <button class="btn btn-xs btn-outline-secondary border-0 incrementar-simple"
                            type="button"
                            disabled
                            data-producto-id="${prod_venta.id}"
                            data-pventa-id=${prod_venta.pivot_id}
                            title="Aumentar cantidad">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            `
        } else if (prod_venta.tipo == "complejo") {
            // De ser un producto complejo, renderizar el listado de ordenes
            return `
                <button
                    data-producto-id="${prod_venta.id}"
                    data-producto-venta-id="${prod_venta.pivot_id}"
                    data-producto-nombre="${prod_venta.nombre}"
                    class="btn btn-s bg-highlight font-500 listado-ordenes-trigger" style="z-index: 10; min-width: 6rem;">
                    Mi Pedido
                </button>
            `
        }

        return ''
    }

    const construirCarritoVentaVacio = (termino, lucideIcon) => {
        return `
            <div class="empty-cart text-center py-5">
                <i data-lucide="${lucideIcon}" class="lucide-icon mb-2" style="width: 4rem;height: 4rem;"></i>
                <h5 class="text-muted">Tu ${termino} está vacío</h5>
                <p class="text-muted">Agrega algunos productos para comenzar</p>
            </div>
        `
    }

    const handleResumenPedido = async() => {
        const repuestaProductosVentaCliente = await VentaService.productosVenta();
        const productosVentaCliente = repuestaProductosVentaCliente.data;

        if (productosVentaCliente.length > 0) {
            prepararResumenPedidoVenta(productosVentaCliente);
            const summaryModal = new bootstrap.Modal(document.getElementById('cartSummaryModal'));
            summaryModal.show();
            // renderizarProductosVenta(productosVenta);
        }
    }

    const prepararResumenPedidoVenta = (productosVentaPendientes) => {
        const $summaryItems = $('#cart-summary-items');
        const $summaryTotals = $('#cart-totals');

        $summaryItems.empty();
        const pedidos = productosVentaPendientes;

        let totalFinal = 0;
        let totalDescuento = 0;
        let totalOriginal = 0;

        if (pedidos.length) {
            pedidos.forEach(prod => {
                // // $available.append(renderCartItem(prod, 'disponible', prod.cantidad));
                $summaryItems.append(renderSummaryItem(prod)); 

                totalFinal += (prod.precio * prod.cantidad) + prod.costo_adicionales;
                totalOriginal += (prod.precio_original * prod.cantidad) + prod.costo_adicionales;
                if (prod.tiene_descuento) {
                    totalDescuento += (prod.precio_original - prod.precio) * prod.cantidad;
                }
            });
        }
        $summaryTotals.html(renderSummaryTotal(totalFinal, totalDescuento, totalOriginal));
    }

    const renderSummaryItem = (producto) => {
        console.log("Informacion recibida para renderizar item resumen: ", producto);
        const itemSubtotal = ((producto.precio * producto.cantidad) + producto.costo_adicionales).toFixed(2);
        return `
            <div class="mb-2">
                <div class="item-name fw-semibold mb-2" title="${producto.nombre}">
                    <p class="mb-0 font-600 text-dark">${producto.nombre}</p>
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <small class="text-muted d-flex align-items-center gap-1">
                        <span>${producto.cantidad}</span>
                        ×
                        <span> Bs. ${producto.precio.toFixed(2)}</span>
                        ${(producto.tiene_descuento ?
                            `<del>${producto.precio_original.toFixed(2)}</del>` : ''
                        )}
                    </small>
                    <div class="item-subtotal fw-bold text-success">
                        <p class="mb-0 fs-6">Bs. ${itemSubtotal}</p>
                    </div>
                    
                </div>
                <div class="divider mt-1 mb-0"></div>
            </div>
        `;
    }

    const renderSummaryTotal = (totalFinal, totalDescuento, totalOriginal) => {
        return `
            ${totalDescuento && totalDescuento > 0 
            ? `
                <div class="d-flex flex-row justify-content-between align-items-center mb-0 text-gray">
                    <p class="fw-bold mb-0">Costo original:</p>
                    <p class="fw-bold mb-0">Bs. ${totalOriginal.toFixed(2)}</p>
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center mb-0 text-gray">
                    <p class="fw-bold mb-0">Descuento total:</p>
                    <p class="fw-bold mb-0">Bs. ${totalDescuento.toFixed(2)}</p>
                </div>
            `
            : ""
            }
            <div class="d-flex flex-row justify-content-between align-items-center mb-3 pt-2 text-black">
                <p class="fw-bold fs-5 mb-0">Total:</p>
                <p id="cart-final-total" class="fw-bold fs-5 mb-0">Bs. ${totalFinal.toFixed(2)}</p>
            </div>
        `;
    };

</script>
@endpush
<!-- CHECK VENTA EXISTENTE -->
@if (!$venta_activa)
    @push('scripts')
    <!-- CONTROL DEL RENDERIZADO DE PRODUCTOS EN EL CARRITO -->
    <script>
        $(document).ready(async function () {
            try {
                $('.cart-validation-info').show();

                const cart = carritoStorage.obtenerCarrito();
                const response = await CarritoService.getCartProductsInfo({
                    sucursaleId: 1,
                    items: cart.items,
                });

                renderCart(response);

            } catch (error) {
                console.error("Error al obtener los productos del carrito:", error);
                showCartError();
            }
        });

        function renderCart(response) {
            const $cartContent = $('.cart-content');
            const $cartInfo = $('.cart-validation-info');
            const $available = $('#contenedor-productos-disponibles-carrito');
            const $limited = $('#container-state-escaso');
            const $unavailable = $('#container-state-agotado');
            const $summaryItems = $('#cart-summary-items');
            const $summaryTotals = $('#cart-totals');

            const emptyCartHTML = `
                <div class="empty-cart text-center py-5">
                    <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tu carrito está vacío</h5>
                    <p class="text-muted">Agrega algunos productos para comenzar</p>
                </div>
            `;

            const disponibles = Object.values(response.disponibles || {});
            const escasos = Object.values(response.escasos || {});
            const agotados = Object.values(response.agotados || {});

            // Si no hay productos válidos
            if (!disponibles.length && !escasos.length && !agotados.length) {
                renderizarCarritoVacio();
                // $cartContent.html(emptyCartHTML);
                $cartInfo.hide();
                return;
            }

            // Limpiar contenido previo
            $cartContent.find('#showSummaryButton, .cart-listing-footer, .empty-cart').remove();
            $available.empty();
            $limited.empty();
            $unavailable.empty();
            $summaryItems.empty();

            let totalFinal = 0;
            let totalDescuento = 0;
            let totalOriginal = 0;

            // 🔸 Render disponibles
            if (disponibles.length) {
                $available.append(`<h5 class="mb-3 color-highlight" id="label-disponible">Productos Disponibles</h5>`);
                disponibles.forEach(prod => {
                    $available.append(renderCartItem(prod, 'disponible', prod.cantidad));
                    $summaryItems.append(renderSummaryItem(prod));

                    totalFinal += prod.precio * prod.cantidad;
                    totalOriginal += prod.precio_original * prod.cantidad;
                    if (prod.tiene_descuento) {
                        totalDescuento += (prod.precio_original - prod.precio) * prod.cantidad;
                    }
                });
            }

            // 🔸 Render escasos
            if (escasos.length) {
                $limited.append(`<h5 class="mb-3 text-warning" id="label-escaso">Productos con stock limitado</h5>`);
                escasos.forEach(prod => {
                    $limited.append(renderCartItem(prod, 'escaso', prod.cantidad));
                    estadoValidacionCarrito.productosLimitados[prod.id] = {
                        mensaje: `Solo existen ${prod.stock_disponible} unidades disponibles de las ${prod.cantidad} solicitadas.`,
                        stockDisponible: prod.stock_disponible,
                        cantidadSolicitada: prod.cantidad,
                    };
                });
            }

            // 🔸 Render agotados
            if (agotados.length) {
                $unavailable.append(`<h5 class="mb-3 text-danger" id="label-agotado">Productos agotados</h5>`);
                agotados.forEach(prod => {
                    $unavailable.append(renderCartItem(prod, 'agotado', prod.cantidad));
                });
            }

            // 🔸 Totales + botones finales
            $summaryTotals.html(renderSummaryTotal(totalFinal, totalDescuento, totalOriginal));

            $cartContent.append(`
                <button id="showSummaryButton" class="summary-btn btn w-30 align-self-center btn-sm rounded-sm bg-highlight font-800 text-uppercase">
                    Realizar Pedido
                </button>
                <div class="cart-listing-footer d-flex flex-row">
                    <button class="delete-cart-btn ms-auto mt-2 me-2" data-menu="menu-confirm">Eliminar Carrito</button>
                </div>
            `);

            $cartInfo.hide();
        }

        function showCartError() {
            const $cartContent = $('.cart-content');
            const $cartInfo = $('.cart-validation-info');

            $cartInfo.hide();
            $cartContent.html(`
                <p class='text-danger text-center'>Error al cargar el carrito. Por favor, intenta nuevamente.</p>
                <div class="cart-listing-footer d-flex flex-row">
                    <button class="delete-cart-btn ms-auto mt-2 me-2" data-menu="menu-confirm">Eliminar Carrito</button>
                </div>
            `);
        }

        const renderProductStateContainer = (estado) => {
            return `<div id={container-state-${estado}}>
                </div>`
        }

        window.renderCartItem = (producto, estado, cantidad) => {
            const isDisabled = estado !== 'disponible';
            const isLowStock = estado === 'escaso';
            const isUnavailable = estado === 'agotado';
            const adicionalesLimitados = producto.adicionalesLimitados; // Flag true/false en caso de adicionales escasos
            const disabledClass = '';
            const disabledOverlay = isUnavailable ? '<div class="card-overlay rounded-sm dark-mode-tint"></div>' : '';
            const adicionalesIds = producto.adicionales && producto.adicionales.length > 0
                ? producto.adicionales.map(adicional => adicional.id)
                : 'null';
            const adicionalesIdsJSON = JSON.stringify(adicionalesIds);

            
            let stockMessage = '';
            let actionButton = '';
            if (isLowStock && producto.stock_disponible !== "INFINITO") {
                stockMessage = `<div class="alert alert-warning py-1 px-2 mt-2 mb-0 small">
                    Solo existen ${producto.stock_disponible} unidades disponibles de las ${producto.cantidad} solicitadas.
                </div>`;
                actionButton = `<button data-product-id="${producto.id}" class="qty-fixer btn-s  rounded bg-highlight" style="z-index: 10;">
                    Actualizar
                </button>`;
            }
            else if (isUnavailable) {
                actionButton = `<button data-product-id="${producto.id}" class="unavailable-fixer btn-s  rounded bg-red-dark" style="z-index: 10;">
                    Eliminar
                </button>`;
            }

            if(adicionalesLimitados) {
                stockMessage += `
                    <div class="alert alert-warning py-1 px-2 mt-2 mb-0 small">
                        Uno o mas de los adicionales no se encuentran disponibles.
                    </div>
                `
                actionButton = `<button data-product-id="${producto.id}" class="adicionale-fixer btn-s  rounded bg-highlight" style="z-index: 10;">
                    Actualizar
                </button>`;
            }

            return `
                <div class="cart-item-wrapper mb-4 ${disabledClass}" data-product-id="${producto.id}"  data-product-state="${estado}" id="cart-item-wrapper-${producto.id}">
                    <div class="card mb-0 d-flex flex-column item-carrito-info justify-content-between p-3 bg-white rounded-sm shadow-sm border">
                        <div class="mb-0 d-flex flex-row justify-content-between">
                            <div class="d-flex flex-column item-carrito-detalles flex-grow-1 me-3" style="z-index: 10">
                                <h5 class="fw-bold text-dark mb-2 product-name">${producto.nombre}</h5>
                                <small class="color-theme">${`Unidades: <span id="unidades-carrito-${producto.id}">${producto.cantidad}`}</span></small>
                                ${producto.costo_adicionales > 0 ? `<small class="color-theme">Extras: Bs. ${producto.costo_adicionales}</small>` : ''}
                            </div>
                            <div class="product-image-container m-0" style="z-index: 10">
                                <img class="product-image rounded"
                                    src="${producto.imagen}"
                                    alt="${producto.nombre}"
                                    data-product-id="${producto.id}">
                                ${(isUnavailable) ? '':
                                `<button class="btn btn-xxs bg-highlight opacity-100 delete-item-btn position-absolute"
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
                            <div>
                                ${(producto.tiene_descuento ?
                                `<del class="badge bg-highlight mb-1 product-price-old">Bs. <span id="pviejo-carrito-${producto.id}">${(producto.precio_original.toFixed(2) * producto.cantidad) + producto.costo_adicionales}</span></del>` : ''
                                )}
                                <p id="precio-carrito-${producto.id}" class="fw-bold mb-0 text-success fs-5 product-price" data-precio="${producto.precio}" data-precio-original="${producto.precio_original}">
                                    Bs. ${producto.precio_final.toFixed(2)}
                                </p>
                            </div>
                            ${renderActions(producto, isDisabled, cantidad, estado)}
                        </div>
                        ${stockMessage}
                        ${disabledOverlay}
                    </div>
                </div>
            `;
        }

        const reemplazarCardCarrito = (productoCarrito) => {
            const cardAntiguo = $(`#cart-item-wrapper-${productoCarrito.id}`);
            const cardNuevo = construirCardItemCarrito(productoCarrito);
            cardAntiguo.replaceWith(cardNuevo);
        }

        window.actualizarInfoCardCarrito = (info) => {
            const unidadesSolicitadasText = $(`#unidades-carrito-${info.id}`);
            const costoTotalAdicionalesText = $(`#adicionales-carrito-${info.id}`);
            const costoTotalSinDescuento = $(`#pviejo-carrito-${info.id}`);
            const costoTotalFinal = $(`#precio-carrito-${info.id}`);

            unidadesSolicitadasText.text(info.cantidad);
            costoTotalAdicionalesText.text(info.costo_adicionales);
            costoTotalSinDescuento.text((info.precio_original.toFixed(2) * info.cantidad) + info.costo_adicionales);
            costoTotalFinal.text(info.precio_final.toFixed(2));
        }


        // SINCRONIZAR APROPIADAMENTE PARA REEMPLAZAR EN renderCartItem
        const construirCardItemCarrito = (producto) => {
            return `
                <div class="cart-item-wrapper mb-4" data-product-id="${producto.id}"  data-product-state="${estado}" id="cart-item-wrapper-${producto.id}">
                    <div class="card mb-0 d-flex flex-column item-carrito-info justify-content-between p-3 bg-white rounded-sm shadow-sm border">
                        <div class="mb-0 d-flex flex-row justify-content-between">
                            <div class="d-flex flex-column item-carrito-detalles flex-grow-1 me-3" style="z-index: 10">
                                <h5 class="fw-bold text-dark mb-2 product-name">${producto.nombre}</h5>
                                <small class="color-theme">${`Unidades: <span id="unidades-carrito-${producto.id}">${producto.cantidad}`}</span></small>
                                ${producto.costo_adicionales > 0 ? `<small class="color-theme">Extras: Bs. <span id="adicionales-carrito-${producto.id}">${producto.costo_adicionales}</span></small>` : ''}
                            </div>
                            <div class="product-image-container m-0" style="z-index: 10">
                                <img class="product-image rounded"
                                    src="${producto.imagen}"
                                    alt="${producto.nombre}"
                                    data-product-id="${producto.id}">
                                <button class="btn btn-xxs bg-highlight opacity-100 delete-item-btn position-absolute"
                                        type="button"
                                        data-product-id="${producto.id}"
                                        title="Eliminar producto"
                                        style="top: 0.1rem; right: 0.01rem; z-index: 20;"
                                        >
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <div class="item-overlay rounded-sm card-overlay opacity-60"></div>
                        </div>
                        <div class="d-flex flex-row justify-content-between align-items-center mt-2" style="color: none !important">
                            <div>
                                ${(producto.tiene_descuento ?
                                `<del class="badge bg-highlight mb-1 product-price-old">Bs. <span id="pviejo-carrito-${producto.id}">${(producto.precio_original.toFixed(2) * producto.cantidad) + producto.costo_adicionales}</span></del>` : ''
                                )}
                                <p id="precio-carrito-${producto.id}" class="fw-bold mb-0 text-success fs-5 product-price" data-precio="${producto.precio}" data-precio-original="${producto.precio_original}">
                                    Bs. <span id="precio-carrito-${producto.id}">${producto.precio_final.toFixed(2)}</span>
                                </p>
                            </div>
                            ${renderActions(producto, isDisabled, cantidad, estado)}
                        </div>
                    </div>
                </div>
            `;
        }

        


        const renderActions = (producto, isDisabled, cantidad, estado) => {
            const tipo = producto.tipo;

            if (estado == "escaso") {
                return `
                    <button
                        data-product-id="${producto.id}"
                        data-producto-id="${producto.id}"
                        data-producto-nombre="${producto.nombre}"
                        class="btn btn-s bg-warning font-500 ${tipo == "simple" ? "qty-fixer" : "listado-ordenes-carrito-trigger" } "
                        style="z-index: 10; min-width: 6rem;">
                        Ajustar
                    </button>
                `
            }

            if (estado == "agotado" ) {
                return `
                    <button 
                        data-product-id="${producto.id}"
                        class="btn btn-s bg-danger font-500 delete-item-btn"
                        style="z-index: 10; min-width: 6rem;">
                        Eliminar
                    </button>
                `
            }

            if (tipo == "simple") {
                return `
                    <div class="quantity-controls bg-light border rounded d-flex align-items-center">
                        <button class="btn btn-xs btn-outline-secondary border-0 qty-decrease"
                                type="button"
                                data-product-id="${producto.id}"
                                title="Disminuir cantidad"
                                ${isDisabled ? 'disabled' : ''}>
                            <i class="fa fa-minus"></i>
                        </button>
                        <span id="item-${producto.id}-qty"
                            class="px-1 fw-semibold product-quantity"
                            data-product-id="${producto.id}">
                            ${cantidad}
                        </span>
                        <button class="btn btn-xs btn-outline-secondary border-0 qty-increase"
                                type="button"
                                data-product-id="${producto.id}"
                                title="Aumentar cantidad"
                                ${isDisabled ? 'disabled' : ''}>
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                `
            } else if (tipo == "complejo") {
                return `
                    <button
                        data-producto-id="${producto.id}"
                        data-producto-nombre="${producto.nombre}"
                        class="btn btn-s bg-highlight font-500 listado-ordenes-carrito-trigger" style="z-index: 10; min-width: 6rem;">
                        Mi Pedido
                    </button>
                `
            }

            return '';
        }

        const prepararResumenCarrito = (validacionProductos) => {
            const $summaryItems = $('#cart-summary-items');
            const $summaryTotals = $('#cart-totals');

            $summaryItems.empty();
            const disponibles = Object.values(validacionProductos.disponibles || {});

            let totalFinal = 0;
            let totalDescuento = 0;
            let totalOriginal = 0;

            if (disponibles.length) {
                disponibles.forEach(prod => {
                    // // $available.append(renderCartItem(prod, 'disponible', prod.cantidad));
                    $summaryItems.append(renderSummaryItem(prod)); 

                    totalFinal += (prod.precio * prod.cantidad) + prod.costo_adicionales;
                    totalOriginal += (prod.precio_original * prod.cantidad) + prod.costo_adicionales;
                    if (prod.tiene_descuento) {
                        totalDescuento += (prod.precio_original - prod.precio) * prod.cantidad;
                    }
                });
            }
            $summaryTotals.html(renderSummaryTotal(totalFinal, totalDescuento, totalOriginal));
        }

        document.addEventListener('DOMContentLoaded', async function() {
            
            document.addEventListener('click', async function(e) {
                // INCREMENTO DE CANTIDAD
                if (e.target.closest('.qty-increase')) {
                    await handleProductIncrease(e);
                }
                // REDUCCION DE CANTIDAD
                if (e.target.closest('.qty-decrease')) {
                    await handleProductDecrease(e);
                }
                // AJUSTAR CANTIDAD A DISPONIBLES
                if (e.target.closest('.qty-fixer')) {
                    await handleActualizarProductoLimitado(e);
                }
                // ACTIVAR MODAL RESUMEN
                if (e.target.closest('.summary-btn')) {
                    await handleSummaryCheck(e);
                }
            });
        });

        const handleSummaryCheck = async(e) => {
            e.preventDefault();

            const cartToCheck = carritoStorage.obtenerCarrito();
            const summaryCheckButton = e.target.closest('.summary-btn');
            const summaryModal = new bootstrap.Modal(document.getElementById('cartSummaryModal'));
            const warningModal = new bootstrap.Modal(document.getElementById('stock-warning'));

            const modalElement = document.getElementById('cartSummaryModal');
            if (modalElement && modalElement.parentNode !== document.body) {
                document.body.appendChild(modalElement);
            }

            try {
                // Validacion de items en carrito
                const cartCheckResponse = await CarritoService.getCartProductsInfo({
                    sucursaleId: 1,
                    items: cartToCheck.items,
                });

                const escasosArray = Object.values(cartCheckResponse.escasos || {});
                const agotadosArray = Object.values(cartCheckResponse.agotados || {});
                if (escasosArray.length == 0 && agotadosArray.length == 0) {
                    // Continuar con el resumen
                    prepararResumenCarrito(cartCheckResponse);
                    summaryModal.show();
                    return;
                } else {
                    // Renderizar modal de curar carrito
                    renderCart(cartCheckResponse);
                    warningModal.show()
                }
            } catch (error) {
                console.error("Error validanto carrito para resumen: ",error);
            }
        }

        const handleActualizarProductoLimitado = async (e) => {
            e.preventDefault();

            const botonFixCantidad = e.target.closest('.qty-fixer');
            const idProductoArreglar = parseInt(botonFixCantidad.getAttribute('data-product-id'), 10);
            const itemCarrito = carritoStorage.obtenerItemCarrito(idProductoArreglar);
            const productInfoRequest =  await CarritoService.obtenerInfoItemCarrito(itemCarrito, 1);

            const productInfo = productInfoRequest.item;
            const contenedorItemsDisponibles = document.getElementById('contenedor-productos-disponibles-carrito')

            // Actualizar el valor al maximo disponible en el carrito
            const intentoActualizacion = await carritoStorage.updateProductToMax(idProductoArreglar);
            if (intentoActualizacion.success == true && intentoActualizacion.cantidad >= 1) {
                eliminarCardProductoCarrito(idProductoArreglar);
                contenedorItemsDisponibles.innerHTML += renderCartItem(productInfo,'disponible', intentoActualizacion.cantidad);
                // notificacion de ajuste exitoso
            } else if (intentoActualizacion.success == true && intentoActualizacion.cantidad == 0) {
                eliminarCardProductoCarrito(idProductoArreglar);  
                renderCartItem(productInfo,'agotado', 0)
            }
        }

        const handleUpdateLimitedAdittional = async(e) => {
            e.preventDefault();

            const aditionalFixButton = e.target.closest('.aditionale-fixer');
            const productToFixId = quantityFixButton.getAttribute('data-product-id');
        }

        const handleProductIncrease = async (e) => {
            e.preventDefault();
            const button = e.target.closest('.qty-increase');
            const productToIncreaseId = parseInt(button.getAttribute('data-product-id'), 10);
            // const adicionalesData = button.getAttribute('data-adicionales');
            // let adicionales = adicionalesData === 'null' ? null : JSON.parse(adicionalesData);
            const quantitySpan = document.getElementById(`item-${productToIncreaseId}-qty`);
            const unidadesCard = $(`#unidades-carrito-${productToIncreaseId}`);
            const textoPrecio = $(`#precio-carrito-${productToIncreaseId}`);
            const precioUnitario = textoPrecio.data('precio');
            const precioUnitarioOriginal = textoPrecio.data('precio-original');
            const costoTotalSinDescuento = $(`#pviejo-carrito-${productToIncreaseId}`);

            const IncreaseAttemp = await carritoStorage.agregarAlCarrito(productToIncreaseId, 1, true);
            
            if (IncreaseAttemp.success === true) {
                nuevaCantidad = IncreaseAttemp.newQuantity;
                quantitySpan.textContent = nuevaCantidad;
                unidadesCard.text(nuevaCantidad);
                textoPrecio.text(`Bs. ${(precioUnitario * nuevaCantidad).toFixed(2)}`)
                costoTotalSinDescuento.text((precioUnitarioOriginal * nuevaCantidad).toFixed(2));
            } else {
                console.error("No se pudo incrementar el valor del producto: ", productToIncreaseId);
            }
        }

        const handleProductDecrease = async(e) => {
            e.preventDefault();
            const button = e.target.closest('.qty-decrease');
            const productToDecreaseId = parseInt(button.getAttribute('data-product-id'), 10);
            // const adicionalesData = button.getAttribute('data-adicionales');
            // let adicionales = adicionalesData === 'null' ? null : JSON.parse(adicionalesData);
            const quantitySpan = document.getElementById(`item-${productToDecreaseId}-qty`);
            const unidadesCard = $(`#unidades-carrito-${productToDecreaseId}`);
            const textoPrecio = $(`#precio-carrito-${productToDecreaseId}`);
            const precioUnitario = textoPrecio.data('precio');
            const precioUnitarioOriginal = textoPrecio.data('precio-original');
            const costoTotalSinDescuento = $(`#pviejo-carrito-${productToDecreaseId}`);

            // Check if quantity is 1 or less before proceeding
            if (parseInt(quantitySpan.textContent, 10) <= 1) return;

            const DecreaseAttemp = await carritoStorage.restarDelCarrito(productToDecreaseId, 1);

            if (DecreaseAttemp.success === true) {
                nuevaCantidad = DecreaseAttemp.newQuantity;
                quantitySpan.textContent = nuevaCantidad;
                unidadesCard.text(nuevaCantidad);
                textoPrecio.text(`Bs. ${(precioUnitario * nuevaCantidad).toFixed(2)}`);
                costoTotalSinDescuento.text((precioUnitarioOriginal * nuevaCantidad).toFixed(2));
            } else {
                console.error("No se pudo reducir el valor del producto: ", productToDecreaseId);
            }
        }
    </script>
    <!-- CONTROL DE ELIMINACION DE PRODUCTOS-CARRITO -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // handleCartSummaryModal();
            handleCartDeletion();
            document.addEventListener('click', handleProductRemoval);
        });

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
                carritoStorage.vaciarCarrito();
                // Renderizado del Carrito Vacio
                renderizarCarritoVacio();
                // Actualizar el contador
                carritoStorage.actualizarContadorCarrito();
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
            const productId = parseInt(button.getAttribute('data-product-id'), 10);

            // Remover el producto del carrito
            carritoStorage.eliminarProducto(productId);

            // Remover el elemento renderizado
            eliminarCardProductoCarrito(productId);

            // Revisar si el carrito esta vacio
            const remainingItems = document.querySelectorAll('.cart-item-wrapper');
            if (remainingItems.length === 0) {
                // De estar vacio, renderizar el carrito vacio
                renderizarCarritoVacio();
            }

            // Actualizar el contador del carrito
            carritoStorage.actualizarContadorCarrito();
        }

        window.eliminarCardProductoCarrito = (productId) => {
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

        const renderizarCarritoVacio = () => {
            const contenedor = $('#contenedor-principal');
            const termino = "carrito";
            const lucideIcon = "shopping-cart";
            contenedor.html(construirCarritoVentaVacio(termino, lucideIcon));
            reinitializeLucideIcons();
        };
    </script>
    @endpush('scripts')
@endif