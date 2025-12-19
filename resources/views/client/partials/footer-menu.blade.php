<div id="footer-bar" class="footer-bar-6">
    @push('modals')
    <div id="cargando-footer" class="snackbar-toast bg-orange-dark color-white fade hide" data-delay="3000"
        data-autohide="true"><i class="fa fa-sync fa-spin color-white"></i> Un momento...</div>
    @endpush
    @push('scripts')
        <script>
            window.onload = function() {
                var asd = document.getElementById('cargando-footer');
                fd = new bootstrap.Toast(asd);
                $('a').click(function() {
                if($($(this)).hasClass( "cargando" )==false)
                {
                    if ($(this).attr('href').includes('http') == true || $(this).attr('href').includes('#') != true) {
                    fd.show();
                    }
                }
            });
                $(".cargando").click(function() {
                    $('.cargando').removeClass('active-nav')
                    $(this).addClass('active-nav rounded-m')
                    var titulo = $(this).children('span').html()
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Actualizar contenido del carrito de existir al momento de cargarse
                carritoStorage.actualizarContadorCarrito();
                refrescarContadoresFooter();
                
                // Optional: Escuchar evento para actualizar el carrito en otro lugar
                // document.addEventListener('cartUpdated', actualizarContadorCarrito);
                
            });

            $(document).ready(function() {
                
                
                actualizarVisibilidadCounters('#mipedido-counter'); 
                actualizarVisibilidadCounters('#cart-counter'); 

               
            });

        </script>
    @endpush
    <a id="uno" href="{{ route('miperfil') }}"
        class="{{ request()->is('miperfil' . '*') ? 'active-nav rounded-m ' : '' }} cargando d-flex flex-column gap-1">
        <i class="fa fa-heart {{request()->is('miperfil' . '*') ? 'color-highlight' : ''}}">
        </i><span class="{{request()->is('miperfil' . '*') ? 'color-highlight' : ''}}">Mi Perfil</span>
        <em></em>
    </a>
    <a href="{{ route('linea.delight') }}"
        class="{{ request()->is('lineadelight' . '*') ? 'active-nav rounded-m' : '' }} cargando d-flex flex-column gap-1">
        <i class="fa fa-leaf {{request()->is('lineadelight' . '*') ? 'color-highlight' : ''}}"></i>
        <span class="{{request()->is('lineadelight' . '*') ? 'color-highlight' : ''}}">Linea {{GlobalHelper::getValorAtributoSetting('nombre_sistema')}}</span>
        <em></em>
    </a>
    <a href="{{ route('menusemanal') }}"
        class="circle-nav {{ request()->is('inicio' . '*') ? 'active-nav rounded-m' : '' }} cargando d-flex flex-column gap-1">
        <i class="fa fa-home {{request()->is('inicio' . '*') ? 'color-highlight' : ''}}"></i>
        <span class="{{request()->is('inicio' . '*') ? 'color-highlight' : ''}}">Inicio</span>
        <em></em>
        <strong><u></u></strong>
    </a>
    <a href="{{ route('productos') }}"
        class="{{ request()->is('productos' . '*') ? 'active-nav rounded-m' : '' }} cargando d-flex flex-column gap-1">
        <i class="fa fa-gem {{request()->is('productos' . '*') ? 'color-highlight' : ''}}"></i>
        <span class="{{request()->is('productos' . '*') ? 'color-highlight' : ''}}">Eco-Tienda</span>
        <em></em>
    </a>
    <a href="{{ route('carrito') }}"
        class="{{ request()->is('carrito' . '*') ? 'active-nav rounded-m' : '' }} cargando d-flex flex-column gap-1">
        @if ($tiene_venta_activa)
            <div class="d-flex align-items-center justify-content-center gap-1 mb-n2">
                <i><i data-lucide="hand-platter" class="lucide-icon color {{request()->is('carrito' . '*') ? 'color-highlight' : ''}}"></i></i>
                <span id="mipedido-counter" class="pedido-counter-badge">{{ $cantidad_total_pedido }}</span>
            </div>
        @else
            <div class="d-flex align-items-center justify-content-center gap-1">
                <!-- <i><i data-lucide="shopping-cart" class="lucide-icon color {{request()->is('carrito' . '*') ? 'color-highlight' : ''}}"></i></i> -->
                <i class="fa fa-shopping-cart {{request()->is('carrito' . '*') ? 'color-highlight' : ''}}"></i>
                <span id="cart-counter" class="cart-counter-badge"></span>
            </div>
        @endif
        <span class="{{request()->is('carrito' . '*') ? 'color-highlight' : ''}}">{{ $tiene_venta_activa ? "Mi Pedido" : "Mi Carrito" }}</span>
        <em></em>
    </a>
</div>
