@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Editar Perfil" cabecera="appkit" />


        @if (session('actualizado'))
            <div class="alert me-3 ms-3 rounded-s bg-green-dark " role="alert">
                <span class="alert-icon"><i class="fa fa-check font-18"></i></span>
                <h4 class="text-uppercase color-white mb-1">¡Hecho!</h4>
                <strong class="alert-icon-text">Foto actualizada.</strong>

                <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
                    aria-label="Close">×</button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert me-3 ms-3 rounded-s bg-green-dark " role="alert">
                <span class="alert-icon"><i class="fa fa-check font-18"></i></span>
                <h4 class="text-uppercase color-white mb-1">¡Hecho!</h4>
                <strong class="alert-icon-text">Perfil Actualizado</strong>

                <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
                    aria-label="Close">×</button>
            </div>
        @endif

        <div class="card card-style">
            <div class="content">
                <div class="d-flex flex-row align-items-center gap-3 justify-content-evenly">
                    <div>
                        @if ($usuario->foto)
                            <img src="{{ $usuario->pathFoto }}" width="70" height="70" class="bg-highlight rounded-xl">
                        @else
                            <img src="{{ asset('user.png') }}" width="70" height="70" class="bg-highlight rounded-xl">
                        @endif
                    </div>
                    <div>
                        <h1 class="mb-0 pt-1">{{ auth()->user()->name }}</h1>
                    </div>
                </div>
                <!-- <p class="color-highlight font-11 mt-n1 mb-0">Información cifrada, no revelaremos esto con nadie</p> -->
                @if (!$usuario->tienePerfilCompleto())
                    <p class="color-highlight font-500 mt-3">
                        Actualiza los campos resaltados para completar tu perfil.
                    </p>
                @endif
                
            </div>
        </div>

        <card id="card-control-numero" class="card card-style py-2 @if(!$usuario->verificado) highlight-incomplete-card @endif">
            <div class="card-header bg-theme border-0">
                <h2 class="mb-0">Número de Contacto</h2>
                <!-- <small class="text-muted">Ingrese su nuevo número para recibir código de verificación</small> -->
            </div>

            <form id="form-cambiar-telefono">
                @csrf
                @method('PUT')

                <div class="card-body">
                    @if (!$usuario->verificado)
                        <p class="mx-1 mt-n3 mb-3 line-height-s">Tu WhatsApp no ha sido verificado, hazlo a continuación:</p>
                    @endif
                    <div class="d-flex flex-row gap-2 justify-content-evenly align-items-center">
                        <!-- Selector de Código de País -->
                        <div class="d-flex flex-column position-relative" style="min-width: 35%;">
                            <label for="selector-codigo_pais-perfil"
                                class="position-absolute d-inline-block font-13 color-highlight line-height-xs bg-theme ms-2 px-1"
                                style="z-index: 10; width: fit-content; top: -7px">
                                Código país
                            </label>
                            <x-countrycode-select id="selector-codigo_pais-perfil" name="codigo_pais" />
                        </div>

                        <!-- Input de Teléfono -->
                        <div class="input-style has-borders has-icon input-style-always-active validate-field remove-mb"
                            style="min-width: 59%;">
                            <i data-lucide="smartphone" class="lucide-icon lucide-input"></i>
                            <input type="tel"
                                class="form-control text-center font-14"
                                placeholder="Ingrese su número"
                                name="telefono-nacional"
                                id="telefono-nacional"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                autocomplete="tel-national"
                                style="height: 40px;"
                                value="{{ old('telefono-nacional', $usuario->telf) }}"
                                required>
                            <label for="telefono-nacional"
                                class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">
                                Teléfono
                            </label>
                        </div>
                    </div>
                    <!-- Campo oculto para dígitos esperados (útil para backend) -->
                    <input type="hidden" name="digitos_esperados" id="digitos_esperados">
                </div>

                <!-- Footer con botón de submit -->
                <div class="card-footer bg-theme border-0 pt-0">
                    <button type="submit"
                            id="btn-guardar-telefono"
                            class="btn w-100 btn-m bg-highlight rounded-s d-flex flex-row align-items-center justify-content-center gap-1 text-uppercase font-900 shadow-s d-none"
                            disabled>
                        <!-- <i data-lucide="message-circle-more" class="lucide-icon" style="width: 1rem; height: 1rem;"></i> -->
                        Verificar Número
                    </button>

                    <!-- Indicador de carga (opcional) -->
                    <div id="loading-validacion" class="d-none text-center py-2">
                        <div class="spinner-border spinner-border-sm text-highlight me-2" role="status">
                            <span class="visually-hidden">Validando...</span>
                        </div>
                        <small class="text-muted">Validando número...</small>
                    </div>
                </div>
            </form>
        </card>

        <!-- @if (true)
            <div class="card card-style bg-light-warning m-0 shadow-xs">
                <p class="mb-0">Para completar tu perfil, debes completar los siguientes campos:</p>
                <ul class="mb-0">
                    @foreach ([1,2,3] as $dato)
                        <li>Campos: {{ $dato }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="mb-0">
                Por favor llena con el máximo detalle los siguientes campos.
            </p>
        @endif -->

        <div id="todoBien">

            <div class="card card-style">
                <form action="{{ route('guardarPerfilFaltante') }}" method="post">
                    <div class="content">
                        <h3 class="font-600">Información de tu perfil</h3>
                        <p class="mb-0">
                            Por favor llena con el máximo detalle los siguientes campos.
                        </p>

                        @csrf

                        <br>
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4 @if(empty($usuario->name)) highlight-incomplete @endif">
                            <i data-lucide="user" class="lucide-icon lucide-input"></i>
                            <input type="text" class="form-control"
                                placeholder="Nombre" name="name"
                                id="name_input"
                                value="{{ old('name', $usuario->name) }}">
                            <label for="name_input" class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">Nombre</label>
                        </div>
                        @error('name')
                            <p class="text-danger line-height-s mx-2 mt-n2">
                                <i class="fa fa-times invalid color-red-dark  me-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4">
                            <!-- <i class="fa fa-envelope"></i> -->
                            <i data-lucide="mail" class="lucide-icon lucide-input"></i>
                            <input type="email" class="form-control" placeholder="" name="email"
                                value="{{ old('email', $usuario->email) }}">
                            <label for="name_input" class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">Email</label>
                        </div>
                        @error('email')
                            <p class="text-danger line-height-s mx-2 mt-n2">
                                <i class="fa fa-times invalid color-red-dark  me-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="d-flex flex-column mb-3 mt-n1 @if(empty($usuario->nacimiento)) highlight-incomplete-wrapper @endif">
                            <label for="nacimiento" class="ms-2 mb-n2 d-inline-block px-1 line-height-xs color-highlight bg-theme font-400 font-13"
                                style="z-index: 10;width:fit-content">Fecha de nacimiento</label>
                            <div class="input-style has-borders remove-mb rounded-sm validate-field d-flex flex-row gap-2">
                                {{-- Campo Día --}}
                                <input type="number" class="text-center form-control"
                                    placeholder="Día" name="dia_nacimiento" required
                                    min="1" max="31"
                                    value="{{ old('dia_nacimiento', $usuario->nacimiento ? explode('-', $usuario->nacimiento)[2] : '') }}"
                                    oninput="if(this.value > 31) this.value = 31; if(this.value < 1) this.value = 1;"
                                >

                                {{-- Campo Mes (Select) --}}
                                <select class="text-center form-control" name="mes_nacimiento" required>
                                    <option value="" disabled selected>Mes</option>
                                    @php
                                        $selectedMonth = $usuario->nacimiento ? explode('-', $usuario->nacimiento)[1] : 1;
                                    @endphp
                                    <option value="1" @if(old('mes_nacimiento', $selectedMonth) == '01' || old('mes_nacimiento', $selectedMonth) == '1') selected @endif>Enero</option>
                                    <option value="2" @if(old('mes_nacimiento', $selectedMonth) == '02' || old('mes_nacimiento', $selectedMonth) == '2') selected @endif>Febrero</option>
                                    <option value="3" @if(old('mes_nacimiento', $selectedMonth) == '03' || old('mes_nacimiento', $selectedMonth) == '3') selected @endif>Marzo</option>
                                    <option value="4" @if(old('mes_nacimiento', $selectedMonth) == '04' || old('mes_nacimiento', $selectedMonth) == '4') selected @endif>Abril</option>
                                    <option value="5" @if(old('mes_nacimiento', $selectedMonth) == '05' || old('mes_nacimiento', $selectedMonth) == '5') selected @endif>Mayo</option>
                                    <option value="6" @if(old('mes_nacimiento', $selectedMonth) == '06' || old('mes_nacimiento', $selectedMonth) == '6') selected @endif>Junio</option>
                                    <option value="7" @if(old('mes_nacimiento', $selectedMonth) == '07' || old('mes_nacimiento', $selectedMonth) == '7') selected @endif>Julio</option>
                                    <option value="8" @if(old('mes_nacimiento', $selectedMonth) == '08' || old('mes_nacimiento', $selectedMonth) == '8') selected @endif>Agosto</option>
                                    <option value="9" @if(old('mes_nacimiento', $selectedMonth) == '09' || old('mes_nacimiento', $selectedMonth) == '9') selected @endif>Septiembre</option>
                                    <option value="10" @if(old('mes_nacimiento', $selectedMonth) == '10') selected @endif>Octubre</option>
                                    <option value="11" @if(old('mes_nacimiento', $selectedMonth) == '11') selected @endif>Noviembre</option>
                                    <option value="12" @if(old('mes_nacimiento', $selectedMonth) == '12') selected @endif>Diciembre</option>
                                </select>

                                {{-- Campo Año --}}
                                @php
                                    $minAge = 0; // Edad mínima requerida
                                    $maxYear = date('Y') - $minAge;
                                @endphp
                                <input type="number" class="text-center form-control" placeholder="Año"
                                    name="ano_nacimiento" required
                                    max="{{ $maxYear }}"
                                    value="{{ old('ano_nacimiento', $usuario->nacimiento ? explode('-', $usuario->nacimiento)[0] : '') }}"
                                    oninput="const maxYear = {{ $maxYear }}; if(this.value > maxYear) this.value = maxYear; if(this.value.length > 4) this.value = this.value.slice(0, 4);"
                                >
                            </div>

                            {{-- Bloque de Error Condicional Único --}}
                            @if ($errors->hasAny(['dia_nacimiento', 'mes_nacimiento', 'ano_nacimiento']))
                                <p class="text-danger line-height-s mx-2 mt-2">
                                    <i class="fa fa-times invalid color-red-dark me-2"></i>
                                    Por favor, ingresa una fecha valida (debes ser mayor de 12 años).
                                </p>
                            @endif
                        </div>
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4">
                            <i data-lucide="briefcase-business" class="lucide-icon lucide-input"></i>
                            <input type="text" class="form-control"
                                placeholder="" name="profesion"
                                value="{{ old('profesion', $usuario->profesion) }}">
                            <label for="name_input" class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">Profesión</label>
                        </div>
                        @error('profesion')
                            <p class="text-danger line-height-s mx-2 mt-n2">
                                <i class="fa fa-times invalid color-red-dark  me-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4 @if(empty($usuario->direccion)) highlight-incomplete @endif">
                            <i data-lucide="map-pin-house" class="lucide-icon lucide-input"></i>
                            <input type="text" class="form-control"
                                placeholder="" name="direccion"
                                value="{{ old('direccion', $usuario->direccion) }}">
                            <label for="name_input" class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">Dirección</label>
                        </div>
                        @error('direccion')
                            <p class="text-danger line-height-s mx-2 mt-n2">
                                <i class="fa fa-times invalid color-red-dark  me-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4">
                            <i data-lucide="map-pin-pen" class="lucide-icon lucide-input"></i>
                            <input type="text" class="form-control" maxlength="50"
                                placeholder="" name="direccion_trabajo"
                                value="{{ old('direccion_trabajo', $usuario->direccion_trabajo) }}">
                            <label for="name_input" class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">Dirección de trabajo</label>
                        </div>
                        @error('direccion_trabajo')
                            <p class="text-danger line-height-s mx-2 mt-n2">
                                <i class="fa fa-times invalid color-red-dark  me-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i data-lucide="users" class="lucide-icon ms-2" style="width: 1.2rem; height: 1.2rem;"></i>
                            <label for="hijos" class="mx-2 color-highlight font-400 font-13">
                                ¿Tiene hijos?:
                            </label>

                            <input
                                class="form-check-input m-0"
                                type="checkbox"
                                name="hijos"
                                id="hijos"
                                value="1"

                                @if (old('hijos', $usuario->hijos)) checked @endif
                            >

                        </div>
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4 position-relative">
                            <i data-lucide="key-round" class="lucide-icon lucide-input"></i>
                            <input type="password" class="form-control password-input-toggle" placeholder="" name="password" id="new-password">
                            <label for="name_input" class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">Nueva Contraseña</label>
                            <button type="button"
                                    class="p-0 m-0 position-absolute end-0 me-3 top-50 translate-middle-y password-toggle-btn"
                                    id="togglePasswordBtn" aria-label="Mostrar/Ocultar contraseña">
                                <i data-lucide="lock" id="toggleIconNew" class="lucide-icon" style="width: 1.1rem; height: 1.1rem;"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-danger line-height-s mx-2 mt-n2">
                                <i class="fa fa-times invalid color-red-dark  me-2"></i>
                                {{ $message }}
                            </p>
                        @enderror


                        <input type="hidden" value="{{ $usuario->id }}" name="idUsuario">
                        <input type="hidden" id="latitud" name="latitud">
                        <input type="hidden" id="longitud" name="longitud">
                        <button type="submit"
                            class="btn w-100 bg-highlight rounded-sm shadow-xl btn-m text-uppercase font-900">
                            Guardar Perfil
                        </button>
                    </div>
                </form>
            </div>
            {{-- <div class="card card-style mb-2 map-full" data-card-height="cover-card" style="height: 573px;">
                <h3 class="m-3 text-center"> Ubique su domicilio detalladamente</h3>
                <div id="map" style="width:100%;height:600px;"></div>
            </div> --}}
            <div class="card card-style">
                <div class="content">
                    <h4>Foto de perfil</h4>
                    <p class="mb-0 line-height-s">
                        @if ($usuario->foto)
                            {{ 'Puedes actualizar tu foto si así lo deseas.' }}
                        @else
                            {{ 'Subir una foto tuya nos ayudará a conocerte mejor.' }}
                        @endif
                    </p>
                    <!-- FORMULARIO INTEGRADO CON CONTROL DE FOTO -->
                    <form action="{{ route('subirfoto.perfil') }}" method="POST" enctype="multipart/form-data" id="photoUploadForm">
                        @csrf

                        <div class="custom-major-file-container d-flex flex-row justify-content-center mt-3" id="major-file-container">
                            <div class="custom-file-upload-container">
                                <div class="file-upload-area" id="uploadArea">
                                    <div class="upload-text">
                                        <strong>Subir Foto</strong><br>
                                        <small>JPEG, PNG, HEIC</small>
                                    </div>
                                    <img class="preview-image" id="previewImage" style="display: none;">
                                    <div class="converting-overlay" id="convertingOverlay">
                                        Convirtiendo...
                                    </div>
                                </div>
                                <input type="file" id="fileInput" class="hidden-input" accept="image/*" name="foto">
                                <div class="error-message" id="errorMessage"></div>
                            </div>
                            <button class="remove-button" id="removeButton" type="button" style="display: none;">×</button>
                        </div>

                        @error('foto')
                            <p class="text-danger text-center mt-2">{{ $message }}</p>
                        @enderror

                        <!-- Botón de confirmación que aparece cuando hay imagen -->
                        <div class="text-center mt-3" id="submitButtonContainer" style="display: none;">
                            <button type="submit" class="btn btn-m w-100 bg-highlight rounded-sm shadow-xl text-uppercase font-900">
                                Guardar Foto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <div id="errorMapa" class="displayNone">
            <div class="card card-style  round-medium " style="height: 380px;">
                <img src="{{ asset('errorMapa.png') }}" class="card-image " style="height: 430px;">
                <div class="card-bottom ms-3 mb-2">
                    <h2 class="font-700 color-white">Concede el permiso</h2>
                    <p class="color-white mt-n2 mb-0">Haz click en el icono del candado en la parte de la url de tu
                        navegador, permite el acceso y
                        recarga la pagina!</p>
                </div>
                <div class="card-overlay bg-black opacity-30"></div>
            </div>
            <div class="alert me-3 ms-3 rounded-s bg-red-dark " role="alert">
                <span class="alert-icon"><i class="fa fa-times-circle font-18"></i></span>
                <h4 class="text-uppercase color-white">Active el GPS</h4>
                <strong class="alert-icon-text">Necesitamos permiso de su ubicacion.</strong>

                <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
                    aria-label="Close">×</button>
            </div>
        </div>
        <div id="toast-3" class="toast toast-tiny toast-top bg-blue-dark fade hide" data-bs-delay="1500"
            data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Actualizado!</div>
        <div id="toast-4" class="toast toast-tiny toast-top bg-red-dark fade hide" data-bs-delay="4000"
            data-bs-autohide="true">
            <i class="fa fa-times me-3"></i>Active su GPS
        </div>

    @push('modals')

    <x-modal-otp-whatsapp funcionalidad="cambionumero" />

    @endpush

    @push('header')
        <!-- ESTILOS SLIM-SELECT -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slim-select@2/dist/slimselect.min.css" />
        <style>
            .displayNone {
                display: none
            }

            .dislayFull {
                display: contents
            }

            /* Resaltado de campos necesarios para completar el perfil */
            .highlight-incomplete {
                border: 2px solid #01f9c6 !important;
                border-radius: 0.7rem;
                box-shadow: 0 0 0 3px rgba(102, 204, 204, 0.5);
            }

            .highlight-incomplete-card {
                border: 2px solid #01f9c6 !important;
                box-shadow: 0 0 0 3px rgba(102, 204, 204, 0.5);
            }

            .highlight-incomplete input,
            .highlight-incomplete select {
                border-color: #01f9c6 !important;
            }

            .highlight-incomplete-wrapper {
                padding: 0.5rem;
                border: 2px solid #01f9c6;
                border-radius: 0.5rem;
                box-shadow: 0 0 0 3px rgba(102, 204, 204, 0.5);
            }

            /* Target the div holding the selected value text */
            .ss-main .ss-single {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }

            .theme-dark .ss-single {
                color: white;
            }

            .theme-dark .ss-option {
                color: white !important;
            }

            .ss-option.ss-selected {
                border-radius: 0.4rem;
                background-color: #7db1b1 !important;
            }

            .ss-search input {
                border-radius: 0.4rem;
            }
            .theme-dark .ss-search input {
                background-color:  #0f1117 !important;
            }

            .theme-dark .ss-content {
                background-color:  #0f1117 !important;
                /* background-color:  #1f2937 !important; */
            }

            /* Optional: Ensure the outer wrapper respects any width constraints */
            .ss-main {
                height: 40px;
                box-shadow: none !important;
                border-color: #00000014;
                transition: none;
                /* If you want a fixed min-width for the selector, define it here */
                /* Example: min-width: 100px; */
                /* Ensure no wrapping within the main container */
                overflow: hidden;
            }

            .theme-dark .ss-main {
                border-color: #ffffff14 !important;
            }

        </style>
        <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC059fhEiwhAxE0iFJ2mDLac1HPtOWLY4Y" async defer></script> -->
    @endpush
    @push('scripts')
        <!-- SCRIPT SLIMSELECT -->
        <script src="https://cdn.jsdelivr.net/npm/slim-select@2/dist/slimselect.min.js"></script>
        <!-- SCRIPT LIBPHONENUMBER -->
        <script src="https://cdn.jsdelivr.net/npm/google-libphonenumber/dist/libphonenumber.js"></script>
        <script>
        $(document).ready(function() {
            // ============= ESTADO DE LA APLICACIÓN =============
            const estado = {
                digitosEsperados: null,
                validacionEnCurso: false,
                temporizadorValidacion: null,
                codigoVerificacion: null
            };

            // ============= ELEMENTOS DEL DOM =============
            const elementos = {
                selectorPais: $('#selector-codigo_pais-perfil'),
                inputTelefono: $('#telefono-nacional'),
                botonGuardar: $('#btn-guardar-telefono'),
                formularioCambiarNumero: $('#form-cambiar-telefono'),
                modalOTP: $('#menu-verificacion-cambionumero'),
                ocultadorMenu: $('.menu-hider'),
                modalExito: $('#codigo-correcto'),
                cardControlNumero: $('#card-control-numero')
            };

            // ============= INICIALIZACIÓN =============
            const iniciarOTPContrasena = () => {
                inicializarSelectorPais();
                configurarEventos();
                detectarDigitosPais();
                if ({{ !$usuario->verificado }}) {
                    mostrarBotonGuardar();
                }
            };

            // ============= INICIALIZACIÓN DE SLIMSELECT =============
            const inicializarSelectorPais = () => {
                if (!elementos.selectorPais.length) return;

                elementos.selectorPais.css('display', '');

                new SlimSelect({
                    select: '#selector-codigo_pais-perfil',
                    settings: {
                        placeholder: 'Seleccione un país',
                        searchPlaceholder: 'Buscar país...',
                        searchText: 'Sin resultados',
                        allowDeselect: false,
                        showSearch: true,
                    }
                });

                // Establecer el código de país del usuario como predeterminado
                setTimeout(() => {
                    const elementoSelect = elementos.selectorPais.get(0);
                    if (!elementoSelect.value || elementoSelect.value === '') {
                        elementoSelect.value = '{{ ltrim($usuario->codigo_pais, '+') }}';
                        elementoSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }, 150);
            };

            // ============= CONFIGURACIÓN DE EVENTOS =============
            const configurarEventos = () => {
                // Cambio de país
                elementos.selectorPais.get(0).addEventListener('change', manejarCambioPais);

                // Entrada de teléfono (con espera)
                elementos.inputTelefono.on('input', manejarEntradaTelefono);

                // Click en botón guardar
                elementos.formularioCambiarNumero.on('submit', function (e) {
                    e.preventDefault();
                    enviarOTPParaCambioNumero();
                });
            };

            const manejarCambioPais = () => {
                detectarDigitosPais();
                elementos.inputTelefono.val('');
                ocultarBotonGuardar();

                if (estado.temporizadorValidacion) {
                    clearTimeout(estado.temporizadorValidacion);
                }
            };

            const manejarEntradaTelefono = function() {
                const valorActual = $(this).val();
                const longitud = valorActual.length;

                // // console.log(`Caracteres: ${longitud}/${estado.digitosEsperados}`);

                // Limpiar validación previa
                if (estado.temporizadorValidacion) {
                    clearTimeout(estado.temporizadorValidacion);
                }

                // Validar cuando se alcance la longitud esperada
                if (estado.digitosEsperados && longitud === estado.digitosEsperados) {
                    estado.temporizadorValidacion = setTimeout(() => {
                        validarTelefonoRemoto(valorActual);
                    }, 500);
                } else {
                    ocultarBotonGuardar();
                }
            };

            const manejarClickGuardar = (e) => {
                e.preventDefault();
                enviarOTPParaCambioNumero();
            };

            // ============= DETECCIÓN DE PAÍS =============
            const detectarDigitosPais = () => {
                const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();
                const codigoPais = parseInt(elementos.selectorPais.val());

                try {
                    const regiones = phoneUtil.getRegionCodesForCountryCode(codigoPais);

                    if (!regiones || regiones.length === 0) {
                        console.warn('No se encontraron regiones para el código:', codigoPais);
                        // Asegurar que no hay límite si no hay datos
                        elementos.inputTelefono.removeAttr('maxlength');
                        estado.digitosEsperados = null;
                        return;
                    }

                    for (const region of regiones) {
                        const ejemplo = phoneUtil.getExampleNumberForType(
                            region,
                            libphonenumber.PhoneNumberType.MOBILE
                        );

                        if (ejemplo) {
                            const numeroEjemplo = phoneUtil.getNationalSignificantNumber(ejemplo);
                            estado.digitosEsperados = numeroEjemplo.length;

                            elementos.inputTelefono.attr('maxlength', estado.digitosEsperados);

                            // // console.log(`Código +${codigoPais} → Región: ${region}, Dígitos esperados: ${estado.digitosEsperados}`);
                            return;
                        }
                    }
                } catch (e) {
                    console.error('Error al detectar dígitos:', e.message);
                    mostrarError('Error al configurar la validación del país');
                }
            };

            // ============= VALIDACIÓN REMOTA =============
            const validarTelefonoRemoto = async (telefono) => {
                if (estado.validacionEnCurso) return;
                estado.validacionEnCurso = true;

                const codigoPais = elementos.selectorPais.val();

                try {
                    const respuesta = await axios.post('{{ route("usuario.verificar-numero") }}', {
                        telefono,
                        codigoPais,
                        digitosPais: estado.digitosEsperados
                    }, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    if (respuesta.data.status === 'success') {
                        mostrarBotonGuardar();
                        mostrarExito('Número válido');
                    } else {
                        ocultarBotonGuardar();
                        const mensajeError = obtenerMensajeError(respuesta.data.errors);
                        mostrarError(mensajeError);
                    }

                } catch (error) {
                    console.error('Error en la validación:', error);
                    ocultarBotonGuardar();

                    let mensaje = 'Error al validar el teléfono';
                    if (error.response?.data?.errors) {
                        mensaje = obtenerMensajeError(error.response.data.errors);
                    } else if (error.response?.data?.message) {
                        mensaje = error.response.data.message;
                    }

                    mostrarError(mensaje);
                } finally {
                    estado.validacionEnCurso = false;
                }
            };

            // ============= GESTIÓN DEL OTP =============
            const enviarOTPParaCambioNumero = async () => {
                const telefono = elementos.inputTelefono.val();
                const codigoPais = elementos.selectorPais.val();

                try {
                    deshabilitarBotonEnviarOTP();
                    const respuesta = await axios.post('{{ route("usuario.enviar-codigo-verificacion") }}', {
                        telefono,
                        codigoPais,
                        digitosPais: estado.digitosEsperados,
                        operacion: 'cambio_telefono_perfil'
                    }, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    if (respuesta.data.status === "success") {
                        estado.codigoVerificacion = respuesta.data.codigo_generado;
                        configurarEventosOTP();
                        mostrarModalOTP();
                    }
                } catch (error) {
                    let mensajeError = 'Error inesperado, por favor intenta más tarde.';

                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        const primeraClave = Object.keys(errors)[0];

                        if (primeraClave && Array.isArray(errors[primeraClave]) && errors[primeraClave].length > 0) {
                            mensajeError = errors[primeraClave][0];
                        }
                    }
                    // Mostrar mensaje en snackbar
                    $('#mensaje-toast-error-snackbar').text(mensajeError);
                    $('#snackbar-error').addClass('show');

                    setTimeout(() => {
                        $('#snackbar-error').removeClass('show');
                    }, 3000);

                    console.error("Error al enviar el código de verificacion OTP:", error);
                }

                habilitarBotonEnviarOTP();
            };

            const configurarEventosOTP = () => {
                // Botón de verificación
                $('#verificar-codigo-btn-cambionumero').off('click').on('click', () => {
                    validarOTPParaCambioNumero();
                });

                // Botón de reenvío
                $('#reenviar-codigo').off('click').on('click', (e) => {
                    e.preventDefault();
                    enviarOTPParaCambioNumero();
                });
            };

            const validarOTPParaCambioNumero = async () => {
                const formulario = '#form-codigo-verificacion-cambionumero';
                const inputs = document.querySelectorAll(`${formulario} .otp`);
                let codigoCompleto = '';

                inputs.forEach(input => {
                    const valor = input.value.trim();
                    if (valor) codigoCompleto += valor;
                });

                if (codigoCompleto.length !== 5) {
                    alert('Por favor complete todos los campos del código');
                    return;
                }

                const telefono = elementos.inputTelefono.val();
                const codigoPais = elementos.selectorPais.val();

                try {
                    deshabilitarBotonVerifiacionOTP();

                    const respuesta = await axios.post('{{ route("usuario.cambiar-numero-otp") }}', {
                        codigo: codigoCompleto,
                        codigoGenerado: estado.codigoVerificacion,
                        nuevoTelefonoNacional: telefono,
                        nuevoCodigoPais: codigoPais,
                        userId: {{ $usuario->id }}
                    }, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    if (respuesta.data.status === "success") {
                        ocultarModalOTP();
                        elementos.modalExito.addClass('menu-active');
                        elementos.cardControlNumero.removeClass('highlight-incomplete-card');

                        setTimeout(() => {
                            elementos.modalExito.removeClass('menu-active');
                            window.location.reload();
                        }, 2000);
                    }

                } catch (error) {
                    let mensajeError = 'Error inesperado, por favor intenta más tarde.';

                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        const primeraClave = Object.keys(errors)[0];

                        if (primeraClave && Array.isArray(errors[primeraClave]) && errors[primeraClave].length > 0) {
                            mensajeError = errors[primeraClave][0];
                        }
                    }

                    $('#mensaje-toast-error-snackbar').text(mensajeError);
                    $('#snackbar-error').addClass('show');

                    setTimeout(() => {
                        $('#snackbar-error').removeClass('show');
                    }, 3000);

                    console.error("Error al validar el OTP:", error);
                }
                habilitarBotonVerifiacionOTP();
            };

            // ============= UTILIDADES DE INTERFAZ =============
            const mostrarBotonGuardar = () => {
                elementos.botonGuardar.prop('disabled', false).removeClass('d-none');
                elementos.inputTelefono.removeClass('is-invalid').addClass('is-valid');
            };

            const ocultarBotonGuardar = () => {
                elementos.botonGuardar.prop('disabled', true).addClass('d-none');
                elementos.inputTelefono.removeClass('is-valid is-invalid');
            };

            const mostrarModalOTP = () => {
                elementos.ocultadorMenu.addClass('menu-active');
                elementos.modalOTP.addClass('menu-active');
            };

            const ocultarModalOTP = () => {
                elementos.modalOTP.removeClass('menu-active');
                elementos.ocultadorMenu.removeClass('menu-active');
            };

            const deshabilitarBotonEnviarOTP = () => {
                $('#btn-guardar-telefono').attr('disabled', true);
                $('#btn-guardar-telefono').text('Enviando...');
            }

            const habilitarBotonEnviarOTP = () => {
                $('#btn-guardar-telefono').attr('disabled', false);
                $('#btn-guardar-telefono').text('Verificar Número');
            }

            const mostrarError = (mensaje) => {
                elementos.inputTelefono.addClass('is-invalid');
                $('#mensaje-toast-error').text(mensaje);
                $('#toast-error').removeClass('hide').addClass('show');
                setTimeout(() => {
                    $('#toast-error').removeClass('show').addClass('hide');
                }, 3000);
            };

            const mostrarExito = (mensaje) => {
                $('#mensaje-toast-success').text(mensaje);
                $('#toast-success').removeClass('hide').addClass('show');
                setTimeout(() => {
                    $('#toast-success').removeClass('show').addClass('hide');
                }, 1500);
            };

            const obtenerMensajeError = (errores) => {
                if (errores.telefono) return errores.telefono[0];
                if (errores.general) return errores.general[0];
                return 'Error de validación';
            };

            // ============= INICIO DE LA APLICACIÓN =============
            iniciarOTPContrasena();
        });
        </script>
        <script>
            $(document).ready(function() {
                const passwordInput = $('#new-password');
                const toggleBtn = $('#togglePasswordBtn');

                toggleBtn.on('click', function() {
                    const isPassword = passwordInput.attr('type') === 'password';

                    passwordInput.attr('type', isPassword ? 'text' : 'password');

                    const toggleIcon = $('#toggleIconNew');

                    toggleIcon.attr('data-lucide', isPassword ? 'lock-open' : 'lock');

                    reinitializeLucideIcons();
                });
            });
        </script>
        <!-- CONTROL ACTUALIZACIÓN DE FOTO -->
        <script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
        <!-- SCRIPT DE CONTROL -->
        <script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/heic'];

                const form = document.getElementById('photoUploadForm');
                const majorContainer = document.getElementById('major-file-container');
                const uploadArea = document.getElementById('uploadArea');
                const fileInput = document.getElementById('fileInput');
                const previewImage = document.getElementById('previewImage');
                const removeButton = document.getElementById('removeButton');
                const errorMessage = document.getElementById('errorMessage');
                const convertingOverlay = document.getElementById('convertingOverlay');
                const uploadText = uploadArea.querySelector('.upload-text');
                const submitButtonContainer = document.getElementById('submitButtonContainer');

                // Click en el área de upload
                uploadArea.addEventListener('click', function(e) {
                    // Prevenir click si ya hay imagen
                    if (!uploadArea.classList.contains('has-image')) {
                        fileInput.click();
                    }
                });

                // Manejo de selección del archivo
                fileInput.addEventListener('change', async function(event) {
                    const file = event.target.files[0];

                    if (!file) {
                        resetUpload();
                        return;
                    }

                    // Limpiar mensajes de error previos
                    errorMessage.textContent = '';

                    // Validar tipo de archivo
                    if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.heic')) {
                        showError('Debe subir una imagen en formato válido (JPEG, PNG, o HEIC)');
                        resetUpload();
                        return;
                    }

                    try {
                        let processedFile = file;

                        // Convertir HEIC de ser necesario
                        if (file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic')) {
                            convertingOverlay.classList.add('show');

                            const convertedBlob = await heic2any({
                                blob: file,
                                toType: "image/jpeg",
                                quality: 0.8
                            });

                            processedFile = new File(
                                [convertedBlob],
                                file.name.replace(/\.heic$/i, '.jpg'),
                                { type: 'image/jpeg' }
                            );

                            // Actualizar el input con el archivo convertido
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(processedFile);
                            fileInput.files = dataTransfer.files;

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

                // Botón de remover
                removeButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    resetUpload();
                });

                // Validación antes de enviar el formulario
                form.addEventListener('submit', function(e) {
                    if (!fileInput.files || fileInput.files.length === 0) {
                        e.preventDefault();
                        showError('Por favor, selecciona una imagen antes de guardar');
                        return false;
                    }
                });

                function showPreview(file) {
                    const previewUrl = URL.createObjectURL(file);
                    previewImage.src = previewUrl;
                    previewImage.style.display = 'block';
                    uploadText.style.display = 'none';
                    uploadArea.classList.add('has-image');
                    majorContainer.classList.add('has-image');

                    // Mostrar botón de remover y botón de guardar
                    removeButton.style.display = 'block';
                    submitButtonContainer.style.display = 'block';

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

                    // Ocultar botón de remover y botón de guardar
                    removeButton.style.display = 'none';
                    submitButtonContainer.style.display = 'none';
                }

                function showError(message) {
                    errorMessage.textContent = message;
                }
            });
        </script>
        <!-- <script>
            window.onload = function() {

                var toastActualizado = document.getElementById('toast-3');
                toastGps = new bootstrap.Toast(toastActualizado);
                var toastErrorGps = document.getElementById('toast-4');
                toastError = new bootstrap.Toast(toastErrorGps);
                navigator.geolocation.getCurrentPosition(function(location) {
                        console.log(location.coords.latitude);
                        console.log(location.coords.longitude);

                        var map;
                        var center = {

                            lat: location.coords.latitude,
                            lng: location.coords.longitude
                        };

                        function initMap() {
                            map = new google.maps.Map(document.getElementById('map'), {
                                center: center,
                                zoom: 17
                            });

                            var marker = new google.maps.Marker({
                                position: {
                                    lat: location.coords.latitude,
                                    lng: location.coords.longitude

                                },
                                draggable: true,
                                map: map,
                                title: 'Ubication'

                            });



                            $('#latitud').val(location.coords.latitude);
                            $('#longitud').val(location.coords.longitude);
                            marker.addListener('dragend', function(event) {
                                //escribimos las coordenadas de la posicion actual del marcador dentro del input #coords
                                toastGps.show();
                                $('#latitud').val(this.getPosition().lat());
                                $('#longitud').val(this.getPosition().lng());
                            });

                        }
                        initMap();
                    },
                    errores);

                function errores(err) {
                    if (err.code == err.TIMEOUT)
                        alert("Se ha superado el tiempo de espera");
                    if (err.code == err.PERMISSION_DENIED)
                        toastError.show();
                    $("#errorMapa").removeClass("displayNone")
                    $("#todoBien").addClass("displayNone")
                    if (err.code == err.POSITION_UNAVAILABLE)
                        alert("El dispositivo no pudo recuperar la posición actual");
                }
            };
        </script> -->
    @endpush
@endsection
