@extends('client.master')
@section('content')
    @auth
        <x-cabecera-pagina titulo="Hola!" cabecera="bordeado" />
        <div class="card card-style preload-img entered loaded" data-src="{{ asset('images/imagen4.jpg') }}" data-card-height="450"
            style="height: 450px; background-image: url(&quot;{{ asset('imagenes/delight/21.jpeg') }}&quot;);"
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

     

        <div class="row text-center mb-0">
            <a href="{{ route('misplanes') }}" class="col-6 pe-2">
                <div class="card card-style me-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-crown color-brown-dark font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Mis Planes</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        Planes suscritos
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>
            <a href="{{ route('usuario.saldo') }}" class="col-6 ps-2">
                <div class="card card-style ms-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-wallet color-gray-dark font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Mi Saldo</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        Balance de saldos
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>
            <a href="{{ route('carrito') }}" class="col-6 pe-2">
                <div class="card card-style me-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-shopping-cart color-teal-light font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Mi carrito</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        For your Birdy
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>
            <a href="{{ route('construccion') }}" class="col-6 ps-2">
                <div class="card card-style ms-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-video color-blue-light font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Tutoriales</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        Aprende a usar nuestro sistema web!
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>
            <a href="{{ route('construccion') }}" class="col-12">
                <div class="card card-style mb-3">
                    <div class="d-flex py-3 my-1">
                        <div class="align-self-center px-3">
                            <i class="fa fa-handshake color-mint-dark font-35 ps-2 pe-1"></i>
                        </div>
                        <div class="align-self-center">
                            <h4 class="text-start color-theme font-600 font-17">Asistente virtual</h4>
                            <p class="text-start mt-n2 font-11 color-highlight mb-0">
                                Activar/desactivar los mensajes automaticos
                            </p>
                        </div>
                        <div class="align-self-center ms-auto pe-4">
                            <i class="fa fa-arrow-right opacity-30"></i>
                        </div>
                    </div>
                </div>
            </a>
            
        </div>

        {{-- @isset($planes)
            @foreach ($planes as $item)
                @foreach ($usuario->planes as $plane)
                    @if ($plane->id == $item['id'])
                        <div data-card-height="140" class="card card-style rounded-m shadow-xl bg-18"
                            style="height: 140px; background-image:url('{{ asset('imagenes/delight/9.jpeg') }}')">
                            <div class="card-top mt-4 ms-3">
                                <h2 class="color-white">{{ Str::limit($item['plan'], 30) }}</h2>
                                <p class="color-white font-10 opacity-70 mt-2 mb-n1"><i class="far fa-calendar"></i> Restante:
                                    {{ $plane->pivot->where('start', '>', date('Y-m-d'))->where('estado', 'pendiente')->where('user_id', $usuario->id)->where('plane_id', $plane->id)->count() }}<i
                                        class="ms-3 fa fa-hashtag"></i> Total:
                                    {{ $plane->pivot->where('plane_id', $plane->id)->where('user_id', $usuario->id)->where('estado', '!=', 'permiso')->where('estado', '!=', 'archivado')->count() }}<i
                                        class="ms-3 fa fa-user"></i>
                                    Permisos:{{ $plane->pivot->where('estado', 'permiso')->where('user_id', $usuario->id)->where('plane_id', $plane->id)->count() }}
                                </p>
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
    @endisset --}}
    {{-- <div class="card card-style p-3">
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
            <a href="{{ route('linea.delight') }}">
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
    </div> --}}
@endauth
@endsection
