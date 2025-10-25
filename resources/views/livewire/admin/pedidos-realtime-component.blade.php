<div class="col-12">
    @if ($ventas->count() == 0)
        <div class="alert alert-secondary solid alert-rounded p-1"><strong>Nada por aqui!</strong> Esperando nuevos
            pedidos... <i class="fa fa-spinner fa-spin"></i>
        </div>
    @endif
    @if ($listado == 'infinito')
        <div class="row flex-nowrap overflow-auto" style="overflow-x: auto; overflow-y: hidden;">
        @else
            <div class="row">
    @endif
    @foreach ($ventas as $item)
        @if ($listado == 'infinito')
            <div class="col-sm-3 col-3" style="flex: 0 0 auto; min-width: 280px;">
            @else
                <div class="col-sm-6 col-6">
        @endif
        <div class="card active_users bordeado" id="pedido-{{ $item->id }}">
            <div class="card-header bg-primary p-1 m-0 ">
                <h4 class="card-title text-white letra14 text-white">para {{ $item->tipo_entrega }}
                    @switch($item->tipo_entrega)
                        @case('mesa')
                            <i class="fa fa-table"></i> {{ $item->mesa->numero }}
                        @break

                        @case('delivery')
                            <i class="fa fa-truck"></i>
                        @break

                        @case('recoger')
                            <i class="fa fa-bolt"></i>
                        @break

                        @default
                            <i class="fa fa-bolt"></i>
                        @break
                    @endswitch
                </h4>
                <strong
                    class="text-white letra12">{{ $item->cliente ? Str::words($item->cliente->name, 2, '') : 'N/A' }}</strong>
            </div>
            <div class="card-body p-2 m-0 letra12">
                <div class="list-group-flush">
                    <center class="letra10"><small class="text-muted">Creado
                            {{ GlobalHelper::timeago($item->created_at) }}</small></center>
                    @if ($item->reservado_at)
                        <center class="letra12">
                            <i class="fa fa-clock-o"></i>
                            <span id="timer-{{ $item->id }}" data-reserva="{{ $item->reservado_at }}"
                                class="timer-reserva">
                                Calculando...
                            </span>
                        </center>
                    @endif
                    @foreach ($item->productos as $prod)
                        <div wire:click="verDetalleItem({{ $prod->pivot->id }})" style="cursor: pointer;"
                            class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1">
                            <div class="flex-grow-1">
                                @if ($prod->pivot->estado_actual == 'despachado')
                                    <a href="#">
                                        <del class="mb-0">{{ Str::limit($prod->nombre, 30) }}</del>
                                    </a>
                                @else
                                    <a href="#">
                                        <p class="mb-0">{{ Str::limit($prod->nombre, 30) }}</p>
                                    </a>
                                @endif


                                {{-- Indicadores de estado --}}
                                <div class="d-flex gap-1 mt-1">
                                    @if ($prod->contadores_estados['pendiente'] > 0)
                                        <span class="badge badge-xxs light badge-danger"
                                            style="font-size: 15px !important; padding: 2px 4px;"
                                            title="{{ $prod->contadores_estados['pendiente'] }} item(s) pendiente(s)">
                                            ⏳ {{ $prod->contadores_estados['pendiente'] }}
                                        </span>
                                    @endif
                                    @if ($prod->contadores_estados['preparacion'] > 0)
                                        <span class="badge badge-xxs light badge-warning"
                                            style="font-size: 15px !important; padding: 2px 4px;"
                                            title="{{ $prod->contadores_estados['preparacion'] }} item(s) en preparación">
                                            🔥 {{ $prod->contadores_estados['preparacion'] }}
                                        </span>
                                    @endif
                                    @if ($prod->contadores_estados['despachado'] > 0)
                                        <span class="badge badge-xxs light badge-success"
                                            style="font-size: 15px !important; padding: 2px 4px;"
                                            title="{{ $prod->contadores_estados['despachado'] }} item(s) listo(s)">
                                            ✅ {{ $prod->contadores_estados['despachado'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="mb-0 align-self-start letra14"><strong>Total:
                                    {{ $prod->pivot->cantidad }}</strong></p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="alert solid p-1 mx-2 d-none ps-3" style="line-height: 15px"
                id="alert-pedido-{{ $item->id }}"><strong><i class="fa fa-spinner fa-spin"></i> </strong> <span
                    class="letra12" id="alert-pedido-text-{{ $item->id }}">Pedido actualizado.</span></div>
        </div>
</div>
@endforeach
</div>

@push('scripts')
    <style>
        .onda-success {
            border-style: solid;
            border-color: #20c996b3;
            border-width: 5px;
            border-radius: 15px;
            animation: pulse 1.5s infinite;

        }

        .onda-warning {
            border-style: solid;
            border-color: #fe8630b3;
            border-width: 5px;
            border-radius: 15px;
            animation: pulse 1.5s infinite;
        }

        .onda-info {
            border-style: solid;
            border-color: #3a82efb3;
            border-width: 5px;
            border-radius: 15px;
            animation: pulse 1.5s infinite;
        }

        .onda-danger {
            border-style: solid;
            border-color: #f72b50b3;
            border-width: 5px;
            border-radius: 15px;
            animation: pulse 1.5s infinite;
        }

        /* Estilos para indicadores de estado */
        .gap-1 {
            gap: 4px;
        }

        .badge-xxs {
            font-size: 9px !important;
            padding: 2px 5px !important;
            border-radius: 3px;
            font-weight: 600;
        }
    </style>
    <script>
        Livewire.on('notificacionCocina', respuesta => {
            toastr[respuesta.icono]('', respuesta.message, {
                positionClass: "toast-bottom-right",
                timeOut: 6000,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                preventDuplicates: !1,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1,
            });
            // Encuentra la tarjeta del pedido por id
            let cardContainer = document.getElementById(`pedido-${respuesta.idVenta}`);
            let alertContainer = document.getElementById(`alert-pedido-${respuesta.idVenta}`);
            let alertTextContainer = document.getElementById(`alert-pedido-text-${respuesta.idVenta}`);
            if (cardContainer) {
                // Agrega la clase de animación
                cardContainer.classList.add('onda-' + respuesta.icono);
                alertContainer.classList.add('alert-' + respuesta.icono);
                alertContainer.classList.remove('d-none');

                alertTextContainer.innerHTML = respuesta.message;
                // Reproduce el audio
                var audio = new Audio('/tonococina.mp3');
                audio.play();

                // Remueve la clase después de 2 segundos
                setTimeout(() => {
                    cardContainer.classList.remove('onda-' + respuesta.icono);
                    alertContainer.classList.add('d-none');
                }, 5000);
            }
        })

        // Función para actualizar los timers de reservas
        function actualizarTimers() {
            const timers = document.querySelectorAll('.timer-reserva');

            timers.forEach(timer => {
                const fechaReserva = timer.getAttribute('data-reserva');
                if (!fechaReserva) return;

                const ahora = new Date();
                const reserva = new Date(fechaReserva);

                // Calcular diferencia en milisegundos
                const diferencia = reserva - ahora;

                if (diferencia <= 0) {
                    // El tiempo ya pasó
                    timer.innerHTML = '<strong class="text-danger">⏰ Tiempo cumplido</strong>';
                    timer.classList.add('text-danger');
                    timer.classList.add('fw-bold');
                } else {
                    // Calcular días, horas, minutos y segundos
                    const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                    const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
                    const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

                    // Construir el texto
                    let texto = '';

                    if (dias > 0) {
                        texto += `${dias}d `;
                    }
                    if (horas > 0 || dias > 0) {
                        texto += `${horas}h `;
                    }
                    texto += `${minutos}m ${segundos}s`;

                    // Determinar color según urgencia
                    let claseColor = '';
                    const minutosRestantes = Math.floor(diferencia / (1000 * 60));

                    if (minutosRestantes <= 15) {
                        // Menos de 15 minutos - Rojo urgente
                        claseColor = 'text-danger fw-bold';
                        timer.innerHTML = `<strong class="text-danger">⚠️ ${texto}</strong>`;
                    } else if (minutosRestantes <= 30) {
                        // Entre 15 y 30 minutos - Naranja advertencia
                        claseColor = 'text-warning fw-bold';
                        timer.innerHTML = `<strong class="text-warning">⏱️ ${texto}</strong>`;
                    } else if (minutosRestantes <= 60) {
                        // Entre 30 y 60 minutos - Azul preparación
                        claseColor = 'text-primary';
                        timer.innerHTML = `<span class="text-primary">🕐 ${texto}</span>`;
                    } else {
                        // Más de 60 minutos - Verde relajado
                        claseColor = 'text-success';
                        timer.innerHTML = `<span class="text-success">✓ ${texto}</span>`;
                    }

                    timer.className = 'timer-reserva ' + claseColor;
                }
            });
        }

        // Actualizar timers cada segundo
        setInterval(actualizarTimers, 1000);

        // Actualizar inmediatamente al cargar
        actualizarTimers();

        // Actualizar cuando Livewire recargue el componente
        document.addEventListener('livewire:load', actualizarTimers);
        document.addEventListener('livewire:update', actualizarTimers);

        // Listener para mostrar detalle de items
        window.addEventListener('verDetalleItem', event => {
            console.log(event.detail);
            const data = event.detail;
            mostrarDetalleItems(data);
        });

        function mostrarDetalleItems(data) {
            const {
                producto_venta_id,
                producto_nombre,
                cantidad_total,
                observacion,
                items
            } = data;

            // Generar HTML para cada item
            let itemsHTML = '';
            items.forEach((item, index) => {
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

                // Calcular tiempo transcurrido
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
                            <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                <span>📅 ${horaFormateada}</span>
                                <span class="fw-bold ${diffMins > 30 ? 'text-danger' : ''}">${tiempoTranscurrido}</span>
                            </div>
                        `;
                }

                // Lista de adicionales
                let adicionalesHTML = '';
                if (item.adicionales && item.adicionales.length > 0) {
                    adicionalesHTML = '<ul class="mb-2" style="font-size: 12px; padding-left: 20px;">';
                    item.adicionales.forEach(adicional => {
                        adicionalesHTML += `<li class="text-black">${adicional}</li>`;
                    });
                    adicionalesHTML += '</ul>';
                } else {
                    adicionalesHTML = '<p class="text-muted mb-2" style="font-size: 12px;">Sin adicionales</p>';
                }

                // Botones de estado
                const botonesEstado = `
                        <div class="btn-group btn-group-sm w-100 mt-2" role="group">
                            <button type="button" 
                                    class="btn ${item.estado === 'pendiente' ? 'btn-danger' : 'btn-outline-danger'}" 
                                    onclick="cambiarEstadoItem(${producto_venta_id}, ${item.indice}, 'pendiente')"
                                    ${item.estado === 'pendiente' ? 'disabled' : ''}>
                                ⏳ Pendiente
                            </button>
                            <button type="button" 
                                    class="btn ${item.estado === 'preparacion' ? 'btn-warning' : 'btn-outline-warning'}" 
                                    onclick="cambiarEstadoItem(${producto_venta_id}, ${item.indice}, 'preparacion')"
                                    ${item.estado === 'preparacion' ? 'disabled' : ''}>
                                🔥 Preparación
                            </button>
                            <button type="button" 
                                    class="btn ${item.estado === 'despachado' ? 'btn-success' : 'btn-outline-success'}" 
                                    onclick="cambiarEstadoItem(${producto_venta_id}, ${item.indice}, 'despachado')"
                                    ${item.estado === 'despachado' ? 'disabled' : ''}>
                                ✅ Listo
                            </button>
                        </div>
                    `;

                itemsHTML += `
                        <div class="card mb-3" style="border-left: 4px solid ${item.estado === 'pendiente' ? '#dc3545' : item.estado === 'preparacion' ? '#ffc107' : '#28a745'};">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Item ${item.indice}</h6>
                                    <span class="badge ${estadoClass[item.estado]}">${estadoIcon[item.estado]} ${item.estado.toUpperCase()}</span>
                                </div>
                                ${tiempoHTML}
                                <hr class="my-2">
                                <strong style="font-size: 13px; color: black;">Adicionales:</strong>
                                ${adicionalesHTML}
                                ${botonesEstado}
                            </div>
                        </div>
                    `;
            });

            // Agregar observación si existe
            let observacionHTML = '';
            if (observacion && observacion.trim() !== '') {
                observacionHTML = `
                    <div class="alert alert-warning mb-3" style="background-color: #fff3cd;">
                        <strong style="color: #856404;">📝 Observación:</strong>
                        <p class="mb-0 mt-1" style="color: #856404;">${observacion}</p>
                    </div>
                `;
            }

            Swal.fire({
                title: `${producto_nombre}`,
                html: `
                        <div class="text-start">
                            <div class="alert alert-info mb-3">
                                <strong>Total de items:</strong> ${cantidad_total}
                            </div>
                            ${observacionHTML}
                            ${itemsHTML}
                        </div>
                    `,
                width: '600px',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    container: 'detalle-items-modal'
                }
            });
        }

        // Función global para cambiar estado de item
        window.cambiarEstadoItem = function(producto_venta_id, indice, nuevoEstado) {
            // Llamar al servicio de Livewire
            Livewire.emit('cambiarEstadoItem', producto_venta_id, indice, nuevoEstado);

            // NO cerrar el modal - mantenerlo abierto para cambiar múltiples items

            // Actualizar visualmente el item en el modal
            actualizarEstadoItemEnModal(indice, nuevoEstado);

            // Mostrar feedback pequeño
            toastr.success(`Item ${indice} → ${nuevoEstado}`, '', {
                timeOut: 2000,
                positionClass: "toast-top-right"
            });
        };

        // Función para actualizar visualmente un item sin recargar el modal
        function actualizarEstadoItemEnModal(indice, nuevoEstado) {
            // Encontrar la card del item
            const cards = document.querySelectorAll('.detalle-items-modal .card');
            cards.forEach(card => {
                const titulo = card.querySelector('h6');
                if (titulo && titulo.textContent === `Item ${indice}`) {
                    // Actualizar badge de estado
                    const badge = card.querySelector('.badge');
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

                    badge.className = `badge ${estadoClass[nuevoEstado]}`;
                    badge.textContent = `${estadoIcon[nuevoEstado]} ${nuevoEstado.toUpperCase()}`;

                    // Actualizar color del borde izquierdo
                    const borderColor = nuevoEstado === 'pendiente' ? '#dc3545' :
                        nuevoEstado === 'preparacion' ? '#ffc107' : '#28a745';
                    card.style.borderLeftColor = borderColor;

                    // Actualizar botones
                    const btnGroup = card.querySelector('.btn-group');
                    const buttons = btnGroup.querySelectorAll('button');

                    buttons.forEach(btn => {
                        const btnText = btn.textContent.trim();

                        // Pendiente
                        if (btnText.includes('Pendiente')) {
                            if (nuevoEstado === 'pendiente') {
                                btn.className = 'btn btn-danger';
                                btn.disabled = true;
                            } else {
                                btn.className = 'btn btn-outline-danger';
                                btn.disabled = false;
                            }
                        }
                        // Preparación
                        else if (btnText.includes('Preparación')) {
                            if (nuevoEstado === 'preparacion') {
                                btn.className = 'btn btn-warning';
                                btn.disabled = true;
                            } else {
                                btn.className = 'btn btn-outline-warning';
                                btn.disabled = false;
                            }
                        }
                        // Listo
                        else if (btnText.includes('Listo')) {
                            if (nuevoEstado === 'despachado') {
                                btn.className = 'btn btn-success';
                                btn.disabled = true;
                            } else {
                                btn.className = 'btn btn-outline-success';
                                btn.disabled = false;
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
</div>
