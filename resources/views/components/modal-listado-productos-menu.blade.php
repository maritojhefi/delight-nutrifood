<div id="{{$identificador}}" class="menu menu-box-modal rounded-m bg-dtheme-blue" style="width: 95%; max-height: 90%; z-index: 1053">
    <!-- Menu Modal Header -->
    <div class="menu-title d-flex flex-row justify-content-between align-items-center px-3 pt-3">
        <h2 id="titulo-listado-productos" class="front-22" style="z-index: 10">Titulo del listado</h2>
        <button href="#" class="close-menu btn-close"></button>
    </div>
    
    <div class="divider mb-0"></div>
    
    <!-- Menu Modal Body -->
    <div id="contenedor-listado-productos">
        <ul id="listado-{{$identificador}}" class="px-3 pt-3">
        </ul>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const identificador = "{{$identificador}}";
        const elementoMenu = document.getElementById(identificador);
        // Abrir el menu recibiendo el listado de productos
        window.abrirDialogListado = async function(listado, titulo) {
            await prepararListado(listado, titulo);

            // Cerrar otros menu abiertos
            $(".menu-active").removeClass("menu-active");

            // Revelar el backdrop
            $(".menu-hider").addClass("menu-active");

            // Revelar el menu
            $(`#${identificador}`).addClass("menu-active");
        };

        const prepararListado = (listado, titulo) => {
            const elementoTitulo = document.getElementById(`titulo-listado-productos`);
            const principalLista = document.getElementById(`listado-${identificador}`);
            
            elementoTitulo.textContent = titulo;
            principalLista.innerHTML = renderizarListadoProductos(listado);
            reinitializeLucideIcons();
        }

        const renderizarListadoProductos = (listado) => {
            return `
                ${listado.map(producto => `
                    <li style="list-style-type: none">
                        <div data-card-height="140" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
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

        const renderizarAcciones = (item) => {
            if (!item.tiene_stock) {
                return `
                    <button class="btn btn-xs rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
                        <div class="d-flex flex-row align-items-center gap-1">
                            <i class="fa fa-ban"></i>
                            <span class="font-10">Sin Stock</span>
                        </div>
                    </button>
                `;
            }
            
            return `
                <button
                    class="${ item.tiene_adicionales ? "menu-adicionales-btn":"agregar-unidad"} btn rounded-s shadow-l bg-highlight font-900 text-uppercase"
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