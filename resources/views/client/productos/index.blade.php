@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Productos" cabecera="bordeado" />

    {{-- <x-page-construccion/> --}}

    <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
        <div class="content mt-2">
            <div class="search-box bg-theme color-theme rounded-m shadow-l">
                <i class="fa fa-search"></i>
                <input type="text" class="border-0" placeholder="Que estas buscando?" data-search="">
                <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
            </div>
            <div class="search-results disabled-search-list mt-3">
                <div class="card card-style mx-0 px-2 p-0 mb-0">

                    @foreach (session('productos') as $item)
                        <a href="{{ route('detalleproducto', $item->id) }}" class="d-flex py-2"
                            data-filter-item="{{ Str::of($item->nombre)->lower() }}"
                            data-filter-name="{{ Str::of($item->nombre)->lower() }}">
                            <div class="align-self-center">
                                <img src="{{ asset($item->pathAttachment()) }}" class="rounded-sm me-3" width="35"
                                    alt="img">
                            </div>
                            <div class="align-self-center">
                                <span class="color-theme font-10 d-block mb-0">{{Str::limit($item->nombre,30,'...')  }}</span>
                            </div>
                            <div class="ms-auto text-center align-self-center pe-2">
                                <h5 class="line-height-xs font-16 font-600 mb-0">{{ $item->precio }} Bs<sup
                                        class="font-11"></sup></h5>
                            </div>
                        </a>
                    @endforeach


                </div>
            </div>
        </div>
        <div class="search-no-results disabled mt-4">
            <div class="card card-style">
                <div class="content">
                    <h1>Ups!</h1>
                    <p>
                        No existe coincidencias <span class="fa-fw select-all fas"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    
@endsection
