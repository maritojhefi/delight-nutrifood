<script>
    $(document).ready(function() {
        $('#confirmarModificarPedidoModal').on('show.bs.modal', function(event) {
            var trigger = $(event.relatedTarget);
            var url = trigger.data('url');
            $('#confirmarModificarPedido').data('redirect-url', url);
        });

        $('#confirmarModificarPedido').on('click', function(e) {
            e.preventDefault();
            var url = $(this).data('redirect-url');
            if (url) {
                window.location.href = url;
            }
        });
    });

    $(document).ready(async function() {
        $(document).on('click', '#abrir-venta-qr', async () => {
            // Mockup functionalidad escaneado de QR
            // // console.log("Simulando escaneado de QR");
            // Asumiendo escaneado exitoso
            // Generacion de una nueva venta con el identificador del usuario
            try {
                const respuestaQR = await VentaService.generarVentaQR();
                await sincronizarCarrito();
                // if (respuestaQR.status === 200) {
                // // //     console.log("La creacion de la venta fue un exito");
                //     await sincronizarCarrito();
                // }
            } catch (error) {
                if (error.response && error.response.status === 409) {
                    // De ya existir una venta, se procede con la sincronizacion del carrito
                    // Esto puede utilizarse para mencionarle al cliente que ya dispone de atencion a sus pedidos
                    // // console.log("Ya existe una venta activa, Procediendo a sincronizar el carrito.");
                    await sincronizarCarrito();
                } else {
                    // Control de Error, puede usarse para mencionarle al cliente que el escaneado
                    // del QR no funcionó
                    console.error("Error al procesar el código QR:", error);
                }
            }


        });

        $(document).on('click', '#cerrar-venta-qr', () => {
            // // console.log("Simulando proceso cerrado de venta");
            // Culminar ciclo de venta sea aceptando o rechazando los producto_venta relacionados
            // a la venta activa del cliente
        });

        const sincronizarCarrito = async () => {
            const carrito = carritoStorage.obtenerCarrito();
            // // console.log("Carrito:", carrito);
            // Sincronizacion de base de datos con elementos actuales en el carrito
            const respuestaSincronizacion = await VentaService.generarProductosVenta_Carrito(
                carrito)
            // // console.log("Sincronización de productos exitosa:", respuestaSincronizacion);
            // Eliminar elmentos existentes en el carrito para evitar nuevos registros indeseados
            // y abusos en generacion de producto_venta
            carritoStorage.vaciarCarrito();
        }
    });
</script>

<script>
    $(document).ready(function() {
        // Find the countdown element
        const $countdownText = $('#countdown-timer-text');
        const $countdownElement = $('#countdown-timer');

        // Get the target timestamp (in seconds) from the data attribute
        const targetTimestamp = parseInt($countdownElement.data('target'));

        if (isNaN(targetTimestamp)) {
            $countdownElement.text('Error en la hora.');
            return;
        }

        // Main update function
        function updateCountdown() {
            // Get current time in seconds
            const now = Math.floor(Date.now() / 1000);

            // Calculate the total remaining seconds
            let remainingSeconds = targetTimestamp - now;

            if (remainingSeconds <= 0) {
                // Countdown is finished or passed
                $countdownText.text('¡Hora de comer!');
                // Stop the timer
                clearInterval(timerInterval);
                // Optionally reload the page or update the target time again
                return;
            }

            // --- Calculation ---
            const days = Math.floor(remainingSeconds / 86400); // 86400 = 24*60*60
            remainingSeconds %= 86400;

            const hours = Math.floor(remainingSeconds / 3600);
            remainingSeconds %= 3600;

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;

            // --- Formatting (Pads single digits with a leading zero) ---
            const dDisplay = days > 0 ? `${days}d ` : '';
            const hDisplay = String(hours).padStart(2, '0');
            const mDisplay = String(minutes).padStart(2, '0');
            const sDisplay = String(seconds).padStart(2, '0');

            // --- Output ---
            $countdownElement.text(`${dDisplay}${hDisplay}h ${mDisplay}m ${sDisplay}s`);
        }

        // Run the update function immediately
        updateCountdown();

        // Run the update function every second
        const timerInterval = setInterval(updateCountdown, 1000);
    });
</script>

<!-- Badge tiempo límite -->
<script>
    $(document).ready(function() {
        // Find all countdown elements (supports multiple timers)
        const $countdownElements = $('.countdown-plan-timer');
        if ($countdownElements.length === 0) {
            return;
        }
        // Create individual timers for each element
        $countdownElements.each(function() {
            const $element = $(this);
            const $badge = $element.closest('p.badge'); // Get parent <p> badge
            const targetTimestamp = parseInt($element.data('target'));
            if (isNaN(targetTimestamp)) {
                $element.text('Error en la hora.');
                return;
            }
            // Main update function for this specific element
            function updateCountdown() {
                // Get current time in seconds
                const now = Math.floor(Date.now() / 1000);
                // Calculate the total remaining seconds
                let remainingSeconds = targetTimestamp - now;
                if (remainingSeconds <= 0) {
                    // Countdown is finished or passed - replace entire badge content
                    $badge.text('Generando automáticamente...');
                    // Stop the timer
                    clearInterval(timerInterval);
                    return;
                }
                // --- Calculation ---
                const days = Math.floor(remainingSeconds / 86400); // 86400 = 24*60*60
                remainingSeconds %= 86400;
                const hours = Math.floor(remainingSeconds / 3600);
                remainingSeconds %= 3600;
                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;
                // --- Formatting (Pads single digits with a leading zero) ---
                const dDisplay = days > 0 ? `${days}d ` : '';
                const hDisplay = String(hours).padStart(2, '0');
                const mDisplay = String(minutes).padStart(2, '0');
                const sDisplay = String(seconds).padStart(2, '0');
                // --- Output ---
                $element.text(`${dDisplay}${hDisplay}h ${mDisplay}m ${sDisplay}s`);
            }
            // Run the update function immediately
            updateCountdown();
            // Run the update function every second
            const timerInterval = setInterval(updateCountdown, 1000);
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Función para copiar el código de referido al portapapeles
        $('#btnCopiarCodigo').on('click', function() {
            const codigoReferido = document.getElementById('codigoReferido');

            // Seleccionar el texto del input
            codigoReferido.select();
            codigoReferido.setSelectionRange(0, 99999); // Para dispositivos móviles

            try {
                // Copiar al portapapeles
                document.execCommand('copy');

                // Mostrar feedback visual
                const btn = $(this);
                const iconoOriginal = btn.html();
                btn.html('<i class="fa fa-check"></i>').removeClass('bg-white color-black').addClass(
                    'bg-green1-dark color-white');

                // Restaurar el botón después de 2 segundos
                setTimeout(function() {
                    btn.html(iconoOriginal).removeClass('bg-green1-dark color-white').addClass(
                        'bg-white color-black');
                }, 2000);

                // Usar la API moderna del portapapeles si está disponible
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(codigoReferido.value).then(function() {
                        // Feedback ya mostrado arriba
                    });
                }
            } catch (err) {
                console.error('Error al copiar:', err);
                alert('No se pudo copiar el código. Por favor, selecciónalo manualmente.');
            }
        });
    });
</script>

<script>
    // Función para copiar el código de referido
    function copiarCodigo() {
        const codigoInput = document.getElementById('codigoReferido');
        const btn = document.getElementById('btnCopiarCodigoBtn');

        if (!codigoInput) {
            alert('No se encontró el código de referido.');
            return;
        }

        const codigoTexto = codigoInput.value;

        // Seleccionar el texto del input para dispositivos móviles
        codigoInput.select();
        codigoInput.setSelectionRange(0, 99999);

        // Función para mostrar feedback visual
        function mostrarFeedback() {
            const btnElement = $(btn);
            const textoOriginal = btnElement.html();
            btnElement.html('<i class="fa fa-check"></i> Copiado')
                .removeClass('bg-green-dark')
                .addClass('bg-green-light');

            // Restaurar el botón después de 2 segundos
            setTimeout(function() {
                btnElement.html('<i class="fa fa-code"></i> Copiar Codigo')
                    .removeClass('bg-green-light')
                    .addClass('bg-green-dark');
            }, 2000);
        }

        try {
            // Usar la API moderna del portapapeles si está disponible
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(codigoTexto).then(function() {
                    mostrarFeedback();
                }).catch(function(err) {
                    console.error('Error al copiar:', err);
                    // Fallback al método antiguo
                    try {
                        document.execCommand('copy');
                        mostrarFeedback();
                    } catch (e) {
                        alert('No se pudo copiar el código. Por favor, selecciónalo manualmente.');
                    }
                });
            } else {
                // Fallback para navegadores antiguos
                try {
                    document.execCommand('copy');
                    mostrarFeedback();
                } catch (e) {
                    alert('No se pudo copiar el código. Por favor, selecciónalo manualmente.');
                }
            }
        } catch (err) {
            console.error('Error al copiar:', err);
            alert('No se pudo copiar el código. Por favor, selecciónalo manualmente.');
        }
    }

    @if (auth()->user() && auth()->user()->perfilesPuntos() && auth()->user()->perfilesPuntos()->first())
        // Función para copiar el link de referido
        function copiarLink() {
            // URL del link de referido generada desde el servidor
            const linkCompleto =
                '{{ url('/register') }}?ref={{ auth()->user()->perfilesPuntos()->first()->pivot->codigo }}';

            const btn = document.getElementById('btnCopiarLink');

            // Función para mostrar feedback visual
            function mostrarFeedback() {
                const btnElement = $(btn);
                const textoOriginal = btnElement.html();
                btnElement.html('<i class="fa fa-check"></i> Copiado')
                    .removeClass('bg-blue-dark')
                    .addClass('bg-green-light');

                // Restaurar el botón después de 2 segundos
                setTimeout(function() {
                    btnElement.html('<i class="fa fa-link"></i> Copiar Link')
                        .removeClass('bg-green-light')
                        .addClass('bg-blue-dark');
                }, 2000);
            }

            try {
                // Usar la API moderna del portapapeles si está disponible
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(linkCompleto).then(function() {
                        mostrarFeedback();
                    }).catch(function(err) {
                        console.error('Error al copiar:', err);
                        // Crear un input temporal para el fallback
                        const inputTemp = document.createElement('input');
                        inputTemp.value = linkCompleto;
                        document.body.appendChild(inputTemp);
                        inputTemp.select();
                        inputTemp.setSelectionRange(0, 99999);

                        try {
                            document.execCommand('copy');
                            document.body.removeChild(inputTemp);
                            mostrarFeedback();
                        } catch (e) {
                            document.body.removeChild(inputTemp);
                            alert('No se pudo copiar el link. El link es: ' + linkCompleto);
                        }
                    });
                } else {
                    // Fallback para navegadores antiguos: crear un input temporal
                    const inputTemp = document.createElement('input');
                    inputTemp.value = linkCompleto;
                    document.body.appendChild(inputTemp);
                    inputTemp.select();
                    inputTemp.setSelectionRange(0, 99999);

                    try {
                        document.execCommand('copy');
                        document.body.removeChild(inputTemp);
                        mostrarFeedback();
                    } catch (e) {
                        document.body.removeChild(inputTemp);
                        alert('No se pudo copiar el link. El link es: ' + linkCompleto);
                    }
                }
            } catch (err) {
                console.error('Error al copiar:', err);
                alert('No se pudo copiar el link. El link es: ' + linkCompleto);
            }
        }
    @endif
</script>
