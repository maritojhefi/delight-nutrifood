@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Editar Perfil" cabecera="appkit" />


    @if (!session('success'))
        @if (session('actualizado'))
            <div class="alert me-3 ms-3 rounded-s bg-green-dark " role="alert">
                <span class="alert-icon"><i class="fa fa-check font-18"></i></span>
                <h4 class="text-uppercase color-white">Hecho!</h4>
                <strong class="alert-icon-text">Se actualizo correctamente su foto de perfil.</strong>

                <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
                    aria-label="Close">×</button>
            </div>
        @endif

        <div class="card card-style">
            <div class="content">
                <div class="d-flex flex-row align-items-center gap-3 justify-content-evenly">
                    <div>
                        @if ($usuario->foto)
                            <img src="{{ $usuario->pathFoto }}" width="55" class="bg-highlight rounded-xl">
                        @else
                            <img src="{{ asset('user.png') }}" width="55" class="bg-highlight rounded-xl">
                        @endif
                    </div>
                    <div>
                        <h1 class="mb-0 pt-1">{{ auth()->user()->name }}</h1>
                    </div>
                </div>
                <p class="color-highlight font-11 mt-n1 mb-0">Información cifrada, no revelaremos esto con nadie</p>
                <p>
                    Completa tu perfil, asi estaras listo para todas las funciones que se vienen pronto!
                </p>
            </div>
        </div>

        <card class="card card-style py-2">
            <div class="card-header bg-theme border-0">
                <h2 class="mb-0">Cambiar Nro Telefónico</h2>
                <!-- <small class="text-muted">Ingrese su nuevo número para recibir código de verificación</small> -->
            </div>
            
            <form id="form-cambiar-telefono" method="POST" action="{{ route('usuario.verificar-numero') }}">
                @csrf
                @method('PUT')
                
                <div class="card-body">
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
                                name="telefono_nacional"
                                id="telefono_nacional"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                autocomplete="tel-national"
                                style="height: 40px;"
                                value="{{ old('telefono_nacional', $usuario->telf) }}"
                                required>
                            <label for="telefono_nacional" 
                                class="color-highlight bg-theme font-400 font-13"
                                style="transition: none;">
                                Teléfono
                            </label>
                            
                            <!-- Feedback de validación -->
                            <!-- <div class="valid-feedback">
                                <i class="fa fa-check me-1"></i> 
                                Número válido
                            </div>
                            <div class="invalid-feedback">
                                Número inválido para el país seleccionado
                            </div> -->
                        </div>
                    </div>

                    <!-- Mensajes de Error de Laravel -->
                    @error('telefono_nacional')
                        <p class="text-danger line-height-s mx-2 mt-2 mb-0">
                            <i class="fa fa-times invalid color-red-dark me-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    
                    @error('codigo_pais')
                        <p class="text-danger line-height-s mx-2 mt-2 mb-0">
                            <i class="fa fa-times invalid color-red-dark me-2"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <!-- Campo oculto para dígitos esperados (útil para backend) -->
                    <input type="hidden" name="digitos_esperados" id="digitos_esperados">
                </div>

                <!-- Footer con botón de submit -->
                <div class="card-footer bg-theme border-0 pt-0">
                    <button type="submit" 
                            id="btn-guardar-telefono"
                            class="btn w-100 btn-m bg-highlight rounded-s d-flex flex-row align-items-center justify-content-center gap-1 text-uppercase font-900 shadow-s d-none"
                            disabled>
                        <i data-lucide="message-circle-more" class="lucide-icon me-2"></i>
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
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4">
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
                        <div class="d-flex flex-column mb-3 mt-n1">
                            <label for="nacimiento" class="ms-2 mb-n2 d-inline-block px-1 line-height-xs color-highlight bg-theme font-400 font-13"
                                style="z-index: 10;width:fit-content">Fecha de nacimiento</label>
                            <div class="input-style has-borders remove-mb rounded-sm validate-field d-flex flex-row gap-2">
                                {{-- Campo Día --}}
                                <input type="number" class="text-center form-control"
                                    placeholder="Día" name="dia_nacimiento" required
                                    min="1" max="31"
                                    value="{{ old('dia_nacimiento', explode('-', $usuario->nacimiento)[2]) }}"
                                    oninput="if(this.value > 31) this.value = 31; if(this.value < 1) this.value = 1;"
                                >

                                {{-- Campo Mes (Select) --}}
                                <select class="text-center form-control" name="mes_nacimiento" required>
                                    <option value="" disabled selected>Mes</option>
                                    @php
                                        $selectedMonth = explode('-', $usuario->nacimiento)[1];
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
                                    value="{{ old('ano_nacimiento', explode('-', $usuario->nacimiento)[0]) }}"
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
                                placeholder="Ejm: Abogado" name="profesion"
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
                        <div class="input-style has-borders has-icon input-style-always-active validate-field mb-4">
                            <i data-lucide="map-pin-house" class="lucide-icon lucide-input"></i>
                            <input type="text" class="form-control"
                                placeholder="Ejm: Tomatitas Av. Principal #134 Porton guindo" name="direccion"
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
                                placeholder="Ejm: Tomatitas Av. Principal #134 Porton guindo" name="direccion_trabajo"
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
                        {{-- <div class="input-style has-borders hnoas-icon input-style-always-active validate-field mb-4">
                            <input type="number" class="form-control" placeholder="" name="telf"
                                value="{{ old('telf', $usuario->telf) }}">
                            <label for="" class="color-highlight font-400 font-13">Telefono</label>

                            @error('telf')
                                <i class="fa fa-times  invalid color-red-dark"></i>
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div> --}}
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
                <div class="content mb-0">
                    <h4>Foto de perfil</h4>
                    <p>
                        Personaliza tu perfil, nos ayudará a conocerte mejor!
                    </p>
                    <form action="{{ route('subirfoto.perfil') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="file-data pb-5">
                            <input type="file" id="file-upload" class="upload-file bg-highlight shadow-s rounded-s "
                                accept="image/*" name="foto">
                            <p class="upload-file-text color-white">Subir Foto</p>
                        </div>
                        @error('foto')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <div class="list-group list-custom-large upload-file-data disabled">
                            <img id="image-data" src="images/empty.png" class="img-fluid"
                                style="width:100%; display:block; height:300px">

                            <button type="submit"
                                class="btn btn-3d btn-m btn-full mb-3 mt-3 rounded-xl text-uppercase font-900 shadow-s  border-blue-dark bg-blue-light">Guardar</button>

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
    @else
        <div data-card-height="200" class="card card-style preload-img entered loaded"
            data-src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}"
            style="height: 200px; background-image: url({{ asset(GlobalHelper::getValorAtributoSetting('inicio_disfruta')) }});"
            data-ll-status="loaded">
            <div class="card-top pt-4 ms-3 me-3">
                <h2 class="color-white font-600">Completaste tu perfil!</h2>
                <p class="mt-n2 color-white">Ya podemos usar todas las funciones contigo! Pronto las descubriras</p>
            </div>
            <div class="card-bottom ms-3 me-3 mb-4">
                <div class="progress" style="height:26px;">
                    <div class="progress-bar border-0 bg-white color-black text-start ps-2" role="progressbar"
                        style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                        Completado al 100%
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-dark opacity-70"></div>
        </div>
        <a href="{{ route('miperfil') }}"
            class="btn btn-m btn-full m-3 rounded-xl text-uppercase font-900 shadow-s bg-green-dark">Ir a mi perfil</a>
    @endif

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

            .theme-dark .ss-option.ss-selected {
                background-color: #7db1b1 !important;
                border-radius: 0.4rem;
            }

            .theme-dark .ss-search input {
                background-color:  #0f1117 !important;
                border-radius: 0.4rem;
                /* background-color:  #1f2937 !important; */
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
            let digitosPais = null;
            let validacionEnProgreso = false;
            let timeoutValidacion = null;

            // ============= ELEMENTOS DEL DOM =============
            const selectorPaisId = 'selector-codigo_pais-perfil';
            const selectorPais = $('#' + selectorPaisId);
            const inputTelefono = $('#telefono_nacional');
            const btnSubmit = $('#btn-guardar-telefono'); // ID del botón submit

            // ============= INICIALIZAR SLIMSELECT =============
            if (selectorPais.length) {
                selectorPais.css('display', '');

                new SlimSelect({
                    select: '#' + selectorPaisId,
                    settings: {
                        placeholder: 'Seleccione un país',
                        searchPlaceholder: 'Buscar país...',
                        searchText: 'Sin resultados',
                        allowDeselect: false,
                        showSearch: true,
                    }
                });

                // Establecer el codigo_pais del usuario como valor por defecto
                setTimeout(() => {
                    const rawElement = selectorPais.get(0);
                    if (!rawElement.value || rawElement.value === '') {
                        rawElement.value = '{{ ltrim($usuario->codigo_pais, '+') }}';
                        // rawElement.value = '591';
                        rawElement.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }, 150);

                // Event listener para cambios en el selector
                selectorPais.get(0).addEventListener('change', function() {
                    detectarDigitosPais();
                    // Limpiar validación previa cuando cambia el país
                    inputTelefono.val('');
                    ocultarBotonSubmit();
                    // Cancelar validaciones pendientes
                    if (timeoutValidacion) {
                        clearTimeout(timeoutValidacion);
                    }
                });
            }

            // ============= DETECTAR DÍGITOS DEL PAÍS =============
            const detectarDigitosPais = () => {
                const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();
                const codigo = parseInt(selectorPais.val());

                console.log('Detectando dígitos para código:', codigo);

                try {
                    const regiones = phoneUtil.getRegionCodesForCountryCode(codigo);
                    
                    if (!regiones || regiones.length === 0) {
                        console.warn('No se encontraron regiones para el código:', codigo);
                        return;
                    }

                    // Buscar ejemplo de número móvil
                    for (const region of regiones) {
                        const ejemplo = phoneUtil.getExampleNumberForType(
                            region,
                            libphonenumber.PhoneNumberType.MOBILE
                        );
                        
                        if (ejemplo) {
                            const numeroEjemplo = phoneUtil.getNationalSignificantNumber(ejemplo);
                            digitosPais = numeroEjemplo.length;
                            
                            console.log(`Código +${codigo} → Región: ${region}, Longitud esperada: ${digitosPais}`);
                            
                            // Actualizar placeholder del input
                            inputTelefono.attr('placeholder', `Ej: ${numeroEjemplo}`);
                            return;
                        }
                    }
                } catch (e) {
                    console.error('Error al detectar dígitos:', e.message);
                    mostrarError('Error al configurar validación de país');
                }
            };

            // ============= VALIDACIÓN EN TIEMPO REAL CON DEBOUNCE =============
            inputTelefono.on('input', function() {
                const valorActual = $(this).val();
                const longitudActual = valorActual.length;

                console.log(`Caracteres: ${longitudActual}/${digitosPais}`);

                // Limpiar timeout anterior
                if (timeoutValidacion) {
                    clearTimeout(timeoutValidacion);
                }

                // Validar solo cuando se alcanza la longitud esperada
                if (digitosPais && longitudActual === digitosPais) {
                    // Debounce de 500ms antes de validar
                    timeoutValidacion = setTimeout(() => {
                        validarTelefonoRemoto(valorActual);
                    }, 500);
                } else {
                    ocultarBotonSubmit();
                }

                // Prevenir entrada de más dígitos de los permitidos
                if (digitosPais && longitudActual > digitosPais) {
                    $(this).val(valorActual.substring(0, digitosPais));
                }
            });

            // ============= VALIDACIÓN REMOTA CON AXIOS =============
            const validarTelefonoRemoto = async (telefono) => {
                // Prevenir validaciones simultáneas
                if (validacionEnProgreso) return;
                validacionEnProgreso = true;

                const codigoPais = selectorPais.val();

                try {
                    const response = await axios.post('{{ route("usuario.verificar-numero") }}', {
                        telefono: telefono,
                        codigoPais: codigoPais,
                        digitosPais: digitosPais
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.data.status === 'success') {
                        console.log('✅ Teléfono válido');
                        mostrarBotonSubmit();
                        mostrarExito('Número válido');
                    } else {
                        ocultarBotonSubmit();
                        const errorMsg = extraerMensajeError(response.data.errors);
                        mostrarError(errorMsg);
                    }

                } catch (error) {
                    console.error('Error en validación:', error);
                    ocultarBotonSubmit();
                    
                    let errorMsg = 'Error al validar el teléfono';
                    if (error.response?.data?.errors) {
                        errorMsg = extraerMensajeError(error.response.data.errors);
                    } else if (error.response?.data?.message) {
                        errorMsg = error.response.data.message;
                    }
                    
                    mostrarError(errorMsg);
                } finally {
                    validacionEnProgreso = false;
                }
            };

            // ============= UTILIDADES =============
            const extraerMensajeError = (errors) => {
                if (errors.telefono) return errors.telefono[0];
                if (errors.general) return errors.general[0];
                return 'Error de validación';
            };

            const mostrarBotonSubmit = () => {
                btnSubmit.prop('disabled', false).removeClass('d-none');
                inputTelefono.removeClass('is-invalid').addClass('is-valid');
            };

            const ocultarBotonSubmit = () => {
                btnSubmit.prop('disabled', true).addClass('d-none');
                inputTelefono.removeClass('is-valid is-invalid');
            };

            const mostrarError = (mensaje) => {
                inputTelefono.addClass('is-invalid');
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

            // ============= INICIALIZACIÓN =============
            detectarDigitosPais();
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
