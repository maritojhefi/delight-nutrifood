{{-- CARTA ENCABEZADO --}}
<div class="card rounded-0 bg-6" data-card-height="150" style="height: 150px;background-image:url({{ asset('imagenes/delight/default-bg-1.png') }});">
    <div class="card-top">
        <a href="#" class="close-menu float-end me-2 text-center mt-3 icon-40 notch-clear"><i class="fa fa-times color-white"></i></a>
    </div>
    <div class="card-bottom">
        <h1 class="color-white ps-3 mb-n1 font-28">Delight</h1>
        <p class="mb-2 ps-3 font-12 color-white opacity-50">Nutriendo Hábitos!</p>
    </div>
    <div class="card-overlay bg-gradient"></div>
</div>

{{-- DIVISOR MANZANITA --}}
<div class="divider-icon divider-margins bg-highlight mb-4"><i class="fa font-17 color-highlight bg-transparent fa-apple-alt"></i></div>

{{-- DIVISOR MENU --}}
<h6 class="menu-divider">Menu</h6>

{{-- CONTENIDO MENU --}}
<div class="list-group list-custom-small list-menu">
    <a id="nav-welcome" href={{route('menusemanal')}}>
        <i class="fa fa-home gradient-highlight color-white"></i>
        <span>Inicio</span>
        <i class="fa fa-angle-right"></i>
    </a>
    <a id="nav-homepages" href={{route('miperfil')}}>
        <i class="fa fa-heart gradient-red color-white"></i>
        <span>Mi Perfil</span>
        <i class="fa fa-angle-right"></i>
    </a>
    <a id="nav-pages" href={{route('linea.delight')}}>
        <i class="fa fa-leaf gradient-green color-white"></i>
        <span>Linea Delight</span>
        <i class="fa fa-angle-right"></i>
    </a>
    <a id="nav-components" href={{route('productos') }}>
        <i class="fa fa-gem gradient-blue color-white"></i>
        <span>Eco-Tienda</span>
        <i class="fa fa-angle-right"></i>
    </a>
    <a id="nav-carrito" href={{route('carrito')}}>
        <i class="fa fa-shopping-cart gradient-orange color-white"></i>
        <span>Mi Carrito</span>
        <i class="fa fa-angle-right"></i>
    </a>
    @guest
    {{-- LOGIN - INVITADOS --}}
    <a id="nav-login" href="{{ route('login') }}">
        <i class="fa fa-sign-in-alt gradient-magenta color-white"></i>
        <span>Iniciar Sesion</span>
        <i class="fa fa-angle-right"></i>
    </a>
    @endguest
    @auth
        {{-- ADMINISTRADOR --}}
        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
        <a id="nav-admin" href="{{ route('ventas.listar') }}">
            <i class="fa fa-lock gradient-night color-white"></i>
                <span>Ir al administrador</span>
            <i class="fa fa-angle-right"></i>
        </a>
        <a id="nav-pedidos" href="{{ route('ventas.cocina.pedido') }}">
            <i class="fa fa-file gradient-night color-white"></i>
                <span>Pedidos</span>
            <i class="fa fa-angle-right"></i>
        </a>
        {{-- ADMINISTRADOR MENOR --}}
        @elseif(auth()->user()->role_id == 3)
        <a id="nav-admin" href="{{ route('ventas.listar') }}">
            <i class="fa fa-lock gradient-night color-white"></i>
                <span>Ir al administrador</span>
            <i class="fa fa-angle-right"></i>
        </a>
        @endif
        {{-- CERRAR SESION --}}
        <a id="nav-logout" href="{{ route('logout') }}" 
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out-alt gradient-magenta color-white"></i>
            <span>Cerrar Sesion</span>
            <i class="fa fa-angle-right"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth
</div>

{{-- REDES SOCIALES --}}
<div class="mt-auto">
    <div class="d-flex justify-content-center flex-wrap gap-3 mb-5">
        <a href="{{GlobalHelper::getValorAtributoSetting('url_facebook')}}" class="text-decoration-none">
        <i class="fab fa-facebook color-facebook fa-3x"></i>
        </a>
        <a href="{{GlobalHelper::getValorAtributoSetting('url_whatsapp')}}" class="text-decoration-none">
            <i class="fab fa-whatsapp color-whatsapp fa-3x"></i>
        </a>
        <a href="{{GlobalHelper::getValorAtributoSetting('url_instagram')}}" class="text-decoration-none">
            <i class="fab fa-instagram color-instagram fa-3x"></i>
        </a>
    </div>
</div>

{{-- BY MACROBYTE --}}
<h6 class="menu-divider font-10 mb-2">
    {{GlobalHelper::getValorAtributoSetting('nombre_sistema')}} 
    <i class="fa fa-heart color-red-dark pl-1 pr-1"></i>
    by Macrobyte
</h6>

@push('modals')
    {{-- TOAST AUTENTICACION NECESARIA --}}
    <div id="toast-sesion" class="toast toast-tiny toast-top bg-red-light fade hide" data-bs-delay="3000"
        data-bs-autohide="true">
        <i class="fa fa-date"></i>
        Inicie sesion!
    </div>
@endpush
@push('scripts')
    <script>
        function reinstalarPWA() {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for (let registration of registrations) {
                    registration.unregister()
                }
            });

            location.reload(true);
        }

        $(document).ready(function() {
            $(".cambiarColor").click(function() {

                $.ajax({
                    method: "get",
                    url: "/otros/cambiarcolor",

                    success: function(result) {
                        if (result == 'theme-dark') {
                            $('#margen').removeClass('theme-light');
                            $('#margen').addClass('theme-dark');
                            $('.color').removeClass('fa-moon');
                            $('.color').addClass('fa-sun');
                        } else if (result == 'theme-light') {
                            $('#margen').removeClass('theme-dark');
                            $('#margen').addClass('theme-light');
                            $('.color').removeClass('fa-sun');
                            $('.color').addClass('fa-moon');
                        } else {
                            var toastID = document.getElementById('toast-sesion');
                            toastID = new bootstrap.Toast(toastID);
                            toastID.show();
                            // var toastID = document.getElementById('toast-sesion2');
                            // toastID = new bootstrap.Toast(toastID);
                            // toastID.show();
                        }
                    }
                })

            });
        });
    </script>
@endpush
