@if ($title)
<h1 class="font-18">{{$title}}</h1>
@endif

<div class="splide double-slider visible-slider slider-no-arrows slider-no-dots splide--loop splide--ltr splide--draggable" id="popular-products-slider" style="visibility: visible;">
    <div class="splide__track" id="popular-products-slider-track">
        <div class="splide__list" id="popular-products-slider-list">
            @foreach ($productos as $producto)
            <div class="splide__slide" id="popular-products-slider-slide01" style="width: 155px;" aria-hidden="true" tabindex="-1">
                <a href="{{route('detalleproducto',$producto->id)}}" class="card m-2 rounded-md card-style">
                    <img src="{{ asset('imagenes/delight/'.$producto->imagen)}}" 
                        onerror="this.src='/imagenes/delight/default-bg-1.png';" 
                        style="height: 150px; width: 100%; object-fit: cover;">
                    <div class="position-absolute position-absolute end-0 p-2 bg-theme bg-dtheme-blue rounded-md color-theme">
                        <h4 class="font-14 mb-0">Bs {{$producto->precioReal()}}</h4>
                    </div>
                    <div class="p-2 bg-theme bg-dtheme-blue rounded-sm">
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="pt-1 mb-1 font-16 line-height-xs mb-0">{{Str::limit(ucfirst(strtolower($producto->nombre)),37)}}</h4>
                                {{-- <span class="font-11">per serving</span> --}}
                            </div>
                        </div>
                        {{-- <p class="font-10 mb-0 pt-1"><i class="fa fa-star color-yellow-dark pe-2"></i>34 Recommend It</p> --}}
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>