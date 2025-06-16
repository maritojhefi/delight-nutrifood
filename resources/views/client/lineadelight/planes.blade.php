@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Planes {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}" cabecera="bordeado" />
    <a href="#" data-menu="menu-tips-1">
        <div class="card card-style bg-11" data-card-height="175"
            style="height: 175px;background-image:url({{ asset(GlobalHelper::getValorAtributoSetting('gif_pulse')) }})">
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
    
    @foreach ($subcategoria->productos as $producto)
    
        

        <div data-card-height="220" class="card card-style round-medium shadow-huge top-30" style="background-image:url({{ asset($producto->pathAttachment()) }})">
            <div class="card-top mt-3 ms-3">
                <h2 class="color-white pt-3 pb-3">{!! wordwrap($producto->nombre(), 15, "<br />\n") !!}</h2>
    
            </div>
            <div class="card-top mt-3 me-3">
                <a href="#" class="float-end bg-white color-black btn btn-s rounded-xl font-900 mt-2 text-uppercase font-11 carrito" id="{{$producto->id}}">Lo quiero!</a>
            </div>
    
            <div class="card-bottom ms-3 mb-3">
                <i class="fa fa-heart font-25 color-white"></i>
            </div>
            <div class="card-bottom mb-n3 ps-5 ms-4">
                <h5 class="font-13 color-white mb-n1">Disponible!</h5>
                <p class="color-white font-10 opacity-70">{{$producto->detalle}}</p>
            </div>
    
            <div class="card-overlay bg-gradient opacity-80"></div>
        </div>
    @endforeach
    @include('client.lineadelight.include-planes-modal')
@endsection
