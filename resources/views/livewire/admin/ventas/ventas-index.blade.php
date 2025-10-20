<div class="row m-0 p-0">
    @include('livewire.admin.ventas.includes-pos.card-ventas-pendientes')
    @isset($cuenta)
        @include('livewire.admin.ventas.includes-pos.card-detalle-cuenta')

        @include('livewire.admin.ventas.includes-pos.card-buscador-productos')
    @endisset
    @include('livewire.admin.ventas.includes-pos.modales')

</div>
@push('css')
    <style>
        /* Ancho del scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            /* Puedes ajustar este valor */
            height: 5px;
            /* Si también deseas estilizar el scrollbar horizontal */
        }

        /* Fondo del scrollbar */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color de fondo del track */
        }

        /* Estilo del thumb (la parte que se mueve) */
        ::-webkit-scrollbar-thumb {
            background: #20c997;
            /* Color del thumb */
            border-radius: 5px;
            /* Bordes redondeados */
        }

        /* Cambiar el color del thumb al pasar el mouse */
        ::-webkit-scrollbar-thumb:hover {
            background: #20c997;
            /* Color al pasar el mouse */
        }


        @media (max-width: 768px) {
            ::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }

            ::-webkit-scrollbar-thumb {
                background: #20c997;
                border-radius: 5px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #20c997;
            }

            html {
                scrollbar-width: thin;
                scrollbar-color: #20c997 #20c997;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        Livewire.on('focusInputBuscador', (id) => {
            const input = document.getElementById('input-buscador');
            if (input) {
                input.focus();
            }
            if (id) {
                setTimeout(() => {
                    const element = document.querySelector(
                        `[data-registro-venta-id="${id}"]`);
                    if (element) {
                        element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    }
                }, 100);
            }
        });

        function sendFinalizarVenta() {
            Livewire.emit('cerrarVenta');
        }

        function atrasModalImpresion() {
            Livewire.emit('modalResumen');
        }

        function descargarPDFFile() {

            Livewire.emit('descargarPDF');
        }

        function imprimirReciboApi() {
            Livewire.emit('imprimir');
        }

        function alertCobrarVenta(montoMetodosAcumulado, montoSubtotal, cliente = null) {
            // Convertir las cantidades a números por si vienen como strings
            let acumulado = parseFloat(montoMetodosAcumulado) || 0;
            let subtotal = parseFloat(montoSubtotal) || 0;
            let icono;
            let texto;
            if (cliente != null) {
                // Comparaciones para determinar el estado
                if (acumulado === subtotal) {
                    icono = 'warning';
                    texto = 'Esta venta cambiará a "PAGADO"';
                } else if (acumulado < subtotal) {
                    icono = 'warning';
                    texto = `Se le creará una deuda de ${subtotal - acumulado} Bs a ${cliente}`;
                } else {
                    icono = 'info';
                    texto = `Se agregará un excedente de ${acumulado - subtotal} Bs a ${cliente}`;
                }
            } else {
                icono = 'warning';
                texto = 'Esta venta cambiará a "PAGADO"';
            }


            // Mostrar SweetAlert con la información correspondiente
            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                title: '¿Estás seguro?',
                text: texto,
                icon: icono,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar evento a Livewire
                    Livewire.emit('cobrar');
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('scrollToSubcategoria', (subcategoriaId) => {
                setTimeout(() => {
                    const element = document.querySelector(
                        `[data-subcategoria-id="${subcategoriaId}"]`);
                    if (element) {
                        element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    }
                }, 100);
            });

            Livewire.on('focusInput', (idInput) => {
                console.log(idInput)
                $('#input-' + idInput).focus().select();
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.min.js"></script>
    <script src="{{ asset('js/adicionales-sweetalert.js') }}?v=1"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('imprimir-recibo-local', (rawbytes) => {
                const decodedBytes = Uint8Array.from(atob(rawbytes), c => c.charCodeAt(0));

                if (!qz.websocket.isActive()) {
                    qz.websocket.connect().then(() => {
                        iniciarImpresion(decodedBytes);
                    }).catch((err) => {
                        console.error("Error al conectar con QZ Tray:", err);
                    });
                } else {
                    iniciarImpresion(decodedBytes);
                }
            });
        });

        function iniciarImpresion(decodedBytes) {
            qz.printers.find().then(printers => {
                const impresora58 = printers.find(nombre => nombre.toLowerCase().includes('58'));
                if (!impresora58) {
                    Toast.fire({
                        title: "No se encontró ninguna impresora conectada.",
                        icon: "error"
                    });
                    return; // IMPORTANTE: evitar seguir si no hay impresora
                }
                const config = qz.configs.create(impresora58);
                Toast.fire({
                    title: "Impresora seleccionada no activa, imprimiendo en la impresora local.",
                    icon: "success"
                });
                return qz.print(config, [{
                    type: 'raw',
                    format: 'hex',
                    data: bytesToHex(decodedBytes)
                }]);
            }).catch(err => {
                console.error('Error al imprimir:', err);
            });
        }

        function bytesToHex(bytes) {
            return Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
        }
    </script>
@endpush
@push('scripts')
    <script>
        function mostrarDetalleDescuentos(detalle, nombreProducto, precioOriginal, subtotal, cantidad = 1) {
            // Separar los descuentos por el delimitador " | "
            const descuentos = detalle.split(' | ');

            // Crear filas de la tabla para cada descuento
            let filasDescuentos = '';
            let totalDescuentosCalculado = 0;

            descuentos.forEach(descuento => {
                if (descuento.trim() !== '') {
                    // Extraer el monto del descuento del texto
                    const match = descuento.match(/(\d+\.?\d*)\s*Bs/);
                    if (match) {
                        const montoDescuento = parseFloat(match[1]);
                        totalDescuentosCalculado += montoDescuento;
                    }

                    filasDescuentos += `
                    <tr>
                        <td style="border: 1px solid white; padding: 8px; color: white;">${descuento}</td>
                        <td style="border: 1px solid white; padding: 8px; text-align: right; color: white; font-weight: bold;">-${match ? parseFloat(match[1]).toFixed(2) : '0.00'} Bs</td>
                    </tr>
                `;
                }
            });

            // Calcular precio original total (precio unitario * cantidad)
            const precioOriginalTotal = precioOriginal * cantidad;

            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                title: false,
                html: `
                <div style="color: white; margin-bottom: 15px;">
                    <div style="margin-bottom: 5px;">Precio Unitario: <strong>${precioOriginal.toFixed(2)} Bs</strong></div>
                    <div>Cantidad: <strong>${cantidad} ${cantidad === 1 ? 'unidad' : 'unidades'}</strong></div>
                </div>
                <table style="width: 100%; color: white; border-collapse: collapse; text-align: justify;">
                    <tr>
                        <td style="border: 1px solid white; padding: 8px;">Subtotal Original</td>
                        <td style="border: 1px solid white; padding: 8px; text-align: right; font-weight: bold;">${precioOriginalTotal.toFixed(2)} Bs</td>
                    </tr>
                    ${filasDescuentos}
                    <tr>
                        <td style="border: 1px solid white; padding: 8px;">Total Descuentos</td>
                        <td style="border: 1px solid white; padding: 8px; text-align: right; font-weight: bold;">-${totalDescuentosCalculado.toFixed(2)} Bs</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid white; padding: 8px;">PRECIO FINAL</td>
                        <td style="border: 1px solid white; padding: 8px; text-align: right; font-weight: bold;">${subtotal.toFixed(2)} Bs</td>
                    </tr>
                </table>
            `,
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                showCloseButton: false
            });
        }
    </script>
@endpush


    <script>
        let mesasDisponiblesGlobal = [];
        let clientesDisponiblesGlobal = [];
        let debounceTimerCambio;
        let clienteSeleccionadoCambioId = null;
        let mesaSeleccionadaId = null;

        // Escuchar mesas disponibles
        window.addEventListener('mesasDisponibles', event => {
            mesasDisponiblesGlobal = event.detail.mesas;
            if (typeof actualizarListaMesas === 'function') {
                actualizarListaMesas();
            }
        });

        // Escuchar clientes encontrados
        window.addEventListener('clientesEncontrados', event => {
            clientesDisponiblesGlobal = event.detail.clientes;
            if (typeof actualizarListaClientesCambio === 'function') {
                actualizarListaClientesCambio();
            }
        });

        function cambiarTipoEntrega(tipoActual = 'recoger') {
            clienteSeleccionadoCambioId = null;
            mesaSeleccionadaId = null;
            clientesDisponiblesGlobal = [];
            mesasDisponiblesGlobal = [];

            // Si el tipo actual es 'reserva', lo tratamos como 'recoger' para el autoseleccionado
            const tipoParaSeleccionar = tipoActual === 'reserva' ? 'recoger' : tipoActual;

            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                title: '<i class="fa fa-exchange"></i> Cambiar Tipo de Entrega',
                html: `
                <div class="text-start">
                    <label class="form-label fw-bold mb-3">Selecciona el tipo de entrega:</label>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioMesa" value="mesa" ${tipoParaSeleccionar === 'mesa' ? 'checked' : ''}>
                            <label class="btn btn-outline-primary w-100 py-3" for="radioMesa">
                                <i class="fa fa-table fa-2x d-block mb-2"></i>
                                <strong>Mesa</strong>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioDelivery" value="delivery" ${tipoParaSeleccionar === 'delivery' ? 'checked' : ''}>
                            <label class="btn btn-outline-secondary w-100 py-3" for="radioDelivery">
                                <i class="fa fa-truck fa-2x d-block mb-2"></i>
                                <strong>Delivery</strong>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioRecoger" value="recoger" ${tipoParaSeleccionar === 'recoger' ? 'checked' : ''}>
                            <label class="btn btn-outline-success w-100 py-3" for="radioRecoger">
                                <i class="fa fa-bolt fa-2x d-block mb-2"></i>
                                <strong>Venta Rápida</strong>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="tipoEntregaCambio" id="radioReservaCambio" value="reserva">
                            <label class="btn btn-outline-info w-100 py-3" for="radioReservaCambio">
                                <i class="flaticon-088-time fa-2x d-block mb-2"></i>
                                <strong>Reserva</strong>
                            </label>
                        </div>
                    </div>

                    <!-- Contenedor dinámico para campos adicionales -->
                    <div id="camposAdicionales" class="border-top pt-3 mt-2">
                        <div class="text-muted text-center py-2">
                            <i class="fa fa-check-circle"></i> Venta Rápida no requiere datos adicionales
                        </div>
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Cambiar Tipo de Entrega',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                width: '600px',
                didOpen: () => {
                    const confirmBtn = document.querySelector('.swal2-confirm');
                    const camposAdicionales = document.getElementById('camposAdicionales');

                    const radioMesa = document.getElementById('radioMesa');
                    const radioDelivery = document.getElementById('radioDelivery');
                    const radioRecoger = document.getElementById('radioRecoger');
                    const radioReserva = document.getElementById('radioReservaCambio');

                    function actualizarCamposAdicionales() {
                        const tipoSeleccionado = document.querySelector(
                            'input[name="tipoEntregaCambio"]:checked').value;
                        clienteSeleccionadoCambioId = null;
                        mesaSeleccionadaId = null;

                        switch (tipoSeleccionado) {
                            case 'mesa':
                                camposAdicionales.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0">Buscar Cliente (Opcional):</label>
                                    <button type="button" id="limpiarClienteMesa" class="btn btn-sm btn-outline-secondary" style="display: none;">
                                        <i class="fa fa-times"></i> Limpiar
                                    </button>
                                </div>
                                <input type="text" id="busquedaClienteMesa" class="form-control mb-2" 
                                       placeholder="Escribe el nombre o email del cliente... (Opcional)"
                                       autocomplete="off">
                                <div id="resultadosClientesMesa" class="border rounded mb-3" style="max-height: 150px; overflow-y: auto; background-color: white;">
                                    <div class="text-muted text-center py-2">Opcional: Escribe para buscar clientes...</div>
                                </div>
                                
                                <label class="form-label fw-bold">Selecciona la Mesa:</label>
                                <div id="listaMesas" class="border rounded p-2" style="max-height: 300px; overflow-y: auto; background-color: white;">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                        <div>Cargando mesas...</div>
                                    </div>
                                </div>
                            `;
                                confirmBtn.disabled = true;
                                @this.call('obtenerMesasDisponibles');

                                setTimeout(() => {
                                    const inputBusquedaMesa = document.getElementById(
                                        'busquedaClienteMesa');
                                    const btnLimpiarCliente = document.getElementById(
                                        'limpiarClienteMesa');
                                    const resultadosDiv = document.getElementById(
                                        'resultadosClientesMesa');

                                    if (inputBusquedaMesa) {
                                        inputBusquedaMesa.addEventListener('input', function() {
                                            this.style.borderColor = '';
                                            this.style.backgroundColor = '';
                                            clienteSeleccionadoCambioId = null;
                                            if (btnLimpiarCliente) btnLimpiarCliente.style
                                                .display = 'none';
                                            buscarClientesCambio(this.value);
                                        });
                                    }

                                    if (btnLimpiarCliente) {
                                        btnLimpiarCliente.addEventListener('click', function() {
                                            clienteSeleccionadoCambioId = null;
                                            if (inputBusquedaMesa) {
                                                inputBusquedaMesa.value = '';
                                                inputBusquedaMesa.style.borderColor = '';
                                                inputBusquedaMesa.style.backgroundColor = '';
                                            }
                                            if (resultadosDiv) {
                                                resultadosDiv.innerHTML =
                                                    '<div class="text-muted text-center py-2">Opcional: Escribe para buscar clientes...</div>';
                                            }
                                            this.style.display = 'none';
                                        });
                                    }
                                }, 100);
                                break;

                            case 'delivery':
                                camposAdicionales.innerHTML = `
                                <label class="form-label fw-bold">Buscar Cliente (Obligatorio):</label>
                                <input type="text" id="busquedaClienteCambio" class="form-control mb-2" 
                                       placeholder="Escribe el nombre o email del cliente..."
                                       autocomplete="off">
                                <div id="resultadosClientesCambio" class="border rounded" style="max-height: 200px; overflow-y: auto; background-color: white;">
                                    <div class="text-muted text-center py-2">Escribe para buscar clientes...</div>
                                </div>
                            `;
                                confirmBtn.disabled = true;

                                setTimeout(() => {
                                    const inputBusqueda = document.getElementById(
                                        'busquedaClienteCambio');
                                    if (inputBusqueda) {
                                        inputBusqueda.focus();
                                        inputBusqueda.addEventListener('input', function() {
                                            this.style.borderColor = '';
                                            this.style.backgroundColor = '';
                                            clienteSeleccionadoCambioId = null;
                                            confirmBtn.disabled = true;
                                            buscarClientesCambio(this.value);
                                        });
                                    }
                                }, 100);
                                break;

                            case 'reserva':
                                camposAdicionales.innerHTML = `
                                <label class="form-label fw-bold">Buscar Cliente (Obligatorio):</label>
                                <input type="text" id="busquedaClienteReservaCambio" class="form-control mb-3" 
                                       placeholder="Escribe el nombre o email del cliente..."
                                       autocomplete="off">
                                <div id="resultadosClientesReservaCambio" class="border rounded mb-3" style="max-height: 150px; overflow-y: auto; background-color: white;">
                                    <div class="text-muted text-center py-2">Escribe para buscar clientes...</div>
                                </div>
                                
                                <label class="form-label fw-bold">Tipo de Entrega (Obligatorio):</label>
                                <select id="tipoEntregaReserva" class="form-select mb-3">
                                    <option value="">Selecciona el tipo de entrega...</option>
                                    <option value="mesa">Mesa</option>
                                    <option value="recoger">Para Recoger</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                                
                                <div id="camposRequeridos"></div>
                                
                                <div class="border-top pt-3 mt-2">
                                    <label class="form-label fw-bold">Fecha de Reserva:</label>
                                    <div class="btn-group w-100 mb-3" role="group">
                                        <input type="radio" class="btn-check" name="fechaReservaCambio" id="radioHoyCambio" value="hoy" checked>
                                        <label class="btn btn-outline-primary" for="radioHoyCambio">
                                            <i class="fa fa-calendar-day"></i> Hoy
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="fechaReservaCambio" id="radioMananaCambio" value="manana">
                                        <label class="btn btn-outline-primary" for="radioMananaCambio">
                                            <i class="fa fa-calendar-plus"></i> Mañana
                                        </label>
                                    </div>
                                    
                                    <label class="form-label fw-bold">Hora de Reserva:</label>
                                    <select id="horaReservaCambio" class="form-select">
                                        <option value="">Selecciona una hora</option>
                                    </select>
                                </div>
                            `;
                                confirmBtn.disabled = true;

                                setTimeout(() => {
                                    const tipoEntregaSelect = document.getElementById(
                                        'tipoEntregaReserva');
                                    const camposRequeridos = document.getElementById(
                                    'camposRequeridos');
                                    const selectHora = document.getElementById('horaReservaCambio');
                                    const radioHoy = document.getElementById('radioHoyCambio');
                                    const radioManana = document.getElementById('radioMananaCambio');
                                    const inputBusquedaClienteReserva = document.getElementById(
                                        'busquedaClienteReservaCambio');

                                    let tipoEntregaReservaSeleccionado = '';

                                    // Inicializar búsqueda de cliente
                                    if (inputBusquedaClienteReserva) {
                                        inputBusquedaClienteReserva.focus();
                                        inputBusquedaClienteReserva.addEventListener('input',
                                    function() {
                                            this.style.borderColor = '';
                                            this.style.backgroundColor = '';
                                            clienteSeleccionadoCambioId = null;
                                            confirmBtn.disabled = true;
                                            buscarClientesCambio(this.value);
                                        });
                                    }

                                    tipoEntregaSelect.addEventListener('change', function() {
                                        tipoEntregaReservaSeleccionado = this.value;
                                        mesaSeleccionadaId = null;

                                        switch (this.value) {
                                            case 'mesa':
                                                camposRequeridos.innerHTML = `
                                                <label class="form-label fw-bold">Selecciona la Mesa:</label>
                                                <div id="listaMesasReserva" class="border rounded p-2 mb-3" style="max-height: 250px; overflow-y: auto; background-color: white;">
                                                    <div class="text-center py-3">
                                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                                        <div>Cargando mesas...</div>
                                                    </div>
                                                </div>
                                            `;
                                                @this.call('obtenerMesasDisponibles');
                                                break;

                                            case 'delivery':
                                                camposRequeridos.innerHTML = `
                                                <div class="alert alert-info mb-3">
                                                    <i class="fa fa-info-circle"></i> Delivery - La entrega será enviada al cliente seleccionado.
                                                </div>
                                            `;
                                                break;

                                            case 'recoger':
                                                camposRequeridos.innerHTML = `
                                                <div class="alert alert-info mb-3">
                                                    <i class="fa fa-info-circle"></i> Reserva para recoger en local.
                                                </div>
                                            `;
                                                break;

                                            default:
                                                camposRequeridos.innerHTML = '';
                                        }

                                        verificarFormularioReservaCambio();
                                    });

                                    // Listener para actualizar lista de mesas cuando se carguen
                                    window.addEventListener('mesasDisponibles', function handler(
                                    event) {
                                        const listaMesas = document.getElementById(
                                            'listaMesasReserva');
                                        if (listaMesas && tipoEntregaReservaSeleccionado ===
                                            'mesa') {
                                            actualizarListaMesasReserva();
                                        }
                                    });

                                    window.actualizarListaMesasReserva = function() {
                                        const listaMesas = document.getElementById(
                                            'listaMesasReserva');
                                        if (!listaMesas) return;

                                        if (mesasDisponiblesGlobal.length === 0) {
                                            listaMesas.innerHTML =
                                                '<div class="text-muted text-center py-2">No hay mesas disponibles</div>';
                                            return;
                                        }

                                        listaMesas.innerHTML = '<div class="row g-2">' +
                                            mesasDisponiblesGlobal.map(mesa => `
                                            <div class="col-4">
                                                <div class="mesa-item-reserva card text-center p-2 ${mesa.ocupada && !mesa.es_actual ? 'mesa-ocupada-cambio' : ''} ${mesa.es_actual ? 'mesa-actual' : ''}" 
                                                     data-mesa-id="${mesa.id}"
                                                     style="cursor: ${mesa.ocupada && !mesa.es_actual ? 'not-allowed' : 'pointer'}; border: 2px solid ${mesa.es_actual ? '#28a745' : mesa.ocupada ? '#dc3545' : '#e9ecef'};">
                                                    <i class="fa fa-table fa-2x ${mesa.ocupada && !mesa.es_actual ? 'text-danger' : mesa.es_actual ? 'text-success' : 'text-primary'}"></i>
                                                    <div><strong>Mesa ${mesa.numero}</strong></div>
                                                    ${mesa.capacidad ? `<small class="text-muted">${mesa.capacidad} personas</small>` : ''}
                                                    ${mesa.es_actual ? '<div class="badge badge-success mt-1">Actual</div>' : ''}
                                                    ${mesa.ocupada && !mesa.es_actual ? '<div class="badge badge-danger mt-1">Ocupada</div>' : ''}
                                                </div>
                                            </div>
                                        `).join('') +
                                            '</div>';

                                        document.querySelectorAll('.mesa-item-reserva').forEach(
                                            item => {
                                                const mesaId = parseInt(item.getAttribute(
                                                    'data-mesa-id'));
                                                const mesa = mesasDisponiblesGlobal.find(m => m
                                                    .id === mesaId);

                                                if (!mesa.ocupada || mesa.es_actual) {
                                                    item.addEventListener('click', function() {
                                                        document.querySelectorAll(
                                                                '.mesa-item-reserva')
                                                            .forEach(m => {
                                                                m.style
                                                                    .borderColor = m
                                                                    .querySelector(
                                                                        '.badge-success'
                                                                        ) ?
                                                                    '#28a745' :
                                                                    '#e9ecef';
                                                                m.style
                                                                    .backgroundColor =
                                                                    '';
                                                            });

                                                        this.style.borderColor =
                                                            '#007bff';
                                                        this.style.backgroundColor =
                                                            '#e7f3ff';
                                                        mesaSeleccionadaId = mesaId;
                                                        verificarFormularioReservaCambio
                                                            ();
                                                    });

                                                    item.addEventListener('mouseover',
                                                    function() {
                                                        if (!mesa.ocupada || mesa
                                                            .es_actual) {
                                                            this.style.transform =
                                                                'scale(1.05)';
                                                        }
                                                    });

                                                    item.addEventListener('mouseout',
                                                function() {
                                                        this.style.transform =
                                                            'scale(1)';
                                                    });
                                                }
                                            });
                                    }

                                    function actualizarOpcionesHoraCambio() {
                                        const fechaSeleccionada = document.querySelector(
                                            'input[name="fechaReservaCambio"]:checked').value;
                                        selectHora.innerHTML =
                                            '<option value="">Selecciona una hora</option>';

                                        const ahora = new Date();
                                        const horaActual = ahora.getHours() + ahora.getMinutes() / 60;

                                        let opciones = [];

                                        if (fechaSeleccionada === 'hoy') {
                                            const horaInicio = Math.ceil(horaActual * 2) / 2;
                                            opciones = generarOpcionesHora(horaInicio, 22);
                                        } else {
                                            opciones = generarOpcionesHora(8, 22);
                                        }

                                        if (opciones.length === 0) {
                                            selectHora.innerHTML =
                                                '<option value="">No hay horarios disponibles</option>';
                                        } else {
                                            opciones.forEach(opcion => {
                                                const option = document.createElement('option');
                                                option.value = opcion.valor;
                                                option.textContent = opcion.display;
                                                selectHora.appendChild(option);
                                            });
                                        }

                                        verificarFormularioReservaCambio();
                                    }

                                    function verificarFormularioReservaCambio() {
                                        const tipoEntregaSelect = document.getElementById(
                                            'tipoEntregaReserva');
                                        const tieneHora = selectHora && selectHora.value !== '';
                                        const tieneTipoEntrega = tipoEntregaSelect && tipoEntregaSelect
                                            .value !== '';
                                        const tieneCliente = clienteSeleccionadoCambioId !== null;

                                        // El cliente es SIEMPRE obligatorio para reservas
                                        if (!tieneCliente || !tieneTipoEntrega || !tieneHora) {
                                            confirmBtn.disabled = true;
                                            return;
                                        }

                                        let cumpleRequisitos = false;

                                        switch (tipoEntregaSelect.value) {
                                            case 'mesa':
                                                cumpleRequisitos = mesaSeleccionadaId !== null;
                                                break;
                                            case 'delivery':
                                            case 'recoger':
                                                cumpleRequisitos = true;
                                                break;
                                        }

                                        confirmBtn.disabled = !cumpleRequisitos;
                                    }

                                    if (radioHoy) radioHoy.addEventListener('change',
                                        actualizarOpcionesHoraCambio);
                                    if (radioManana) radioManana.addEventListener('change',
                                        actualizarOpcionesHoraCambio);
                                    if (selectHora) selectHora.addEventListener('change',
                                        verificarFormularioReservaCambio);

                                    window.verificarFormularioReservaCambio =
                                        verificarFormularioReservaCambio;
                                    actualizarOpcionesHoraCambio();
                                }, 100);
                                break;

                            case 'recoger':
                            default:
                                camposAdicionales.innerHTML = `
                                <div class="text-success text-center py-3">
                                    <i class="fa fa-check-circle fa-2x mb-2"></i>
                                    <div><strong>Venta Rápida no requiere datos adicionales</strong></div>
                                </div>
                            `;
                                confirmBtn.disabled = false;
                                break;
                        }
                    }

                    // Listeners para cambio de tipo
                    radioMesa.addEventListener('change', actualizarCamposAdicionales);
                    radioDelivery.addEventListener('change', actualizarCamposAdicionales);
                    radioRecoger.addEventListener('change', actualizarCamposAdicionales);
                    radioReserva.addEventListener('change', actualizarCamposAdicionales);

                    // Ejecutar inicialmente para cargar los campos del tipo preseleccionado
                    actualizarCamposAdicionales();

                    // Funciones auxiliares
                    window.actualizarListaMesas = function() {
                        const listaMesas = document.getElementById('listaMesas');
                        if (!listaMesas) return;

                        if (mesasDisponiblesGlobal.length === 0) {
                            listaMesas.innerHTML =
                                '<div class="text-muted text-center py-2">No hay mesas disponibles</div>';
                            return;
                        }

                        listaMesas.innerHTML = '<div class="row g-2">' +
                            mesasDisponiblesGlobal.map(mesa => `
                            <div class="col-4">
                                <div class="mesa-item-cambio card text-center p-2 ${mesa.ocupada && !mesa.es_actual ? 'mesa-ocupada-cambio' : ''} ${mesa.es_actual ? 'mesa-actual' : ''}" 
                                     data-mesa-id="${mesa.id}"
                                     style="cursor: ${mesa.ocupada && !mesa.es_actual ? 'not-allowed' : 'pointer'}; border: 2px solid ${mesa.es_actual ? '#28a745' : mesa.ocupada ? '#dc3545' : '#e9ecef'};">
                                    <i class="fa fa-table fa-2x ${mesa.ocupada && !mesa.es_actual ? 'text-danger' : mesa.es_actual ? 'text-success' : 'text-primary'}"></i>
                                    <div><strong>Mesa ${mesa.numero}</strong></div>
                                    ${mesa.capacidad ? `<small class="text-muted">${mesa.capacidad} personas</small>` : ''}
                                    ${mesa.es_actual ? '<div class="badge badge-success mt-1">Actual</div>' : ''}
                                    ${mesa.ocupada && !mesa.es_actual ? '<div class="badge badge-danger mt-1">Ocupada</div>' : ''}
                                </div>
                            </div>
                        `).join('') +
                            '</div>';

                        document.querySelectorAll('.mesa-item-cambio').forEach(item => {
                            const mesaId = parseInt(item.getAttribute('data-mesa-id'));
                            const mesa = mesasDisponiblesGlobal.find(m => m.id === mesaId);

                            if (!mesa.ocupada || mesa.es_actual) {
                                item.addEventListener('click', function() {
                                    document.querySelectorAll('.mesa-item-cambio').forEach(
                                        m => {
                                            m.style.borderColor = m.querySelector(
                                                    '.badge-success') ? '#28a745' :
                                                '#e9ecef';
                                            m.style.backgroundColor = '';
                                        });

                                    this.style.borderColor = '#007bff';
                                    this.style.backgroundColor = '#e7f3ff';
                                    mesaSeleccionadaId = mesaId;
                                    confirmBtn.disabled = false;
                                });

                                item.addEventListener('mouseover', function() {
                                    if (!mesa.ocupada || mesa.es_actual) {
                                        this.style.transform = 'scale(1.05)';
                                    }
                                });

                                item.addEventListener('mouseout', function() {
                                    this.style.transform = 'scale(1)';
                                });
                            }
                        });
                    };

                    window.actualizarListaClientesCambio = function() {
                        const listaResultados = document.getElementById('resultadosClientesCambio') ||
                            document.getElementById('resultadosClientesReservaCambio') ||
                            document.getElementById('resultadosClientesMesa');

                        if (!listaResultados) return;

                        if (clientesDisponiblesGlobal.length === 0) {
                            listaResultados.innerHTML =
                                '<div class="text-muted text-center py-2">No se encontraron clientes</div>';
                            return;
                        }

                        listaResultados.innerHTML = clientesDisponiblesGlobal.map(cliente => `
                        <div class="cliente-item-cambio p-2 border-bottom" style="cursor: pointer; transition: background-color 0.2s;"
                             data-cliente-id="${cliente.id}"
                             onmouseover="this.style.backgroundColor='#f0f0f0'"
                             onmouseout="this.style.backgroundColor='white'">
                            <div><strong>${cliente.name}</strong></div>
                            <div class="text-muted" style="font-size: 0.85em;">${cliente.email}</div>
                        </div>
                    `).join('');

                        document.querySelectorAll('.cliente-item-cambio').forEach(item => {
                            item.addEventListener('click', function() {
                                const clienteId = this.getAttribute('data-cliente-id');
                                const clienteNombre = this.querySelector('strong')
                                    .textContent;
                                seleccionarClienteCambio(clienteId, clienteNombre);
                            });
                        });
                    };

                    function seleccionarClienteCambio(id, nombre) {
                        clienteSeleccionadoCambioId = id;

                        const inputBusqueda = document.getElementById('busquedaClienteCambio') ||
                            document.getElementById('busquedaClienteReservaCambio') ||
                            document.getElementById('busquedaClienteMesa');
                        const listaResultados = document.getElementById('resultadosClientesCambio') ||
                            document.getElementById('resultadosClientesReservaCambio') ||
                            document.getElementById('resultadosClientesMesa');

                        if (inputBusqueda) {
                            inputBusqueda.value = nombre;
                            inputBusqueda.style.borderColor = '#28a745';
                            inputBusqueda.style.backgroundColor = '#e8f5e9';
                        }

                        if (listaResultados) {
                            listaResultados.innerHTML =
                                '<div class="text-success text-center py-2"><i class="fa fa-check-circle"></i> Cliente seleccionado</div>';
                        }

                        const tipoActual = document.querySelector('input[name="tipoEntregaCambio"]:checked')
                            .value;

                        // Mostrar botón de limpiar para mesa
                        if (tipoActual === 'mesa') {
                            const btnLimpiarCliente = document.getElementById('limpiarClienteMesa');
                            if (btnLimpiarCliente) {
                                btnLimpiarCliente.style.display = 'inline-block';
                            }
                        }

                        if (tipoActual === 'delivery') {
                            confirmBtn.disabled = false;
                        } else if (tipoActual === 'reserva' && typeof verificarFormularioReservaCambio ===
                            'function') {
                            verificarFormularioReservaCambio();
                        }
                        // Para mesa, el cliente es opcional, no afecta el estado del botón
                    }

                    function buscarClientesCambio(termino) {
                        clearTimeout(debounceTimerCambio);

                        if (termino.length < 2) {
                            clientesDisponiblesGlobal = [];
                            actualizarListaClientesCambio();
                            return;
                        }

                        debounceTimerCambio = setTimeout(() => {
                            @this.set('user', termino);
                            @this.call('buscarClientes');
                        }, 500);
                    }

                    window.seleccionarClienteCambio = seleccionarClienteCambio;
                    window.buscarClientesCambio = buscarClientesCambio;
                },
                preConfirm: () => {
                    const tipoSeleccionado = document.querySelector('input[name="tipoEntregaCambio"]:checked')
                        .value;
                    let resultado = {
                        tipo: tipoSeleccionado
                    };

                    switch (tipoSeleccionado) {
                        case 'mesa':
                            if (!mesaSeleccionadaId) {
                                Swal.showValidationMessage('Debe seleccionar una mesa');
                                return false;
                            }
                            resultado.mesaId = mesaSeleccionadaId;
                            // Cliente opcional para mesa
                            if (clienteSeleccionadoCambioId) {
                                resultado.clienteId = clienteSeleccionadoCambioId;
                            }
                            break;

                        case 'delivery':
                            if (!clienteSeleccionadoCambioId) {
                                Swal.showValidationMessage('Debe seleccionar un cliente para delivery');
                                return false;
                            }
                            resultado.clienteId = clienteSeleccionadoCambioId;
                            break;

                        case 'reserva':
                            // Validar cliente (OBLIGATORIO para reservas)
                            if (!clienteSeleccionadoCambioId) {
                                Swal.showValidationMessage('Debe seleccionar un cliente para la reserva');
                                return false;
                            }

                            const tipoEntregaReservaSelect = document.getElementById('tipoEntregaReserva');
                            if (!tipoEntregaReservaSelect || !tipoEntregaReservaSelect.value) {
                                Swal.showValidationMessage('Debe seleccionar el tipo de entrega');
                                return false;
                            }

                            const tipoEntregaReserva = tipoEntregaReservaSelect.value;

                            // Validar mesa si el tipo es mesa
                            if (tipoEntregaReserva === 'mesa' && !mesaSeleccionadaId) {
                                Swal.showValidationMessage('Debe seleccionar una mesa');
                                return false;
                            }

                            const selectHora = document.getElementById('horaReservaCambio');
                            if (!selectHora || !selectHora.value) {
                                Swal.showValidationMessage('Debe seleccionar una hora');
                                return false;
                            }

                            const fechaSeleccionada = document.querySelector(
                                'input[name="fechaReservaCambio"]:checked').value;
                            const hora = selectHora.value;

                            const ahora = new Date();
                            let fechaReserva = new Date();

                            if (fechaSeleccionada === 'manana') {
                                fechaReserva.setDate(fechaReserva.getDate() + 1);
                            }

                            const [horas, minutos] = hora.split(':');
                            fechaReserva.setHours(parseInt(horas), parseInt(minutos), 0, 0);

                            const fechaHoraFormateada = fechaReserva.getFullYear() + '-' +
                                String(fechaReserva.getMonth() + 1).padStart(2, '0') + '-' +
                                String(fechaReserva.getDate()).padStart(2, '0') + ' ' +
                                String(fechaReserva.getHours()).padStart(2, '0') + ':' +
                                String(fechaReserva.getMinutes()).padStart(2, '0') + ':00';

                            // El tipo real es el seleccionado en el select
                            resultado.tipo = tipoEntregaReserva;
                            resultado.esReserva = true;
                            resultado.clienteId = clienteSeleccionadoCambioId; // Cliente SIEMPRE incluido

                            // Agregar mesa solo si el tipo es mesa
                            if (tipoEntregaReserva === 'mesa') {
                                resultado.mesaId = mesaSeleccionadaId;
                            }

                            resultado.fechaHora = fechaHoraFormateada;
                            break;

                        case 'recoger':
                            // No requiere validaciones adicionales
                            break;
                    }

                    return resultado;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('cambiarTipoEntregaVenta',
                        result.value.tipo,
                        result.value.mesaId || null,
                        result.value.clienteId || null,
                        result.value.fechaHora || null
                    );
                }
                // Limpiar variables
                @this.set('user', '');
            });
        }

        // Estilos adicionales
        const style = document.createElement('style');
        style.textContent = `
        .mesa-ocupada-cambio {
            opacity: 0.5;
            filter: grayscale(50%);
        }
        .mesa-actual {
            background-color: #d4edda !important;
        }
        .mesa-item-cambio {
            transition: all 0.2s ease;
        }
        
        /* Estilos para SweetAlert con fondo blanco */
        .swal-fondo-blanco {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-title {
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-html-container {
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-content {
            color: #000000 !important;
        }
        .swal-fondo-blanco label,
        .swal-fondo-blanco .form-label {
            color: #000000 !important;
        }
    `;
        document.head.appendChild(style);
    </script>


@push('scripts')
    <style>
        /* Estilos para mesas disponibles */
        .mesa-disponible:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .mesa-disponible:active {
            transform: scale(0.95);
        }

        .mesa-icon {
            transition: transform 0.3s ease;
            position: relative;
        }

        .mesa-disponible:hover .mesa-icon {
            transform: rotate(5deg);
        }

        /* Estilos para mesas ocupadas */
        .mesa-ocupada {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
            position: relative;
            border-color: #dc3545 !important;
        }

        .mesa-ocupada::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(45deg,
                    transparent,
                    transparent 2px,
                    rgba(220, 53, 69, 0.1) 2px,
                    rgba(220, 53, 69, 0.1) 4px);
            pointer-events: none;
        }

        .mesa-ocupada:hover {
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            border-color: #dc3545 !important;
        }

        .mesa-ocupada .mesa-icon {
            transition: transform 0.3s ease;
        }

        .mesa-ocupada:hover .mesa-icon {
            transform: rotate(5deg);
        }

        .mesa-ocupada-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(220, 53, 69, 0.9);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
            border: 2px solid white;
        }

        .mesa-ocupada-overlay i {
            color: white !important;
        }

        .mesa-ocupada .mesa-numero {
            color: #6c757d !important;
            font-weight: normal;
        }

        /* Indicador de estado en la esquina */
        .mesa-ocupada::after {
            content: 'OCUPADA - CLICK PARA VER';
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #dc3545;
            color: white;
            font-size: 7px;
            font-weight: bold;
            padding: 3px 5px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Separador vertical personalizado */
        .vertical-divider {
            width: 2px;
            height: 100%;
            background-color: #dee2e6;
            opacity: 0.6;
            border-radius: 1px;
        }

        /* Animación para el ícono de timer */
        @keyframes faa-flash {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            25% {
                opacity: 0.6;
                transform: scale(0.9);
            }

            50% {
                opacity: 1;
                transform: scale(1.1);
            }

            75% {
                opacity: 0.6;
                transform: scale(0.9);
            }
        }

        .faa-flash.animated {
            animation: faa-flash 2s ease infinite;
            display: inline-block;
        }
    </style>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('cerrarModal', function(data) {
                const modal = document.getElementById(data.modalId);
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        });

        let debounceTimer;
        let clientesDisponibles = [];
        window.mesasDisponiblesGlobal = [];
        window.mesaSeleccionadaModalGlobal = null;

        // Escuchar resultados de búsqueda de clientes
        window.addEventListener('clientesEncontrados', event => {
            clientesDisponibles = event.detail.clientes;
            actualizarListaClientes();
        });

        // Escuchar mesas disponibles
        window.addEventListener('mesasDisponibles', event => {
            window.mesasDisponiblesGlobal = event.detail.mesas;
        });

        // Función para actualizar la lista de clientes en el SweetAlert
        function actualizarListaClientes() {
            const inputBusqueda = document.getElementById('busquedaCliente');
            const listaResultados = document.getElementById('resultadosClientes');

            if (!listaResultados) return;

            if (clientesDisponibles.length === 0) {
                listaResultados.innerHTML = '<div class="text-muted text-center py-2">No se encontraron clientes</div>';
                return;
            }

            listaResultados.innerHTML = clientesDisponibles.map(cliente => `
            <div class="cliente-item p-2 border-bottom" style="cursor: pointer; transition: background-color 0.2s;"
                 data-cliente-id="${cliente.id}"
                 onmouseover="this.style.backgroundColor='#f0f0f0'"
                 onmouseout="this.style.backgroundColor='white'">
                <div><strong>${cliente.name}</strong></div>
                <div class="text-muted" style="font-size: 0.85em;">${cliente.email}</div>
            </div>
        `).join('');

            // Agregar eventos click a cada cliente
            document.querySelectorAll('.cliente-item').forEach(item => {
                item.addEventListener('click', function() {
                    const clienteId = this.getAttribute('data-cliente-id');
                    const clienteNombre = this.querySelector('strong').textContent;
                    seleccionarCliente(clienteId, clienteNombre);
                });
            });
        }

        let clienteSeleccionadoId = null;
        let clienteSeleccionadoNombre = '';

        function seleccionarCliente(id, nombre) {
            clienteSeleccionadoId = id;
            clienteSeleccionadoNombre = nombre;

            const inputBusqueda = document.getElementById('busquedaCliente');
            const listaResultados = document.getElementById('resultadosClientes');
            const confirmBtn = document.querySelector('.swal2-confirm');

            if (inputBusqueda) {
                inputBusqueda.value = nombre;
                inputBusqueda.style.borderColor = '#28a745';
                inputBusqueda.style.backgroundColor = '#e8f5e9';
            }

            if (listaResultados) {
                listaResultados.innerHTML =
                    '<div class="text-success text-center py-2"><i class="fa fa-check-circle"></i> Cliente seleccionado</div>';
            }

            if (confirmBtn) {
                confirmBtn.disabled = false;
            }
        }

        function buscarClientesConDebounce(termino) {
            clearTimeout(debounceTimer);

            if (termino.length < 2) {
                clientesDisponibles = [];
                actualizarListaClientes();
                return;
            }

            debounceTimer = setTimeout(() => {
                @this.set('user', termino);
                @this.call('buscarClientes');
            }, 500);
        }

        // SweetAlert para Delivery
        function confirmarVentaDelivery() {
            clienteSeleccionadoId = null;
            clienteSeleccionadoNombre = '';
            clientesDisponibles = [];

            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                title: '<i class="fa fa-truck"></i> Venta Delivery',
                html: `
                <div class="text-start">
                    <label class="form-label fw-bold">Buscar Cliente:</label>
                    <input type="text" id="busquedaCliente" class="form-control mb-2" 
                           placeholder="Escribe el nombre o email del cliente..."
                           autocomplete="off">
                    <div id="resultadosClientes" class="border rounded" style="max-height: 250px; overflow-y: auto; background-color: white;">
                        <div class="text-muted text-center py-3">Escribe para buscar clientes...</div>
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Crear Venta Delivery',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#dc3545',
                width: '500px',
                didOpen: () => {
                    const confirmBtn = document.querySelector('.swal2-confirm');
                    confirmBtn.disabled = true;

                    const inputBusqueda = document.getElementById('busquedaCliente');
                    inputBusqueda.focus();

                    inputBusqueda.addEventListener('input', function() {
                        const termino = this.value;
                        this.style.borderColor = '';
                        this.style.backgroundColor = '';
                        clienteSeleccionadoId = null;
                        confirmBtn.disabled = true;
                        buscarClientesConDebounce(termino);
                    });
                },
                preConfirm: () => {
                    if (!clienteSeleccionadoId) {
                        Swal.showValidationMessage('Debe seleccionar un cliente');
                        return false;
                    }
                    return clienteSeleccionadoId;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('crearVentaDelivery', clienteSeleccionadoId);
                }
                // Limpiar la variable de búsqueda
                @this.set('user', '');
            });
        }

        // Función para generar opciones de hora
        function generarOpcionesHora(horaInicio, horaFin) {
            const opciones = [];
            let hora = horaInicio;

            while (hora <= horaFin) {
                const horas = Math.floor(hora);
                const minutos = (hora % 1) * 60;
                const horaStr = String(horas).padStart(2, '0') + ':' + String(minutos).padStart(2, '0');

                // Formato 12 horas
                let hora12 = horas;
                let ampm = 'AM';
                if (horas >= 12) {
                    ampm = 'PM';
                    if (horas > 12) hora12 = horas - 12;
                }
                if (hora12 === 0) hora12 = 12;

                const horaDisplay = String(hora12).padStart(2, '0') + ':' + String(minutos).padStart(2, '0') + ' ' + ampm;

                opciones.push({
                    valor: horaStr,
                    display: horaDisplay
                });
                hora += 0.5; // Incremento de 30 minutos
            }

            return opciones;
        }

        // SweetAlert para Reserva
        function confirmarVentaReserva() {
            clienteSeleccionadoId = null;
            clienteSeleccionadoNombre = '';
            clientesDisponibles = [];

            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                title: '<i class="flaticon-088-time"></i> Reserva',
                html: `
                <div class="text-start">
                    <label class="form-label fw-bold">Buscar Cliente (Obligatorio):</label>
                    <input type="text" id="busquedaClienteReserva" class="form-control mb-3" 
                           placeholder="Escribe el nombre o email del cliente..."
                           autocomplete="off">
                    <div id="resultadosClientesReserva" class="border rounded mb-3" style="max-height: 150px; overflow-y: auto; background-color: white;">
                        <div class="text-muted text-center py-2">Escribe para buscar clientes...</div>
                    </div>
                    
                    <label class="form-label fw-bold">Tipo de Entrega (Obligatorio):</label>
                    <select id="tipoEntregaReservaModal" class="form-select mb-3">
                        <option value="">Selecciona el tipo de entrega...</option>
                        <option value="mesa">Mesa</option>
                        <option value="recoger">Para Recoger</option>
                        <option value="delivery">Delivery</option>
                    </select>
                    
                    <div id="camposRequeridosModal"></div>
                    
                    <div class="border-top pt-3 mt-2">
                        <label class="form-label fw-bold">Fecha de Reserva:</label>
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="fechaReserva" id="radioHoy" value="hoy" checked>
                            <label class="btn btn-outline-primary" for="radioHoy">
                                <i class="fa fa-calendar-day"></i> Hoy
                            </label>
                            
                            <input type="radio" class="btn-check" name="fechaReserva" id="radioManana" value="manana">
                            <label class="btn btn-outline-primary" for="radioManana">
                                <i class="fa fa-calendar-plus"></i> Mañana
                            </label>
                        </div>
                        
                        <label class="form-label fw-bold">Hora de Reserva:</label>
                        <select id="horaReserva" class="form-select" disabled>
                            <option value="">Primero selecciona una fecha</option>
                        </select>
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Crear Reserva',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#dc3545',
                width: '550px',
                didOpen: () => {
                    const confirmBtn = document.querySelector('.swal2-confirm');
                    confirmBtn.disabled = true;

                    const tipoEntregaSelect = document.getElementById('tipoEntregaReservaModal');
                    const camposRequeridos = document.getElementById('camposRequeridosModal');
                    const selectHora = document.getElementById('horaReserva');
                    const radioHoy = document.getElementById('radioHoy');
                    const radioManana = document.getElementById('radioManana');
                    const inputBusquedaCliente = document.getElementById('busquedaClienteReserva');

                    let mesaSeleccionadaModal = null;
                    let tipoEntregaModalSeleccionado = '';

                    // Inicializar búsqueda de cliente
                    if (inputBusquedaCliente) {
                        inputBusquedaCliente.focus();
                        inputBusquedaCliente.addEventListener('input', function() {
                            this.style.borderColor = '';
                            this.style.backgroundColor = '';
                            clienteSeleccionadoId = null;
                            confirmBtn.disabled = true;
                            buscarClientesConDebounceReserva(this.value);
                        });
                    }

                    tipoEntregaSelect.addEventListener('change', function() {
                        tipoEntregaModalSeleccionado = this.value;
                        mesaSeleccionadaModal = null;

                        switch (this.value) {
                            case 'mesa':
                                camposRequeridos.innerHTML = `
                                <label class="form-label fw-bold">Selecciona la Mesa:</label>
                                <div id="listaMesasModal" class="border rounded p-2 mb-3" style="max-height: 250px; overflow-y: auto; background-color: white;">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                        <div>Cargando mesas...</div>
                                    </div>
                                </div>
                            `;
                                @this.call('obtenerMesasDisponibles');
                                break;

                            case 'delivery':
                                camposRequeridos.innerHTML = `
                                <div class="alert alert-info mb-3">
                                    <i class="fa fa-info-circle"></i> Delivery - La entrega será enviada al cliente seleccionado.
                                </div>
                            `;
                                break;

                            case 'recoger':
                                camposRequeridos.innerHTML = `
                                <div class="alert alert-info mb-3">
                                    <i class="fa fa-info-circle"></i> Reserva para recoger en local.
                                </div>
                            `;
                                break;

                            default:
                                camposRequeridos.innerHTML = '';
                        }

                        verificarFormularioReserva();
                    });

                    // Listener global de mesas para actualizar cuando se carguen
                    window.addEventListener('mesasDisponibles', function(event) {
                        if (document.getElementById('listaMesasModal') &&
                            tipoEntregaModalSeleccionado === 'mesa') {
                            actualizarListaMesasModal();
                        }
                    });

                    window.actualizarListaMesasModal = function() {
                        const listaMesas = document.getElementById('listaMesasModal');
                        if (!listaMesas) return;

                        const mesasData = window.mesasDisponiblesGlobal || [];

                        if (mesasData.length === 0) {
                            listaMesas.innerHTML =
                                '<div class="text-muted text-center py-2">No hay mesas disponibles</div>';
                            return;
                        }

                        listaMesas.innerHTML = '<div class="row g-2">' +
                            mesasData.map(mesa => `
                            <div class="col-4">
                                <div class="mesa-item-modal card text-center p-2 ${mesa.ocupada ? 'mesa-ocupada-cambio' : ''}" 
                                     data-mesa-id="${mesa.id}"
                                     style="cursor: ${mesa.ocupada ? 'not-allowed' : 'pointer'}; border: 2px solid ${mesa.ocupada ? '#dc3545' : '#e9ecef'};">
                                    <i class="fa fa-table fa-2x ${mesa.ocupada ? 'text-danger' : 'text-primary'}"></i>
                                    <div><strong>Mesa ${mesa.numero}</strong></div>
                                    ${mesa.capacidad ? `<small class="text-muted">${mesa.capacidad} personas</small>` : ''}
                                    ${mesa.ocupada ? '<div class="badge badge-danger mt-1">Ocupada</div>' : '<div class="badge badge-success mt-1">Disponible</div>'}
                                </div>
                            </div>
                        `).join('') +
                            '</div>';

                        document.querySelectorAll('.mesa-item-modal').forEach(item => {
                            const mesaId = parseInt(item.getAttribute('data-mesa-id'));
                            const mesa = mesasData.find(m => m.id === mesaId);

                            if (!mesa.ocupada) {
                                item.addEventListener('click', function() {
                                    document.querySelectorAll('.mesa-item-modal').forEach(
                                        m => {
                                            m.style.borderColor = '#e9ecef';
                                            m.style.backgroundColor = '';
                                        });

                                    this.style.borderColor = '#007bff';
                                    this.style.backgroundColor = '#e7f3ff';
                                    mesaSeleccionadaModal = mesaId;
                                    window.mesaSeleccionadaModalGlobal = mesaId;
                                    verificarFormularioReserva();
                                });

                                item.addEventListener('mouseover', function() {
                                    this.style.transform = 'scale(1.05)';
                                });

                                item.addEventListener('mouseout', function() {
                                    this.style.transform = 'scale(1)';
                                });
                            }
                        });
                    };

                    // Función para actualizar lista de clientes (reserva)
                    window.actualizarListaClientesReserva = function() {
                        const listaResultados = document.getElementById('resultadosClientesReserva');
                        if (!listaResultados) return;

                        if (clientesDisponibles.length === 0) {
                            listaResultados.innerHTML =
                                '<div class="text-muted text-center py-2">No se encontraron clientes</div>';
                            return;
                        }

                        listaResultados.innerHTML = clientesDisponibles.map(cliente => `
                        <div class="cliente-item-reserva p-2 border-bottom" style="cursor: pointer; transition: background-color 0.2s;"
                             data-cliente-id="${cliente.id}"
                             onmouseover="this.style.backgroundColor='#f0f0f0'"
                             onmouseout="this.style.backgroundColor='white'">
                            <div><strong>${cliente.name}</strong></div>
                            <div class="text-muted" style="font-size: 0.85em;">${cliente.email}</div>
                        </div>
                    `).join('');

                        document.querySelectorAll('.cliente-item-reserva').forEach(item => {
                            item.addEventListener('click', function() {
                                const clienteId = this.getAttribute('data-cliente-id');
                                const clienteNombre = this.querySelector('strong')
                                    .textContent;
                                seleccionarClienteReserva(clienteId, clienteNombre);
                            });
                        });
                    };

                    function seleccionarClienteReserva(id, nombre) {
                        clienteSeleccionadoId = id;
                        clienteSeleccionadoNombre = nombre;

                        const inputBusqueda = document.getElementById('busquedaClienteReserva');
                        const listaResultados = document.getElementById('resultadosClientesReserva');

                        if (inputBusqueda) {
                            inputBusqueda.value = nombre;
                            inputBusqueda.style.borderColor = '#28a745';
                            inputBusqueda.style.backgroundColor = '#e8f5e9';
                        }

                        if (listaResultados) {
                            listaResultados.innerHTML =
                                '<div class="text-success text-center py-2"><i class="fa fa-check-circle"></i> Cliente seleccionado</div>';
                        }

                        verificarFormularioReserva();
                    }

                    function buscarClientesConDebounceReserva(termino) {
                        clearTimeout(debounceTimer);

                        if (termino.length < 2) {
                            clientesDisponibles = [];
                            actualizarListaClientesReserva();
                            return;
                        }

                        debounceTimer = setTimeout(() => {
                            @this.set('user', termino);
                            @this.call('buscarClientes');
                        }, 500);
                    }

                    // Escuchar resultados de búsqueda
                    window.addEventListener('clientesEncontrados', event => {
                        clientesDisponibles = event.detail.clientes;
                        if (typeof actualizarListaClientesReserva === 'function') {
                            actualizarListaClientesReserva();
                        }
                    });

                    // Cambio de fecha
                    function actualizarOpcionesHora() {
                        const fechaSeleccionada = document.querySelector('input[name="fechaReserva"]:checked')
                            .value;
                        selectHora.disabled = false;
                        selectHora.innerHTML = '<option value="">Selecciona una hora</option>';

                        const ahora = new Date();
                        const horaActual = ahora.getHours() + ahora.getMinutes() / 60;

                        let opciones = [];

                        if (fechaSeleccionada === 'hoy') {
                            // Desde la hora actual hasta las 22:00
                            const horaInicio = Math.ceil(horaActual * 2) /
                            2; // Redondear a la próxima media hora
                            opciones = generarOpcionesHora(horaInicio, 22);
                        } else {
                            // Mañana: de 8:00 AM a 10:00 PM
                            opciones = generarOpcionesHora(8, 22);
                        }

                        if (opciones.length === 0) {
                            selectHora.innerHTML =
                                '<option value="">No hay horarios disponibles para hoy</option>';
                            selectHora.disabled = true;
                        } else {
                            opciones.forEach(opcion => {
                                const option = document.createElement('option');
                                option.value = opcion.valor;
                                option.textContent = opcion.display;
                                selectHora.appendChild(option);
                            });
                        }

                        verificarFormularioReserva();
                    }

                    radioHoy.addEventListener('change', actualizarOpcionesHora);
                    radioManana.addEventListener('change', actualizarOpcionesHora);
                    selectHora.addEventListener('change', verificarFormularioReserva);

                    // Inicializar opciones de hora
                    actualizarOpcionesHora();

                    function verificarFormularioReserva() {
                        const tipoEntregaSelect = document.getElementById('tipoEntregaReservaModal');
                        const selectHora = document.getElementById('horaReserva');
                        const tieneHora = selectHora && selectHora.value !== '';
                        const tieneTipoEntrega = tipoEntregaSelect && tipoEntregaSelect.value !== '';
                        const tieneCliente = clienteSeleccionadoId !== null;

                        // El cliente es SIEMPRE obligatorio para reservas
                        if (!tieneCliente || !tieneTipoEntrega || !tieneHora) {
                            confirmBtn.disabled = true;
                            return;
                        }

                        let cumpleRequisitos = false;

                        switch (tipoEntregaSelect.value) {
                            case 'mesa':
                                cumpleRequisitos = (window.mesaSeleccionadaModalGlobal || null) !== null;
                                break;
                            case 'delivery':
                            case 'recoger':
                                cumpleRequisitos = true;
                                break;
                        }

                        confirmBtn.disabled = !cumpleRequisitos;
                    }
                },
                preConfirm: () => {
                    // Validar cliente (OBLIGATORIO para reservas)
                    if (!clienteSeleccionadoId) {
                        Swal.showValidationMessage('Debe seleccionar un cliente para la reserva');
                        return false;
                    }

                    const tipoEntregaReservaSelect = document.getElementById('tipoEntregaReservaModal');
                    if (!tipoEntregaReservaSelect || !tipoEntregaReservaSelect.value) {
                        Swal.showValidationMessage('Debe seleccionar el tipo de entrega');
                        return false;
                    }

                    const tipoEntregaReserva = tipoEntregaReservaSelect.value;
                    const mesaSeleccionadaModal = window.mesaSeleccionadaModalGlobal || null;

                    // Validar mesa si el tipo es mesa
                    if (tipoEntregaReserva === 'mesa' && !mesaSeleccionadaModal) {
                        Swal.showValidationMessage('Debe seleccionar una mesa');
                        return false;
                    }

                    const selectHora = document.getElementById('horaReserva');
                    if (!selectHora || !selectHora.value) {
                        Swal.showValidationMessage('Debe seleccionar una hora');
                        return false;
                    }

                    const fechaSeleccionada = document.querySelector('input[name="fechaReserva"]:checked')
                    .value;
                    const hora = selectHora.value;

                    // Calcular la fecha y hora completa
                    const ahora = new Date();
                    let fechaReserva = new Date();

                    if (fechaSeleccionada === 'manana') {
                        fechaReserva.setDate(fechaReserva.getDate() + 1);
                    }

                    const [horas, minutos] = hora.split(':');
                    fechaReserva.setHours(parseInt(horas), parseInt(minutos), 0, 0);

                    // Formato: YYYY-MM-DD HH:MM:SS para MySQL
                    const fechaHoraFormateada = fechaReserva.getFullYear() + '-' +
                        String(fechaReserva.getMonth() + 1).padStart(2, '0') + '-' +
                        String(fechaReserva.getDate()).padStart(2, '0') + ' ' +
                        String(fechaReserva.getHours()).padStart(2, '0') + ':' +
                        String(fechaReserva.getMinutes()).padStart(2, '0') + ':00';

                    let resultado = {
                        tipoEntrega: tipoEntregaReserva,
                        fechaHora: fechaHoraFormateada,
                        clienteId: clienteSeleccionadoId // Cliente SIEMPRE incluido
                    };

                    // Agregar mesa solo si el tipo es mesa
                    if (tipoEntregaReserva === 'mesa') {
                        resultado.mesaId = mesaSeleccionadaModal;
                    }

                    return resultado;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('crearVentaReserva',
                        result.value.tipoEntrega,
                        result.value.mesaId || null,
                        result.value.clienteId || null,
                        result.value.fechaHora
                    );
                }
                // Limpiar variables globales
                @this.set('user', '');
                window.mesaSeleccionadaModalGlobal = null;
            });
        }

        // Estilos para SweetAlert con fondo blanco
        const styleSwals = document.createElement('style');
        styleSwals.textContent = `
        .swal-fondo-blanco {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-title {
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-html-container {
            color: #000000 !important;
        }
        .swal-fondo-blanco .swal2-content {
            color: #000000 !important;
        }
        .swal-fondo-blanco label,
        .swal-fondo-blanco .form-label {
            color: #000000 !important;
        }
    `;
        document.head.appendChild(styleSwals);

        // Función para actualizar los temporizadores de reserva
        function actualizarTemporizadores() {
            // Actualizar temporizadores normales y del modal
            document.querySelectorAll('[id^="timer-"]').forEach(timer => {
                const reservadoAt = timer.getAttribute('data-reservado');
                if (!reservadoAt) return;

                const now = new Date();
                const reservado = new Date(reservadoAt.replace(' ', 'T'));
                const diffInMs = reservado - now;
                const diffInSeconds = Math.floor(diffInMs / 1000);

                if (diffInSeconds <= 0) {
                    // Tiempo cumplido
                    timer.textContent = 'Tiempo cumplido';
                    timer.className = 'badge badge-sm bg-danger';
                } else {
                    // Calcular horas, minutos y segundos
                    const hours = Math.floor(diffInSeconds / 3600);
                    const minutes = Math.floor((diffInSeconds % 3600) / 60);
                    const seconds = diffInSeconds % 60;

                    // Actualizar el texto del temporizador
                    timer.textContent = hours + 'h ' + minutes + 'm ' + seconds + 's';

                    // Determinar color según el tiempo restante
                    let colorClass = '';
                    if (diffInSeconds > 10800) { // 3 horas
                        colorClass = 'bg-dark';
                    } else if (diffInSeconds > 1800) { // 30 minutos
                        colorClass = 'bg-warning';
                    } else {
                        colorClass = 'bg-danger';
                    }

                    // Actualizar clase del badge
                    timer.className = 'badge badge-sm ' + colorClass;
                }
            });
        }

        // Actualizar cada segundo
        setInterval(actualizarTemporizadores, 1000);

        // Actualizar inmediatamente al cargar
        document.addEventListener('DOMContentLoaded', actualizarTemporizadores);

        // Actualizar después de que Livewire actualice el DOM
        document.addEventListener('livewire:load', function() {
            actualizarTemporizadores();

            Livewire.hook('message.processed', (message, component) => {
                setTimeout(actualizarTemporizadores, 100);
            });
        });
    </script>
@endpush
