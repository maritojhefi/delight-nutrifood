@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Registrate" cabecera="appkit" />
    <div class="d-flex justify-content-center">
        {{-- <div class="card card-style signin-card bg-24" style="height: 650px;max-height: 920px; width: 400px;"> --}}
        <div class="card card-style signin-card bg-24 py-4" style="min-height: 450px; width: 400px; ">
            <div class="">
                <div class="px-5">
                    <div class="d-flex flex-column">
                        <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}"
                            class="img mx-auto d-block" style="width:100px" alt="">
                        <h3 class="mt-2 mb-3 font-26 text-center ">¡Bienvenido a
                            {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!</h3>
                    </div>
                    {{-- Form and Steps definition --}}
                    <form action="{{ route('usuario.registrar') }}" method="post" id="multiStepForm" novalidate>
                        @csrf
                        <!-- Paso 1: Información Personal -->
                        <div class="form-step" id="step-1">
                            <x-form-step-progress :step="1" :maxSteps="3"></x-form-step-progress>
                            <h5 class="text-center mt-2">Paso 1: Información Personal</h5>
                            {{-- Nombre Completo --}}
                            <div class="d-flex flex-column">
                                <label for="name">Nombre Completo</label>
                                <div class="input-style d-flex flex-row">
                                    <i class="fa fa-user ms-2 input-icon mt-3 position-fixed"></i>
                                    <div class="d-flex flex-column w-100">
                                        <input type="text" class="form-control w-auto rounded-sm" id="name"
                                            name="name" value="{{ old('name') }}">
                                    </div>

                                </div>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Correo Electrónico --}}
                            <div class="d-flex flex-column">
                                <label for="email">Correo Electrónico</label>
                                <div class="input-style d-flex flex-row">
                                    <i class="fa fa-at ms-2 input-icon mt-3 position-fixed"></i>
                                    <div class="d-flex flex-column w-100">
                                        <input type="text" class="form-control rounded-sm" id="email" name="email"
                                            value="{{ old('email') }}">
                                    </div>
                                </div>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Telefono --}}
                            <div class="d-flex flex-column">
                                <label for="telefono">Teléfono</label>
                                <div class="input-style remove-mb d-flex flex-row justify-content-between ">
                                    <x-countrycode-select></x-countrycode-select>
                                    <div class="d-flex flex-column w-99">
                                        <input type="number" class="no-padding text-center form-control rounded-sm"
                                            id="telefono" name="telefono" value="{{ old('telefono') }}">
                                    </div>
                                    @error('telefono')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <a href="#" data-menu="menu-verificacion" id="verificar-numero"
                                    class="btn btn-xxs mb-3 rounded-s text-uppercase font-700 shadow-s bg-phone mt-2 d-none">
                                    Verificar Número<i class="fab fa-whatsapp ms-2 me-1"></i><br> (+5 puntos)</span>
                                </a>

                            </div>
                            {{-- foto de perfil --}}
                            <div class="custom-major-file-container d-flex flex-row justify-content-center mt-3"
                                id="major-file-container">
                                <div class="custom-file-upload-container">
                                    <div class="file-upload-area" id="uploadArea">
                                        <div class="upload-text">
                                            <strong>Subir Foto</strong><br>
                                            <small>JPEG, PNG, HEIC</small>
                                            <small>(opcional)</small>
                                        </div>
                                        <img class="preview-image" id="previewImage" style="display: none;">
                                        <div class="converting-overlay" id="convertingOverlay">
                                            Convirtiendo...
                                        </div>
                                    </div>
                                    <input type="file" id="fileInput" class="hidden-input" accept="image/*"
                                        name="foto">
                                    <div class="error-message" id="errorMessage"></div>
                                </div>
                                <button class="remove-button" id="removeButton" type="button">×</button>
                            </div>
                            @error('foto')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            {{-- fin foto de perfil --}}


                            <input type="hidden" name="digitos_pais" id="digitos_pais" value="">

                            <div class="d-flex justify-content-end">
                                <button type="button"
                                    class="btn btn-xs rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-next"
                                    id="nextStep1">Siguiente</button>
                            </div>
                        </div>

                        <!-- Paso 2: Detalles Adicionales -->
                        <div class="form-step d-none" id="step-2">
                            <x-form-step-progress :step="2" :maxSteps="3"></x-form-step-progress>
                            {{-- <div class="form-step d-none" id="step-1"> --}}
                            <h5 class="text-center mt-2">Paso 2: Detalles Adicionales</h5>
                            <div class="d-flex flex-column">
                                <label for="profesion">Profesión</label>
                                <div class="input-style validate-field d-flex flex-row">
                                    <i class="fa fa-briefcase ms-2 mt-3 position-fixed input-icon"></i>
                                    <div class="d-flex flex-column w-100">
                                        <input type="text"
                                            class="form-control rounded-sm @error('profesion') is-invalid @enderror"
                                            id="profesion" name="profesion" value="{{ old('profesion') }}">
                                    </div>
                                    @error('profesion')
                                        {{-- <small class="text-danger identifying">{{ $message }}</small> --}}
                                        <small class="text-danger identifying">Test error message</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex flex-column">
                                <label for="nacimiento">Fecha de nacimiento</label>
                                <div class="input-style remove-mb rounded-sm validate-field d-flex flex-row gap-2">
                                    <i class="fa fa-calendar ms-2 mt-3 position-absolute input-icon"></i>
                                    <input type="number" class="text-center form-control rounded-sm flex-grow-1"
                                        placeholder="Día" name="dia_nacimiento" required>

                                    <select class="text-center form-control rounded-sm flex-grow-1" name="mes_nacimiento"
                                        required>
                                        <option value="" disabled selected>Mes</option>
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                    <input type="number" class="form-control rounded-sm" placeholder="Año"
                                        name="ano_nacimiento" required>
                                    {{-- <div class="d-flex flex-column w-100">
                                    <input type="date" class="form-control rounded-sm" id="nacimiento" name="nacimiento">
                                </div> --}}
                                </div>
                            </div>


                            <div class="d-flex flex-column mt-2">
                                <label for="direccion">Dirección</label>
                                <div class="input-style validate-field d-flex flex-row align-content-center">
                                    <i class="fa fa-map ms-2 m-3 position-absolute input-icon"></i>
                                    <div class="d-flex flex-column w-100">
                                        <input type="text"
                                            class="form-control rounded-sm @error('direccion') is-invalid @enderror"
                                            id="direccion" name="direccion" value="{{ old('direccion') }}">
                                    </div>
                                    @error('direccion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex flex-column">
                                <label for="direccion_trabajo">Dirección de Trabajo (opcional)</label>
                                <div class="input-style validate-field d-flex flex-row align-content-center">
                                    <i class="fa fa-building ms-2 mt-3 position-absolute input-icon"></i>
                                    <div class="d-flex flex-column w-100">
                                        <input type="text"
                                            class="form-control rounded-sm @error('direccion_trabajo') is-invalid @enderror"
                                            id="direccion_trabajo" name="direccion_trabajo"
                                            value="{{ old('direccion_trabajo') }}">
                                    </div>
                                    @error('direccion_trabajo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-content-between">
                                <button type="button"
                                    class="btn btn-xs rounded-xl text-uppercase font-900 shadow-s bg-red-dark btn-prev">Anterior</button>
                                <button type="button"
                                    class="btn btn-xs rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-next">Siguiente</button>
                            </div>
                        </div>

                        <!-- Paso 3: Seguridad -->
                        <div class="form-step d-none" id="step-3">
                            <x-form-step-progress :step="3" :maxSteps="3"></x-form-step-progress>
                            <h5 class="text-center mt-2">Paso 3: Seguridad y Otros Detalles</h5>
                            <div class="d-flex flex-column">
                                <label for="password">Contraseña</label>
                                <div class="input-style validate-field d-flex flex-row">
                                    <i class="fa fa-key ms-2 mt-3 position-absolute input-icon"></i>
                                    <div class="d-flex flex-column w-100">
                                        <input type="password"
                                            class="form-control rounded-sm @error('password') is-invalid @enderror"
                                            id="password" placeholder="Minimo 4 caracteres" name="password">
                                    </div>
                                    <button type="button"
                                        class="me-2 btn btn-link position-absolute mt-2 end-0 pe-3 password-toggle"
                                        style="border: none; background: none; z-index: 10;">
                                        <i class="fa fa-lock" id="toggleIcon1"></i>
                                    </button>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex flex-column">
                                <label for="password_confirmation">Confirmar Contraseña</label>
                                <div class="input-style validate-field d-flex flex-row">
                                    <i class="fa fa-arrow-right ms-2 mt-3 position-fixed input-icon"></i>
                                    <div class="d-flex flex-column w-100">
                                        <input type="password" class="form-control rounded-sm" id="password_confirmation"
                                            name="password_confirmation">
                                        <button type="button"
                                            class="me-2 btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3 password_confirmation-toggle"
                                            style="border: none; background: none; z-index: 10;">
                                            <i class="fa fa-lock" id="toggleIcon2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-content-center mb-4">
                                <label for="hijos" class="me-2">¿Tiene hijos?:</label>
                                <input class="form-check-input" type="checkbox" name="hijos" id="hijos"
                                    value="1">
                            </div>


                            <div class="d-flex justify-content-between align-content-between">
                                <button type="button"
                                    class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-red-dark btn-prev">Anterior</button>
                                <button type="submit"
                                    class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-block">Registrar
                                    Cuenta</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('modals')
    <div id="menu-errores" class="menu menu-box-bottom menu-box-detached bg-yellow-dark rounded-m "
        data-menu-height="305" data-menu-effect="menu-over" style="display: block; height: 305px;">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-info-circle color-white shadow-xl rounded-circle"></i></h1>
        <h1 class="text-center mt-3 text-uppercase color-white font-700">Uy!</h1>
        <div class="text-center">
            <span class=" color-white opacity-70 font-500 m-0 p-0 text-center">
                Tu informacion no es correcta:
            </span>
            <div id="error-registro"></div>
        </div>


        <a href="#"
            class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-white color-yellow-dark"
            style="">Entendido</a>
    </div>

    <div id="menu-existe" class="menu menu-box-bottom menu-box-detached bg-mint-dark rounded-m " data-menu-height="305"
        data-menu-effect="menu-over">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-check-circle color-white shadow-xl rounded-circle"></i>
        </h1>
        <h1 class="text-center mt-3 text-uppercase color-white font-700">Te encontramos!</h1>
        <p class="boxed-text-l color-white opacity-70">
            Bienvenido de nuevo. Termina por favor de llenar tus datos para tener tu cuenta actualizada.
        </p>
        <a href="#"
            class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-white color-mint-dark"
            style="">Continuar</a>
    </div>








    <div id="menu-verificacion" class="menu menu-box-modal rounded-m"
        style="display: block; width: 350px; height: auto;">
        <div class="card card-style p-0 m-0 pb-3">
            <div class="card-header p-0">
                <div class="menu-title">
                    <p class="color-highlight">Verificación</p>
                    <h1>Verifica tu número </h1>
                    <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
                </div>
            </div>
            <div class="content mb-3">
                <p>
                    Ingresa el código de verificación que te enviamos a tu numero de teléfono por whatsapp.
                </p>
                <div class="text-center mx-n3">
                    <form action="" id="form-codigo-verificacion">
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
                <p class="text-center my-4 font-11">No te llego ningun codigo aún? <a href="#">Reenviar Codigo</a>
                </p>
                <a href="#" onclick="verificarCodigo()"
                    class="btn btn-full btn-l font-600 font-13 bg-mint-dark mt-4 rounded-s border-0">
                    Verificar Número
                </a>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <!-- Revisar contraseña -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmationInput = document.getElementById('password_confirmation');
            const toggleButton1 = document.querySelector('.password-toggle');
            const toggleButton2 = document.querySelector('.password_confirmation-toggle');

            const toggleIcon1 = document.getElementById('toggleIcon1');
            const toggleIcon2 = document.getElementById('toggleIcon2');

            if (passwordInput && toggleButton1 && toggleIcon1) {
                toggleButton1.addEventListener('click', function() {
                    const isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';
                    toggleIcon1.classList.toggle('fa-lock', !isHidden);
                    toggleIcon1.classList.toggle('fa-lock-open', isHidden);
                });
            }

            if (confirmationInput && toggleButton2 && toggleIcon2) {
                toggleButton2.addEventListener('click', function() {
                    const isHidden = confirmationInput.type === 'password';
                    confirmationInput.type = isHidden ? 'text' : 'password';
                    toggleIcon2.classList.toggle('fa-lock', !isHidden);
                    toggleIcon2.classList.toggle('fa-lock-open', isHidden);
                });
            }


        });
    </script>
    <!-- Inclusion de heic2any desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/heic'];

            const majorContainer = document.getElementById('major-file-container');
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            const previewImage = document.getElementById('previewImage');
            const removeButton = document.getElementById('removeButton');
            const errorMessage = document.getElementById('errorMessage');
            const convertingOverlay = document.getElementById('convertingOverlay');
            const uploadText = uploadArea.querySelector('.upload-text');

            uploadArea.addEventListener('click', function() {
                fileInput.click();
            });

            // Manejo de seleccion del archivo
            fileInput.addEventListener('change', async function(event) {
                const file = event.target.files[0];

                if (!file) {
                    resetUpload();
                    return;
                }

                // Clear previous error messages
                errorMessage.textContent = '';

                // Validar tipo de archivo
                if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.heic')) {
                    alert('Debe subir una imagen en formato valido (JPEG, PNG, o HEIC)');
                    showError('Debe subir una imagen en formato válido (JPEG, PNG, o HEIC)');
                    resetUpload();
                    return;
                }

                try {
                    let processedFile = file;

                    // Convertir HEIC de ser necesario
                    if (file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic')) {
                        convertingOverlay.classList.add('show');

                        console.log('HEIC file detected, converting...');

                        const convertedBlob = await heic2any({
                            blob: file,
                            toType: "image/jpeg",
                            quality: 0.8
                        });

                        processedFile = new File([convertedBlob],
                            file.name.replace(/\.heic$/i, '.jpg'), {
                                type: 'image/jpeg'
                            }
                        );

                        // Actualizar el Input con el archivo convertido
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(processedFile);
                        event.target.files = dataTransfer.files;

                        console.log('HEIC conversion completed');
                        convertingOverlay.classList.remove('show');
                    }

                    // Mostrar preview
                    showPreview(processedFile);

                } catch (error) {
                    console.error('Error processing image:', error);
                    showError('Error al procesar la imagen. Por favor, intenta nuevamente.');
                    resetUpload();
                    convertingOverlay.classList.remove('show');
                }
            });

            // Boton de remover
            removeButton.addEventListener('click', function(event) {
                event.stopPropagation();
                resetUpload();
            });

            function showPreview(file) {
                const previewUrl = URL.createObjectURL(file);
                previewImage.src = previewUrl;
                previewImage.style.display = 'block';
                uploadText.style.display = 'none';
                uploadArea.classList.add('has-image');
                majorContainer.classList.add('has-image');

                previewImage.onload = function() {
                    URL.revokeObjectURL(previewUrl);
                };
            }

            function resetUpload() {
                fileInput.value = '';
                previewImage.style.display = 'none';
                previewImage.src = '';
                uploadText.style.display = 'block';
                uploadArea.classList.remove('has-image');
                majorContainer.classList.remove('has-image');
                errorMessage.textContent = '';
                convertingOverlay.classList.remove('show');
            }

            function showError(message) {
                errorMessage.textContent = message;
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.form-step');
            const nextBtns = document.querySelectorAll('.btn-next');
            const prevBtns = document.querySelectorAll('.btn-prev');
            let currentStep = 0;
            const selectedCountryCode = document.getElementById('country-code-selector');

            // ============= PREVENIR ENVIO DEL FORMULARIO POR TECLA ENTER =============
            const mainForm = document.getElementById('multiStepForm');

            mainForm.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    const target = e.target;

                    // Permitir ENTER en text-areas
                    if (target.tagName === 'TEXTAREA') {
                        return true;
                    }

                    // Prevenir ENTER en el resto de la pagina
                    e.preventDefault();
                    return false;
                }
            });

            // ============= QUITAR MENSAJES DE ERROR =============

            // Primer Paso
            function clearFirstErrorMessages() {
                document.querySelectorAll('#step-1 .custom-error-message').forEach(el => el.remove());
            }

            // Todos los pasos
            function clearErrorMessages() {
                document.querySelectorAll('.custom-error-message').forEach(el => el.remove());
            }

            // ============= MOSTRAR MENSAJES DE ERROR =============
            function displayErrorMessages(errors) {
                clearErrorMessages();

                let nacimientoErrorAdded = false;

                console.log(errors);

                for (const [field, messages] of Object.entries(errors)) {
                    const inputField = document.querySelector(`[name="${field}"]`);

                    if (!inputField) continue; // Skip if field not found

                    // Crear el div para los mensajes de error
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('font-10', 'custom-error-message');
                    errorDiv.innerText = messages.join(', ');
                    errorDiv.style.cssText = 'color: red !important;';

                    // Maneja las fechas de nacimiento
                    if (['dia_nacimiento', 'mes_nacimiento', 'ano_nacimiento'].includes(field)) {
                        if (!nacimientoErrorAdded) {
                            inputField.parentElement.parentElement.appendChild(errorDiv);
                            nacimientoErrorAdded = true;
                        }
                        continue;
                    }

                    // Insertar el div de error después del input
                    if (field == 'telefono') {
                        inputField.parentElement.parentElement.parentElement.appendChild(errorDiv);
                    } else {
                        console.log(inputField);
                        inputField.parentElement.appendChild(errorDiv);
                    }
                }
            }

            // ============= VALIDAR PASO ACTUAL =============
            async function validateCurrentStep(stepNumber) {
                const currentFormStep = steps[stepNumber];
                const formData = new FormData();
                formData.append('formStep', stepNumber);

                console.log(`Iniciada validacion del paso ${stepNumber}`);

                if (stepNumber === 0) {
                    const nameInput = currentFormStep.querySelector('[name="name"]');
                    const emailInput = currentFormStep.querySelector('[name="email"]');
                    const codigoPaisInput = currentFormStep.querySelector('[name="codigo_pais"]');
                    const telefonoInput = currentFormStep.querySelector('[name="telefono"]');
                    const digitosPaisInput = currentFormStep.querySelector('[name="digitos_pais"]');
                    const fotoInput = currentFormStep.querySelector('[name="foto"]');

                    if (nameInput) formData.append('name', nameInput.value);
                    if (emailInput) formData.append('email', emailInput.value);
                    if (codigoPaisInput) formData.append('codigo_pais', `+${codigoPaisInput.value}`)
                    if (telefonoInput) formData.append('telefono', telefonoInput.value);
                    if (fotoInput && fotoInput.files.length > 0) {
                        formData.append('foto', fotoInput.files[0]);
                    }
                    if (digitosPaisInput) formData.append('digitos_pais', digitosPaisInput.value);
                } else if (stepNumber === 1) {
                    const profesionInput = currentFormStep.querySelector('[name="profesion"]');
                    const dia_nacimientoInput = currentFormStep.querySelector('[name="dia_nacimiento"]');
                    const mes_nacimientoInput = currentFormStep.querySelector('[name="mes_nacimiento"]');
                    const ano_nacimientoInput = currentFormStep.querySelector('[name="ano_nacimiento"]');
                    const direccionInput = currentFormStep.querySelector('[name="direccion"]');
                    if (profesionInput) formData.append('profesion', profesionInput.value);
                    if (dia_nacimientoInput) formData.append('dia_nacimiento', dia_nacimientoInput.value);
                    if (mes_nacimientoInput) formData.append('mes_nacimiento', mes_nacimientoInput.value);
                    if (ano_nacimientoInput) formData.append('ano_nacimiento', ano_nacimientoInput.value);
                    if (direccionInput) formData.append('direccion', direccionInput.value);
                }

                console.log(`FormData:`, ...formData);

                try {
                    const response = await fetch('{{ route('usuario.validar-paso') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        return {
                            valid: true,
                            data: data
                        };
                    } else {
                        const errors = data.errors;
                        displayErrorMessages(errors);
                        return {
                            valid: false,
                            data: data
                        };
                    }
                } catch (error) {
                    console.error('Validation error:', error);
                    return {
                        valid: false,
                        error: 'Network error occurred'
                    };
                }
            }

            // ============= MANEJO BOTONES - SIGUIENTE =============
            nextBtns.forEach(button => {
                button.addEventListener('click', async () => {
                    if (currentStep == 0) {
                        console.log("cleaning very first step error messages");
                        clearFirstErrorMessages();
                        console.log("They should've been cleared now");
                    } else {
                        clearErrorMessages();
                    }

                    button.disabled = true;
                    const originalText = button.textContent;
                    button.textContent = 'Validando...';

                    try {
                        const validationResult = await validateCurrentStep(currentStep);
                        // Si la validacion es valida se avanza al siguiente paso
                        if (validationResult.valid) {
                            steps[currentStep].classList.add('d-none');
                            currentStep++;
                            steps[currentStep].classList.remove('d-none');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    } finally {
                        // Reiniciar estado del boton siguiente
                        button.disabled = false;
                        button.textContent = originalText;
                    }
                });
            });

            // ============= MANEJO BOTONES - ANTERIOR =============
            prevBtns.forEach(button => {
                button.addEventListener('click', () => {
                    steps[currentStep].classList.add('d-none');
                    currentStep--;
                    steps[currentStep].classList.remove('d-none');
                });
            });

            // ============= MANEJO ENVIO FORMULARIO REGISTRO =============
            const form = document.getElementById('multiStepForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Evitar el envío tradicional
                console.log(`Codigo seleccionado: ${selectedCountryCode.value}`);

                const formData = new FormData(form);
                const originalCountryCode = formData.get('codigo_pais');
                formData.set('codigo_pais', `+${originalCountryCode}`);

                fetch('{{ route('usuario.registrar') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Redirigir si el registro es exitoso
                            window.location.href = data.redirect;
                        } else if (data.status === 'error') {
                            const errors = data.errors;
                            // Generar mensajes de error
                            displayErrorMessages(errors);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slim-select@2/dist/slimselect.min.css" />
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/slim-select@2/dist/slimselect.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/google-libphonenumber/dist/libphonenumber.js"></script>s
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



        var digitosPais = null;

        function detectar() {
            const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();
            const codigo = parseInt(document.getElementById("country-code-selector").value);
            try {
                const regiones = phoneUtil.getRegionCodesForCountryCode(codigo);
                if (!regiones || regiones.length === 0) {
                    console.log(`⚠ No se encontraron regiones para código ${codigo}`);
                    return;
                }
                regiones.forEach(region => {
                    const ejemplo = phoneUtil.getExampleNumberForType(
                        region,
                        libphonenumber.PhoneNumberType.MOBILE
                    );
                    if (ejemplo) {
                        const numeroEjemplo = phoneUtil.getNationalSignificantNumber(ejemplo);
                        digitosPais = numeroEjemplo.length; // ✅ aquí lo asignas
                        document.getElementById('digitos_pais').value = digitosPais;
                        console.log(`Código +${codigo} → Región: ${region}, Longitud: ${digitosPais}`);
                    }
                });
                // console.log("👉 Digitos del país: ", digitosPais);
            } catch (e) {
                console.log("❌ Error: " + e.message);
            }
        }

        var codigoVerificacion = null;

        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('telefono').addEventListener('input', function() {
                if (this.value.length === digitosPais) {
                    document.getElementById('verificar-numero').classList.remove('d-none');
                    console.log("✅ El usuario escribió exactamente 8 caracteres");
                } else {
                    document.getElementById('verificar-numero').classList.add('d-none');
                    console.log("Caracteres actuales:", this.value.length);
                }
            });

            document.getElementById('country-code-selector').addEventListener('change', function() {
                // console.log("✅ El usuario seleccionó un país", this.value);
                detectar();
            });
            detectar();


            document.getElementById('verificar-numero').addEventListener('click', function() {
                var telefono = document.getElementById('telefono').value;
                var codigoPais = document.getElementById('country-code-selector').value;
                var digitosPais = document.getElementById('digitos_pais').value;
                var csrfToken = '{{ csrf_token() }}';
                var data = {
                    telefono: telefono,
                    codigoPais: codigoPais,
                    digitosPais: digitosPais
                };

                $.ajax({
                    type: "post",
                    url: "{{ route('usuario.verificar-numero') }}",
                    data: data,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        codigoVerificacion = response.codigo_generado;
                        // console.log("✅ Codigo de verificacion: ", codigoVerificacion);
                    },
                    error: function(xhr, status, error) {
                        console.log("❌ Error: ", error);
                    }
                });
            });



        });

        // Configurar inputs cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            configurarInputsOTP();
        });

        // Configurar inputs para un solo carácter
        function configurarInputsOTP() {
            const inputs = document.querySelectorAll('#form-codigo-verificacion .otp');

            inputs.forEach((input, index) => {
                // Solo permitir números
                input.addEventListener('input', function(e) {
                    // Remover cualquier carácter que no sea número
                    let valor = e.target.value.replace(/[^0-9]/g, '');

                    // Limitar a un solo carácter
                    if (valor.length > 1) {
                        valor = valor.slice(0, 1);
                    }

                    e.target.value = valor;

                    // Si se ingresó un valor y no es el último input, avanzar al siguiente
                    if (valor && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    // Si es el último input y tiene valor, verificar automáticamente
                    if (valor && index === inputs.length - 1) {
                        setTimeout(() => {
                            verificarCodigo();
                        }, 100);
                    }
                });

                // Manejar tecla backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Manejar teclas de navegación
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowLeft' && index > 0) {
                        inputs[index - 1].focus();
                    } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                // Prevenir pegar múltiples caracteres
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const texto = (e.clipboardData || window.clipboardData).getData('text');
                    const numeros = texto.replace(/[^0-9]/g, '');

                    if (numeros.length > 0) {
                        e.target.value = numeros[0];

                        // Si hay más números, distribuirlos en los siguientes inputs
                        for (let i = 1; i < numeros.length && (index + i) < inputs.length; i++) {
                            inputs[index + i].value = numeros[i];
                        }

                        // Enfocar el último input con valor
                        const ultimoIndex = Math.min(index + numeros.length - 1, inputs.length - 1);
                        inputs[ultimoIndex].focus();
                    }
                });
            });
        }

        function verificarCodigo() {
            // Obtener todos los inputs del formulario
            const inputs = document.querySelectorAll('#form-codigo-verificacion .otp');
            let codigoCompleto = '';

            // Recorrer cada input y obtener su valor
            inputs.forEach((input, index) => {
                const valor = input.value.trim();

                // Si el valor no está vacío
                if (valor) {
                    codigoCompleto += valor;
                } else {
                    console.log(`Input ${index + 1} está vacío`);
                }
            });

            // Verificar si se completó el código
            if (codigoCompleto.length === 5) {
                console.log('Código completo:', codigoCompleto);

                // Aquí puedes hacer la verificación del código
                // Por ejemplo, enviar al servidor:
                enviarCodigoVerificacion(codigoCompleto);

                return codigoCompleto;
            } else {
                console.log('Código incompleto. Faltan caracteres:', 5 - codigoCompleto.length);
                alert('Por favor completa todos los campos del código');
                return null;
            }
        }

        // Función para enviar el código al servidor
        function enviarCodigoVerificacion(codigo) {
            // Aquí puedes hacer la petición AJAX o usar Livewire
            console.log('Enviando código para verificación:', codigo);

            if (codigo == codigoVerificacion) {
                console.log("✅ Codigo de verificacion correcto");
            } else {
                console.log("❌ Codigo de verificacion incorrecto");
                alert('Código incorrecto, intenta de nuevo');
            }

        }
    </script>
@endpush
