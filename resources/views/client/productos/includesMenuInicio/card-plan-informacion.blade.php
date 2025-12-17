@auth
{{-- @php
    $horaActual = Carbon\Carbon::now()->hour;
    $periodoDia = '';
    $gradienteClass = '';
    $iconoSvg = '';

    if ($horaActual >= 6 && $horaActual < 12) {
        // Mañana: 6:00 - 11:59
        $periodoDia = 'manana';
        $lucideDia = 'sun-dim';
        $gradienteClass = 'gradient-morning';
        $iconoSvg = '<svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="sunGradient" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" style="stop-color:#FFD700;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#FFA500;stop-opacity:1" />
                </radialGradient>
            </defs>
            <circle cx="12" cy="12" r="6" fill="url(#sunGradient)" stroke="#FF8C00" stroke-width="1"/>
            <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12" stroke="#FFD700" stroke-width="2" stroke-linecap="round"/>
            <circle cx="12" cy="12" r="2" fill="#FFF" opacity="0.8"/>
        </svg>';
    } elseif ($horaActual >= 12 && $horaActual < 18) {
        // Tarde: 12:00 - 17:59
        $periodoDia = 'tarde';
        $lucideDia = 'sun';
        $gradienteClass = 'gradient-afternoon';
        $iconoSvg = '<svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="afternoonGradient" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" style="stop-color:#FFA500;stop-opacity:1" />
                    <stop offset="50%" style="stop-color:#FF8C00;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#FF6347;stop-opacity:1" />
                </radialGradient>
                <linearGradient id="cloudGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#E6E6FA;stop-opacity:0.8" />
                    <stop offset="100%" style="stop-color:#D3D3D3;stop-opacity:0.6" />
                </linearGradient>
            </defs>
            <!-- Sol de la tarde más bajo -->
            <circle cx="12" cy="14" r="4" fill="url(#afternoonGradient)" stroke="#FF6347" stroke-width="1"/>
            <!-- Rayos del sol (8 rayos simétricos) -->
            <!-- Top -->
            <line x1="12" y1="8" x2="12" y2="6" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Bottom -->
            <line x1="12" y1="20" x2="12" y2="22" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Left -->
            <line x1="6" y1="14" x2="4" y2="14" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Right -->
            <line x1="18" y1="14" x2="20" y2="14" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Top-left diagonal -->
            <line x1="7.2" y1="9.2" x2="5.8" y2="7.8" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Top-right diagonal -->
            <line x1="16.8" y1="9.2" x2="18.2" y2="7.8" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Bottom-left diagonal -->
            <line x1="9.2" y1="16.8" x2="7.8" y2="18.2" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Bottom-right diagonal -->
            <line x1="14.8" y1="16.8" x2="16.2" y2="18.2" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
            <!-- Nubes de la tarde -->
            <path d="M4 14c0-1.5 1-2.5 2.5-2.5s2.5 1 2.5 2.5c1 0 2 1 2 2s-1 2-2 2H6c-1.5 0-2.5-1-2.5-2.5z" fill="url(#cloudGradient)"/>
            <path d="M16 12c0-1 1-2 2-2s2 1 2 2c1 0 1.5 0.5 1.5 1.5s-0.5 1.5-1.5 1.5H18c-1 0-2-1-2-2z" fill="url(#cloudGradient)"/>
            <!-- Montañas en el horizonte -->
            <path d="M0 20L6 16L12 18L18 14L24 16V24H0V20Z" fill="#8B4513" opacity="1"/>
            <!-- Reflejo del sol -->
            <ellipse cx="12" cy="16" rx="3" ry="1" fill="#FFD700" opacity="0.4"/>
        </svg>';
    } else {
        // Noche: 18:00 - 5:59
        $periodoDia = 'noche';
        $lucideDia = 'moon-star';
        $gradienteClass = 'gradient-night';
        $iconoSvg = '<svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="moonGradient" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" style="stop-color:#E6E6FA;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#B0C4DE;stop-opacity:1" />
                </radialGradient>
            </defs>
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="url(#moonGradient)" stroke="#B0C4DE" stroke-width="1"/>
            <circle cx="12" cy="12" r="1.5" fill="#FFD700"/>
            <circle cx="8" cy="8" r="0.8" fill="#FFD700" opacity="0.8"/>
            <circle cx="16" cy="8" r="0.6" fill="#FFD700" opacity="0.6"/>
            <circle cx="8" cy="16" r="0.7" fill="#FFD700" opacity="0.7"/>
            <circle cx="16" cy="16" r="0.5" fill="#FFD700" opacity="0.5"/>
            <circle cx="6" cy="12" r="0.4" fill="#FFD700" opacity="0.4"/>
            <circle cx="18" cy="12" r="0.3" fill="#FFD700" opacity="0.3"/>
        </svg>';
    }
@endphp --}}




@php
    $planData = GlobalHelper::planInteligenteSegunHora(
        auth()->user()->id,
        Carbon\Carbon::now()->format('Y-m-d'),
        Carbon\Carbon::now()->format('H:i'),
    );
    $horarioActual = GlobalHelper::horarioHoraActual();
@endphp

@isset($planData)
    @php
        $plan = $planData->plan;
        $pedidos = $planData->pedidos;
        $detalle = $plan->pivot->detalle ? json_decode($plan->pivot->detalle, true) : [];
        $todosPermiso = count($pedidos) > 0 ? false : true;
        // dd($detalle, $plan);
        $estado = $planData->estado;
        $tiempoRestanteMinutos = $planData->tiempo_restante;
        $planesRestantes = $planData->planes_restantes;
        $fechaPlan = $planData->fecha_plan;
        $iconoPlanCard = $horarioActual->icono_lucide ?? 'hourglass';
        // $iconoPlanCard = $plan->horarioActual->icono_lucide ?? 'hourglass';
        // Formatear tiempo restante
        if ($tiempoRestanteMinutos >= 60) {
            $horas = floor($tiempoRestanteMinutos / 60);
            $minutos = $tiempoRestanteMinutos % 60;
            $tiempoRestante = $horas . 'h ' . $minutos . 'm';
        } else {
            $tiempoRestante = $tiempoRestanteMinutos . ($tiempoRestanteMinutos > 1 ? ' minutos' : 'minuto');
        }
        // Determinar el mensaje según el estado
        switch ($estado) {
            case 'en_curso':
                $textoTiempo = 'Termina en: ';
                $mensajeTiempo = 'Termina en ' . $tiempoRestante;
                $iconoTiempo = 'fa-clock';
                $iconoTiempoLucide = 'utensils';
                $colorTiempo = 'success';
                $textodia =
                    'Hoy ' .
                    lcfirst(App\Helpers\GlobalHelper::fechaFormateada(11, Carbon\Carbon::now())) .
                    (!$todosPermiso ? ' te servirás:' : ' solicitaste permiso');
                break;
            case 'proximo':
                $textoTiempo = 'Inicia en: ';
                $mensajeTiempo = 'Inicia en ' . $tiempoRestante;
                $iconoTiempo = 'fa-hourglass-start';
                $iconoTiempoLucide = 'hourglass';
                $colorTiempo = 'warning';
                $textodia =
                    'Hoy ' .
                    lcfirst(App\Helpers\GlobalHelper::fechaFormateada(11, Carbon\Carbon::now())) .
                    (!$todosPermiso ? ' te servirás:' : ' solicitaste permiso');
                break;
            case 'proximo_dia':
                $textoTiempo = 'Mañana en: ';
                $mensajeTiempo = 'Mañana en ' . $tiempoRestante;
                $iconoTiempo = 'fa-calendar-plus';
                $iconoTiempoLucide = 'calendar';
                $colorTiempo = 'info';

                if (!$todosPermiso) {
                    $textodia =
                        'Mañana ' .
                        lcfirst(App\Helpers\GlobalHelper::fechaFormateada(11, Carbon\Carbon::now()->addDay())) .
                        ' te servirás:';
                } else {
                    $textodia =
                        'Solicitaste permiso este ' .
                        lcfirst(App\Helpers\GlobalHelper::fechaFormateada(11, Carbon\Carbon::now()->addDay()));
                }
                break;
            default:
                $textoTiempo = '';
                $mensajeTiempo = 'Disponible';
                $iconoTiempo = 'fa-check';
                $iconoTiempoLucide = 'check';
                $colorTiempo = 'primary';
        }
    @endphp

    <!-- Acordeón del Plan -->
    <div class="card card-style pb-0 mb-3">
        <div class="card-header bg-highlight bg-dtheme-blue py-3">
            <h2 class="plan-title mb-0 color-white">{{ $plan->nombre }}</h2>
        </div>
        <div class="card-body m-0 p-0">
            <div class="accordion mb-1" id="planAccordion">
                <div class="accordion-item plan-accordion-item mb-3">
                    <!-- Header del acordeón (siempre visible) -->
                    <div class="accordion-header" id="planHeader">
                        <button class="accordion-button plan-accordion-button" type="button"
                            aria-expanded="false" aria-controls="planCollapse" {{ $plan->editable != 1 ? 'disabled' : '' }}>
                            <div class="plan-header-content mt-3 px-0"
                                style="padding-right: 2% !important; padding-left: 2% !important;">

                                <div class="day-info d-flex justify-content-evenly">
                                    <div class="day-icon-container">
                                        <div class="day-icon">
                                            <i data-lucide={{ $iconoPlanCard }} class="lucide-icon color-highlight mt-1"
                                                style="width: 3rem; height: 3rem;"></i>
                                        </div>
                                    </div>

                                    <div class="day-text" class="d-flex flex-column ms-2">
                                        <div class="d-flex flex-column gap-1">
                                            <p
                                                class="badge gradient-blue rounded rounded-s color-white d-flex flex-row gap-1 justify-content-center align-items-center mb-0 ">
                                                <span>Despacho:
                                                    {{ $plan->horario->hora_inicio . ' - ' . $plan->horario->hora_fin }}</span>
                                                <i data-lucide="clock" class="lucide-icon"
                                                    style="width: 1rem; height: 1rem;"></i>
                                            </p>
                                            <!-- Badge de tiempo con estado -->
                                            @if (count($pedidos->filter(fn($pedido) => $pedido->detalle == null)))
                                                <p
                                                    @php
                                                        // Configuración
                                                        $now = Carbon\Carbon::now();
                                                        $horaLimiteStr = GlobalHelper::getValorAtributoSetting('hora_finalizacion_planes');

                                                        $isBadgeVisible = true;

                                                        // Parsear horarios del plan
                                                        $horaInicioDespacho = Carbon\Carbon::createFromFormat('H:i', $plan->horario->hora_inicio);
                                                        $horaFinDespacho = Carbon\Carbon::createFromFormat('H:i', $plan->horario->hora_fin);

                                                        // Verificar si el pedido es para el día siguiente
                                                        $esParaSiguiente = $estado == "proximo_dia";

                                                        // Determinar la fecha objetivo (hoy o mañana)
                                                        $fechaObjetivo = $esParaSiguiente ? $now->copy()->addDay() : $now->copy();

                                                        // Combinar fecha objetivo con horarios
                                                        $tiempoInicio = $fechaObjetivo->copy()->setTime(
                                                            $horaInicioDespacho->hour,
                                                            $horaInicioDespacho->minute,
                                                            0
                                                        );

                                                        $tiempoFin = $fechaObjetivo->copy()->setTime(
                                                            $horaFinDespacho->hour,
                                                            $horaFinDespacho->minute,
                                                            0
                                                        );

                                                        // Crear hora límite con la fecha correcta
                                                        $horaLimite = Carbon\Carbon::createFromFormat('H:i', $horaLimiteStr);

                                                        // Verificar si estamos dentro del horario de despacho
                                                        $dentroDeHorarioDespacho = $now->between($tiempoInicio, $tiempoFin);

                                                        // Lógica de visibilidad del badge
                                                        if (!$plan->editable) {
                                                            // Si el plan no es editable, mostrar badge SOLO si estamos en horario de despacho
                                                            $isBadgeVisible = $dentroDeHorarioDespacho;
                                                        } elseif ($esParaSiguiente) {
                                                            // Pedido del día siguiente: mostrar badge si aún no alcanzamos la hora límite de mañana
                                                            $horaLimiteSiguiente = $now->copy()->addDay()->setTime(
                                                                $horaLimite->hour,
                                                                $horaLimite->minute,
                                                                0
                                                            );
                                                            if ($now->greaterThanOrEqualTo($horaLimiteSiguiente)) {
                                                                $isBadgeVisible = false;
                                                            }
                                                        } else {
                                                            // Pedido de HOY (editable)
                                                            $horaLimiteHoy = $now->copy()->setTime(
                                                                $horaLimite->hour,
                                                                $horaLimite->minute,
                                                                0
                                                            );

                                                            if ($dentroDeHorarioDespacho) {
                                                                // Si estamos en horario de despacho, el badge SIEMPRE es visible
                                                                $isBadgeVisible = true;
                                                            } elseif ($now->greaterThanOrEqualTo($horaLimiteHoy)) {
                                                                // Si ya pasó la hora límite Y no estamos en horario de despacho, ocultar
                                                                $isBadgeVisible = false;
                                                            }
                                                            // Si no ha pasado la hora límite (antes de 9am), el badge es visible
                                                        }

                                                        // Timestamp para JavaScript (usar la hora límite de modificación como referencia)
                                                        $tiempoObjetivoJS = $esParaSiguiente
                                                            ? $now->copy()->addDay()->setTime($horaLimite->hour, $horaLimite->minute, 0)
                                                            : $now->copy()->setTime($horaLimite->hour, $horaLimite->minute, 0);

                                                        $tiempoIniciostamp = $tiempoObjetivoJS->timestamp;
                                                    @endphp

                                                    class="badge bg-highlight rounded rounded-s color-white d-flex flex-row gap-1 justify-content-center align-items-center mb-0
                                                    {{ $isBadgeVisible ? "" : "d-none"}}">

                                                    {{-- The countdown display area --}}
                                                    <span id="countdown-timer" data-target="{{ $tiempoIniciostamp }}">
                                                        Calculando...
                                                    </span>

                                                    <i data-lucide="{{ $iconoTiempoLucide }}" class="lucide-icon"
                                                        style="width: 1rem; height: 1rem;"></i>
                                                </p>
                                            @endif

                                            <!-- <p class="badge bg-highlight rounded rounded-s color-white d-flex flex-row gap-1 justify-content-center align-items-center mb-0 ">
                                                                                                                                                                                                                                                                                                                                                                        <span>{{ $mensajeTiempo }}</span>
                                                                                                                                                                                                                                                                                                                                                                        <i data-lucide="{{ $iconoTiempoLucide }}" class="lucide-icon" style="width: 1rem; height: 1rem;"></i>
                                                                                                                                                                                                                                                                                                                                                                    </p> -->

                                            @if (count($pedidos) >= 2)
                                                <p
                                                    class="badge bg-delight-red rounded rounded-s color-white d-flex flex-row gap-1 justify-content-center align-items-center mb-0 ">
                                                    <span>Porciones: {{ count($pedidos) }}</span>
                                                    <i data-lucide="utensils" class="lucide-icon"
                                                        style="width: 1rem; height: 1rem;"></i>
                                                </p>
                                            @endif
                                        </div>




                                    </div>
                                </div>

                                <div class="d-flex flex-row justify-content-evenly w-100 align-items-center">
                                    {{-- FIX: Using align-items-center is usually better than align-items-between --}}

                                    <div class="plan-summary flex-shrink-1 text-wrap {{ $plan->editable ? '': 'd-none'}}"> {{-- FIX: Ocultar en pedidos no editables --}}
                                        <div class="d-flex flex-column gap-2 text-wrap">
                                            <strong class="day-title color-theme font-18 text-wrap">
                                                {{ $textodia }}
                                            </strong>
                                        </div>
                                    </div>

                                    @if ($plan->editable == 1 && count($pedidos) >= 1)
                                        {{-- Something here if the condition is met (it's empty now) --}}
                                    @else
                                        <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}"
                                            class="btn flex-shrink-0 bg-delight-red rounded-s color-white font-13"
                                            style="font-weight: 700 !important;">Controlar Plan</a>
                                    @endif
                                </div>
                            </div>
                        </button>
                    </div>
                    <!-- Contenido colapsable del acordeón -->
                    <div id="planCollapse" class="accordion-collapse" aria-labelledby="planHeader"
                        data-bs-parent="#planAccordion">
                        @php
                            $menuItems = [
                                'SOPA' => [
                                    'label' => 'Sopa',
                                    'icon' => 'soup',
                                ],
                                'PLATO' => [
                                    'label' => 'Principal',
                                    'icon' => 'utensils-crossed',
                                ],
                                'CARBOHIDRATO' => [
                                    'label' => 'Carbohidrato',
                                    'icon' => 'sprout',
                                ],
                                'ENSALADA' => [
                                    'label' => 'Ensalada',
                                    'icon' => 'salad',
                                ],
                                'JUGO' => [
                                    'label' => 'Jugo',
                                    'icon' => 'glass-water',
                                ],
                                'EMPAQUE' => [
                                    'label' => 'Empaque',
                                    'icon' => 'package-2',
                                ],
                            ];
                        @endphp

                        @if (count($pedidos) > 1 && $plan->editable)
                            <!-- Listado de pedidos para el plan -->
                            <ul class="list-unstyled d-flex flex-column gap-3 mb-0 mt-2">
                                @foreach ($pedidos as $pedido)
                                    <li>
                                        @php
                                            $numeroPedido = 'pedido' . $loop->iteration;
                                            $pedidoHeaderId = $numeroPedido . 'Header';
                                            $pedidoCollapseId = $numeroPedido . 'Collapse';
                                            $detallePedido = $pedido->detalle
                                                ? json_decode($pedido->detalle, true)
                                                : [];
                                            $loopPedido = $loop->iteration;
                                        @endphp

                                        @if (empty($detallePedido))
                                            <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}?pedido={{ $pedido->id }}"
                                                class="btn pedido-pendiente btn-s bg-highlight bg-dtheme-blue rounded rounded-s m-0 ">
                                                <div class="d-flex flex-row justify-content-between align-items-center">
                                                    <h3 class="mb-0 color-white font-18">Pedido {{ $loop->iteration }}</h3>
                                                    <p
                                                        class="badge bg-delight-red rounded rounded-s color-white mb-0 font-12">
                                                        ¡Pendiente!</p>
                                                </div>
                                            </a>
                                        @else
                                            <div class="card card-style bg-transparent rounded-s mx-0 mb-0">
                                                <div class="accordion-header" id="{{ $pedidoHeaderId }}">
                                                    <button type="button"
                                                        class="accordion-button pedido-accordion-button btn rounded rounded-s bg-highlight bg-dtheme-blue py-2 collapsed"
                                                        data-bs-toggle="collapse" data-bs-target="#{{ $pedidoCollapseId }}"
                                                        aria-expanded="false" aria-controls="{{ $pedidoCollapseId }}">
                                                        <div
                                                            class="d-flex flex-row align-items-center justify-content-between w-100 me-2">
                                                            <h3 class="mb-0 color-white font-18">Pedido
                                                                {{ $loop->iteration }}</h3>
                                                            @if ($pedido->detalle != null)
                                                                <p
                                                                    class="badge bg-green-dark rounded rounded-s color-white mb-0 font-12">
                                                                    Guardado</p>
                                                            @endif
                                                        </div>
                                                    </button>
                                                </div>
                                                <div id="{{ $pedidoCollapseId }}" class="accordion-collapse collapse"
                                                    aria-labelledby="{{ $pedidoHeaderId }}">
                                                    <div
                                                        class="accordion-body m-0 mt-0 px-2 py-0 card card-style bg-dtheme-dkblue">
                                                        <div class="row m-0">
                                                            @php
                                                                $iteracionValidaDetalle = 0;
                                                            @endphp
                                                            @foreach ($menuItems as $key => $item)
                                                                @if (isset($detallePedido[$key]) && $detallePedido[$key] != '')
                                                                    @php
                                                                        $iteracionValidaDetalle++;
                                                                        $bgClass =
                                                                            $iteracionValidaDetalle % 2 !== 0
                                                                                ? 'bg-delight-red'
                                                                                : 'bg-highlight';
                                                                        $colorClass =
                                                                            $iteracionValidaDetalle % 2 !== 0
                                                                                ? 'color-delight-red'
                                                                                : 'color-highlight';
                                                                    @endphp
                                                                    <div class="col-6 px-1 py-2">
                                                                        <div
                                                                            class="d-flex flex-row gap-2 align-items-center">
                                                                            <!-- <div class="{{ $bgClass }} rounded-circle d-flex align-items-center justify-content-center p-1" style="height: 1.8rem; width: 1.8rem"> -->
                                                                            <i data-lucide="{{ $item['icon'] }}"
                                                                                class="lucide-icon color-theme"
                                                                                style="min-height: 1.8rem; min-width: 1.8rem"></i>
                                                                            <!-- </div> -->
                                                                            <div class="d-flex flex-column">
                                                                                <h3
                                                                                    class="detalle-label line-height-s font-15 {{ $colorClass }}">
                                                                                    {{ $item['label'] }}</h3>
                                                                                <span
                                                                                    class="item-value line-height-xs font-12 m-0 color-theme">
                                                                                    {{ ucfirst($detallePedido[$key]) }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @if (isset($detallePedido['ENVIO']) && $detallePedido['ENVIO'] != '')
                                                        <hr class="my-2">
                                                        <div
                                                            class="accordion-body m-0 card card-style py-1 px-3 gap-2 bg-dtheme-dkblue d-flex flex-row justify-content-evenly w-100">
                                                            <div
                                                                class="d-flex flex-row gap-2 color-theme align-items-center">
                                                                <!-- <div class="gradient-blue rounded rounded-circle d-flex align-items-center justify-content-center p-1" style="height: 1.8rem; width: 1.8rem"> -->
                                                                <i data-lucide="truck" class="lucide-icon color-theme"
                                                                    style="height: 2rem; width: 2rem"></i>
                                                                <!-- </div> -->
                                                                <!-- <div class="d-flex flex-column"> -->
                                                                <span
                                                                    class="font-12 item-value color-theme m-0 line-height-s w-auto">{{ $detallePedido['ENVIO'] }}</span>
                                                                <!-- </div> -->
                                                            </div>
                                                            @if ($plan->editable != 0 && $pedido->estado == 'pendiente')
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#confirmarModificarPedidoModal"
                                                                    data-url="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}?pedido={{ $pedido->id }}"
                                                                    class="btn rounded-s gradient-blue d-flex align-items-center justify-content-center m-0 py-1 px-2 w-auto modificar-pedido-trigger"
                                                                    {{ $pedido->estado == 'finalizado' ? 'disabled' : '' }}>
                                                                    <span
                                                                        class="w-auto color-white text-center font-13 font-700 line-height-s">Modificar
                                                                        Pedido</span>
                                                                </a>
                                                            @else
                                                                <!-- <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}"
                                                                                                                                                                                                                                                                                                                                                                                    class="btn btn-xs flex-shrink-0 gradient-blue rounded-s color-white font-13" style="font-weight: 700 !important;">Controlar Plan</a> -->
                                                                <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}"
                                                                    class="btn rounded-s gradient-blue d-flex align-items-center justify-content-center m-0 py-1 px-2 w-auto">
                                                                    <span
                                                                        class="w-auto color-white text-center font-13 font-700 line-height-s">Controlar
                                                                        Plan</span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @elseif (count($pedidos) == 1 && $plan->editable)
                            <!-- Pedido individual -->
                            @php
                                $pedido = $pedidos->first();
                                $detallePedido = $pedido->detalle ? json_decode($pedido->detalle, true) : [];
                            @endphp
                            <div class="card card-style bg-transparent rounded-s mt-0 mx-0 mb-0">
                                @if (empty($detallePedido))
                                    <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}?pedido={{ $pedido->id }}"
                                        class="btn btn-s bg-highlight bg-dtheme-blue rounded rounded-s m-0">
                                        <div class="d-flex flex-row justify-content-between align-items-center">
                                            <h3 class="mb-0 color-white font-18">Pedido</h3>
                                            <p class="badge bg-delight-red rounded rounded-s color-white mb-0 font-12">
                                                ¡Pendiente!</p>
                                        </div>
                                    </a>
                                @else
                                    <div class="m-0 p-0 card card-style bg-dtheme-dkblue">
                                        <div class="row m-0">
                                            @php
                                                $iteracionValidaDetalle = 0;
                                            @endphp
                                            @foreach ($menuItems as $key => $item)
                                                @if (isset($detallePedido[$key]) && $detallePedido[$key] != '')
                                                    @php
                                                        $iteracionValidaDetalle++;
                                                        $bgClass =
                                                            $iteracionValidaDetalle % 2 !== 0
                                                                ? 'bg-delight-red'
                                                                : 'bg-highlight';
                                                        $colorClass =
                                                            $iteracionValidaDetalle % 2 !== 0
                                                                ? 'color-delight-red'
                                                                : 'color-highlight';
                                                    @endphp
                                                    <div class="col-6 px-1 py-2">
                                                        <div class="d-flex flex-row gap-2 align-items-center">
                                                            <!-- <div class="{{ $bgClass }} rounded-circle d-flex align-items-center justify-content-center p-1" style="height: 1.8rem; width: 1.8rem"> -->
                                                            <i data-lucide="{{ $item['icon'] }}"
                                                                class="lucide-icon color-theme"
                                                                style="min-height: 1.8rem; min-width: 1.8rem"></i>
                                                            <!-- </div> -->
                                                            <div class="d-flex flex-column">
                                                                <h3
                                                                    class="detalle-label line-height-s font-15 {{ $colorClass }}">
                                                                    {{ $item['label'] }}</h3>
                                                                <span
                                                                    class="item-value line-height-xs font-12 m-0 color-theme">
                                                                    {{ ucfirst($detallePedido[$key]) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @if (isset($detallePedido['ENVIO']) && $detallePedido['ENVIO'] != '')
                                        <hr class="my-2">
                                        <div
                                            class="accordion-body m-0 card card-style py-1 px-3 gap-2  bg-dtheme-dkblue d-flex flex-row justify-content-evenly w-100">
                                            <div class="d-flex flex-row gap-2 color-theme align-items-center">
                                                <!-- <div class="gradient-blue rounded rounded-circle d-flex align-items-center justify-content-center p-1" style="height: 1.8rem; width: 1.8rem"> -->
                                                <i data-lucide="truck" class="lucide-icon color-theme"
                                                    style="height: 2rem; width: 2rem"></i>
                                                <!-- </div> -->
                                                <!-- <div class="d-flex flex-column"> -->
                                                <span
                                                    class="font-12 item-value color-theme m-0 line-height-s w-auto">{{ $detallePedido['ENVIO'] }}</span>
                                                <!-- </div> -->
                                            </div>
                                            @if ($plan->editable != 0 && $pedido->estado == 'pendiente')
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#confirmarModificarPedidoModal"
                                                    data-url="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}?pedido={{ $pedido->id }}"
                                                    class="btn rounded-s gradient-blue d-flex align-items-center justify-content-center m-0 py-1 px-2 w-auto modificar-pedido-trigger"
                                                    {{ $pedido->estado == 'finalizado' ? 'disabled' : '' }}>
                                                    <span
                                                        class="w-auto color-white text-center font-13 font-700 line-height-s">Modificar
                                                        Pedido</span>
                                                </a>
                                            @else
                                                <!-- <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}"
                                                                                                                                                                                                                                                                                                                                                                        class="btn btn-xs flex-shrink-0 gradient-blue rounded-s color-white font-13" style="font-weight: 700 !important;">Controlar Plan</a> -->
                                                <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}"
                                                    class="btn rounded-s gradient-blue d-flex align-items-center justify-content-center m-0 py-1 px-2 w-auto">
                                                    <span
                                                        class="w-auto color-white text-center font-13 font-700 line-height-s">Controlar
                                                        Plan</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        <div class="modal fade" id="confirmarModificarPedidoModal" tabindex="-1"
            aria-labelledby="confirmarModificarPedidoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content mx-2">
                    <div class="modal-header border-0 pb-0 align-self-center">
                        <h1 class="modal-title fw-bold  color-delight-red" id="confirmarModificarPedidoLabel">
                            ¿Modificar el pedido?
                        </h1>
                    </div>
                    <div class="modal-body pt-2 pb-3">
                        <p class="text-muted text-center font-15 mb-0">
                            Tendrás que armar tu pedido nuevamente
                        </p>
                    </div>
                    <div
                        class="modal-footer bg-white bg-dtheme-blue border-0 pt-0 d-flex flex-row align-items-center justify-content-between px-3">

                        <a href="#"
                            class="py-2 px-2 font-15 rounded-s text-uppercase bg-delight-red color-white font-600 line-height-s"
                            data-bs-dismiss="modal">Cancelar</a>
                        <a id="confirmarModificarPedido"
                            class="py-2 px-2 wrapper font-15 bg-teal-dark rounded-s line-height-s text-uppercase font-600 shadow-xl">
                            <span class="d-flex flex-row align-items-center gap-1">Confirmar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    <!-- Estilos específicos para el acordeón del plan -->
    <style>
        .plan-accordion-item {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 0 15px;
            background: transparent;
        }

        .plan-accordion-button {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
            border-radius: 20px !important;
            position: relative;
            overflow: hidden;
            min-height: 120px;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .plan-accordion-button:not(.collapsed) {
            background: transparent !important;
            box-shadow: none !important;
        }

        .plan-accordion-button:focus {
            box-shadow: none !important;
            border: none !important;
        }

        .plan-accordion-button::after {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .plan-accordion-button:not(.collapsed)::after {
            transform: rotate(180deg);
            background: rgba(255, 255, 255, 0.4);
        }

        .plan-header-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.5rem;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        .day-info {
            display: flex;
            align-items: center;
            /* justify-content: space; */
            gap: 1.5rem;
            width: 100%;
        }

        .day-icon-container {
            position: relative;
        }

        .day-icon {
            animation: float 3s ease-in-out infinite;
            /* filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2)); */
        }

        .day-text {
            display: flex;
            flex-direction: column;
        }

        .day-title {
            font-size: 18px;
            font-weight: 900;
            margin: 0;
            opacity: 0.9;
        }

        .day-greeting {
            font-size: 14px;
            margin: 0;
            opacity: 0.8;
        }

        .plan-summary {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
        }

        /* .plan-chevron {
                                                                                                                                                                                                                                                                                                                                    transform: rotate(0deg);
                                                                                                                                                                                                                                                                                                                                    transition: transform 0.3s step-start;
                                                                                                                                                                                                                                                                                                                                } */

        /* .plan-accordion-button.collapsed .plan-chevron {
                                                                                                                                                                                                                                                                                                                                    transform: rotate(180deg);
                                                                                                                                                                                                                                                                                                                                } */


        .time-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .plan-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 8px;
        }

        .plan-info small {
            font-size: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .badge-success {
            background-color: #28a745 !important;
            color: white !important;
        }

        .badge-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .badge-info {
            background-color: #17a2b8 !important;
            color: white !important;
        }

        .badge-primary {
            background-color: #007bff !important;
            color: white !important;
        }

        .plan-accordion-body {
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 25px;
        }

        .menu-details {
            margin-bottom: 0px;
        }

        .no-details {
            display: flex;
            align-items: center;
            gap: 10px;
            /* color: rgba(255, 255, 255, 0.7); */
            font-style: italic;
            padding: 20px;
            text-align: center;
            justify-content: center;
        }

        .menu-items {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 12px 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .item-icon {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            /* color: white; */
            font-size: 16px;
        }

        .item-content {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .item-label {
            font-size: 12px;
            /* color: rgba(255, 255, 255, 0.7); */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .item-value span {
            font-size: 10px;
            font-weight: 500;
            margin-top: 2px;
        }

        .detalle-label {
            margin-bottom: 0;
        }

        .additional-info {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 12px;
            /* color: rgba(255, 255, 255, 0.9); */
        }

        .info-item i {
            font-size: 10px;
        }

        .schedule-info {
            display: flex;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 12px 15px;
            margin-top: 5px;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            font-size: 13px;
            font-weight: 500;
        }

        .schedule-item i {
            font-size: 14px;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .plan-header-content {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
                text-align: left;
            }

            .plan-summary {
                align-items: flex-start;
                text-align: left;
            }

            .schedule-info {
                flex-direction: column;
                gap: 8px;
            }

            .plan-accordion-button::after {
                top: 15px;
                right: 15px;
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }
    </style>
@endisset
@endauth
