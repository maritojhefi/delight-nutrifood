@if (!empty($datos))
    @push('modals')
        <div class="scroll-toast d-flex flex-row gap-2 shadow-xxl rounded-sm w-100 py-2 ps-2 pe-2 bg-theme bg-dtheme-blue"
            style="max-width: 95%">
            <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" 
                class="" style="width: 4.2rem;"  alt="logo_{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}">
            <div class="d-flex flex-column w-100 gap-1">
                <h2 class="text-uppercase font-700 font-16 color-theme mb-0">¡Tu perfil está incompleto!</h2>
                <div class="d-flex flex-row align-items-center justify-content-between gap-1 w-100">
                    <p class="font-12 mb-0 color-theme line-height-s">Complétalo para disfrutar de todos los servicios</p>
                    <a href="{{ route('llenarDatosPerfil') }}" class="rounded-s px-2 py-1 bg-highlight text-uppercase font-500 color-white shadow-m">Llenar</a>
                </div>  
            </div>
        </div>
    @endpush
@endif

@push('scripts')
<script>
$(document).ready(function() {
    var toastCompletar = $(".scroll-toast");

    function checkScroll() {
        var scrollTop = $(window).scrollTop();
        var docHeight = $(document).height() - $(window).height();
        var scrollPercent = (scrollTop / docHeight) * 100;

        if (scrollPercent >= 5 && scrollPercent <= 90) {
            toastCompletar.addClass("visible");
        } else {
            toastCompletar.removeClass("visible");
        }
    }

    $(window).on("scroll", checkScroll);
});
</script>
@endpush