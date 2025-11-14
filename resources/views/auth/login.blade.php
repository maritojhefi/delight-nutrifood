@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Iniciar Sesión" cabecera="appkit" />
    <div class="d-flex justify-content-center">
        <div class="card card-style login-card bg-24 d-flex align-items-center justify-content-center" style="height: 550px; width: 380px;">
            <div class="d-flex flex-column align-items-center justify-content-center gap-2 px-4 mx-2">
                <div class="d-flex flex-column mb-3">
                    <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" class="img mx-auto d-block" style="width:100px" alt="">
                    <h3 class="mt-2 font-26 text-center ">¡Bienvenido a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!</h3>
                </div>
                <form action="{{ route('login') }}" method="post" class="d-flex flex-column justify-content-center gap-1">
                    @csrf
                    <label for="telefono-ingreso">Número de Teléfono</label>
                

                    <div class="d-flex flex-row gap-2">
                        <x-countrycode-select></x-countrycode-select>
                        <div class="input-with-icon-container mb-0 validate-field d-flex flex-grow-1">
                            <i class="fa fa-phone position-absolute ms-2 align-self-center"></i>

                            <input class="ps-3 text-center form-control rounded-sm" id="telefono-ingreso" name="telf" type="tel" value="{{ old('telf') }}">
                        </div>
                    </div>
                    

                    @error('telf')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    
                    <label for="password-input">Contraseña</label>
                    <div class="input-with-icon-container position-relative mb-0 validate-field">
                        <i class="fa fa-key position-absolute start-0 ms-2 top-50 translate-middle-y"></i>
                        <input class="px-4 text-center form-control rounded-sm validate-password font-13" id="password-input" type="password" name="password">
                        <i class="fa password-toggle position-absolute end-0 me-2 top-50 translate-middle-y fa-lock" id="toggleIcon"></i>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    
                    <button type="submit" class="btn btn-m mt-2 mb-2 bg-mint-dark rounded-sm text-uppercase align-self-center font-900 loader w-100">
                        Iniciar Sesión
                    </button>
                </form>
                <button id="btn-forgot" class="align-self-center"
                    data-menu="menu-forgot">
                    <span class="text-decoration-underline mb-0">
                        He olvidado mi contraseña
                    </span>
                </button>
                <div class="opacity-80 mt-3 font-13 text-decoration-none color-theme d-flex flex-row justify-content-evenly align-items-center w-100">
                    <span class="color*theme mb-0">
                        ¿Aún sin cuenta?
                    </span> 
                    <a href="{{ route('register') }}" class="btn btn-xs rounded-sm bg-delight-red">
                        Regístrate aquí</a>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('modals')
    <div id="menu-forgot" class="menu menu-box-modal rounded-m" style="display: block; width: 20rem; height: 12rem;">
        <div class="menu-title">
            <p class="color-highlight">{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}</p>
            <h1 class="font-24">Ingresar a mi cuenta</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>

        <div class="content mt-1 d-flex flex-column justify-content-center ">
            <div class="d-flex flex-row justify-content-between gap-1">
                <x-countrycode-select id="country-code-selector-ingreso"></x-countrycode-select>
                <input type="hidden" name="digitos_pais" id="digitos_pais" value="">
                <div class="input-with-icon-container mb-0 validate-field d-flex flex-grow-1">
                    <i class="fa fa-phone position-absolute ms-2 align-self-center"></i>
                    <input type="tel" class="form-control validate-name rounded-sm text-center" id="telefono-ingreso-otp" placeholder="Número de WhatsApp">
                </div>
            </div>
            <button id="btnNumeroIngreso" type="button" class="btn btn-full btn-m shadow-l validador-ingresar align-self-center rounded-s bg-highlight font-600 mt-3">
                Enviar código de ingreso
            </button>
        </div>
    </div>

    <x-modal-otp-whatsapp funcionalidad="ingreso" />
@endpush

@push('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slim-select@2/dist/slimselect.min.css" />
<style>
    #country-code-selector + .ss-main .ss-single-selected,
    #country-code-selector + .ss-main .ss-selected-text {
        word-break: normal !important; 
        overflow-wrap: normal !important;
        white-space: nowrap !important;
    }

    .ss-main .ss-single {
        white-space: nowrap !important; 
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
var slimSelectIngreso = null;

document.addEventListener('DOMContentLoaded', function() {
    // ============= MAIN LOGIN FORM COUNTRY SELECTOR =============
    const mainSelectId = 'country-code-selector';
    const mainSelect = document.getElementById(mainSelectId);

    if (mainSelect) {
        mainSelect.classList.remove('select2-hidden-accessible');
        mainSelect.style.display = '';

        new SlimSelect({
            select: '#' + mainSelectId,
            settings: {
                placeholder: 'Seleccione un país',
                searchPlaceholder: 'Buscar país...',
                searchText: 'Sin resultados',
                allowDeselect: false,
                showSearch: true,
            }
        });

        // ACTIVAR detección para login form
        mainSelect.addEventListener('change', function() {
            detectarYAplicarMaxLength('country-code-selector', 'telefono-ingreso');
        });
        
        // Aplicar al cargar la página
        setTimeout(() => {
            detectarYAplicarMaxLength('country-code-selector', 'telefono-ingreso');
        }, 150);
    }

    // ============= FORGOT PASSWORD MODAL COUNTRY SELECTOR =============
    const modalSelectId = 'country-code-selector-ingreso';
    const modalSelect = document.getElementById(modalSelectId);

    if (modalSelect) {
        modalSelect.style.display = '';

        slimSelectIngreso = new SlimSelect({
            select: '#' + modalSelectId,
            settings: {
                placeholder: 'Seleccione un país',
                searchPlaceholder: 'Buscar país...',
                searchText: 'Sin resultados',
                allowDeselect: false,
                showSearch: true,
            }
        });
        
        setTimeout(() => {
            if (!modalSelect.value || modalSelect.value === '') {
                modalSelect.value = '591'; // Bolivia default
                
                const event = new Event('change', { bubbles: true });
                modalSelect.dispatchEvent(event);
            }
            detectar(); // Usar función legacy
        }, 150);
        
        modalSelect.addEventListener('change', function() {
            detectar(); // Usar función legacy
        });
    }

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

// ============= FUNCIÓN GENÉRICA PARA DETECTAR Y APLICAR MAXLENGTH =============
function detectarYAplicarMaxLength(selectorId, inputId) {
    const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();
    const selector = document.getElementById(selectorId);
    const input = document.getElementById(inputId);
    
    if (!selector || !input) {
        console.warn(`❌ Selector o input no encontrado: ${selectorId}, ${inputId}`);
        return;
    }
    
    let codigoValue = selector.value;
    
    if ((!codigoValue || codigoValue === '') && slimSelectIngreso && selectorId === 'country-code-selector-ingreso') {
        const selectedData = slimSelectIngreso.selected();
        if (selectedData) {
            codigoValue = selectedData;
        }
    }
    
    if (!codigoValue || codigoValue === '') {
        const selectedOption = selector.options[selector.selectedIndex];
        if (selectedOption) {
            codigoValue = selectedOption.value;
        }
    }
    
    const codigo = parseInt(codigoValue);
    
    if (!codigo || isNaN(codigo)) {
        console.warn(`⚠️ Código de país inválido: ${codigoValue}`);
        return;
    }
    
    try {
        const regiones = phoneUtil.getRegionCodesForCountryCode(codigo);
        if (!regiones || regiones.length === 0) {
            console.warn(`⚠️ No se encontraron regiones para código ${codigo}`);
            return;
        }
        
        for (const region of regiones) {
            const ejemplo = phoneUtil.getExampleNumberForType(
                region,
                libphonenumber.PhoneNumberType.MOBILE
            );
            
            if (ejemplo) {
                const numeroEjemplo = phoneUtil.getNationalSignificantNumber(ejemplo);
                const digitosEsperados = numeroEjemplo.length;
                
                input.setAttribute('maxlength', digitosEsperados);
                
                if (selectorId === 'country-code-selector-ingreso') {
                    const digitosPaisInput = document.getElementById('digitos_pais');
                    if (digitosPaisInput) {
                        digitosPaisInput.value = digitosEsperados;
                    }
                    digitosPais = digitosEsperados;
                }
                
                // // console.log(`${inputId}: Código +${codigo} → Región: ${region}, MaxLength: ${digitosEsperados}`);
                return;
            }
        }
    } catch (e) {
        console.error(`❌ Error detectando dígitos para ${inputId}:`, e.message);
    }
}

// ============= FUNCIÓN LEGACY (mantener por compatibilidad) =============
function detectar() {
    detectarYAplicarMaxLength('country-code-selector-ingreso', 'telefono-ingreso-otp');
}

$(document).ready(function() {
    $('#verificar-codigo-btn-ingreso').on('click', function() {
        verificarCodigoIngreso(true);
    });

    $('#btnNumeroIngreso').on('click', function(e) {
        // console.log("Click en botón para enviar código verificación ingreso");
        e.preventDefault();
        validacionOTPIngreso();
    });

    // Actualizar valor por defecto al hacer click en contrasena olvidada
    $('#btn-forgot').on('click', function() {
        $('#telefono-ingreso-otp').val( $('#telefono-ingreso').val() );    
    });
});

const validacionOTPIngreso = () => {
    const telefono = $('#telefono-ingreso-otp').val();
    const selector = document.getElementById('country-code-selector-ingreso');
    
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
    
    const digitosPaisValue = $('#digitos_pais').val();
    
    // console.log("Datos a enviar:", {
    //     telefono: telefono,
    //     codigoPais: codigoPais,
    //     digitosPais: digitosPaisValue,
    //     operacion: 'ingreso_usuario'
    // });
    
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
        $('#menu-verificacion-ingreso').addClass('menu-active');
        $('.menu-hider').addClass('menu-active');
            // // habilitarBotonEnviarCodigo(); 
    } else {
        console.error('❌ Error en la respuesta:', response);
        alert('Error: ' + (response.errors.telefono ? response.errors.telefono[0] : 'Error desconocido'));
    }
}

const manejarErrorEnvioIngreso = (xhr, status, error) => {

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
            // console.log(`Input ${index + 1} está vacío`);
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
    const telefono = $('#telefono-ingreso-otp').val();
    const selector = document.getElementById('country-code-selector-ingreso');
    let codigoPais = selector.value;
    const telefono_completo = `+${codigoPais}${telefono}`;


    // deshabilitarBotonValidarCodigo();
    deshabilitarBotonVerifiacionOTP();
    
    $.ajax({
        type: "post",
        url: "{{ route('usuario.iniciar-sesion-otp') }}",
        data: {
            codigo: codigo,
            codigo_generado: codigoVerificacion,
            telefono_completo: telefono_completo
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

                setTimeout(() => {
                    $('#codigo-correcto').removeClass('menu-active');
                }, 2000);

                window.location.href = response.redirect_url;

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
            let mensajeError = 'Ha ocurrido un error. Por favor, intenta nuevamente.';
            
            // Intentar obtener el mensaje del servidor
            if (xhr.responseJSON?.errors) {
                // Priorizar errores específicos (codigo, general, etc.)
                if (xhr.responseJSON.errors.codigo?.[0]) {
                    mensajeError = xhr.responseJSON.errors.codigo[0];
                } else if (xhr.responseJSON.errors.general?.[0]) {
                    mensajeError = xhr.responseJSON.errors.general[0];
                } else if (xhr.responseJSON.errors.telefono?.[0]) {
                    mensajeError = xhr.responseJSON.errors.telefono[0];
                }
            }
            
            $('#mensaje-toast-error-snackbar').text(mensajeError);
            $('#snackbar-error').addClass('show');
            setTimeout(() => {
                $('#snackbar-error').removeClass('show');
            }, 3000);
        },
        complete: function() {
            // // habilitarBoton('#verificar-codigo-btn-ingreso');
            // // habilitarBotonValidarCodigo();
            habilitarBotonVerifiacionOTP();
        }
    });
};

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