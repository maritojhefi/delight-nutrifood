@switch($cabecera)
@case('bordeado')
<div class="page-title page-title-small">
    <h2><a href="#" data-back-button=""><i class="fa fa-arrow-left"></i></a>{{$titulo}}</h2>
    @guest
    <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded"
    data-src="{{asset('user.png')}}" data-ll-status="loaded"
    style="background-image: url(&quot;{{asset('user.png')}}&quot;);"></a>
    @endguest
    @auth
        @if (auth()->user()->foto)
        <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded"
    data-src="{{asset('user.png')}}" data-ll-status="loaded"
    style="background-image: url(&quot;{{asset('imagenes/perfil/'.auth()->user()->foto)}}&quot;);"></a>
            @else
            <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded"
            data-src="{{asset('user.png')}}" data-ll-status="loaded"
            style="background-image: url(&quot;{{asset('user.png')}}&quot;);"></a>
        @endif
    @endauth
    
</div>

<div class="card header-card shape-rounded" data-card-height="150" style="height: 150px;">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg preload-img entered loaded" data-src="{{asset('user.png')}}" data-ll-status="loaded"
        style="background-image: url(&quot;{{asset(GlobalHelper::getValorAtributoSetting('logo'))}}&quot;);"></div>
</div>
@break

@case('entero')
<div class="page-title page-title-small">
    <h2><a href="#" data-back-button=""><i class="fa fa-arrow-left"></i></a>{{$titulo}}</h2>
    @guest
    <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded"
    data-src="{{asset('user.png')}}" data-ll-status="loaded"
    style="background-image: url(&quot;{{asset('user.png')}}&quot;);"></a>
    @endguest
    @auth
        @if (auth()->user()->foto)
        <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded"
    data-src="{{asset('user.png')}}" data-ll-status="loaded"
    style="background-image: url(&quot;{{asset('imagenes/perfil/'.auth()->user()->foto)}}&quot;);"></a>
            @else
            <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded"
            data-src="{{asset('user.png')}}" data-ll-status="loaded"
            style="background-image: url(&quot;{{asset('user.png')}}&quot;);"></a>
        @endif
    @endauth
</div>
<div class="card header-card " data-card-height="85" style="height: 85px;">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg preload-img entered loaded" data-src="{{asset('user.png')}}" data-ll-status="loaded"
        style="background-image: url(&quot;{{asset('user.png')}}&quot;);"></div>
</div>
@break

@case('bordepequeno')

<div class="page-title page-title-small">
    <h2><a href="#" data-back-button=""><i class="fa fa-arrow-left"></i></a>{{$titulo}}</h2>
    <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img entered loaded"
        data-src="images/avatars/5s.png" data-ll-status="loaded"
        style="background-image: url(&quot;{{asset('imagenes/perfil/'.auth()->user()->foto)}}&quot;)"></a>
</div>
<div class="card header-card shape-rounded" data-card-height="95">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg preload-img entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
        style="background-image: url(&quot;{{asset('imagenes/perfil/'.auth()->user()->foto)}}&quot;);"></div>
</div>
@break

@case('appkit')
<div class="page-title page-title-small" style="opacity: 1;">
    {{-- <h1 class="font-24" style="margin-left: 25px" ><i class="fa fa-chevron-left" data-back-button style="margin-right: 0.8rem"></i>{{$titulo}}</h1> --}}

    <h1>{{$titulo}}</h1>

    @if (request()->is('carrito*') && $tiene_venta_activa )
        <x-modal-carrito-pendiente />
    @endif
    <button href="#" class="page-title-icon shadow-xl bg-theme cambiarColor"><x-theme-icon/></button>
    <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></a>
</div>
@break

@case('appkit-highlight')
<div class="card notch-clear rounded-0 gradient-highlight mb-n5">
    <div class="page-title page-title-small" style="opacity: 1;">
        <h1>{{$titulo}}</h1>
        <button href="#" class="page-title-icon shadow-xl bg-theme cambiarColor"><x-theme-icon/></button>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></a>
    </div>
    {{-- <x-slider-individual /> --}}
</div>
@break


@endswitch
