<div class="header header-fixed header-auto-show header-logo-center">
    <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-chevron-left"></i></a>
    <a href="#" data-back-button class="header-icon header-icon-2">Atras</a>
    <a href="/" class="header-title">Delight Nutrifood</a>
    <a href="#" class="cambiarColor header-icon header-icon-3">
        {{-- @if (isset(auth()->user()->color_page) && auth()->user()->color_page == 'theme-dark')
            <i class="fas fa-sun color"></i>
        @elseif(isset(auth()->user()->color_page) && auth()->user()->color_page == 'theme-light')
            <i class="fas fa-moon color"></i>
        @else
            <i class="fas fa-moon color"></i>
        @endif --}}
        <x-theme-icon/>
    </a>
    <a href="#" data-menu="menu-main" class="header-icon header-icon-4 "><i class="fas fa-bars"></i></a>
</div>