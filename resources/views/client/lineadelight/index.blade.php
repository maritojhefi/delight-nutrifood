@extends('client.master')
@section('content')
    {{-- CABECERA --}}
    <x-cabecera-pagina titulo="Linea Delight" cabecera="appkit" />
    {{-- FUNCIONALIDAD DE BUSQUEDA --}}
    <x-barra-busqueda-productos tipo='lineadelight' />
    {{-- <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
        <div class="content mt-2">
            <div class="search-box bg-theme color-theme rounded-m shadow-l">
                <i class="fa fa-search"></i>
                @php
                    $productoRandom = $productos->random();
                @endphp
                <input type="text" class="border-0" placeholder="Busca por ejm: {{ $productoRandom->nombre }}"
                    data-search="">
                <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
            </div>
            <div class="search-results disabled-search-list mt-3">
                <div class="card card-style mx-0 px-2 p-0 mb-0">
                    @foreach ($productos as $item)
                        <a href="{{ route('delight.detalleproducto', $item->id) }}" class="d-flex py-2"
                            data-filter-item="{{ Str::of($item->nombre)->lower() }}"
                            data-filter-name="{{ Str::of($item->nombre)->lower() }}">
                            <div class="align-self-center">
                                <i class="fa fa-search"></i>
                            </div>
                            <div class="align-self-center ps-2">
                                <span
                                    class="color-theme font-15 d-block mb-0">{{ Str::limit(ucfirst(strtolower($item->nombre)), 35, '...') }}</span>
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
                        No existen coincidencias <span class="fa-fw select-all fas"></span>
                    </p>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- CONTENIDO DE LA PAGINA --}}
    <div class="content mb-0">

        {{-- SLIDER INICIO LINEADELIGHT --}}
        <div class="splide single-slider-custom slider-inicio" 
            id="slider-inicio-lineadelight" 
            data-slider-ready="false"
            style="opacity: 0; transition: opacity 0.3s ease; visibility: visible;">
            <div class="splide__track">
                <ul class="splide__list w-100">
                    {{-- ITEM POPULARES --}}
                    <li class="splide__slide">
                        <a href='#' data-bs-toggle="modal" data-bs-target="#popularProductsModal" 
                        data-subcategoria-id="000" 
                        data-subcategoria-nombre="Nuestros productos mas populares!" 
                        data-card-height="200" 
                        class="card mb-0 shadow-l rounded-m" 
                        style="background-color: #FF5A5A;">
                            <div class="card-center mt-n4 d-flex flex-column align-items-center">
                                <i class="fa fa-apple-alt fa-7x text-white"></i>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">PRODUCTOS POPULARES</h2>
                                <p class="under-heading color-white">Descubre nuestros articulos mas vendidos.</p>
                            </div>
                            <div class="card-overlay dark-mode-tint"></div>
                        </a>
                    </li>

                    {{-- ITEM PLANES Y PAQUETES --}}
                    <li class="splide__slide">
                        <a href="{{ route('categoria.planes') }}" 
                        data-card-height="200" 
                        class="card mb-0 shadow-l rounded-m" 
                        style="background-color: #4ECDC4;">
                            <div class="card-center mt-n4 d-flex flex-column align-items-center">
                                <i class="fa fa-calendar fa-7x text-white"></i>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">PLANES Y PAQUETES</h2>
                                <p class="under-heading color-white">Encuentra la opcion mas apropiada para ti.</p>
                            </div>
                            <div class="card-overlay dark-mode-tint"></div>
                        </a>
                    </li>

                    {{-- TAGS --}}
                    @foreach ($tags as $tag)
                    <li class="splide__slide">
                        <div 
                            data-card-height="200" 
                            data-tag-id="{{$tag->id}}" 
                            data-tag-nombre="{{$tag->nombre}}"  
                            class="productos-tag-trigger card mb-0 shadow-l rounded-m" 
                            style="background-image: url({{asset('imagenes/delight/mesa_tags.webp')}});">
                            <div class="card-center mt-n4 d-flex flex-column align-items-center">
                                <i data-lucide="{{$tag->icono}}" class="lucide-icon text-white" style="width: 5rem; height: 5rem;"></i>
                            </div>
                            <div class="card-bottom text-center mb-3">
                                <h2 class="color-white text-uppercase font-900 mb-0">{{$tag->nombre}}</h2>
                                <p class="under-heading color-white">Productos {{strtolower($tag->nombre)}}</p>
                            </div>
                            <div class="card-overlay dark-mode-tint light-mode-tint"></div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
                
        {{-- SLIDER SUBCATEGORIAS --}}
        <x-slider-subcategorias-horario :horarios="$horarios" :horariosData="$horariosData" />

        {{-- SLIDER PRODUCTOS MAS VENDIDOS --}}
        <div id="best-selling-container" class="my-4">
            <x-slider-productos :productos="$masVendidos" tag="popular" :title="'Los mas vendidos'" />
        </div>

        {{-- SLIDER PRODUCTOS NUEVOS --}}
        <div id="recent-container" class="my-4">
            <x-slider-productos :productos="$masRecientes" tag="recent" title="Novedades" orientation="right" />
        </div>
    </div>

    <x-divider-manzana class="mb-4"/>

    {{-- CARD PRODUCTOS PUNTUADOS --}}
    @if ($conMasPuntos->count() > 0)
    <x-seccion-gana-puntos :productos="$conMasPuntos" />
    @endif


    {{-- MODAL PRODUCTOS POPULARES --}}
    <div class="modal fade" id="popularProductsModal" tabindex="-1" aria-labelledby="popularProductsModalLabel" aria-hidden="true" style="z-index: 1051">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="popular-modal-title" class="mb-0 align-self-center text-uppercase">Nuestros productos mas populares!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 px-3 mt-0 d-flex flex-column" id="listado-productos-populares">
                        <!-- Contenedor items individuales-->
                        @if($masVendidos->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <i class="fa fa-question-circle fa-5x mb-3"></i>
                                <p>Parece que no hay productos disponibles en esta categoria, intenta mas tarde.</p>
                            </div>
                        @else
                            @foreach ($masVendidos as $masVendido)
                                <x-producto-card :producto="$masVendido" />
                            @endforeach
                        @endif
                </div>
            </div>
        </div>
    </div>

    <button id="loader-test" class="btn btn-xl bg-highlight" >TEST LOADER FUNCTION</button>

    <x-menu-adicionales-producto :isUpdate="false"/>
    <x-modal-listado-productos />
    <!-- MODAL ACCIONES PRODUCTO INDIVIDUAL -->

@endsection

@push('scripts')
<!-- SCRIPT CONTROL SLIDER INICIO -->
<script type="module">
import Splide from 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.esm.min.js';

const montarSliderInicio = (slider) => {
    // Revisar si ya está montado
    if (slider.dataset.sliderReady === 'true') {
        console.log('Slider already mounted:', slider.id);
        return;
    }

    try {
        const splide = new Splide(slider, {
            type: 'loop',
            fixedHeight: '200px',
            perPage: 1,
            gap: '1rem',
            arrows: true,
            pagination: false,
            pauseOnHover: true,
            pauseOnFocus: false,
            autoplay: true,
            interval: 5000,
            live: false,
        });

        splide.on('mounted', () => {
            slider.style.opacity = '1';
            slider.dataset.sliderReady = 'true';
            console.log('Slider mounted successfully:', slider.id);
        });

        splide.on('destroy', () => {
            console.log('Slider destroyed:', slider.id);
            slider.dataset.sliderReady = 'false';
        });

        // Montar el slider
        splide.mount();

        // Almacenar instancia
        slider.splide = splide;

    } catch (error) {
        console.error('Error mounting slider:', slider.id, error);
    }
}

function initSliderInicio() {
    const slider = document.querySelector('.slider-inicio');
    
    if (!slider) {
        console.log('Slider inicio no encontrado');
        return;
    }

    console.log('Inicializando slider inicio');

    // Lazy loading usando IntersectionObserver
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target.dataset.sliderReady !== 'true') {
                console.log('Slider entering viewport:', entry.target.id);
                montarSliderInicio(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    observer.observe(slider);
}

// Esperar al DOM y dar tiempo al template para inicializar
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        initSliderInicio();
    }, 200);
});
</script>
{{-- SCRIPT CONTROL DEL MODAL PRODUCTOS CATEGORIZADOS [LINEA-DELGIHT] --}}
<script>
    // LISTAR PRODUCTOS POR TAG [LINEA-DELIGHT]
    $(document).on('click', '.productos-tag-trigger', async function(e) {
        e.preventDefault();

        const tagId = $(this).data('tag-id');
        const tagNombre = $(this).data('tag-nombre');

        try {
            const productosTag = await ProductoService.getProductosTag(tagId);
            abrirDialogListado(productosTag.data, tagNombre);
        } catch (error) {
            console.error('Error cargando los productos del tag correspondiente:', error);
        }
    });
</script>
@endpush