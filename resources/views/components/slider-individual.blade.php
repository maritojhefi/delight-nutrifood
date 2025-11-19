
<div class="mb-4 mx-3 splide single-slider slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="single-slider-1" style="visibility: visible">
    <div class="splide__track" id="single-slider-1-track">
        <div class="splide__list" id="single-slider-1-list">
            {{-- ITEMS EN DESCUENTO --}}
            <div class="splide__slide mx-3 is-active is-visible" id="single-slider-1-slide02" style="width: 320px;">
                <div data-bs-target="#saleProductsModal" data-bs-toggle="modal"  data-card-height="300" class="card mb-0 shadow-xl rounded-m" style="background-image: url({{asset('imagenes/delight/picking_image.webp')}});background-size: cover; background-position: center;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-bolt fa-7x color-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-gray text-uppercase font-900 mb-0 text-white">EN DESCUENTO</h2>
                        <p class="under-heading color-white">Aprovecha las ultimas ofertas.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint light-mode-tint opacity-95"></div>
                </div>
            </div>

            {{-- ITEM STARK --}}
            <div class="splide__slide mx-3 is-active is-visible" id="single-slider-1-slide01" style="width: 320px;">
                <div data-bs-target="#starkSuplementsModal" data-bs-toggle="modal"  data-card-height="300" class="card mb-0 shadow-xl rounded-m" style="background-image: url({{asset('imagenes/delight/protein_shake.webp')}});background-size: cover; background-position: center;">
                    <div class="card-center mt-n4 d-flex flex-column align-items-center">
                        <i class="fa fa-heart fa-7x text-white"></i>
                    </div>
                    <div class="card-bottom text-center mb-3">
                        <h2 class="color-white text-uppercase font-900 mb-0">SUMPLEMENTOS STARK</h2>
                        <p class="under-heading color-white mx-2">Complementa tu dieta con ayuda de la linea de SUPLEMENTOS STARK.</p>
                    </div>
                    <div class="card-overlay dark-mode-tint"></div>
                </div>
            </div>
        </div>
    </div>
</div>