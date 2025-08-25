@props([
    'title' => null,
    'productos' => [],
    'tag' => 'default',
    'orientation' => 'left'
])

@if ($title && $orientation == 'right')
<div class="card float-end bg-red-light mb-2 py-3 me-n4 px-5 rounded-sm" >
    <h2 class="font-24 text-white mb-0" style="z-index: 10">{{$title}}</h2>
    <div class="card-overlay dark-mode-tint"></div>
</div>
@elseif ($title && $orientation == 'left')
<div class="card float-start bg-highlight mb-2 py-3 ms-n4 px-5 rounded-sm"> 
    <h2 class="font-24 text-white mb-0" style="z-index: 10">{{$title}}</h2>
    <div class="card-overlay dark-mode-tint"></div>
</div>
@endif

<div class="splide double-slider visible-slider slider-no-arrows slider-no-dots splide--loop splide--ltr splide--draggable" id="{{$tag}}-products-slider" style="visibility: visible;">
    <div class="splide__track" id="{{$tag}}-products-slider-track">
        <div class="splide__list" id="{{$tag}}-products-slider-list">
            @foreach ($productos as $producto)
            <div class="splide__slide" id="{{$tag}}-products-slider-slide{{$producto->id}}" style="width: 155px;" aria-hidden="true" tabindex="-1">
                <a href="{{route('detalleproducto',$producto->id)}}" class="card m-2 rounded-md card-style">
                    <img src="{{ asset('imagenes/producto/'.$producto->imagen)}}" 
                        onerror="this.src='/imagenes/delight/default-bg-1.png';" 
                        style="height: 150px; width: 100%; object-fit: cover;">
                    <div class="position-absolute position-absolute end-0 p-2 bg-theme bg-dtheme-blue rounded-md color-theme" style="border-radius: 0 0 0 0.375rem;">
                        @if ($producto->descuento && $producto->descuento > 0 && $producto->descuento < $producto->precio)
                        <del class="font-bold">Bs {{$producto->precio}}</del>
                        @endif
                        <h4 class="font-14 mb-0">Bs {{$producto->precioReal()}}</h4>
                    </div>
                    <div class="p-2 bg-theme bg-dtheme-blue rounded-sm">
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="pt-1 mb-1 font-16 line-height-xs mb-0">{{Str::limit(ucfirst(strtolower($producto->nombre)),35)}}</h4>
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