@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Inicia Sesion" cabecera="bordeado" />
    <div class="d-flex justify-content-center">
        <div class="card card-style login-card bg-24" style="height: 500px; width: 350px;">
            <div class="card-center">
                <div class="px-5 align-content-center ">
                    <div class="d-flex flex-column">
                        <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" class="img mx-auto d-block" style="width:100px" alt="">
                        <p class="mt-2 color-highlight font-26 font-weight-bold text-center ">Bienvenido a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!</p>
                    </div>
                    
                    <form action="{{ route('login') }}" method="post" class="d-flex flex-column justify-content-center">
                        @csrf
                        <label for="form1a">Correo electronico</label>

                        <div class="input-style has-icon validate-field d-flex flex-row align-content-center">
                                <i class="fa fa-user ms-2 input-icon"></i>
                                <input type="email" class="form-control rounded-sm validate-email font-13" id="form1a"
                                    name="email" value="{{ old('email') }}" required>
                        </div>

                        @error('email')
                            <small class="text-danger">{{ $message }}</ small>
                        @enderror

                        <label for="form3a">Contraseña</label>
                        {{-- <div class="input-style no-borders has-icon validate-field d-flex flex-row align-content-center"> --}}
                        <div class="input-style no-borders has-icon validate-field d-flex flex-row align-content-center"> 
                            <i class="fa fa-lock ms-2 input-icon"></i>
                            <input type="password" class="form-control rounded-sm validate-password font-13" id="form3a"
                                required name="password">
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="d-flex justify-content-center"> 
                            <button type="submit"
                                class="btn btn-m mt-2 mb-4 bg-green-dark rounded-sm text-uppercase font-900 loader"
                                style="width: 160px;"
                                >
                                Iniciar Sesion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-bottom">
                <div class="row">
                    <div class="text-end pe-5">
                        <a href="{{ route('register') }}"
                            class="color-black opacity-50 font-15">Aun sin cuenta? <strong>Registrate aqui</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
