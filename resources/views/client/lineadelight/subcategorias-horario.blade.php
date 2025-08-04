@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Linea Delight - {{ ucfirst($horarioData->nombre) }}" cabecera="appkit" />    
    <div class="card card-style">
        <div class="content">
            <h4>Nuestras categorias populares durante la {{$horarioData->nombre}}!</h4>
            <p>
                Encuentra lo que mas te gusta!
            </p>
        </div>
    </div>
    <div class="content mb-0">
        <div class="row mb-0">
            @foreach($subcategorias as $subcategoria)
                {{-- <div class="col-12">
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
                </div> --}}
                <div class="col-12">
                    <a href="{{ route('delight.listar.productos.subcategoria', $subcategoria->id) }}" data-card-height="120" class="card card-style mb-4 mx-0 hover-grow-s" style="height: 120px;overflow: hidden">
                        <div class="d-flex flex-row align-items-center gap-4"> 
                            <div class="category-card-image">
                                <img src="{{ asset($subcategoria->rutaFoto()) }}" class="" style="background-color: white; border: ; " />
                            </div>
                            <div class="d-flex flex-column" style="max-width: 300px">
                                <h4 class="">{{$subcategoria->nombre}}</h4>
                                <p class="mt-n2 font-12 color-highlight mb-0">Delight</p>
                            </div>
                            {{-- <div class="card-center opacity-30">
                                <i class="fa fa-arrow-right opacity-50 float-end color-theme pe-3"></i>
                            </div> --}}
                        </div>
                    </a>
                </div>
                {{-- <div class="col-12">
                    <a href="{{ route('delight.listar.productos.subcategoria', $subcategoria->id) }}" 
                    data-card-height="120" 
                    class="card card-style mb-4 mx-0 custom-card-container" 
                    style="height: 120px;">
                        
                        <!-- Image Section -->
                        <div class="custom-card-image">
                            <img src="{{ $subcategoria->imagen ?? '/path/to/default-image.jpg' }}" 
                                alt="{{ $subcategoria->nombre }}" 
                                class="custom-card-img">
                        </div>
                        
                        <!-- Content Section -->
                        <div class="custom-card-content">
                            <h4 class="custom-card-title">{{ $subcategoria->nombre }}</h4>
                            <p class="custom-card-subtitle">Best selling Category</p>
                        </div>
                    </a>
                </div> --}}
            @endforeach
        </div>
    </div>
@endsection
