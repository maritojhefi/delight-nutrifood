<div class="col-12">
    @if ($ventas->count() == 0)
        <div class="alert alert-secondary solid alert-rounded p-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <strong style="color: white;">🍽️ Nada por aquí!</strong> 
            <span style="color: white;">Esperando nuevos pedidos...</span> 
            <i class="fa fa-spinner fa-spin" style="color: white;"></i>
        </div>
    @endif
    @if ($listado == 'infinito')
        <div class="row flex-nowrap overflow-auto pb-2" style="overflow-x: auto; overflow-y: hidden; gap: 10px;">
        @else
            <div class="row">
    @endif
    @foreach ($ventas as $item)
        @if ($listado == 'infinito')
            <div class="col-auto" style="flex: 0 0 auto; min-width: clamp(260px, 90vw, 300px);">
            @else
                <div class="col-12 col-sm-6 col-lg-4 mb-3">
        @endif
        <div class="card active_users pedido-card" id="pedido-{{ $item->id }}" 
             style="border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s; border: none; overflow: hidden;">
            <div class="card-header p-2 m-0" 
                 style="background: linear-gradient(135deg, 
                    {{ $item->tipo_entrega == 'mesa' ? '#667eea 0%, #764ba2 100%' : 
                       ($item->tipo_entrega == 'delivery' ? '#f093fb 0%, #f5576c 100%' : 
                       '#4facfe 0%, #00f2fe 100%') }}); 
                    border: none;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-0" style="font-size: clamp(12px, 3vw, 14px); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            @switch($item->tipo_entrega)
                                @case('mesa')
                                    <i class="fa fa-table"></i> Mesa {{ $item->mesa->numero }}
                                @break
                                @case('delivery')
                                    <i class="fa fa-truck"></i> Delivery
                                @break
                                @case('recoger')
                                    <i class="fa fa-shopping-bag"></i> Para llevar
                                @break
                                @default
                                    <i class="fa fa-utensils"></i> {{ ucfirst($item->tipo_entrega) }}
                                @break
                            @endswitch
                        </h5>
                        <small class="text-white" style="font-size: clamp(10px, 2.5vw, 12px); opacity: 0.95; font-weight: 600;">
                            {{ $item->cliente ? Str::words($item->cliente->name, 3, '') : 'Cliente sin nombre' }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body p-2" style="background: #f8f9fa;">
                <div class="list-group-flush">
                    <div class="text-center mb-2">
                        <small class="text-muted" style="font-size: clamp(9px, 2vw, 10px);">
                            <i class="fa fa-clock"></i> {{ GlobalHelper::timeago($item->created_at) }}
                        </small>
                    </div>
                    @if ($item->reservado_at)
                        <div class="alert alert-info py-1 px-2 mb-2" style="font-size: clamp(10px, 2.5vw, 12px); border-radius: 8px; background: #e3f2fd; border: 1px solid #90caf9;">
                            <i class="fa fa-calendar-alt"></i>
                            <span id="timer-{{ $item->id }}" data-reserva="{{ $item->reservado_at }}"
                                class="timer-reserva fw-bold">
                                Calculando...
                            </span>
                        </div>
                    @endif
                    @foreach ($item->productos as $prod)
                        <div wire:click="verDetalleItem({{ $prod->pivot->id }})" 
                            class="producto-item list-group-item bg-white d-flex justify-content-between align-items-start px-2 py-2 mb-2" 
                            style="cursor: pointer; border-radius: 8px; border: 1px solid #e0e0e0; transition: all 0.2s;">
                            <div class="flex-grow-1" style="min-width: 0;">
                                @if ($prod->pivot->estado_actual == 'despachado')
                                    <div class="mb-0" style="font-size: clamp(11px, 2.8vw, 13px); font-weight: 600; color: #666;">
                                        <del>{{ Str::limit($prod->nombre, 25) }}</del>
                                    </div>
                                @else
                                    <div class="mb-0" style="font-size: clamp(11px, 2.8vw, 13px); font-weight: 600; color: #333;">
                                        {{ Str::limit($prod->nombre, 25) }}
                                    </div>
                                @endif

                                {{-- Indicadores de estado --}}
                                <div class="d-flex flex-wrap gap-1 mt-1" style="gap: 4px;">
                                    @if ($prod->contadores_estados['pendiente'] > 0)
                                        <span class="badge-estado badge-danger-estado"
                                            title="{{ $prod->contadores_estados['pendiente'] }} item(s) pendiente(s)">
                                            ⏳ {{ $prod->contadores_estados['pendiente'] }}
                                        </span>
                                    @endif
                                    @if ($prod->contadores_estados['preparacion'] > 0)
                                        <span class="badge-estado badge-warning-estado"
                                            title="{{ $prod->contadores_estados['preparacion'] }} item(s) en preparación">
                                            🔥 {{ $prod->contadores_estados['preparacion'] }}
                                        </span>
                                    @endif
                                    @if ($prod->contadores_estados['despachado'] > 0)
                                        <span class="badge-estado badge-success-estado"
                                            title="{{ $prod->contadores_estados['despachado'] }} item(s) listo(s)">
                                            ✅ {{ $prod->contadores_estados['despachado'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="ms-2" style="font-size: clamp(11px, 2.8vw, 13px); font-weight: 700; color: #667eea; white-space: nowrap;">
                                x{{ $prod->pivot->cantidad }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="alert solid p-1 mx-2 mb-2 d-none" style="line-height: 15px; border-radius: 8px; font-size: 11px;"
                id="alert-pedido-{{ $item->id }}">
                <strong><i class="fa fa-spinner fa-spin"></i></strong> 
                <span id="alert-pedido-text-{{ $item->id }}">Pedido actualizado.</span>
            </div>
        </div>
</div>
@endforeach
</div>

@push('scripts')
    <style>
        /* Animaciones de pulso para notificaciones */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(32, 201, 151, 0.7);
            }
            50% {
                transform: scale(1.02);
                box-shadow: 0 0 0 10px rgba(32, 201, 151, 0);
            }
        }
        .swal2-content{
            padding: 0 !important;
            margin: 0 !important;
        }
        .onda-success {
            border: 3px solid #20c997;
            border-radius: 12px;
            animation: pulse 1.5s infinite;
        }

        .onda-warning {
            border: 3px solid #fe8630;
            border-radius: 12px;
            animation: pulse 1.5s infinite;
        }

        .onda-info {
            border: 3px solid #3a82ef;
            border-radius: 12px;
            animation: pulse 1.5s infinite;
        }

        .onda-danger {
            border: 3px solid #f72b50;
            border-radius: 12px;
            animation: pulse 1.5s infinite;
        }

        /* Estilos para las tarjetas de pedidos */
        .pedido-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
        }

        .producto-item:hover {
            background: #f0f0f0 !important;
            transform: translateX(4px);
            border-color: #667eea !important;
        }

        /* Badges de estado personalizados */
        .badge-estado {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: clamp(9px, 2.2vw, 11px);
            font-weight: 700;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .badge-danger-estado {
            background: linear-gradient(135deg, #f72b50 0%, #dc3545 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(247, 43, 80, 0.3);
        }

        .badge-warning-estado {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #333;
            box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
        }

        .badge-success-estado {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }

        /* Responsive para móvil */
        @media (max-width: 768px) {
            .pedido-card {
                margin-bottom: 12px;
            }
            
            .producto-item {
                padding: 8px !important;
            }
        }

        /* Scrollbar personalizado para listado horizontal */
        .overflow-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Scrollbar para modal de detalles */
        .detalle-items-modal ::-webkit-scrollbar {
            width: 6px;
        }

        .detalle-items-modal ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .detalle-items-modal ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        .detalle-items-modal ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Estilos adicionales para el modal */
        .detalle-items-popup {
            border-radius: 16px !important;
        }

        /* Animación de entrada para items */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .detalle-items-modal .card {
            animation: fadeInUp 0.3s ease-out;
        }

        /* Mejora en botones del modal */
        .detalle-items-modal .btn {
            transition: all 0.2s ease;
        }

        .detalle-items-modal .btn:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
                const estadoColors = {
                    'pendiente': { bg: '#dc3545', light: '#f8d7da', text: '#721c24' },
                    'preparacion': { bg: '#ffc107', light: '#fff3cd', text: '#856404' },
                    'despachado': { bg: '#28a745', light: '#d4edda', text: '#155724' }
                };

                const estadoIcon = {
                    'pendiente': '⏳',
                    'preparacion': '🔥',
                    'despachado': '✅'
                };

                const currentColor = estadoColors[item.estado];

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

                    const horaFormateada = agregado.toLocaleTimeString('es-BO', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    tiempoHTML = `
                        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: clamp(9px, 2.5vw, 11px); background: #f8f9fa; padding: 6px 10px; border-radius: 6px;">
                            <span>📅 ${horaFormateada}</span>
                            <span class="fw-bold ${diffMins > 30 ? 'text-danger' : 'text-success'}">${tiempoTranscurrido}</span>
                        </div>
                    `;
                }

                // Lista de adicionales
                let adicionalesHTML = '';
                if (item.adicionales && item.adicionales.length > 0) {
                    adicionalesHTML = `<div style="background: #f8f9fa; padding: 8px 12px; border-radius: 6px; margin-bottom: 10px;">
                        <strong style="font-size: clamp(10px, 2.5vw, 12px); color: #495057; display: block; margin-bottom: 6px;">📋 Adicionales:</strong>
                        <ul class="mb-0" style="font-size: clamp(10px, 2.5vw, 12px); padding-left: 20px; margin: 0;">`;
                    item.adicionales.forEach(adicional => {
                        adicionalesHTML += `<li class="text-dark fw-bold letra14" style="margin-bottom: 3px;">${adicional}</li>`;
                    });
                    adicionalesHTML += '</ul></div>';
                } else {
                    adicionalesHTML = '<div style="background: #f8f9fa; padding: 8px 12px; border-radius: 6px; margin-bottom: 10px; font-size: clamp(10px, 2.5vw, 12px); color: #6c757d; text-align: center;">Sin adicionales</div>';
                }

                // Botones de estado - responsive
                const botonesEstado = `
                    <div class="d-grid gap-2 mt-2">
                        <div class="btn-group btn-group-sm" role="group" style="display: flex; flex-wrap: wrap; gap: 4px;">
                            <button type="button" 
                                    class="btn ${item.estado === 'pendiente' ? 'btn-danger' : 'btn-outline-danger'}" 
                                    onclick="cambiarEstadoItem(${producto_venta_id}, ${item.indice}, 'pendiente')"
                                    style="flex: 1; min-width: 90px; font-size: clamp(9px, 2.2vw, 11px); padding: 6px 8px; border-radius: 6px;"
                                    ${item.estado === 'pendiente' ? 'disabled' : ''}>
                                ⏳ Pendiente
                            </button>
                            <button type="button" 
                                    class="btn ${item.estado === 'preparacion' ? 'btn-warning' : 'btn-outline-warning'}" 
                                    onclick="cambiarEstadoItem(${producto_venta_id}, ${item.indice}, 'preparacion')"
                                    style="flex: 1; min-width: 90px; font-size: clamp(9px, 2.2vw, 11px); padding: 6px 8px; border-radius: 6px;"
                                    ${item.estado === 'preparacion' ? 'disabled' : ''}>
                                🔥 Preparación
                            </button>
                            <button type="button" 
                                    class="btn ${item.estado === 'despachado' ? 'btn-success' : 'btn-outline-success'}" 
                                    onclick="cambiarEstadoItem(${producto_venta_id}, ${item.indice}, 'despachado')"
                                    style="flex: 1; min-width: 90px; font-size: clamp(9px, 2.2vw, 11px); padding: 6px 8px; border-radius: 6px;"
                                    ${item.estado === 'despachado' ? 'disabled' : ''}>
                                ✅ Listo
                            </button>
                        </div>
                    </div>
                `;

                itemsHTML += `
                    <div class="card mb-3" style="border: none; border-left: 4px solid ${currentColor.bg}; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 10px; overflow: hidden;">
                        <div class="card-body" style="padding: clamp(10px, 3vw, 16px);">
                            <div class="d-flex justify-content-between align-items-center mb-2" style="flex-wrap: wrap; gap: 8px;">
                                <h6 class="mb-0" style="font-size: clamp(12px, 3vw, 15px); font-weight: 700; color: #333;">Item ${item.indice}</h6>
                                <span style="background: ${currentColor.bg}; color: white; padding: 4px 10px; border-radius: 8px; font-size: clamp(9px, 2.2vw, 11px); font-weight: 700; white-space: nowrap;">
                                    ${estadoIcon[item.estado]} ${item.estado.toUpperCase()}
                                </span>
                            </div>
                            ${tiempoHTML}
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
                    <div class="alert mb-3" style="background: linear-gradient(135deg, #fff3cd 0%, #ffe8a1 100%); border: 2px solid #ffc107; border-radius: 10px; padding: clamp(10px, 3vw, 16px);">
                        <strong style="color: #856404; font-size: clamp(11px, 2.8vw, 13px); display: block; margin-bottom: 6px;">📝 Observación del cliente:</strong>
                        <p class="mb-0" style="color: #856404; font-size: clamp(10px, 2.5vw, 12px); line-height: 1.4;">${observacion}</p>
                    </div>
                `;
            }

            Swal.fire({
                title: `<div style="font-size: clamp(14px, 4vw, 18px); font-weight: 700; color: white; line-height: 1.2;">${producto_nombre}</div>`,
                html: `
                    <div class="text-start" style="padding: 0;">
                        <div class="alert alert-info mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; padding: clamp(8px, 2.5vw, 12px); text-align: center;">
                            <strong style="color: white; font-size: clamp(11px, 2.8vw, 14px);">🍽️ Total de items: ${cantidad_total}</strong>
                        </div>
                        ${observacionHTML}
                        <div style="max-height: 60vh; overflow-y: auto; padding-right: 4px;">
                            ${itemsHTML}
                        </div>
                    </div>
                `,
                width: 'clamp(300px, 95vw, 600px)',
                padding: 'clamp(12px, 3vw, 20px)',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    container: 'detalle-items-modal',
                    popup: 'detalle-items-popup'
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
            // Colores por estado
            const estadoColors = {
                'pendiente': { bg: '#dc3545', light: '#f8d7da', text: '#721c24' },
                'preparacion': { bg: '#ffc107', light: '#fff3cd', text: '#856404' },
                'despachado': { bg: '#28a745', light: '#d4edda', text: '#155724' }
            };

            const estadoIcon = {
                'pendiente': '⏳',
                'preparacion': '🔥',
                'despachado': '✅'
            };

            const currentColor = estadoColors[nuevoEstado];

            // Encontrar la card del item
            const cards = document.querySelectorAll('.detalle-items-modal .card');
            cards.forEach(card => {
                const titulo = card.querySelector('h6');
                if (titulo && titulo.textContent.trim() === `Item ${indice}`) {
                    
                    // 1. Actualizar el borde izquierdo de la card
                    card.style.borderLeftColor = currentColor.bg;

                    // 2. Actualizar el badge de estado (el span con el ícono)
                    const cardBody = card.querySelector('.card-body');
                    const headerDiv = cardBody.querySelector('.d-flex');
                    const badge = headerDiv.querySelector('span[style*="background"]');
                    
                    if (badge) {
                        badge.style.background = currentColor.bg;
                        badge.textContent = `${estadoIcon[nuevoEstado]} ${nuevoEstado.toUpperCase()}`;
                    }

                    // 3. Actualizar los botones
                    const btnGroup = card.querySelector('.btn-group');
                    const buttons = btnGroup.querySelectorAll('button');

                    buttons.forEach(btn => {
                        const btnText = btn.textContent.trim();

                        // Resetear estilos inline para que las clases Bootstrap funcionen
                        btn.style.flex = '1';
                        btn.style.minWidth = '90px';
                        btn.style.fontSize = 'clamp(9px, 2.2vw, 11px)';
                        btn.style.padding = '6px 8px';
                        btn.style.borderRadius = '6px';

                        // Actualizar clases según el estado
                        if (btnText.includes('Pendiente')) {
                            if (nuevoEstado === 'pendiente') {
                                btn.className = 'btn btn-danger';
                                btn.disabled = true;
                            } else {
                                btn.className = 'btn btn-outline-danger';
                                btn.disabled = false;
                            }
                        }
                        else if (btnText.includes('Preparación')) {
                            if (nuevoEstado === 'preparacion') {
                                btn.className = 'btn btn-warning';
                                btn.disabled = true;
                            } else {
                                btn.className = 'btn btn-outline-warning';
                                btn.disabled = false;
                            }
                        }
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

                    // 4. Añadir una animación visual de feedback
                    card.style.transition = 'all 0.3s ease';
                    card.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        card.style.transform = 'scale(1)';
                    }, 300);
                }
            });
        }
    </script>
@endpush
</div>
