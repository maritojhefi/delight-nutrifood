<div class="menu-header">
    {{-- <a href="#" data-toggle-theme="" class="border-right-0"><i class="fa font-12 color-yellow-dark fa-lightbulb"></i></a> --}}
    <a href="#" class=" pwa-install border-right-0"><i class="fa font-12 color-blue-dark fa-brush"></i></a>
    <a href="#" onclick="reinstalarPWA()" class="border-right-0"><i class="fa font-15 color-green-dark fa-smile"></i></a>
    <a href="#" class="close-menu border-right-0"><i class="fa font-15 color-red-dark fa-times"></i></a>
    
</div>
<div class="menu-logo text-center">
    @guest
        <a href="#"><img class="rounded-circle bg-highlight" width="80" src="{{ asset('user.png') }}"></a>

    @endguest
    @auth
        @if (auth()->user()->foto)
            <a href="#"><img class="rounded-circle bg-highlight" width="80"
                    src="{{ asset('imagenes/perfil/' . auth()->user()->foto) }}"></a>
        @else
            <a href="#"><img class="rounded-circle bg-highlight" width="80" src="{{ asset('user.png') }}"></a>
        @endif
        <h1 class="pt-3 font-800 font-28 text-uppercase">{{ Str::words(auth()->user()->name, 1, '') }}</h1>
    @endauth
    @guest
        <h1 class="pt-3 font-800 font-28 text-uppercase">DELIGHT</h1>
    @endguest
    <p class="font-11 mt-n2">Nutriendo <span class="color-highlight">tus</span> habitos!</p>
</div>
<div class="menu-items mb-4">
    <h5 class="text-uppercase opacity-20 font-12 pl-3">Menu</h5>
    <a id="nav-welcome" href="{{ route('menusemanal') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-home" data-feather-line="1" data-feather-size="16" data-feather-color="blue-dark"
            data-feather-bg="blue-fade-light" style="stroke-width: 1; width: 16px; height: 16px;">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        <span>Inicio</span>

        <i class="fa fa-circle"></i>
    </a>

    <a id="nav-features" href="{{ route('miperfil') }}"
        class="{{ request()->is('miperfil' . '*') ? 'nav-item-active' : '' }} ">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-heart" data-feather-line="1" data-feather-size="16" data-feather-color="red-dark"
            data-feather-bg="red-fade-light" style="stroke-width: 1; width: 16px; height: 16px;">
            <path
                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
            </path>
        </svg>
        <span>Mi perfil</span>
        <i class="fa fa-circle"></i>
    </a>
    <a id="nav-pages" href="{{ route('productos') }}"
        class="{{ request()->is('productos' . '*') ? 'nav-item-active' : '' }} ">
        <i class="fa fa-gem font-16 color-blue-dark"></i>
        <span>Eco-Tienda</span>
        <i class="fa fa-circle"></i>
    </a>
    <a id="nav-media" href="{{ route('promociones') }}"
        class="{{ request()->is('promociones' . '*') ? 'nav-item-active' : '' }} ">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-image" data-feather-line="1" data-feather-size="16"
            data-feather-color="green-dark" data-feather-bg="green-fade-light"
            style="stroke-width: 1; width: 16px; height: 16px;">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2">
            </rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
        </svg>
        <span>Promociones</span>
        <i class="fa fa-circle"></i>
    </a>




    <!-- <a id="nav-settings" href="{{ route('ajustes') }}"  class="{{ request()->is('ajustes' . '*') ? 'nav-item-active' : '' }} ">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings" data-feather-line="1" data-feather-size="16" data-feather-color="gray-light" data-feather-bg="gray-fade-light" style="stroke-width: 1; width: 16px; height: 16px;"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
    <span>Ajustes</span>
    <i class="fa fa-circle"></i>
    </a> -->
    @guest
        <a id="nav-shapes" href="{{ route('login') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-hexagon" data-feather-line="1" data-feather-size="16"
                data-feather-color="magenta-dark" data-feather-bg="magenta-fade-light"
                style="stroke-width: 1; width: 16px; height: 16px;">
                <path
                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                </path>
            </svg>
            <span>Iniciar Sesion</span>
            <i class="fa fa-circle"></i>
        </a>
    @endguest
    @auth
        @if (auth()->user()->role_id == 1)
            <a id="nav-media" href="{{ route('ventas.listar') }}" class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-hexagon" data-feather-line="1" data-feather-size="16"
                    data-feather-color="magenta-dark" data-feather-bg="magenta-fade-light"
                    style="stroke-width: 1; width: 16px; height: 16px;">
                    <path
                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                    </path>
                </svg>
                <span>Ir al administrador</span>
                <i class="fa fa-circle"></i>
            </a>
        @endif
        <a href="{{ route('logout') }}" class=""
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Cerrar Sesion</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </a>


    @endauth
    <!-- <a href="#" class="close-menu">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x" data-feather-line="3" data-feather-size="16" data-feather-color="red-dark" data-feather-bg="red-fade-dark" style="stroke-width: 3; width: 16px; height: 16px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    <span>Cerrar  Menu</span>
    <i class="fa fa-circle"></i>
    </a> -->
</div>
<div class="text-center">
    <a href="https://www.facebook.com/DelightNutriFoodEcoTienda" class="icon icon-xs mr-1 rounded-s bg-facebook"><i
            class="fab fa-facebook"></i></a>
    <!--  <a href="#" class="icon icon-xs mr-1 rounded-s bg-twitter"><i class="fab fa-twitter"></i></a> -->
    <!--  <a href="#" class="icon icon-xs mr-1 rounded-s bg-instagram"><i class="fab fa-instagram"></i></a> -->
    <!-- <a href="#" class="icon icon-xs mr-1 rounded-s bg-linkedin"><i class="fab fa-linkedin-in"></i></a> -->
    <a href="https://wa.link/ewfjau" class="icon icon-xs rounded-s bg-whatsapp"><i class="fab fa-whatsapp"></i></a>
    <a href="https://www.instagram.com/delight_nutrifood_ecotienda/" class="icon icon-xs rounded-s bg-instagram"><i
            class="fab fa-instagram"></i></a>
    <p class="mb-0 pt-3 font-10 opacity-30">Delight-Nutrifood <span class="copyright-year"></span> by Macrobyte</p>

</div>

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
    </script>
@endpush
