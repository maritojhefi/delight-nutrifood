@extends('client.masterSinFooter')
@section('content')
    <x-cabecera-pagina titulo="Identificate" cabecera="bordeado" />

    <div class="card card-style bg-24" style="height: 750px;max-height: 920px;">
        <div class="card-center">
            <div class="ms-5 me-5">
                <h2 class="text-center color-white font-800 fa-4x">DELIGHT!</h2>
                <p class="color-highlight font-12 text-center ">Registrate!</p>
                <div class="mt-2 mb-0">

                    


                    <form action="{{ route('register') }}" method="post">
                        @csrf
                        <div class="input-style input-transparent no-borders has-icon validate-field">
                            <i class="fa fa-user"></i>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre Completo"
                                name="name" value="{{ old('name') }}" required>
                            <label for="name" class="color-blue-dark font-10 mt-1">Nombre Completo</label>

                        </div>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-style input-transparent no-borders has-icon validate-field">
                            <i class="fa fa-at"></i>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="form1a" placeholder="Email"
                                name="email" value="{{ old('email') }}" required>
                            <label for="form1a" class="color-blue-dark font-10 mt-1">Email</label>

                        </div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-style input-transparent no-borders has-icon validate-field">
                            <i class="fa fa-phone"></i>
                            <input type="number" class="form-control @error('telefono') is-invalid @enderror" id="telefono" placeholder="Telefono"
                                name="telefono" value="{{ old('telefono') }}" required>
                            <label for="telefono" class="color-blue-dark font-10 mt-1">Telefono</label>

                        </div>
                        @error('telefono')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-style input-transparent no-borders has-icon validate-field">
                            <i class="fa fa-calendar"></i>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" placeholder="Nacimiento"
                                name="fecha" value="{{ old('fecha') }}" required>
                            <label for="fecha" class="color-blue-dark font-10 mt-1">Nacimiento</label>

                        </div>
                        @error('fecha')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-style input-transparent no-borders has-icon validate-field">
                            <i class="fa fa-map"></i>
                            <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" placeholder="Direccion detallada"
                                name="direccion" value="{{ old('direccion') }}" required>
                            <label for="direccion" class="color-blue-dark font-10 mt-1">Direccion</label>

                        </div>
                        @error('direccion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-style input-transparent no-borders has-icon validate-field mt-4">
                            <i class="fa fa-lock"></i>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="form3a"
                                placeholder="Contraseña" required name="password">
                            <label for="form3a" class="color-blue-dark font-10 mt-1">Contraseña</label>

                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-style input-transparent no-borders has-icon validate-field mt-4">
                            <i class="fa fa-arrow-right"></i>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="asd"
                                placeholder="Confirmar" name="password_confirmation">
                            <label for="asd" class="color-blue-dark font-10 mt-1">Confirmar</label>

                        </div>
                        <button type="submit"
                            class="btn btn-m mt-2 mb-4 btn-full btn-block bg-green-dark rounded-sm text-uppercase font-900">Registrar
                            Cuenta</button>


                        <div class="row">
                            <div class="col-12 pt-3 text-end"><a href="{{ route('login') }}"
                                    class="color-white opacity-50 font-15">Ya tienes una cuenta? <strong>Inicia Sesion</strong>
                                </a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-black opacity-85"></div>
    </div>
@endsection

