@extends('admin.master')

@section('login')
<div class="authincation h-100">
    <div class="container h-100 ">
        <div class="row justify-content-center h-100 align-items-center mt-5">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3">
                                    <a href="index.html"><img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}" alt=""></a>
                                </div>
                                <h4 class="text-center mb-4">Inicia sesion con tu cuenta</h4>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Email</strong></label>
                                        <input id="email" type="email" placeholder="vivalosano@ejemplo.com" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                  
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Contraseña</strong></label>
                                        <input id="password" placeholder="8 digitos" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                   
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Iniciar Sesion</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Aun sin una cuenta? <a class="text-primary" href="{{route('register')}}">Crea una</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
