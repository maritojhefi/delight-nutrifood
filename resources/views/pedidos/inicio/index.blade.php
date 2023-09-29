@extends('pedidos.master')
@section('content')
    <div class="page-content">
       @include('pedidos.inicio.include-menu-1')
       @include('pedidos.inicio.include-search-productos')
       @include('pedidos.inicio.include-menu-collapsed')
       @include('pedidos.inicio.include-favoritos')
       @include('pedidos.inicio.include-populares')
       @include('pedidos.inicio.include-nuevos')
       @include('pedidos.inicio.include-puntuados')
    </div>
@endsection
