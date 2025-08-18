<div class="card notch-clear rounded-0 gradient-highlight mb-n5">
    {{-- <div class="card-body pb-3 pt-4">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="color-white font-30 mb-0">{{ $titulo ?? 'Titulo' }}</h1>
            <a href="#" class="color-black btn btn-xs font-600 rounded-s bg-white">
                <i class="fa fa-phone me-2"></i>Button
            </a>
        </div>
    </div> --}}
    <div class="page-title page-title-small" style="opacity: 1;">
        {{-- <h1>{{$titulo}}</h1> --}}
        <h1 class="color-white font-30 mb-0" style="z-index: 10">{{ $titulo ?? 'Titulo' }}</h1>

        <button href="#" class="page-title-icon shadow-xl bg-theme color-theme cambiarColor"><x-theme-icon/></button>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></a>
    </div>
    <div class="highlighted-header-body card-body mx-0 px-0 mt-n3 mb-2">    
            <x-slider-individual />
    </div>    
    <div class="card-overlay dark-mode-tint"></div>
</div>