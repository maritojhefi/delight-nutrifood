@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="{{ $plan->nombre }}" cabecera="bordeado" />
    <div class="card card-style">
        <div class="content text-white">
            <h4>Personaliza tu plan de esta semana!</h4>
            <p>
                Quedan {{ $coleccion->count() }} dias, personaliza cada uno!
            </p>
        </div>
        <div class="accordion mt-4" id="accordion-3">
            @foreach ($coleccion as $lista)
                <div class="card card-style">
                    <div
                        class="list-group list-custom-small list-icon-0 bg-{{ $lista['detalle'] == null ? 'mint' : 'green' }}-dark ps-3 pe-4 ">
                        <a data-bs-toggle="collapse" class="no-effect collapsed" href="#collapse-7{{ $lista['id'] }}"
                            aria-expanded="false">

                            @if ($lista['detalle'] == null || $lista['detalle'] == '')
                                <i class="fas fa-user-edit color-white"></i>
                                <span class="font-14 color-white">{{ $lista['dia'] }}({{ $lista['fecha'] }})</span>
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
                            @if ($lista['detalle'] == null || $lista['detalle'] == '')
                                <form action="{{ route('personalizardia') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        @if ($plan->segundo)
                                            <div class="col-6">
                                                <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">Elija su plato</mark>
                                                <div class="fac fac-radio fac-default"><span></span>
                                                    <input id="box1-fac-radio{{ $lista['id'] }}" type="radio"
                                                        name="plato{{ $lista['id'] }}" value="{{ $lista['ejecutivo'] }}">
                                                    <label
                                                        for="box1-fac-radio{{ $lista['id'] }}">{{ $lista['ejecutivo'] }}</label>
                                                </div>
                                                <div class="fac fac-radio fac-default"><span></span>
                                                    <input id="box2-fac-radio{{ $lista['id'] }}" type="radio"
                                                        name="plato{{ $lista['id'] }}" value="{{ $lista['dieta'] }}">
                                                    <label
                                                        for="box2-fac-radio{{ $lista['id'] }}">{{ $lista['dieta'] }}</label>
                                                </div>
                                                <div class="fac fac-radio fac-default"><span></span>
                                                    <input id="box3-fac-radio{{ $lista['id'] }}" type="radio"
                                                        name="plato{{ $lista['id'] }}"
                                                        value="{{ $lista['vegetariano'] }}">
                                                    <label
                                                        for="box3-fac-radio{{ $lista['id'] }}">{{ $lista['vegetariano'] }}</label>
                                                </div>

                                            </div>
                                        @endif
                                        @if ($plan->carbohidrato)
                                            <div class="col-6">
                                                <mark class="highlight ps-2 font-12 pe-2 bg-blue-dark">Elija su
                                                    carbohidrato</mark>
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


                                            </div>
                                        @endif

                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <mark class="highlight ps-2 font-12 pe-2 bg-mint-dark">Tipo de Envio</mark>
                                            <div class="fac fac-radio fac-default"><span></span>
                                                <input id="box7-fac-radio{{ $lista['id'] }}" type="radio"
                                                    name="envio{{ $lista['id'] }}" value="{{ $lista['envio1'] }}">
                                                <label
                                                    for="box7-fac-radio{{ $lista['id'] }}">{{ $lista['envio1'] }}</label>
                                            </div>
                                            <div class="fac fac-radio fac-default"><span></span>
                                                <input id="box8-fac-radio{{ $lista['id'] }}" type="radio"
                                                    name="envio{{ $lista['id'] }}" value="{{ $lista['envio2'] }}">
                                                <label
                                                    for="box8-fac-radio{{ $lista['id'] }}">{{ $lista['envio2'] }}</label>
                                            </div>
                                            <div class="fac fac-radio fac-default"><span></span>
                                                <input id="box9-fac-radio{{ $lista['id'] }}" type="radio"
                                                    name="envio{{ $lista['id'] }}" value="{{ $lista['envio3'] }}">
                                                <label
                                                    for="box9-fac-radio{{ $lista['id'] }}">{{ $lista['envio3'] }}</label>
                                            </div>

                                        </div>
                                        <div class="col-6">
                                            <mark class="highlight ps-2 font-12 pe-2 bg-green-dark">Tipo de empaque</mark>
                                            <div class="fac fac-radio fac-default"><span></span>
                                                <input id="box10-fac-radio{{ $lista['id'] }}" type="radio"
                                                    name="empaque{{ $lista['id'] }}" value="{{ $lista['empaque1'] }}">
                                                <label
                                                    for="box10-fac-radio{{ $lista['id'] }}">{{ $lista['empaque1'] }}</label>
                                            </div>
                                            <div class="fac fac-radio fac-default"><span></span>
                                                <input id="box11-fac-radio{{ $lista['id'] }}" type="radio"
                                                    name="empaque{{ $lista['id'] }}" value="{{ $lista['empaque2'] }}">
                                                <label
                                                    for="box11-fac-radio{{ $lista['id'] }}">{{ $lista['empaque2'] }}</label>
                                            </div>



                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        @if ($plan->sopa)
                                            <h4 class="col-6 font-500  font-13 text-white"><mark
                                                    class="highlight ps-2 font-12 pe-2 bg-orange-dark">Sopa</mark></h4>
                                            <p class="col-6 mb-3 text-end  font-900 mb-0">
                                                {{ $lista['sopa'] }}
                                            </p>
                                            <input type="hidden" value="{{ $lista['sopa'] }}" name="sopa">
                                        @endif
                                        @if ($plan->ensalada)
                                            <h4 class="col-6 font-500 font-13 text-white"><mark
                                                    class="highlight ps-2 font-12 pe-2 bg-orange-dark">Ensalada</mark></h4>
                                            <p class="col-6 mb-3 text-end  font-900 mb-0">
                                                {{ $lista['ensalada'] }}
                                            </p>
                                            <input type="hidden" value="{{ $lista['ensalada'] }}" name="ensalada">
                                        @endif
                                        @if ($plan->jugo)
                                            <h4 class="col-6 font-500 font-13 text-white"><mark
                                                    class="highlight ps-2 font-12 pe-2 bg-orange-dark">Jugo</mark></h4>
                                            <p class="col-6 mb-3 text-end  font-900 mb-0">
                                                {{ $lista['jugo'] }}
                                            </p>
                                            <input type="hidden" value="{{ $lista['jugo'] }}" name="jugo">
                                        @endif

                                        <input type="hidden" value="{{ $lista['dia'] }}" name="dia">
                                        <input type="hidden" value="{{ $lista['id'] }}" name="id">
                                        <input type="hidden" value="{{ $plan->id }}" name="plan">
                                       
                                        
                                        <div class="col">
                                            <button type="submit"
                                                class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-mint-dark">Guardar
                                                {{ $lista['dia'] }}</button>
                                        </div>
                                    </div>

                                </form>
                            @else
                                <ul class="icon-list">
                                    @foreach (json_decode($lista['detalle'], true) as $plato => $valor)
                                    @if ($valor!="")
                                    <li><i class="fa fa-check color-green-dark"></i>{{ $plato }} : <label
                                        for="" class="font-700 mb-0">{{ $valor }}</label> </li>
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

    </div>
    @if ($errors->any())
        <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert">
            <span><i class="fa fa-times"></i></span>
            <strong>Incompleto!</strong> Formulario incompleto del dia seleccionado.

            
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
    @once
        <div class="card card-style bg-18" data-card-height="150" style="height: 150px;">
            <div class="card-center ms-3">
                <h1 class="color-white">Detalles del plan:</h1>
                <p class="color-white mt-n1 mb-0 opacity-70">{{$plan->detalle}}</p>
            </div>
            <div class="card-overlay bg-black opacity-80"></div>
        </div>
    @endonce
@endsection
