@extends('client.master')
{{-- @section('content-comentado')
    <x-cabecera-pagina titulo="Eco-Tienda" cabecera="appkit" />
    <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
        <div class="content mt-2">
            <div class="search-box bg-theme color-theme rounded-m shadow-l">
                <i class="fa fa-search"></i>
                <input type="text" class="border-0" placeholder="Que producto buscas hoy?" data-search="">
                <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
            </div>
            <div class="search-results disabled-search-list mt-3">
                <div class="card card-style mx-0 px-2 p-0 mb-0">

                    @foreach (session('productos') as $item)
                        <a href="{{ route('detalleproducto', $item->id) }}" class="d-flex py-2"
                            data-filter-item="{{ Str::of($item->nombre)->lower() }}"
                            data-filter-name="{{ Str::of($item->nombre)->lower() }}">
                            <div class="align-self-center">
                            </div>
                            <div class="align-self-center">
                                <span
                                    class="color-theme font-15 d-block p-2 mb-0">{{ Str::limit(ucfirst(strtolower($item->nombre)), 35, '...') }}</span>
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
    <div class="splide double-slider slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active"
        id="double-slider-1a" style="visibility: visible;">
        <div class="splide__track" id="double-slider-1a-track">
            <div class="splide__list" id="double-slider-1a-list"
                style="transform: translate(-1162px, 0px); transition: transform 400ms cubic-bezier(0.42, 0.65, 0.27, 0.99) 0s;">
                @foreach ($subcategorias as $item)
                    <div class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="width: 166px;">
                        <a href="{{route('listar.productos.subcategoria',$item->id)}}" class="mx-3">
                            <div class="card card-style me-0 mb-0"
                                style="background-image: url('{{ asset($item->rutaFoto()) }}'); height: 250px;"
                                data-card-height="250">
                                <div class="card-bottom p-2 px-3">
                                    <h4 class="color-white">{{ $item->nombre }}</h4>
                                </div>
                                <div class="card-overlay bg-gradient opacity-80"></div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <ul class="splide__pagination">
            <li><button class="splide__pagination__page" type="button"
                    aria-controls="double-slider-1a-slide01 double-slider-1a-slide02" aria-label="Go to page 1"></button>
            </li>
            <li><button class="splide__pagination__page" type="button"
                    aria-controls="double-slider-1a-slide03 double-slider-1a-slide04" aria-label="Go to page 2"></button>
            </li>
            <li><button class="splide__pagination__page is-active" type="button"
                    aria-controls="double-slider-1a-slide04 double-slider-1a-slide05" aria-label="Go to page 3"
                    aria-current="true"></button></li>
        </ul>
    </div>
    @if ($enDescuento->count() > 0)
        <div class="d-flex px-3 mb-2">
            <h4 class="mb-2 font-600">Productos en descuento!</h4>
        </div>

        <div class="splide single-slider slider-no-arrows slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active"
            id="single-slider-2" style="visibility: visible;">
            <div class="splide__arrows"><button class="splide__arrow splide__arrow--prev" type="button"
                    aria-controls="single-slider-2-track" aria-label="Previous slide"><svg
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40">
                        <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z">
                        </path>
                    </svg></button><button class="splide__arrow splide__arrow--next" type="button"
                    aria-controls="single-slider-2-track" aria-label="Go to first slide"><svg
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40">
                        <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z">
                        </path>
                    </svg></button></div>
            <div class="splide__track" id="single-slider-2-track">
                <div class="splide__list" id="single-slider-2-list" style="transform: translateX(-1328px);">
                    @foreach ($enDescuento as $item)
                    <div class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                        style="width: 332px;">
                        <div data-card-height="400" class="card mx-3 rounded-m shadow-l"
                            style="background-image: url({{ asset($item->pathAttachment()) }}); height: 400px;">
                            <div class="card-top">
                                <a href="#" class="bg-theme color-theme rounded-sm icon icon-xs float-end m-3"><i
                                        class="far fa-shopping-bag font-12"></i></a>
                            </div>
                            <div class="card-bottom p-3 m-2 rounded-m bg-white">
                                <a href="{{route('detalleproducto',$item->id)}}">
                                    <h1 class="font-14 line-height-m font-700 mb-0">
                                        {{Str::limit($item->nombre(),50)}}
                                    </h1>
                                    <p class="mb-0">
                                        {{Str::limit($item->detalle(),60)}}
                                    </p>
                                </a>
                                <div class="d-flex pt-3">
                                    <div class="align-self-center">
                                        <strong class="font-800 font-22 color-theme"><small class="text-secondary"><del>{{$item->precio}}</del></small><span > {{$item->descuento}} Bs</span> </strong>
                                    </div>
                                    <div class="align-self-center ms-auto">
                                        <a href="#"
                                            class="btn-s rounded-s btn bg-highlight font-700 text-uppercase mb-1 carrito" id="{{$item->id}}">Añadir <i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <ul class="splide__pagination">
                <li><button class="splide__pagination__page" type="button" aria-controls="single-slider-2-slide01"
                        aria-label="Go to slide 1"></button></li>
                <li><button class="splide__pagination__page" type="button" aria-controls="single-slider-2-slide02"
                        aria-label="Go to slide 2"></button></li>
                <li><button class="splide__pagination__page is-active" type="button"
                        aria-controls="single-slider-2-slide03" aria-label="Go to slide 3" aria-current="true"></button>
                </li>
            </ul>
        </div>
    @endif
    @if ($conMasPuntos->count() > 0)
        <div class="card preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
            style="background-image: url({{asset(GlobalHelper::getValorAtributoSetting('mas_puntos'))}});">
            <div class="card-body">
                <h4 class="color-white pt-3 font-24">Gana Puntos!</h4>
                <p class="color-white pt-1">
                    Mientras mas puntos, mas premios!
                </p>
                <div class="card card-style bg-transparent m-0 shadow-0">
                    <div class="row mb-0">
                        @foreach ($conMasPuntos as $item)
                            <div class="col-6 ps-2">
                                <a href="{{ route('detalleproducto', $item->id) }}" class="card card-style mx-0 mb-3"
                                    data-menu="menu-product">
                                    <img src="{{ asset($item->pathAttachment()) }}" alt="img" width="100"
                                        class="mx-auto mt-2">
                                    <div class="p-2">
                                        <h4 class="mb-0 font-600">{{ Str::limit($item->nombre(), 20) }}</h4>
                                        <p class="mb-0 font-11 mt-n1">Acumula puntos por su compra!</p>
                                    </div>
                                    <div class="divider mb-0"></div>
                                    <h5 class="py-3 pb-2 px-2 font-13 font-600">
                                        {{ $item->descuento ? $item->descuento : $item->precio }} Bs
                                        <span
                                            class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl float-end">{{ $item->puntos }}
                                            Pts</span>
                                    </h5>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-highlight opacity-90"></div>
            <div class="card-overlay dark-mode-tint"></div>
        </div>
    @endif
@endsection --}}
@section('content')
    <x-cabecera-pagina-highlight titulo="Eco Tienda" />
    <div class="content mb-0">
        <div class="col-12">
            <i data-lucide="search" class="lucide-icon"></i>
            <div class="card bg-white rounded-xl p-3">
                Buscar
            </div>
        </div>

        {{-- SLIDER PRODUCTOS MAS VENDIDOS --}}
        <div id="best-selling-container" class="my-4">
            <x-slider-productos :productos="$masVendidos" tag="popular" :title="'Los mas vendidos'" />
        </div>

        {{-- SLIDER PRODUCTOS MAS RECIENTES --}}
        <div id="recent-container" class="my-4">
            <x-slider-productos :productos="$masRecientes" tag="recent" title="Novedades" orientation="right" />
        </div>
        <a  
            {{-- data-bs-toggle="modal" 
            data-bs-target="#subcategoriesModal" --}}
            href="{{ route('listar.subcategorias.productos') }}"
            data-card-height="100" class="card card-style col-12 mx-0 mt-2 px-0 round-medium shadow-huge hover-grow-xs"
            style="height: 100px;background-color: #FF5A5A;">
            <div class="card-center d-flex flex-row align-items-center justify-content-between ps-3 pe-3">
                <div class="d-flex flex-row align-items-center gap-3">
                    {{-- <i data-lucide="apple" class="lucide-icon" style="color: white; width: 3rem; height: 3rem;"></i> --}}
                    <i class="fa fa-apple-alt fa-3x" style="color: white"></i>
                    <div class="text-start">
                        <h2 class="text-white font-16">Todas nuestras categorias</h2>
                        <p class="mb-0 font-12 text-white opacity-75">Explora las categorias disponibles</p>
                    </div>
                </div>
                <i class="fa fa-arrow-circle-right fa-2x" style="color: white"></i>
            </div>
            <div class="card-overlay dark-mode-tint"></div>
        </a>

        {{-- CARD PRODUCTOS PUNTUADOS --}}
        @if ($conMasPuntos->count() > 0)
            <x-seccion-gana-puntos :productos="$conMasPuntos" />
        {{-- <div class="card card-style rounded-md mx-0 preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
            style="background-image: url({{ asset('imagenes/delight/default-bg-vertical.jpg') }});">
            <div class="card-body">
                <div class="mx-4 mb-0">
                    <h4 class="color-white pt-3 font-24">Gana Puntos!</h4>
                    <p class="color-white pt-1 mb-2">
                        Los productos seleccionados atribuyen puntos por cada compra realizada.
                        Mientras mas puntos, mas premios!
                    </p>
                </div>
                <div class="card card-style bg-transparent m-0 shadow-0">
                    <div class="row mb-0 p-2">
                        @foreach ($conMasPuntos as $producto)
                            <div class="col-6">
                                <a href="{{ route('delight.detalleproducto', $producto->id) }}"
                                    class="card card-style py-3 d-flex align-items-center hover-grow" data-menu="menu-product">
                                    <img src="{{ asset('imagenes/delight/optimal_logo.svg')}}" alt="img" width="100"
                                    class="mx-auto">
                                    <div class="p-2">
                                        <p class="mb-0 font-600 text-center">{{ Str::limit($producto->nombre(), 40) }}</p>
                                    </div>
                                    <div class="divider mb-0"></div>
                                    <div class="d-flex flex-row justify-content-between gap-4 mb-0">
                                        <p class="font-600 mb-0">Bs. {{ $producto->descuento ? $producto->descuento : $producto->precio }}</p>
                                        <p class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl mb-0">{{ $producto->puntos }} Pts</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-highlight opacity-90"></div>
            <div class="card-overlay dark-mode-tint"></div>
        </div> --}}
        @endif
    </div>

    {{-- MODAL SUPLEMENTOS STARK --}}
    <div class="modal fade" id="starkSuplementsModal" tabindex="-1" aria-labelledby="starkSuplementsModalLabel" aria-hidden="true" style="z-index: 9999">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mx-2 mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="stark-modal-title" class="mb-0 ms-4 align-self-center text-uppercase">STARK SUPLEMENTS</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 px-3 d-flex flex-column"  id="listado-productos-stark">
                    {{-- <div class="p-0 m-0 justify-content-center align-items-center"> --}}
                        <!-- Contenedor items individuales-->
                        @if($suplementosStark->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <i class="fa fa-question-circle fa-5x mb-3"></i>
                                <p>Parece que no hay suplementos stark en stock ahora mismo, verifica más tarde.</p>
                            </div>
                        @else
                            @foreach ($suplementosStark as $productoStark)
                                <x-producto-card :producto="$productoStark" />
                            @endforeach
                        @endif
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PRODUCTOS EN OFERTA --}}
    <div class="modal fade" id="saleProductsModal" tabindex="-1" aria-labelledby="saleProductsModalLabel" aria-hidden="true" style="z-index: 9999">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="sale-modal-title" class="mb-0 align-self-center text-uppercase">Productos en Oferta</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 px-3 mt-0 d-flex flex-column" id="listado-productos-ofertados">
                    {{-- <div class="content justify-content-center align-items-center" > --}}
                        <!-- Contenedor items individuales-->
                        @if($enDescuento->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <i class="fa fa-question-circle fa-5x mb-3"></i>
                                <p>Parece que no hay productos en oferta ahora mismo, verifica más tarde.</p>
                            </div>
                        @else
                            @foreach ($enDescuento as $ofertado)
                                <x-producto-card :producto="$ofertado" />
                            @endforeach
                        @endif
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script> 
    $(document).ready(function() {
        $(document).on('click', '.add-to-cart', addToCartHandler);
    });

    async function addToCartHandler() {
        const product_Id = $(this).data('producto-id');
        const product_nombre = $(this).data('producto-nombre')

        try {
            const result = await carritoStorage.addToCart(product_Id, 1);
            if (result.success) {
                console.log("Producto  agregado con exito al carrito.")
            } else {
                console.log(`Error al agregar el producto ${product_nombre} al carrito.`)
            }
        } catch (error) {
            console.error('Error agregando el producto al carrito:', error);
        }
    }
</script>
{{-- SCRIPT CONTROL DEL MODAL PRODUCTOS CATEGORIZADOS [ECO-TIENDA] --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        saleProductsModal = new bootstrap.Modal(document.getElementById('saleProductsModal'), {
            focus: true
        });

        starkProductsModal = new bootstrap.Modal(document.getElementById('starkSuplementsModal'), {
            focus: true
        });

        const saleModalElement = document.getElementById('saleProductsModal');
        const starkModalElement = document.getElementById('starkSuplementsModal');

        saleModalElement.addEventListener('show.bs.modal', async function (event) {
            const triggerElement = event.relatedTarget;
        });

        starkModalElement.addEventListener('show.bs.modal', async function (event) {
            const triggerElement = event.relatedTarget;
        });
    });
</script>

@endpush