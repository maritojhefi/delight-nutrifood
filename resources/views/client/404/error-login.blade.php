@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="UY!" cabecera="bordeado"/>
<div class="card card-style text-center">
    <div class="content pt-4 pb-4">
    <h1><i class="fa fa-exclamation-triangle color-red-dark fa-5x"></i></h1>
    <h1 class="fa-6x pt-5 pb-2">404</h1>
    <h3 class="text-uppercase pb-3">Direccion invalida!</h3>
    <p class="boxed-text-l">
    No puedes iniciar sesion sin el link correcto.
    
    </p>
    
    </div>
    </div>
@endsection