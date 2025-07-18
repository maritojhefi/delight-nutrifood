@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Registrate" cabecera="bordeado" />
    <div class="d-flex justify-content-center">
    {{-- <div class="card card-style signin-card bg-24" style="height: 650px;max-height: 920px; width: 400px;"> --}}
    <div class="card card-style signin-card bg-24 py-4" style="min-height: 450px; width: 400px; ">
        <div class="">
            <div class="px-5">
                <div class="d-flex flex-column">
                    <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" class="img mx-auto d-block" style="width:100px" alt="">
                    <p class="mt-2 color-highlight font-26 font-weight-bold text-center ">Bienvenido a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!</p>
                </div>
                <form action="{{ route('usuario.registrar') }}" method="post" id="multiStepForm" novalidate>
                    @csrf
                    <!-- Paso 1: Información Personal -->
                    <div class="form-step" id="step-1">
                        <h5 class="text-center">Paso 1: Información Personal</h5>
                        {{-- Nombre Completo --}}
                        <div class="d-flex flex-column">
                            <label for="name">Nombre Completo</label>
                            <div class="input-style validate-field d-flex flex-row">
                                <i class="fa fa-user ms-2 input-icon mt-3 position-fixed"></i>
                                <div class="d-flex flex-column w-100">
                                    <input type="text" class="form-control w-auto rounded-sm @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        required>
                                </div>

                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- Correo Electrónico --}}
                        <div class="d-flex flex-column">
                            <label for="email">Correo Electrónico</label>
                            <div class="input-style validate-field d-flex flex-row">
                                <i class="fa fa-at ms-2 input-icon mt-3 position-fixed"></i>
                                <div class="d-flex flex-column w-100">
                                    <input type="email" class="form-control rounded-sm @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- Telefono --}}
                        <div class="d-flex flex-column">
                            <label for="telefono">Teléfono (8 digitos)</label>
                            <div class="input-style validate-field d-flex flex-row">
                                <i class="fa fa-phone ms-2 mt-3 position-fixed input-icon"></i>  
                                <div class="d-flex flex-column w-100">
                                    <input type="number" class="form-control rounded-sm @error('telefono') is-invalid @enderror"
                                        id="telefono" name="telefono"
                                        value="{{ old('telefono') }}" required>
                                </div>
                                @error('telefono')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- foto de perfil --}}
                        {{-- <div class="file-data pb-5">
                            <input type="file" id="file-upload" class="upload-file rounded-sm bg-highlight shadow-s rounded-s "
                                accept="image/*" name="foto">
                            <p class="upload-file-text color-white">Subir Foto</p>
                        </div>
                        @error('foto')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <div class="list-group list-custom-large upload-file-data disabled">
                            <img id="image-data" class="mx-auto img-fluid rounded-circle"
                                style="width:150px; display:block; height:150px;">
                            <button id="remove-file-upload" class="rounded md bg-red-dark">
                                <i class="fa fa-cross text-white"></i>
                            </button>
                        </div> --}}
                        
                        {{-- fin foto de perfil --}}

                        {{-- foto de perfil --}}
                        <div class="custom-major-file-container d-flex flex-row justify-content-center" id="major-file-container">
                            <div class="custom-file-upload-container">
                                <div class="file-upload-area" id="uploadArea">
                                    <div class="upload-text">
                                        <strong>Subir Foto</strong><br>
                                        <small>JPEG, PNG, HEIC</small>
                                        <small>(opcional)</small>
                                    </div>
                                    <img class="preview-image" id="previewImage" style="display: none;">
                                    {{-- <button class="remove-button" id="removeButton" type="button">×</button> --}}
                                    <div class="converting-overlay" id="convertingOverlay">
                                        Convirtiendo...
                                    </div>
                                </div>
                                <input type="file" id="fileInput" class="hidden-input" accept="image/*" name="foto">
                                <div class="error-message" id="errorMessage"></div>
                            </div>
                            <button class="remove-button" id="removeButton" type="button">×</button>
                        </div>
                        @error('foto')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        {{-- fin foto de perfil --}}
                        <div class="d-flex justify-content-end">
                            <button type="button"
                            class="btn btn-xs rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-next"
                            id="nextStep1">Siguiente</button>
                        </div>
                    </div>

                    <!-- Paso 2: Detalles Adicionales -->
                    <div class="form-step d-none" id="step-2">
                    {{-- <div class="form-step d-none" id="step-1"> --}}
                        <h5 class="text-center">Paso 2: Detalles Adicionales</h5>
                        <div class="d-flex flex-column">
                            <label for="profesion">Profesión</label>
                            <div class="input-style validate-field d-flex flex-row">
                                <i class="fa fa-briefcase ms-2 mt-3 position-fixed input-icon"></i>
                                <div class="d-flex flex-column w-100">
                                    <input type="text" class="form-control rounded-sm @error('profesion') is-invalid @enderror"
                                        id="profesion" name="profesion" value="{{ old('profesion') }}"
                                        required>
                                </div>        
                                @error('profesion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>        
                        </div>

                        <div class="d-flex flex-column">
                            <label for="nacimiento">Fecha de nacimiento</label>
                            <div class="input-style rounded-sm validate-field d-flex flex-row">
                                <i class="fa fa-calendar ms-2 mt-3 position-absolute input-icon"></i>
                                <div class="d-flex flex-column w-100">
                                    <input type="date" class="form-control rounded-sm" id="nacimiento" name="nacimiento" required>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex flex-column">
                            <label for="direccion">Dirección</label>
                            <div class="input-style validate-field d-flex flex-row align-content-center">
                                <i class="fa fa-map ms-2 m-3 position-absolute input-icon"></i>
                                <div class="d-flex flex-column w-100">
                                    <input type="text" class="form-control rounded-sm @error('direccion') is-invalid @enderror"
                                        id="direccion" name="direccion"
                                        value="{{ old('direccion') }}" required>
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
                        <h5 class="text-center">Paso 3: Seguridad y Otros Detalles</h5>
                        <div class="d-flex flex-column">       
                            <label for="password">Contraseña</label>
                            <div class="input-style validate-field d-flex flex-row">
                                <i class="fa fa-lock ms-2 mt-3 position-absolute input-icon"></i>
                                <div class="d-flex flex-column w-100">
                                    <input type="password" class="form-control rounded-sm @error('password') is-invalid @enderror"
                                        id="password" placeholder="Minimo 4 caracteres" name="password" required>
                                </div>
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
                                        name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-row align-content-center">
                            <label for="hijos">¿Tiene hijos?: </label>
                            <input class="form-check-input ms-1" type="checkbox" name="hijos" id="hijos"
                                value="1">
                        </div>
                        <br>
                        <div class="d-flex justify-content-between align-content-between">
                            <button type="button"
                                class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-red-dark btn-prev"
                                >Anterior</button>
                            <button type="submit"
                                class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-block"
                                >Registrar Cuenta</button>
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
        <div class="text-center" >
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
@endpush
@push('scripts')
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
                            file.name.replace(/\.heic$/i, '.jpg'), 
                            { type: 'image/jpeg' }
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

            // Manejo de los botones de siguiente
            nextBtns.forEach(button => {
                button.addEventListener('click', () => {
                    const currentFormStep = steps[currentStep].querySelectorAll(
                        'input, select, textarea');

                    // Validar solo los campos visibles del paso actual
                    let valid = true;
                    currentFormStep.forEach(input => {
                        if (!input.checkValidity()) {
                            input.reportValidity();
                            valid = false;
                        }
                    });

                    // Si el primer paso es válido, verificar si el usuario ya existe
                    if (valid && currentStep === 0) {
                        const email = document.getElementById('email').value;

                        // Verificación AJAX del usuario por correo
                        fetch('{{ route('usuario.existe') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    email: email
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.exists) {
                                    // Si el usuario ya existe, mostrar modal de usuario existente
                                    $('#menu-existe').addClass('menu-active');
                                    $('.menu-hider').addClass('menu-active');

                                    // Esperar a que el usuario cierre el modal para avanzar al siguiente paso
                                    $('.close-menu').on('click', function() {
                                        $('#menu-existe').removeClass('menu-active');
                                        $('.menu-hider').removeClass('menu-active');

                                        // Continuar al siguiente paso
                                        steps[currentStep].classList.add('d-none');
                                        currentStep++;
                                        steps[currentStep].classList.remove('d-none');
                                    });
                                } else {
                                    // Si no existe, pasar al siguiente paso directamente
                                    steps[currentStep].classList.add('d-none');
                                    currentStep++;
                                    steps[currentStep].classList.remove('d-none');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    } else if (valid) {
                        // Si no es el primer paso, simplemente avanzar al siguiente
                        steps[currentStep].classList.add('d-none');
                        currentStep++;
                        steps[currentStep].classList.remove('d-none');
                    }
                });
            });

            // Botón para retroceder pasos
            prevBtns.forEach(button => {
                button.addEventListener('click', () => {
                    steps[currentStep].classList.add('d-none');
                    currentStep--;
                    steps[currentStep].classList.remove('d-none');
                });
            });

            // Manejo del envío del formulario
            const form = document.getElementById('multiStepForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Evitar el envío tradicional

                const formData = new FormData(form);
                console.log("Enviado en el formulario: ",formData);
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
                            // Limpiar mensajes de error previos
                            document.querySelectorAll('.text-danger').forEach(el => el.remove());

                            const errors = data.errors;
                            let firstErrorStep = 0;
                            $('.menu-hider').addClass('menu-active');
                            $('#menu-errores').addClass('menu-active');
                            if (Array.isArray(errors)) {
                                // Si es un array, concatenar los errores en un solo string
                                errorMessages = errors.map(error => `<li>${error}</li>`).join('');
                            } else if (typeof errors === 'object') {
                                // Si es un objeto, recorrer cada propiedad y concatenar
                                errorMessages = Object.values(errors)
                                    .flat() // Aplana posibles arrays anidados
                                    .map(error => `<li>${error}</li>`)
                                    .join('');
                            } else {
                                // Si no es ni array ni objeto, mostrarlo directamente
                                errorMessages = `<li>${errors}</li>`;
                            }
                            // Mostrar errores en la lista de errores
                            $('#error-registro').html(
                                `<ul class="list-group text-danger">${errorMessages}</ul>`);
                            // Mostrar errores y encontrar el paso con el primer error
                            for (const [field, messages] of Object.entries(errors)) {
                                const inputField = document.querySelector(`[name="${field}"]`);

                                // Crear el div para los mensajes de error
                                const errorDiv = document.createElement('div');
                                errorDiv.classList.add(
                                    'font-10'); // Añadir otras clases si es necesario
                                errorDiv.innerText = messages.join(', ');

                                // Aplicar estilo en línea con !important
                                errorDiv.style.cssText = 'color: red !important;';

                                // Insertar el div de error después del input
                                inputField.parentElement.appendChild(errorDiv);

                                // Determinar el paso del error
                                if (['name', 'email', 'telefono'].includes(field)) {
                                    firstErrorStep = Math.min(firstErrorStep, 0); // Paso 1
                                } else if (['profesion', 'dia_nacimiento', 'mes_nacimiento',
                                        'ano_nacimiento', 'direccion', 'direccion_trabajo'
                                    ].includes(field)) {
                                    firstErrorStep = Math.min(firstErrorStep, 1); // Paso 2
                                } else if (['password', 'password_confirmation'].includes(field)) {
                                    firstErrorStep = Math.min(firstErrorStep, 2); // Paso 3
                                }
                            }

                            // Relocalizar al primer paso con error
                            steps[currentStep].classList.add('d-none');
                            currentStep = firstErrorStep;
                            steps[currentStep].classList.remove('d-none');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endpush
