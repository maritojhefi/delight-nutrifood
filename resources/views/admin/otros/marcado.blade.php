@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Hola!" cabecera="bordeado" />
    
    <div class="card card-style text-center" id="close">
        <form action="{{ route('registrar.asistencia') }}" method="post" id="myform">
            @csrf
            <input type="hidden" id="latitud" name="latitud">
            <input type="hidden" id="longitud" name="longitud">
           
        </form>
        <div class="content py-5">
            
            <img src="{{asset('cargando.gif')}}" alt="">
            <h3 class="fa-5x pt-5 pb-2">Espera!</h3>
            <br>
            <h5 class="text-uppercase pb-3 mt-3">Registrando la hora!</h5>
            <p class="boxed-text-l">
                 Delight by Macrobyte
            </p>
            <div class="row mb-0">
               
            </div>
        </div>
    </div>
    <div class="card card-style text-center d-none" id="tooFar">
        
        <div class="content py-5">
            
            <img src="{{asset('map.gif')}}" alt="">
            <h3 class="fa-5x pt-5 pb-2">Estas lejos!</h3>
            <br>
            <h5 class="text-uppercase pb-3 mt-3">Debes estar mas cerca para registrar!</h5>
            <p class="boxed-text-l" id="textoCustom">
                 Delight by Macrobyte
            </p>
            <div class="row mb-0">
               
            </div>
        </div>
    </div>
@endsection

@push('header')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChXiGEyXk4hNpeiAR8EyBDWzHVZJxMk2U" async defer></script>
@endpush
@push('scripts')
    <script>
        console.log('hola')
        $( document ).ready(function() {

            
            navigator.geolocation.getCurrentPosition(function(location) {
                    console.log(location.coords.latitude);
                    console.log(location.coords.longitude);
                    console.log(getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude));
                    if (getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude) < 0.3) {
                        console.log(getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude))
                        var submit = false;

                        setTimeout(function() {
                            submit = true;
                            $('#latitud').val(location.coords.latitude);
                            $('#longitud').val(location.coords.longitude);
                            $("#myform").submit(); // if you want            
                        }, 2000);

                        console.log($("#myform"))
                    } else {
                        console.log()
                        $('#tooFar').removeClass('d-none');
                        $('#close').addClass('d-none');
                        $('#textoCustom').html('Estas a '+(getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude).toFixed(2)*1000)+' metros de Delight')
                    }

                    function getDistanceFromLatLonInKm(lat2, lon2) {
                        var lat1 = -21.5336906;
                        var lon1 = -64.7356312;
                        var R = 6371; // Radius of the earth in km
                        var dLat = deg2rad(lat2 - lat1); // deg2rad below
                        var dLon = deg2rad(lon2 - lon1);
                        var a =
                            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                            Math.sin(dLon / 2) * Math.sin(dLon / 2);
                        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        var d = R * c; // Distance in km
                        return d;
                    }

                    function deg2rad(deg) {
                        return deg * (Math.PI / 180)
                    }



                },
                errores);

            function errores(err) {
                if (err.code == err.TIMEOUT)
                    alert("Se ha superado el tiempo de espera");
                if (err.code == err.PERMISSION_DENIED)
                    alert('activa tu gps antes de registrar tu entrada')
                $('#botonRegistro').prop('disabled', true)
                if (err.code == err.POSITION_UNAVAILABLE)
                    alert("El dispositivo no pudo recuperar la posición actual");
            }
        });
    </script>
@endpush
