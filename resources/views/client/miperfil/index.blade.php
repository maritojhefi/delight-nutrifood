@extends('client.master')
@section('content')
@auth
<x-cabecera-pagina titulo="Mi Perfil" cabecera="bordeado"/>
<div class="card card-style preload-img entered loaded" data-src="images/pictures/0l.jpg" data-card-height="450" style="height: 450px; background-image: url(&quot;images/pictures/0l.jpg&quot;);" data-ll-status="loaded">
<div class="card-bottom ms-3">
<h1 class="font-40 line-height-xl">{{auth()->user()->name}}</h1>
<p class="pb-0 mb-0 font-12"><i class="fa fa-map-marker me-2"></i>Tarija, Bolivia</p>
<p>
Encuentra toda la informacion sobre tu cuenta en esta pestaña.
</p>
</div>
<div class="card-overlay bg-gradient-fade"></div>
</div>
<div class="card card-style">
<div class="content mb-0">
<div class="row mb-0 text-center">
<div class="col-4">
<h1 class="mb-n1">351</h1>
<p class="font-10 mb-0 pb-0">Me gusta</p>
</div>
<div class="col-4">
<h1 class="mb-n1">193</h1>
<p class="font-10 mb-0 pb-0">Puntos</p>
</div>
<div class="col-4">
<h1 class="mb-n1">615</h1>
<p class="font-10 mb-0 pb-0">Productos Comprados</p>
</div>
</div>
<div class="divider mb-4 mt-3"></div>
<div class="row mb-0 pb-4">
<div class="col-6">
<a href="#" class="btn btn-m btn-full rounded-s bg-highlight text-uppercase font-900">Ver Detalle</a>
</div>
<div class="col-6">
<a href="#" class="btn btn-m btn-border btn-full rounded-s border-highlight color-highlight text-uppercase font-900">Mensajear</a>
</div>
</div>
</div>
</div>
<div class="card card-style p-3">
<div class="row text-center row-cols-3 mb-n4">
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/30t.jpg" title="Vynil and Typerwritter">
<img data-src="images/pictures/30s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/30s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/22t.jpg" title="Cream Cookie">
<img data-src="images/pictures/22s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/22s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/23t.jpg" title="Cookies and Flowers">
<img data-src="images/pictures/23s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/23s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/24t.jpg" title="Pots and Pans">
<img data-src="images/pictures/24s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/24s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/25t.jpg" title="Berries are Packed with Fiber">
<img data-src="images/pictures/25s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/25s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/26t.jpg" title="A beautiful Retro Camera">
<img data-src="images/pictures/26s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/26s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/27t.jpg" title="Pots and Pans">
<img data-src="images/pictures/27s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/27s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/28t.jpg" title="Berries are Packed with Fiber">
<img data-src="images/pictures/28s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/28s.jpg">
</a>
<a class="col mb-4" data-gallery="gallery-1" href="images/pictures/31t.jpg" title="A beautiful Retro Camera">
<img data-src="images/pictures/31s.jpg" class="img-fluid rounded-xs preload-img entered loaded" alt="img" data-ll-status="loaded" src="images/pictures/31s.jpg">
</a>
</div>
</div>

@endauth
   
    @guest
    <x-cabecera-pagina titulo="Inicia Sesion" cabecera="bordeado"/>

    <div class="card card-style">
      <form action="{{route('login')}}" method="POST">
        @csrf
        <div class="content mt-2 mb-0">
            <div class="input-style no-borders has-icon validate-field mb-4">
            <i class="fa fa-user"></i>
            <input type="email" class="form-control validate-name" id="form1a" name="email" value="{{ old('email') }}" placeholder="Correo">
            <label for="form1a" class="color-blue-dark font-10 mt-1">Correo</label>
            <i class="fa fa-times disabled invalid color-red-dark"></i>
            <i class="fa fa-check disabled valid color-green-dark"></i>
            <em>(requerido)</em>
            </div>
            <div class="input-style no-borders has-icon validate-field mb-4">
            <i class="fa fa-lock"></i>
            <input type="password" class="form-control validate-password" name="password" id="form3a" placeholder="Contraseña">
            <label for="form3a" class="color-blue-dark font-10 mt-1">Contraseña</label>
            <i class="fa fa-times disabled invalid color-red-dark"></i>
            <i class="fa fa-check disabled valid color-green-dark"></i>
            <em>(requerido)</em>
            </div>
            <button type="submit" class="btn btn-m mt-2 mb-4 btn-full bg-green-dark rounded-sm text-uppercase font-900">Inicia Sesion</button>
            @if($errors->any())
            <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">Ingrese los datos correctamente</mark>
            @endif
           
            <div class="divider"></div>
            <a href="#" class="btn btn-icon btn-m btn-full shadow-l bg-facebook text-uppercase font-900 text-start"><i class="fab fa-facebook-f text-center"></i>Loguea con Facebook</a>
            <a href="#" class="btn btn-icon btn-m mt-2 btn-full shadow-l bg-google text-uppercase font-900 text-start"><i class="fab fa-google text-center"></i>Loguea con Google</a>
            <div class="divider mt-4 mb-3"></div>
            <div class="d-flex">
            <div class="w-50 font-11 pb-2 color-theme opacity-60 pb-3 text-start"><a href="#" class="color-theme">Crear una cuenta</a></div>
            </div>
        </div>
      </form>
    </div> 
   
    @endguest
@endsection