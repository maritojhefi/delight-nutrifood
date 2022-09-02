@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Planes Delight" cabecera="bordeado" />
    <a href="#" data-menu="menu-tips-1">
        <div class="card card-style bg-11" data-card-height="175"
            style="height: 175px;background-image:url({{ asset('imagenes/delight/gifpulse6.gif') }})">
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

    @foreach ($subcategoria->productos() as $item)
    
        <div class="card card-style gradient-pink">
            <div class="content pb-3 pt-3">
                <h3 class="mb-1 color-white font-700">Gradient Card</h3>
                <p class="color-white opacity-80">
                    This box is a solid color. You can set it to the page highlight or set any color from the pack.
                </p>
                <a href="#" class="btn btn-s shadow-l bg-white color-black font-900">AWESOME</a>
            </div>
        </div>
    @endforeach
    @include('client.lineadelight.include-planes-modal')
@endsection
