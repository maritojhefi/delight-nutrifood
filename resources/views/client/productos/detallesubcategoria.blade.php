@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="{{ $subcategoria->nombre }}" cabecera="bordeado" />
    
    <div class="card card-style">
        <div class="content">
            <h4>Todos los productos de esta categoria!</h4>
            <p>
                Encuentra lo que mas te gusta!
            </p>
            @foreach ($subcategoria->productos->shuffle() as $item)
            <div class="d-flex">
                <a href="{{route('detalleproducto',$item->id)}}">
                <div>
                    <img src="{{asset($item->pathAttachment())}}" class="rounded-sm" width="55">
                </div>
                <div class="ps-3">
                    <h6>{{$item->nombre()}}</h6>
                    </a>
                    <a href="#"><span class="badge bg-red-dark font-700 font-11 text-uppercase">Añadir <i class="fa fa-shopping-cart"></i></span></a>
                </div>
                <div class="ms-auto">
                    <h1 class="font-20">{{$item->precio()}} Bs</h1>
                </div>
            </div>
            <div class="divider mt-3 mb-3"></div>
            @endforeach
            
           
        </div>
    </div>
@endsection
