@extends('client.master')
@section('content')
    @auth
        <x-cabecera-pagina titulo="Perfil Personal" cabecera="appkit" />
        <div class="card card-style preload-img entered loaded mb-3" data-src="{{ asset('images/imagen4.jpg') }}" data-card-height="450"
            style="height: 450px; background-image: url(&quot;{{ asset(GlobalHelper::getValorAtributoSetting('mi_perfil_deligth')) }}&quot;);"
            data-ll-status="loaded">
            <div class="card-bottom ms-3 ">
                <h1 class="font-40 line-height-xl color-white">{{ auth()->user()->name }}</h1>
                <a href="{{ route('llenarDatosPerfil') }}"
                    class="btn btn-xxs mb-3 rounded-s text-uppercase font-900 shadow-s border-red-dark  bg-red-light">Editar
                    Perfil </a>
                <p class="pb-0 mb-0 font-12 color-white"><i class="fa fa-map-marker me-2"></i>Tarija, Bolivia</p>
                <p class="color-white">
                    Encuentra toda la informacion sobre tu cuenta en esta pestaña.
                </p>
            </div>
            <div class="card-overlay bg-gradient"></div>
        </div>
        <div class="card card-style mb-3">
            <div class="content mb-0">
                <div class="row mb-0 text-center mb-3">

                    <div class="col-3" style="line-height: 5px;">
                        <h1 class="mb-0 pb-0">{{ $usuario->puntos }}</h1>
                        <p class="font-10 mb-0 pb-0">Puntos</p>

                    </div>
                    <div class="col-6" style="line-height: 5px;">
                        <a href="{{ route('usuario.saldo') }}">
                            <h1 class="mb-0 pb-0" >{{ $usuario->saldo }} Bs @if ($usuario->saldo > 0)
                                    <i class="fa fa-arrow-right rotate-135 color-red-dark"></i>
                                @else
                                    <i class="fa fa-arrow-right rotate-45 color-green-dark"></i>
                                @endif
                            </h1>
                            <p class="font-10 mb-0 pb-0">Saldo</p>

                        </a>
                    </div>
                    <div class="col-3" style="line-height: 5px;">
                        <h1 class="mb-0 pb-0" >{{ $usuario->historial_ventas->count() }}</h1>
                        <p class="font-10 mb-0 pb-0">Compras</p>
                    </div>
                </div>
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
        </div>
    @endauth
@endsection
