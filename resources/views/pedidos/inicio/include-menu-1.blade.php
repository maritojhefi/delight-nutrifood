<div class="card notch-clear rounded-0 gradient-dark mb-n5">
    <div class="card-body">
        <h1 class="color-white font-20 float-start">Arma tu pedido!</h1>
        <a href="#" class="float-end color-white btn btn-xs font-600 rounded-s border-white"><i
                class="fa fa-list me-2"></i>Ver mi pedido</a>
        <div clas="clearfix"></div>
    </div>
    <div class="card-body mx-0 px-0 mt-n3 mb-2">
        <div class="splide single-slider slider-no-arrows slider-no-dots splide--loop splide--ltr splide--draggable is-active"
            id="single-slider-1" style="visibility: visible;">
            
            <div class="splide__track" id="single-slider-1-track">
                <div class="splide__list" id="single-slider-1-list" style="transform: translateX(-1628px);">
                    @foreach ($categorias as $item)
                    <div class="splide__slide splide__slide--clone" style="width: 407px;" aria-hidden="true"
                        tabindex="-1">
                        <div class="card card-style"
                            style="background-image: url(&quot;images/food/regular/2.jpg&quot;); height: 300px;"
                            data-card-height="300">
                            <div class="card-top px-3 py-3">
                                <a href="#" data-menu="menu-heart"
                                    class="bg-white rounded-sm icon icon-xs float-end"><i
                                        class="fa fa-heart color-red-dark"></i></a>
                                <a href="#"
                                    class="bg-white color-black rounded-sm btn btn-xs float-start font-700 font-12">$12.99</a>
                            </div>
                            <div class="card-bottom px-3 py-3">
                                <h1 class="color-white">House Special<br>Crusted Pizza</h1>
                            </div>
                            <div class="card-overlay bg-gradient opacity-30"></div>
                            <div class="card-overlay bg-gradient"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <ul class="splide__pagination">
                <li><button class="splide__pagination__page" type="button"
                        aria-controls="single-slider-1-slide01" aria-label="Go to slide 1"></button></li>
                <li><button class="splide__pagination__page" type="button"
                        aria-controls="single-slider-1-slide02" aria-label="Go to slide 2"></button></li>
                <li><button class="splide__pagination__page is-active" type="button"
                        aria-controls="single-slider-1-slide03" aria-label="Go to slide 3"
                        aria-current="true"></button></li>
            </ul>
        </div>
    </div>
</div>