@extends('client.master')
@section('content-comentado')
    <x-cabecera-pagina titulo="Linea {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!" cabecera="bordeado" />



    <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
        <div class="content mt-2">
            <div class="search-box bg-theme color-theme rounded-m shadow-l">
                <i class="fa fa-search"></i>
                @php
                    $productoRandom = $productos->random();
                @endphp
                <input type="text" class="border-0" placeholder="Busca por ejm: {{ $productoRandom->nombre }}"
                    data-search="">
                <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
            </div>
            <div class="search-results disabled-search-list mt-3">
                <div class="card card-style mx-0 px-2 p-0 mb-0">
                    @foreach ($productos as $item)
                        <a href="{{ route('delight.detalleproducto', $item->id) }}" class="d-flex py-2"
                            data-filter-item="{{ Str::of($item->nombre)->lower() }}"
                            data-filter-name="{{ Str::of($item->nombre)->lower() }}">
                            <div class="align-self-center">
                                <img src="{{ asset($item->pathAttachment()) }}" class="rounded-sm me-3" width="35"
                                    alt="img">
                            </div>
                            <div class="align-self-center">
                                <span
                                    class="color-theme font-15 d-block mb-0">{{ Str::limit(ucfirst(strtolower($item->nombre)), 35, '...') }}</span>
                            </div>
                            <div class="ms-auto text-center align-self-center pe-2">
                                <h5 class="line-height-xs font-16 font-600 mb-0">{{ $item->precio }} Bs<sup
                                        class="font-11"></sup></h5>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="search-no-results disabled mt-4">
            <div class="card card-style">
                <div class="content">
                    <h1>Ups!</h1>
                    <p>
                        No existen coincidencias <span class="fa-fw select-all fas"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="splide double-slider slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active"
        id="double-slider-1a" style="visibility: visible;">
        <div class="splide__track" id="double-slider-1a-track">
            <div class="splide__list" id="double-slider-1a-list"
                style="transform: translate(-1162px, 0px); transition: transform 400ms cubic-bezier(0.42, 0.65, 0.27, 0.99) 0s;">
                @foreach ($subcategorias as $item)
                    <div class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="width: 166px;">
                        <a href="{{ route('delight.listar.productos.subcategoria', $item->id) }}" class="mx-3">
                            <div class="card card-style me-0 mb-0"
                                style="background-image: url('{{ asset($item->rutaFoto()) }}'); height: 250px;"
                                data-card-height="250">
                                <div class="card-bottom p-2 px-3">
                                    <h4 class="color-white">{{ $item->nombre }}</h4>
                                </div>
                                <div class="card-overlay bg-gradient opacity-80"></div>
                            </div>
                        </a>
                    </div>
                @endforeach


            </div>
        </div>
        <ul class="splide__pagination">
            <li><button class="splide__pagination__page" type="button"
                    aria-controls="double-slider-1a-slide01 double-slider-1a-slide02" aria-label="Go to page 1"></button>
            </li>
            <li><button class="splide__pagination__page" type="button"
                    aria-controls="double-slider-1a-slide03 double-slider-1a-slide04" aria-label="Go to page 2"></button>
            </li>
            <li><button class="splide__pagination__page is-active" type="button"
                    aria-controls="double-slider-1a-slide04 double-slider-1a-slide05" aria-label="Go to page 3"
                    aria-current="true"></button></li>
        </ul>
    </div>

    <div data-card-height="140" class="card card-style round-medium shadow-huge top-30"
        style="height: 140px;background-image:url('{{ asset(GlobalHelper::getValorAtributoSetting('inicio_disfruta')) }}')">
        <div class="card-top mt-3 ms-3">
            <h2 class="color-white pt-3 pb-3">Planes Saludables!</h2>

        </div>
        <div class="card-top mt-3 me-3">
            <a href="{{ route('categoria.planes') }}"
                class="float-end bg-white color-black btn btn-s rounded-xl font-900 mt-2 text-uppercase font-11">Ver
                planes</a>
        </div>

        <div class="card-bottom ms-3 mb-3">
            <i class="fa fa-heart font-25 color-white"></i>
        </div>
        <div class="card-bottom mb-n3 ps-5 ms-4">
            <h5 class="font-13 color-white mb-n1">Encuentra uno para ti!</h5>
            <p class="color-white font-10 opacity-70">{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} by Macrobyte</p>
        </div>

        <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    @if ($enDescuento->count() > 0)
        <div class="d-flex px-3 mb-2">
            <h4 class="mb-2 font-600">Productos en descuento!</h4>
        </div>


        <div class="splide single-slider slider-no-arrows slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active"
            id="single-slider-2" style="visibility: visible;">
            <div class="splide__arrows"><button class="splide__arrow splide__arrow--prev" type="button"
                    aria-controls="single-slider-2-track" aria-label="Previous slide"><svg
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40">
                        <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z">
                        </path>
                    </svg></button><button class="splide__arrow splide__arrow--next" type="button"
                    aria-controls="single-slider-2-track" aria-label="Go to first slide"><svg
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40">
                        <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z">
                        </path>
                    </svg></button></div>
            <div class="splide__track" id="single-slider-2-track">
                <div class="splide__list" id="single-slider-2-list" style="transform: translateX(-1328px);">
                    @foreach ($enDescuento as $item)
                        <div class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                            style="width: 332px;">
                            <div data-card-height="400" class="card mx-3 rounded-m shadow-l"
                                style="background-image: url({{ asset($item->pathAttachment()) }}); height: 400px;">
                                <div class="card-top">
                                    <a href="#"
                                        class="bg-theme color-theme rounded-sm icon icon-xs float-end m-3"><i
                                            class="far fa-shopping-bag font-12"></i></a>
                                </div>
                                <div class="card-bottom p-3 m-2 rounded-m bg-white">
                                    <a href="#">
                                        <h1 class="font-14 line-height-m font-700 mb-0">
                                            {{ Str::limit($item->nombre(), 50) }}
                                        </h1>
                                        <p class="mb-0">
                                            {{ Str::limit($item->detalle(), 60) }}
                                        </p>
                                    </a>
                                    <div class="d-flex pt-3">
                                        <div class="align-self-center">
                                            <strong class="font-800 font-22 color-theme"><small
                                                    class="text-secondary"><del>{{ $item->precio }}</del></small><span>
                                                    {{ $item->descuento }} Bs</span> </strong>
                                        </div>
                                        <div class="align-self-center ms-auto">
                                            <a href="#"
                                                class="btn-s rounded-s btn bg-highlight font-700 text-uppercase mb-1 carrito"
                                                id="{{ $item->id }}">Añadir <i class="fa fa-shopping-cart"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <ul class="splide__pagination">
                <li><button class="splide__pagination__page" type="button" aria-controls="single-slider-2-slide01"
                        aria-label="Go to slide 1"></button></li>
                <li><button class="splide__pagination__page" type="button" aria-controls="single-slider-2-slide02"
                        aria-label="Go to slide 2"></button></li>
                <li><button class="splide__pagination__page is-active" type="button"
                        aria-controls="single-slider-2-slide03" aria-label="Go to slide 3" aria-current="true"></button>
                </li>
            </ul>
        </div>
    @endif
    @if ($conMasPuntos->count() > 0)
        <div class="card preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
            style="background-image: url({{ asset(GlobalHelper::getValorAtributoSetting('gana_puntos')) }});">
            <div class="card-body">
                <h4 class="color-white pt-3 font-24">Gana Puntos!</h4>
                <p class="color-white pt-1">
                    Mientras mas puntos, mas premios!
                </p>
                <div class="card card-style bg-transparent m-0 shadow-0">
                    <div class="row mb-0">
                        @foreach ($conMasPuntos as $item)
                            <div class="col-6 ps-2">
                                <a href="{{ route('delight.detalleproducto', $item->id) }}"
                                    class="card card-style mx-0 mb-3" data-menu="menu-product">
                                    <img src="{{ asset($item->pathAttachment()) }}" alt="img" width="100"
                                        class="mx-auto mt-2">
                                    <div class="p-2">
                                        <h4 class="mb-0 font-600">{{ Str::limit($item->nombre(), 20) }}</h4>
                                        <p class="mb-0 font-11 mt-n1">Acumula puntos por su compra!</p>
                                    </div>
                                    <div class="divider mb-0"></div>
                                    <h5 class="py-3 pb-2 px-2 font-13 font-600">
                                        {{ $item->descuento ? $item->descuento : $item->precio }} Bs
                                        <span
                                            class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl float-end">{{ $item->puntos }}
                                            Pts</span>
                                    </h5>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-highlight opacity-90"></div>
            <div class="card-overlay dark-mode-tint"></div>
        </div>
    @endif
@endsection

@section('content')
    <x-cabecera-pagina titulo="Linea Delight" cabecera="bordeado" />
    <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
        <div class="content mt-2">
            <div class="search-box bg-theme color-theme rounded-m shadow-l">
                <i class="fa fa-search"></i>
                @php
                    $productoRandom = $productos->random();
                @endphp
                <input type="text" class="border-0" placeholder="Busca por ejm: {{ $productoRandom->nombre }}"
                    data-search="">
                <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
            </div>
            <div class="search-results disabled-search-list mt-3">
                <div class="card card-style mx-0 px-2 p-0 mb-0">

                    @foreach ($productos as $item)
                        <a href="{{ route('delight.detalleproducto', $item->id) }}" class="d-flex py-2"
                            data-filter-item="{{ Str::of($item->nombre)->lower() }}"
                            data-filter-name="{{ Str::of($item->nombre)->lower() }}">
                            <div class="align-self-center">
                                {{-- <img src="{{ asset($item->pathAttachment()) }}" class="rounded-sm me-3" width="35"
                                    alt="img"> --}}
                                <i class="fa fa-search"></i>
                            </div>
                            <div class="align-self-center ps-2">
                                <span
                                    class="color-theme font-15 d-block mb-0">{{ Str::limit(ucfirst(strtolower($item->nombre)), 35, '...') }}</span>
                            </div>
                            <div class="ms-auto text-center align-self-center pe-2">
                                <h5 class="line-height-xs font-16 font-600 mb-0">{{ $item->precio }} Bs<sup
                                        class="font-11"></sup></h5>
                            </div>
                        </a>
                    @endforeach


                </div>
            </div>
        </div>
        <div class="search-no-results disabled mt-4">
            <div class="card card-style">
                <div class="content">
                    <h1>Ups!</h1>
                    <p>
                        No existen coincidencias <span class="fa-fw select-all fas"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="content mb-0">
        <div class="row mb-0">
            {{-- <div data-card-height="140" class="card card-style col-12 mx-0 mt-1 px-0 round-medium shadow-huge"
                style="height: 140px;background-image:url('{{ asset('imagenes/delight/default-bg-horizontal.jpg') }}')">
                <div class="card-center d-flex flex-row align-items-center">
                    <div class="">
                        <i class="fa fa-arrow-up"></i>
                    </div>
                    <div>
                        <h2>Productos Populares</h2>
                        <p>Descrubre nuestros articulos mas vendidos</p>
                    </div>
                    <div class="d-flex flex-row gap-2 align-items-center">
                        <span>Ver Todos</span>
                        <i class="fa fa-arrow-right"></i>
                    </div>
                </div>

                <div class="card-overlay bg-gradient opacity-80"></div>
            </div> --}}
            <a href="{{ route('delight.listar.populares') }}" data-card-height="100" class="card card-style col-12 mx-0 mt-1 px-0 round-medium shadow-huge hover-grow-xs" 
                style="height: 100px;background-color: #FF5A5A;">
                <div class="card-center d-flex flex-row align-items-center justify-content-between ps-4 pe-3">
                    <div class="d-flex flex-row align-items-center gap-3">
                        {{-- <img class="mb-1" src="{{ asset('imagenes/delight/logo_white.svg') }}" alt="Logo" style="width: 50px; height: 50px;"> --}}
                        <i class="fa fa-apple-alt fa-3x" style="color: white"></i>
                        <div class="text-start">
                            <h2 class="text-white">Productos Populares</h2>
                            <p class="mb-0 text-white opacity-75">Descubre nuestros artículos más vendidos</p>
                        </div>
                    </div>
                    <!-- Right section with "Ver Todos" -->
                    {{-- <div class="d-flex fl   ex-row gap-2 align-items-center">
                        <span class="text-white font-weight-bold">Ver Todos</span>
                        <i class="fa fa-arrow-right text-white"></i>
                    </div> --}}
                    <i class="fa fa-arrow-circle-right fa-2x" style="color: white"></i>
                </div>
                <div class="card-overlay dark-mode-tint"></div>
            </a>
            <div class="col-6">
                <a href="{{ route('delight.listar.subcategorias.horario', "manana") }}" data-card-height="125" class="card card-style mb-4 mx-0 hover-grow" style="height: 125px;">
                    <div class="card-center text-center">
                        <i class="fa fa-coffee font-30 pt-3 color-red-dark"></i>
                        <h5 class="pt-2">Mañana</h5>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('delight.listar.subcategorias.horario', "tarde") }}" data-card-height="125" class="card card-style mb-4 mx-0 hover-grow" style="height: 125px;">
                    <div class="card-center text-center">
                        <i class="fa fa-sun font-30 pt-3 color-green-dark"></i>
                        <h5 class="pt-2">Tarde</h5>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('delight.listar.subcategorias.horario', "noche") }}" data-card-height="125" class="card card-style mb-4 mx-0 hover-grow" style="height: 125px;">
                    <div class="card-center text-center">
                        <i class="fa fa-moon font-30 pt-3 color-magenta-dark"></i>
                        <h5 class="pt-2">Noche</h5>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('delight.listar.productos.subcategoria', 53) }}" data-card-height="125" class="card card-style mb-4 mx-0 hover-grow" style="height: 125px;">
                    <div class="card-center text-center">
                        <i class="fa fa-heartbeat font-30 pt-3 color-orange-dark"></i>
                        <h5 class="pt-2">Fitness</h5>
                    </div>
                </a>
            </div>
            {{-- <div data-card-height="120" class="card card-style col-12 mx-0 my-0 px-0 round-medium shadow-huge hover-grow-xs"
                style="height: 120px;background-image:url('{{ asset(GlobalHelper::getValorAtributoSetting('inicio_disfruta')) }}')">
                <div class="d-flex flex-row justify-content-between mx-3 mt-4 align-items-center">
                    <h2 class="color-white pb-0">Planes y paquetes</h2>
                    <a href="{{ route('categoria.planes') }}"
                        class="float-end bg-white color-black btn btn-s rounded-xl font-900 text-uppercase font-11">
                        Ver planes
                    </a>
                </div>
                <div class="d-flex flex-row card-bottom mx-3 align-items-center gap-2 mb-2">
                        <i class="fa fa-calendar font-25 color-white"></i>
                    <div>
                        <h5 class="font-13 color-white mb-n1">Encuentra uno para ti!</h5>
                        <p class="color-white font-10 opacity-70">{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} by Macrobyte</p>
                    </div>   
                </div>
                <div class="card-overlay bg-gradient opacity-80"></div>
            </div> --}}
            <a href="{{ route('categoria.planes') }}" data-card-height="100" class="card card-style col-12 mx-0 my-0 px-0 round-medium shadow-huge hover-grow-xs"
                style="height: 100px;background-color: #4ECDC4">
                <div class="card-center d-flex flex-row align-items-center justify-content-between ps-4 pe-3">
                    <div class="d-flex flex-row align-items-center gap-3">
                        <i class="fa fa-calendar fa-3x" style="color: white"></i>
                        <!-- Middle section with text -->
                        <div class="text-start">
                            <h2 class="text-white">Planes y paquetes</h2>
                            <p class="mb-0 text-white opacity-75">Encuentra el adecuado para ti!</p>
                        </div>
                    </div>
                    <!-- Right section with "Ver Todos" -->
                    {{-- <a href="{{ route('categoria.planes') }}"
                        class="float-end bg-white color-black btn btn-s rounded-xl font-900 text-uppercase font-11 hover-grow">
                        Ver planes
                    </a> --}}
                    <i class="fa fa-arrow-circle-right fa-2x" style="color: white"></i>
                </div>
                <div class="card-overlay dark-mode-tint"></div>
            </a>

            {{-- <div class="col-4">
                <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                    <div class="card-center text-center">
                        <i class="fa fa-credit-card pt-3 font-30 color-gray-dark"></i>
                        <h6 class="pt-2">Cards</h6>
                        <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                    </div>
                </a>
            </div>
            <div class="col-4">
                <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                    <div class="card-center text-center">
                        <i class="fa fa-cog font-30 pt-3 color-blue-dark"></i>
                        <h5 class="pt-2">Service</h5>
                        <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                    </div>
                </a>
            </div>
            <div class="col-4">
                <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                    <div class="card-center text-center">
                        <i class="fab fa-usb font-30 pt-3 color-dark-dark"></i>
                        <h5 class="pt-2">Storage</h5>
                        <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                    </div>
                </a>
            </div> --}}
        </div>
    </div>
    
    <div class="divider divider-margins"></div>

    @if ($conMasPuntos->count() > 0)
        {{-- <div class="card card-style rounded-md mx-0 preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
            style="background-image: url({{ asset(GlobalHelper::getValorAtributoSetting('gana_puntos')) }});"> --}}
        <div class="card card-style rounded-md mx-0 preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
            style="background-image: url({{ asset('imagenes/delight/default-bg-vertical.jpg') }});">
            <div class="card-body">
                <h4 class="color-white pt-3 font-24">Gana Puntos!</h4>
                <p class="color-white pt-1">
                    Los productos seleccionados atribuyen puntos por cada compra realizada.
                    Mientras mas puntos, mas premios!
                </p>
                <div class="card card-style bg-transparent m-0 shadow-0">
                    <div class="row mb-0 p-2">
                        {{-- @foreach ($conMasPuntos as $item)
                            <div class="col-6 ps-2">
                                <a href="{{ route('delight.detalleproducto', $item->id) }}"
                                    class="card card-style mx-0 mb-3" data-menu="menu-product">
                                    <img src="{{ asset($item->pathAttachment()) }}" alt="img" width="100"
                                        class="mx-auto mt-2">
                                    <div class="p-2">
                                        <h4 class="mb-0 font-600">{{ Str::limit($item->nombre(), 20) }}</h4>
                                        <p class="mb-0 font-11 mt-n1">Acumula puntos por su compra!</p>
                                    </div>
                                    <div class="divider mb-0"></div>
                                    <h5 class="py-3 pb-2 px-2 font-13 font-600">
                                        {{ $item->descuento ? $item->descuento : $item->precio }} Bs
                                        <span
                                            class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl float-end">{{ $item->puntos }}
                                            Pts</span>
                                    </h5>
                                </a>
                            </div>
                        @endforeach --}}
                        @foreach ($conMasPuntos as $item)
                            <div class="col-6">
                                <a href="{{ route('delight.detalleproducto', $item->id) }}"
                                    class="card card-style py-3 d-flex align-items-center hover-grow" data-menu="menu-product">
                                    <img src="{{ asset('imagenes/delight/optimal_logo.svg')}}" alt="img" width="100"
                                    class="mx-auto">
                                    <div class="p-2">
                                        <p class="mb-0 font-600 text-center">{{ Str::limit($item->nombre(), 22) }}</p>
                                    </div>
                                    <div class="divider mb-0"></div>
                                    <div class="d-flex flex-row justify-content-between gap-4 mb-0">
                                        <p class="font-600 mb-0">{{ $item->descuento ? $item->descuento : $item->precio }} Bs</p>
                                        <p class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl mb-0">{{ $item->puntos }} Pts</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-highlight opacity-90"></div>
            <div class="card-overlay dark-mode-tint"></div>
        </div>
    @endif
    <!-- footer and footer card-->
    {{-- <div class="footer" data-menu-load="menu-footer.html"><div class="footer card card-style mx-0 mb-0">
        <a href="#" class="footer-title pt-4">AZURES</a>
        <p class="text-center font-12 mt-n1 mb-3 opacity-70">
            Put a little <span class="color-highlight">color</span> in  your life
        </p>
        <p class="boxed-text-l">
            Built to match the design trends and give your page the awesome facelift it deserves.
        </p>
        <div class="text-center mb-3">
            <a href="#" class="icon icon-xs rounded-sm shadow-l me-1 bg-facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="icon icon-xs rounded-sm shadow-l me-1 bg-twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="icon icon-xs rounded-sm shadow-l me-1 bg-phone"><i class="fa fa-phone"></i></a>
            <a href="#" data-menu="menu-share" class="icon icon-xs rounded-sm me-1 shadow-l bg-red-dark"><i class="fa fa-share-alt"></i></a>
            <a href="#" class="back-to-top icon icon-xs rounded-sm shadow-l bg-highlight color-white"><i class="fa fa-arrow-up"></i></a>
        </div>
            <p class="footer-copyright pb-3 mb-1">Copyright © Enabled <span id="copyright-year">2025</span>. All Rights Reserved.</p>
        </div>
        <div class="footer-card card shape-rounded bg-20" style="height:230px">
            <div class="card-overlay bg-highlight opacity-90"></div>
        </div>
    </div>   --}}
@endsection
