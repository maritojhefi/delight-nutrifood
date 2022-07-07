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
        <div id="todoBien">
            <div class="card card-style mb-2 map-full" data-card-height="cover-card" style="height: 573px;">
                <h3 class="m-3 text-center"> Ubique su domicilio detalladamente</h3>
                <div id="map" style="width:100%;height:600px;"></div>
            </div>
            <div class="card card-style">
                <form action="{{ route('guardarPerfilFaltante') }}" method="post">

                    <div class="content mb-0">
                        <h3 class="font-600">Informacion de tu perfil</h3>
                        <p>
                            Por favor llena con el maximo detalle los siguientes campos.
                        </p>

                        @csrf

                        <br>
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
                            <input type="number" class="form-control" placeholder="" name="telf"
                                value="{{ old('telf', $usuario->telf) }}">
                            <label for="form1" class="color-highlight font-400 font-13">Telefono</label>

                            @error('telf')
                                <i class="fa fa-times  invalid color-red-dark"></i>
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="input-style has-borders no-icon mb-4">
                            <input type="date" name="nacimiento" id="form6" value="{{ old('nacimiento', $usuario->nacimiento) }}" max="2010-01-01" min="1950-01-01" class="form-control">
                            
                            @error('nacimiento')
                                <i class="fa fa-times  invalid color-red-dark"></i>
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            </div>
                        

                        <input type="hidden" value="{{ $usuario->id }}" name="idUsuario">
                        <input type="hidden" id="latitud" name="latitud">
                        <input type="hidden" id="longitud" name="longitud">
                    </div>

                    <button type="submit"
                        class="btn btn-full btn-margins bg-highlight mt-3 rounded-sm shadow-xl btn-m text-uppercase font-900 btn-block">Guardar
                        Perfil</button>
                </form>
            </div>
        </div>

        <div id="errorMapa" class="displayNone">
           


            <div class="card card-style  round-medium " style="height: 380px;">
                <img src="{{ asset('errorMapa.png') }}" class="card-image " style="height: 430px;">
                <div class="card-bottom ms-3 mb-2">
                    <h2 class="font-700 color-white">Concede el permiso</h2>
                    <p class="color-white mt-n2 mb-0">Haz click en el icono del candado en la parte de la url de tu navegador, permite el acceso y
                        recarga la pagina!</p>
                </div>
                <div class="card-overlay bg-black opacity-30"></div>
            </div>
            <div class="alert me-3 ms-3 rounded-s bg-red-dark " role="alert">
                <span class="alert-icon"><i class="fa fa-times-circle font-18"></i></span>
                <h4 class="text-uppercase color-white">Active el GPS</h4>
                <strong class="alert-icon-text">Necesitamos permiso de su ubicacion.</strong>

                <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
                    aria-label="Close">×</button>
            </div>
        </div>
        <div id="toast-3" class="toast toast-tiny toast-top bg-blue-dark fade hide" data-bs-delay="1500"
            data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Actualizado!</div>
        <div id="toast-4" class="toast toast-tiny toast-top bg-red-dark fade hide" data-bs-delay="4000"
            data-bs-autohide="true"><i class="fa fa-times me-3"></i>Active su GPS</div>
    @else
        <div data-card-height="200" class="card card-style preload-img entered loaded"
            data-src="{{ asset('delight_logo.jpg') }}"
            style="height: 200px; background-image: url({{ asset('images/imagen4.jpg') }});" data-ll-status="loaded">
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
        <a href="{{ route('miperfil') }}"
            class="btn btn-m btn-full m-3 rounded-xl text-uppercase font-900 shadow-s bg-green-dark">Ir a mi perfil</a>
    @endif

    @push('header')
        <style>
            .displayNone {
                display: none
            }

            .dislayFull {
                display: contents
            }
        </style>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChXiGEyXk4hNpeiAR8EyBDWzHVZJxMk2U" async defer></script>
    @endpush
    @push('scripts')
        <script>
            window.onload = function() {

                var toastActualizado = document.getElementById('toast-3');
                toastGps = new bootstrap.Toast(toastActualizado);
                var toastErrorGps = document.getElementById('toast-4');
                toastError = new bootstrap.Toast(toastErrorGps);
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
                                toastGps.show();
                                $('#latitud').val(this.getPosition().lat());
                                $('#longitud').val(this.getPosition().lng());
                            });

                        }
                        initMap();
                    },
                    errores);

                function errores(err) {
                    if (err.code == err.TIMEOUT)
                        alert("Se ha superado el tiempo de espera");
                    if (err.code == err.PERMISSION_DENIED)
                    toastError.show();
                        $("#errorMapa").removeClass("displayNone")
                    $("#todoBien").addClass("displayNone")
                    if (err.code == err.POSITION_UNAVAILABLE)
                        alert("El dispositivo no pudo recuperar la posición actual");
                }
            };
        </script>
    @endpush
@endsection
