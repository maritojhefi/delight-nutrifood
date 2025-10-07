<div class="modal fade" id="listadoProductosModal" tabindex="-1" aria-labelledby="listadoProductosModalLabel" style="z-index: 1051">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
        <div class="modal-content">
            <div class="modal-header mt-2 border-0 gap-4 d-flex align-items-center">
                <h4 id="titulo-listado-productos" class="mb-0 align-self-center text-uppercase">Todos los productos de esta categoria!</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="contenedor-listado-productos">
                <ul id="listado-productos" class="px-3 pt-3">
                </ul>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
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
            console.log("Elm titulo: ", elementoTitulo);
            elementoTitulo.textContent = titulo;
            console.log("El titulo deberia haber cambiado");
            listaPrincipal.innerHTML = renderizarListadoProductos(listado);
            reinitializeLucideIcons();
        }

        const renderizarListadoProductos = (listado) => {
            return `
                ${listado.map(producto => `
                    <li style="list-style-type: none">
                        <div data-card-height="155" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
                            <div class="d-flex flex-row gap-2"> 
                                <a href="${producto.url_detalle}" class="product-card-image">
                                    <img src="${producto.imagen}"
                                    style="background-color: white;min-width: 130px">
                                </a>
                                <div class="d-flex flex-column w-100 flex-grow-1 justify-content-between py-3 me-2">
                                    <h4 class="me-1 font-18" style="max-height: 3rem;overflow: hidden">${producto.nombre}</h4>
                                    ${renderizarFilaTags(producto)}
                                    <div class="d-flex flex-row align-items-center justify-content-between">
                                        ${renderizarPrecio(producto)}
                                        <div class="d-flex flex-row gap-1">
                                            <button ruta="${producto.url_detalle}" class="btn p-1 copiarLink rounded-s bg-red-light font-900">
                                                <i class="fa fa-link"></i>
                                            </button>
                                            ${renderizarAcciones(producto)}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                `).join('')}
            `
        }

        const renderizarFilaTags = (productoTag) => {
            if (productoTag.tags && productoTag.tags.length > 0) {
                return `
                    <div class="tags-container d-flex flex-row align-items-center justify-content-start gap-2">
                    ${productoTag.tags.map(tag => `
                        <button popovertarget="poppytag-${productoTag.id}-${tag.id}" popoveraction="toggle" style="anchor-name: --tag-btn-${productoTag.id}-${tag.id};">
                            <i data-lucide="${tag.icono}" class="lucide-icon" style="width:1.5rem;height:1.5rem;"></i>
                        </button>
                        <div popover
                            id="poppytag-${productoTag.id}-${tag.id}"
                            class="tag-info-popover bg-white bg-dtheme-blue p-2 rounded-2 shadow-lg border"
                            style="position-anchor: --tag-btn-${productoTag.id}-${tag.id}; max-width:250px;">
                            <p class="color-theme">${tag.nombre}</p>
                        </div>
                    `).join('')}
                    </div>
                `;
            }
            return '';
        }

        const renderizarAcciones = (producto) => {
            if (!producto.tiene_stock) {
                return renderizarBotonAgotado();
                // return `
                //     <button class="btn btn-xs rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
                //         <div class="d-flex flex-row align-items-center gap-1">
                //             <i class="fa fa-ban"></i>
                //             <span class="font-10">Sin Stock</span>
                //         </div>
                //     </button>
                // `;
            }
            
            return `
                <button
                    class="${ producto.tiene_adicionales ? "menu-adicionales-btn":"agregar-unidad"} add-disabler btn rounded-s shadow-l bg-highlight font-900 text-uppercase font-10"
                    data-producto-id="${producto.id}"
                    data-producto-nombre="${producto.nombre}"
                >
                    <div class="d-flex flex-row align-items-center gap-1">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="font-10">Añadir</span>
                    </div>
                </button>
            `;
        }

        const renderizarBotonAgotado = () => {
            return `
                    <button class="btn btn-xs rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase font-10" disabled>
                        <div class="d-flex flex-row align-items-center gap-1 font-10">
                            <i class="fa fa-ban"></i>
                            <span class="font-10">Agotado</span>
                        </div>
                    </button>
                `;
        }

        const renderizarPrecio = (productoPrecio) => {
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
    });
</script>
@endpush