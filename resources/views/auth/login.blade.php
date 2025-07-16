@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Inicia Sesion" cabecera="bordeado" />
    <div class="d-flex justify-content-center">
        <div class="card card-style login-card bg-24" style="height: 500px; width: 380px;">
            <div class="card-center">
                <div class="px-5">
                    <div class="d-flex flex-column mb-3">
                        <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" class="img mx-auto d-block" style="width:100px" alt="">
                        <h3 class="mt-2 font-26 text-center ">¡Bienvenido a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!</h3>
                    </div>
                    <form action="{{ route('login') }}" method="post" class="d-flex flex-column justify-content-center mx-2">
                        @csrf
                        <label for="form1a">Correo electronico</label>
                        <div class="input-style has-icon validate-field d-flex flex-row align-content-center">
                            <i class="fa fa-user ms-2 input-icon"></i>
                            <input type="email" class="form-control rounded-sm validate-email font-13" id="form1a" name="email" value="{{ old('email') }}" required>
                        </div>

                        @error('email')
                            <small class="text-danger">{{ $message }}</ small>
                        @enderror
                        
                        <label for="password-input">Contraseña</label>
                        <div class="input-style no-borders has-icon validate-field d-flex flex-row align-content-center position-relative">
                            <i class="fa fa-key ms-2 input-icon"></i>
                            <input id="password-input" type="password" class="form-control rounded-sm validate-password font-13" required name="password">
                            <button type="button" class="me-2 btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3 password-toggle" style="border: none; background: none; z-index: 10;">
                                <i class="fa fa-lock" id="toggleIcon"></i>
                            </button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
</script>
@endpush

