@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Bienvenidos a Delight" cabecera="bordeado" />

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
                    <div class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="width: 382px;">
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
                                        <img src="{{ asset('delight_logo.jpg') }}" width="23" class="rounded-xs me-2">
                                    </div>
                                    <div class="align-self-center">
                                        <a href="#" class="color-white font-14 d-block font-500 opacity-80">by Delight
                                        </a>
                                    </div>
                                    <div class="align-self-center ms-auto">
                                        <strong class="font-300 color-white opacity-30">Nutriendo tus habitos</strong>
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


    <div class="card card-style pb-3">
        <div class="content">
            <h4>Almuerzos Saludables</h4>
            <p>
                Nuestros menus cambian cada semana y tenemos varias opciones!
            </p>
        </div>
        <div class="accordion mb-2" id="accordion-3">
            @foreach ($almuerzos as $almuerzo)
                <div data-card-height="90" class="card card-style bg-25 mb-0 rounded-s m-3"
                    style="height: 90px;background-image:url({{ asset('imagenes/delight/21.jpeg') }}">
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
                                <p class="font-400">{{ $almuerzo->sopa }}</p>
                            </div>
                            <div class="divider mb-3"></div>
                            <div class="col-5">
                                <p class="color-theme font-700">Segundo Ejecutivo</p>
                            </div>
                            <div class="col-7">
                                <p class="font-400">{{ $almuerzo->ejecutivo }}</p>
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Segundo Dieta</p>
                            </div>
                            <div class="col-7">
                                <p class="font-400">{{ $almuerzo->dieta }}</p>
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Segundo Veggie</p>
                            </div>
                            <div class="col-7">
                                <p class="font-400">{{ $almuerzo->vegetariano }}</p>
                            </div>
                            <div class="divider mb-3"></div>
                            <div class="col-5">
                                <p class="color-theme font-700">Carbohidrato 1</p>
                            </div>
                            <div class="col-7">
                                <p class="font-400">{{ $almuerzo->carbohidrato_1 }}</p>
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Carbohidrato 2</p>
                            </div>
                            <div class="col-7">
                                <p class="font-400">{{ $almuerzo->carbohidrato_2 }}</p>
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Carbohidrato 3</p>
                            </div>
                            <div class="col-7">
                                <p class="font-400">{{ $almuerzo->carbohidrato_3 }}</p>
                            </div>
                            <div class="divider mb-3"></div>
                            <div class="col-5">
                                <p class="color-theme font-700">Jugo</p>
                            </div>
                            <div class="col-7">
                                <p class="font-400">{{ $almuerzo->jugo }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
    </div>
    @auth
        


        <div data-card-height="140" class="card card-style round-medium shadow-huge top-30"
            style="height: 140px;background-image:url('{{ asset('imagenes/delight/4.jpeg') }}')">
            <div class="card-top mt-3 ms-3">
                <h2 class="color-white pt-3 pb-3">{{ Str::limit(auth()->user()->name,25) }}</h2>

            </div>
            <div class="card-top mt-3 me-3">
                <a href="{{route('miperfil')}}"
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
                <p class="color-white font-10 opacity-70">Delight by Macrobyte</p>
            </div>
            <div class="card-overlay bg-highlight opacity-40"></div>
            <div class="card-overlay bg-gradient"></div>
        </div>
    @endauth
    <div class="card card-style mb-0" style="">
        <div class="card mb-0 bg-0" data-card-height="570"
            style="height: 570px;background-image:url({{ asset('imagenes/delight/2.jpeg') }}">
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
