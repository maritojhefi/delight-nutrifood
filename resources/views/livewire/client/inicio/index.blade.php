<div>
    <x-cabecera-pagina titulo="Bienvenido!" cabecera="bordeado"/>


    <div class="splide single-slider slider-no-arrows slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="single-slider-1" style="visibility: visible;">
    <div class="splide__arrows"><button class="splide__arrow splide__arrow--prev" type="button" aria-controls="single-slider-1-track" aria-label="Go to last slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button><button class="splide__arrow splide__arrow--next" type="button" aria-controls="single-slider-1-track" aria-label="Next slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button></div><div class="splide__track" id="single-slider-1-track">
    <div class="splide__list" id="single-slider-1-list" style="transform: translateX(-836px);">
    @foreach (session('subcategorias') as $item)
    <div class="splide__slide splide__slide--clone" style="width: 418px;" aria-hidden="true" tabindex="-1">
        <div class="content">
        <div class="card rounded-l shadow-xl bg-12 mb-3" data-card-height="320" style="height: 320px;">
        <div class="card-top mt-3 me-3">
        <a href="#" wire:click="toast" class="icon icon-s rounded-l shadow-xl bg-red-dark color-white float-end ms-2 me-2"><i class="fa fa-heart"></i></a>
        <a href="#" wire:click="toast" data-menu="menu-share" class="icon icon-s rounded-l shadow-xl bg-highlight color-white float-end"><i class="fa fa-shopping-cart"></i></a>
        </div>
        <div class="card-bottom mb-3">
        <div class="content mb-0">
        <div class="d-flex">
        <div>
        <p class="mb-n1 font-600 color-highlight">{{$item->productos->count()}} Productos</p>
        <h1 class="font-700">{{$item->nombre}}</h1>
        </div>
        <div class="ms-auto">
        <a href="#" wire:click="toast()" class="btn btn-secondary btn-rounded">Ver</a>
        </div>
        </div>
        </div>
        </div>
        <div class="card-overlay bg-gradient-fade rounded-l"></div>
        <div class="card-overlay"></div>
        </div>
        </div>
    </div>
    @endforeach
    </div>
    </div>
    <ul class="splide__pagination"><li><button class="splide__pagination__page is-active" type="button" aria-controls="single-slider-1-slide01" aria-label="Go to slide 1" aria-current="true"></button></li><li><button class="splide__pagination__page" type="button" aria-controls="single-slider-1-slide02" aria-label="Go to slide 2"></button></li><li><button class="splide__pagination__page" type="button" aria-controls="single-slider-1-slide03" aria-label="Go to slide 3"></button></li></ul></div>
    <div class="content mb-3">
    <h5 class="float-start font-16 font-500">Productos mas vendidos</h5>
    
    <div class="clearfix"></div>
    </div>
    <div class="splide double-slider slider-has-arrows slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="double-slider-1" style="visibility: visible;">
    <div class="splide__track" id="double-slider-1-track">
    <div class="splide__list" id="double-slider-1-list" style="transform: translateX(-1254px);">
    
    <div class="splide__slide px-3 splide__slide--clone" style="width: 209px;" aria-hidden="true" tabindex="-1">
        <div class="item bg-theme pb-3 shadow-l rounded-m">
        <div data-card-height="200" class="card mb-3 bg-11 rounded-m" style="height: 200px;">
        <div class="card-bottom">
        <h5 class="color-white text-center pe-2 pb-2">Licuado de leche</h5>
        </div>
        <div class="card-overlay bg-gradient"></div>
        </div>
        <div class="d-flex px-3">
        <div>
        <h3 class="mb-n1">5 bs</h3>
        <span class="opacity-60">antes a 7 bs</span>
        <p class="mb-0">
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
        <i class="fa fa-star color-yellow-dark"></i>
        </p>
        <p class="color-green-dark mb-0 font-11">Disponible en stock</p>
        </div>
        </div>
        </div>
    </div>
   
  
    
 </div>
    </div>
    <ul class="splide__pagination"><li><button class="splide__pagination__page" type="button" aria-controls="double-slider-1-slide01 double-slider-1-slide02" aria-label="Go to page 1"></button></li><li><button class="splide__pagination__page is-active" type="button" aria-controls="double-slider-1-slide03 double-slider-1-slide04" aria-label="Go to page 2" aria-current="true"></button></li></ul></div>
    <div class="divider divider-margins mt-4"></div>
    <div class="card preload-img entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);">
    <div class="card-body">
    <h4 class="color-white font-600">Porque elegirnos?</h4>
    <p class="color-white opacity-80">
    Todos nuestros productos son garantia de calidad y 100% naturales
    </p>
    <div class="card card-style ms-0 me-0 bg-white">
    <div class="row mt-3 pt-1 mb-3">
    <div class="col-6">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe float-start ms-3 me-3" data-feather-line="1" data-feather-size="35" data-feather-color="blue-dark" data-feather-bg="blue-fade-light" style="stroke-width: 1; width: 35px; height: 35px;"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
    <h5 class="color-black float-start font-13 font-500 line-height-s pb-3 mb-3">Envios<br>al interior</h5>
    </div>
    <div class="col-6 ps-0">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smartphone float-start ms-3 me-3" data-feather-line="1" data-feather-size="35" data-feather-color="dark-dark" data-feather-bg="dark-fade-light" style="stroke-width: 1; width: 35px; height: 35px;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
    <h5 class="color-black float-start font-13 font-500 line-height-s pb-3 mb-3">Atencion<br>24/7</h5>
    </div>
    <div class="col-6">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star float-start ms-3 me-3" data-feather-line="1" data-feather-size="35" data-feather-color="yellow-dark" data-feather-bg="yellow-fade-light" style="stroke-width: 1; width: 35px; height: 35px;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
    <h5 class="color-black float-start font-13 font-500 line-height-s">Puntuacion<br>5.0</h5>
    </div>
    <div class="col-6 ps-0">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck float-start ms-3 me-3" data-feather-line="1" data-feather-size="33" data-feather-color="green-dark" data-feather-bg="green-fade-light" style="stroke-width: 1; width: 33px; height: 33px;"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
    <h5 class="color-black float-start font-13 font-500 line-height-s">Envios<br>Delivery</h5>
    </div>
    </div>
    </div>
    </div>
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    </div>
    <div class="divider divider-margins mt-4"></div>
    <div class="content mb-3">
    <h5 class="float-start font-16 font-500">Basado en tus favoritos</h5>
    <a class="float-end font-12 color-highlight mt-n1" href="#">Ver todos</a>
    <div class="clearfix"></div>
    </div>
    <div class="splide double-slider slider-has-arrows slider-no-dots splide--loop splide--ltr splide--draggable is-active" id="double-slider-3" style="visibility: visible;">
    <div class="splide__track" id="double-slider-3-track">
    <div class="splide__list" id="double-slider-3-list" style="transform: translateX(-1254px);">
        <div class="splide__slide px-3 splide__slide--clone" style="width: 209px;" aria-hidden="true" tabindex="-1">
            <div class="item bg-theme pb-3 shadow-l rounded-m">
            <div data-card-height="200" class="card mb-3 bg-11 rounded-m" style="height: 200px;">
            <div class="card-bottom">
            <h5 class="color-white text-center pe-2 pb-2">Licuado de leche</h5>
            </div>
            <div class="card-overlay bg-gradient"></div>
            </div>
            <div class="d-flex px-3">
            <div>
            <h3 class="mb-n1">5 bs</h3>
            <span class="opacity-60">antes a 7 bs</span>
            <p class="mb-0">
            <i class="fa fa-star color-yellow-dark"></i>
            <i class="fa fa-star color-yellow-dark"></i>
            <i class="fa fa-star color-yellow-dark"></i>
            <i class="fa fa-star color-yellow-dark"></i>
            <i class="fa fa-star color-yellow-dark"></i>
            </p>
            <p class="color-green-dark mb-0 font-11">Disponible en stock</p>
            </div>
            </div>
            </div>
        </div>
    </div>
    </div>
    <ul class="splide__pagination"><li><button class="splide__pagination__page" type="button" aria-controls="double-slider-3-slide01 double-slider-3-slide02" aria-label="Go to page 1"></button></li><li><button class="splide__pagination__page is-active" type="button" aria-controls="double-slider-3-slide03 double-slider-3-slide04" aria-label="Go to page 2" aria-current="true"></button></li></ul></div>
    <div class="divider divider-margins mt-4"></div>
    <div class="card mt-4 preload-img" data-src="images/pictures/20s.jpg">
    <div class="card-body">
    <h3 class="color-white font-600">Los mejores descuentos</h3>
    <p class="color-white opacity-80">
    Productos que te podria interesar!
    </p>
    <div class="card rounded-m shadow-xl mb-0">
    <div class="content">
    <div class="d-flex pb-3">
    <div class="pe-3">
    <h5 class="font-14 font-600 opacity-80 pb-2">Licuado de platano </h5>
    <h1 class="font-24 font-700 ">10<sup class="font-15 opacity-50">BS</sup></h1>
    </div>
    <div class="ms-auto">
    <img src="{{asset('delight_logo.jpg')}}" class="rounded-m shadow-xl" width="90">
    </div>
    </div>
    <div class="divider mb-4"></div>
    <div class="d-flex pb-2">
    <div class="pe-3">
    <h5 class="font-14 font-600 opacity-80 pb-2">Chia 300 gr </h5>
    <h1 class="font-24 font-700 color-green-dark">15<sup class="font-15 opacity-50">BS</sup></h1>
    </div>
    <div class="ms-auto">
    <img src="{{asset('delight_logo.jpg')}}" class="rounded-m shadow-xl" width="90">
    </div>
    </div>
    <div class="divider mb-4"></div>
    <a href="#" class="btn btn-full btn-m bg-highlight font-700 text-uppercase rounded-sm">Ir al carrito</a>
    </div>
    </div>
    </div>
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    </div>
    <div class="divider divider-margins"></div>
    
    <div id="notification-1" data-dismiss="notification-1" data-bs-delay="3000" data-bs-autohide="true" class="notification notification-ios bg-dark-dark ms-2 me-2 mt-2 rounded-s fade hide">
        <span class="notification-icon color-white rounded-s">
        <i class="fa fa-bell"></i>
        <em>Enabled</em>
        <i data-dismiss="notification-1" class="fa fa-times-circle"></i>
        </span>
        <h1 class="font-18 color-white mb-n3">All Good</h1>
        <p class="pt-1">
        I'm a notification. I show at the top or bottom of the page.
        </p>
        </div>
    
</div>
