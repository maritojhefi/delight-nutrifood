@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Registrate" cabecera="appkit" />
    <div class="d-flex justify-content-center">
        {{-- <div class="card card-style signin-card bg-24" style="height: 650px;max-height: 920px; width: 400px;"> --}}
        <div class="card card-style signin-card bg-24 py-4" style="min-height: 450px; width: 400px; ">
            <div class="">
                <div class="px-4 mx-3">
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
                            <!-- <div class="d-flex flex-column">
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
                            </div> -->
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

                                <!-- <div class="d-flex justify-content-center">
                                    <button href="#" data-menu="menu-verificacion" id="verificar-numero"
                                        class="btn btn-xxs mb-3 rounded-s font-700 shadow-s bg-phone mt-2 d-none w-50">
                                        Verificar <i
                                            class="fab fa-whatsapp ms-1"></i><br>{{ isset($usuarioReferido) && $usuarioReferido != '' ? 'Gana Puntos' : '' }}
                                        <span id="contador-cuenta-regresiva" class="d-none ms-2">30</span></span>
                                    </button>
                                </div> -->
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
                            <input type="hidden" name="telefono_verificado" id="telefono_verificado" value="0">
                            <div class="d-flex justify-content-between row">
                                <div
                                    class="d-flex align-items-center justify-content-center col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2 mt-2">
                                    <button type="button"
                                        class="btn btn-xs rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-next"
                                        id="nextStep1">Siguiente</button>
                                </div>
                                @isset($usuarioReferido)
                                    <input type="hidden" name="partner_id" id="partner_id" value="{{ $usuarioReferido->id }}">
                                    <div
                                        class="d-flex align-items-center justify-content-center col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2">
                                        <div class="align-self-center">
                                            @if ($usuarioReferido->foto)
                                                <img src="{{ $usuarioReferido->pathFoto }}"
                                                    class="rounded-sm me-1" width="40">
                                            @else
                                                <img src="{{ asset('user.png') }}" class="rounded-sm me-1" width="40">
                                            @endif
                                        </div>
                                        <div class="align-self-center">
                                            <p class="color-mint-dark font-11 mb-n2">Invitado por:</p>
                                            <h2 class="font-15 line-height-s mt-1 mb-1">{{ $usuarioReferido->name }}</h2>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="partner_id" id="partner_id" value="">
                                @endisset
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


                            @isset($usuarioReferido)
                                <div
                                    class="d-flex align-items-center justify-content-center col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2 mt-4">
                                    <div class="align-self-center">
                                        @if ($usuarioReferido->foto)
                                            <img src="{{ $usuarioReferido->pathFoto }}"
                                                class="rounded-sm me-1" width="40">
                                        @else
                                            <img src="{{ asset('user.png') }}" class="rounded-sm me-1" width="40">
                                        @endif
                                    </div>
                                    <div class="align-self-center">
                                        <p class="color-mint-dark font-11 mb-n2">Invitado por:</p>
                                        <h2 class="font-15 line-height-s mt-1 mb-1">{{ $usuarioReferido->name }}</h2>
                                    </div>
                                </div>
                            @endisset
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
                                    class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-block">
                                    Registrar
                                </button>
                            </div>


                            @isset($usuarioReferido)
                                <div
                                    class="d-flex align-items-center justify-content-center col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2 mt-4">
                                    <div class="align-self-center">
                                        @if ($usuarioReferido->foto)
                                            <img src="{{ $usuarioReferido->pathFoto }}"
                                                class="rounded-sm me-1" width="40">
                                        @else
                                            <img src="{{ asset('user.png') }}" class="rounded-sm me-1" width="40">
                                        @endif
                                    </div>
                                    <div class="align-self-center">
                                        <p class="color-mint-dark font-11 mb-n2">Invitado por:</p>
                                        <h2 class="font-15 line-height-s mt-1 mb-1">{{ $usuarioReferido->name }}</h2>
                                    </div>
                                </div>
                            @endisset

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
        style="display: block; width: 90%; height: auto;">
        <div class="card card-style p-0 m-0 pb-3">
            <div class="card-header p-0">
                <div class="menu-title">
                    <p class="color-highlight">Delight-Nutrifood</p>
                    <h1 class="font-20">Verifica tu número </h1>
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
                <p class="text-center my-4 font-11">No te llego ningun codigo aún? <a href="#"
                        id="reenviar-codigo">Reenviar Codigo</a>
                </p>
                <div class="text-center">
                    <button type="button" id="verificar-codigo-btn"
                        class="btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-mint-dark color-white">
                        Verificar Código
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="d-flex justify-content-center">
        <button href="#" data-menu="menu-verificacion" id="verificar-numero"
            class="btn btn-xxs mb-3 rounded-s font-700 shadow-s bg-phone mt-2 d-none w-50">
            Verificar <i
                class="fab fa-whatsapp ms-1"></i><br>{{ isset($usuarioReferido) && $usuarioReferido != '' ? 'Gana Puntos' : '' }}
            <span id="contador-cuenta-regresiva" class="d-none ms-2">30</span></span>
        </button>
    </div> -->



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
        var formData = new FormData();
        let currentStep = 0;
        let omitirValidacionWhatsapp = false;
        let otpRetryCount = 0;
        const MAX_OTP_RETRIES = 3;

        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.form-step');
            const nextBtns = document.querySelectorAll('.btn-next');
            const prevBtns = document.querySelectorAll('.btn-prev');
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
                formData.append('formStep', stepNumber);

                console.log(`Iniciada validacion del paso ${stepNumber}`);

                if (stepNumber === 0) {
                    const nameInput = currentFormStep.querySelector('[name="name"]');
                    const emailInput = currentFormStep.querySelector('[name="email"]');
                    const codigoPaisInput = currentFormStep.querySelector('[name="codigo_pais"]');
                    const telefonoInput = currentFormStep.querySelector('[name="telefono"]');
                    const digitosPaisInput = currentFormStep.querySelector('[name="digitos_pais"]');
                    const fotoInput = currentFormStep.querySelector('[name="foto"]');
                    const partnerIdInput = currentFormStep.querySelector('[name="partner_id"]');

                    const telefonoVerificadoInput = currentFormStep.querySelector(
                        '[name="telefono_verificado"]');
                    if (nameInput) formData.append('name', nameInput.value);
                    if (emailInput) formData.append('email', emailInput.value);
                    if (codigoPaisInput) formData.append('codigo_pais', `+${codigoPaisInput.value}`)
                    if (telefonoInput) formData.append('telefono', telefonoInput.value);
                    if (fotoInput && fotoInput.files.length > 0) {
                        formData.append('foto', fotoInput.files[0]);
                    }
                    if (telefonoVerificadoInput) formData.append('telefono_verificado', telefonoVerificadoInput
                        .value);

                    if (telefonoVerificadoInput.value == 0) {
                        if (digitosPaisInput) formData.append('digitos_pais', digitosPaisInput.value);
                        formData.append('telefono_verificado', telefonoVerificadoInput.value);
                    }
                    if (partnerIdInput) formData.append('partner_id', partnerIdInput.value);
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

                console.log(`FormData actual:`, ...formData);

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
                    } else if (data.status == 'otp-register-validation') {
                        // Display del menu OTP para validar whatsapp
                        console.log("respuesta otp-register-validation detextado");
                        validacionOTPStep();
                    } else {
                        const errors = data.errors;
                        habilitarBotonesValidando();
                        displayErrorMessages(errors);
                        return {
                            valid: false,
                            data: data
                        };  
                    }
                } catch (error) {
                    console.error('Validation error:', error);
                    habilitarBotonesValidando();
                    return {
                        valid: false,
                        error: 'Network error occurred'
                    };
                }
            }

            const validacionOTPStep = function() {
                var telefono = document.getElementById('telefono').value;
                var codigoPais = document.getElementById('country-code-selector').value;
                var digitosPais = document.getElementById('digitos_pais').value;
                var csrfToken = '{{ csrf_token() }}';

                // Validar que todos los campos estén completos
                if (!telefono || !codigoPais || !digitosPais) {
                    alert('Por favor completa todos los campos antes de verificar.');
                    return;
                }

                // Validar longitud del teléfono
                if (telefono.length != digitosPais) {
                    alert('El número de teléfono debe tener exactamente ' + digitosPais + ' dígitos.');
                    return;
                }

                var data = {
                    telefono: telefono,
                    codigoPais: codigoPais,
                    digitosPais: digitosPais
                };

                // Deshabilitar el botón durante la petición
                this.disabled = true;
                this.textContent = 'Enviando...';

                if (omitirValidacionWhatsapp) {
                    $('#step-1').addClass('d-none');
                    $('#step-2').removeClass('d-none');
                    currentStep = 1;
                    habilitarBotonesValidando();
                    return;
                }

                $.ajax({
                    type: "post",
                    url: "{{ route('usuario.enviar-codigo-verificacion') }}",
                    data: data,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            codigoVerificacion = response.codigo_generado;
                            console.log('✅ Código enviado exitosamente');
                            // Resetear contador en caso de éxito
                            otpRetryCount = 0;
                            // Mostrar modal de verificación
                            $('#menu-verificacion').addClass('menu-active');
                            $('.menu-hider').addClass('menu-active');
                        } else {
                            console.log('❌ Error en la respuesta:', response);
                            alert('Error: ' + (response.errors.telefono ? response.errors.telefono[0] : 'Error desconocido'));
                        }
                        habilitarBotonesValidando();
                    },
                    error: function(xhr, status, error) {
                        // Incrementar contador de reintentos
                        otpRetryCount++;
                        console.log(`❌ Intento fallido ${otpRetryCount} de ${MAX_OTP_RETRIES}`);

                        var codigo = xhr.responseJSON?.codigo_error;
                        
                        // Si alcanzamos el máximo de reintentos, omitir validación OTP
                        if (otpRetryCount >= MAX_OTP_RETRIES) {
                            console.log('⚠️ Máximo de reintentos alcanzado. Omitiendo validación OTP.');
                            
                            // Marcar teléfono como no verificado pero permitir continuar
                            $('#telefono_verificado').val(0);
                            
                            // Mostrar mensaje al usuario
                            $('#mensaje-toast-error-snackbar').text(
                                'No pudimos verificar tu número. Continuarás sin verificación de teléfono.'
                            );
                            $('#snackbar-error').removeClass('hide').addClass('show');
                            setTimeout(() => {
                                $('#snackbar-error').removeClass('show').addClass('hide');
                            }, 3000);
                            
                            // TODO: Aquí debes avanzar al siguiente paso
                            // Por ejemplo, si estás en un wizard de pasos:
                            // steps[currentStep].classList.add('d-none');
                            // currentStep++;
                            // steps[currentStep].classList.remove('d-none');
                            $('#step-1').addClass('d-none');
                            $('#step-2').removeClass('d-none');

                            currentStep = 1;
                            omitirValidacionWhatsapp = true;
                            
                            // O cerrar el modal y permitir continuar
                            $('#menu-verificacion').removeClass('menu-active');
                            $('.menu-hider').removeClass('menu-active');
                            
                            habilitarBotonesValidando();
                            return;
                        }

                        // Lógica normal de error si no hemos alcanzado el máximo
                        if (codigo == 401 || codigo == 500) {
                            $('#menu-verificacion').removeClass('menu-active');
                            $('.menu-hider').removeClass('menu-active');
                            $('#verificar-numero').addClass('d-none');
                            $('#telefono_verificado').val(0);
                            
                            var mensajeError = xhr.responseJSON?.errors?.general?.[0] || 'Error al enviar el código';
                            mensajeError += ` (Intento ${otpRetryCount}/${MAX_OTP_RETRIES})`;
                            
                            $('#mensaje-toast-error-snackbar').text(mensajeError);
                            $('#snackbar-error').removeClass('hide').addClass('show');
                            setTimeout(() => {
                                $('#snackbar-error').removeClass('show').addClass('hide');
                            }, 5000);
                        } else {
                            var errorMessage = 'Error al enviar el código de verificación.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                var errors = xhr.responseJSON.errors;
                                if (errors.telefono) {
                                    errorMessage = errors.telefono[0];
                                } else if (errors.general) {
                                    errorMessage = errors.general[0];
                                }
                            }
                            errorMessage += ` (Intento ${otpRetryCount}/${MAX_OTP_RETRIES})`;
                            alert(errorMessage);
                        }
                        
                        habilitarBotonesValidando();
                    },
                });
            }

            // ============= MANEJO BOTONES - SIGUIENTE =============
            nextBtns.forEach(button => {
                button.addEventListener('click', async () => {
                    const originalText = button.textContent;

                    if (currentStep == 0) {
                        console.log("cleaning very first step error messages");
                        clearFirstErrorMessages();
                        console.log("They should've been cleared now");
                    } else {
                        clearErrorMessages();
                    }

                    // button.disabled = true;
                    // button.textContent = 'Validando...';
                    // deshabilitarBotonValidando(button);
                    deshabilitarBotonesValidando();

                    try {
                        const validationResult = await validateCurrentStep(currentStep);
                        // Si la validacion es valida se avanza al siguiente paso
                        if (validationResult?.valid) {
                            steps[currentStep].classList.add('d-none');
                            currentStep++;
                            steps[currentStep].classList.remove('d-none');
                            habilitarBotonesValidando();
                            // habilitarBoton(button,originalText);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        habilitarBotonesValidando();
                        // habilitarBoton(button,originalText);
                    } 
                    // finally {
                    //     // Reiniciar estado del boton siguiente
                    //     habilitarBoton(button,originalText);
                    // }
                });
            });

            const deshabilitarBotonValidando = (boton) => {
                boton.disabled = true;
                boton.textContent = 'Validando...';  // Sin paréntesis
            }

            const habilitarBoton = (boton, texto) => {
                boton.disabled = false;
                boton.textContent = texto;  // Sin paréntesis
            }

            const deshabilitarBotonesValidando = () => {
                const nextBtns = $('.btn-next');
                nextBtns.prop('disabled', true);
                nextBtns.text('Validando...');
            }

            const habilitarBotonesValidando = () => {
                const nextBtns = $('.btn-next');
                nextBtns.prop('disabled', false);
                nextBtns.text('Siguiente');
            }



            // ============= MANEJO BOTONES - ANTERIOR =============
            prevBtns.forEach(button => {
                button.addEventListener('click', () => {
                    // Only allow going back if currentStep is greater than 0
                    console.log("valor actual currentStep:", currentStep);
                    if (currentStep > 0) { 
                        steps[currentStep].classList.add('d-none');
                        currentStep--;
                        steps[currentStep].classList.remove('d-none');
                    }
                });
            })

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
                // Verificar que digitosPais esté definido
                if (digitosPais && this.value.length === digitosPais) {
                    // Validar el teléfono en tiempo real cuando se complete la longitud
                    validarTelefonoEnTiempoReal(this.value);
                } else {
                    document.getElementById('verificar-numero').classList.add('d-none');
                    console.log("Caracteres actuales:", this.value.length, "Esperados:", digitosPais);
                }
            });

            // Función para validar el teléfono en tiempo real
            function validarTelefonoEnTiempoReal(telefono) {
                var codigoPais = document.getElementById('country-code-selector').value;
                var digitosPais = document.getElementById('digitos_pais').value;

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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);

                        if (response.status === 'success') {
                            // Teléfono válido - mostrar botón de verificar
                            document.getElementById('verificar-numero').classList.remove('d-none');
                            console.log("✅ Teléfono válido - se puede verificar");
                        } else {
                            // Teléfono inválido - ocultar botón y mostrar error
                            document.getElementById('verificar-numero').classList.add('d-none');
                            console.log("❌ Teléfono inválido:", response.errors);

                            // Mostrar mensaje de error específico
                            var errorMessage = 'Error en el teléfono';
                            if (response.errors.telefono) {
                                errorMessage = response.errors.telefono[0];
                            } else if (response.errors.general) {
                                errorMessage = response.errors.general[0];
                            }

                            $('#mensaje-toast-error').text(errorMessage);
                            $('#toast-error').removeClass('hide').addClass('show');
                            setTimeout(() => {
                                $('#toast-error').removeClass('show').addClass('hide');
                            }, 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("❌ Error en validación:", xhr.responseJSON);
                        document.getElementById('verificar-numero').classList.add('d-none');

                        var errorMessage = 'Error al validar el teléfono';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            if (errors.telefono) {
                                errorMessage = errors.telefono[0];
                            } else if (errors.general) {
                                errorMessage = errors.general[0];
                            }
                        }

                        $('#mensaje-toast-error').text(errorMessage);
                        $('#toast-error').removeClass('hide').addClass('show');
                        setTimeout(() => {
                            $('#toast-error').removeClass('show').addClass('hide');
                        }, 3000);
                    }
                });
            }

            document.getElementById('country-code-selector').addEventListener('change', function() {
                // console.log("✅ El usuario seleccionó un país", this.value);
                detectar();
            });
            detectar();



            // document.getElementById('verificar-numero').addEventListener('click', function() {
            //     var telefono = document.getElementById('telefono').value;
            //     var codigoPais = document.getElementById('country-code-selector').value;
            //     var digitosPais = document.getElementById('digitos_pais').value;
            //     var csrfToken = '{{ csrf_token() }}';

            //     // Validar que todos los campos estén completos
            //     if (!telefono || !codigoPais || !digitosPais) {
            //         alert('Por favor completa todos los campos antes de verificar.');
            //         return;
            //     }

            //     // Validar longitud del teléfono
            //     if (telefono.length != digitosPais) {
            //         alert('El número de teléfono debe tener exactamente ' + digitosPais + ' dígitos.');
            //         return;
            //     }

            //     var data = {
            //         telefono: telefono,
            //         codigoPais: codigoPais,
            //         digitosPais: digitosPais
            //     };

            //     // Deshabilitar el botón durante la petición
            //     this.disabled = true;
            //     this.textContent = 'Enviando...';

            //     $.ajax({
            //         type: "post",
            //         url: "{{ route('usuario.enviar-codigo-verificacion') }}",
            //         data: data,
            //         dataType: "json",
            //         headers: {
            //             'X-CSRF-TOKEN': csrfToken
            //         },
            //         success: function(response) {
            //             if (response.status === 'success') {
            //                 codigoVerificacion = response.codigo_generado;
            //                 console.log('✅ Código enviado exitosamente');
            //                 // Mostrar modal de verificación
            //                 $('#menu-verificacion').addClass('menu-active');
            //                 $('.menu-hider').addClass('menu-active');
            //             } else {
            //                 console.log('❌ Error en la respuesta:', response);
            //                 alert('Error: ' + (response.errors.telefono ? response.errors
            //                     .telefono[0] : 'Error desconocido'));
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             // console.log("❌ Error: ", xhr.responseJSON, 'skhfkhgkhkjhgkjhs');
            //             var codigo = xhr.responseJSON.codigo_error;
            //             // console.log("❌ Error: ", codigo);
            //             if (codigo == 401 || codigo == 500) {
            //                 $('#menu-verificacion').removeClass('menu-active');
            //                 $('.menu-hider').removeClass('menu-active');
            //                 // $('#codigo-incorrecto').addClass('menu-active');
            //                 $('#verificar-numero').addClass('d-none');
            //                 $('#telefono_verificado').val(0);
            //                 $('#telefono_verificado').val(0);
            //                 // $('#digitos_pais').val('');
            //                 $('#mensaje-toast-error-snackbar').text(xhr.responseJSON.errors
            //                     .general[0]);
            //                 $('#snackbar-error').removeClass('hide').addClass('show');
            //                 setTimeout(() => {
            //                     $('#snackbar-error').removeClass('show').addClass(
            //                         'hide');
            //                 }, 5000);

            //             } else {
            //                 var errorMessage = 'Error al enviar el código de verificación.';
            //                 if (xhr.responseJSON && xhr.responseJSON.errors) {
            //                     var errors = xhr.responseJSON.errors;
            //                     if (errors.telefono) {
            //                         errorMessage = errors.telefono[0];
            //                     } else if (errors.general) {
            //                         errorMessage = errors.general[0];
            //                     }
            //                 }
            //                 alert(errorMessage);
            //             }
            //         },
            //         complete: function() {
            //             // Rehabilitar el botón
            //             document.getElementById('verificar-numero').disabled = false;
            //             document.getElementById('verificar-numero').innerHTML =
            //                 `Verificar <i class="fab fa-whatsapp ms-1"></i><br>{{ isset($usuarioReferido) && $usuarioReferido != '' ? 'Gana Puntos' : '' }} <span id="contador-cuenta-regresiva" class="d-none ms-2">30</span>`;
            //         }
            //     });
            // });



        });

        // Configurar inputs cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            configurarInputsOTP();

            // Event listener para el botón de verificar código
            document.getElementById('verificar-codigo-btn').addEventListener('click', function() {
                verificarCodigo();
            });

            // Event listener para el botón de reenviar código
            document.getElementById('reenviar-codigo').addEventListener('click', function(e) {
                e.preventDefault();

                // Verificar si el botón está deshabilitado (en contador)
                if (this.style.pointerEvents === 'none') {
                    return;
                }

                // Iniciar contador de reenvío
                iniciarContadorReenvio();

                // Cerrar modal actual
                $('#menu-verificacion').removeClass('menu-active');
                $('.menu-hider').removeClass('menu-active');

                // Reenviar código (repetir el proceso de verificación)
                document.getElementById('verificar-numero').click();
            });

            // Event listeners para limpiar contador cuando se cierre el modal
            document.querySelectorAll('.close-menu').forEach(function(closeBtn) {
                closeBtn.addEventListener('click', function() {
                    limpiarContadorReenvio();
                });
            });

            // Limpiar contador cuando se cierre el modal con backdrop
            document.querySelector('.menu-hider').addEventListener('click', function() {
                limpiarContadorReenvio();
            });
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

                    // NO verificar automáticamente - solo cuando se presione el botón
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
                // Enviar código al servidor para verificación
                enviarCodigoVerificacion(codigoCompleto);
                return codigoCompleto;
            } else {
                alert('Por favor completa todos los campos del código');
                return null;
            }
        }

        const steps = document.querySelectorAll('.form-step');

        // Función para enviar código al servidor
        function enviarCodigoVerificacion(codigo) {
            $.ajax({
                type: "post",
                url: "{{ route('usuario.verificar-codigo-otp') }}",
                data: {
                    codigo: codigo,
                    codigo_generado: codigoVerificacion
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Código correcto
                        $('#menu-verificacion').removeClass('menu-active');
                        $('.menu-hider').removeClass('menu-active');
                        $('#codigo-correcto').addClass('menu-active');
                        $('#verificar-numero').addClass('d-none');
                        $('#telefono_verificado').val(1);
                        formData.set('telefono_verificado', 1);
                        formData.delete('digitos_pais');
                        console.log('✅ Teléfono verificado correctamente');

                        setTimeout(() => {
                            $('#codigo-correcto').removeClass('menu-active');
                        }, 2000);

                        $('#step-1').addClass('d-none');
                        $('#step-2').removeClass('d-none');

                        currentStep = 1;
                    } else {
                        // Código incorrecto
                        $('#menu-verificacion').removeClass('menu-active');
                        $('.menu-hider').removeClass('menu-active');
                        $('#codigo-incorrecto').addClass('menu-active');
                        $('#verificar-numero').attr('disabled', true);
                        $('#contador-cuenta-regresiva').removeClass('d-none');
                        contadorCuentaRegresiva();

                        setTimeout(() => {
                            $('#codigo-incorrecto').removeClass('menu-active');
                        }, 3000);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON.errors.codigo[0]);
                    $('#mensaje-toast-error').text(xhr.responseJSON.errors.codigo[0]);
                    $('#toast-error').removeClass('show');
                    $('#toast-error').addClass('show');
                    setTimeout(() => {
                        $('#toast-error').removeClass('show');
                        $('#toast-error').addClass('hide');
                    }, 2000);
                }
            });
        }


        var contadorReintentoVerificacion = 30;
        var contadorReenvioCodigo = 30;
        var intervaloReenvio = null;

        function contadorCuentaRegresiva() {
            var contador = document.getElementById('contador-cuenta-regresiva');
            contador.textContent = contadorReintentoVerificacion;
            contadorReintentoVerificacion--;
            if (contadorReintentoVerificacion < 0) {
                $('#verificar-numero').attr('disabled', false);
                $('#contador-cuenta-regresiva').addClass('d-none');
                contadorReintentoVerificacion = 30;
            }
            setTimeout(contadorCuentaRegresiva, 1000);
        }

        function iniciarContadorReenvio() {
            // Limpiar intervalo anterior si existe
            if (intervaloReenvio) {
                clearInterval(intervaloReenvio);
            }

            // Deshabilitar el botón de reenviar
            var botonReenviar = document.getElementById('reenviar-codigo');
            botonReenviar.style.pointerEvents = 'none';
            botonReenviar.style.opacity = '0.5';

            // Iniciar contador
            contadorReenvioCodigo = 30;
            actualizarTextoReenvio();

            // Configurar intervalo
            intervaloReenvio = setInterval(function() {
                contadorReenvioCodigo--;
                actualizarTextoReenvio();

                if (contadorReenvioCodigo <= 0) {
                    // Restaurar botón
                    clearInterval(intervaloReenvio);
                    intervaloReenvio = null;
                    botonReenviar.style.pointerEvents = 'auto';
                    botonReenviar.style.opacity = '1';
                    botonReenviar.innerHTML = 'Reenviar Codigo';
                }
            }, 1000);
        }

        function actualizarTextoReenvio() {
            var botonReenviar = document.getElementById('reenviar-codigo');
            if (contadorReenvioCodigo > 0) {
                botonReenviar.innerHTML = `Reenviar en ${contadorReenvioCodigo}s`;
            }
        }

        function limpiarContadorReenvio() {
            if (intervaloReenvio) {
                clearInterval(intervaloReenvio);
                intervaloReenvio = null;
            }

            var botonReenviar = document.getElementById('reenviar-codigo');
            botonReenviar.style.pointerEvents = 'auto';
            botonReenviar.style.opacity = '1';
            botonReenviar.innerHTML = 'Reenviar Codigo';
        }
    </script>
@endpush
