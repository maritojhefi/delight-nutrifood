@extends('client.master')

@push('header')
    @include('client.productos.includesMenuInicio.styles')
@endpush
@section('content')
    <x-cabecera-pagina titulo="Bienvenidos" cabecera="appkit" />
    @include('client.productos.includesMenuInicio.card-plan-informacion')

    @include('client.productos.includesMenuInicio.card-convenios-info')

    @include('client.productos.includesMenuInicio.card-menu-almuerzos-publico')

    @auth
        <div data-card-height="125" class="card card-style round-medium shadow-huge top-30 mb-3"
            style="height: 125px;background-image:url('{{ asset(GlobalHelper::getValorAtributoSetting('banner_mi_perfil')) }}')">
            <div class="card-top mt-3 ms-3">
                <h2 class="color-white pt-3 pb-3">{{ Str::limit(auth()->user()->name, 25) }}</h2>
            </div>
            <div class="card-top mt-3 me-3">
                <a href="{{ route('miperfil') }}"
                    class="float-end bg-white color-black btn btn-s rounded-xl font-700 mt-2 text-uppercase font-11"><i class="fa fa-hand-pointer fa-beat"></i> Ir a mi
                    perfil </a>
            </div>
            <div class="card-bottom ms-3 mb-3">
                @if (auth()->user()->foto)
                    <img data-src="{{ auth()->user()->pathFoto }}" alt="img" width="40"
                        class="pb-1 preload-img shadow-xl rounded-m entered loaded" data-ll-status="loaded"
                        src="{{ auth()->user()->pathFoto }}">
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
            {{-- <div class="card-overlay bg-highlight opacity-40"></div> --}}
            <div class="card-overlay bg-black opacity-70"></div>
        </div>

        @include('client.productos.includesMenuInicio.card-patrocinador')

    @endauth


    <a href="#" class="cambiarColor card card-style mb-3" data-card-height="125"
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


@endsection
@push('scripts')
    @include('client.productos.includesMenuInicio.scripts')
@endpush
