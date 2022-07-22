@extends('admin.master')
@section('content')
    <form action="{{ route('registrar.asistencia') }}" method="post" id="myform">
        @csrf
        <input type="hidden" id="latitud" name="latitud">
        <input type="hidden" id="longitud" name="longitud">

    </form>

    Delight Derechos reservados
@endsection

@push('header')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChXiGEyXk4hNpeiAR8EyBDWzHVZJxMk2U" async defer></script>
@endpush
@push('scripts')
    <script>
        window.onload = function() {


            navigator.geolocation.getCurrentPosition(function(location) {
                    console.log(location.coords.latitude);
                    console.log(location.coords.longitude);
                    console.log(getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude));
                    if (getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude) < 1) {
                        console.log(getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude))
                        var submit = false;

                        setTimeout(function() {
                            submit = true;
                            $('#latitud').val(location.coords.latitude);
                            $('#longitud').val(location.coords.longitude);
                            $("#myform").submit(); // if you want            
                        }, 1000);

                        console.log($("#myform"))
                    } else {
                        console.log(getDistanceFromLatLonInKm(location.coords.latitude, location.coords.longitude))
                        alert('no puedes registrar porque estas muy lejos')
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
        };
    </script>
@endpush
