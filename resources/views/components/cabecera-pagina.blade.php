
 

        @switch($cabecera)
        @case('bordeado')
        <div class="page-title page-title-small">
            <h2><a href="#" data-back-button=""><i class="fa fa-arrow-left"></i></a>{{$titulo}}</h2>
            <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded" data-src="images/avatars/5s.png" data-ll-status="loaded" style="background-image: url(&quot;images/avatars/5s.png&quot;);"></a>
            </div>
           
            <div class="card header-card shape-rounded" data-card-height="150" style="height: 150px;">
                <div class="card-overlay bg-highlight opacity-95"></div>
                <div class="card-overlay dark-mode-tint"></div>
                <div class="card-bg preload-img entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);"></div>
                </div>
            @break
    
        @case('entero')
        <div class="page-title page-title-small">
            <h2><a href="#" data-back-button=""><i class="fa fa-arrow-left"></i></a>{{$titulo}}</h2>
            <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded" data-src="images/avatars/5s.png" data-ll-status="loaded" style="background-image: url(&quot;images/avatars/5s.png&quot;);"></a>
            </div>
            <div class="card header-card " data-card-height="85" style="height: 85px;">
                <div class="card-overlay bg-highlight opacity-95"></div>
                <div class="card-overlay dark-mode-tint"></div>
                <div class="card-bg preload-img entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);"></div>
                </div>
            @break
    
        @default
            Default case...
    @endswitch