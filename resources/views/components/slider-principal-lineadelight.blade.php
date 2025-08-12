<div class="my-4 splide single-slider slider-has-arrows slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="single-slider-1" style="visibility: visible">
    <div class="splide__track" id="single-slider-1-track">
        <div class="splide__list" id="single-slider-1-list">
            {{-- LISTADO DE ELEMENTOS A RENDERIZARSE --}}
            
            {{-- ITEM POPULARES --}}
            <div class="splide__slide mx-2 is-active is-visible" id="single-slider-1-slide01" style="width: 320px;">
                <a href="{{ route('delight.listar.populares') }}" data-card-height="200" class="card bg-6 mb-0 shadow-l rounded-m" style="height: 200px; background-color: #FF5A5A;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-apple-alt fa-7x text-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-white text-uppercase font-900 mb-0">PRODUCTOS POPULARES</h2>
                        <p class="under-heading color-white">Descubre nuestros articulos mas vendidos.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint"></div>
                </a>
            </div>
            {{-- ITEM PLANES Y PAQUETES --}}
            <div class="splide__slide mx-2 is-active is-visible" id="single-slider-1-slide02" style="width: 320px;">
                <a href="{{ route('categoria.planes') }}" data-card-height="200" class="card bg-6 mb-0 shadow-l rounded-m" style="height: 200px;background-color: #4ECDC4;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-calendar fa-7x text-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-white text-uppercase font-900 mb-0">PLANES Y PAQUETES</h2>
                        <p class="under-heading color-white">Encuentra la opcion mas apropiada para ti.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint"></div>
                </a>
            </div>
        </div>
    </div>
</div>