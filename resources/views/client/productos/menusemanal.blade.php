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
    <x-cabecera-pagina titulo="Bienvenidos a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}" cabecera="bordeado" />


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
                    <div id="collapse{{ $almuerzo->id }}" class="collapse" data-bs-parent="#accordion-3" style="">
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
    <div class="splide single-slider slider-no-arrows slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active"
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
                                        <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}" width="23"
                                            class="rounded-xs me-2">
                                    </div>
                                    <div class="align-self-center">
                                        <a href="#" class="color-white font-14 d-block font-500 opacity-80">by
                                            {{GlobalHelper::getValorAtributoSetting('nombre_sistema')}}
                                        </a>
                                    </div>
                                    <div class="align-self-center ms-auto">
                                        <strong class="font-300 color-white opacity-30">{{GlobalHelper::getValorAtributoSetting('slogan')}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="card-overlay bg-gradient"></div>
                        </div>
                    </div>
                @endforeach


            </div>
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
                <p class="color-white font-10 opacity-70">{{GlobalHelper::getValorAtributoSetting('nombre_sistema')}} by Macrobyte</p>
            </div>
            <div class="card-overlay bg-highlight opacity-40"></div>
            <div class="card-overlay bg-gradient"></div>
        </div>
    @endauth
    <div class="card card-style mb-0" style="">
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
@endsection
