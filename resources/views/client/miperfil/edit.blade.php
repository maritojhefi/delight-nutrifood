@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Completa tu perfil" cabecera="bordeado" />


    @if (!session('success'))
        <div class="card card-style">
            <div class="content">
                <div class="d-flex">
                    <div>
                        <img src="{{ asset('user.png') }}" width="50" class="me-3 bg-highlight rounded-xl">
                    </div>
                    <div>
                        <h1 class="mb-0 pt-1">{{ auth()->user()->name }}</h1>
                        <p class="color-highlight font-11 mt-n2 mb-3">Informacion cifrada, no revelaremos esto con nadie</p>
                    </div>
                </div>
                <p>
                    Completa tu perfil, asi estaras listo para todas las funciones que se vienen pronto!
                </p>
            </div>
        </div>
        <div class="card card-style">
            <form action="{{ route('guardarPerfilFaltante') }}" method="post">
                <div class="content mb-0">
                    <h3 class="font-600">Informacion de tu perfil</h3>
                    <p>
                        Por favor llena con el maximo detalle los siguientes campos.
                    </p>

                    @csrf

                    <div class="input-style has-borders hnoas-icon input-style-always-active validate-field mb-4">
                        <input type="email" class="form-control" placeholder="" name="email"
                            value="{{ old('email', $usuario->email) }}">
                        <label for="form1" class="color-highlight font-400 font-13">Email</label>

                        @error('email')
                            <i class="fa fa-times  invalid color-red-dark"></i>
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-style has-borders hnoas-icon input-style-always-active validate-field mb-4">
                        <input type="text" class="form-control"
                            placeholder="Ejm: Tomatitas Av. Principal #134 Porton guindo" name="direccion"
                            value="{{ old('direccion', $usuario->direccion) }}">
                        <label for="form1" class="color-highlight font-400 font-13">Direccion</label>

                        @error('direccion')
                            <i class="fa fa-times  invalid color-red-dark"></i>
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="input-style has-borders hnoas-icon input-style-always-active validate-field mb-4">
                        <input type="text" class="form-control" placeholder="A detalle" name="telf"
                            value="{{ old('telf', $usuario->telf) }}">
                        <label for="form1" class="color-highlight font-400 font-13">Telefono</label>

                        @error('telf')
                            <i class="fa fa-times  invalid color-red-dark"></i>
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="input-style has-borders hnoas-icon input-style-always-active validate-field mb-4">
                        <input type="date" class="form-control" placeholder="A detalle" name="nacimiento"
                            value="{{ old('nacimiento', $usuario->nacimiento) }}">
                        <label for="form1" class="color-highlight font-400 font-13">Fecha Nacimiento</label>

                        @error('nacimiento')
                            <i class="fa fa-times  invalid color-red-dark"></i>
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" value="{{ $usuario->id }}" name="idUsuario">
                    <input type="hidden" id="latitud" name="latitud">
                    <input type="hidden" id="longitud" name="longitud">
                </div>
                <div class="card card-style mb-0 map-full" data-card-height="cover-card" style="height: 573px;">
                    <h3 class="m-3 text-center"> Ubique su domicilio detalladamente</h3>
                    <div id="map" style="width:100%;height:600px;"></div>
                </div>
                <button type="submit"
                    class="btn btn-full btn-margins bg-highlight mt-3 rounded-sm shadow-xl btn-m text-uppercase font-900 btn-block">Guardar
                    Perfil</button>
            </form>
        </div>
    @else
        <div data-card-height="200" class="card card-style preload-img entered loaded" data-src="{{asset('delight_logo.jpg')}}"
            style="height: 200px; background-image: url({{asset('images/imagen4.jpg')}});" data-ll-status="loaded">
            <div class="card-top pt-4 ms-3 me-3">
                <h2 class="color-white font-600">Completaste tu perfil!</h2>
                <p class="mt-n2 color-white">Ya podemos usar todas las funciones contigo! Pronto las descubriras</p>
            </div>
            <div class="card-bottom ms-3 me-3 mb-4">
                <div class="progress" style="height:26px;">
                    <div class="progress-bar border-0 bg-white color-black text-start ps-2" role="progressbar"
                        style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                        Completado al 100%
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-highlight opacity-80"></div>
        </div>
        <a href="{{route('miperfil')}}" class="btn btn-m btn-full m-3 rounded-xl text-uppercase font-900 shadow-s bg-green-dark">Ir a mi perfil</a>
    @endif

    @push('header')
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChXiGEyXk4hNpeiAR8EyBDWzHVZJxMk2U" async defer></script>
    @endpush
    @push('scripts')
        <script>
            window.onload = function() {
                navigator.geolocation.getCurrentPosition(function(location) {
                    console.log(location.coords.latitude);
                    console.log(location.coords.longitude);

                    var map;
                    var center = {

                        lat: location.coords.latitude,
                        lng: location.coords.longitude
                    };

                    function initMap() {
                        map = new google.maps.Map(document.getElementById('map'), {
                            center: center,
                            zoom: 17
                        });

                        var marker = new google.maps.Marker({
                            position: {
                                lat: location.coords.latitude,
                                lng: location.coords.longitude

                            },
                            draggable: true,
                            map: map,
                            title: 'Ubication'

                        });



                        $('#latitud').val(location.coords.latitude);
                        $('#longitud').val(location.coords.longitude);
                        marker.addListener('dragend', function(event) {
                            //escribimos las coordenadas de la posicion actual del marcador dentro del input #coords

                            $('#latitud').val(this.getPosition().lat());
                            $('#longitud').val(this.getPosition().lng());
                        });

                    }
                    initMap();
                });
            };
        </script>
    @endpush
@endsection
