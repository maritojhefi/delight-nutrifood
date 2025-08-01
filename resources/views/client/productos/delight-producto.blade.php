@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Detalles del Producto" cabecera="appkit" />
    <x-producto-detalle-component :producto="$producto"/>

    <div class="card card-style">
        <div class="content">
            <h4>Productos relacionados</h4>

            <div class="divider mt-3 mb-3"></div>
            @foreach ($producto->subcategoria->productos->shuffle()->take(5) as $item)
                <div class="d-flex">
                    <div>
                        <a href="{{ route('delight.detalleproducto', $item->id) }}">
                            <img src="{{ asset($item->pathAttachment()) }}" class="rounded-sm" width="55">
                    </div>
                    <div class="ps-3">
                        <h4>{{ Str::limit($item->nombre(), 25) }}</h4>
                        </a>
                        <a href="#"><span class="badge bg-magenta-dark font-700 font-11 text-uppercase carrito" id="{{$item->id}}">Añadir <i
                                    class="fa fa-shopping-cart"></i></span></a>
                    </div>
                    <div class="ms-auto">
                        <h1 class="font-20">{{ $item->descuento ? $item->descuento : $item->precio }} Bs</h1>
                    </div>
                </div>
                <div class="divider mt-3 mb-3"></div>
            @endforeach


        </div>
    </div>
@endsection
