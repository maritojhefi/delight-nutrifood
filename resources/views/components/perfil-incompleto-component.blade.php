@if (count($datos)>0)
<div class="scroll-ad shadow-xl bg-dark-dark">
    <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}" class="shadow-l" alt="img">
    <h1 class="text-uppercase font-800 font-18 color-white">Tu perfil esta incompleto!</h1>
    <em class="font-12">Se vienen funciones interesantes <br>Necesitamos saber mas de ti!</em>
    <a href="#" class="bg-highlight color-white shadow-m " data-menu="menu-perfil-1">Llenar</a>
</div>
@endif


@push('modals')
    @foreach ($datos as $llave => $valor)
        @if (!isset($valor))
            <div id="menu-perfil-{{ $loop->iteration }}" class="menu menu-box-modal menu-box-detached rounded-l"
                data-menu-width="360" data-menu-effect="menu-over">
                <div class="card header-card shape-rounded" data-card-height="200">

                    <div class="card-overlay dark-mode-tint"></div>
                    <div class="card-bg preload-img entered loaded" data-src="{{ asset(GlobalHelper::getValorAtributoSetting('mi_perfil_deligth')) }}"
                        data-ll-status="loaded" style="background-image: url({{ asset(GlobalHelper::getValorAtributoSetting('mi_perfil_deligth')) }});">
                    </div>
                </div>
                <div class="mt-3 pt-1 pb-1">
                    <h1 class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-smartphone" data-feather-line="1"
                            data-feather-size="60" data-feather-color="gray-dark" data-feather-bg="none"
                            style="stroke-width: 1; width: 60px; height: 60px;">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2">
                            </rect>
                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                        </svg>
                    </h1>
                    <h1 class="text-center color-white font-22 font-700">PWA Ready</h1>
                    <p class="text-center mt-n3 mb-3 font-11 color-white">Just add it to your home screen and Enjoy!</p>
                </div>
                <div class="card card-style">
                    @switch($llave)
                        @case('nacimiento')
                            <h3 class="mx-auto mt-2">Fecha de nacimiento</h3>
                            <p class="boxed-text-xl pt-3 mb-3">
                                Obtendras descuentos en tu cumpleaños!
                            </p>

                            <div class="row m-3">

                                <div class="col-4">
                                    <div class="input-style input-style-always-active no-borders no-icon validate-field mb-4">
                                        <select name="" id="dia" class="form-control validate-name nacimiento">
                                            <option value="">Elija</option>
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>

                                        <label for="form1ac" class="color-green-dark text-uppercase font-700 font-10">Dia</label>
                                        <i class="fa fa-times disabled invalid color-red-dark "></i>
                                        <i class="fa fa-check disabled valid color-green-dark check"></i>

                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-style input-style-always-active no-borders no-icon validate-field mb-4">
                                        <select name="" id="mes" class="form-control validate-name nacimiento">
                                            @php
                                                $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                            @endphp
                                            <option value="">Elija</option>
                                            @foreach ($meses as $mes)
                                                <option value="{{ $loop->iteration }}">{{ $mes }}</option>
                                            @endforeach
                                        </select>

                                        <label for="form1ac" class="color-green-dark text-uppercase font-700 font-10">Mes</label>
                                        <i class="fa fa-times disabled invalid color-red-dark"></i>
                                        <i class="fa fa-check disabled valid color-green-dark check"></i>

                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-style input-style-always-active no-borders no-icon validate-field mb-4">
                                        <select name="" id="ano" class="form-control validate-name nacimiento">

                                            @for ($i = 1930; $i <= 2020; $i++)
                                                <option value="{{ $i }}" {{ $i == 2015 ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>

                                        <label for="form1ac" class="color-green-dark text-uppercase font-700 font-10">Año</label>
                                        <i class="fa fa-times disabled invalid color-red-dark"></i>
                                        <i class="fa fa-check disabled valid color-green-dark check"></i>

                                    </div>
                                </div>
                                <button type="text" disabled="true"
                                    class="btn btn-xs mb-3 rounded-s text-uppercase font-900 shadow-s border-orange-dark  bg-orange-light "
                                    id="guardarNacimiento">Guardar</button>


                            </div>
                            @push('modals')
                                <div id="toast-exito" class="toast toast-tiny toast-top bg-green-dark fade hide" data-bs-delay="1500"
                                    data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Fecha guardada!</div>
                                <div id="toast-fallo" class="toast toast-tiny toast-top bg-red-dark fade hide" data-bs-delay="1500"
                                    data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Algo salio mal!</div>
                            @endpush
                            @push('scripts')
                                <script>
                                    $(document).ready(function() {

                                        $('#ano,#mes,#dia').change(function() {

                                            if ($('#ano').parent().children('.check').hasClass('disabled') == false && $('#mes')
                                                .parent().children('.check').hasClass('disabled') == false && $('#dia').parent()
                                                .children('.check').hasClass('disabled') == false) {
                                                $('#guardarNacimiento').attr('disabled', false)
                                            }


                                        });
                                        $("#guardarNacimiento").click(function() {
                                            var fecha = '' + $("#ano").val() + '-' + ('0' + $("#mes").val()).slice(-2) + '-' + ('0' + $(
                                                "#dia").val()).slice(-2)

                                            $.ajax({
                                                method: "post",
                                                url: "/miperfil/change/birthday",
                                                data: {
                                                    _token: '{{ csrf_token() }}',
                                                    fecha: fecha
                                                },
                                                success: function(result) {
                                                    if (result == 'exito') {
                                                        var toaster = document.getElementById('toast-exito');
                                                        cart = new bootstrap.Toast(toaster);
                                                        cart.show()
                                                    } else {
                                                        var toaster = document.getElementById('toast-fallo');
                                                        cart = new bootstrap.Toast(toaster);
                                                        cart.show()
                                                    }
                                                }
                                            })
                                        })


                                    })
                                </script>
                            @endpush
                        @break

                        @case('foto')
                            <h3 class="mx-auto mt-2">Sube tu foto</h3>
                            <p class="boxed-text-xl pt-3 mb-3">
                                Obtendras beneficios si subes tu foto de perfil!

                            </p>
                        @break

                        @case('latitud')
                            <h3 class="mx-auto mt-2">Necesitamos tu ubicacion!</h3>
                            <p class="boxed-text-xl pt-3 mb-3">
                                Usa el mapa de google para facilitar los envios delivery

                            </p>
                        @break

                        @case('direccion')
                            <h3 class="mx-auto mt-2">Direccion de domicilio</h3>
                            <p class="boxed-text-xl pt-3 mb-3">
                                Dinos referencias sobre tu ubicacion!

                            </p>
                        @break

                        @default
                    @endswitch

                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <a href="#"
                            class="btn btn-border btn-sm ms-3 rounded-s btn-full shadow-l color-highlight border-highlight close-menu text-uppercase font-900">Cerrar</a>
                    </div>
                    <div class="col-6">
                        <a data-menu="menu-perfil-{{ $loop->iteration + 1 }}" href="#"
                            class="btn btn-sm me-3 rounded-s btn-full shadow-l bg-highlight font-900 text-uppercase">{{ $loop->iteration }}/{{ $loop->count }}</a>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endpush
