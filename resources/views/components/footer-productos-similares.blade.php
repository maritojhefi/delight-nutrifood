@if (count($similares) >= 1) 
<div class="card mx-n3 rounded-0" 
    style="background-image: url({{ $producto->subcategoria->pathFoto ? $producto->subcategoria->pathFoto:'imagenes/delight/stock_default.jpg' }}), url({{asset('imagenes/delight/stock_default.jpg')}}); height: 150px;" 
    data-card-height="150">
    <div class="card-center text-end ms-2 me-3">
        <h1 class="color-white font-900 font-34 mb-n2">{{$producto->subcategoria->nombre}}</h1>
        <h1 class="color-white font-900 font-18 mt-1">Descubre productos similares!</h1>
    </div>
    <div class="card-overlay dark-mode-tint light-mode-tint"></div>
</div>
@endif
@switch(count($similares))
    @case(1)
        <div class="row mb-0">
            <div class="col-12">
                <a class="card mx-0 mb-2 card-style default-link hover-grow-s" data-card-height="150" data-gallery="gallery-b" href="{{$similares[0]->url_detalle}}" style="background-image: url({{asset($similares[0]->imagen)}}); height: 150px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[0]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>
        </div>
        @break
    @case(2)
        <div class="row mb-0">
            <div class="col-6 ps-3">
                <a class="card mx-0 mb-3 card-style default-link hover-grow-s" data-card-height="270" data-gallery="gallery-b" href="{{$similares[0]->url_detalle}}" style="background-image: url({{asset($similares[0]->imagen)}}); height: 410px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[0]->nombre(),30)  }}</h3>
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>
            <div class="col-6 pe-3">
                <a class="card mx-0 mb-3 card-style default-link hover-grow-s" data-card-height="270" data-gallery="gallery-b" href="{{$similares[1]->url_detalle}}" style="background-image: url({{asset($similares[1]->imagen)}}); height: 410px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[1]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>  
        </div>
    @break
    @case(3)
        <div class="row mb-0">
            <div class="col-6 pe-0">
                <a class="card mx-0 mb-3 card-style default-link hover-grow-s" data-card-height="270" data-gallery="gallery-b" href="{{$similares[0]->url_detalle}}" style="background-image: url({{asset($similares[0]->imagen)}}); height: 410px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[0]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>
            <div class="col-6">
                <a class="card mx-0 mb-2 card-style default-link hover-grow-s" data-card-height="130" data-gallery="gallery-b" href="{{$similares[1]->url_detalle}}" style="background-image: url({{asset($similares[1]->imagen)}}); height: 130px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[1]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
                <a class="card mx-0 mb-2 card-style default-link hover-grow-s" data-card-height="130" data-gallery="gallery-b" href="{{$similares[2]->url_detalle}}" style="background-image: url({{asset($similares[2]->imagen)}}); height: 130px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[2]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>   
        </div>

    @break
    @case(4)
        <div class="row mb-0">
            <div class="col-6 pe-0">
                <a class="card mx-0 mb-3 card-style default-link hover-grow-s" data-card-height="270" data-gallery="gallery-b" href="{{$similares[0]->url_detalle}}" style="background-image: url({{asset($similares[0]->imagen)}}); height: 410px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[0]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>    
            <div class="col-6">
                <a class="card mx-0 mb-2 card-style default-link hover-grow-s" data-card-height="130" data-gallery="gallery-b" href="{{$similares[1]->url_detalle}}" style="background-image: url({{asset($similares[1]->imagen)}}); height: 130px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[1]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
                <a class="card mx-0 mb-2 card-style default-link hover-grow-s" data-card-height="130" data-gallery="gallery-b" href="{{$similares[2]->url_detalle}}" style="background-image: url({{asset($similares[2]->imagen)}}); height: 130px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[2]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>
            <div class="col-12">
                <a class="card mx-0 mb-2 card-style default-link hover-grow-s" data-card-height="150" data-gallery="gallery-b" href="{{$similares[3]->url_detalle}}" style="background-image: url({{asset($similares[3]->imagen)}}); height: 150px;">
                    <div class="card-bottom mb-1">
                        <h3 class="color-white text-center  mx-1">{{ Str::limit($similares[3]->nombre(),30)  }}</h3>
                        
                    </div>
                    <div class="card-overlay bg-gradient opacity-70"></div>
                </a>
            </div>
        </div>
    @break
    @default
    {{-- DE NO EXISTIR PRODUCTOS SIMILARES, NO RENDERIZAR EL COMPONENTE --}}
@endswitch