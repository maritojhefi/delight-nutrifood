@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Gracias por tu tiempo!" cabecera="bordeado" />
    <div data-card-height="cover-card" class="card card-style"
        style="background-image: url(&quot;images/pictures/14t.jpg&quot;); height: 398px;">
        <div class="card-center text-center">
            <h1 class="mb-5"><i class="fa fa-4x fa-star color-yellow-dark "></i></h1>
            <h1 class="color-white bolder fa-3x">Gracias</h1>
            <h6 class="color-white mb-4">Con cariño, el equipo {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}</h6>
            <p class="boxed-text-l color-white opacity-80 mb-5">
                Con mas de 8 años de experiencia, estamos a la vanguardia de tu salud y tu paladar, se vienen sorpresas y novedades, pronto seras parte de ellas.
            </p>
            <a href="{{route('home')}}"
                class="btn btn-m bg-highlight rounded-xl btn-center-m shadow-l font-900 text-uppercase">Ir al inicio</a>
        </div>
        <div class="card-overlay bg-black opacity-70"></div>
    </div>
@endsection
