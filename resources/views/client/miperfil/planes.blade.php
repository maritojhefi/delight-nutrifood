@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Mis planes" cabecera="bordeado" />
    @isset($planes)
        <div class="card card-style">
            <div class="content">
                <h4 class="font-700">Planes suscritos</h4>
                <p class="pb-0">
                  Actualmente estas suscrito en {{$planes->count()}} plan(es):
                </p>
            </div>
        </div>
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
                                class="float-end bg-highlight btn btn-xs text-uppercase font-900 rounded-xl font-11">Administrar
                                </a>
                        </div>
                        <div class="card-overlay bg-black opacity-60"></div>
                    </div>
                @break
            @endif
        @endforeach
    @endforeach
   
    <div class="divider bg-mint-dark divider-margins"></div>
@endisset

<a href="#" data-menu="menu-tips-1">
    <div class="card card-style bg-11" data-card-height="175"
        style="height: 175px;background-image:url({{ asset('imagenes/delight/gifpulse7.gif') }})">
        <div class="card-center">
            <div class="row mb-0">
                <div class="col-10">
                    <h1 class="color-white font-700 mb-n1 ms-3">Terminos y condiciones</h1>
                    <p class="color-white opacity-60 mb-0 ms-3">Leelo antes de ingresar a cualquiera de nuestros planes.
                    </p>
                </div>
                <div class="col-2">
                    <i class="fa fa-shield-alt color-green-dark fa-3x"></i>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-dark opacity-80"></div>
    </div>
</a>

<div class="divider bg-mint-dark divider-margins"></div>
<div class="card card-style">
    <div class="content">
        <h4 class="font-700">Estos son todos nuestros planes:</h4>
       
    </div>
</div>
@foreach ($subcategoria->productos as $producto)
    <div data-card-height="220" class="card card-style round-medium shadow-huge top-30"
        style="background-image:url({{ asset($producto->pathAttachment()) }})">
        <div class="card-top mt-3 ms-3">
            <h2 class="color-white pt-3 pb-3">{!! wordwrap($producto->nombre(), 15, "<br />\n") !!}</h2>

        </div>
        <div class="card-top mt-3 me-3">
            <a href="#"
                class="float-end bg-white color-black btn btn-s rounded-xl font-900 mt-2 text-uppercase font-11 carrito"
                id="{{ $producto->id }}">Lo quiero!</a>
        </div>

        <div class="card-bottom ms-3 mb-3">
            <i class="fa fa-heart font-25 color-white"></i>
        </div>
        <div class="card-bottom mb-n3 ps-5 ms-4">
            <h5 class="font-13 color-white mb-n1">Disponible!</h5>
            <p class="color-white font-10 opacity-70">{{ $producto->detalle }}</p>
        </div>

        <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
@endforeach
@include('client.lineadelight.include-planes-modal')
@endsection
