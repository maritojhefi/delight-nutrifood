@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="{{ $plan->nombre }}" cabecera="appkit" />
    @env('local')
    @php
        $path = env('APP_URL');
    @endphp
    @endenv
    @production
        @php
            $path = GlobalHelper::getValorAtributoSetting('url_web');
        @endphp
    @endproduction
    <div class="card card-style mt-n4 mb-3">
        @if ($estadoMenu->activo)
            @if ($plan->editable)
                <div class="content text-white">
                    <h4>Personaliza tu menú de esta semana!</h4>
                    <p>
                        Quedan {{ $coleccion->count() }} pedidos esta semana, personaliza cada uno!
                    </p>
                </div>
                <div class="accordion" id="accordion-3">
                    @foreach ($coleccion as $lista)
                        {{-- @dd($lista) --}}
                        <div class="card card-style mb-3">
                            <div
                                class="list-group list-custom-small list-icon-0 bg-@if($lista['detalle'] == null && $lista['estado'] == 'pendiente'){{ 'mint' }}@elseif($lista['estado'] == 'desarrollo'){{ 'yellow' }}@else{{ 'green' }}@endif-dark ps-3 pe-4 ">
                                <a data-bs-toggle="collapse" class="{{ ($idPedidoEditar == $lista['id']) ? '' : 'collapsed' }}" href="#collapse-7{{ $lista['id'] }}"
                                    aria-expanded="{{ ($idPedidoEditar == $lista['id']) ? 'true' : 'false' }}">

                                    @if ($lista['detalle'] == null && $lista['estado'] == 'pendiente')
                                        <i class="fas fa-user-edit color-white"></i>
                                        <span class="font-14 color-white">{{ $lista['dia'] }}({{ $lista['fecha'] }})</span>
                                    @elseif($lista['estado'] == 'desarrollo')
                                        <i class="fab fa-whatsapp color-white"></i>
                                        <span class="font-14 color-white">{{ $lista['dia'] }}</span>
                                        <small for="" class="text-magenta text-white">
                                            (En desarrollo por whatsapp)
                                        </small>
                                    @else
                                        <i class="fas fa-save color-white"></i>
                                        <span class="font-14 color-white">{{ $lista['dia'] }}({{ $lista['fecha'] }})</span>
                                        <span class="text-white">Guardado!</span>
                                    @endif
                                    <i class="fa fa-angle-down color-white"></i>
                                </a>
                            </div>
                            <div class="collapse bordeado {{ ($idPedidoEditar == $lista['id']) ? 'show' : '' }}" id="collapse-7{{ $lista['id'] }}"
                                data-bs-parent="#accordion-3">
                                @if ($lista['detalle'] == null && $lista['estado'] == 'pendiente')
                                    <form class="d-flex flex-column flex-grow-1 px-3 py-2" action="{{ route('personalizardia') }}" id="{{ $lista['id'] }}"
                                        method="POST">
                                        @csrf
                                        <div class="row px-2 mb-0">
                                            @if ($plan->segundo)
                                                @include('client.miperfil.includes.seleccion-segundo')
                                                <hr class="my-2">
                                            @endif

                                            @if ($plan->carbohidrato)
                                                @include('client.miperfil.includes.seleccion-carbohidrato')
                                                <hr class="my-2">
                                            @endif
                                        </div>
                                        @include('client.miperfil.includes.seleccion-envio')
                                        <hr class="my-2">
                                        @include('client.miperfil.includes.seleccion-secundarios')

                                    </form>
                                @elseif($lista['estado'] == 'desarrollo')
                                    Ve a tu whatsapp para terminar de programar este plan!
                                @else
                                    @php
                                        $menuItems = [
                                            'SOPA' => [
                                                'label' => 'Sopa',
                                                'icon' => 'soup',
                                            ],
                                            'PLATO' => [
                                                'label' => 'Principal',
                                                'icon' => 'utensils-crossed',
                                            ],
                                            'CARBOHIDRATO' => [
                                                'label' => 'Carbohidrato',
                                                'icon' => 'sprout',
                                            ],
                                            'ENSALADA' => [
                                                'label' => 'Ensalada',
                                                'icon' => 'salad',
                                            ],
                                            'JUGO' => [
                                                'label' => 'Jugo',
                                                'icon' => 'glass-water',
                                            ],
                                            'EMPAQUE' => [
                                                'label' => 'Empaque',
                                                'icon' => 'package-2',
                                            ],
                                            'ENVIO' => [
                                                'label' => 'Envío',
                                                'icon' => 'truck',
                                            ],
                                        ];
                                    @endphp
                                    @php
                                        $menuItems = [
                                            'SOPA' => [
                                                'label' => 'Sopa',
                                                'icon' => 'soup',
                                                'color' => 'color-yellow-dark'
                                            ],
                                            'PLATO' => [
                                                'label' => 'Principal',
                                                'icon' => 'utensils-crossed',
                                                'color' => 'color-delight-red'
                                            ],
                                            'CARBOHIDRATO' => [
                                                'label' => 'Carbohidrato',
                                                'icon' => 'sprout',
                                                'color' => 'color-green-dark'
                                            ],
                                            'ENSALADA' => [
                                                'label' => 'Ensalada',
                                                'icon' => 'salad',
                                                'color' => 'color-teal-dark'
                                            ],
                                            'JUGO' => [
                                                'label' => 'Jugo',
                                                'icon' => 'glass-water',
                                                'color' => 'color-blue-light'
                                            ],
                                            'EMPAQUE' => [
                                                'label' => 'Empaque',
                                                'icon' => 'package-2',
                                                'color' => 'color-gray-dark'
                                            ],
                                            'ENVIO' => [
                                                'label' => 'Envío',
                                                'icon' => 'truck',
                                                'color' => 'color-gray-dark'
                                            ],
                                        ];
                                        
                                        // Count valid items
                                        $validItemCount = 0;
                                        $detalleDecoded = json_decode($lista['detalle'], true);
                                        foreach ($detalleDecoded as $plato => $valor) {
                                            if ($valor != '' && isset($menuItems[$plato])) {
                                                $validItemCount++;
                                            }
                                        }
                                        
                                        // Determine button column class based on even/odd count
                                        $buttonColClass = ($validItemCount % 2 === 0) ? 'col-12' : 'col-6';
                                    @endphp
                                    <div class="p-2 d-flex flex-column align-items-center">
                                        <div class="row m-0">
                                            @foreach ($detalleDecoded as $plato => $valor)
                                                @if ($valor != '' && isset($menuItems[$plato]))
                                                    <div class="col-6 px-0 py-2">
                                                        <div class="d-flex flex-row gap-2 align-items-center">
                                                            <i data-lucide="{{ $menuItems[$plato]['icon'] }}" class="lucide-icon {{ $menuItems[$plato]['color'] }}" style="min-height: 1.8rem; min-width: 1.8rem"></i>
                                                            <div class="d-flex flex-column">
                                                                <h3 class="detalle-label line-height-s font-15">{{ $menuItems[$plato]['label'] }}</h3>
                                                                <span class="item-value line-height-xs font-12 m-0 color-theme">
                                                                    {{ ucfirst($valor) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <div class="{{ $buttonColClass }} px-0 py-2">
                                                <div class="d-flex flex-row gap-2 align-items-center justify-content-center">
                                                    <a href="{{ route('editardia', $lista['id']) }}"
                                                        class="py-2 px-3 font-15 color-white rounded-s text-uppercase font-600 line-height-s border-red-dark bg-delight-red">Editar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @push('content-comentado')
                <div class="accordion" id="accordion-3">
                    @foreach ($coleccion as $lista)
                        {{-- @dd($lista) --}}
                        <div class="card card-style rounded-s ">
                            <div
                                class="list-group list-custom-small list-icon-0 bg-@if($lista['detalle'] == null && $lista['estado'] == 'pendiente'){{ 'mint' }}@elseif($lista['estado'] == 'desarrollo'){{ 'yellow' }}@else{{ 'green' }}@endif-dark ps-3 pe-4 ">
                                <a data-bs-toggle="collapse" class="no-effect collapsed" href="#collapse-7{{ $lista['id'] }}"
                                    aria-expanded="false">

                                    @if ($lista['detalle'] == null && $lista['estado'] == 'pendiente')
                                        <i class="fas fa-user-edit color-white"></i>
                                        <span class="font-14 color-white">{{ $lista['dia'] }}({{ $lista['fecha'] }})</span>
                                    @elseif($lista['estado'] == 'desarrollo')
                                        <i class="fab fa-whatsapp color-white"></i>
                                        <span class="font-14 color-white">{{ $lista['dia'] }}</span>
                                        <small for="" class="text-magenta text-white">
                                            (En desarrollo por whatsapp)
                                        </small>
                                    @else
                                        <i class="fas fa-save color-white"></i>
                                        <span class="font-14 color-white">{{ $lista['dia'] }}({{ $lista['fecha'] }})</span>
                                        <span class="text-white">Guardado!</span>
                                    @endif
                                    <i class="fa fa-angle-down color-white"></i>
                                </a>
                            </div>
                            <div class="ps-2 pe-4 collapse bordeado" id="collapse-7{{ $lista['id'] }}"
                                data-bs-parent="#accordion-3" style="">
                                <div class="p-2">
                                    @if ($lista['detalle'] == null && $lista['estado'] == 'pendiente')
                                        <form action="{{ route('personalizardia') }}" id="{{ $lista['id'] }}"
                                            method="POST">
                                            @csrf
                                            <div class="row mb-0">
                                                @if ($plan->segundo)
                                                    @include('client.miperfil.includes.seleccion-segundo')
                                                    <hr>
                                                @endif

                                                @if ($plan->carbohidrato)
                                                    @include('client.miperfil.includes.seleccion-carbohidrato')
                                                    <hr>
                                                @endif

                                            </div>

                                            @include('client.miperfil.includes.seleccion-envio')
                                            <hr>
                                            @include('client.miperfil.includes.seleccion-secundarios')

                                        </form>
                                    @elseif($lista['estado'] == 'desarrollo')
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
                @endpush
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
    @include('client.miperfil.includes.show-errores')

    <x-calendario-appkit-plan :plan="$plan" :usuario="$usuario" />

    
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
                <div class="modal-content card card-style shadow-xl pt-2 mx-2">
                    <div class="card-header bg-transparent d-flex flex-row justify-content-between align-items-center">
                        <h5 class="modal-title">Esta seguro? Esta accion no se puede revertir</h5>
                            <button type="button" class="btn-close bg-magenta-dark p-2" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="card-body">
                        <span>El plan seleccionado pasara a un dia despues del ultimo plan encontrado</span>
                        <form action="" id="formBasic">
                            <input type="hidden" name="id" id="id">

                        </form>
                    </div>
                    <div class="card-footer align-self-end bg-transparent border-top-0">

                        <button type="button"
                            class="btn btn-xxs mb-3 rounded-s text-uppercase font-900 shadow-s border-magenta-dark  bg-magenta-light"
                            id="btnPermiso">Confirmar Permiso<span class="btn-icon-end"><i class="ms-2 fa fa-calendar"></i></span>
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

        <div id="permiso-aceptado" class="snackbar-toast bg-magenta-dark color-white fade hide" data-delay="3000"
            data-autohide="true"><i class="fa fa-shopping-cart me-3"></i>Tu permiso fue aceptado!</div>
    @endpush

    @push('header')
    <!-- Estilos para el calendario generado mediante -->
    <style>

        .list-custom-small {
            position: relative; 
            z-index: 1; 
            
            /* background-color: rgba(0, 0, 0, 0.4);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            color: white; */
        }

        .list-custom-small::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            /* background-color: rgba(0, 0, 0, 0.4); */
            z-index: 2; /* Entre el fondo (1) y el contenido (3) */
        }

        .list-custom-small > * {
            z-index: 3;
            position: relative;
        }
    </style>
    @endpush()
    @push('scripts')
        <script>
            $(document).ready(function() {

                $('input[type="radio"]').change(function() {
                    if ($('.fac-radio[data-group="' + groupName + '"]'))
                        var groupName = $(this).attr('name'); // Obtener el nombre del grupo
                    $('.fac-radio[data-group="' + groupName + '"]').removeClass(
                    'font-700'); // Elimina la clase solo del grupo específico
                    if ($(this).prop('checked')) {
                        $(this).closest('.fac-radio').addClass(
                        'font-700'); // Agrega la clase al div padre más cercano
                    }
                });



                $('.otro').change(function() {
                    if (this.checked) {
                        $('.empaques').removeClass('d-none');
                        $('.empaques :input').prop('disabled', false);
                        $("#pamesa").remove();
                    }
                });

                $('.segundos').change(function() {
                    if (this.checked) {
                        if ($(this).attr('data-carbo') ==
                            '1') { // Si el segundo seleccionado tiene carbohidrato
                            $('.carbos' + $(this).attr('data-id')).removeClass(
                            'd-none'); // Mostrar elementos con carbohidratos
                            $('#box13-fac-radio' + $(this).attr('data-id')).prop('checked',
                            false); // Marcar el radio button
                        } else {
                            $('.carbos' + $(this).attr('data-id')).addClass(
                            'd-none'); // Ocultar elementos con carbohidratos
                            $('#box13-fac-radio' + $(this).attr('data-id')).prop('checked',
                            true); // Marcar el radio button
                        }
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
                        // console.log(data[i])
                        if ($("input[name='" + data[i] + "']:checked").val()) {
                            // console.log($("input[name='" + data[i] + "']:checked").val())
                            cont++
                        }

                    }
                    if (document.getElementById('pamesa') && !document.getElementById('empaque' + idForm)) {
                        // console.log(document.getElementById('pamesa'))
                        cont++
                    }
                    if (cont == data.length) {
                        $(this).find('button').prop('disabled', false);
                    } else {
                        $(this).find('button').prop('disabled', true);
                    }
                    // console.log(cont);
                    // if ($("input[name='carb" + idForm + "']:checked").val() && $("input[name='plato" + idForm +
                    //         "']:checked").val() && $("input[name='envio" + idForm + "']:checked").val()) {
                    //     $(this).find('button').prop('disabled', false);
                    // } else {

                    // }

                })
            });
        </script>
        <!-- Script para el retirado de pedidos individuales de un acordeon plan -->
        <script>
            $(document).ready( function () {
                    $('.confirmar-pedido-permiso').on('click', async function (e) {
                        e.preventDefault();
                        const idPedido = $(this).data('pedido');
                        console.log(`click en confirmar permiso para el pedido ${idPedido}`);

                        await PlanesService.permisoPedido(idPedido).then(
                            (respuestaPermiso) => {
                                // Cerrar modal
                                mostrarToastSuccess("¡Permiso obtenido!");
                                cerrarModalConfirmarPermiso(idPedido);
                                // Recargar pagina
                                location.reload();
                                // (Posible caso) En el caso de quedar pocos dias en el plan, se deberan renderizar el acordeon para el nuevo pedido
                                // Empujado un dia al final del plan, (lo mas sencillo es re renderizar toda la pagina)
                            }
                        ).catch(error => {
                            if (error.response) {
                                console.log("Respuesta al error permiso pedido:", error.response);
                            }
                            cerrarModalConfirmarPermiso(idPedido);
                            mostrarToastError("Ocurrio un error al solicitar el permiso");
                            console.log(`Ocurrio un error al solicitar el permiso ${idPedido}:`, error);
                        });

                        $(`#permiso-menu-${idPedido}`).removeClass('menu-active');
                        $(`.menu-hider`).removeClass('menu-active');
                    });
                }
            );

            const cerrarModalConfirmarPermiso = (idPedido) => {
                $(`#permiso-menu-${idPedido}`).removeClass('menu-active');
                $(`.menu-hider`).removeClass('menu-active');
            }
        </script>
        
    @endpush

@endsection