<button id="boton-carrito-pendiente" href="#" class="page-title-icon shadow-xl bg-theme d-none align-items-center justify-content-center position-relative">
    <i class="fa fa-shopping-cart"></i>
    <span id="cantidad-carrito-pendiente" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        3
        <span class="visually-hidden">elementos en carrito</span>
    </span>
</button>

@push('modals')
<div class="modal fade" id="carritoPendienteModal" tabindex="-1" aria-labelledby="carritoPendienteModalLabel">
    <div class="modal-dialog modal-dialog-centered align-items-center justify-content-center">
        <div class="modal-content card rounded-sm modal-body rounded-sm d-flex flex-column gap-1 justify-content-center align-items-center" style="z-index: 10; max-width: 84vw;">
            <h2>Tienes un carrito pendiente</h2>
            <p class="mb-0 px-2">¿Te gustaria incluir los productos en tu carrito con tu pedido actual?</p>
            <div class="card card-style rounded-sm p-2 w-100 m-0" >
                <!-- Contenedor resumen items individuales-->
                <ul id="listado-carrito-pendiente" class="list-unstyled mb-0 gap-2 d-flex flex-column">

                </ul>
                <div class="d-flex align-items-center justify-content-between gap-2 mt-3 mb-1" >
                    <button id="eliminar-carrito-pendiente-btn" class="btn bg-red-dark">Eliminar</button>
                    <button id="incluir-carrito-pendiente-btn" class="btn bg-highlight line-height-s">
                        <div class="d-flex flex-row justify-content-between align-items-center gap-1">
                            Incluir al pedido<i class="lucide-icon" data-lucide="shopping-cart"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const tieneVentaActiva = @json($tiene_venta_activa); 

    $(document).ready( async function() {
        console.log("Hay venta activa?: ", tieneVentaActiva);
        if (tieneVentaActiva) {
            renderizarBotonCarritoPendiente();
            $('#boton-carrito-pendiente').off('click').on('click', async function () {
                await mostrarCarritoPendiente();
            })
        }
    });

    const renderizarBotonCarritoPendiente = () => {
        console.log("Renderizando carrito pendiente")
        const carritoPendiente = carritoStorage.obtenerCarrito();
        const contadorPendientes = $('#cantidad-carrito-pendiente');
        const botonCarritoPendiente = $('#boton-carrito-pendiente');

        if (carritoPendiente.items.length) {
            contadorPendientes.text("!");
            botonCarritoPendiente.removeClass('d-none');
            botonCarritoPendiente.addClass('d-flex');
            $('#eliminar-carrito-pendiente-btn').off('click').on('click', function() {
                carritoStorage.vaciarCarrito();
                terminarModalCarritoPendiente();
            });
            $('#incluir-carrito-pendiente-btn').off('click').on('click', async function() {
                await sincronizarCarrito();
            });
        } else {
            contadorPendientes.text(0);
            botonCarritoPendiente.removeClass('d-flex');
            botonCarritoPendiente.addClass('d-none');
        }
    }

    const mostrarCarritoPendiente = async () => {
        console.log("Validando informacion del carrito pendiente");
        const modalPendientes = new bootstrap.Modal(document.getElementById('carritoPendienteModal'));
        const carritoPendiente = carritoStorage.obtenerCarrito();
        console.log("carritoPendiente: ", carritoPendiente.items);
        
        try {
            const validacionCarrito = await CarritoService.getCartProductsInfo({
                sucursaleId: 1,
                items: carritoPendiente.items,
            });
            
            const pendientesSincronizables = [
                ...validacionCarrito.disponibles.map(item => ({
                    ...item,
                    cantidadSincronizable: item.cantidad // All servings are syncable
                })),
                ...validacionCarrito.escasos.map(item => ({
                    ...item,
                    cantidadSincronizable: calcularCantidadSincronizable(item)
                }))
            ].filter(item => item.cantidadSincronizable > 0);
            
            prepararListadoCarritoPendiente(pendientesSincronizables);
            
        } catch (error) {
            console.log("Error al validar carrito pendiente: ", error);
            mostrarToastError("Error al validar carrito pendiente.");
            return;
        }
        
        // Revelar el modal con informacion del carrito pendiente
        console.log("Revelando modal carrito pendiente");
        modalPendientes.show();
    }

    const calcularCantidadSincronizable = (item) => {
        let cantidadSincronizable = 0;
        
        if (item.adicionales && Object.keys(item.adicionales).length > 0) {
            Object.values(item.adicionales).forEach(adicionalesGroup => {
                const tieneAdicionalLimitado = adicionalesGroup.some(adicional => adicional.limitado === true);
                
                if (!tieneAdicionalLimitado) {
                    cantidadSincronizable++;
                }
            });
        } else {
            cantidadSincronizable = item.cantidad;
        }
        
        if (item.stock_disponible !== "INFINITO" && 
            typeof item.stock_disponible === 'number' && 
            item.stock_disponible < 9223372036854775807) { // Check if not PHP_INT_MAX
            
            cantidadSincronizable = Math.min(cantidadSincronizable, item.stock_disponible);
        }
        
        return cantidadSincronizable;
    }

    const terminarModalCarritoPendiente = () => {
        const modalElement = document.getElementById('carritoPendienteModal');
        
        const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        
        $('#boton-carrito-pendiente').remove();

        modalElement.addEventListener('hidden.bs.modal', function handler() {
            modalElement.remove();
            modalElement.removeEventListener('hidden.bs.modal', handler);
        });
        modalInstance.hide();
    };

    const sincronizarCarritoPendiente = () => {

    }

    const prepararListadoCarritoPendiente = (listadoPendientes) => {
        console.log("Preparando el modal carrito pendiente");
        const listadoCarritoPendiente = $('#listado-carrito-pendiente');
        
        // Limpiar listado
        listadoCarritoPendiente.html('');
        
        // Solo se listaran los elementos sincronizables (disponibles + escasos viables)
        if (listadoPendientes.length) {
            listadoCarritoPendiente.html(construirListadoPendiente(listadoPendientes));
        } else {
            listadoCarritoPendiente.html('<li><p class="text-muted mb-0">No hay productos disponibles para sincronizar</p></li>');
        }
    }

    const construirListadoPendiente = (listadoPendientes) => {
        return `
            ${listadoPendientes.map(pendiente => `
                <li>
                    <div id="pendiente-carrito-${pendiente.id}" class="d-flex flex-row align-items-center justify-content-between gap-2">
                        <p class="mb-0">${pendiente.nombre}</p>
                        <p class="mb-0">x${pendiente.cantidadSincronizable}</p>
                    </div>
                </li>
            `).join('')}
        `;
    }

    const sincronizarCarrito = async () => {
        estaSincronizando(true);
        let success = false; // Flag to track success
        try {
            const carrito = carritoStorage.obtenerCarrito();
            console.log("Carrito:", carrito);
            // Sincronizacion de base de datos con elementos actuales en el carrito
            const respuestaSincronizacion = await VentaService.generarProductosVenta_Carrito(carrito)
            console.log("Sincronización de productos exitosa:", respuestaSincronizacion);
            // Eliminar elmentos existentes en el carrito para evitar nuevos registros indeseados 
            // y abusos en generacion de pedidos
            carritoStorage.vaciarCarrito();
            success = true; 
        } catch (error) {
            console.log(error);
            mostrarToastError("Error al sincronizar el carrito pendiente");   
        } finally {
            estaSincronizando(false);
            if (success) {
                window.location.reload(); 
            }
        }
    }

    const estaSincronizando = (booleano) => {
        const botonSincronizacion = $('#incluir-carrito-pendiente-btn');
        if (booleano) {
            botonSincronizacion.html(
                `
                    <div class="d-flex flex-row justify-content-between align-items-center gap-1">
                        Procesando  <i class="lucide-icon" data-lucide="loader-circle"></i>
                    </div>
                `
            );
        } else {
            botonSincronizacion.html(
                `
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        Incluir al pedido<i class="lucide-icon" data-lucide="shopping-cart"></i>
                    </div>
                `
            );
        }
        reinitializeLucideIcons();
    }
</script>
@endpush
