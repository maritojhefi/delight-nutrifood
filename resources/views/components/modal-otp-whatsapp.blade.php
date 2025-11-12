<div id="menu-verificacion-{{ $funcionalidad }}" data-menu-width="320" class="menu menu-box-modal hider-intocable rounded-m"
    style="height: auto;">
        <div class="card card-style p-0 m-0 pb-3">
        <div class="card-header bg-white border-0 p-0">
            <div class="menu-title d-flex flex-column px-2">
                <!-- <p class="ps-1 color-highlight d-inline-block" style="width: fit-content">{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}</p> -->
                <h1 class="px-0 align-self-center font-20 line-height-m">Ingresar código de verificación</h1>
            </div>
        </div>
        <div class="content d-flex flex-column gap-2 align-items-center mt-0">
            <p class="mb-0">
                Se envió un código de verificación a su WhatsApp.
            </p>
            <div class="text-center mx-n3">
                <form action="" id="form-codigo-verificacion-{{ $funcionalidad }}" class="form-otp-configurable">
                    <input class="otp mx-1 rounded-sm text-center font-20 font-900" type="tel" maxlength="1"
                        value="" placeholder="●">
                    <input class="otp mx-1 rounded-sm text-center font-20 font-900" type="tel" maxlength="1"
                        value="" placeholder="●">
                    <input class="otp mx-1 rounded-sm text-center font-20 font-900" type="tel" maxlength="1"
                        value="" placeholder="●">
                    <input class="otp mx-1 rounded-sm text-center font-20 font-900" type="tel" maxlength="1"
                        value="" placeholder="●">
                    <input class="otp mx-1 rounded-sm text-center font-20 font-900" type="tel" maxlength="1"
                        value="" placeholder="●">
                </form>
            </div>
            <!-- <div class="d-flex flex-row">
                <button id="untouchabler" class="btn btn-m color-theme">UNTOUCH</button>
                <button id="touchabler" class="btn btn-m color-theme">TOUCH</button>
            </div> -->
            <p class="text-center font-11 mb-0">
                ¿No ha recibido un código aún?
                <a href="#" id="reenviar-codigo">
                    Reenviar código
                </a>
            </p>
            <div class="d-flex flex-row justify-content-evenly w-100">
                <a href="#" class="close-menu btn btn-s font-15 shadow-l rounded-s text-uppercase font-900 bg-delight-red color-white" >Cancelar</a>
                <button type="button" id="verificar-codigo-btn-{{ $funcionalidad }}"
                    class="btn btn-s font-15 shadow-l rounded-s validador-ingresar text-uppercase font-900 bg-mint-dark color-white">
                    Verificar
                </button>
            </div>
        </div>
    </div>
</div>

@push('modals')
<div id="codigo-incorrecto" class="menu menu-box-modal rounded-m"
    style="display: block; width: 220px; height: auto; padding: 1%;">
    <h1 class="text-center fa-5x mt-2 pt-3 pb-2"><i class="fa fa-times-circle color-red-dark"></i></h1>
    <h2 class="text-center">Código incorrecto, intenta de nuevo dentro de 30 segundos</h2>
</div>

<div id="codigo-correcto" class="menu menu-box-modal rounded-m"
    style="display: block; width: 220px; height: auto; padding: 1%;">
    <h1 class="text-center fa-5x mt-2 pt-3 pb-2"><i class="fa fa-check-circle color-mint-dark"></i></h1>
    <h2 class="text-center">Teléfono verificado correctamente</h2>
</div>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    configurarInputsOTP();
    // $('.menu-hider').addClass('menu-active');
    // $('#untouchabler').on('click', hacerMenuHiderIntocable);
    // $('#touchabler').on('click', hacerMenuHiderTocable);
});

const configurarInputsOTP = () => {
    const $inputs = $('.form-otp-configurable .otp');

    $inputs.each(function(index) {
        const $input = $(this);

        // Permitir solo números
        $input.on('input', function(e) {
            let valor = $(this).val().replace(/[^0-9]/g, '');
            
            // Limitar a un carácter
            if (valor.length > 1) valor = valor.slice(0, 1);
            $(this).val(valor);

            // Avanzar automáticamente al siguiente input
            if (valor && index < $inputs.length - 1) {
                $inputs.eq(index + 1).focus();
            }
        });

        // Manejar backspace
        $input.on('keydown', function(e) {
            if (e.key === 'Backspace' && !$input.val() && index > 0) {
                $inputs.eq(index - 1).focus();
            }

            // Flechas izquierda/derecha
            if (e.key === 'ArrowLeft' && index > 0) {
                $inputs.eq(index - 1).focus();
            } else if (e.key === 'ArrowRight' && index < $inputs.length - 1) {
                $inputs.eq(index + 1).focus();
            }
        });

        // Manejar pegado
        $input.on('paste', function(e) {
            e.preventDefault();
            const texto = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
            const numeros = texto.replace(/[^0-9]/g, '');

            if (numeros.length > 0) {
                $inputs.eq(index).val(numeros[0]);

                // Distribuir los siguientes números en los demás inputs
                for (let i = 1; i < numeros.length && (index + i) < $inputs.length; i++) {
                    $inputs.eq(index + i).val(numeros[i]);
                }

                // Enfocar el último input rellenado
                const ultimoIndex = Math.min(index + numeros.length - 1, $inputs.length - 1);
                $inputs.eq(ultimoIndex).focus();
            }
        });
    });
}
</script>
@endpush
