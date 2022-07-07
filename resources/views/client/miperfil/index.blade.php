@extends('client.master')
@section('content')
    @auth
        <x-cabecera-pagina titulo="Mi Perfil" cabecera="bordeado" />
        <div class="card card-style preload-img entered loaded" data-src="{{ asset('images/imagen4.jpg') }}"
            data-card-height="450" style="height: 450px; background-image: url(&quot;{{ asset('imagenes/delight/21.jpeg') }}&quot;);"
            data-ll-status="loaded">
            <div class="card-bottom ms-3 ">
                <h1 class="font-40 line-height-xl ">{{ auth()->user()->name }}</h1>
                <a href="{{ route('llenarDatosPerfil') }}"
                    class="btn btn-xxs mb-3 rounded-s text-uppercase font-900 shadow-s border-red-dark  bg-red-light">Editar
                    Perfil </a>
                <p class="pb-0 mb-0 font-12 "><i class="fa fa-map-marker me-2"></i>Tarija, Bolivia</p>
                <p class="">
                    Encuentra toda la informacion sobre tu cuenta en esta pestaña.
                </p>
            </div>
            <div class="card-overlay bg-gradient-fade"></div>
        </div>
        <div class="card card-style">
            <div class="content mb-0">
                <div class="row mb-0 text-center">

                    <div class="col-3">
                        <h1 class="mb-n1">{{ $usuario->puntos }}</h1>
                        <p class="font-10 mb-0 pb-0">Puntos</p>

                    </div>
                    <div class="col-6">
                        <a href="{{ route('usuario.saldo') }}">
                            <h1 class="mb-n1">{{ $usuario->saldo }} Bs @if ($usuario->saldo > 0)
                                    <i class="fa fa-arrow-right rotate-135 color-red-dark"></i>
                                @else
                                    <i class="fa fa-arrow-right rotate-45 color-green-dark"></i>
                                @endif
                            </h1>
                            <p class="font-10 mb-0 pb-0">Saldo</p>

                            <em class="badge bg-highlight color-white">DETALLES</em>
                        </a>
                    </div>
                    <div class="col-3">
                        <h1 class="mb-n1">{{ $usuario->historial_ventas->count() }}</h1>
                        <p class="font-10 mb-0 pb-0">Compras realizadas</p>
                    </div>
                </div>
                <div class="divider mb-4 mt-3"></div>

            </div>
        </div>
        {{-- <div class="splide single-slider slider-no-arrows slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active"
            id="single-slider-3" style="visibility: visible;">
            <div class="splide__arrows"><button class="splide__arrow splide__arrow--prev" type="button"
                    aria-controls="single-slider-3-track" aria-label="Previous slide"><svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 40 40" width="40" height="40">
                        <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                    </svg></button><button class="splide__arrow splide__arrow--next" type="button"
                    aria-controls="single-slider-3-track" aria-label="Go to first slide"><svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 40 40" width="40" height="40">
                        <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                    </svg></button></div>
            <div class="splide__track" id="single-slider-3-track">
                <div class="splide__list" id="single-slider-3-list" style="transform: translateX(-1528px);">
                    <div class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="width: 382px;">
                        <div class="card rounded-m mx-3 bg-red-dark">
                            <div class="content m-2">
                                <div class="d-flex">
                                    <div class="pe-3">
                                        <img src="images/pictures/28s.jpg" width="85" class="rounded-m">
                                    </div>
                                    <div class="ms-auto">
                                        <span class="color-white font-10 mb-n1 d-block opacity-50">John Doe</span>
                                        <h4 class="color-white font-15 font-500">Google plans to release a new Plane this year.
                                        </h4>
                                        <strong class="font-10 opacity-30 font-300 mt-n2 mb-n1 d-block">Jun 25, <span
                                                class="copyright-year"></span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide splide__slide--clone" style="width: 382px;">
                        <div class="card rounded-m mx-3 bg-green-dark">
                            <div class="content m-2">
                                <div class="d-flex">
                                    <div class="pe-3">
                                        <img src="images/pictures/28s.jpg" width="85" class="rounded-m">
                                    </div>
                                    <div class="ms-auto">
                                        <span class="color-white font-10 mb-n1 d-block opacity-50">John Doe</span>
                                        <h4 class="color-white font-15 font-500">New fuel derived from your Facebook likes.</h4>
                                        <strong class="font-10 opacity-30 font-300 mt-n2 mb-n1 d-block">Jun 25, <span
                                                class="copyright-year"></span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide" id="single-slider-3-slide01" aria-hidden="true" tabindex="-1"
                        style="width: 382px;">
                        <div class="card rounded-m mx-3 bg-blue-dark">
                            <div class="content m-2">
                                <div class="d-flex">
                                    <div class="pe-3">
                                        <img src="images/pictures/28s.jpg" width="85" class="rounded-m">
                                    </div>
                                    <div class="ms-auto">
                                        <span class="color-white font-10 mb-n1 d-block opacity-50">John Doe</span>
                                        <h4 class="color-white font-15 font-500">Apple rumoured to release a Car next year.</h4>
                                        <strong class="font-10 opacity-30 font-300 mt-n2 mb-n1 d-block">Jun 25, <span
                                                class="copyright-year"></span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide" id="single-slider-3-slide02" style="width: 382px;" aria-hidden="true"
                        tabindex="-1">
                        <div class="card rounded-m mx-3 bg-red-dark">
                            <div class="content m-2">
                                <div class="d-flex">
                                    <div class="pe-3">
                                        <img src="images/pictures/28s.jpg" width="85" class="rounded-m">
                                    </div>
                                    <div class="ms-auto">
                                        <span class="color-white font-10 mb-n1 d-block opacity-50">John Doe</span>
                                        <h4 class="color-white font-15 font-500">Google plans to release a new Plane this year.
                                        </h4>
                                        <strong class="font-10 opacity-30 font-300 mt-n2 mb-n1 d-block">Jun 25, <span
                                                class="copyright-year"></span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide is-active is-visible" id="single-slider-3-slide03" style="width: 382px;"
                        aria-hidden="false" tabindex="0">
                        <div class="card rounded-m mx-3 bg-green-dark">
                            <div class="content m-2">
                                <div class="d-flex">
                                    <div class="pe-3">
                                        <img src="images/pictures/28s.jpg" width="85" class="rounded-m">
                                    </div>
                                    <div class="ms-auto">
                                        <span class="color-white font-10 mb-n1 d-block opacity-50">John Doe</span>
                                        <h4 class="color-white font-15 font-500">New fuel derived from your Facebook likes.
                                        </h4>
                                        <strong class="font-10 opacity-30 font-300 mt-n2 mb-n1 d-block">Jun 25, <span
                                                class="copyright-year"></span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide splide__slide--clone" style="width: 382px;">
                        <div class="card rounded-m mx-3 bg-blue-dark">
                            <div class="content m-2">
                                <div class="d-flex">
                                    <div class="pe-3">
                                        <img src="images/pictures/28s.jpg" width="85" class="rounded-m">
                                    </div>
                                    <div class="ms-auto">
                                        <span class="color-white font-10 mb-n1 d-block opacity-50">John Doe</span>
                                        <h4 class="color-white font-15 font-500">Apple rumoured to release a Car next year.
                                        </h4>
                                        <strong class="font-10 opacity-30 font-300 mt-n2 mb-n1 d-block">Jun 25, <span
                                                class="copyright-year"></span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide splide__slide--clone" style="width: 382px;">
                        <div class="card rounded-m mx-3 bg-red-dark">
                            <div class="content m-2">
                                <div class="d-flex">
                                    <div class="pe-3">
                                        <img src="images/pictures/28s.jpg" width="85" class="rounded-m">
                                    </div>
                                    <div class="ms-auto">
                                        <span class="color-white font-10 mb-n1 d-block opacity-50">John Doe</span>
                                        <h4 class="color-white font-15 font-500">Google plans to release a new Plane this year.
                                        </h4>
                                        <strong class="font-10 opacity-30 font-300 mt-n2 mb-n1 d-block">Jun 25, <span
                                                class="copyright-year"></span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="splide__pagination">
                <li><button class="splide__pagination__page" type="button" aria-controls="single-slider-3-slide01"
                        aria-label="Go to slide 1"></button></li>
                <li><button class="splide__pagination__page" type="button" aria-controls="single-slider-3-slide02"
                        aria-label="Go to slide 2"></button></li>
                <li><button class="splide__pagination__page is-active" type="button" aria-controls="single-slider-3-slide03"
                        aria-label="Go to slide 3" aria-current="true"></button></li>
            </ul>
        </div> --}}

        @isset($planes)
            @foreach ($planes as $item)
                @foreach ($usuario->planes as $plane)
                    @if ($plane->id == $item['id'])
                        <div data-card-height="140" class="card card-style rounded-m shadow-xl bg-18" style="height: 140px; background-image:url('{{asset('imagenes/delight/9.jpeg')}}')">
                            <div class="card-top mt-4 ms-3">
                                <h2 class="color-white">{{ Str::limit($item['plan'], 30) }}</h2>
                                <p class="color-white font-10 opacity-70 mt-2 mb-n1"><i class="far fa-calendar"></i> Restante:  {{ $plane->pivot->where('start', '>', date('Y-m-d'))->where('estado', 'pendiente')->where('user_id', $usuario->id)->where('plane_id', $plane->id)->count() }}<i
                                        class="ms-3 fa fa-hashtag"></i> Total: {{$plane->pivot->where('plane_id',$plane->id)->where('user_id', $usuario->id)->where('estado','!=', 'permiso')->count()}}<i class="ms-3 fa fa-user"></i> Permisos:{{ $plane->pivot->where('estado', 'permiso')->where('user_id', $usuario->id)->where('plane_id', $plane->id)->count() }}</p>
                                <p class="color-white font-10 opacity-70"><i class="fa fa-map-marker-alt"></i> Sucursal Central</p>

                            </div>
                            <div class="card-center me-3">
                                <a href="{{ route('calendario.cliente', [$item['id'], $usuario->id]) }}"
                                    class="float-end bg-highlight btn btn-xs text-uppercase font-900 rounded-xl font-11">Ver
                                    detalle</a>
                            </div>
                            <div class="card-overlay bg-black opacity-60"></div>
                        </div>
                        @break
                    @endif
                
            @endforeach
        @endforeach
    @endisset

    <div class="card card-style p-3">
        <div class="row text-center row-cols-3 mb-n4">

            <a class="col mb-4" data-gallery="gallery-1" href="{{ asset('imagenes/almuerzo/' . $fotoMenu->foto) }}"
                title="Pots and Pans">
                <img data-src="{{ asset('imagenes/almuerzo/' . $fotoMenu->foto) }}"
                    class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded"
                    src="{{ asset('imagenes/almuerzo/' . $fotoMenu->foto) }}">
            </a>
            <a class="col mb-4" data-gallery="gallery-1" href="{{ asset('imagenes/delight/6.jpeg') }}"
                title="Berries are Packed with Fiber">
                <img data-src="{{ asset('imagenes/delight/6.jpeg') }}"
                    class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded"
                    src="{{ asset('imagenes/delight/6.jpeg') }}">
            </a>
            <a class="col mb-4" data-gallery="gallery-1" href="{{ asset('imagenes/delight/2.jpeg') }}"
                title="A beautiful Retro Camera">
                <img data-src="{{ asset('imagenes/delight/2.jpeg') }}"
                    class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded"
                    src="{{ asset('imagenes/delight/2.jpeg') }}">
            </a>
        </div>
    </div>
    <div class="row mb-0">
        <div class="col-6 pe-0">
            <a href="{{ route('menusemanal') }}">
                <div class="card card-style me-0 bg-18" data-card-height="280"
                    style="height: 280px;background-image:url({{ asset('imagenes/almuerzo/' . $fotoMenu->foto) }})">
                    <div class="card-top">
                        <span class="badge bg-highlight px-2 py-1 color-white m-3">MENU DE LA SEMANA</span>
                    </div>
                    <div class="card-bottom p-3">
                        <h5 class="color-white font-500">
                            Click para mas detalles
                        </h5>
                        <span class="color-white opacity-50 font-10">Variedad cada semana</span>
                    </div>
                    <div class="card-overlay bg-black opacity-60"></div>
                </div>
            </a>

        </div>
        <div class="col-6 ps-0">
            <a href="{{ route('productos') }}">
                <div class="card card-style bg-20 mb-2" data-card-height="135"
                    style="height: 135px;background-image:url({{ asset('imagenes/delight/4.jpeg') }})">
                    <div class="card-bottom p-3">
                        <h5 class="color-white font-500 font-14 mb-n1">
                            Ver los <br>productos
                        </h5>
                        <span class="color-white opacity-50 font-10">En todas sus categorias</span>
                    </div>
                    <div class="card-overlay bg-black opacity-60"></div>
                </div>
            </a>
            <a href="{{ route('promociones') }}">
                <div class="card card-style bg-14 mb-2" data-card-height="135"
                    style="height: 135px;background-image:url({{ asset('imagenes/delight/22.jpeg') }})">
                    <div class="card-bottom p-3">
                        <h5 class="color-white font-500 font-14 mb-n1">
                            Descubre las promociones
                        </h5>
                        <span class="color-white opacity-50 font-10">Siempre hay novedades!</span>
                    </div>
                    <div class="card-overlay bg-black opacity-60"></div>
                </div>
            </a>


        </div>
    </div>
@endauth

@guest
    <x-cabecera-pagina titulo="Inicia Sesion" cabecera="bordeado" />

    <div class="card card-style">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="content mt-2 mb-0">
                <div class="input-style no-borders has-icon validate-field mb-4">
                    <i class="fa fa-user"></i>
                    <input type="email" class="form-control validate-name" id="form1a" name="email"
                        value="{{ old('email') }}" placeholder="Correo">
                    <label for="form1a" class="color-blue-dark font-10 mt-1">Correo</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(requerido)</em>
                </div>
                <div class="input-style no-borders has-icon validate-field mb-4">
                    <i class="fa fa-lock"></i>
                    <input type="password" class="form-control validate-password" name="password" id="form3a"
                        placeholder="Contraseña">
                    <label for="form3a" class="color-blue-dark font-10 mt-1">Contraseña</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(requerido)</em>
                </div>
                <button type="submit"
                    class="btn btn-m mt-2 mb-4 btn-full bg-green-dark rounded-sm text-uppercase font-900">Inicia
                    Sesion</button>
                @if ($errors->any())
                    <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">Ingrese los datos correctamente</mark>
                @endif

                <div class="divider"></div>
                {{-- <a href="#" class="btn btn-icon btn-m btn-full shadow-l bg-facebook text-uppercase font-900 text-start"><i
                    class="fab fa-facebook-f text-center"></i>Loguea con Facebook</a>
            <a href="#"
                class="btn btn-icon btn-m mt-2 btn-full shadow-l bg-google text-uppercase font-900 text-start"><i
                    class="fab fa-google text-center"></i>Loguea con Google</a> --}}
                <div class="divider mt-4 mb-3"></div>
                <div class="d-flex">
                    <div class="w-50 font-11 pb-2 color-theme opacity-60 pb-3 text-start"><a
                            href="{{ route('register') }}" class="color-theme">Crear una cuenta</a></div>
                </div>
            </div>
        </form>
    </div>

@endguest
@endsection
