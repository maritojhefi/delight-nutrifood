@extends('client.master')
@section('content-comentado')
    <x-cabecera-pagina titulo="Mi carrito" cabecera="bordeado" />
    <div class="card card-style">
        <div class="content">
            @foreach ($user->addCarrito as $item)
            <div class="d-flex pb-2">
                <div class="me-auto">
                    <img src="{{asset($item->pathAttachment())}}" class="rounded-m shadow-xl" width="110">
                    <a href="#" data-menu="cart-item-edit" class="color-white mt-n5 py-3 ps-2 d-block font-11"><i
                            class="fa fa-pen ps-2 pe-2"></i> </a>
                </div>
                <div class="ms-auto w-100 ps-3">
                    <h5 class="font-14 font-600 opacity-80 pb-2">{{$item->nombre()}} </h5>
                    <div class="clearfix"></div>
                    <h1 class="font-23 font-700 float-start pt-2 ">{{$item->precio()}}<sup class="font-15 opacity-50">BS</sup></h1>
                    <div class="">
                        <div
                            class="input-style float-end w-50 has-borders no-icon input-style-always-active validate-field mb-4">
                            <input type="number" class="form-control validate-number font-500 font-12"  value="{{$item->pivot->cantidad}}"
                                placeholder="1">
                            <label for="form2a" class="color-highlight">Cantidad</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                </div>
            </div> 
            @endforeach
            
           
            <div class="divider mt-3"></div>
            <h4>Resumen de pedido</h4>
            <p>
                El pedido se procesara una vez haya completado el pago
            </p>
            <div class="row mb-0">
                
                <div class="col-6 text-start">
                    <h4>Total</h4>
                </div>
                <div class="col-6 text-end">
                    
                    <h4 class="font-600">{{$user->addCarrito->sum('precio')}}<sup>BS</sup></h4>
                </div>
            </div>
            <div class="divider mt-4"></div>
            <a href="#" class="btn btn-full btn-sm rounded-sm bg-highlight font-800 text-uppercase">Realizar pago seguro</a>
        </div>
    </div>
@endsection



@section('content')
    <x-cabecera-pagina titulo="Mi Carrito" cabecera="bordeado" />
    <div class="listado-carrito card card-style">
        <div class="content">
            @if (!empty($listado))
                @foreach ($listado as $producto)
                    <div class="cart-item-wrapper mb-4">
                        <div class="d-flex flex-row item-carrito-info justify-content-between p-3 bg-white rounded shadow-sm border">
                            <div class="d-flex flex-column item-carrito-detalles flex-grow-1 me-3">
                                <h5 class="fw-bold text-dark mb-2">{{$producto->nombre}}</h5>
                                <p class="text-muted mb-3 small">{{$producto->descripcion}}</p>
                                <div class="d-flex flex-row justify-content-between align-items-center mt-auto">
                                    <p class="fw-bold mb-0 text-success fs-5">Bs. {{$producto->precio}}</p>
                                    <div class="quantity-controls bg-light border rounded d-flex align-items-center" style="min-width: 120px;">
                                        <button class="btn btn-sm btn-outline-secondary border-0 px-2 py-1" type="button">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <span id="item-{{$producto->id}}-qty" class="px-3 fw-semibold">{{$producto->cantidad}}</span>
                                        <button class="btn btn-sm btn-outline-secondary border-0 px-2 py-1" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="product-image-container">
                                <img class="product-image rounded" src="{{$producto->imagen}}" alt="{{$producto->nombre}}">
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="contenido-carrito-vacio d-flex flex-column align-items-center">
                    <i class="fa fa-6x fa-shopping-cart"></i>
                    <p class="mb-0 mt-3">Parece ser que tu carrito esta vacio.</p>
                    <p>Consigue ya nuevos productos!!!</p>
                    {{-- <div class="d-flex flex-row justify-content-center">
                    
                    </div> --}}
                </div>
            @endif
        </div>
    </div>

    @if (empty($listado))
        <div class="splide single-slider slider-has-arrows slider-arrows-push slider-has-dots splide--loop splide--ltr splide--draggable is-active" id="single-slider-6" style="visibility: visible;">
            <div class="splide__arrows">
                <button class="splide__arrow splide__arrow--prev bg-white" type="button" aria-controls="single-slider-6-track" aria-label="Previous slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button>
                <button class="splide__arrow splide__arrow--next bg-white" type="button" aria-controls="single-slider-6-track" aria-label="Go to first slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button>
            </div>  
            <div class="splide__track" id="single-slider-6-track">
                <div class="splide__list" id="single-slider-6-list" style="transform: translateX(-1520px);">
                    <div class="splide__slide splide__slide--clone" style="width: 380px;" >
                        <div data-card-height="250" class="bg-red-dark card mx-3 bg-14 rounded-m shadow-l">
                            <div class="card-top mt-4 d-flex flex-column align-items-center">
                                    <a href="/miperfil/misplanes" class="text-white">
                                        <i class="fa fa-heart fa-9x"></i>   
                                    </a>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">Planes</h2>
                                <p class="under-heading color-white">Accede a planes personalizables y nos encargaremos de todo.</p>
                            </div>
                            {{-- <div class="card-overlay bg-gradient"></div> --}}
                        </div>
                    </div>
                    <div class="splide__slide splide__slide--clone" style="width: 380px;">
                        <div data-card-height="250" class="bg-green-light card mx-3 bg-14 rounded-m shadow-l">
                            <div class="card-top mt-4 d-flex flex-column align-items-center">
                                    <a href="/lineadelight" class="text-white">
                                        <i class="fa fa-leaf fa-9x"></i>
                                    </a>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">Linea Delight</h2>
                                <p class="under-heading color-white">Date el gusto y obten el producto que deseas.</p>
                            </div>
                            {{-- <div class="card-overlay bg-gradient"></div> --}}
                        </div>
                    </div>
                    <div class="splide__slide" id="single-slider-6-slide01" style="width: 380px;">
                        <div data-card-height="250" class="bg-brown-light card mx-3 bg-18 rounded-m shadow-l">
                            <div class="card-top mt-4 d-flex flex-column align-items-center">
                                    <a href="/productos" class="text-white">
                                        <i class="fa fa-gem fa-9x"></i>
                                    </a>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">Eco-Tienda</h2>
                                <p class="under-heading color-white">Destaca con accesorios mientras ayudas al planeta.</p>
                            </div>
                            {{-- <div class="card-overlay bg-gradient"></div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <ul class="splide__pagination">
                <li>
                    <button class="splide__pagination__page" type="button" 
                    aria-controls="single-slider-6-slide01" aria-label="Go to slide 1"></button>
                </li>
                <li>
                    <button class="splide__pagination__page" type="button" 
                    aria-controls="single-slider-6-slide02" aria-label="Go to slide 2"></button>
                </li>
                <li>
                    <button class="splide__pagination__page" type="button" 
                    aria-controls="single-slider-6-slide03" aria-label="Go to slide 3"></button>
                </li>
            </ul>
        </div>
    @endif

    <div class="resumen-carrito bg-highlight my-4 py-4 d-flex flex-column justify-content-center align-items-center opacity-95">
        <div class="content d-flex flex-column justify-content-center align-items-center" style="z-index: 10"> 
            <h2 class="text-white">Mi Cuenta</h2>
            <p class="text-white">Los costos se ajustan a nuestros terminos y condiciones</p>
            <div class="card card-style mx-2">
                <div class="content">
                    <div class="resumen-carrito-detalles d-flex flex-column justify-content-center align-items-center">
                        <p>Texto de un item</p>
                        <button class="btn btn-m rounded-sm text-uppercase font-800">Realizar Pago</button>
                        
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card-overlay bg-highlight opacity-95"></div> --}}
        <div class="card-overlay dark-mode-tint"></div>
    </div>
    <div class="divider bg-grey-dark divider-margins my-3"></div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
        });        
    </script>
@endpush