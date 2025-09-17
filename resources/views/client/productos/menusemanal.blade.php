@extends('client.master')

@push('header')
    <style>
        .gradient-border {
            --borderWidth: 3px;
            background: #1D1F20;
            position: relative;
            border-radius: var(--borderWidth);
        }

        .gradient-border:after {
            content: '';
            position: absolute;
            top: calc(-1 * var(--borderWidth));
            left: calc(-1 * var(--borderWidth));
            height: calc(100% + var(--borderWidth) * 2);
            width: calc(100% + var(--borderWidth) * 2);
            background: linear-gradient(60deg, #f79533, #f37055, #ef4e7b, #a166ab, #5073b8, #1098ad, #07b39b, #6fba82);
            border-radius: calc(2 * var(--borderWidth));
            z-index: -1;
            animation: animatedgradient 3s ease alternate infinite;
            background-size: 300% 300%;
        }


        @keyframes animatedgradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
@endpush
@section('content')
    {{-- <x-cabecera-pagina titulo="Bienvenidos a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}" cabecera="appkit" /> --}}

    <x-cabecera-pagina titulo="Bienvenidos" cabecera="appkit" />


    {{-- <div class="splide single-slider slider-no-arrows slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active"
        id="single-slider-2" style="visibility: visible;">
        <div class="splide__arrows"><button class="splide__arrow splide__arrow--prev" type="button"
                aria-controls="single-slider-2-track" aria-label="Previous slide"><svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 40 40" width="40" height="40">
                    <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                </svg></button><button class="splide__arrow splide__arrow--next" type="button"
                aria-controls="single-slider-2-track" aria-label="Next slide"><svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 40 40" width="40" height="40">
                    <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                </svg></button></div>
        <div class="splide__track" id="single-slider-2-track">
            <div class="splide__list" id="single-slider-2-list" style="transform: translateX(-1146px);">
                @foreach ($galeria as $foto)
                    <div class="splide__slide splide__slide--clone " aria-hidden="true" tabindex="-1"
                        style="width: 382px;">
                        <div data-card-height="300" class="card bg-28 mx-3 rounded-l shadow-l"
                            style="height: 300px;background-image:url({{ asset('imagenes/galeria/' . $foto->foto) }})">
                            <div class="card-top">
                                <span
                                    class="badge bg-red-dark text-uppercase p-2 rounded-s m-4">{{ $foto->titulo }}</span>
                            </div>
                            <div class="card-top">
                                <a href="#" class="bg-theme color-theme rounded-sm icon icon-xs float-end m-3"><i
                                        class="far fa-bookmark font-12"></i></a>
                            </div>
                            <div class="card-bottom px-3 mb-3">
                                <a href="#">
                                    <h1 class="font-18 line-height-m color-white font-500 mb-0">
                                        {{ $foto->descripcion }}
                                    </h1>
                                </a>
                                <div class="d-flex pt-3">
                                    <div class="align-self-center">
                                        <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}"
                                            width="23" class="rounded-xs me-2">
                                    </div>
                                    <div class="align-self-center">
                                        <a href="#" class="color-white font-14 d-block font-500 opacity-80">by
                                            {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}
                                        </a>
                                    </div>
                                    <div class="align-self-center ms-auto">
                                        <strong
                                            class="font-300 color-white opacity-30">{{ GlobalHelper::getValorAtributoSetting('slogan') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="card-overlay bg-gradient"></div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>

    </div> --}}
    @auth
        @php
            $horaActual = Carbon\Carbon::now()->hour;
            $periodoDia = '';
            $gradienteClass = '';
            $iconoSvg = '';

            if ($horaActual >= 6 && $horaActual < 12) {
                // Mañana: 6:00 - 11:59
                $periodoDia = 'manana';
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
                    <circle cx="12" cy="16" r="4" fill="url(#afternoonGradient)" stroke="#FF6347" stroke-width="1"/>
                    <!-- Rayos del sol más cortos -->
                    <path d="M12 8v2M12 20v2M6.5 6.5l1.5 1.5M16 16l1.5 1.5M6.5 17.5l1.5-1.5M16 8l1.5-1.5M8 12h2M14 12h2" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>
                    <!-- Nubes de la tarde -->
                    <path d="M4 14c0-1.5 1-2.5 2.5-2.5s2.5 1 2.5 2.5c1 0 2 1 2 2s-1 2-2 2H6c-1.5 0-2.5-1-2.5-2.5z" fill="url(#cloudGradient)"/>
                    <path d="M16 12c0-1 1-2 2-2s2 1 2 2c1 0 1.5 0.5 1.5 1.5s-0.5 1.5-1.5 1.5H18c-1 0-2-1-2-2z" fill="url(#cloudGradient)"/>
                    <!-- Montañas en el horizonte -->
                    <path d="M0 20L6 16L12 18L18 14L24 16V24H0V20Z" fill="#8B4513" opacity="0.3"/>
                    <!-- Reflejo del sol -->
                    <ellipse cx="12" cy="18" rx="3" ry="1" fill="#FFD700" opacity="0.4"/>
                </svg>';
            } else {
                // Noche: 18:00 - 5:59
                $periodoDia = 'noche';
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
        @endphp

        <style>
            .gradient-morning {
                background: linear-gradient(135deg, #1b8dbb 0%, #9ad3ea 30%, #18b4c8 70%, #4fa6f2 100%);
                box-shadow: 0 4px 15px rgba(135, 206, 235, 0.3);
            }

            .gradient-afternoon {
                background: linear-gradient(135deg, #c16a00 0%, #d1b000 30%, #e59708 70% 70%, #ffc35a 100%);
                box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
            }

            .gradient-night {
                background: linear-gradient(135deg, #191970 0%, #000080 30%, #483D8B 70%, #000000 100%);
                box-shadow: 0 4px 15px rgba(25, 25, 112, 0.3);
            }

            .gradient-morning:hover,
            .gradient-afternoon:hover,
            .gradient-night:hover {
                transform: translateY(-2px);
                transition: transform 0.3s ease;
            }

            .day-icon {
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-5px);
                }
            }

            .day-icon svg {
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            }

            .plan-accordion-button:not(.collapsed)::after {
                background: none !important;
                border: none !important;
            }

            .plan-accordion-button::after {
                background: none !important;
                border: none !important;
            }
        </style>



        @php
            $planData = GlobalHelper::planInteligenteSegunHora(
                auth()->user()->id,
                Carbon\Carbon::now()->format('Y-m-d'),
                Carbon\Carbon::now()->format('H:i'),
            );
        @endphp

        @isset($planData)
            @php
                $plan = $planData->plan;
                $detalle = $plan->pivot->detalle ? json_decode($plan->pivot->detalle, true) : [];
                // dd($detalle, $plan);
                $estado = $planData->estado;
                $tiempoRestanteMinutos = $planData->tiempo_restante;
                $planesRestantes = $planData->planes_restantes;
                $fechaPlan = $planData->fecha_plan;
                // Formatear tiempo restante
                if ($tiempoRestanteMinutos >= 60) {
                    $horas = floor($tiempoRestanteMinutos / 60);
                    $minutos = $tiempoRestanteMinutos % 60;
                    $tiempoRestante = $horas . 'h ' . $minutos . 'm';
                } else {
                    $tiempoRestante = $tiempoRestanteMinutos . ' min';
                }
                // Determinar el mensaje según el estado
                switch ($estado) {
                    case 'en_curso':
                        $mensajeTiempo = 'Termina en ' . $tiempoRestante;
                        $iconoTiempo = 'fa-clock';
                        $colorTiempo = 'success';
                        break;
                    case 'proximo':
                        $mensajeTiempo = 'Inicia en ' . $tiempoRestante;
                        $iconoTiempo = 'fa-hourglass-start';
                        $colorTiempo = 'warning';
                        break;
                    case 'proximo_dia':
                        $mensajeTiempo = 'Mañana en ' . $tiempoRestante;
                        $iconoTiempo = 'fa-calendar-plus';
                        $colorTiempo = 'info';
                        break;
                    default:
                        $mensajeTiempo = 'Disponible';
                        $iconoTiempo = 'fa-check';
                        $colorTiempo = 'primary';
                }
            @endphp

            <!-- Acordeón del Plan -->
            <div class="card card-style pb-0">
                <div class="content m-0 p-0">
                    <div class="accordion mb-1" id="planAccordion">
                        <div class="accordion-item plan-accordion-item">
                            <!-- Header del acordeón (siempre visible) -->
                            <div class="accordion-header" id="planHeader">
                                <button class="accordion-button plan-accordion-button {{ $gradienteClass }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#planCollapse" aria-expanded="false"
                                    aria-controls="planCollapse">
                                    <div class="plan-header-content"
                                        style="padding-right: 2% !important; padding-left: 2% !important;">
                                        <div class="day-info">
                                            <div class="day-icon-container">
                                                <div class="day-icon">
                                                    {!! $iconoSvg !!}
                                                </div>
                                            </div>
                                            <div class="day-text">
                                                @if ($periodoDia == 'manana')
                                                    <p class="day-greeting">¡Buenos días!☀️</p>
                                                    <h2 class="day-title">
                                                        {{ App\Helpers\GlobalHelper::fechaFormateada(11, Carbon\Carbon::now()) }}
                                                    </h2>
                                                @elseif($periodoDia == 'tarde')
                                                    <p class="day-greeting">¡Buenas tardes! 🌤️</p>
                                                    <h2 class="day-title">
                                                        {{ App\Helpers\GlobalHelper::fechaFormateada(11, Carbon\Carbon::now()) }}
                                                    </h2>
                                                @else
                                                    <p class="day-greeting">¡Buenas noches! 🌙</p>
                                                    <h2 class="day-title">
                                                        {{ App\Helpers\GlobalHelper::fechaFormateada(11, Carbon\Carbon::now()) }}
                                                    </h2>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="plan-summary">
                                            <h5 class="plan-title">{{ $plan->nombre }}</h5>
                                            <!-- Badge de tiempo con estado -->
                                            <div class="badge badge-{{ $colorTiempo }} badge-outline-{{ $colorTiempo }} mb-0">
                                                <i class="fa {{ $iconoTiempo }} me-1"></i>
                                                <span>{{ $mensajeTiempo }}</span>
                                            </div>
                                            <!-- Información adicional -->
                                            <div class="plan-info mt-0 pt-0">
                                                @if ($plan->pivot->cocina != 'despachado')
                                                    @if ($estado == 'en_curso')
                                                        <small class="text-success">
                                                            <i class="fa fa-play-circle me-1"></i>
                                                            Plan en curso
                                                        </small>
                                                    @elseif($estado == 'proximo')
                                                        <small class="text-warning">
                                                            <i class="fa fa-pause-circle me-1"></i>
                                                            Próximo plan
                                                        </small>
                                                    @elseif($estado == 'proximo_dia')
                                                        <small class="text-info">
                                                            <i class="fa fa-calendar me-1"></i>
                                                            Plan de mañana
                                                        </small>
                                                    @endif
                                                    @if ($planesRestantes > 0)
                                                        <small class="text-muted d-block">
                                                            <i class="fa fa-list me-1"></i>
                                                            {{ $planesRestantes }} plan{{ $planesRestantes > 1 ? 'es' : '' }}
                                                            restante{{ $planesRestantes > 1 ? 's' : '' }}
                                                        </small>
                                                    @endif
                                                @else
                                                    <small class="text-primary">
                                                        <i class="fa fa-motorcycle me-1"></i>
                                                        {{ ucfirst($plan->pivot->cocina) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <!-- Contenido colapsable del acordeón -->
                            <div id="planCollapse" class="accordion-collapse collapse" aria-labelledby="planHeader"
                                data-bs-parent="#planAccordion">
                                <div class="accordion-body plan-accordion-body" style="border-radius: 15px !important;">
                                    <!-- Detalles del menú -->
                                    <div class="menu-details">
                                        @if (empty($detalle))
                                            <div class="no-details">
                                                <i class="fa fa-info-circle"></i>
                                                <span>No hay detalles disponibles.</span>
                                            </div>
                                        @else
                                            <div class="menu-items">
                                                <div class="row mb-0">
                                                    @if (isset($detalle['SOPA']) && $detalle['SOPA'] != '')
                                                        <div class="col-6 px-1 mb-1">
                                                            <div class="menu-item">
                                                                <div class="item-icon">
                                                                    <svg fill="#727272" viewBox="0 0 16 16"
                                                                        xmlns="http://www.w3.org/2000/svg" stroke="#727272"
                                                                        style="width: 17px; height: 17px;">
                                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                        </g>
                                                                        <g id="SVGRepo_iconCarrier">
                                                                            <path
                                                                                d="M0 6h16v2A8 8 0 1 1 0 8V6zm2 2a6 6 0 1 0 12 0H2zm0-7h2v4H2V1zm5-1h2v4H7V0zm5 1h2v4h-2V1z"
                                                                                fill-rule="evenodd"></path>
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                                <div class="item-content">
                                                                    <span class="item-label">Sopa</span>
                                                                    <span class="item-value"
                                                                        style="font-size: 12px !important; line-height: normal;">{{ $detalle['SOPA'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (isset($detalle['PLATO']) && $detalle['PLATO'] != '')
                                                        <div class="col-6 px-1 mb-1">
                                                            <div class="menu-item">
                                                                <div class="item-icon">
                                                                    <i class="fa fa-utensils"></i>
                                                                </div>
                                                                <div class="item-content">
                                                                    <span class="item-label">Principal</span>
                                                                    <span class="item-value"
                                                                        style="font-size: 12px !important; line-height: normal;">{{ $detalle['PLATO'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (isset($detalle['ENSALADA']) && $detalle['ENSALADA'] != '')
                                                        <div class="col-6 px-1 mb-1">
                                                            <div class="menu-item">
                                                                <div class="item-icon">
                                                                    <i class="fa fa-leaf"></i>
                                                                </div>
                                                                <div class="item-content">
                                                                    <span class="item-label">Ensalada</span>
                                                                    <span class="item-value"
                                                                        style="font-size: 12px !important; line-height: normal;">{{ $detalle['ENSALADA'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (isset($detalle['CARBOHIDRATO']) && $detalle['CARBOHIDRATO'] != '')
                                                        <div class="col-6 px-1 mb-1">
                                                            <div class="menu-item">
                                                                <div class="item-icon">
                                                                    <i class="fa fa-seedling"></i>
                                                                </div>
                                                                <div class="item-content">
                                                                    <span class="item-label">Carbohidrato</span>
                                                                    <span class="item-value"
                                                                        style="font-size: 12px !important; line-height: normal;">{{ $detalle['CARBOHIDRATO'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (isset($detalle['JUGO']) && $detalle['JUGO'] != '')
                                                        <div class="col-6 px-1 mb-1">
                                                            <div class="menu-item">
                                                                <div class="item-icon">
                                                                    <i class="fa fa-wine-glass"></i>
                                                                </div>
                                                                <div class="item-content">
                                                                    <span class="item-label">Jugo</span>
                                                                    <span class="item-value"
                                                                        style="font-size: 12px !important; line-height: normal;">{{ $detalle['JUGO'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Información adicional -->
                                            <div class="additional-info">
                                                @if (isset($detalle['ENVIO']) && $detalle['ENVIO'] != '')
                                                    <div class="info-item">
                                                        <i class="fa fa-truck"></i>
                                                        <span>{{ $detalle['ENVIO'] }}</span>
                                                    </div>
                                                @endif
                                                @if (isset($detalle['EMPAQUE']) && $detalle['EMPAQUE'] != '')
                                                    <div class="info-item">
                                                        <i class="fa fa-box"></i>
                                                        <span>{{ $detalle['EMPAQUE'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Horario de disponibilidad -->
                                    <div class="schedule-info">
                                        <div class="schedule-item">
                                            <i class="fa fa-play-circle"></i>
                                            <span>Inicio: {{ $plan->horario->hora_inicio }}</span>
                                        </div>
                                        <div class="schedule-item">
                                            <i class="fa fa-stop-circle"></i>
                                            <span>Fin: {{ $plan->horario->hora_fin }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if ($plan->pivot->cocina != 'despachado')
                                    <div class="row d-flex justify-content-center align-items-center mb-0 mt-3">
                                        <a href="{{ route('calendario.cliente', [$plan->id, auth()->user()->id]) }}"
                                            class="btn btn-xxs mb-3 rounded-s text-uppercase font-700 shadow-s bg-primary  w-50">
                                            Editar Plan
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                    justify-content: space-between;
                    align-items: center;
                    width: 100%;
                    padding: 20px 25px;
                    position: relative;
                    z-index: 2;
                }

                .day-info {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .day-icon-container {
                    position: relative;
                }

                .day-icon {
                    animation: float 3s ease-in-out infinite;
                    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
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

                .item-value {
                    font-size: 14px;
                    /* color: white; */
                    font-weight: 500;
                    margin-top: 2px;
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
                        gap: 15px;
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

    <div class="card card-style pb-3">
        <div class="content">
            <div class="d-flex no-effect" data-trigger-switch="toggle-id-2" data-bs-toggle="collapse"
                href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample2">
                <div class="pt-2 mt-1">
                    <h4>Almuerzos Saludables</h4>
                </div>
                {{-- <div class="ms-auto me-4 pe-2">
                    <div class="custom-control ios-switch ios-switch-icon">
                        <input type="checkbox" class="ios-input" id="toggle-id-2">
                        <label class="custom-control-label" for="toggle-id-2"></label>
                        <i class="fa fa-sun font-11 color-white"></i>
                        <i class="fa fa-moon font-11 color-white"></i>
                    </div>
                </div> --}}
            </div>
            <p>
                Nuestros menus cambian cada semana y tenemos varias opciones!
            </p>
        </div>
        @php
            $diaActual = false;
        @endphp
        <div class="accordion mb-2" id="accordion-3">
            @foreach ($almuerzos as $almuerzo)
                @php
                    $dia = $almuerzo->dia;
                    $feriado = false;
                    if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $dia) {
                        $diaActual = true;
                    }
                    if ($diaActual) {
                        // Convertimos el nombre del día a un formato que Carbon entiende
                        $diaSemana = match ($dia) {
                            'Lunes' => 0,
                            'Martes' => 1,
                            'Miercoles' => 2,
                            'Jueves' => 3,
                            'Viernes' => 4,
                            'Sabado' => 5,
                            'Domingo' => 6,
                            default => null,
                        };
                        // Obtener la fecha del inicio de la semana
                        $fechaInicioSemana = Carbon\Carbon::now()->startOfWeek();

                        // Si es domingo, avanzar al inicio de la próxima semana
                        if (Carbon\Carbon::now()->isSunday()) {
                            $fechaInicioSemana = $fechaInicioSemana->addWeek();
                        }

                        // Obtener la fecha del día específico dentro de la semana actual
                        $fechaDia = $fechaInicioSemana->copy()->addDays($diaSemana)->format('Y-m-d');
                        $feriado = DB::table('plane_user')
                            ->where('start', $fechaDia)
                            ->where('title', 'feriado')
                            ->exists();
                    }
                @endphp
                @if ($diaActual && !$feriado)
                    <div data-card-height="90"
                        class="card card-style bg-25 mb-0 rounded-s m-3 {{ App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia ? 'gradient-border' : '' }}"
                        style="height: 90px;background-image:url({{ asset(GlobalHelper::getValorAtributoSetting('mi_perfil_deligth')) }}">
                        @if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                            <div class="card-top"><i class="fa fa-check color-yellow-dark fa-3x float-end me-3 mt-3"></i>
                            </div>
                        @endif

                        <div class="card-center">
                            <button class="btn accordion-btn collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $almuerzo->id }}" aria-expanded="false">
                                <h4 class="text-center color-white text-uppercase">{{ $almuerzo->dia }}</h4>
                                <p class="text-center color-white opacity-70 mb-0 mt-n2">Descubre el menu para este dia</p>
                            </button>
                        </div>
                        <div class="card-overlay rounded-s bg-black opacity-70"></div>
                    </div>
                    <div id="collapse{{ $almuerzo->id }}" class="collapse" data-bs-parent="#accordion-3"
                        style="">
                        <div class="content">
                            <h4 class="mb-n1">{{ $almuerzo->dia }}</h4>
                            <div class="divider mb-3"></div>
                            <div class="row mb-0">
                                <div class="col-5">
                                    <p class="color-theme font-700">Sopa</p>
                                </div>
                                <div class="col-7">
                                    @if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->sopa }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @else
                                        <p class="font-400">{{ $almuerzo->sopa }} </p>
                                    @endif

                                </div>
                                <div class="divider mb-3"></div>
                                <div class="col-5">
                                    <p class="color-theme font-700">Segundo Ejecutivo</p>
                                </div>
                                <div class="col-7">
                                    @if (
                                        $almuerzo->ejecutivo_estado &&
                                            $almuerzo->ejecutivo_cant > 0 &&
                                            App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->ejecutivo }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <del class="font-400">{{ $almuerzo->ejecutivo }}</del> <i
                                            class="fa fa-times-circle color-red-dark me-2"></i>
                                    @else
                                        <p class="font-400">{{ $almuerzo->ejecutivo }} </p>
                                    @endif
                                </div>
                                <div class="col-5">
                                    <p class="color-theme font-700">Segundo Dieta</p>
                                </div>
                                <div class="col-7">
                                    @if (
                                        $almuerzo->dieta_estado &&
                                            $almuerzo->dieta_cant > 0 &&
                                            App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->dieta }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <del class="font-400">{{ $almuerzo->dieta }}</del> <i
                                            class="fa fa-times-circle color-red-dark me-2"></i>
                                    @else
                                        <p class="font-400">{{ $almuerzo->dieta }} </p>
                                    @endif
                                </div>
                                <div class="col-5">
                                    <p class="color-theme font-700">Segundo Veggie</p>
                                </div>
                                <div class="col-7">
                                    @if (
                                        $almuerzo->vegetariano_estado &&
                                            $almuerzo->vegetariano_cant > 0 &&
                                            App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->vegetariano }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <del class="font-400">{{ $almuerzo->vegetariano }}</del> <i
                                            class="fa fa-times-circle color-red-dark me-2"></i>
                                    @else
                                        <p class="font-400">{{ $almuerzo->vegetariano }}</p>
                                    @endif
                                </div>
                                <div class="divider mb-3"></div>
                                <div class="col-5">
                                    <p class="color-theme font-700">Carbohidrato 1</p>
                                </div>
                                <div class="col-7">
                                    @if (
                                        $almuerzo->carbohidrato_1_estado &&
                                            $almuerzo->carbohidrato_1_cant > 0 &&
                                            App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->carbohidrato_1 }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        {{-- <del class="font-400">{{ $almuerzo->carbohidrato_1 }}</del> <i
                                            class="fa fa-times-circle color-red-dark me-2"></i> --}}
                                    @else
                                        <p class="font-400">{{ $almuerzo->carbohidrato_1 }}</p>
                                    @endif
                                </div>
                                <div class="col-5">
                                    <p class="color-theme font-700">Carbohidrato 2</p>
                                </div>
                                <div class="col-7">
                                    @if (
                                        $almuerzo->carbohidrato_2_estado &&
                                            $almuerzo->carbohidrato_2_cant > 0 &&
                                            App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->carbohidrato_2 }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        {{-- <del class="font-400">{{ $almuerzo->carbohidrato_2 }}</del> <i
                                            class="fa fa-times-circle color-red-dark me-2"></i> --}}
                                    @else
                                        <p class="font-400">{{ $almuerzo->carbohidrato_2 }} </p>
                                    @endif
                                </div>
                                <div class="col-5">
                                    <p class="color-theme font-700">Carbohidrato 3</p>
                                </div>
                                <div class="col-7">
                                    @if (
                                        $almuerzo->carbohidrato_3_estado &&
                                            $almuerzo->carbohidrato_3_cant > 0 &&
                                            App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->carbohidrato_3 }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        {{-- <del class="font-400">{{ $almuerzo->carbohidrato_3 }}</del> <i
                                            class="fa fa-times-circle color-red-dark me-2"></i> --}}
                                    @else
                                        <p class="font-400">{{ $almuerzo->carbohidrato_3 }} </p>
                                    @endif
                                </div>
                                <div class="divider mb-3"></div>
                                <div class="col-5">
                                    <p class="color-theme font-700">Jugo </p>
                                </div>
                                <div class="col-7">
                                    @if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                        <p class="font-400">{{ $almuerzo->jugo }} <i
                                                class="fa fa-check-circle color-green-dark me-2"></i></p>
                                    @else
                                        <p class="font-400">{{ $almuerzo->jugo }} </p>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>
                @elseif($feriado)
                    <div data-card-height="90"
                        class="card card-style bg-25 mb-0 rounded-s m-3 {{ App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia ? 'gradient-border' : '' }}"
                        style="height: 90px;background-image:url({{ asset(GlobalHelper::getValorAtributoSetting('dia_noche_inicio')) }}">

                        <div class="card-center">
                            <button class="btn accordion-btn">
                                <h4 class="text-center color-red-light text-uppercase">{{ $almuerzo->dia }}</h4>
                                <p class="text-center color-white opacity-70 mb-0 mt-n2">Dia sin atención</p>
                            </button>
                        </div>
                        <div class="card-overlay rounded-s bg-black opacity-70"></div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>


    @auth
        <div data-card-height="140" class="card card-style round-medium shadow-huge top-30"
            style="height: 140px;background-image:url('{{ asset(GlobalHelper::getValorAtributoSetting('inicio_perfil')) }}')">
            <div class="card-top mt-3 ms-3">
                <h2 class="color-white pt-3 pb-3">{{ Str::limit(auth()->user()->name, 25) }}</h2>

            </div>
            <div class="card-top mt-3 me-3">
                <a href="{{ route('miperfil') }}"
                    class="float-end bg-white color-black btn btn-s rounded-xl font-900 mt-2 text-uppercase font-11">Ir a mi
                    perfil</a>
            </div>

            <div class="card-bottom ms-3 mb-3">
                @if (auth()->user()->foto)
                    <img data-src="{{ asset('imagenes/perfil/' . auth()->user()->foto) }}" alt="img" width="40"
                        class="pb-1 preload-img shadow-xl rounded-m entered loaded" data-ll-status="loaded"
                        src="{{ asset('imagenes/perfil/' . auth()->user()->foto) }}">
                @else
                    <img data-src="{{ asset('user.png') }}" alt="img" width="40"
                        class="pb-1 preload-img shadow-xl rounded-m entered loaded" data-ll-status="loaded"
                        src="{{ asset('user.png') }}">
                @endif

            </div>
            <div class="card-bottom mb-n3 ps-5 ms-4">
                <h5 class="font-13 color-white mb-n1">Toda tu informacion aqui!</h5>
                <p class="color-white font-10 opacity-70">{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} by
                    Macrobyte</p>
            </div>
            <div class="card-overlay bg-highlight opacity-40"></div>
            <div class="card-overlay bg-gradient"></div>
        </div>
    @endauth


    <div class="card card-style" style="">
        <div class="card mb-0 bg-0" data-card-height="570"
            style="height: 570px;background-image:url({{ asset(GlobalHelper::getValorAtributoSetting('inicio_disfruta')) }}">
            <div class="card-bottom text-center">
                <h1 class="color-white font-26 font-700">Disfruta de todas tus comidas</h1>
                <p class="font-14 color-white px-4 pb-3 opacity-60">
                    Con los ingredientes adecuados para mejorar y preservar tu salud!
                </p>
            </div>
            <div class="card-overlay bg-gradient opacity-70"></div>
        </div>
    </div>

    <a href="#" class="cambiarColor card card-style bg-3" data-card-height="125"
        style="background-image:url({{ asset(GlobalHelper::getValorAtributoSetting('dia_noche_inicio')) }})">
        <div class="card-top">
            @if (isset(auth()->user()->color_page) && auth()->user()->color_page == 'theme-dark')
                <i class="fa fa-sun color-yellow-dark fa-3x float-end me-3 mt-3 color"></i>
            @elseif(isset(auth()->user()->color_page) && auth()->user()->color_page == 'theme-light')
                <i class="fa fa-moon color-yellow-dark fa-3x float-end me-3 mt-3 color"></i>
            @else
                <i class="fa fa-moon color-yellow-dark fa-3x float-end me-3 mt-3 color"></i>
            @endif

        </div>
        <div class="card-bottom">
            <h1 class="color-white font-700 ms-3 mb-n1">Dia o noche?</h1>
            <p class="color-white opacity-60 ms-3">Tu decides! Haz click para cambiar</p>
        </div>
        <div class="card-overlay bg-black opacity-60"></div>
    </a>
@endsection
