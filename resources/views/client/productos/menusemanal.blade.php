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
    <!-- Estilos para el acordeón de convenios -->
    <style>
        .convenio-card {
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .convenio-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .convenio-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        .convenio-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .convenio-content {
            /* background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%); */
            padding: 25px;
            border-radius: 0 0 15px 15px;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .convenio-icon {
            width: 50px;
            height: 50px;
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .productos-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }

        .producto-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .producto-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .convenio-footer .alert {
            border: none;
            border-radius: 15px;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.2);
        }

        .convenio-footer .alert i {
            color: #28a745;
            font-size: 1.2rem;
        }

        /* Animación de entrada para los badges */
        .producto-badge {
            animation: slideInUp 0.5s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .producto-badge:nth-child(1) {
            animation-delay: 0.1s;
        }

        .producto-badge:nth-child(2) {
            animation-delay: 0.2s;
        }

        .producto-badge:nth-child(3) {
            animation-delay: 0.3s;
        }

        .producto-badge:nth-child(4) {
            animation-delay: 0.4s;
        }

        .producto-badge:nth-child(5) {
            animation-delay: 0.5s;
        }

        .producto-badge:nth-child(6) {
            animation-delay: 0.6s;
        }

        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .convenio-content {
                padding: 20px 15px;
                padding-top: 10px !important;
            }

            .producto-badge {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .convenio-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }
    </style>

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











        @if (auth()->user()->convenios->first())
            <div class="card card-style" id="accordion-3">
                <style>
                    .btn-svg-bg {
                        position: relative;
                        overflow: hidden;
                        background: transparent;
                    }

                    .btn-svg-figure {
                        position: absolute;
                        right: 12px;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 80px;
                        height: auto;
                        pointer-events: none;
                        opacity: .95;
                    }
                </style>
                <div class="accordion">
                    <div data-card-height="90" class="card card-style mb-0 rounded-m convenio-card m-0 p-0"
                        style="height: 90px; background-image: url('{{ asset('imagenes/delight/default-bg-horizontal.jpg') }}'); background-size: cover; background-position: center;">
                        <div class="card-center">
                            <button class="btn accordion-btn collapsed btn-svg-bg" data-bs-toggle="collapse"
                                data-bs-target="#collapse7" aria-expanded="false" style="padding-right: 110px;">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div class="text-start">
                                        <h5 class="color-white text-uppercase mb-1">
                                            Convenio Disponible!
                                        </h5>
                                        <p class="color-white opacity-90 mb-0 font-400 font-11">
                                            <i class="fa fa-gift me-1"></i>Haz click para ver los detalles
                                        </p>
                                    </div>
                                    <div class="convenio-badge">
                                        <a href="#"
                                            class="btn btn-xxs btn-full mb-0 rounded-xl text-uppercase font-700 shadow-s bg-orange-light"
                                            style="">
                                            {{ auth()->user()->convenios->first()->valor_descuento }}% OFF
                                        </a>
                                    </div>
                                </div>
                                <!-- SVG decorativo: pega aquí el contenido de tu SVG (limpio, con solo viewBox) -->
                                <svg class="btn-svg-figure" viewBox="0 0 507.39935 356.73134" aria-hidden="true"
                                    xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#"
                                    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                    xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                    xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" width="143.19937mm"
                                    height="100.67751mm" viewBox="0 0 507.39935 356.73134" id="svg6917" version="1.1"
                                    inkscape:version="0.91 r13725" sodipodi:docname="handshake.svg">
                                    <defs id="defs6919" />
                                    <sodipodi:namedview id="base" pagecolor="#ffffff" bordercolor="#666666"
                                        borderopacity="1.0" inkscape:pageopacity="0.0" inkscape:pageshadow="2"
                                        inkscape:zoom="0.24748737" inkscape:cx="296.88464" inkscape:cy="97.22243"
                                        inkscape:document-units="px" inkscape:current-layer="layer1" showgrid="false"
                                        fit-margin-top="10" fit-margin-left="10" fit-margin-right="10"
                                        fit-margin-bottom="10" inkscape:window-width="1366" inkscape:window-height="705"
                                        inkscape:window-x="-8" inkscape:window-y="-8" inkscape:window-maximized="1" />
                                    <metadata id="metadata6922">
                                        <rdf:RDF>
                                            <cc:Work rdf:about="">
                                                <dc:format>image/svg+xml</dc:format>
                                                <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
                                                <dc:title></dc:title>
                                            </cc:Work>
                                        </rdf:RDF>
                                    </metadata>
                                    <g inkscape:label="Layer 1" inkscape:groupmode="layer" id="layer1"
                                        transform="translate(-126.89063,-329.45424)">
                                        <g id="g7535" transform="translate(-28.284271,-391.93919)">
                                            <path
                                                sodipodi:nodetypes="csssssssssssssssssssssscssssssssscsscsscsssscsssscsssscscsssssscc"
                                                inkscape:connector-curvature="0" id="path7476"
                                                d="m 458.75076,1012.1367 c 5.43876,-2.5808 13.3921,-15.4573 13.3921,-21.6818 0,-4.4072 -3.99627,-8.9704 -11.14644,-12.7277 -9.30153,-4.8878 -11.46906,-7.3301 -7.5964,-8.5592 2.39972,-0.7617 9.37466,2.4195 22.31349,10.177 19.23583,11.5327 21.32447,12.0476 27.22908,6.7132 4.26636,-3.8544 9.59129,-14.1349 10.22264,-19.7362 0.4358,-3.8665 0.33056,-4.0438 -4.58504,-7.7219 -2.76709,-2.0705 -11.96697,-7.7443 -20.44417,-12.6084 -8.47719,-4.8642 -15.83555,-9.5137 -16.35189,-10.3322 -1.38447,-2.1949 0.92163,-4.458 4.54263,-4.458 2.97132,0 4.24035,0.6862 26.59902,14.3831 11.88798,7.2825 21.1836,11.8669 24.0621,11.8669 5.75458,0 16.40498,-15.7118 16.40498,-24.2012 0,-6.9623 -4.99951,-10.7999 -34.17902,-26.23526 -7.71252,-4.07977 -14.43806,-8.07615 -14.94563,-8.88082 -1.43494,-2.27486 0.984,-4.43272 4.96902,-4.43272 4.36383,0 14.4086,4.79417 25.1069,11.98304 17.74861,11.9264 22.53712,13.50729 28.93555,9.55284 5.60525,-3.46423 12.32749,-16.43403 11.19356,-21.59672 -0.61789,-2.81325 -5.20648,-7.46312 -9.36163,-9.48666 -1.54688,-0.75333 -15.04688,-8.56862 -30,-17.36732 -37.16083,-21.86614 -68.42018,-39.65394 -79.87018,-45.44928 l -9.55768,-4.83755 -3.85914,2.67438 c -5.9827,4.14601 -8.09019,7.20362 -8.82366,12.80163 -1.16375,8.88186 -5.2501,28.7579 -7.63822,37.15237 -2.64769,9.30689 -5.24062,12.33295 -12.24904,14.29519 -10.72349,3.00239 -37.03373,-3.6643 -45.48952,-11.52648 -7.21589,-6.70933 -7.7089,-15.30064 -2.24563,-39.13294 5.2147,-22.74798 10.45987,-41.2431 12.47565,-43.99067 0.97472,-1.32857 7.09457,-6.01158 13.59967,-10.40669 6.50511,-4.39511 12.10619,-8.26984 12.44685,-8.6105 1.48651,-1.48651 -11.92852,-6.88335 -16.08488,-6.47091 -0.57519,0.0571 -9.6455,5.72267 -20.15625,12.59021 -24.38676,15.93388 -40.10772,25.54796 -47.28228,28.91525 l -5.67181,2.662 -13.07819,-3.22466 c -10.88254,-2.68328 -56.52363,-15.55585 -72.87045,-20.55231 l -4.47977,-1.36925 -2.20155,4.36939 c -8.78404,17.43357 -17.90771,51.75911 -20.22411,76.08814 -1.36403,14.32632 -1.72038,55.84586 -0.48981,57.06984 0.75855,0.75446 31.66763,9.71716 74.72807,21.66886 l 6.29057,1.746 3.29762,-4.59 c 1.81368,-2.5245 4.98735,-5.7083 7.05258,-7.075 3.24869,-2.1499 4.48401,-2.4289 9.16183,-2.0698 6.28816,0.4827 13.27193,3.5747 21.31175,9.4355 l 5.59242,4.0767 5.43681,-5.8119 c 7.614,-8.1394 10.47657,-9.347 18.87093,-7.9607 7.02174,1.1596 13.91808,3.7959 16.83855,6.4369 0.97605,0.8827 2.62535,3.2923 3.66512,5.3548 1.03976,2.0625 2.02214,3.8984 2.18307,4.0797 0.16092,0.1814 3.32981,-1.1897 7.04196,-3.0468 5.5445,-2.7738 7.7729,-3.3765 12.48281,-3.3759 10.43325,0 22.44987,7.5637 27.11287,17.0628 1.34126,2.7322 2.63204,5.6432 2.86842,6.4687 0.30103,1.0512 1.98556,1.6614 5.62297,2.0368 10.20899,1.0537 21.97463,9.4796 26.47832,18.9622 3.36935,7.0944 2.70829,13.6359 -2.09293,20.7109 -2.82397,4.1613 -2.37108,5.6125 2.53272,8.1158 4.01824,2.0512 6.71274,2.0703 10.91269,0.077 z"
                                                style="fill:#3abbba;fill-opacity:0.79607843" />
                                            <path
                                                sodipodi:nodetypes="sssssssssssssssssssscssssssssssscssssscscsscccssssscssscssssscscs"
                                                inkscape:connector-curvature="0" id="path7476-2"
                                                d="m 414.42296,1041.2875 c 6.82418,-6.8242 24.9074,-43.23995 24.9074,-50.15821 0,-3.93574 -2.10814,-7.61628 -6.53081,-11.40192 -5.54162,-4.74342 -14.33309,-6.84419 -18.7499,-4.48039 -0.94597,0.50627 -8.09011,11.83254 -15.87587,25.16952 -12.22717,20.945 -14.15592,24.792 -14.15592,28.2346 0,5.3846 3.35206,8.4126 12.83645,11.5953 8.42985,2.8289 15.36956,3.2402 17.56865,1.0411 z m -38.37385,-25.0053 c 9.13778,-12.1073 30.92168,-47.8979 30.82616,-50.64685 -0.11389,-3.27711 -5.3014,-10.96221 -9.20522,-13.63715 -5.29227,-3.62632 -18.64352,-4.95695 -22.61334,-2.25372 -0.54581,0.37167 -7.19034,12.22957 -14.76563,26.35088 -16.029,29.88014 -16.2382,30.65374 -9.78885,36.19724 4.11502,3.5371 18.2639,10.9821 19.92188,10.4828 0.51562,-0.1553 3.04687,-3.0772 5.625,-6.4932 z m -38.123,-17.66741 c 3.75813,-1.31009 9.08724,-9.23761 18.45359,-27.45137 4.41608,-8.58746 7.01316,-14.74673 7.01316,-16.63249 0,-6.20944 -12.94592,-14.89193 -22.20444,-14.89193 l -4.9613,0 -13.24049,19.82787 c -12.62947,18.91284 -13.2172,20.00152 -12.73579,23.59071 0.27759,2.06956 1.30682,4.85405 2.2872,6.18775 2.26474,3.08097 10.5603,7.61557 16.63607,9.09377 6.05996,1.47435 5.37538,1.45279 8.752,0.27569 z m -34.38315,-23.58517 c 4.36412,-4.46079 10.89032,-14.07292 11.67349,-17.19331 0.66079,-2.63281 -2.34586,-8.45143 -6.35081,-12.29042 -4.19911,-4.02509 -9.04608,-5.90689 -15.21449,-5.90689 -6.25755,0 -8.92451,1.96783 -15.65417,11.55052 -5.20509,7.41178 -5.82943,11.08931 -2.73321,16.09911 3.42382,5.53985 14.04864,11.62853 20.39576,11.68801 3.47499,0.0325 4.48464,-0.47293 7.88343,-3.94702 z m 268.082,-38.09701 c 28.32323,-7.38375 51.62058,-13.95824 52.46031,-14.80423 2.80629,-2.82726 4.20855,-57.25479 1.88812,-73.28608 -2.12415,-14.67524 -8.50961,-33.49663 -18.4408,-54.35497 -4.90255,-10.29674 -6.24383,-12.40273 -7.62268,-11.96866 -0.92032,0.28971 -19.06249,4.95229 -40.31594,10.36127 l -38.64264,9.8345 -26.98236,-16.28057 c -14.8403,-8.95431 -32.22606,-19.27909 -38.63504,-22.94397 l -11.65267,-6.6634 -9.45109,0 -9.45108,0 -6.08374,4.104 c -3.34605,2.2572 -14.50473,10.48009 -24.79706,18.27309 -21.47516,16.26028 -19.07758,12.59934 -24.33262,37.15416 -4.21828,19.71043 -5.57074,30.43284 -5.06175,40.1302 0.5104,9.72444 1.70881,11.7869 8.89055,15.30059 4.70077,2.29988 6.4588,2.63252 13.86281,2.62306 5.77037,-0.007 9.58192,-0.49801 11.93056,-1.53572 3.15725,-1.395 3.64707,-2.15012 5.75053,-8.86526 1.26462,-4.0372 4.09267,-15.62809 6.28455,-25.75753 l 3.98524,-18.41715 10.15869,-7.12972 c 5.58728,-3.92134 10.42061,-7.12972 10.74073,-7.12972 1.63908,0 126.07298,72.14843 129.02738,74.81179 8.28807,7.4716 9.54975,17.9392 3.57988,29.70079 -4.04744,7.9741 -10.75301,15.2455 -14.80683,16.05627 l -3.01515,0.60302 0,7.95187 c 0,6.33146 0.26062,7.85185 1.27898,7.46108 0.70343,-0.26994 9.45734,-2.62286 19.45312,-5.22871 z"
                                                style="fill:#ff681e;fill-opacity:1" />
                                        </g>
                                    </g>
                                </svg>
                            </button>
                        </div>
                        <div class="card-overlay rounded-s" style="background: rgba(0,0,0,.55);"></div>
                    </div>
                    <div id="collapse7" class="collapse" data-bs-parent="#accordion-3">
                        <div class="convenio-content">
                            <div class="convenio-header">
                                <div class="d-flex align-items-center mb-0">
                                    <div class="row mb-0">
                                        <div class="col-12 col-md-12">
                                            <h4 class="font-700">Descuento del
                                                {{ auth()->user()->convenios->first()->valor_descuento }}%</h4>
                                            <p class="mt-n2 mb-2">
                                                Aplicable en los siguientes productos:
                                            </p>
                                        </div>
                                        <div class="row mb-0">
                                            @foreach (json_decode(auth()->user()->convenios->first()->productos_afectados) as $producto)
                                                <a href="{{ route('detalleproducto', $producto) }}">
                                                    <p class="col-12 font-600 mb-0">
                                                        <i class="fa fa-check-circle color-highlight font-15"></i>
                                                        {{ Str::limit(\App\Helpers\GlobalHelper::obtenerNombresProductos($producto), 45) }}

                                                        <i class="ms-2 fa fa-link fa-beat"></i>

                                                    </p>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-0 p-0 mt-3">
                                <small class="m-0 p-0 text-center opacity-60">
                                    Disponible hasta el
                                    {{ \App\Helpers\GlobalHelper::fechaFormateada(3, auth()->user()->convenios->first()->fecha_limite) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


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
@push('scripts')
<script>
    $(document).ready( async function() {
        $(document).on('click', '#abrir-venta-qr', async () => {
            // Mockup functionalidad escaneado de QR
            console.log("Simulando escaneado de QR");
            // Asumiendo escaneado exitoso
            // Generacion de una nueva venta con el identificador del usuario
            try {
                const respuestaQR = await VentaService.generarVentaQR();
                if (respuestaQR.status === 200) {
                    await sincronizarCarrito();
                }
            } catch (error) {
                if (error.response && error.response.status === 409) {
                    // De ya existir una venta, se procede con la sincronizacion del carrito
                    // Esto puede utilizarse para mencionarle al cliente que ya dispone de atencion a sus pedidos
                    console.log("Ya existe una venta activa, Procediendo a sincronizar el carrito.");
                    await sincronizarCarrito();
                } else {
                    // Control de Error, puede usarse para mencionarle al cliente que el escaneado
                    // del QR no funcionó
                    console.error("Error al procesar el código QR:", error);
                }
            }

            
        });

        $(document).on('click', '#cerrar-venta-qr', () => {
            console.log("Simulando proceso cerrado de venta");
            // Culminar ciclo de venta sea aceptando o rechazando los producto_venta relacionados
            // a la venta activa del cliente
        });

        const sincronizarCarrito = async () => {
            const carrito = carritoStorage.obtenerCarrito();
            console.log("Carrito:", carrito);
            // Sincronizacion de base de datos con elementos actuales en el carrito
            const respuestaSincronizacion = await VentaService.generarProductosVenta_Carrito(carrito)
            console.log("Sincronización de productos exitosa:", respuestaSincronizacion);
            // Eliminar elmentos existentes en el carrito para evitar nuevos registros indeseados 
            // y abusos en generacion de producto_venta 
            carritoStorage.vaciarCarrito();
        }
    });
</script>
@endpush