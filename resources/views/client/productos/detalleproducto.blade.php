@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="{{ Str::limit($producto->nombre,20) }}" cabecera="bordeado" />
    <div class="card card-style">
        <div class="card bg-13" data-card-height="250" style="height: 250px;background-image:url('{{asset($producto->pathAttachment())}}')">
            <div class="card-bottom pb-4 ps-3">
                <h1 class="font-20 text-white">
                    @foreach ($nombrearray as $item)
                        {{  Str::limit($item,20) }}
                        <br>
                    @endforeach
                </h1>
            </div>
            <div class="card-bottom pb-4 pe-3">
                <h1 class="font-20 text-end mb-3 text-white">{{ $producto->descuento ? $producto->descuento : $producto->precio }} Bs <br><sup
                        class="font-400 font-17 opacity-50">({{ $producto->medicion }})</sup></h1>
                <span
                    class="badge bg-dark color-white px-2 py-1 mt-n1 text-uppercase d-block float-end">{{ $producto->descuento ? 'En descuento' : 'Sin promocion' }}</span>
            </div>
            <div class="card-overlay bg-gradient opacity-70 rounded-0"></div>
        </div>
        <div class="content mt-n2">
            <div class="row">
                <div class="col-6">
                    <p class="line-height-m">
                        {{ $producto->detalle }}
                    </p>
                </div>
                <div class="col-6">
                    <div>
                        <p class="font-10 mb-n2">Categoria</p>
                        <p class="font-12 color-theme font-700">{{ $producto->subcategoria->nombre }}</p>
                    </div>
                    <div>
                        <p class="font-10 mb-n2">Stock</p>
                        <p class="font-12 color-theme font-700">
                            @if ($producto->sucursale->count() != 0)
                                @foreach ($producto->sucursale as $item)
                                    {{ $item->nombre }} : {{ $item->pivot->cantidad }} <br>
                                @endforeach
                            @else
                                Agotado
                            @endif


                        </p>
                    </div>
                    <div>
                        <p class="font-10 mb-n2">Puntos por la compra</p>
                        <p class="font-12 color-theme font-700">{{ $producto->puntos==null?'0':$producto->puntos }} Pts</p>
                    </div>

                </div>
            </div>
            <div class="divider mt-4 mb-2"></div>
            <div class="d-flex">
                <div>
                    <p class="mb-n1 font-10">Puntuacion &amp; Comentarios</p>
                    <h6 class="float-start">4.9</h6>
                    <i class="float-start color-yellow-dark pt-1 ps-2 fa fa-star"></i>
                    <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
                    <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
                    <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
                    <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
                </div>
                <div class="ms-auto">

                </div>
            </div>
            <div class="divider mt-3"></div>
            <a href="#" class="btn btn-full bg-highlight btn-l rounded-sm text-uppercase font-800">Comprar ahora</a>
        </div>
    </div>
@endsection
