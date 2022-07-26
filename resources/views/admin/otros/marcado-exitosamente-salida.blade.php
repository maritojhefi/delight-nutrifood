@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Vuelve pronto...!" cabecera="bordeado" />
    <div class="card card-style text-center">
        <div class="content py-5">
            <h1><i class="fa fa-check color-green-dark fa-4x"></i></h1>
            <h1 class="fa-5x pt-5 pb-2">SI!</h1>
            <h4 class="text-uppercase pb-3">Acabas de marcar tu salida!</h4>
            <p class="boxed-text-l">
                Tu hora de salida es a las {{ date('h:i') }}
            </p>
            <div class="row m-3">
                @if ($diferencia < 0)
                    <div class="ms-3 me-3 alert alert-small rounded-s shadow-xl bg-yellow-dark" role="alert">
                        <span><i class="fa fa-exclamation-triangle"></i></span>
                        <strong>Aun faltaban {{ $diferencia }} minutos para salir!</strong>

                    </div>
                @else
                    <div class="ms-3 me-3 alert alert-small rounded-s shadow-xl bg-green-dark" role="alert">
                        <span><i class="fa fa-check"></i></span>
                        <strong>Se te agradece! Estas saliendo {{ $diferencia }} minutos despues de tu hora de
                            salida</strong>

                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
