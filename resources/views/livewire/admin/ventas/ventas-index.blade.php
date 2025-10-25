<div class="row m-0 p-0">
    @include('livewire.admin.ventas.includes-pos.card-ventas-pendientes')
    @isset($cuenta)
        @include('livewire.admin.ventas.includes-pos.card-detalle-cuenta')

        @include('livewire.admin.ventas.includes-pos.card-buscador-productos')
    @endisset
    @include('livewire.admin.ventas.includes-pos.modales')

</div>
@push('css')
    <script src="{{ asset('js/adicionales-sweetalert.js') }}?v=1"></script>
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
                allowOutsideClick: false,
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
                allowOutsideClick: false,
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
                allowOutsideClick: false,
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
                allowOutsideClick: false,
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

        // Configuración de países y sus requisitos de teléfono
        const paisesTelefono = {
            '+591': {
                nombre: 'Bolivia',
                digitos: 8,
                bandera: '🇧🇴'
            },
            '+54': {
                nombre: 'Argentina',
                digitos: 10,
                bandera: '🇦🇷'
            },
            '+55': {
                nombre: 'Brasil',
                digitos: 11,
                bandera: '🇧🇷'
            },
            '+56': {
                nombre: 'Chile',
                digitos: 9,
                bandera: '🇨🇱'
            },
            '+57': {
                nombre: 'Colombia',
                digitos: 10,
                bandera: '🇨🇴'
            },
            '+593': {
                nombre: 'Ecuador',
                digitos: 9,
                bandera: '🇪🇨'
            },
            '+51': {
                nombre: 'Perú',
                digitos: 9,
                bandera: '🇵🇪'
            },
            '+595': {
                nombre: 'Paraguay',
                digitos: 9,
                bandera: '🇵🇾'
            },
            '+598': {
                nombre: 'Uruguay',
                digitos: 8,
                bandera: '🇺🇾'
            },
            '+58': {
                nombre: 'Venezuela',
                digitos: 10,
                bandera: '🇻🇪'
            },
            '+52': {
                nombre: 'México',
                digitos: 10,
                bandera: '🇲🇽'
            },
            '+1': {
                nombre: 'USA/Canadá',
                digitos: 10,
                bandera: '🇺🇸'
            },
            '+34': {
                nombre: 'España',
                digitos: 9,
                bandera: '🇪🇸'
            }
        };

        // Función para crear usuario rápido
        function crearUsuarioRapido(asignarACuenta = true, nombre = '', codigoPais = '+591', telefono = '') {
            // Generar opciones del select de países
            const opcionesPaises = Object.keys(paisesTelefono).map(codigo => {
                const pais = paisesTelefono[codigo];
                const selected = codigo === codigoPais ? 'selected' : '';
                return `<option value="${codigo}" ${selected}>${pais.bandera} ${codigo}</option>`;
            }).join('');

            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                allowOutsideClick: false,
                title: '<i class="fa fa-user-plus"></i> Crear Cliente Rápido',
                html: `
                <div class="text-start">
                    <label class="form-label fw-bold">Nombre Completo: <span class="text-danger">*</span></label>
                    <input type="text" id="nombreClienteRapido" class="form-control mb-3" 
                           placeholder="Ej: Juan Pérez García" value="${nombre}"
                           autocomplete="off">
                    
                    <label class="form-label fw-bold">Teléfono: <span class="text-danger">*</span></label>
                    <div class="input-group mb-2">
                        <select id="codigoPaisClienteRapido" class="form-select" style="max-width: 200px;">
                            ${opcionesPaises}
                        </select>
                        <input type="tel" id="telefonoClienteRapido" class="form-control" 
                               placeholder="71234567" value="${telefono}"
                               autocomplete="off">
                    </div>
                    <small class="text-muted mb-3 d-block" id="infoDigitos">
                        <i class="fa fa-info-circle"></i> Debe tener 8 dígitos
                    </small>
                    
                    <small class="text-muted">
                        <i class="fa fa-envelope"></i> El correo se generará automáticamente
                    </small>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-save"></i> Crear Cliente',
                cancelButtonText: '<i class="fa fa-times"></i> Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                width: '550px',
                didOpen: () => {
                    const nombreInput = document.getElementById('nombreClienteRapido');
                    const telefonoInput = document.getElementById('telefonoClienteRapido');
                    const codigoPaisSelect = document.getElementById('codigoPaisClienteRapido');
                    const infoDigitos = document.getElementById('infoDigitos');

                    // Función para actualizar el placeholder y la info según el país
                    function actualizarInfoPais() {
                        const codigoPais = codigoPaisSelect.value;
                        const pais = paisesTelefono[codigoPais];

                        telefonoInput.setAttribute('maxlength', pais.digitos);
                        telefonoInput.placeholder = '7'.repeat(pais.digitos).substring(0, pais.digitos);
                        infoDigitos.innerHTML =
                            `<i class="fa fa-info-circle"></i> Debe tener exactamente ${pais.digitos} dígitos (${pais.nombre})`;

                        // Limpiar el input si cambia de país
                        telefonoInput.value = '';
                    }

                    // Actualizar info inicial
                    actualizarInfoPais();

                    // Listener para cambio de país
                    codigoPaisSelect.addEventListener('change', actualizarInfoPais);

                    // Focus en el primer campo
                    nombreInput.focus();

                    // Validar que el teléfono solo contenga números
                    telefonoInput.addEventListener('input', function(e) {
                        this.value = this.value.replace(/[^0-9]/g, '');

                        // Validación visual en tiempo real
                        const codigoPais = codigoPaisSelect.value;
                        const pais = paisesTelefono[codigoPais];

                        if (this.value.length > 0) {
                            if (this.value.length === pais.digitos) {
                                this.style.borderColor = '#28a745';
                                this.style.backgroundColor = '#f0fff4';
                            } else {
                                this.style.borderColor = '#ffc107';
                                this.style.backgroundColor = '#fff9e6';
                            }
                        } else {
                            this.style.borderColor = '';
                            this.style.backgroundColor = '';
                        }
                    });

                    // Permitir enviar con Enter
                    [nombreInput, telefonoInput].forEach(input => {
                        input.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                Swal.clickConfirm();
                            }
                        });
                    });
                },
                preConfirm: () => {
                    const nombre = document.getElementById('nombreClienteRapido').value.trim();
                    const codigoPais = document.getElementById('codigoPaisClienteRapido').value;
                    const telefono = document.getElementById('telefonoClienteRapido').value.trim();
                    const pais = paisesTelefono[codigoPais];

                    // Validaciones
                    if (!nombre) {
                        Swal.showValidationMessage('El nombre completo es obligatorio');
                        return false;
                    }

                    if (nombre.length < 3) {
                        Swal.showValidationMessage('El nombre debe tener al menos 3 caracteres');
                        return false;
                    }

                    if (!telefono) {
                        Swal.showValidationMessage('El teléfono es obligatorio');
                        return false;
                    }

                    if (!/^\d+$/.test(telefono)) {
                        Swal.showValidationMessage('El teléfono debe contener solo números');
                        return false;
                    }

                    if (telefono.length !== pais.digitos) {
                        Swal.showValidationMessage(
                            `El teléfono de ${pais.nombre} debe tener exactamente ${pais.digitos} dígitos`);
                        return false;
                    }

                    return {
                        nombre: nombre,
                        codigoPais: codigoPais,
                        telefono: telefono
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Creando cliente...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Llamar al método de Livewire
                    @this.call('crearClienteRapido', result.value.nombre, result.value.codigoPais, result.value
                            .telefono, asignarACuenta)
                        .then((response) => {
                            if (response != false) {
                                Swal.close();
                            } else {
                                setTimeout(() => {
                                    crearUsuarioRapido(asignarACuenta,result.value.nombre,result.value.codigoPais,result.value.telefono);
                                }, 2000);
                               
                            }
                        })
                }
            });
        }
        function seleccionar(id) {
            @this.call('seleccionar', id);
        }

        // Listener para mostrar detalle de items en POS
        window.addEventListener('verDetalleItemPOS', event => {
            const data = event.detail;
            mostrarDetalleItemsPOS(data);
        });

        function mostrarDetalleItemsPOS(data) {
            const {
                producto_venta_id,
                producto_nombre,
                producto_tiene_seccion,
                cantidad_total,
                observacion,
                items
            } = data;

            // Generar HTML para cada item
            let itemsHTML = '';
            items.forEach((item, index) => {
                // Lista de adicionales
                let adicionalesHTML = '';
                if (item.adicionales && item.adicionales.length > 0) {
                    adicionalesHTML = '<div class="mb-1" style="font-size: 11px; padding-left: 8px;">';
                    item.adicionales.forEach(adicional => {
                        adicionalesHTML += `<div class="text-black">• ${adicional}</div>`;
                    });
                    adicionalesHTML += '</div>';
                } else {
                    adicionalesHTML = '<div class="text-muted mb-1" style="font-size: 11px;"><em>Sin adicionales</em></div>';
                }

                // Estado del item (solo si el producto tiene sección)
                let estadoHTML = '';
                if (producto_tiene_seccion && item.estado) {
                    const estadoClass = {
                        'pendiente': 'badge-danger',
                        'preparacion': 'badge-warning',
                        'despachado': 'badge-success'
                    };
                    const estadoIcon = {
                        'pendiente': '⏳',
                        'preparacion': '🔥',
                        'despachado': '✅'
                    };

                    estadoHTML = `<span class="badge badge-sm ${estadoClass[item.estado]}" style="font-size: 10px;">${estadoIcon[item.estado]} ${item.estado.toUpperCase()}</span>`;
                }

                // Calcular tiempo transcurrido si existe
                let tiempoHTML = '';
                if (item.agregado_at) {
                    const agregado = new Date(item.agregado_at);
                    const ahora = new Date();
                    const diffMs = ahora - agregado;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHoras = Math.floor(diffMins / 60);

                    let tiempoTranscurrido = '';
                    if (diffHoras > 0) {
                        tiempoTranscurrido = `Hace ${diffHoras}h ${diffMins % 60}m`;
                    } else if (diffMins > 0) {
                        tiempoTranscurrido = `Hace ${diffMins}m`;
                    } else {
                        tiempoTranscurrido = 'Recién agregado';
                    }

                    // Formatear hora
                    const horaFormateada = agregado.toLocaleTimeString('es-BO', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    tiempoHTML = `
                        <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 10px;">
                            <span>📅 ${horaFormateada}</span>
                            <span class="fw-bold ${diffMins > 30 ? 'text-danger' : ''}">${tiempoTranscurrido}</span>
                        </div>
                    `;
                }

                // Botón de eliminar compacto (solo para items pendientes si tiene sección)
                let botonEliminar = '';
                const puedeEliminar = !producto_tiene_seccion || !item.estado || item.estado === 'pendiente';
                
                if (puedeEliminar) {
                    botonEliminar = `
                        <button type="button" 
                                class="btn btn-outline-danger btn-xs mt-1 py-0 px-2" 
                                style="font-size: 10px; line-height: 1.5;"
                                onclick="eliminarItemPOS(${producto_venta_id}, ${item.indice}, '${item.estado || 'pendiente'}', ${producto_tiene_seccion})">
                            <i class="fa fa-trash" style="font-size: 9px;"></i> Eliminar
                        </button>
                    `;
                } else {
                    botonEliminar = `
                        <div class="alert alert-warning p-1 mt-1 mb-0" style="font-size: 9px; line-height: 1.3;">
                            <i class="fa fa-lock"></i> No se puede eliminar (en ${item.estado})
                        </div>
                    `;
                }

                const borderColor = producto_tiene_seccion && item.estado
                    ? (item.estado === 'pendiente' ? '#dc3545' : item.estado === 'preparacion' ? '#ffc107' : '#28a745')
                    : '#6c757d';

                itemsHTML += `
                    <div class="card mb-2" style="border-left: 3px solid ${borderColor};">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong style="font-size: 12px;">Item ${item.indice}</strong>
                                ${estadoHTML}
                            </div>
                            ${tiempoHTML}
                            <div style="font-size: 11px; color: #666; margin-bottom: 2px;">Adicionales:</div>
                            ${adicionalesHTML}
                            ${botonEliminar}
                        </div>
                    </div>
                `;
            });

            // Agregar observación si existe
            let observacionHTML = '';
            if (observacion && observacion.trim() !== '') {
                observacionHTML = `
                    <div class="alert alert-warning mb-2 p-2" style="background-color: #fff3cd;">
                        <strong style="color: #856404; font-size: 11px;">📝 Observación:</strong>
                        <p class="mb-0 mt-1" style="color: #856404; font-size: 11px;">${observacion}</p>
                    </div>
                `;
            }

            Swal.fire({
                customClass: {
                    popup: 'swal-fondo-blanco'
                },
                allowOutsideClick: false,
                title: `<div style="font-size: 18px;">${producto_nombre}</div>`,
                html: `
                    <div class="text-start">
                        <div class="alert alert-info mb-2 p-2" style="font-size: 12px;">
                            <strong>Total de items:</strong> ${cantidad_total}
                        </div>
                        ${observacionHTML}
                        ${itemsHTML}
                    </div>
                `,
                width: '450px',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    container: 'detalle-items-modal-pos',
                    title: 'swal2-title-custom'
                }
            });
        }

        // Función global para eliminar item desde el SweetAlert
        window.eliminarItemPOS = function(producto_venta_id, indice, estado, producto_tiene_seccion) {
            // Validar que solo se puedan eliminar items pendientes si el producto tiene sección
            if (producto_tiene_seccion && estado && estado !== 'pendiente') {
                Swal.fire({
                    title: '<span style="font-size: 16px;">No se puede eliminar</span>',
                    html: `<p style="font-size: 13px; margin: 0;">Este item está en <strong>${estado}</strong> y ya está siendo preparado en cocina.<br>Solo se pueden eliminar items <strong>pendientes</strong>.</p>`,
                    icon: 'error',
                    confirmButtonText: '<span style="font-size: 13px;">Entendido</span>',
                    customClass: {
                        confirmButton: 'btn btn-primary btn-sm px-3'
                    }
                });
                return;
            }

            Swal.fire({
                title: '<span style="font-size: 16px;">¿Estás seguro?</span>',
                html: '<p style="font-size: 13px; margin: 0;">Se eliminará este item del pedido</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<span style="font-size: 13px;">Sí, eliminar</span>',
                cancelButtonText: '<span style="font-size: 13px;">Cancelar</span>',
                buttonsStyling: true,
                customClass: {
                    confirmButton: 'btn btn-danger btn-sm px-3',
                    cancelButton: 'btn btn-secondary btn-sm px-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminarItemPOS', producto_venta_id, indice);
                }
            });
        }
    </script>
@endpush
