<div class="col-12">
    @if ($ventas->count() == 0)
        <div class="alert alert-secondary solid alert-rounded p-1"><strong>Nada por aqui!</strong> Esperando nuevos pedidos... <i class="fa fa-spinner fa-spin"></i>
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
                        <div class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1">
                            @if ($prod->pivot->estado_actual == 'pendiente')
                                <a href="#"
                                    wire:click="cambiarEstado('{{ $prod->pivot->id }}','{{ $prod->pivot->estado_actual }}')">
                                    <p class="mb-0">{{ Str::limit($prod->nombre, 30) }}</p>
                                </a>
                            @else
                                <a href="#"
                                    wire:click="cambiarEstado('{{ $prod->pivot->id }}','{{ $prod->pivot->estado_actual }}')"><i
                                        class="fa fa-check text-success"></i><del
                                        class="mb-0 text-success">{{ Str::limit($prod->nombre, 20) }}</del></a>
                            @endif

                            <p class="mb-0"><strong>{{ $prod->pivot->cantidad }}</strong></p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="alert solid p-1 mx-2 d-none ps-3" style="line-height: 15px"
                id="alert-pedido-{{ $item->id }}"><strong><i
                        class="fa fa-spinner fa-spin"></i> </strong> <span class="letra12" id="alert-pedido-text-{{ $item->id }}">Pedido actualizado.</span></div>
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
    </script>
@endpush
</div>
