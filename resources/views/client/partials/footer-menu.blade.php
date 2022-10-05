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

<div id="footer-bar" class="footer-bar-2 bg-dark-dark mb-1 ms-3 me-3 rounded-m margen">
    {{-- <a href="index.html"><i class="fa fa-home color-white"></i><span class="color-white">Home</span></a>
        <a href="index-components.html"><i class="fa fa-star color-white"></i><span class="color-white">Features</span></a>
        <a href="index-pages.html" class="active-nav"><i class="fa fa-heart color-white"></i><span class="color-white">Pages</span></a>
        <a href="index-search.html"><i class="fa fa-search color-white"></i><span class="color-white">Search</span></a>
        <a href="#" data-menu="menu-settings"><i class="fa fa-cog color-white"></i><span class="color-white">Settings</span></a> --}}
    @push('modals')
    <div id="cargando-footer" class="snackbar-toast bg-orange-dark color-white fade" data-delay="3000"
        data-autohide="true"><i class="fa fa-sync fa-spin color-white"></i> Un momento...</div>
    @endpush
    @push('scripts')
        <script>
            window.onload = function() {
                $('a').click(function() {
                    
                if ($(this).attr('href').includes('http') == true || $(this).attr('href').includes('#') != true) {
                    var asd = document.getElementById('cargando-footer');
                    fd = new bootstrap.Toast(asd);
                    fd.show();
                }
            });
                   
                

                $(".cargando").click(function() {
                    // $(this).addClass('disabled');
                    $('.cargando').removeClass('active-nav')
                    $(this).addClass('active-nav rounded-m')
                    var titulo = $(this).children('span').html()
                    $(this).html('<i class="fa fa-sync fa-spin color-white"></i><span class="color-white">' +
                        titulo + '</span>')

                });
            }
        </script>
    @endpush
    <a id="uno" href="{{ route('miperfil') }}"
        class="{{ request()->is('miperfil' . '*') ? 'active-nav rounded-m' : '' }} cargando">
        <i class="fa fa-heart color-white">
        </i><span class="color-white">Mi Perfil</span>
    </a>
    <a href="{{ route('linea.delight') }}"
        class="{{ request()->is('lineadelight' . '*') ? 'active-nav rounded-m' : '' }} cargando">
        <i class="fa fa-leaf font-16 color-white"></i>
        <span class="color-white">Linea Delight!</span>
    </a>
    <a href="{{ route('menusemanal') }}"
        class="{{ request()->is('inicio' . '*') ? 'active-nav rounded-m' : '' }} cargando">
        <i class="fa fa-home font-16 color-white"></i>
        <span class="color-white">Inicio</span>
    </a>
    <a href="{{ route('productos') }}"
        class="{{ request()->is('productos' . '*') ? 'active-nav rounded-m' : '' }} cargando">
        <i class="fa fa-gem font-16 color-white"></i>
        <span class="color-white">Eco-Tienda</span>
    </a>
    <a href="{{ route('carrito') }}"
        class="{{ request()->is('carrito' . '*') ? 'active-nav rounded-m' : '' }} cargando">
        <i class="fa fa-shopping-cart font-16 color-white"></i>
        <span class="color-white">Mi carrito</span>
    </a>
    <!--  <a href="{{ route('ajustes') }}" class="{{ request()->is('ajustes' . '*') ? 'active-nav' : '' }} ">
            <i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="dark-dark" data-feather-bg="gray-fade-light"></i>
            <span>Ajustes</span>
        </a> -->
</div>
