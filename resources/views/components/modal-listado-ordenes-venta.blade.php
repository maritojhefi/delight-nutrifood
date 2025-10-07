<div class="modal fade" id="listadoOrdenesProducto" tabindex="-1" aria-labelledby="listadoOrdenesProductoModalLabel">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
        <div class="modal-content bg-dtheme-dkblue">
            <div class="modal-header bg-dtheme-dkblue mt-2 border-0 gap-4 d-flex align-items-center">
                <h4 id="titulo-listado-ordenes" class="mb-0 align-self-center text-uppercase">Detalles del pedido</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="contenedor-listado-ordenes" class="d-flex align-content-center flex-column">
                <ul id="listado-ordenes" class="px-3 pt-3 mb-0">
                </ul>
                <!-- <div id="contenedor-boton-agregar" class="">

                </div> -->
                    <button id="boton-agregar-orden-venta" class="d-none">Agregar Orden</button>
                <div id="contenedor-observacion" class="mb-4 px-4">
                </div>
            </div>
        </div>
    </div>
</div>

<x-menu-adicionales-producto :isUpdate="true" />

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        window.abrirDialogDetalleOrden = async (info) => {
            await prepararListadoOrdenes(info);
            const modal = new bootstrap.Modal(document.getElementById('listadoOrdenesProducto'));
            modal.show();
        };
        
        const prepararListadoOrdenes = (info) => {
            const elementoTitulo = document.getElementById('titulo-listado-ordenes');
            elementoTitulo.textContent = info.nombre;
            renderizarObservacionPedido(info);
            renderizarListadoOrdenesVenta(info);
        }

        const renderizarObservacionPedido = (info) => {
            
            let textoInicial = '';


            if (typeof info.observacion === 'undefined') {
                console.log("Propiedad 'observacion' no existe. obteniendo de localStorage.");
                const itemCarrito = carritoStorage.obtenerItemCarrito(info.id);
                textoInicial = itemCarrito.observacion ?? '';
                // return null; 
            } else {
                const textoInicial = info.observacion ?? ''; 
            }

            const contenedorObservacion = $('#contenedor-observacion');

            if (!info.aceptado) {
                contenedorObservacion.html(`
                    <label for="observacion-pv-${info.pivot_id}" class="color-highlight">Detalles para tu orden</label>
                    <div class="input-style has-borders no-icon d-flex flex-row gap-2 align-items-center">
                        <textarea id="observacion-pv-${info.pivot_id}" placeholder="Detalles para tu orden">${textoInicial}</textarea>
                        <button 
                            data-pventa-id="${info.pivot_id}"
                            data-producto-id="${info.id}"
                            class="btn btn-md bg-highlight my-3 btn-guardar-observacion">Guardar</button>
                    </div>
                `);
            } else if (info.aceptado && info.observacion) {
                contenedorObservacion.html(`
                    <label for="observacion-pv-${info.pivot_id}" class="color-highlight">Detalles para tu orden</label>
                    <div class="input-style has-borders no-icon">
                        <textarea readonly id="observacion-pv-${info.pivot_id}" placeholder="">${initialText}</textarea>
                    </div>
                `);
            }

            // Asignar evento para actualizar observacion
            contenedorObservacion.off('click', '.btn-guardar-observacion').on('click', '.btn-guardar-observacion', async function() {
                const pivotID = $(this).data('pventa-id');
                const texto = $(`#observacion-pv-${pivotID}`).val();
                if (!pivotID || pivotID == "undefined") {
                    const productoID = $(this).data('producto-id');
                    actualizarObservacionCarrito(productoID, texto);
                } else {
                    await actualizarObservacionVenta(pivotID, texto);
                }
            });
        };

        const actualizarObservacionVenta = async(pivotID, texto) => {
            try {
                await VentaService.actualizarObservacionPorID(pivotID, texto);
                mostrarToastSuccess("Nota actualizada con éxito");
            } catch (error) {
                mostrarToastError("Ha sucedido un error al actualizar la nota.");
                console.error('Error al actualizar observación:', error);
            }
        }
        const actualizarObservacionCarrito = async (productoID, texto) => {
            carritoStorage.actualizarObservacion(productoID, texto);
            mostrarToastSuccess("Nota actualizada con éxito");
        }

        const eliminarOrden = async (pivotID, index) => {
            try {
                const response = await VentaService.eliminarOrdenIndex(pivotID, index); 
                // Se renderizan nuevamente los listados, debido a que los indices de producto_venta.adicionales
                // se reorganizaron
                
                // Renderizar el listado permite reatribuirle los Indices ya modificados
                renderizarListadoOrdenesVenta(response.data);
                // En caso de desear solo eliminar el card de la orden seleccionada, usar la funcion comentada
                // // eliminarCardOrdenIndice(pivotID, index);
                // Actualizar la informacion del pedido general
                console.log("Valor de respuesta tras eliminacion venta: ", response.data);
                window.reemplazarCardProductoVenta(response.data);
                mostrarToastSuccess("Orden eliminada con éxito");
            } catch (error) {
                const serverResponse = error.response?.data; 
                
                let errorMessage = "Ha sucedido un error al eliminar la orden."; 

                if (serverResponse && serverResponse.message) {
                    errorMessage = serverResponse.message;
                } 
                
                mostrarToastError(errorMessage);
            }
        }

        const eliminarOrdenCarrito = async (producto_id, indice) => {
            try {
                carritoStorage.eliminarOrdenProducto(producto_id, indice);
                const itemCarrito = carritoStorage.obtenerItemCarrito(producto_id);
                if (!itemCarrito) {
                    console.warn("No existe registro en itemCarrito")
                    mostrarToastError("El producto ya no existe en el carrito");
                }
                carritoStorage.actualizarContadorCarrito();
                const infoProductoActualizado = await CarritoService.obtenerInfoItemCarrito(itemCarrito, 1);
                // console.log("Valor de carrito tras eliminacion de orden: ", response.data);
                
                // // reemplazarCardOrdenIndice(infoProductoActualizado.item, infoOrden.indice);
                renderizarListadoOrdenesVenta(infoProductoActualizado.item);
            } catch (error) {
                if (error.name === "LastOrderCartDeletionError") {
                    console.warn("🚫 ADVERTENCIA: No se puede eliminar la única orden restante.");
                    mostrarToastError(error.message);
                } else {
                    console.error("❌ Ocurrió otro error inesperado:", error.message);
                    mostrarToastError("Ha ocurrido un error inesperado");
                }
            }
            
            // obtener la informacion actualizada para reiniciar el listado.
            
        }

        window.reemplazarCardOrdenIndice = (infoProductoVenta, indice) => {
            console.log("Informacion al reemplazar card: ", infoProductoVenta);
            const adicionalesIndice = infoProductoVenta.adicionales[indice];
            const cardAntiguo = $(`#pedido-${infoProductoVenta.pivot_id}-orden-${indice}`);
            const cardNuevo = renderizarCardOrden(infoProductoVenta, adicionalesIndice, indice);
            cardAntiguo.replaceWith(cardNuevo);
        }

        const eliminarCardOrdenIndice = (pivotID,index) => {
            const cardEliminar = $(`#pedido-${pivotID}-orden-${index}`);
            cardEliminar.remove();
        }

        const mostrarToastSuccess = (mensaje) => {
            const toastsuccess = $('#toast-success');
            toastsuccess.text(mensaje);
            const toast = new bootstrap.Toast(toastsuccess);
            toast.show()
            setTimeout(() => {
                toast.hide();
            }, 3000);
        }

        const mostrarToastError = (mensaje) => {
            const toasterror = $('#toast-error');
            toasterror.text(mensaje);
            const toast = new bootstrap.Toast(toasterror);
            toast.show()
            setTimeout(() => {
                toast.hide();
            }, 3000);
        }

        const renderizarCardOrden = (info, adicionales, indice) => {
            const precioAdicionalesItem = adicionales.reduce((sum, adicional) => {
                return sum + (parseFloat(adicional.precio) || 0);
            }, 0);

            // Explicitly check for aceptado - treat undefined, null, and false as "not accepted"
            const esAceptado = info.aceptado === true;

            return `
                <li id="pedido-${info.pivot_id}-orden-${indice}"
                    data-producto-id="${info.id}"
                    data-pventa-id="${info.pivot_id}"
                    data-orden-index="${indice}"
                    class="${esAceptado ? '' : 'actualizar-orden-venta'}"
                    style="list-style-type: none">
                    <div class="card card-style">
                        <div class="card-header bg-teal-light">
                            <div class="card-title mb-0 d-flex flex-row justify-content-between">
                                <h4 class="mb-0">Orden N° ${indice}</h4>
                                ${esAceptado ? '' : `
                                    <button
                                        data-pventa-id="${info.pivot_id}"
                                        data-producto-id="${info.id}"
                                        data-orden-index="${indice}"
                                        class="borrar-orden-pventa"
                                    >
                                        <i class="lucide-icon" data-lucide="trash-2"></i>
                                    </button>
                                `}
                            </div>
                        </div>
                        
                        <div class="card-body bg-dtheme-blue">
                            ${adicionales.length <= 0 ? `
                                <span class="color-theme">Sin extras</span>
                            ` : `
                                <h5 class="color-teal-light">Adicionales ${precioAdicionalesItem > 0 ? `(Bs. ${precioAdicionalesItem.toFixed(2)})` : ''}</h5>
                                <ul class="row mb-0 ps-1">
                                    ${adicionales.map(adicional => `
                                        <li class="col-6 color-theme" style="list-style-type: none">
                                            ${adicional.nombre}
                                            ${adicional.precio > 0 ? ` <span class="text-muted">(${parseFloat(adicional.precio).toFixed(2)})</span>` : ''}
                                            ${adicional.limitado ? '<span class="text-danger"> (Limitado)</span>' : ''}
                                            ${adicional.cantidad > 1 ? ` (x${adicional.cantidad})` : ''}
                                        </li>
                                    `).join('')}
                                </ul>
                            `}
                        </div>
                    </div>
                </li>
            `;
        }

        const renderizarBotonOrden = (infoProductoVenta) => {
            // // console.log("infoProductoVentaBotonOrden: ", infoProductoVenta);
            
            const { id, tipo, stock_disponible } = infoProductoVenta;
            const enCarrito = carritoStorage.cantidadOrdenesProducto(id);
            const esLimitado = stock_disponible !== "INFINITO";
            const limiteAlcanzado = esLimitado && (stock_disponible <= enCarrito || stock_disponible === 0);
            
            const buttonClass = tipo === "complejo" ? "menu-adicionales-btn" : "agregar-unidad";
            const disabledAttr = limiteAlcanzado ? "disabled" : "";
            const textoBoton = limiteAlcanzado ? "Límite actual alcanzado" : "Agregar orden";
            
            return `
                <button 
                    id="boton-agregar-orden-venta"
                    data-producto-id="${id}"
                    ${disabledAttr}
                    class="${buttonClass} btn btn-xs add-disabler mx-5 mb-3 mt-n1 rounded-s bg-teal-light d-flex flex-row gap-2 align-items-center text-uppercase justify-content-center">
                    ${textoBoton}
                    <i class="lucide-icon" data-lucide="circle-plus"></i>
                </button>
            `;
        }

        window.deshabilitarBotonAgregadoOrden = (mensaje = "Límite actual alcanzado") => {
            const botonAgregadoOrden = $('#boton-agregar-orden-venta');
            
            botonAgregadoOrden
                .prop('disabled', true)
                // .removeClass('bg-teal-light')
                // .addClass('bg-gray-dark')
                .html(`
                    ${mensaje}
                    <i class="lucide-icon" data-lucide="ban"></i>
                `);
        }

        window.habilitarBotonAgregadoOrden = () => {
            const botonAgregadoOrden = $('#boton-agregar-orden-venta');
            
            botonAgregadoOrden
                .prop('disabled', false)
                // .removeClass('bg-gray-dark')
                // .addClass('bg-teal-light')
                .html(`
                    Agregar orden
                    <i class="lucide-icon" data-lucide="circle-plus"></i>
                `);
        }

        window.renderizarListadoOrdenesVenta = (info) => {
            // Convertir al objeto info en un array de pares [key, value]
            const ordenesEntries = Object.entries(info.adicionales);
            const listaPrincipal = $(`#listado-ordenes`);
            const contenedorBotonAgregar = $(`#contenedor-boton-agregar`);
            const botonBase = $(`#boton-agregar-orden-venta`);
            const botonAgregar = renderizarBotonOrden(info);
            
            listaPrincipal.html(`
            ${ordenesEntries.map(([ordenKey, adicionales]) => {
                    return renderizarCardOrden(info, adicionales, ordenKey);
                }).join('')}
            `);

            if (info.aceptado == false || !info.aceptado) {
                // contenedorBotonAgregar.html(botonAgregar);
                botonBase.replaceWith(botonAgregar);
            } else {
                botonBase.addClass('d-none');
            }
            // else {
            //     contenedorBotonAgregar.html('');
            // }
            
            
            listaPrincipal.off('click', '.borrar-orden-pventa').on('click', '.borrar-orden-pventa', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const pivotID = $(this).data('pventa-id');
                const productoID = $(this).data('producto-id');
                const index = $(this).data('orden-index');
                if (!pivotID || pivotID == "undefined") {
                    // // console.log("PivotID: ", pivotID);
                    // // console.log("Eliminado orden del carrito")
                    await eliminarOrdenCarrito(productoID, index);
                } else {
                    // // console.log("PivotID: ", pivotID);
                    // // console.log("Eliminando orden en venta activa")
                    await eliminarOrden(pivotID, index);
                }
            });

            cantidadEnCarrito = carritoStorage.cantidadOrdenesProducto(info.id);
            // Si existe el item en carrito
            
            if (cantidadEnCarrito > 0 && cantidadEnCarrito >= info.stock_disponible) {
                deshabilitarBotonAgregadoOrden();
                // Verificar el stock del producto, si es menor o igual a las unidades en el carrito, deshabilitar el boton
            } else if (cantidadEnCarrito > 0 && cantidadEnCarrito < info.stock_disponible) {
                habilitarBotonAgregadoOrden();
            }

            reinitializeLucideIcons();
                // De otra forma, rehabilitarlo
        };


    });
</script>
@endpush