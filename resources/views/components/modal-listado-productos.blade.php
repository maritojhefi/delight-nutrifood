<div class="modal fade" id="listadoProductosModal" tabindex="-1" aria-labelledby="listadoProductosModalLabel" style="z-index: 1051">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
        <div class="modal-content">
            <div class="modal-header mt-2 border-0 gap-4 d-flex justify-content-between align-items-center">
                <div></div>
                <h4 id="titulo-listado-productos" class="mb-0 align-self-center font-900 text-uppercase">Todos los productos de esta categoria!</h4>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="d-flex flex-row align-content-center justify-content-center">
                <p class="badge badge-lg font-14 bg-highlight mb-0 d-inline-block">
                    <span id="cantidad-disponibles-listado">N</span> Productos disponibles
                </p>
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
                    <li style="list-style-type: none">
                        <div data-card-height="155" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
                            <div class="d-flex flex-row gap-2"> 
                                <a href="${producto.url_detalle}" class="product-card-image">
                                    <img src="${producto.imagen}"
                                    style="background-color: white;min-width: 130px">
                                </a>
                                <div class="d-flex flex-column w-100 justify-content-evenly flex-grow-1 me-2" style="min-width: 0;">
                                    <h4 class="me-1 font-20 mb-0" style="overflow: hidden">${producto.nombre}</h4>
                                    <div class="tags-container d-flex flex-row align-items-center justify-content-start gap-1 my-n2" style="flex-wrap: wrap;">
                                        ${renderizarFilaTags(producto)}
                                    </div>
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
                    <span class="badge badge-xs gradient-blue color-white">${tag.nombre}</span>
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
                    class="${ producto.tiene_adicionales ? "menu-adicionales-btn":"agregar-unidad"} add-disabler w-100 rounded-s shadow-l bg-highlight font-10"
                    data-producto-id="${producto.id}"
                    data-producto-nombre="${producto.nombre}"
                >
                    <i class="fa fa-shopping-cart line-height-xs"></i>
                </button>
            `;

            // // Botón copiar link mini
            // <button class="w-100 rounded-s shadow-l bg-delight-red font-10 copiarLink">
            //     <i class="fa fa-link line-height-xs"></i>
            // </button>
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
                        <p class="font-13 m-0"><del>Bs. ${productoPrecio.precio_original}</del></p>
                        <p class="font-23 font-700 color-theme mb-0">Bs. ${productoPrecio.precio}</p>
                    </div>
                `;
            }
            
            return `<p class="font-23 font-700 color-theme mb-0">Bs. ${productoPrecio.precio}</p>`;
        }
    });
</script>
@endpush