@extends('client.master')
@section('content')
    @auth
        <x-cabecera-pagina titulo="Hola!" cabecera="bordeado" />
        <div class="card card-style preload-img entered loaded" data-src="{{ asset('images/imagen4.jpg') }}" data-card-height="450"
            style="height: 450px; background-image: url(&quot;{{ asset('imagenes/delight/21.jpeg') }}&quot;);"
            data-ll-status="loaded">
            <div class="card-bottom ms-3 ">
                <h1 class="font-40 line-height-xl ">{{ auth()->user()->name }}</h1>
                <a href="{{ route('llenarDatosPerfil') }}"
                    class="btn btn-xxs mb-3 rounded-s text-uppercase font-900 shadow-s border-red-dark  bg-red-light">Editar
                    Perfil </a>
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

                    <div class="col-3">
                        <h1 class="mb-n1">{{ $usuario->puntos }}</h1>
                        <p class="font-10 mb-0 pb-0">Puntos</p>

                    </div>
                    <div class="col-6">
                        <a href="{{ route('usuario.saldo') }}">
                            <h1 class="mb-n1">{{ $usuario->saldo }} Bs @if ($usuario->saldo > 0)
                                    <i class="fa fa-arrow-right rotate-135 color-red-dark"></i>
                                @else
                                    <i class="fa fa-arrow-right rotate-45 color-green-dark"></i>
                                @endif
                            </h1>
                            <p class="font-10 mb-0 pb-0">Saldo</p>

                            <em class="badge bg-highlight color-white">DETALLES</em>
                        </a>
                    </div>
                    <div class="col-3">
                        <h1 class="mb-n1">{{ $usuario->historial_ventas->count() }}</h1>
                        <p class="font-10 mb-0 pb-0">Compras realizadas</p>
                    </div>
                </div>
                <div class="divider mb-4 mt-3"></div>

            </div>
        </div>



        <div class="row text-center mb-0">
            <a href="{{ route('misplanes') }}" class="col-6 pe-2">
                <div class="card card-style me-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-crown color-brown-dark font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Mis Planes</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        Planes suscritos
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>
            <a href="{{ route('usuario.saldo') }}" class="col-6 ps-2">
                <div class="card card-style ms-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-wallet color-gray-dark font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Mi Saldo</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        Balance de saldos
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>
            <a href="{{ route('carrito') }}" class="col-6 pe-2">
                <div class="card card-style me-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-shopping-cart color-teal-light font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Mi carrito</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        Tus favoritos!
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>
            <a href="{{ route('tutoriales') }}" class="col-6 ps-2">
                <div class="card card-style ms-0 mb-3">
                    <h1 class="center-text pt-3 mt-3 mb-3">
                        <i class="fa fa-video color-blue-light font-50"></i>
                    </h1>
                    <h4 class="color-theme font-600">Videos</h4>
                    <p class="mt-n2 font-11 color-highlight mb-3">
                        Conoce mas nuestra comunidad!
                    </p>
                    <p class="font-10 opacity-30 mb-1">Click para ver</p>
                </div>
            </a>

            <a href="#" id="revisarWhatsapp" class="col-12">
                <div class="card card-style mb-3">
                    <div class="d-flex py-3 my-1">
                        <div class="align-self-center px-3">
                            <i class="fa fa-handshake color-mint-dark font-35 ps-2 pe-1"></i>
                        </div>
                        <div class="align-self-center">
                            <h4 class="text-start color-theme font-600 font-17">Asistente virtual</h4>
                            <p class="text-start mt-n2 font-11 color-highlight mb-0">
                                Activar/desactivar los mensajes automaticos
                            </p>
                        </div>
                        <div class="align-self-center ms-auto pe-4">
                            <i class="fa fa-arrow-right opacity-30"></i>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <div id="success" data-menu="menu-success-2"></div>
        <div id="warning" data-menu="menu-warning-2"></div>
        @push('modals')
            <div id="menu-success-2" class="menu menu-box-modal bg-green-dark rounded-m" data-menu-height="350"
                data-menu-width="310">
                <h1 class="text-center mt-3 pt-1"><i class="fa fa-3x fa-check-circle color-white shadow-xl rounded-circle"></i></h1>
                <h1 class="text-center mt-3 font-700 color-white">Activado!</h1>
                <p class="boxed-text-l color-white opacity-70">
                    Tienes activado los mensajes automaticos para programar tu plan.<br> En caso de que te encuentres suscrito en
                    alguno...
                </p>
                <a href="#"
                    class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-white color-green-dark cambiarEstado">Desactivar</a>
            </div>

            <div id="menu-warning-2" class="menu menu-box-modal bg-red-dark rounded-m" data-menu-height="350" data-menu-width="310">
                <h1 class="text-center mt-3 pt-1"><i class="fa fa-3x fa-times-circle color-white shadow-xl rounded-circle"></i></h1>
                <h1 class="text-center mt-3 color-white font-700">Desactivado!</h1>
                <p class="boxed-text-l color-white opacity-70">
                    No estas habilitad@ para recibir mensajes.<br> Presiona el boton debajo para activar los mensajes automaticos
                    para programar tu plan!.
                </p>
                <a href="#"
                    class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-white color-red-dark cambiarEstado">Activar</a>
            </div>
            <div id="whatsapp-false" class="snackbar-toast bg-red-dark color-white fade hide" data-delay="3000"
                data-autohide="true"><i class="fa fa-times me-3"></i>Desactivaste a tu asistente automatico!</div>
            <div id="whatsapp-true" class="snackbar-toast bg-green-dark color-white fade hide" data-delay="3000"
                data-autohide="true"><i class="fa fa-check me-3"></i>Tu asistente ahora esta activo!</div>
        @endpush
        @push('scripts')
            <script>
                $(document).ready(function() {

                    $("#revisarWhatsapp").click(function() {

                        $.ajax({
                            method: "get",
                            url: "/miperfil/whatsapp/asistente/",
                            success: function(result) {
                                if (result == true) {
                                    click_event = new CustomEvent('click');
                                    btn_element = document.querySelector('#success');
                                    btn_element.dispatchEvent(click_event);
                                } else {
                                    click_event = new CustomEvent('click');
                                    btn_element = document.querySelector('#warning');
                                    btn_element.dispatchEvent(click_event);
                                }
                            }
                        })

                    });
                    $(".cambiarEstado").click(function() {

                        $.ajax({
                            method: "get",
                            url: "/miperfil/whatsapp/cambiar/estado/",
                            success: function(result) {
                                if (result == true) {
                                    var toaster = document.getElementById('whatsapp-true');
                                    cart = new bootstrap.Toast(toaster);
                                    cart.show()
                                } else if (result == false) {
                                    var toaster = document.getElementById('whatsapp-false');
                                    cart = new bootstrap.Toast(toaster);
                                    cart.show()
                                }
                            }
                        })

                    });
                });
            </script>
        @endpush
    @endauth
@endsection
