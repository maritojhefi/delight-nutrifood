@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Fuera de linea!" cabecera="appkit" />
    <div class="card card-style text-center">
        <div class="content py-5">
            <h1><i class="fa fa-exclamation-triangle color-red-dark fa-4x"></i></h1>
            <h1 class="fa-5x pt-5 pb-2">UPS!</h1>
            <h4 class="text-uppercase pb-3">No estas conectado</h4>
            <p class="boxed-text-l">
                Algo paso con tu conexion a internet, revisalo y vuelve!
            </p>
            <div class="row mb-0">
               
            </div>
        </div>
    </div>
@endsection
