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
        Livewire.on('focusInputBuscador', () => {
            const input = document.getElementById('input-buscador');
            if (input) {
                input.focus();
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