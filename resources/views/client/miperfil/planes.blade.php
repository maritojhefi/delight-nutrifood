@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Mis planes" cabecera="bordeado" />
    @isset($planes)
        <div class="card card-style guide-card mb-3">
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
                    <div data-card-height="140" class="card card-style rounded-m shadow-xl bg-18 mb-3"
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
   
    <div class="divider bg-mint-dark divider-margins my-3"></div>
@endisset

<a href="#" data-menu="menu-tips-1 " style="background-color: #000000;">
    <div class="card card-style mb-3" style="background-color: #000000 !important;" data-card-height="125"
        style="height: 145px;">
        <div class="card-center ">
            <div class="row mb-0 align-items-center px-1">
                <div class="col-10">
                    <h1 class="color-white font-700 mb-n1 ms-3">Terminos y condiciones</h1>
                    <p class="color-white opacity-100 mb-0 ms-3">
                        Leelos antes de ingresar a cualquiera de nuestros planes.
                    </p>
                </div>
                <div class="col-2 ">
                    <i class="fa fa-exclamation text-white fa-3x fa-beat"></i>
                </div>
            </div>
        </div>
        <div class="card-overlay terms-button-overlay"></div>
    </div>
</a>

<div class="divider bg-mint-dark divider-margins my-3"></div>
<div class="card card-style guide-card mb-3">
    <div class="content">
        <h4 class="font-700">Estos son todos nuestros planes:</h4>
    </div>
</div>
@foreach ($planesTodos as $plan)
    <div data-card-height="150"
    class="card card-style plan-card round-medium shadow-huge top-30 mb-3"
    style="background-image: url('{{ asset('imagenes/delight/default-bg-horizontal.jpg') }}'); background-size: cover; background-position: center;">


        <div class="d-flex justify-content-between align-items-center ms-4 me-4 mt-4">
            <div class="plan-title-container" style="flex: 1; margin-right: 15px;">
                <h2 class="mb-0 plan-title-text" 
                    style="z-index: 10; 
                           display: -webkit-box; 
                           -webkit-line-clamp: 2; 
                           -webkit-box-orient: vertical; 
                           overflow: hidden; 
                           line-height: 1.2;
                           word-break: break-word;"
                    data-plan-name="{{ $plan->nombre }}">
                    {{ $plan->nombre }}
                </h2>
            </div>
            <a href="#"
                class="btn confirm-btn text-light rounded-pill fw-bold text-uppercase small carrito flex-shrink-0"
                style="z-index: 10;"
                id="{{ $plan->id }}">
                <span class="text-white">{{$plan->producto->precioReal()}} Bs </span>
                <i class="fa fa-heart fa-beat" style="color: deeppink;"> </i>
            </a>
        </div>
        <div class="d-flex align-items-center ms-4 me-3 mt-3 card-center">
            <a href="#" class="icon icon-xxs rounded-circle shadow-l ms-0 me-2 bg-green-light">
                <i class="fa fa-check"></i>
            </a>
            <p class="text-white fw-bold font-12 small lh-sm m-0">PLAN PERSONALIZABLE</p>
        </div>
        <div class="d-flex align-items-center ms-4 me-3 mb-2 card-bottom">
            <i class="fa fa-apple-alt fs-1 me-3 plan-icon" ></i>
            <p class="text-white small lh-sm m-0">{{ $plan->detalle }}</p>
        </div>

        <div class="plan-overlay card-overlay opacity-60"></div>

    </div>
@endforeach
@include('client.lineadelight.include-planes-modal')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para ajustar el tamaño de fuente de los títulos de planes
    function adjustPlanTitleFontSize() {
        const planTitles = document.querySelectorAll('.plan-title-text');
        
        planTitles.forEach(function(title) {
            const planName = title.getAttribute('data-plan-name');
            const nameLength = planName.length;
            
            // Determinar el tamaño de fuente basado en la longitud del nombre
            let fontSize;
            if (nameLength <= 15) {
                fontSize = '1.5rem'; // Tamaño normal para nombres cortos
            } else if (nameLength <= 25) {
                fontSize = '1.3rem'; // Tamaño mediano para nombres medianos
            } else if (nameLength <= 35) {
                fontSize = '1.1rem'; // Tamaño más pequeño para nombres largos
            } else {
                fontSize = '0.95rem'; // Tamaño mínimo para nombres muy largos
            }
            
            title.style.fontSize = fontSize;
            
            // Ajustar también el line-height proporcionalmente
            const lineHeight = parseFloat(fontSize) * 1.2;
            title.style.lineHeight = lineHeight + 'rem';
        });
    }
    
    // Ejecutar la función al cargar la página
    adjustPlanTitleFontSize();
});
</script>

@endsection
