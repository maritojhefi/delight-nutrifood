// import ProductoService from '../productos/producto-service.js';

// document.addEventListener('DOMContentLoaded', function () {
//     productsModal = new bootstrap.Modal(document.getElementById('categorizedProductsModal'), {
//         focus: true
//     });

//     const modalElement = document.getElementById('categorizedProductsModal');

//     modalElement.addEventListener('show.bs.modal', async function (event) {
//         const triggerElement = event.relatedTarget; // Elemento que activo el modal
//         const categoriaId = triggerElement.getAttribute('data-category-id');
//         const categoryName = triggerElement.getAttribute('data-category-name');
//         const categoryTitle = document.getElementById('categorizer-title');

//         if (!categoriaId) {
//             console.error('No category ID found in the trigger element');
//             return;
//         }

//         showLoadingState();

//         if (categoryName) {
//             categoryTitle.textContent = `${categoryName}`;
//         }

//         try {
//             const categorizedProducts = await ProductoService.getProductosCategoria(categoriaId);
//             // console.log("Productos categorizados: ", categorizedProducts);
//             renderProductItems(categorizedProducts);
//             reinitializeLucideIcons();
//         } catch (error) {
//                 console.error(`Error al obtener productos para la categoria con ID ${categoriaId}`, error);
//                 showErrorState();
//             }
//         });
// });

// const renderProductItems = (categorizedProducts) => {
//     const container = document.getElementById("listado-productos-categoria");
//     const isDisabled = false;
//     const cantidadInicial = 0;
//     container.innerHTML = '';

//     const renderProductCard = (item, formattedName) => {
//         container.innerHTML += `
//             <div class="col-12">
//                 <div data-card-height="130" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
//                     <div class="d-flex flex-row align-items-center gap-3"> 
//                         <a href="${item.url_detalle}" class="product-card-image">
//                             <img src="${item.imagen}" 
//                                 onerror="this.src='/imagenes/delight/default-bg-1.png';" 
//                                 style="background-color: white;" />
//                         </a>
//                         <div class="d-flex flex-column w-100 gap-2 me-2" style="max-width: 260px">
//                             <h4 class="me-1">${formattedName.length > 50 ? formattedName.substring(0, 50) + '...' : formattedName}</h4>
//                             ${renderTagsRow(item)}
//                             <div class="d-flex flex-row align-items-center justify-content-between gap-4">
//                                 ${renderPriceSection(item)}
//                                 <div class="d-flex flex-row gap-2">
//                                     <button ruta="${item.url_detalle}" class="btn btn-xs copiarLink rounded-s btn-full shadow-l bg-red-light font-900">
//                                         <i class="fa fa-link"></i>
//                                     </button>
//                                     ${renderActionButton(item)}
//                                 </div>
//                             </div>
//                         </div>
//                     </div>
//                 </div>
//             </div>
//         `;
//     }

//     const renderActionButton = (item) => {
//         if (!item.tiene_stock) {
//             return `
//                 <button class="btn btn-xs rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
//                     <i class="fa fa-ban"></i>
//                     Sin Stock
//                 </button>
//             `;
//         }
        
//         return `
//             <button
//                 class="add-to-cart btn btn-xs rounded-s btn-full shadow-l bg-highlight font-900 text-uppercase"
//                 data-producto-id="${item.id}"
//                 data-producto-nombre="${item.nombre}"
//             >
//                 <i class="fa fa-shopping-cart"></i>
//                 Añadir
//             </button>
//         `;
//     }

//     const renderPriceSection = (item) => {
//         const hasDiscount = item.descuento && (item.descuento > 0 && item.descuento < item.precio);
        
//         if (hasDiscount) {
//             return `
//                 <div class="d-flex flex-column">
//                     <p class="font-10 mb-0 mt-n2"><del>Bs. ${item.precio}</del></p>
//                     <p class="font-21 mt-n2 font-weight-bolder color-highlight mb-0">Bs. ${item.descuento}</p>
//                 </div>
//             `;
//         }
        
//         return `<p class="font-21 font-weight-bolder color-highlight mb-0">Bs. ${item.precio}</p>`;
//     }

//     const renderTagsRow = (item) => {
//         if (item.tag && item.tag.length > 0) {
//             return `
//                 <div class="tags-container d-flex flex-row align-items-center justify-content-start gap-2">
//                 ${item.tag.map(tag => `
//                     <button popovertarget="poppytag-${item.id}-${tag.id}" popoveraction="toggle" style="anchor-name: --tag-btn-${item.id}-${tag.id};">
//                         <i data-lucide="${tag.icono}" class="lucide-icon" style="width:1.5rem;height:1.5rem;"></i>
//                     </button>
//                     <div popover
//                         id="poppytag-${item.id}-${tag.id}"
//                         class="tag-info-popover bg-white bg-dtheme-blue p-2 rounded-2 shadow-lg border"
//                         style="position-anchor: --tag-btn-${item.id}-${tag.id}; max-width:250px;">
//                         <p class="color-theme">${tag.nombre}</p>
//                     </div>
//                 `).join('')}
//                 </div>
//             `;
//         }
//         return '';
//     }

//     if (categorizedProducts.length === 0) {
//         container.innerHTML = `
//             <div id="cart-summary-items" class="item-producto-categoria mb-3">
//                 <p class="text-muted"><span>Ups!</span> Parece que aun no hay productos agregados a esta categoria, regresa mas tarde.</p>
//             </div>`;
//     }

//     categorizedProducts.forEach(item => {
//         // Condicionar el renderizado en el caso de que el producto disponga de un descuento
//         // En el caso de disponer de descuento, se muestra el precio descontado, con el precio original tachado
//         const formattedName = item.nombre.charAt(0).toUpperCase() + item.nombre.slice(1).toLowerCase();

//         renderProductCard(item,formattedName);
//     });
// }



// const showErrorState = () => {
//     const container = document.getElementById("listado-productos-categoria");
//     container.innerHTML = `
//         <div id="cart-summary-items" class="item-producto-categoria mb-3">
//             <p class="text-danger">
//                 <span class="font-bold">Error!</span> 
//                 No se pudieron cargar los productos. Por favor, intenta de nuevo.
//             </p>
//         </div>
//     `;
// };

// const showLoadingState = () => {
// const container = document.getElementById("listado-productos-categoria");
// container.innerHTML = `
//         <div class="d-flex justify-content-center align-items-center py-4">
//             <div class="spinner-border text-primary" role="status">
//                 <span class="visually-hidden">Cargando...</span>
//             </div>
//             <span class="ms-3">Cargando productos...</span>
//         </div>
//     `;
// };