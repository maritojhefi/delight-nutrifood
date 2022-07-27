{{-- <div id="footer-bar" class="footer-bar-5">
        <a href="{{route('miperfil')}}" class="{{(request () -> is ('miperfil'.'*'))? 'active-nav': ''}} ">
            <i data-feather="heart" data-feather-line="1" data-feather-size="21" data-feather-color="red-dark" data-feather-bg="red-fade-light">
            </i><span>Mi Perfil</span>
        </a>
        <a href="{{route('promociones')}}" class="{{(request () -> is ('promociones'.'*'))? 'active-nav': ''}} ">
            <i data-feather="plus" data-feather-line="1" data-feather-size="21" data-feather-color="green-dark" data-feather-bg="green-fade-light"></i>
            <span>Promociones</span>
        </a>
        <a href="{{route('menusemanal')}}" class="{{(request () -> is ('inicio'.'*'))? 'active-nav': ''}} ">
            <i data-feather="home" data-feather-line="1" data-feather-size="21" data-feather-color="blue-dark" data-feather-bg="blue-fade-light"></i>
            <span>Inicio</span>
        </a>
        <a href="{{route('productos')}}" class="{{(request () -> is ('productos'.'*'))? 'active-nav': ''}} ">
            <i data-feather="file" data-feather-line="1" data-feather-size="21" data-feather-color="brown-dark" data-feather-bg="brown-fade-light"></i>
            <span>Productos</span>
        </a>
      <!--  <a href="{{route('ajustes')}}" class="{{(request () -> is ('ajustes'.'*'))? 'active-nav': ''}} ">
            <i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="dark-dark" data-feather-bg="gray-fade-light"></i>
            <span>Ajustes</span>
        </a> -->
    </div> --}}

<div id="footer-bar" class="footer-bar-2 bg-dark-dark mb-1 ms-3 me-3 rounded-s">
    {{-- <a href="index.html"><i class="fa fa-home color-white"></i><span class="color-white">Home</span></a>
        <a href="index-components.html"><i class="fa fa-star color-white"></i><span class="color-white">Features</span></a>
        <a href="index-pages.html" class="active-nav"><i class="fa fa-heart color-white"></i><span class="color-white">Pages</span></a>
        <a href="index-search.html"><i class="fa fa-search color-white"></i><span class="color-white">Search</span></a>
        <a href="#" data-menu="menu-settings"><i class="fa fa-cog color-white"></i><span class="color-white">Settings</span></a> --}}
        <div id="toast-2" class="toast toast-tiny toast-top bg-blue-dark hide" data-bs-delay="3000" data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Cargando...</div>
    @push('scripts')
        <script>
            
            window.onload = function() {

                var asd = document.getElementById('toast-2');
                fd = new bootstrap.Toast(asd);
                $(".cargando").click(function() {
                   // $(this).addClass('disabled');
                
                fd.show();
            });
}
        </script>
    @endpush
    <a id="uno" href="{{ route('miperfil') }}"
        class="{{ request()->is('miperfil' . '*') ? 'active-nav' : '' }} cargando">
        <i class="fa fa-heart color-white">
        </i><span class="color-white">Mi Perfil</span>
    </a>
    <a href="{{ route('novedades') }}" class="{{ request()->is('novedades' . '*') ? 'active-nav' : '' }} cargando">
        <i class="fa fa-plus fas color-white"></i>
        <span class="color-white">Novedades</span>
    </a>
    <a href="{{ route('menusemanal') }}" class="{{ request()->is('inicio' . '*') ? 'active-nav' : '' }} cargando">
        <i class="fa fa-home color-white"></i>
        <span class="color-white">Inicio</span>
    </a>
    <a href="{{ route('productos') }}" class="{{ request()->is('productos' . '*') ? 'active-nav' : '' }} cargando">
        <i class="fa fa-gem font-16 color-white"></i>
        <span class="color-white">Eco-Tienda</span>
    </a>
    <a href="{{ route('carrito') }}" class="{{ request()->is('carrito' . '*') ? 'active-nav' : '' }} cargando">
        <i class="fa fa-shopping-cart font-16 color-white"></i>
        <span class="color-white">Mi carrito</span>
    </a>
    <!--  <a href="{{ route('ajustes') }}" class="{{ request()->is('ajustes' . '*') ? 'active-nav' : '' }} ">
            <i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="dark-dark" data-feather-bg="gray-fade-light"></i>
            <span>Ajustes</span>
        </a> -->
</div>
