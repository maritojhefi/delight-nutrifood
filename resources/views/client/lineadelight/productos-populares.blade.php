@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Linea Delight - Populares" cabecera="bordeado" />    
    <div class="card card-style">
        <div class="content">
            <h4>La sensacion del momento!</h4>
            <p>
                Estos son los productos mas populares de nuestra linea Delight.
            </p>
        </div>
    </div>
    <div class="content mb-0">
        <div class="row mb-0">
            {{-- @foreach($subcategorias as $subcategoria)
                <div class="col-12">
                    <a href="{{ route('delight.listar.productos.subcategoria', $subcategoria->id) }}" data-card-height="120" class="card card-style mb-4 mx-0" style="height: 120px;">
                        <div class="card-center ps-2 ms-2">
                            <i class="fa fa-mobile-alt font-40 ps-3"></i>
                        </div>
                        <div class="card-center ps-4 ms-5">
                            <h4 class="ps-2">{{$subcategoria->nombre}}</h4>
                            <p class="ps-2 mt-n2 font-12 color-highlight mb-0">Best selling Category</p>
                        </div>
                        <div class="card-center opacity-30">
                            <i class="fa fa-arrow-right opacity-50 float-end color-theme pe-3"></i>
                        </div>
                    </a>
                </div>
            @endforeach --}}
        </div>
    </div>
@endsection
