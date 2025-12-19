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
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px">
            <div class="modal-content border-0 overflow-hidden" style="border-radius: 20px;">
                <!-- Header que respeta el tema (oscuro / claro) del master -->
                <div class="position-relative">
                    <!-- Botón cerrar -->
                    <button type="button" class="btn-close btn-close-white position-absolute" 
                        data-bs-dismiss="modal" aria-label="Close"
                        style="top: 15px; right: 15px; opacity: 0.7; z-index: 10;"></button>
                    
                    <!-- Encabezado del modal -->
                    <div class="px-4 pt-4 pb-3">
                        <p class="mb-1 font-600 opacity-50 font-12">
                            <span id="cantidad-populares-listado">{{ $masVendidos->count() }}</span> producto(s) encontrados:
                        </p>
                        <h4 id="popular-modal-title" class="color-theme font-22 font-800 mb-0 text-uppercase" style="letter-spacing: 1px;">
                            Nuestros productos mas populares!
                        </h4>
                    </div>
                    
                    <!-- Contenedor de productos -->
                    <div class="px-4 pb-4" style="max-height: 60vh; overflow-y: auto;" id="listado-productos-populares">
                        <!-- Contenedor items individuales-->
                        @if($masVendidos->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <i class="fa fa-question-circle fa-5x mb-3"></i>
                                <p>Parece que no hay productos disponibles en esta categoria, intenta mas tarde.</p>
                            </div>
                        @else
                            @foreach ($masVendidos as $masVendido)
                                <x-producto-card :producto="$masVendido" :version="2" class="listado-populares-item" />
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-menu-adicionales-producto :isUpdate="false"/>
    <x-modal-listado-productos />
    <!-- MODAL ACCIONES PRODUCTO INDIVIDUAL -->

@endsection

@push('styles')
<style>
    /* Estilos para el modal de productos populares - Diseño menú elegante */
    #popularProductsModal .modal-content {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    /* Ajustes visuales para las tarjetas dentro del modal */
    #popularProductsModal .listado-populares-item {
        padding: 4px 0;
    }

    #popularProductsModal .listado-populares-item .card.card-style {
        margin-bottom: 8px !important;
        border-radius: 12px;
        transition: background 0.2s ease;
    }

    #popularProductsModal .listado-populares-item .card.card-style:hover {
        background: rgba(0, 0, 0, 0.04) !important;
    }

    #popularProductsModal .listado-populares-item:last-of-type .card.card-style {
        margin-bottom: 0 !important;
    }
    
    /* Scrollbar personalizado para el contenedor de productos populares */
    #listado-productos-populares::-webkit-scrollbar {
        width: 6px;
    }
    
    #listado-productos-populares::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 3px;
    }
    
    #listado-productos-populares::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
    
    #listado-productos-populares::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@push('scripts')
<!-- SCRIPT CONTROL SLIDER INICIO -->
<script type="module">
import Splide from 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.esm.min.js';

const montarSliderInicio = (slider) => {
    // Revisar si ya está montado
    if (slider.dataset.sliderReady === 'true') {
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
        });

        splide.on('destroy', () => {
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
        return;
    }

    // Lazy loading usando IntersectionObserver
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target.dataset.sliderReady !== 'true') {
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