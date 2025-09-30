<div class="modal fade" id="listadoOrdenesProducto" tabindex="-1" aria-labelledby="listadoOrdenesProductoModalLabel">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
        <div class="modal-content bg-dtheme-dkblue">
            <div class="modal-header bg-dtheme-dkblue mt-2 border-0 gap-4 d-flex align-items-center">
                <h4 id="titulo-listado-ordenes" class="mb-0 align-self-center text-uppercase">Detalles del pedido</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="contenedor-listado-ordenes">
                <ul id="listado-ordenes" class="px-3 pt-3">
                </ul>
                <div id="contenedor-observacion" class="mb-4 px-4">
                </div>
            </div>
        </div>
    </div>
</div>

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
            
            console.log("Valor de info: ", info)
            elementoTitulo.textContent = info.nombre;
            console.log("Informacion obtenida sobre el producto: ", info);
            renderizarObservacionPedido(info);
            renderizarListadoOrdenes(info);
            // reinitializeLucideIcons();
        }

        const renderizarObservacionPedido = (info) => {
            const initialText = info.observacion ? info.observacion : ''; 

            const contenedorObservacion = $('#contenedor-observacion');
            contenedorObservacion.html(`
                <label for="observacion-pv-${info.pivot_id}" class="color-highlight">Detalles para tu orden</label>
                <div class="input-style has-borders no-icon d-flex flex-row gap-2 align-items-center">
                    <textarea id="observacion-pv-${info.pivot_id}" placeholder="Detalles para tu orden">${initialText}</textarea>
                    <button 
                        data-pventa-id="${info.pivot_id}"
                        class="btn btn-md bg-highlight my-3 btn-guardar-observacion">Guardar</button>
                </div>
            `);
            
            // Asignar evento para actualizar observacion
            contenedorObservacion.off('click', '.btn-guardar-observacion').on('click', '.btn-guardar-observacion', async function() {
                const pivotID = $(this).data('pventa-id');
                const texto = $(`#observacion-pv-${pivotID}`).val();
                
                await actualizarObservacion(pivotID, texto);
            });
        };

        const actualizarObservacion = async(pivotID, texto) => {
            try {
                await VentaService.actualizarObservacionPorID(pivotID, texto);
                mostrarToastSuccess("Nota actualizada con éxito");
                console.log('Observación actualizada correctamente');
            } catch (error) {
                mostrarToastError("Ha sucedido un error al actualizar la nota.");
                console.error('Error al actualizar observación:', error);
            }
        }

        const eliminarOrden = async (pivotID, index) => {
            try {
                console.log(`Intento de eliminar la orden ${index} del producto_venta: ${pivotID}`);
                
                // 'response' is only defined inside 'try'
                const response = await VentaService.eliminarOrdenIndex(pivotID, index); 
                console.log("response al eliminar el item:", response);
                // Se renderizan nuevamente los listados, debido a que los indices de producto_venta.adicionales
                // se reorganizaron
                
                // Renderizar el listado permite reatribuirle los Indices ya modificados
                renderizarListadoOrdenes(response.data);
                // En caso de desear solo eliminar el card de la orden seleccionada, usar la funcion comentada
                // // eliminarCardOrdenIndice(pivotID, index);
                window.reemplazarCardProductoVenta(response.data);
                mostrarToastSuccess("Orden eliminada con éxito");
                console.log('Orden eliminada correctamente');
            } catch (error) {
                // 1. Check if the error is an Axios error with a server response
                const serverResponse = error.response?.data; 
                
                // 2. Default error message
                let errorMessage = "Ha sucedido un error al eliminar la orden."; 

                // 3. Check if the server response contains a 'message'
                if (serverResponse && serverResponse.message) {
                    console.log("Hay message en la respuesta del servidor");
                    errorMessage = serverResponse.message;
                } 
                
                // Display the correct error message
                mostrarToastError(errorMessage);
                console.error('Error al eliminar orden:', error);
            }
        }

        const eliminarCardOrdenIndice = (pivotID,index) => {
            // console.log("card a eliminar: ", pivotID);
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

        const renderizarListadoOrdenes = (info) => {
            // Convertir al objeto info en un array de pares [key, value]
            const ordenesEntries = Object.entries(info.adicionales);
            const listaPrincipal = $(`#listado-ordenes`);

            listaPrincipal.html(`
                ${ordenesEntries.map(([ordenKey, adicionales]) => {
                    // Calcular el costo de adicionales para la orden
                    const precioAdicionalesItem = adicionales.reduce((sum, adicional) => {
                        return sum + (parseFloat(adicional.precio) || 0);
                    }, 0);
                    
                    return `
                        <li id="pedido-${info.pivot_id}-orden-${ordenKey}" style="list-style-type: none">
                            <div class="card card-style">
                                <div class="card-header bg-teal-light">
                                    <div class="card-title mb-0 d-flex flex-row justify-content-between">
                                        <h4 class="mb-0">Orden N° ${ordenKey}</h4>
                                        ${info.aceptado ? `` : `
                                        <button
                                            data-pventa-id="${info.pivot_id}"
                                            data-orden-index="${ordenKey}"
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
                                    `:`
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
                }).join('')}
            `);

            reinitializeLucideIcons();
            
            listaPrincipal.off('click', '.borrar-orden-pventa').on('click', '.borrar-orden-pventa', async function(e) {
                e.preventDefault();

                const pivotID = $(this).data('pventa-id');
                const index = $(this).data('orden-index');
                // const tagNombre = $(this).data('tag-nombre');
                await eliminarOrden(pivotID, index);
                // const infoActualizada = await VentaService.productoVenta(pivotID)
                // const infoProducto = response.data;
                // renderizarListadoOrdenes(infoActualizada.data);
            });
        };
    });
</script>
@endpush