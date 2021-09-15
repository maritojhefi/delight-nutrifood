@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Productos" cabecera="bordeado"/>


    
    <div class="card card-style bg-transparent mx-0 mb-n2 mt-n3 shadow-0">
    <div class="content mt-2">
    <div class="search-box bg-theme color-theme rounded-m shadow-l">
    <i class="fa fa-search"></i>
    <input type="text" class="border-0" placeholder="Search.. - try Milk " data-search="">
    <a href="#" class="clear-search disabled mt-0"><i class="fa fa-times color-red-dark"></i></a>
    </div>
    <div class="search-results disabled-search-list mt-3">
    <div class="card card-style mx-0 px-2 p-0 mb-0">
    <a href="#" class="d-flex py-2" data-filter-item="" data-filter-name="all coffee milk fresh dairy taste">
    <div class="align-self-center">
    <img src="images/grocery/6s.jpg" class="rounded-sm me-3" width="35" alt="img">
    </div>
    <div class="align-self-center">
    <strong class="color-theme font-16 d-block mb-0">Fresh Milk</strong>
    </div>
    <div class="ms-auto text-center align-self-center pe-2">
    <h5 class="line-height-xs font-16 font-600 mb-0">$2.<sup class="font-11 pt-1">05</sup></h5>
    </div>
    </a>
    <a href="#" class="d-flex py-2" data-filter-item="" data-filter-name="all fruit orange">
    <div class="align-self-center">
    <img src="images/grocery/7s.jpg" class="rounded-sm me-3" width="35" alt="img">
    </div>
    <div class="align-self-center">
    <strong class="color-theme font-16 d-block mb-0">Oranges</strong>
    </div>
    <div class="ms-auto text-center align-self-center pe-2">
    <h5 class="line-height-xs font-16 font-600 mb-0">$24.<sup class="font-11 pt-1">10</sup></h5>
    </div>
    </a>
    <a href="#" class="d-flex py-2" data-filter-item="" data-filter-name="all fruit peach">
    <div class="align-self-center">
    <img src="images/grocery/5s.jpg" class="rounded-sm me-3" width="35" alt="img">
    </div>
    <div class="align-self-center">
    <strong class="color-theme font-16 d-block mb-0">Peaches</strong>
    </div>
    <div class="ms-auto text-center align-self-center pe-2">
    <h5 class="line-height-xs font-16 font-600 mb-0">$12.<sup class="font-11 pt-1">15</sup></h5>
    </div>
    </a>
    <a href="#" class="d-flex py-2" data-filter-item="" data-filter-name="all fruit tomato legume">
    <div class="align-self-center">
    <img src="images/grocery/12s.jpg" class="rounded-sm me-3" width="35" alt="img">
    </div>
    <div class="align-self-center">
    <strong class="color-theme font-16 d-block mb-0">Tomatoes</strong>
    </div>
    <div class="ms-auto text-center align-self-center pe-2">
    <h5 class="line-height-xs font-16 font-600 mb-0">$12.<sup class="font-11 pt-1">25</sup></h5>
    </div>
    </a>
    <a href="#" class="d-flex py-2" data-filter-item="" data-filter-name="all cucumber pickle legume">
    <div class="align-self-center">
    <img src="images/grocery/4s.jpg" class="rounded-sm me-3" width="35" alt="img">
    </div>
    <div class="align-self-center">
    <strong class="color-theme font-16 d-block mb-0">Cucumbers</strong>
    </div>
    <div class="ms-auto text-center align-self-center pe-2">
    <h5 class="line-height-xs font-16 font-600 mb-0">$5.<sup class="font-11 pt-1">15</sup></h5>
    </div>
    </a>
    <a href="#" class="d-flex py-2" data-filter-item="" data-filter-name="all salad cabbage salad legume">
    <div class="align-self-center">
    <img src="images/grocery/11s.jpg" class="rounded-sm me-3" width="35" alt="img">
    </div>
    <div class="align-self-center">
    <strong class="color-theme font-16 d-block mb-0">Iceberg Salad</strong>
    </div>
    <div class="ms-auto text-center align-self-center pe-2">
    <h5 class="line-height-xs font-16 font-600 mb-0">$2.<sup class="font-11">31</sup></h5>
    </div>
    </a>
    </div>
    </div>
    </div>
    <div class="search-no-results disabled mt-4">
    <div class="card card-style">
    <div class="content">
    <h1>No Results</h1>
    <p>
    Your search brought up no results. Try using a different keyword. Or try typing all
    to see all items in the demo. These can be linked to anything you want.
    </p>
    </div>
    </div>
    </div>
    </div>
    <div class="card card-style mx-0 shadow-0 mb-0 bg-transparent">
    <div class="splide double-slider slider-no-dots visible-slider splide--loop splide--ltr splide--draggable is-active" id="double-slider-1a" style="visibility: visible;">
    <div class="splide__track" id="double-slider-1a-track">
    <div class="splide__list" id="double-slider-1a-list" style="transform: translateX(-1221.5px);">
    <div class="splide__slide splide__slide--clone" style="width: 174.5px;" aria-hidden="true" tabindex="-1">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/3m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Local</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div><div class="splide__slide splide__slide--clone" style="width: 174.5px;" aria-hidden="true" tabindex="-1">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/4m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Greens</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div><div class="splide__slide splide__slide--clone" style="width: 174.5px;">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/5m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Sugary</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div><div class="splide__slide splide__slide--clone" style="width: 174.5px;">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/11m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Dietary</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div><div class="splide__slide" id="double-slider-1a-slide01" style="width: 174.5px;" aria-hidden="true" tabindex="-1">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/1m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Imports</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div>
    <div class="splide__slide" id="double-slider-1a-slide02" style="width: 174.5px;" aria-hidden="true" tabindex="-1">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/3m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Local</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div>
    <div class="splide__slide" id="double-slider-1a-slide03" style="width: 174.5px;" aria-hidden="true" tabindex="-1">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/4m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Greens</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div>
    <div class="splide__slide is-visible is-active" id="double-slider-1a-slide04" style="width: 174.5px;" aria-hidden="false" tabindex="0">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/5m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Sugary</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div>
    <div class="splide__slide is-visible" id="double-slider-1a-slide05" style="width: 174.5px;" aria-hidden="false" tabindex="0">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/11m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Dietary</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div>
    <div class="splide__slide splide__slide--clone" style="width: 174.5px;">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/1m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Imports</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div><div class="splide__slide splide__slide--clone" style="width: 174.5px;">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/3m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Local</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div><div class="splide__slide splide__slide--clone" style="width: 174.5px;">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/4m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Greens</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div><div class="splide__slide splide__slide--clone" style="width: 174.5px;">
    <a href="#" class="mx-3">
    <div class="card card-style me-0 mb-0" style="background-image: url(&quot;images/grocery/5m.jpg&quot;); height: 250px;" data-card-height="250">
    <div class="card-bottom p-2 px-3">
    <h4 class="color-white">Sugary</h4>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
    </a>
    </div></div>
    </div>
    <ul class="splide__pagination"><li><button class="splide__pagination__page" type="button" aria-controls="double-slider-1a-slide01 double-slider-1a-slide02" aria-label="Go to page 1"></button></li><li><button class="splide__pagination__page" type="button" aria-controls="double-slider-1a-slide03 double-slider-1a-slide04" aria-label="Go to page 2"></button></li><li><button class="splide__pagination__page is-active" type="button" aria-controls="double-slider-1a-slide04 double-slider-1a-slide05" aria-label="Go to page 3" aria-current="true"></button></li></ul></div>
    </div>
    <div class="card card-style mt-2">
    <div class="content mb-0">
    <h4>Recently Viewed</h4>
    <p class="mb-2">Products you've recently viewed</p>
    <div class="list-group list-custom-small">
    <a href="#">
    <img src="images/grocery/isolated/3.png" alt="img">
    <span>Strawberries</span>
    <i class="fa fa-angle-right"></i>
    </a>
    <a href="#">
    <img src="images/grocery/isolated/6.png" alt="img">
    <span>Iceberg Salad</span>
    <i class="fa fa-angle-right"></i>
    </a>
    <a href="#">
    <img src="images/grocery/isolated/4.png" alt="img">
    <span>Red EU Onions</span>
    <i class="fa fa-angle-right"></i>
    </a>
    <a href="#" class="border-0">
    <img src="images/grocery/isolated/1.png" alt="img">
    <span>American Tomatoes</span>
    <i class="fa fa-angle-right"></i>
    </a>
    </div>
    </div>
    </div>
    <div class="card preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);">
    <div class="card-body">
    <h4 class="color-white pt-3 font-24">On Sale Today</h4>
    <p class="color-white pt-1">
    Fresh in stock and the best offers for you. Get them while they're hot! Today only!
    </p>
    <div class="card card-style bg-transparent m-0 shadow-0">
    <div class="row mb-0">
    <div class="col-6 pe-2">
    <a href="#" class="card card-style mx-0 mb-3" data-menu="menu-product">
    <img src="images/grocery/isolated/1.png" alt="img" width="100" class="mx-auto mt-2">
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
    <img src="images/grocery/isolated/2.png" alt="img" width="100" class="mx-auto mt-2">
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
    <img src="images/grocery/isolated/4.png" alt="img" width="100" class="mx-auto mt-2">
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
    <img src="images/grocery/isolated/9.png" alt="img" width="100" class="mx-auto mt-2">
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
    <div class="d-flex px-3 mb-2">
    <h4 class="mb-2 font-600">Recommended for You</h4>
    </div>
    <div class="splide single-slider slider-no-dots slider-no-arrows visible-slider splide--loop splide--ltr splide--draggable is-active" id="single-slider-3" style="visibility: visible;">
    <div class="splide__arrows"><button class="splide__arrow splide__arrow--prev" type="button" aria-controls="single-slider-3-track" aria-label="Previous slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button><button class="splide__arrow splide__arrow--next" type="button" aria-controls="single-slider-3-track" aria-label="Go to first slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg></button></div><div class="splide__track" id="single-slider-3-track">
    <div class="splide__list" id="single-slider-3-list" style="transform: translateX(-1396px);">
    <div class="splide__slide splide__slide--clone" style="width: 349px;" aria-hidden="true" tabindex="-1">
    <div class="card card-style">
    <img src="images/grocery/2w.jpg" alt="img" class="img-fluid">
    <div class="content mt-3">
    <h2 class="font-17">Wholegrain Bread <span class="float-end">$1.30</span></h2>
    <p class="mb-3">
    With wheat from local farms for added richness, texture and flavor.
    </p>
    <a href="#" class="btn btn-s rounded-s text-uppercase bg-blue-dark font-700 btn-full">Add to Bag</a>
    </div>
    </div>
    </div><div class="splide__slide splide__slide--clone" style="width: 349px;">
    <div class="card card-style">
    <img src="images/grocery/1w.jpg" alt="img" class="img-fluid">
    <div class="content mt-3">
    <h2 class="font-17">Cranberries <span class="float-end">$3.45</span></h2>
    <p class="mb-3">
    Freshly farmed from the mountain, grown organically.
    </p>
    <a href="#" class="btn btn-s rounded-s text-uppercase bg-blue-dark font-700 btn-full">Add to Bag</a>
    </div>
    </div>
    </div><div class="splide__slide" id="single-slider-3-slide01" style="width: 349px;" aria-hidden="true" tabindex="-1">
    <div class="card card-style">
    <img src="images/grocery/5w.jpg" alt="img" class="img-fluid">
    <div class="content mt-3">
    <h2 class="font-17">Peaches <span class="float-end">$7.50</span></h2>
    <p class="mb-3">
    Freshly delivered from exotic regions for an added sweet taste.
    </p>
    <a href="#" class="btn btn-s rounded-s text-uppercase bg-blue-dark font-700 btn-full">Add to Bag</a>
    </div>
    </div>
    </div>
    <div class="splide__slide" id="single-slider-3-slide02" style="width: 349px;" aria-hidden="true" tabindex="-1">
    <div class="card card-style">
    <img src="images/grocery/2w.jpg" alt="img" class="img-fluid">
    <div class="content mt-3">
    <h2 class="font-17">Wholegrain Bread <span class="float-end">$1.30</span></h2>
    <p class="mb-3">
    With wheat from local farms for added richness, texture and flavor.
    </p>
    <a href="#" class="btn btn-s rounded-s text-uppercase bg-blue-dark font-700 btn-full">Add to Bag</a>
    </div>
    </div>
    </div>
    <div class="splide__slide is-active is-visible" id="single-slider-3-slide03" style="width: 349px;" aria-hidden="false" tabindex="0">
    <div class="card card-style">
    <img src="images/grocery/1w.jpg" alt="img" class="img-fluid">
    <div class="content mt-3">
    <h2 class="font-17">Cranberries <span class="float-end">$3.45</span></h2>
    <p class="mb-3">
    Freshly farmed from the mountain, grown organically.
    </p>
    <a href="#" class="btn btn-s rounded-s text-uppercase bg-blue-dark font-700 btn-full">Add to Bag</a>
    </div>
    </div>
    </div>
    <div class="splide__slide splide__slide--clone" style="width: 349px;">
    <div class="card card-style">
    <img src="images/grocery/5w.jpg" alt="img" class="img-fluid">
    <div class="content mt-3">
    <h2 class="font-17">Peaches <span class="float-end">$7.50</span></h2>
    <p class="mb-3">
    Freshly delivered from exotic regions for an added sweet taste.
    </p>
    <a href="#" class="btn btn-s rounded-s text-uppercase bg-blue-dark font-700 btn-full">Add to Bag</a>
    </div>
    </div>
    </div><div class="splide__slide splide__slide--clone" style="width: 349px;">
    <div class="card card-style">
    <img src="images/grocery/2w.jpg" alt="img" class="img-fluid">
    <div class="content mt-3">
    <h2 class="font-17">Wholegrain Bread <span class="float-end">$1.30</span></h2>
    <p class="mb-3">
    With wheat from local farms for added richness, texture and flavor.
    </p>
    <a href="#" class="btn btn-s rounded-s text-uppercase bg-blue-dark font-700 btn-full">Add to Bag</a>
    </div>
    </div>
    </div></div>
    </div>
    <ul class="splide__pagination"><li><button class="splide__pagination__page" type="button" aria-controls="single-slider-3-slide01" aria-label="Go to slide 1"></button></li><li><button class="splide__pagination__page" type="button" aria-controls="single-slider-3-slide02" aria-label="Go to slide 2"></button></li><li><button class="splide__pagination__page is-active" type="button" aria-controls="single-slider-3-slide03" aria-label="Go to slide 3" aria-current="true"></button></li></ul></div>
    
    
    
@endsection