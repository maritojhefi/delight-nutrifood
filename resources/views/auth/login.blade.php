@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Inicia Sesion" cabecera="bordeado" />

    <div class="card card-style bg-24" style="height: 573px;">
        <div class="card-center">
            <div class="ms-5 me-5">
                <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" class="img mx-auto d-block " style="width:100px" alt="">
                <p class="color-highlight font-12 text-center ">Bienvenido a {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}!</p>
                <div class="mt-2 mb-0">
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="input-style input-transparent no-borders has-icon validate-field">
                            <i class="fa fa-user"></i>
                            <input type="email" class="form-control validate-name" id="form1a" placeholder="Email"
                                name="email" value="{{ old('email') }}" required>
                            <label for="form1a" class="color-blue-dark font-10 mt-1">Email</label>

                        </div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-style input-transparent no-borders has-icon validate-field mt-4">
                            <i class="fa fa-lock"></i>
                            <input type="password" class="form-control validate-password" id="form3a"
                                placeholder="Contraseña" required name="password">
                            <label for="form3a" class="color-blue-dark font-10 mt-1">Contraseña</label>

                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <button type="submit"
                            class="btn btn-m mt-2 mb-4 btn-full btn-block bg-green-dark rounded-sm text-uppercase font-900 loader">Iniciar
                            Sesion</button>


                        <div class="row">
                            <div class="col-12 pt-3 text-end"><a href="{{ route('register') }}"
                                    class="color-white opacity-50 font-15">Aun sin cuenta? <strong>Registrate aqui</strong>
                                </a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-black opacity-85"></div>
    </div>
@endsection
