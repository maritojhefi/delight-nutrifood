<div class="modal fade" id="listadoOrdenesProducto" tabindex="-1" aria-labelledby="listadoOrdenesProductoModalLabel">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
        <div class="modal-content">
            <div class="modal-header mt-2 border-0 gap-4 d-flex align-items-center">
                <h4 id="titulo-listado-ordenes" class="mb-0 align-self-center text-uppercase">Detalles del pedido</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="contenedor-listado-ordenes">
                <ul id="listado-ordenes" class="px-3 pt-3">
                </ul>
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
            const listaPrincipal = document.getElementById(`listado-ordenes`);
            console.log("Valor de info: ", info)
            elementoTitulo.textContent = info.nombre;
            console.log("Informacion obtenida sobre el producto: ", info);
            listaPrincipal.innerHTML = renderizarListadoOrdenes(info);
        }

        const renderizarListadoOrdenes = (info) => {
            // Convertir al objeto info en un array de pares [key, value]
            const ordenesEntries = Object.entries(info.adicionales);
            
            return `
                ${ordenesEntries.map(([ordenKey, adicionales]) => `
                    <li style="list-style-type: none">
                        <div class="card card-style">
                            <div class="card-header bg-teal-light">
                                <div class="card-title mb-0">
                                    <h4 class="mb-0">Orden N° ${ordenKey}</h4>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5>Adicionales</h5>
                                <ul class="row mb-0">
                                    ${adicionales.map(adicional => `
                                        <li class="col-6">
                                            ${adicional.nombre}
                                            ${adicional.limitado ? '<span class="text-danger"> (Limitado)</span>' : ''}
                                            ${adicional.cantidad > 1 ? ` (x${adicional.cantidad})` : ''}
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        </div>
                    </li>
                `).join('')}
            `;
        };
    });
</script>
@endpush