
<div class="mb-4 mx-3 splide single-slider slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="single-slider-1" style="visibility: visible">
    <div class="splide__track" id="single-slider-1-track">
        <div class="splide__list" id="single-slider-1-list">
            {{-- LISTADO DE ELEMENTOS A RENDERIZARSE --}}
            
            {{-- ITEMS EN DESCUENTO --}}
            <div class="splide__slide mx-3 is-active is-visible" id="single-slider-1-slide02" style="width: 320px;">
                <a href="{{ route('categoria.planes') }}" data-card-height="300" class="card mb-0 shadow-xl rounded-m" style="background-image: url({{asset('imagenes/delight/picking_image.jpg')}});background-size: cover; background-position: center;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-bolt fa-7x color-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-gray text-uppercase font-900 mb-0 text-white">EN DESCUENTO</h2>
                        <p class="under-heading color-white">Aprovecha las ultimas ofertas.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint light-mode-tint opacity-95"></div>
                </a>
            </div>

            {{-- ITEM STARK --}}
            <div class="splide__slide mx-3 is-active is-visible" id="single-slider-1-slide01" style="width: 320px;">
                <a href='#' data-card-height="300" class="card mb-0 shadow-xl rounded-m" style="background-image: url({{asset('imagenes/delight/protein_shake.jpg')}});background-size: cover; background-position: center;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-heart fa-7x text-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-white text-uppercase font-900 mb-0">SUMPLEMENTOS STARK</h2>
                        <p class="under-heading color-white">Complementa tu dieta con ayuda de la linea Stark.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint"></div>
                </a>
            </div>
            
            {{-- ITEM POPULARES --}}
            {{-- <div class="splide__slide mx-3 is-active is-visible" id="single-slider-1-slide01" style="width: 320px;">
                <a href='#' data-bs-toggle="modal" data-bs-target="#categorizedProductsModal" data-category-id="000" data-category-name="Nuestros productos mas populares!" data-card-height="300" class="card bg-6 mb-0 shadow-l rounded-m" style="background-color: #FF5A5A;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-star fa-7x text-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-white text-uppercase font-900 mb-0">PRODUCTOS POPULARES</h2>
                        <p class="under-heading color-white">Descubre nuestros articulos mas vendidos.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint"></div>
                </a>
            </div> --}}

            {{-- ITEM PLANES Y PAQUETES --}}
            {{-- <div class="splide__slide mx-2 is-active is-visible" id="single-slider-1-slide02" style="width: 320px;">
                <a href="{{ route('categoria.planes') }}" data-card-height="300" class="card bg-6 mb-0 shadow-l rounded-m" style="height: 300px;background-color: #4ECDC4;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-calendar fa-7x text-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-white text-uppercase font-900 mb-0">PLANES Y PAQUETES</h2>
                        <p class="under-heading color-white">Encuentra la opcion mas apropiada para ti.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint"></div>
                </a>
            </div> --}}
        </div>
    </div>
</div>