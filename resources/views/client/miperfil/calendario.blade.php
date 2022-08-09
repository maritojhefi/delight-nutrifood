@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="{{ $plan->nombre }}" cabecera="bordeado" />
    @env('local')
    @php
    $path = env('APP_URL');
    @endphp
    @endenv
    @production
        @php
        $path = 'https://delight-nutrifood.com';
        @endphp
    @endproduction
    @include('client.miperfil.script-calendar')
    <div class="card card-style">
        @if ($estadoMenu->activo)
            @if ($plan->editable)
                <div class="content text-white">
                    <h4>Personaliza tu menu de esta semana!</h4>
                    <p>
                        Quedan {{ $coleccion->count() }} dias, personaliza cada uno!
                    </p>
                </div>
                <div class="accordion mt-4" id="accordion-3">
                    @foreach ($coleccion as $lista)
                        <div class="card card-style">
                            <div
                                class="list-group list-custom-small list-icon-0 bg-@if($lista['detalle'] == null && $lista['estado']=='pendiente'){{'mint'}}@elseif($lista['estado']=='desarrollo'){{'yellow'}}@else{{'green'}}@endif-dark ps-3 pe-4 ">
                                <a data-bs-toggle="collapse" class="no-effect collapsed" href="#collapse-7{{ $lista['id'] }}"
                                    aria-expanded="false">

                                    @if ($lista['detalle'] == null && $lista['estado']=='pendiente')
                                        <i class="fas fa-user-edit color-white"></i>
                                        <span class="font-14 color-white">{{ $lista['dia'] }}({{ $lista['fecha'] }})</span>
                                    @elseif($lista['estado']=='desarrollo')
                                    <i class="fab fa-whatsapp color-white"></i>
                                    <span class="font-14 color-white">{{ $lista['dia'] }}</span>
                                    <label for="" class="text-magenta text-white">
                                        (En desarrollo por whatsapp!)</label>
                                    @else
                                    <i class="fas fa-save color-white"></i>
                                    <span class="font-14 color-white">{{ $lista['dia'] }}({{ $lista['fecha'] }})</span>
                                    <label for="" class="text-magenta text-white">
                                        Guardado!</label>
                                    @endif
                                    <i class="fa fa-angle-down color-white"></i>
                                </a>
                            </div>
                            <div class="ps-2 pe-4 collapse" id="collapse-7{{ $lista['id'] }}" data-bs-parent="#accordion-3"
                                style="">
                                <div class="p-2">
                                    @if ($lista['detalle'] == null && $lista['estado']=='pendiente')
                                        <form action="{{ route('personalizardia') }}" id="{{ $lista['id'] }}"
                                            method="POST">
                                            @csrf
                                            <div class="row">
                                                @if ($plan->segundo)
                                                    <div class="col-12 mb-3" id="plato{{ $lista['id'] }}">
                                                        <i class="fa fa-star color-yellow-dark"></i> <strong>Elija su
                                                            plato</strong>
                                                        <div class="fac fac-radio fac-default"><span></span>
                                                            <input id="box1-fac-radio{{ $lista['id'] }}" type="radio"
                                                                name="plato{{ $lista['id'] }}"
                                                                value="{{ $lista['ejecutivo'] }}">
                                                            <label for="box1-fac-radio{{ $lista['id'] }}"><mark
                                                                    class="highlight ps-2 font-12 pe-2 bg-magenta-dark mr-2">Ejecutivo</mark>
                                                                {{ $lista['ejecutivo'] }}</label>
                                                        </div>
                                                        <div class="fac fac-radio fac-default"><span></span>
                                                            <input id="box2-fac-radio{{ $lista['id'] }}" type="radio"
                                                                name="plato{{ $lista['id'] }}"
                                                                value="{{ $lista['dieta'] }}">
                                                            <label for="box2-fac-radio{{ $lista['id'] }}"><mark
                                                                    class="highlight ps-2 font-12 pe-2 bg-magenta-dark mr-2">Dieta</mark>
                                                                {{ $lista['dieta'] }} </label>
                                                        </div>
                                                        <div class="fac fac-radio fac-default"><span></span>
                                                            <input id="box3-fac-radio{{ $lista['id'] }}" type="radio"
                                                                name="plato{{ $lista['id'] }}"
                                                                value="{{ $lista['vegetariano'] }}">
                                                            <label for="box3-fac-radio{{ $lista['id'] }}"><mark
                                                                    class="highlight ps-2 font-12 pe-2 bg-magenta-dark mr-2">Veggie</mark>
                                                                {{ $lista['vegetariano'] }} </label>
                                                        </div>

                                                    </div>
                                                @endif
                                                @if ($plan->carbohidrato)
                                                    <div class="col-12" id="carb{{ $lista['id'] }}">
                                                        <i class="fa fa-star color-yellow-dark"></i> <strong>Elija su
                                                            carbohidrato</strong>
                                                        <div class="fac fac-radio fac-default"><span></span>
                                                            <input id="box4-fac-radio{{ $lista['id'] }}" type="radio"
                                                                name="carb{{ $lista['id'] }}"
                                                                value="{{ $lista['carbohidrato_1'] }}">
                                                            <label
                                                                for="box4-fac-radio{{ $lista['id'] }}">{{ $lista['carbohidrato_1'] }}</label>
                                                        </div>
                                                        <div class="fac fac-radio fac-default"><span></span>
                                                            <input id="box5-fac-radio{{ $lista['id'] }}" type="radio"
                                                                name="carb{{ $lista['id'] }}"
                                                                value="{{ $lista['carbohidrato_2'] }}">
                                                            <label
                                                                for="box5-fac-radio{{ $lista['id'] }}">{{ $lista['carbohidrato_2'] }}</label>
                                                        </div>
                                                        <div class="fac fac-radio fac-default"><span></span>
                                                            <input id="box6-fac-radio{{ $lista['id'] }}" type="radio"
                                                                name="carb{{ $lista['id'] }}"
                                                                value="{{ $lista['carbohidrato_3'] }}">
                                                            <label
                                                                for="box6-fac-radio{{ $lista['id'] }}">{{ $lista['carbohidrato_3'] }}</label>
                                                        </div>
                                                        <div class="fac fac-radio fac-default"><span></span>
                                                            <input id="box13-fac-radio{{ $lista['id'] }}" type="radio"
                                                                name="carb{{ $lista['id'] }}" value="sin carbohidrato">
                                                            <label for="box13-fac-radio{{ $lista['id'] }}">Sin
                                                                carbohidrato</label>
                                                        </div>

                                                    </div>
                                                @endif

                                            </div>
                                            <div class="row">
                                                <div class="col-6" id="envio{{ $lista['id'] }}">
                                                    <i class="fa fa-map-marker font-16 color-red-dark"></i> <strong>Tipo de
                                                        envio</strong>
                                                    <div class="fac fac-radio fac-default mesa"><span></span>
                                                        <input id="box7-fac-radio{{ $lista['id'] }}" type="radio"
                                                            data-id="{{ $lista['id'] }}" class="mesa"
                                                            name="envio{{ $lista['id'] }}"
                                                            value="{{ $lista['envio1'] }}">
                                                        <label
                                                            for="box7-fac-radio{{ $lista['id'] }}">{{ $lista['envio1'] }}</label>
                                                    </div>
                                                    <div class="fac fac-radio fac-default"><span></span>
                                                        <input id="box8-fac-radio{{ $lista['id'] }}" type="radio"
                                                            data-id="{{ $lista['id'] }}" class="otro"
                                                            name="envio{{ $lista['id'] }}"
                                                            value="{{ $lista['envio2'] }}">
                                                        <label
                                                            for="box8-fac-radio{{ $lista['id'] }}">{{ $lista['envio2'] }}</label>
                                                    </div>
                                                    <div class="fac fac-radio fac-default"><span></span>
                                                        <input id="box9-fac-radio{{ $lista['id'] }}" type="radio"
                                                            data-id="{{ $lista['id'] }}" class="otro"
                                                            name="envio{{ $lista['id'] }}"
                                                            value="{{ $lista['envio3'] }}">
                                                        <label
                                                            for="box9-fac-radio{{ $lista['id'] }}">{{ $lista['envio3'] }}</label>
                                                    </div>

                                                </div>

                                                <div class="col-6 empaques d-none" id="empaque{{ $lista['id'] }}">
                                                    <i class="fa fa-edit font-16 color-red-dark"></i> <strong>Tipo de
                                                        empaque</strong>
                                                    <div class="fac fac-radio fac-default"><span></span>
                                                        <input id="box10-fac-radio{{ $lista['id'] }}" type="radio"
                                                            name="empaque{{ $lista['id'] }}"
                                                            value="{{ $lista['empaque1'] }}">
                                                        <label
                                                            for="box10-fac-radio{{ $lista['id'] }}">{{ $lista['empaque1'] }}</label>
                                                    </div>
                                                    <div class="fac fac-radio fac-default"><span></span>
                                                        <input id="box11-fac-radio{{ $lista['id'] }}" type="radio"
                                                            name="empaque{{ $lista['id'] }}"
                                                            value="{{ $lista['empaque2'] }}">
                                                        <label
                                                            for="box11-fac-radio{{ $lista['id'] }}">{{ $lista['empaque2'] }}</label>
                                                    </div>
                                                    <div class="fac fac-radio fac-default d-none"><span></span>
                                                        <input id="box12-fac-radio{{ $lista['id'] }}" type="radio"
                                                            name="empaque{{ $lista['id'] }}" value="Ninguno">
                                                        <label for="box12-fac-radio{{ $lista['id'] }}">Ninguno(si es para
                                                            mesa)</label>
                                                    </div>


                                                </div>
                                                <div class="box7-fac-radio{{ $lista['id'] }}"
                                                    id="empaque{{ $lista['id'] }}"></div>
                                            </div>
                                            <div class="row mb-0">
                                                @if ($plan->sopa)
                                                    <h4 class="col-6 font-500  font-13"> <i
                                                            class="fa fa-check color-green-dark"></i> <strong>Sopa</strong>
                                                    </h4>
                                                    <p class="col-6 mb-3 text-end  font-900 mb-0">
                                                        {{ $lista['sopa'] }}
                                                    </p>
                                                    <input type="hidden" value="{{ $lista['sopa'] }}" name="sopa">
                                                @endif
                                                @if ($plan->ensalada)
                                                    <h4 class="col-6 font-500 font-13 "> <i
                                                            class="fa fa-check color-green-dark"></i>
                                                        <strong>Ensalada</strong>
                                                    </h4>
                                                    <p class="col-6 mb-3 text-end  font-900 mb-0">
                                                        {{ $lista['ensalada'] }}
                                                    </p>
                                                    <input type="hidden" value="{{ $lista['ensalada'] }}"
                                                        name="ensalada">
                                                @endif
                                                @if ($plan->jugo)
                                                    <h4 class="col-6 font-500 font-13"> <i
                                                            class="fa fa-check color-green-dark"></i> <strong>Jugo</strong>
                                                    </h4>
                                                    <p class="col-6 mb-3 text-end  font-900 mb-0">
                                                        {{ $lista['jugo'] }}
                                                    </p>
                                                    <input type="hidden" value="{{ $lista['jugo'] }}" name="jugo">
                                                @endif

                                                <input type="hidden" value="{{ $lista['dia'] }}" name="dia">
                                                <input type="hidden" value="{{ $lista['id'] }}" name="id">
                                                <input type="hidden" value="{{ $plan->id }}" name="plan">


                                                <div class="col">
                                                    <button type="submit" disabled
                                                        class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-mint-dark">Guardar
                                                        {{ $lista['dia'] }}</button>
                                                </div>
                                            </div>

                                        </form>
                                        @elseif($lista['estado']=='desarrollo')
                                        Ve a tu whatsapp para terminar de programar este plan!
                                        @else
                                        <ul class="icon-list">
                                            @foreach (json_decode($lista['detalle'], true) as $plato => $valor)
                                                @if ($valor != '')
                                                    <li><i class="fa fa-check color-green-dark"></i>{{ $plato }} :
                                                        <label for=""
                                                            class="font-700 mb-0">{{ $valor }}</label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        <a href="{{ route('editardia', $lista['id']) }}"
                                            class="btn btn-xxs  rounded-s text-uppercase font-900 shadow-s border-red-dark  bg-red-light">Editar</a>
                                    @endif


                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="content text-center pt-4">
                <h1><i class="fa fa-sync fa-spin color-red-dark fa-3x"></i></h1>
                <h2 class="pt-4">Casi listo!</h2>
                <p class="boxed-text-l">
                    Estamos planificando el menu para toda la semana! Estara listo en breve...
                </p>
                
            </div>

        @endif



    </div>
    @if ($errors->any())
        <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert">
            <span><i class="fa fa-times"></i></span>
            <strong>Rellene bien los campos</strong>


        </div>
    @endif
    @if (session('success'))
        <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-green-dark" role="alert">
            <span><i class="fa fa-check"></i></span>
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
                aria-label="Close">×</button>
        </div>
    @endif
    @if (session('error'))
        <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert">
            <span><i class="fa fa-times-circle "></i></span>
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
                aria-label="Close">×</button>
        </div>
    @endif


    <div class="card card-style bg-18">
        <div class="card">
            <div class="card-body">
                <div id="calendar" class="app-fullcalendar"></div>
            </div>
        </div>
    </div>
    @push('modals')
        <div id="toast-finalizado" class="toast toast-tiny toast-top bg-orange-dark fade hide" data-bs-delay="2000"
            data-bs-autohide="true"><i class="fa fa-exclamation"></i> Dia finalizado...</div>
        <div id="toast-permiso" class="toast toast-tiny toast-top bg-magenta-dark fade hide" data-bs-delay="2000"
            data-bs-autohide="true"><i class="fa fa-date"></i> Dia de permiso!</div>
        <div id="toast-archivado" class="toast toast-tiny toast-top bg-gray-dark fade hide" data-bs-delay="3000"
            data-bs-autohide="true"><i class="fa fa-date"></i> Registro archivado!</div>
            <div id="toast-whatsapp" class="toast toast-tiny toast-top bg-yellow-dark fade hide" data-bs-delay="3000"
            data-bs-autohide="true"><i class="fab fa-whatsapp"></i> En desarrollo!</div>

        <div class="card card-style bg-18" data-card-height="150" style="height: 150px;">
            <div class="card-center ms-3">
                <h1 class="color-white">Detalles del plan:</h1>
                <p class="color-white mt-n1 mb-0 opacity-70">{{ $plan->detalle }}</p>
            </div>
            <div class="card-overlay bg-black opacity-80"></div>
        </div>

        <div class="modal fade" id="basicModal" data-bs-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 15px;box-shadow: 1px 1px 1px 1px teal;">
                    <div class="modal-header">
                        <h5 class="modal-title">Pedir permiso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <span>Esta seguro? Esta accion no se puede revertir</span>
                        <form action="" id="formBasic">
                            <input type="hidden" name="id" id="id">

                        </form>

                    </div>
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-xxs mb-3 rounded-s text-uppercase font-900 shadow-s border-magenta-dark  bg-magenta-light"
                            id="btnPermiso">Confirmar <span class="btn-icon-end"><i class="fa fa-calendar"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalPermiso" data-bs-backdrop="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 15px;box-shadow: 1px 1px 1px 1px teal;">
                    <div class="modal-header">
                        <h5 class="modal-title">Se encontro mas de un plan para este dia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <span>Si selecciona "todos", se otorgara el permiso a los planes de este dia, los mismos pasaran a un
                            dia despues de la ultima fecha que tenga en este plan</span>
                        <form action="" id="formPermiso">
                            <input type="hidden" name="id" id="id">

                        </form>

                    </div>
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-xxs mb-3 rounded-s text-uppercase font-900 shadow-s border-green-dark  bg-green-light"
                            id="btnPermisoUno">Solo 1 permiso <span class="btn-icon-end"><i class="fa fa-check"></i></span>
                        </button>
                        <button type="button"
                            class="btn btn-xxs mb-3 rounded-s text-uppercase font-900 shadow-s border-mint-dark  bg-mint-light"
                            id="btnPermisoTodos">Permiso para todos <span class="btn-icon-end"><i
                                    class="fa fa-check"></i></span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    @endpush
    @push('scripts')
        <script>
            $(document).ready(function() {
                //set initial state.
                //$('#textbox1').val(this.checked);

                $('.otro').change(function() {
                    if (this.checked) {
                        $('.empaques').removeClass('d-none');
                        $('.empaques :input').prop('disabled', false);
                        $("#pamesa").remove();
                    }
                });
                $('.mesa').change(function() {
                    if (this.checked) {
                        var idData = $(this).attr("data-id")

                        $('.empaques').addClass('d-none');
                        $('.empaques :input').prop('disabled', true);
                        //$('#empaque'+idData).remove();
                        var input = document.createElement("input");

                        input.setAttribute("type", "hidden");
                        input.setAttribute("id", "pamesa");
                        input.setAttribute("name", "empaque" + idData);
                        input.setAttribute("value", "Ninguno");
                        $('.' + $(this).attr('id')).append(input);
                    }
                });

                $("form").on("keyup change", function(e) {
                    data = []
                    var idForm = $(this).attr('id')

                    if (document.getElementById('carb' + idForm)) {
                        data.push('carb' + idForm)
                    }
                    if (document.getElementById('plato' + idForm)) {
                        data.push('plato' + idForm)
                    }
                    if (document.getElementById('envio' + idForm)) {
                        data.push('envio' + idForm)
                    }
                    if (document.getElementById('empaque' + idForm) && !document.getElementById('pamesa')) {
                        data.push('empaque' + idForm)
                    }

                    var cont = 0;
                    for (var i = 0; i < data.length; i++) {
                        console.log(data[i])
                        if ($("input[name='" + data[i] + "']:checked").val()) {
                            console.log($("input[name='" + data[i] + "']:checked").val())
                            cont++
                        }

                    }
                    if (document.getElementById('pamesa') && !document.getElementById('empaque' + idForm)) {
                        console.log(document.getElementById('pamesa'))
                        cont++
                    }
                    if (cont == data.length) {
                        $(this).find('button').prop('disabled', false);
                    } else {
                        $(this).find('button').prop('disabled', true);
                    }
                    console.log(cont);
                    // if ($("input[name='carb" + idForm + "']:checked").val() && $("input[name='plato" + idForm +
                    //         "']:checked").val() && $("input[name='envio" + idForm + "']:checked").val()) {
                    //     $(this).find('button').prop('disabled', false);
                    // } else {

                    // }

                })
            });
        </script>
    @endpush

@endsection
