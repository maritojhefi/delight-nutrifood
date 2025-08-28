{{-- CABECERA CON COLOR DE FONDO SEGUN EL HIGHLIGHT UTILIZADO --}}
<div class="card notch-clear rounded-0 bg-highlight">
    <div class="page-title page-title-small" style="opacity: 1;">
        {{-- TITULO DE LA PAGINA --}}
        <h1 class="color-white font-30 mb-0" style="z-index: 10">{{ $titulo ?? 'Titulo' }}</h1>
        {{-- BOTON CAMBIO DE COLOR --}}
        <button class="page-title-icon shadow-xl bg-theme color-theme cambiarColor"><x-theme-icon/></button>
        {{-- BOTON MENU-SIDEBAR --}}
        <button class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></button>
    </div>
    {{-- CONTENIDO EXTRA --}}
    <div class="highlighted-header-body card-body d-flex flex-column gap-3 pt-0 px-0">
        <x-barra-busqueda-productos tipo="eco-tienda"/>
        <x-slider-individual />
    </div>      
    {{-- OSCURECIMIENTO DEL BG-CARD EN TEMA OSCURO --}}
    <div class="card-overlay dark-mode-tint"></div>
</div>