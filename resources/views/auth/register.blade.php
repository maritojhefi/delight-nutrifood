@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Registrate" cabecera="bordeado" />

    <div class="card card-style bg-24" style="height: 750px;max-height: 920px;">
        <div class="card-center">
            <div class="ms-5 me-5">
                <img src="{{ asset('logo2.png') }}" class="img mx-auto d-block " style="width:100px" alt="">
                <p class="color-highlight font-15 text-center "><strong> Bienvenido a Delight!</strong></p>
                <div class="mt-2 mb-0">
                    <form action="{{ route('usuario.registrar') }}" method="post" id="multiStepForm" novalidate>
                        @csrf

                        <!-- Paso 1: Información Personal -->
                        <div class="form-step" id="step-1">
                            <h5 class="text-center text-white">Paso 1: Información Personal</h5>
                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-user"></i>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" placeholder="Nombre Completo" name="name" value="{{ old('name') }}"
                                    required>
                                <label for="name" class="color-blue-dark font-10 mt-1">Nombre Completo</label>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-at"></i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" placeholder="Correo Electrónico" name="email"
                                    value="{{ old('email') }}" required>
                                <label for="email" class="color-blue-dark font-10 mt-1">Correo Electrónico</label>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-phone"></i>
                                <input type="number" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" placeholder="Teléfono (8 digitos)" name="telefono" value="{{ old('telefono') }}"
                                    required>
                                <label for="telefono" class="color-blue-dark font-10 mt-1">Teléfono</label>
                                @error('telefono')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="button"
                                class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-next"
                                id="nextStep1">Siguiente</button>
                        </div>

                        <!-- Paso 2: Detalles Adicionales -->
                        <div class="form-step d-none" id="step-2">
                            <h5 class="text-center text-white">Paso 2: Detalles Adicionales</h5>
                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-briefcase"></i>
                                <input type="text" class="form-control @error('profesion') is-invalid @enderror"
                                    id="profesion" placeholder="Profesión" name="profesion" value="{{ old('profesion') }}"
                                    required>
                                <label for="profesion" class="color-blue-dark font-10 mt-1">Profesión</label>
                                @error('profesion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <label for="profesion" class="color-white-dark font-10 mt-1">Fecha de nacimiento</label>
                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-calendar"></i>
                                <div class="row">
                                    <div class="col-4">
                                        <input type="number" class="form-control" placeholder="Día" name="dia_nacimiento"
                                            required>
                                    </div>
                                    <div class="col-4">
                                        <!-- Convertimos el campo del mes en un select -->
                                        <select class="form-control" name="mes_nacimiento" required>
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
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control" placeholder="Año" name="ano_nacimiento"
                                            required>
                                    </div>
                                </div>
                                <label for="nacimiento" class="color-blue-dark font-10 mt-1">Fecha de Nacimiento</label>
                            </div>


                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-map"></i>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" placeholder="Dirección" name="direccion"
                                    value="{{ old('direccion') }}" required>
                                <label for="direccion" class="color-blue-dark font-10 mt-1">Dirección</label>
                                @error('direccion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-building"></i>
                                <input type="text"
                                    class="form-control @error('direccion_trabajo') is-invalid @enderror"
                                    id="direccion_trabajo" placeholder="Dirección de Trabajo" name="direccion_trabajo"
                                    value="{{ old('direccion_trabajo') }}">
                                <label for="direccion_trabajo" class="color-blue-dark font-10 mt-1">Dirección de
                                    Trabajo</label>
                                @error('direccion_trabajo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="button"
                                class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-prev">Anterior</button>
                            <button type="button"
                                class="btn btn-xs  mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-next">Siguiente</button>
                        </div>

                        <!-- Paso 3: Seguridad -->
                        <div class="form-step d-none" id="step-3">
                            <h5 class="text-center text-white">Paso 3: Seguridad y Otros Detalles</h5>

                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-lock"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" placeholder="Contraseña" name="password" required>
                                <label for="password" class="color-blue-dark font-10 mt-1">Contraseña</label>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="input-style input-transparent no-borders has-icon">
                                <i class="fa fa-arrow-right"></i>
                                <input type="password" class="form-control" id="password_confirmation"
                                    placeholder="Confirmar Contraseña" name="password_confirmation" required>
                                <label for="password_confirmation" class="color-blue-dark font-10 mt-1">Confirmar
                                    Contraseña</label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="hijos" id="hijos"
                                    value="1">
                                <label class="form-check-label" for="hijos">¿Tiene hijos?</label>
                            </div>
                            <br>
                            <button type="button"
                                class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark btn-prev">Anterior</button>
                            <button type="submit"
                                class="btn btn-xs mb-3 rounded-xl text-uppercase font-900 shadow-s bg-red-dark btn-block">Registrar
                                Cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-black opacity-85"></div>
    </div>
@endsection
@push('modals')
    <div id="menu-errores" class="menu menu-box-bottom menu-box-detached bg-yellow-dark rounded-m " data-menu-height="305"
        data-menu-effect="menu-over" style="display: block; height: 305px;">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-info-circle color-white shadow-xl rounded-circle"></i></h1>
        <h1 class="text-center mt-3 text-uppercase color-white font-700">Uy!</h1>
        <p class="boxed-text-l color-white opacity-70">
            Tu informacion no es correcta.<br> Por favor vuelve a revisarla.
        </p>
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
