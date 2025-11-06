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
                </div>
            </div>
            <div class="card-bottom">
                <div class="row">
                    <div class="text-end pe-5">
                        <a href="{{ route('register') }}" class="opacity-50 font-15 text-decoration-none">
                            Aun sin cuenta? <strong>Registrate aqui</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
    document.addEventListener('DOMContentLoaded', function() {
        const selectId = '{{ $id ?? 'country-code-selector' }}';

        const selectElement = document.getElementById(selectId);

        let select = document.getElementById(selectId);
        select.classList.remove('select2-hidden-accessible'); // limpia restos de select2
        select.style.display = '';

        console.log(select, selectElement);

        if (selectElement) {

            // muestra de nuevo el select si fue ocultado

            // Inicializar SlimSelect
            new SlimSelect({
                select: '#' + selectId,
                placeholder: 'Seleccione un país',
                allowDeselect: false,
                searchPlaceholder: 'Buscar país...',
                searchText: 'Sin resultados',
                showSearch: true
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password-input');
        const toggleButton = document.querySelector('.password-toggle');
        const toggleIcon = document.getElementById('toggleIcon');
        // reinitializeLucideIcons();

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
</script>
@endpush

