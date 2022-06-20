@extends('client.master')
@section('content')

@auth
<x-cabecera-pagina titulo="Mi Perfil" cabecera="bordeado" />

<div class="card card-style preload-img entered loaded" data-src="{{asset('images/imagen4.jpg')}}" data-card-height="450"
    style="height: 450px; background-image: url(&quot;{{asset('images/imagen4.jpg')}}&quot;);" data-ll-status="loaded">
    <div class="card-bottom ms-3 ">
        <h1 class="font-40 line-height-xl ">{{auth()->user()->name}}</h1>
        <p class="pb-0 mb-0 font-12 "><i class="fa fa-map-marker me-2"></i>Tarija, Bolivia</p>
        <p class="">
            Encuentra toda la informacion sobre tu cuenta en esta pestaña.
        </p>
    </div>
    <div class="card-overlay bg-gradient-fade"></div>
</div>
<div class="card card-style">
    <div class="content mb-0">
        <div class="row mb-0 text-center">
            <div class="col-4">
                <h1 class="mb-n1">{{$usuario->saldo}}</h1>
                <p class="font-10 mb-0 pb-0">Saldo</p>
            </div>
            <div class="col-4">
                <h1 class="mb-n1">{{$usuario->puntos}}</h1>
                <p class="font-10 mb-0 pb-0">Puntos</p>
            </div>
            <div class="col-4">
                <h1 class="mb-n1">{{$usuario->historial_ventas->count()}}</h1>
                <p class="font-10 mb-0 pb-0">Compras realizadas</p>
            </div>
        </div>
        <div class="divider mb-4 mt-3"></div>

    </div>
</div>

<div class="card card-style">
    <div class="content mb-0">
        <h1>Planes suscritos</h1>
        <p class="font-10 color-highlight mt-n2 mb-0">Tienes actualmente {{$usuario->planes->count()}} plan(es) activos
        </p>
        <div class="list-group list-custom-large mb-4">
            @isset($planes)
            @foreach ($planes as $item)
            @if($item['editable']==true)
            <a href="{{route('calendario.cliente',[$item['id'],$usuario->id])}}">
                @else
                <a href="#">
                    @endif

                    <i class="fa fa-ticket-alt color-green-dark"></i>
                    <span>{{$item['plan']}}</span>
                    <strong>Cantidad restante - {{$item['cantidad']}}</strong>

                    @if($item['editable']==true)
                    <span class="badge bg-blue-dark">Editar semana</span>
                    @else
                    <span class="badge bg-red-dark">No personalizable</span>
                    @endif
                    <i class="fa fa-angle-right"></i>
                </a>
                @endforeach
                @endisset

        </div>


    </div>
</div>
<div class="card card-style p-3">
    <div class="row text-center row-cols-3 mb-n4">

        <a class="col mb-4" data-gallery="gallery-1" href="{{asset('images/imagen1.jpg')}}" title="Pots and Pans">
            <img data-src="{{asset('images/imagen1.jpg')}}" class="img-fluid rounded-xs preload-img entered loaded" alt="img"
                data-ll-status="loaded" src="{{asset('images/imagen1.jpg')}}">
        </a>
        <a class="col mb-4" data-gallery="gallery-1" href="{{asset('images/imagen2.jpg')}}"
            title="Berries are Packed with Fiber">
            <img data-src="{{asset('images/imagen2.jpg')}}" class="img-fluid rounded-xs preload-img entered loaded" alt="img"
                data-ll-status="loaded" src="{{asset('images/imagen2.jpg')}}">
        </a>
        <a class="col mb-4" data-gallery="gallery-1" href="{{asset('images/imagen3.jpg')}}" title="A beautiful Retro Camera">
            <img data-src="{{asset('images/imagen3.jpg')}}" class="img-fluid rounded-xs preload-img entered loaded" alt="img"
                data-ll-status="loaded" src="{{asset('images/imagen3.jpg')}}">
        </a>
    </div>
</div>

@endauth

@guest
<x-cabecera-pagina titulo="Inicia Sesion" cabecera="bordeado" />

<div class="card card-style">
    <form action="{{route('login')}}" method="POST">
        @csrf
        <div class="content mt-2 mb-0">
            <div class="input-style no-borders has-icon validate-field mb-4">
                <i class="fa fa-user"></i>
                <input type="email" class="form-control validate-name" id="form1a" name="email"
                    value="{{ old('email') }}" placeholder="Correo">
                <label for="form1a" class="color-blue-dark font-10 mt-1">Correo</label>
                <i class="fa fa-times disabled invalid color-red-dark"></i>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em>(requerido)</em>
            </div>
            <div class="input-style no-borders has-icon validate-field mb-4">
                <i class="fa fa-lock"></i>
                <input type="password" class="form-control validate-password" name="password" id="form3a"
                    placeholder="Contraseña">
                <label for="form3a" class="color-blue-dark font-10 mt-1">Contraseña</label>
                <i class="fa fa-times disabled invalid color-red-dark"></i>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em>(requerido)</em>
            </div>
            <button type="submit"
                class="btn btn-m mt-2 mb-4 btn-full bg-green-dark rounded-sm text-uppercase font-900">Inicia
                Sesion</button>
            @if($errors->any())
            <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">Ingrese los datos correctamente</mark>
            @endif

            <div class="divider"></div>
            {{-- <a href="#" class="btn btn-icon btn-m btn-full shadow-l bg-facebook text-uppercase font-900 text-start"><i
                    class="fab fa-facebook-f text-center"></i>Loguea con Facebook</a>
            <a href="#"
                class="btn btn-icon btn-m mt-2 btn-full shadow-l bg-google text-uppercase font-900 text-start"><i
                    class="fab fa-google text-center"></i>Loguea con Google</a> --}}
            <div class="divider mt-4 mb-3"></div>
            <div class="d-flex">
                <div class="w-50 font-11 pb-2 color-theme opacity-60 pb-3 text-start"><a href="{{route('register')}}"
                        class="color-theme">Crear una cuenta</a></div>
            </div>
        </div>
    </form>
</div>

@endguest


@endsection
