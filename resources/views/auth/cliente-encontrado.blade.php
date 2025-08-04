@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Tarjeta escaneada correctamente!" cabecera="appkit" />
    <div data-card-height="cover-card" class="card card-style"
        style="background-image: url(&quot;images/pictures/14t.jpg&quot;); height: 398px;">
        <div class="card-center text-center">
            <h1 class="mb-5">
                @if ($usuario->foto)
                    <img src="{{ asset('imagenes/perfil/' . $usuario->foto) }}" class="rounded-circle"
                        style="width: 150px;height:150px" alt="">
                @else
                    <i class="fa fa-4x fa-user color-mint-dark "></i>
                @endif

            </h1>
            <h1 class="color-white bolder fa-3x">Hola {{ Str::of($usuario->name)->explode(' ')->first() }}!</h1>
            <h6 class="color-white mb-4">INGRESO REGISTRADO</h6>
            <a href="{{ route('home') }}"
                class="btn btn-m bg-highlight rounded-xl btn-center-m shadow-l font-900 text-uppercase">Ir al inicio</a>
        </div>
        <div class="card-overlay bg-black opacity-70"></div>
    </div>
@endsection
