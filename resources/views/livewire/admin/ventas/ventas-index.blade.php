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
@endpush
