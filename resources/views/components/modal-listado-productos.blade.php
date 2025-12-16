<div class="modal fade" id="listadoProductosModal" tabindex="-1" aria-labelledby="listadoProductosModalLabel" style="z-index: 1051">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 20px;">
            <!-- Header que respeta el tema (oscuro / claro) del master -->
            <div class="position-relative">
                <!-- Botón cerrar -->
                <button type="button" class="btn-close btn-close-white position-absolute" 
                    data-bs-dismiss="modal" aria-label="Close"
                    style="top: 15px; right: 15px; opacity: 0.7; z-index: 10;"></button>
                
                <!-- Encabezado del modal -->
                <div class="px-4 pt-4 pb-3">
                    <p class="mb-1 font-600 opacity-50 font-12">
                        <span id="cantidad-disponibles-listado">0</span> producto(s) encontrados:
                    </p>
                    <h4 id="titulo-listado-productos" class="color-theme font-22 font-800 mb-0 text-uppercase" style="letter-spacing: 1px;">
                        Categoría
                    </h4>
                </div>
                
                <!-- Contenedor de productos -->
                <div id="contenedor-listado-productos" class="px-4 pb-4" style="max-height: 60vh; overflow-y: auto;">
                    <div id="listado-productos"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
    /* Estilos para el modal de listado de productos - Diseño menú elegante */
    #listadoProductosModal .modal-content {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    #listadoProductosModal .producto-item:hover {
        background: rgba(0, 0, 0, 0.04) !important;
    }
    
    #listadoProductosModal .producto-item:last-of-type + .divider-producto {
        display: none;
    }
    
    /* Botones de acción: solo comportamiento genérico, el look viene de las clases del master (icon, bg-phone, etc.) */
    .btn-accion-circular {
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.15s ease;
    }

    .btn-accion-circular:hover {
        transform: translateY(-1px);
    }

    .btn-accion-circular.btn-accion-disabled {
        cursor: not-allowed;
        opacity: .55;
    }
    
    /* Scrollbar personalizado para el contenedor */
    #contenedor-listado-productos::-webkit-scrollbar {
        width: 6px;
    }
    
    #contenedor-listado-productos::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 3px;
    }
    
    #contenedor-listado-productos::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
    
    #contenedor-listado-productos::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    // Función global para navegar al producto
    window.navegarAProducto = function(event, url) {
        // Si el click fue en un botón o dentro de un botón, no navegar
        if (event.target.closest('.btn-accion-circular')) {
            return;
        }
        window.location.href = url;
    };

    document.addEventListener('DOMContentLoaded', async function() {
        // Abrir el modal con el listado de productos
        window.abrirDialogListado = async function(listado, titulo) {
            await prepararListado(listado, titulo);
            // Show the Bootstrap modal
            const modal = new bootstrap.Modal(document.getElementById('listadoProductosModal'));
            modal.show();
        };

        window.actualizarBotonAgregado = async (productoID) => {
            console.log("llamado a actualizarBotonAgregado");
            const botonActual = $(`[data-producto-id="${productoID}"`);
            console.log("Boton a reemplazar: ", botonActual);
            if (botonActual.length) {
                const nuevoBoton = $(renderizarBotonAgotado());
                botonActual.replaceWith(nuevoBoton);
                console.log("El boton deberia haber sido reemplazado")
            }
        }

        const prepararListado = (listado, titulo) => {
            console.log("Titulo a usarse: ", titulo);
            const elementoTitulo = document.getElementById('titulo-listado-productos');
            const listaPrincipal = document.getElementById(`listado-productos`);
            const cantidadElementos = document.getElementById('cantidad-disponibles-listado');
            console.log("Elm titulo: ", elementoTitulo);
            elementoTitulo.textContent = titulo;
            cantidadElementos.textContent = listado.length;
            console.log("El titulo deberia haber cambiado");
            listaPrincipal.innerHTML = renderizarListadoProductos(listado);
            reinitializeLucideIcons();
        }

        const renderizarListadoProductos = (listado) => {
            return `
                ${listado.map(producto => `
                    <div class="producto-item d-flex align-items-center mb-1 position-relative" 
                         style="cursor: pointer; padding: 10px; border-radius: 12px; transition: background 0.2s ease;"
                         onclick="navegarAProducto(event, '${producto.url_detalle}')">
                        
                        <!-- Imagen circular del producto -->
                        <div class="flex-shrink-0 me-3">
                            <img src="${producto.imagen}" 
                                 alt="${producto.nombre}"
                                 class="rounded-circle shadow-sm"
                                 style="width: 55px; height: 55px; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                        </div>
                        
                        <!-- Información del producto -->
                        <div class="flex-grow-1" style="min-width: 0;">
                            <h3 class="color-theme mb-0 font-16 font-700" style="line-height: 1.2;">${producto.nombre}</h3>
                            <p class="opacity-50 line-height-s font-11 mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                ${producto.detalle || ''}
                            </p>
                            <div class="d-flex flex-wrap gap-1">
                                ${renderizarFilaTags(producto)}
                            </div>
                        </div>
                        
                        <!-- Precio y acciones -->
                        <div class="d-flex flex-column align-items-end ms-2 flex-shrink-0">
                            ${renderizarPrecio(producto)}
                            <div class="d-flex mt-1">
                                <button ruta="${producto.url_detalle}" 
                                        class="btn-accion-circular icon icon-xs rounded-circle shadow-l me-1 bg-blue-dark copiarLink"
                                        title="Copiar link">
                                    <i class="fa fa-link"></i>
                                </button>
                                ${renderizarAcciones(producto)}
                            </div>
                        </div>
                    </div>
                    <div class="divider-producto" style="height: 1px; background: rgba(255,255,255,0.1); margin: 0 10px;"></div>
                `).join('')}
            `
        }

        // // VERSION APPKIT RENDERIZADO DE LISTADOS MINI
        // const renderizarListadoProductos = (listado) => {
        //     return `
        //         ${listado.map(producto => `
        //             <li style="list-style-type: none">
        //                 <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-4">
        //                     <div class="">
        //                         <img src="${producto.imagen}" class="rounded-m" style="width: 5rem;height:5rem;object-fit:cover;">
        //                     </div>
        //                     <div class="d-flex flex-column align-self-stretch justify-content-evenly" style="min-width: 0; flex: 1;">
        //                         <h2 class="font-15 line-height-s">${producto.nombre}</h2>
        //                         <div class="tags-container d-flex flex-row align-items-center justify-content-start gap-1" style="flex-wrap: wrap;">
        //                             ${renderizarFilaTagsMini(producto)}
        //                         </div>
        //                     </div>
        //                     <div class="d-flex flex-column gap-2 text-center text-nowrap w-100" style="max-width: 3.6rem;">
        //                         <h2 class="font-18 mb-0 line-height-xs">Bs. ${producto.precio}</h2>
        //                         ${renderizarAccionesMini(producto)}
        //                     </div>
        //                 </div>
        //             </li>
        //         `).join('')}
        //     `
        // }

        // 57.45

        const renderizarFilaTags = (productoTag) => {
            if (productoTag.tags && productoTag.tags.length > 0) {
                return productoTag.tags.map(tag => `
                    <span class="badge font-9 px-2 py-1" style="background: rgba(0,0,0,0.06); border-radius: 20px;">${tag.nombre}</span>
                `).join('');
            }
            return '';
        }

        const renderizarFilaTagsMini = (productoTag) => {
            if (productoTag.tags && productoTag.tags.length > 0) {
                return productoTag.tags.map(tag => `
                    <span class="badge badge-xs gradient-blue color-white">${tag.nombre}</span>
                `).join('');
            }
            return '';
        }

        const renderizarAcciones = (producto) => {
            if (!producto.tiene_stock) {
                return renderizarBotonAgotado();
            }
            
            return `
                <button
                    class="${ producto.tiene_adicionales ? "menu-adicionales-btn":"agregar-unidad"} add-disabler btn-accion-circular icon icon-xs rounded-circle shadow-l bg-green-dark btn-accion-add"
                    data-producto-id="${producto.id}"
                    data-producto-nombre="${producto.nombre}"
                    title="Añadir al carrito"
                >
                    <i class="fa fa-cart-plus"></i>
                </button>
            `;
        }

        const renderizarAccionesMini = (producto) => {
            if (!producto.tiene_stock) {
                return `
                    <button class="w-100 rounded-s shadow-l bg-gray-dark font-10" disabled>
                        <i class="fa fa-ban line-height-xs"></i>
                    </button>
                `; 
            }
            return `
                <button
                    class="${ producto.tiene_adicionales ? "menu-adicionales-btn":"agregar-unidad"} add-disabler w-100 rounded-circle shadow-l bg-green-dark font-10"
                    data-producto-id="${producto.id}"
                    data-producto-nombre="${producto.nombre}"
                >
                    <i class="fa fa-cart-plus line-height-xs"></i>
                </button>
            `;

            // // Botón copiar link mini
            // <button class="w-100 rounded-s shadow-l bg-delight-red font-10 copiarLink">
            //     <i class="fa fa-link line-height-xs"></i>
            // </button>
        }

        const renderizarBotonAgotado = () => {
            return `
                <button class="btn-accion-circular icon icon-xs rounded-circle shadow-l bg-gray-dark btn-accion-disabled" disabled title="Agotado">
                    <i class="fa fa-ban"></i>
                </button>
            `;
        }

        const renderizarPrecio = (productoPrecio) => {
            const tieneDescuento = productoPrecio.precio_original;

            if (tieneDescuento) {
                return `
                    <div class="text-end">
                        <p class="font-11 mb-0 opacity-50"><del>Bs. ${productoPrecio.precio_original}</del></p>
                        <h4 class="color-theme font-700 mb-0 font-16">Bs. ${productoPrecio.precio}</h4>
                    </div>
                `;
            }
            
            return `<h4 class="color-theme font-700 mb-0 font-16">Bs. ${productoPrecio.precio}</h4>`;
        }
    });
</script>
@endpush