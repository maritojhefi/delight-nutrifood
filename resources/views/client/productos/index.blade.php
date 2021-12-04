@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Productos" cabecera="bordeado"/>

<x-page-construccion/>
    
  <!--  <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
    <div class="content mt-2">
    <div class="search-box bg-theme color-theme rounded-m shadow-l">
    <i class="fa fa-search"></i>
    <input type="text" class="border-0" placeholder="Busca productos" data-search="">
    <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
    </div>
    <div class="search-results disabled-search-list mt-3">
    <div class="card card-style mx-0 px-2 p-0 mb-0">
    
   @foreach (session('productos') as $item)
   <a href="{{route('detalleproducto',$item->id)}}" class="d-flex py-2" data-filter-item="{{Str::of($item->nombre)->lower()}}" data-filter-name="{{Str::of($item->nombre)->lower()}}">
    <div class="align-self-center">
    <img src="{{asset($item->pathAttachment())}}" class="rounded-sm me-3" width="35" alt="img">
    </div>
    <div class="align-self-center">
    <strong class="color-theme font-16 d-block mb-0">{{$item->nombre}}</strong>
    </div>
    <div class="ms-auto text-center align-self-center pe-2">
    <h5 class="line-height-xs font-16 font-600 mb-0">{{$item->precio}} Bs<sup class="font-11"></sup></h5>
    </div>
    </a>
   @endforeach
    
    
    </div>
    </div>
    </div>
    <div class="search-no-results disabled mt-4">
    <div class="card card-style">
    <div class="content">
    <h1>Sin resultados</h1>
    <p>
    Verifica el texto escrito o busca otro producto
    </p>
    </div>
    </div>
    </div>
    </div>
    <div class="card card-style mx-0 shadow-0 mb-0 bg-transparent">
    <div class="splide double-slider slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active" id="double-slider-1a" style="visibility: visible;">
    <div class="splide__track" id="double-slider-1a-track">
    <div class="splide__list" id="double-slider-1a-list" style="transform: translateX(-1221.5px);">
    
    
    @foreach (session('subcategorias') as $item)
    <div class="splide__slide splide__slide--clone" style="width: 174.5px;">
        <a href="#" class="mx-3">
        <div class="card card-style me-0 mb-0" style="background-image: url(&quot;delight_logo.jpg&quot;); height: 250px;" data-card-height="250">
        <div class="card-bottom p-2 px-3">
        <h4 class="color-white">{{$item->nombre}}</h4>
        </div>
        <div class="card-overlay bg-gradient opacity-80"></div>
        </div>
        </a>
        </div>
    @endforeach
    
    
    </div>
    </div>
    <ul class="splide__pagination"><li><button class="splide__pagination__page" type="button" aria-controls="double-slider-1a-slide01 double-slider-1a-slide02" aria-label="Go to page 1"></button></li><li><button class="splide__pagination__page" type="button" aria-controls="double-slider-1a-slide03 double-slider-1a-slide04" aria-label="Go to page 2"></button></li><li><button class="splide__pagination__page is-active" type="button" aria-controls="double-slider-1a-slide04 double-slider-1a-slide05" aria-label="Go to page 3" aria-current="true"></button></li></ul></div>
    </div>
    <div class="card card-style mt-2">
    <div class="content mb-0">
    <h4>Comprados recientemente</h4>
    <p class="mb-2">Comprados por nuestros clientes</p>
    <div class="list-group list-custom-small">
    <a href="#">
    <img src="{{asset('delight_logo.jpg')}}" alt="img">
    <span>Chia 300 gr</span>
    <i class="fa fa-angle-right"></i>
    </a>
    <a href="#">
    <img src="{{asset('delight_logo.jpg')}}" alt="img">
    <span>Almuerzo vegetariano</span>
    <i class="fa fa-angle-right"></i>
    </a>
    <a href="#">
    <img src="{{asset('delight_logo.jpg')}}" alt="img">
    <span>Pan con semillas</span>
    <i class="fa fa-angle-right"></i>
    </a>
    <a href="#" class="border-0">
    <img src="{{asset('delight_logo.jpg')}}" alt="img">
    <span>Chocolate dietetico</span>
    <i class="fa fa-angle-right"></i>
    </a>
    </div>
    </div>
    </div>
    <div class="card preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);">
    <div class="card-body">
    <h4 class="color-white pt-3 font-24">Promociones de hoy</h4>
    <p class="color-white pt-1">
    Productos con tiempo limitado para su promocion
    </p>
    <div class="card card-style bg-transparent m-0 shadow-0">
    <div class="row mb-0">
    <div class="col-6 pe-2">
    <a href="#" class="card card-style mx-0 mb-3" data-menu="menu-product">
    <img src="{{asset('delight_logo.jpg')}}" alt="img" width="100" class="mx-auto mt-2">
    <div class="p-2">
    <h4 class="mb-0 font-600">Tomatoes</h4>
    <p class="mb-0 font-11 mt-n1">Imported from Asia</p>
    </div>
    <div class="divider mb-0"></div>
    <h5 class="py-3 pb-2 px-2 font-13 font-600">
    $14.50/kg
    <span class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl float-end">10% OFF</span>
    </h5>
    </a>
    </div>
    <div class="col-6 ps-2">
    <a href="#" class="card card-style mx-0 mb-3" data-menu="menu-product">
    <img src="{{asset('delight_logo.jpg')}}" alt="img" width="100" class="mx-auto mt-2">
    <div class="p-2">
    <h4 class="mb-0 font-600">Potatoes</h4>
    <p class="mb-0 font-11 mt-n1">Imported from Europe</p>
    </div>
    <div class="divider mb-0"></div>
    <h5 class="py-3 pb-2 px-2 font-13 font-600">
     $1.35/kg
    <span class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl float-end">25% OFF</span>
    </h5>
    </a>
    </div>
    <div class="col-6 pe-2">
    <a href="#" class="card card-style mx-0 mb-3" data-menu="menu-product">
    <img src="{{asset('delight_logo.jpg')}}" alt="img" width="100" class="mx-auto mt-2">
    <div class="p-2">
    <h4 class="mb-0 font-600">Red Onion</h4>
    <p class="mb-0 font-11 mt-n1">Local Farm Produce</p>
    </div>
    <div class="divider mb-0"></div>
    <h5 class="py-3 pb-2 px-2 font-13 font-600">
    $1.15/kg
    <span class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl float-end">5% OFF</span>
    </h5>
    </a>
    </div>
    <div class="col-6 ps-2">
    <a href="#" class="card card-style mx-0 mb-3" data-menu="menu-product">
    <img src="{{asset('delight_logo.jpg')}}" alt="img" width="100" class="mx-auto mt-2">
    <div class="p-2">
    <h4 class="mb-0 font-600">Green Apple</h4>
    <p class="mb-0 font-11 mt-n1">Local Farm Produce</p>
    </div>
    <div class="divider mb-0"></div>
    <h5 class="py-3 pb-2 px-2 font-13 font-600">
    $0.35/pcs
    <span class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl float-end">3% OFF</span>
    </h5>
    </a>
    </div>
    </div>
    </div>
    </div>
    <div class="card-overlay bg-highlight opacity-90"></div>
    <div class="card-overlay dark-mode-tint"></div>
    </div>
    
   
-->
    
    
@endsection