<div class="card card-style pb-3 mb-3">
    <div class="content">
        <div class="d-flex no-effect" data-trigger-switch="toggle-id-2" data-bs-toggle="collapse" href="#collapseExample2"
            role="button" aria-expanded="false" aria-controls="collapseExample2">
            <div class="pt-2 mt-1">
                <h4>Almuerzos Saludables</h4>
            </div>
        </div>
        <p>
            Nuestros menus cambian cada semana y tenemos varias opciones!
        </p>
    </div>
    @php
        $diaActual = false;
    @endphp
    <div class="accordion mb-2" id="accordion-3">
        @foreach ($almuerzos as $almuerzo)
            @php
                $dia = $almuerzo->dia;
                $feriado = false;
                if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $dia) {
                    $diaActual = true;
                }
                if ($diaActual) {
                    // Convertimos el nombre del día a un formato que Carbon entiende
                    $diaSemana = match ($dia) {
                        'Lunes' => 0,
                        'Martes' => 1,
                        'Miercoles' => 2,
                        'Jueves' => 3,
                        'Viernes' => 4,
                        'Sabado' => 5,
                        'Domingo' => 6,
                        default => null,
                    };
                    // Obtener la fecha del inicio de la semana
                    $fechaInicioSemana = Carbon\Carbon::now()->startOfWeek();

                    // Si es domingo, avanzar al inicio de la próxima semana
                    if (Carbon\Carbon::now()->isSunday()) {
                        $fechaInicioSemana = $fechaInicioSemana->addWeek();
                    }

                    // Obtener la fecha del día específico dentro de la semana actual
                    $fechaDia = $fechaInicioSemana->copy()->addDays($diaSemana)->format('Y-m-d');
                    $feriado = DB::table('plane_user')->where('start', $fechaDia)->where('title', 'feriado')->exists();
                }
            @endphp
            @if ($diaActual && !$feriado)
                <div data-card-height="90"
                    class="card card-style bg-25 mb-0 rounded-s m-3 {{ App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia ? 'gradient-border' : '' }}"
                    style="height: 90px;background-image:url({{ asset(GlobalHelper::getValorAtributoSetting('mi_perfil_deligth')) }}">


                    <div class="card-center">
                        <button class="btn accordion-btn collapsed" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $almuerzo->id }}" aria-expanded="false">
                            <h4 class="text-center color-white text-uppercase">{{ $almuerzo->dia }}</h4>
                            @if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                <p class="text-center color-white opacity-70 mb-0 mt-n2 fs-20">Menú disponible para
                                    hoy!</p>
                            @else
                                <p class="text-center color-white opacity-70 mb-0 mt-n2">Descubre el menu para este dia
                                </p>
                            @endif

                        </button>
                    </div>
                    <div class="card-overlay rounded-s bg-black opacity-70"></div>
                </div>
                <div id="collapse{{ $almuerzo->id }}"
                    class="collapse {{ App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia ? 'show' : '' }}"
                    data-bs-parent="#accordion-3" style="">
                    <div class="content">
                        <div class="row mb-0 justify-content-center align-items-center text-center">
                            <div class="col-5 text-center">
                                <p class="color-theme font-700">Sopa</p>
                            </div>
                            <div class="col-7">
                                @if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia &&
                                        $almuerzo->sopa_estado &&
                                        $almuerzo->sopa_cant > 0)
                                    <p class="font-400">{{ $almuerzo->sopa }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i></p>
                                @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <del class="font-400">{{ $almuerzo->sopa }}</del> <i
                                        class="fa fa-times-circle color-red-dark me-2"></i>
                                @else
                                    <p class="font-400">{{ $almuerzo->sopa }} </p>
                                @endif

                            </div>
                            <div class="divider mb-3"></div>
                            <div class="col-5">
                                <p class="color-theme font-700">Segundo Ejecutivo</p>
                            </div>
                            <div class="col-7">
                                @if (
                                    $almuerzo->ejecutivo_estado &&
                                        $almuerzo->ejecutivo_cant > 0 &&
                                        App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <p class="font-400" style="line-height: 1.2;">{{ $almuerzo->ejecutivo }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i>
                                        {!! $almuerzo->ejecutivo_tiene_carbo ? '' : '<br>(sin carbo)' !!}</p>
                                @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <del class="font-400">{{ $almuerzo->ejecutivo }}</del> <i
                                        class="fa fa-times-circle color-red-dark me-2"></i>
                                @else
                                    <p class="font-400">{{ $almuerzo->ejecutivo }} </p>
                                @endif
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Segundo Dieta</p>
                            </div>
                            <div class="col-7">
                                @if (
                                    $almuerzo->dieta_estado &&
                                        $almuerzo->dieta_cant > 0 &&
                                        App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <p class="font-400" style="line-height: 1.2;">{{ $almuerzo->dieta }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i>
                                        {!! $almuerzo->dieta_tiene_carbo ? '' : '<br>(sin carbo)' !!}</p>
                                @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <del class="font-400">{{ $almuerzo->dieta }}</del> <i
                                        class="fa fa-times-circle color-red-dark me-2"></i>
                                @else
                                    <p class="font-400">{{ $almuerzo->dieta }} </p>
                                @endif
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Segundo Veggie</p>
                            </div>
                            <div class="col-7">
                                @if (
                                    $almuerzo->vegetariano_estado &&
                                        $almuerzo->vegetariano_cant > 0 &&
                                        App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <p class="font-400" style="line-height: 1.2;">{{ $almuerzo->vegetariano }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i>
                                        {!! $almuerzo->vegetariano_tiene_carbo ? '' : '<br>(sin carbo)' !!}</p>
                                @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <del class="font-400">{{ $almuerzo->vegetariano }}</del> <i
                                        class="fa fa-times-circle color-red-dark me-2"></i>
                                @else
                                    <p class="font-400">{{ $almuerzo->vegetariano }}</p>
                                @endif
                            </div>
                            <div class="divider mb-3"></div>
                            <div class="col-5">
                                <p class="color-theme font-700">Carbohidrato 1</p>
                            </div>
                            <div class="col-7">
                                @if (
                                    $almuerzo->carbohidrato_1_estado &&
                                        $almuerzo->carbohidrato_1_cant > 0 &&
                                        App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <p class="font-400">{{ $almuerzo->carbohidrato_1 }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i></p>
                                @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    {{-- <del class="font-400">{{ $almuerzo->carbohidrato_1 }}</del> <i
                                        class="fa fa-times-circle color-red-dark me-2"></i> --}}
                                @else
                                    <p class="font-400">{{ $almuerzo->carbohidrato_1 }}</p>
                                @endif
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Carbohidrato 2</p>
                            </div>
                            <div class="col-7">
                                @if (
                                    $almuerzo->carbohidrato_2_estado &&
                                        $almuerzo->carbohidrato_2_cant > 0 &&
                                        App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <p class="font-400">{{ $almuerzo->carbohidrato_2 }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i></p>
                                @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    {{-- <del class="font-400">{{ $almuerzo->carbohidrato_2 }}</del> <i
                                        class="fa fa-times-circle color-red-dark me-2"></i> --}}
                                @else
                                    <p class="font-400">{{ $almuerzo->carbohidrato_2 }} </p>
                                @endif
                            </div>
                            <div class="col-5">
                                <p class="color-theme font-700">Carbohidrato 3</p>
                            </div>
                            <div class="col-7">
                                @if (
                                    $almuerzo->carbohidrato_3_estado &&
                                        $almuerzo->carbohidrato_3_cant > 0 &&
                                        App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <p class="font-400">{{ $almuerzo->carbohidrato_3 }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i></p>
                                @elseif(App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    {{-- <del class="font-400">{{ $almuerzo->carbohidrato_3 }}</del> <i
                                        class="fa fa-times-circle color-red-dark me-2"></i> --}}
                                @else
                                    <p class="font-400">{{ $almuerzo->carbohidrato_3 }} </p>
                                @endif
                            </div>
                            <div class="divider mb-3"></div>
                            <div class="col-5">
                                <p class="color-theme font-700">Jugo </p>
                            </div>
                            <div class="col-7">
                                @if (App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia)
                                    <p class="font-400">{{ $almuerzo->jugo }} <i
                                            class="fa fa-check-circle color-green-dark me-2"></i></p>
                                @else
                                    <p class="font-400">{{ $almuerzo->jugo }} </p>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            @elseif($feriado)
                <div data-card-height="90"
                    class="card card-style bg-25 mb-0 rounded-s m-3 {{ App\Helpers\WhatsappAPIHelper::saber_dia(date('Y-m-d')) == $almuerzo->dia ? 'gradient-border' : '' }}"
                    style="height: 90px;background-image:url({{ asset(GlobalHelper::getValorAtributoSetting('dia_noche_inicio')) }}">

                    <div class="card-center">
                        <button class="btn accordion-btn">
                            <h4 class="text-center color-red-light text-uppercase">{{ $almuerzo->dia }}</h4>
                            <p class="text-center color-white opacity-70 mb-0 mt-n2">Dia sin atención</p>
                        </button>
                    </div>
                    <div class="card-overlay rounded-s bg-black opacity-70"></div>
                </div>
            @endif
        @endforeach
    </div>
</div>
