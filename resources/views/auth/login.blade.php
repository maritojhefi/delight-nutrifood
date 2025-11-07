@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Inicia Sesion" cabecera="appkit" />
    <div class="d-flex justify-content-center">
        <div class="card card-style login-card bg-24" style="height: 550px; width: 380px;">
            <div class="card-center mt-n3">
                <div class="px-4 mx-2">
                    <div class="d-flex flex-column mb-3">
                        <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" class="img mx-auto d-block" style="width:100px" alt="">
                        <h3 class="mt-2 font-26 text-center ">¡Bienvenido a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!</h3>
                    </div>
                    <form action="{{ route('login') }}" method="post" class="d-flex flex-column justify-content-center gap-1">
                        @csrf
                        <label for="form1a">Número de Teléfono</label>
                    

                        <div class="d-flex flex-row gap-2">
                            <x-countrycode-select></x-countrycode-select>
                            <div class="input-with-icon-container mb-0 validate-field d-flex flex-grow-1">
                                <i class="fa fa-phone position-absolute ms-2 align-self-center"></i>

                                <input class="ps-3 text-center form-control rounded-sm" id="form1a" name="telf" type="number" value="{{ old('telf') }}">
                            </div>
                            
                        </div>
                        

                        @error('telf')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        
                        <label for="password-input">Contraseña</label>
                        <!-- <div class="input-style no-borders has-icon validate-field d-flex flex-row align-content-center position-relative"> -->
                        <!-- <div class="input-with-icon-container mb-0 validate-field d-flex flex-grow-1">
                            <i class="fa fa-key position-absolute ms-2 align-self-center"></i>
                            <input class="ps-4 form-control rounded-sm validate-password font-13" id="password-input" type="password" name="password">
                            <i class="fa fa-lock password-toggle position-absolute align-self-center" id="toggleIcon"></i>

                        </div> -->
                        <!-- <div class="input-with-icon-container justify-content-between flex-row mb-0 validate-field d-flex">
                            <i class="fa fa-key position-absolute ms-2 align-self-center"></i>

                            <input class="ps-4 form-control rounded-sm validate-password font-13" id="password-input" type="password" name="password">

                            <i class="fa fa-lock password-toggle position-absolute align-self-center" id="toggleIcon"></i>
                        </div> -->
                        <div class="input-with-icon-container position-relative mb-0 validate-field">
                            <i class="fa fa-key position-absolute start-0 ms-2 top-50 translate-middle-y"></i>
                            <input class="px-4 text-center form-control rounded-sm validate-password font-13" id="password-input" type="text" name="password">
                            <i class="fa password-toggle position-absolute end-0 me-2 top-50 translate-middle-y fa-lock-open" id="toggleIcon"></i>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-m mt-2 mb-4 bg-mint-dark rounded-sm text-uppercase font-900 loader" style="width: 160px;">
                                Iniciar Sesion
                            </button>
                        </div>
                    </form>
                    <button data-menu="menu-forgot">
                        He olvidado mi contraseña
                    </button>
                </div>
            </div>
            <div class="card-bottom">
                <div class="row">
                    <div class="text-end pe-5">
                        <a href="{{ route('register') }}" class="opacity-50 font-15 text-decoration-none">
                            Aún sin cuenta? <strong>Registrate aqui</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('modals')
    <div id="menu-forgot" class="menu menu-box-modal rounded-m mx-1" style="display: block; width: 20rem; height: 12rem;">
        <div class="menu-title">
            <p class="color-highlight">Delight-Nutrifood</p>
            <h1 class="font-24">Ingresar a mi cuenta</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>

        <div class="content mt-1 d-flex flex-column justify-content-center ">
            <div class="d-flex flex-row justify-content-between gap-1">
                <x-countrycode-select id="country-code-selector-ingreso"></x-countrycode-select>
                <input type="hidden" name="digitos_pais" id="digitos_pais" value="">
                <div class="input-with-icon-container mb-0 validate-field d-flex flex-grow-1">
                    <i class="fa fa-phone position-absolute ms-2 align-self-center"></i>
                    <input type="number" class="form-control validate-name rounded-sm text-center" id="telefono-ingreso" placeholder="Número de WhatsApp">
                </div>
            </div>
            <button id="btnNumeroIngreso" type="button" class="btn btn-full btn-m shadow-l validador-ingresar align-self-center rounded-s bg-highlight font-600 mt-4">
                Enviar código de ingreso
            </button>
        </div>
    </div>

    <div id="menu-verificacion-ingreso" class="menu menu-box-modal rounded-m"
    style="display: block; width: 90%; height: auto;">
        <div class="card card-style p-0 m-0 pb-3">
            <div class="card-header p-0">
                <div class="menu-title">
                    <p class="color-highlight">Delight-Nutrifood</p>
                    <h1 class="font-20">Verificación para iniciar sesión</h1>
                    <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
                </div>
            </div>
            <div class="content mb-3">
                <p>
                    Se envió un código de verificación a su WhatsApp. <br> Por favor, ingrese el código para iniciar sesión
                </p>
                <div class="text-center mx-n3">
                    <form action="" id="form-codigo-verificacion-ingreso">
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
                <p class="text-center my-3 font-11">
                    ¿No ha recibido un código aún?
                    <a href="#" id="reenviar-codigo">
                        Reenviar código
                    </a>
                </p>
                <div class="d-flex flex-row justify-content-evenly">
                    <a href="#" class="close-menu btn btn-s font-15 shadow-l rounded-s text-uppercase font-900 bg-delight-red color-white" >Cancelar</a>
                    <button type="button" id="verificar-codigo-btn-ingreso"
                        class="btn btn-s font-15 shadow-l rounded-s validador-ingresar text-uppercase font-900 bg-mint-dark color-white">
                        Verificar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-error" class="toast toast-tiny toast-top bg-red-dark fade hide" data-bs-delay="1000"
        data-bs-autohide="true" style="width: max-content; z-index: 1000; text-align: center; line-height: 19px;">
        <i class="fa fa-times-circle me-2"></i>
        <span id="mensaje-toast-error">
        </span>
    </div>

    <div id="snackbar-error" class="snackbar-toast color-white bg-red-dark mb-4 fade hide-ad"
        style="bottom: 1% !important;">
        <h1 class="color-white font-20 pt-3 mb-0">Error</h1>
        <p class="color-white mb-0 pb-3" id="mensaje-toast-error-snackbar" style="line-height: 18px;"></p>
    </div>

@endpush

@push('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slim-select@2/dist/slimselect.min.css" />
<style>
    /* Target the main container created by Slim Select based on your original select's ID */
    #country-code-selector + .ss-main .ss-single-selected,
    #country-code-selector + .ss-main .ss-selected-text {
        /* These properties prevent the text from breaking inside the element */
        word-break: normal !important; 
        overflow-wrap: normal !important;
        white-space: nowrap !important; /* Forces text to stay on one line */
    }

    /* Selects the specific text container within the Slim Select element */
    .ss-main .ss-single {
        /* Prevents the text from wrapping to the next line */
        white-space: nowrap !important; 
        
        /* Ensures words are not broken forcefully */
        word-break: normal !important; 
        overflow-wrap: normal !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/slim-select@2/dist/slimselect.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/google-libphonenumber/dist/libphonenumber.js"></script>

<script>

var digitosPais = null;
var codigoVerificacion = null;
var slimSelectIngreso = null; // Store SlimSelect instance
// // let telefonoCompleto = '';

document.addEventListener('DOMContentLoaded', function() {
    // ============= MAIN LOGIN FORM COUNTRY SELECTOR =============
    const mainSelectId = 'country-code-selector';
    const mainSelect = document.getElementById(mainSelectId);

    if (mainSelect) {
        mainSelect.classList.remove('select2-hidden-accessible');
        mainSelect.style.display = '';

        new SlimSelect({
            select: '#' + mainSelectId,
            placeholder: 'Seleccione un país',
            allowDeselect: false,
            searchPlaceholder: 'Buscar país...',
            searchText: 'Sin resultados',
            showSearch: true
        });
    }

    // ============= FORGOT PASSWORD MODAL COUNTRY SELECTOR =============
    const modalSelectId = 'country-code-selector-ingreso';
    const modalSelect = document.getElementById(modalSelectId);
    console.log('modalSelect encontrado:', modalSelect);

    if (modalSelect) {
        modalSelect.classList.remove('select2-hidden-accessible');
        modalSelect.style.display = '';

        // Initialize SlimSelect and store the instance
        slimSelectIngreso = new SlimSelect({
            select: '#' + modalSelectId,
            placeholder: 'Seleccione un país',
            allowDeselect: false,
            searchPlaceholder: 'Buscar país...',
            searchText: 'Sin resultados',
            showSearch: true
        });
        
        // Set initial value (Bolivia by default)
        setTimeout(() => {
            // Force set the value if not already set
            if (!modalSelect.value || modalSelect.value === '') {
                modalSelect.value = '591'; // Bolivia default
                // ❌ REMOVE: slimSelectIngreso.set('591');
                
                // ✅ Trigger change event so SlimSelect updates its display
                const event = new Event('change', { bubbles: true });
                modalSelect.dispatchEvent(event);
            }
            detectar(); // Initialize on page load
        }, 150);
        
        // Event listener for changes
        modalSelect.addEventListener('change', function() {
            console.log("✅ País seleccionado (ingreso):", this.value);
            detectar();
        });
    }

    // ============= PASSWORD TOGGLE =============
    const passwordInput = document.getElementById('password-input');
    const toggleButton = document.querySelector('.password-toggle');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordInput && toggleButton && toggleIcon) {
        toggleButton.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-lock');
                toggleIcon.classList.add('fa-lock-open');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-lock-open');
                toggleIcon.classList.add('fa-lock');
            }
        });
    }
});

// ============= DETECTION FUNCTION =============
function detectar() {
    const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();
    const selector = document.getElementById("country-code-selector-ingreso");
    
    if (!selector) {
        console.log("❌ Selector no encontrado");
        return;
    }
    
    let codigoValue = selector.value;
    
    // If still empty, try getting from SlimSelect instance
    if ((!codigoValue || codigoValue === '') && slimSelectIngreso) {
        const selectedData = slimSelectIngreso.selected();
        if (selectedData) {
            codigoValue = selectedData;
        }
    }
    
    // Last resort: get from selected option
    if (!codigoValue || codigoValue === '') {
        const selectedOption = selector.options[selector.selectedIndex];
        if (selectedOption) {
            codigoValue = selectedOption.value;
        }
    }
    
    const codigo = parseInt(codigoValue);
    
    console.log("🔍 Buscando dígitos para el código de país:", codigo);
    
    if (!codigo || isNaN(codigo)) {
        console.log(`⚠️ Código de país inválido: ${codigoValue}`);
        return;
    }
    
    try {
        const regiones = phoneUtil.getRegionCodesForCountryCode(codigo);
        if (!regiones || regiones.length === 0) {
            console.log(`⚠️ No se encontraron regiones para código ${codigo}`);
            return;
        }
        
        regiones.forEach(region => {
            const ejemplo = phoneUtil.getExampleNumberForType(
                region,
                libphonenumber.PhoneNumberType.MOBILE
            );
            if (ejemplo) {
                const numeroEjemplo = phoneUtil.getNationalSignificantNumber(ejemplo);
                digitosPais = numeroEjemplo.length;
                
                const digitosPaisInput = document.getElementById('digitos_pais');
                if (digitosPaisInput) {
                    digitosPaisInput.value = digitosPais;
                }
                
                console.log(`✅ Código +${codigo} → Región: ${region}, Longitud: ${digitosPais}`);

            }
        });
        console.log("👉 Dígitos del país:", digitosPais);
    } catch (e) {
        console.log("❌ Error:", e.message);
    }
}

// ============= JQUERY HANDLERS =============
$(document).ready(function() {
    $('#verificar-codigo-btn-ingreso').on('click', function() {
        verificarCodigoIngreso(true);
    });

    $('#btnNumeroIngreso').on('click', function(e) {
        console.log("Click en botón para enviar código verificación ingreso");
        e.preventDefault();
        validacionOTPIngreso();
    });
});

// ============= VALIDATION FUNCTION =============
const validacionOTPIngreso = () => {
    // Get values directly from elements
    const telefono = $('#telefono-ingreso').val();
    const selector = document.getElementById('country-code-selector-ingreso');
    
    // Get country code - try multiple methods
    let codigoPais = '';
    if (selector) {
        codigoPais = selector.value;
        // If empty, try SlimSelect instance
        if ((!codigoPais || codigoPais === '') && slimSelectIngreso) {
            const selectedData = slimSelectIngreso.selected();
            if (selectedData) {
                codigoPais = selectedData;
            }
        }
    }
    
    // Get digitos_pais from hidden input
    const digitosPaisValue = $('#digitos_pais').val();
    
    console.log("📞 Datos a enviar:", {
        telefono: telefono,
        codigoPais: codigoPais,
        digitosPais: digitosPaisValue,
        operacion: 'ingreso_usuario'
    });
    
    // Validate before sending
    if (!telefono || !codigoPais || !digitosPaisValue) {
        alert('Por favor complete todos los campos y seleccione un país');
        return;
    }
    
    var data = {
        telefono: telefono,
        codigoPais: codigoPais,
        digitosPais: digitosPaisValue,
        operacion: 'ingreso_usuario'
    }; 

    deshabilitarBotonEnviarCodigo();

    $.ajax({
        type: "post",
        url: "{{ route('usuario.enviar-codigo-verificacion') }}",
        data: data,
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: manejarExitoEnvioIngreso,
        error: manejarErrorEnvioIngreso,
        complete: habilitarBotonEnviarCodigo
    });

    // habilitarBotonEnviarCodigo();
}

const manejarExitoEnvioIngreso = (response) => {
    if (response.status === 'success') {
        codigoVerificacion = response.codigo_generado;
        console.log('✅ Código enviado exitosamente');
        $('#menu-verificacion-ingreso').addClass('menu-active');
        $('.menu-hider').addClass('menu-active');
            // // habilitarBotonEnviarCodigo(); 
    } else {
        console.log('❌ Error en la respuesta:', response);
        alert('Error: ' + (response.errors.telefono ? response.errors.telefono[0] : 'Error desconocido'));
    }
}

const manejarErrorEnvioIngreso = (xhr, status, error) => {
    // // habilitarBotonEnviarCodigo(); 

    if (xhr.responseJSON && xhr.responseJSON.errors) {
        const errors = xhr.responseJSON.errors;
        let primerMensajeError = 'Error desconocido al procesar la solicitud.'; // Mensaje de fallback

        const primeraClave = Object.keys(errors)[0];

        if (primeraClave && Array.isArray(errors[primeraClave]) && errors[primeraClave].length > 0) {
            primerMensajeError = errors[primeraClave][0];
        }
        
        // // console.error("Error al enviar código:", error);
        // // console.error("Errores detallados:", errors);

        $('#mensaje-toast-error-snackbar').text(primerMensajeError);
        $('#snackbar-error').addClass('show');
        
        setTimeout(() => {
            $('#snackbar-error').removeClass('show');
        }, 3000); 
    } else {
        console.error("Error de conexión o respuesta no esperada:", error);
        $('#mensaje-toast-error-snackbar').text('Error de conexión con el servidor. Intenta de nuevo.');
        $('#snackbar-error').addClass('show');
        setTimeout(() => {
            $('#snackbar-error').removeClass('show');
        }, 3000);
    }
};

const verificarCodigoIngreso = () => {
    const claseSolicitud = '#form-codigo-verificacion-ingreso'
    const inputs = document.querySelectorAll(`${claseSolicitud} .otp`);
    let codigoCompleto = '';

    inputs.forEach((input, index) => {
        const valor = input.value.trim();
        if (valor) {
            codigoCompleto += valor;
        } else {
            console.log(`Input ${index + 1} está vacío`);
        }
    });

    if (codigoCompleto.length === 5) {
        enviarCodigoVerificacionIngreso(codigoCompleto);
        return codigoCompleto;
    } else {
        alert('Por favor completa todos los campos del código');
        return null;
    }
};

const enviarCodigoVerificacionIngreso = (codigo) => {
    const telefono = $('#telefono-ingreso').val();
    const selector = document.getElementById('country-code-selector-ingreso');
    // Get country code - try multiple methods
    let codigoPais = selector.value;
    const telefono_completo = `+${codigoPais}${telefono}`;
    console.log("Telefono completo validacion final: ", telefono_completo);


    deshabilitarBotonValidarCodigo();
    
    $.ajax({
        type: "post",
        url: "{{ route('usuario.iniciar-sesion-otp') }}",
        data: {
            codigo: codigo,
            codigo_generado: codigoVerificacion,
            telefono_completo: telefono_completo
            // // telefono_completo: $('#') $('#telefono-ingreso').val()
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#menu-verificacion-ingreso').removeClass('menu-active');
                $('.menu-hider').removeClass('menu-active');
                $('#codigo-correcto').addClass('menu-active');
                $('#verificar-numero-ingreso').addClass('d-none');
                console.log('✅ Teléfono verificado correctamente para el ingreso del usuario');

                setTimeout(() => {
                    $('#codigo-correcto').removeClass('menu-active');
                }, 2000);

                console.log('Iniciando sesión...');
                // TODO: Implement actual login logic here
                window.location.href = response.redirect_url; // Perform the redirect

            } else {
                $('#menu-verificacion').removeClass('menu-active');
                $('.menu-hider').removeClass('menu-active');
                $('#codigo-incorrecto').addClass('menu-active');
                
                setTimeout(() => {
                    $('#codigo-incorrecto').removeClass('menu-active');
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseJSON?.errors?.codigo?.[0] || 'Error desconocido');
            $('#mensaje-toast-error').text(xhr.responseJSON?.errors?.codigo?.[0] || 'Error al verificar código');
            $('#toast-error').addClass('show');
            setTimeout(() => {
                $('#toast-error').removeClass('show');
            }, 2000);
        },
        complete: function() {
            // // habilitarBoton('#verificar-codigo-btn-ingreso');
            habilitarBotonValidarCodigo();
        }
    });
};

// const deshabilitarBoton = (selector) => {
//     const $boton = $(selector);
    
//     console.log("Deshabilitando botón:", $boton);
//     // Guardar texto original
//     if (!$boton.data('original-text')) {
//         $boton.data('original-text', $boton.text().trim());
//     }
    
//     $boton.prop('disabled', true);
//     $boton.text('Validando...');
// };

const deshabilitarBotonEnviarCodigo = () => {
    const boton = $('#btnNumeroIngreso');
    boton.prop('disabled', true);
    boton.text('Enviando...');
};

const habilitarBotonEnviarCodigo = () => {
    const boton = $('#btnNumeroIngreso');
    boton.prop('disabled', false);
    boton.text('Enviar código de ingreso');
};

const deshabilitarBotonValidarCodigo = () => {
    const boton = $('#verificar-codigo-btn-ingreso');
    boton.prop('disabled', true);
    boton.text('Verificando...');
};

const habilitarBotonValidarCodigo = () => {
    const boton = $('#verificar-codigo-btn-ingreso');
    boton.prop('disabled', false);
    boton.text('Verificar');
};



</script>
@endpush