<div>
    @livewire('admin.pedidos-realtime-component')
    <div class="card col-12 letra12 bordeado">
        <div class="card-header d-block">
            <div class="row align-items-center mx-auto justify-content-center">
                <div class="col-12 col-md-6 col-lg-3 mb-2 mb-lg-0 text-center">
                    <a href="#" wire:click="cambiarDisponibilidad" onclick="event.preventDefault()">
                        <span class="badge badge-pill badge-primary">Revisar disponibilidad</span>
                    </a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2 mb-lg-0 text-center">
                    <strong>{{ App\Helpers\GlobalHelper::fechaFormateada(2, $fechaSeleccionada) }}</strong>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2 mb-lg-0">
                    <input type="date" class="form-control bordeado p-1 m-0 px-2" style="height: 30px"
                        wire:model="fechaSeleccionada" wire:change="cambioDeFecha">
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2 mb-lg-0">
                    <input type="text" class="form-control form-control-sm px-2 m-0" style="height: 30px"
                        wire:model.debounce.500ms="search" placeholder="Buscar cliente">
                </div>
            </div>

            <hr class="my-2">

            <!-- Fila de filtros de planes -->
            <div class="row align-items-center mx-auto justify-content-center">
                <div class="col-12 col-md-auto mb-2 mb-md-0">
                    <select class="form-control p-1 py-0 m-0 bordeado" style="height: 30px"
                        wire:model="planSeleccionado">
                        <option value="">Seleccionar plan para filtrar</option>
                        @foreach ($planesColeccion as $plan)
                            @if (!in_array($plan->id, $planesSeleccionados))
                                <option value="{{ $plan->id }}">{{ $plan->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-auto d-none d-md-block">
                    <div class="m-1">
                        <div wire:loading class="spinner-border" style="width: 20px;height:20px" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila de badges de planes seleccionados -->
            @if (!empty($planesSeleccionados))
                <div class="row mt-2">
                    <div class="col-12 d-flex align-items-center flex-wrap justify-content-center" style="gap: 8px;">
                        <span class="text-muted" style="font-size: 12px; font-weight: 600;">
                            Filtrando por:
                        </span>
                        @foreach ($planesColeccion as $plan)
                            @if (in_array($plan->id, $planesSeleccionados))
                                <span class="badge badge-primary d-inline-flex align-items-center plan-badge"
                                    style="padding: 6px 12px; font-size: 12px; border-radius: 15px; cursor: default; animation: fadeIn 0.3s;">
                                    <span>{{ $plan->nombre }}</span>
                                    <button wire:click="removerPlan({{ $plan->id }})" class="btn btn-link p-0 ml-1"
                                        style="color: white; text-decoration: none; font-size: 14px; line-height: 1; margin-left: 6px;"
                                        title="Remover filtro">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </span>
                            @endif
                        @endforeach

                        <!-- Botón para limpiar todos los filtros si hay más de uno -->
                        @if (count($planesSeleccionados) > 1)
                            <button wire:click="limpiarFiltrosPlanes" class="btn btn-sm btn-outline-danger btn-limpiar"
                                style="padding: 4px 10px; font-size: 11px; border-radius: 12px; font-weight: 600;"
                                title="Limpiar todos los filtros">
                                <i class="fa fa-trash"></i> Limpiar todo
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <span class="badge badge-info">
                            <i class="fa fa-info-circle"></i> Mostrando todos los planes
                        </span>
                    </div>
                </div>
            @endif

        </div>

        <div class="">
            <div class="table-responsive letra14" style="">
                <table class="table table-responsive-sm" style="table-layout: auto;">
                    <th style="">
                        <tr>
                            <td></td>
                            <td></td>
                            @php
                                $colores = ['warning', 'success', 'danger', 'primary', 'secondary', 'info', 'dark'];
                                $totalColores = count($colores);
                                $cont = 1;
                                $contColor = 0;
                            @endphp
                            @foreach ($totalEspera[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                @php
                                                    // Calcular el índice del color basado en la iteración
                                                    $colorIndex = $contColor % $totalColores;
                                                    $color = $colores[$colorIndex];
                                                @endphp
                                                @if ($cont == 1)
                                                    <span class="badge  badge-xxs badge-{{ $color }}"> <small
                                                            class="text-white">{{ $cantidad }}</small></span>
                                                    <br>
                                                @elseif ($cont == 4)
                                                    <span class="badge  badge-xxs badge-{{ $color }}"> <small
                                                            class="text-white">{{ Str::limit($nombre, 8, '') }}:{{ $cantidad }}</small></span>
                                                    <br>
                                                @else
                                                    <span class="badge  badge-xxs badge-{{ $color }}"> <small
                                                            class="text-white">{{ Str::limit($nombre, 15, '') }}:{{ $cantidad }}</small></span>
                                                    <br>
                                                @endif
                                            @endif
                                            @php
                                                $contColor++;
                                            @endphp
                                        @endforeach
                                    </small></th>
                                @php
                                    $cont++;
                                @endphp
                            @endforeach

                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sopa</th>
                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            <th>Empaque</th>
                            <th>Envio</th>
                            <th>Plan</th>
                        </tr>
                    </th>
                    <tbody style="">
                        @foreach ($coleccion->whereIn('COCINA', ['espera', 'solo-sopa', 'solo-segundo']) as $lista)
                            <tr class="
                                @if ($lista['ENVIO'] == 'a.- Delivery') table-primary
                                @elseif($lista['ENVIO'] == 'b.- Para llevar(Paso a recoger)') table-info
                                @elseif($lista['ENVIO'] == 'c.- Para Mesa') table-success @endif"
                                style="border-color:#211d1d !important;cursor: pointer;"
                                @if ($lista['ESTADO'] != 'permiso') onclick="event.preventDefault(); mostrarPlanCocina({
                                    id: {{ $lista['ID'] }},
                                    nombre: '{{ addslashes($lista['NOMBRE']) }}',
                                    plan: '{{ addslashes($lista['PLAN']) }}',
                                    sopa: '{{ addslashes($lista['SOPA']) }}',
                                    plato: '{{ addslashes($lista['PLATO']) }}',
                                    carbohidrato: '{{ addslashes($lista['CARBOHIDRATO']) }}',
                                    envio: '{{ addslashes($lista['ENVIO']) }}',
                                    empaque: '{{ addslashes($lista['EMPAQUE']) }}',
                                    cocina: '{{ $lista['COCINA'] }}',
                                    envio_icon: '{{ $lista['ENVIO_ICON'] }}',
                                    cliente_ingresado: {{ $lista['CLIENTE_INGRESADO'] ? 'true' : 'false' }}
                                })" @endif>
                                <td style="border-color:#211d1d !important"><strong>
                                    {!! $lista['ESTADO'] == 'permiso'
                                        ? '<a href="javascript:void(0)" class="text-primary">PERMISO </a>'
                                        : $loop->iteration !!}</strong>
                                    @if (isset($lista['CLIENTE_INGRESADO']) && $lista['CLIENTE_INGRESADO'])
                                        <img src="{{ asset('images/welcome.gif') }}" alt="Cliente ingresado"
                                            style="width: 20px; height: 20px;">
                                        @if (isset($lista['CLIENTE_INGRESADO_AT']) && $lista['CLIENTE_INGRESADO_AT'])
                                            <br>
                                            <span class="timer-simple text-muted"
                                                data-timestamp="{{ $lista['CLIENTE_INGRESADO_AT'] }}"
                                                style="font-size: 9px;">
                                                00:00
                                            </span>
                                        @endif
                                    @endif
                                    
                                </td>
                                <td style="border-color:#211d1d !important"><small>
                                        @if ($lista['ESTADO'] == 'permiso')
                                            <del class="text-muted">{{ Str::limit($lista['NOMBRE'], 35) }}</del>
                                        @else
                                            <a
                                                href="javascript:void(0)"><strong>{{ Str::limit($lista['NOMBRE'], 35) }}</strong></a>
                                        @endif
                                    </small>
                                </td>
                                @if ($lista['COCINA'] == 'solo-sopa')
                                    <td style="border-color:#211d1d !important">
                                        <small>
                                            <a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            @if ($lista['SOPA_DESPACHADA_AT'])
                                                <br>
                                                <span class="timer-badge badge badge-info badge-xs mt-1"
                                                    data-timestamp="{{ $lista['SOPA_DESPACHADA_AT'] }}"
                                                    style="font-size: 10px;">
                                                    00:00
                                                </span>
                                            @endif
                                        </small>
                                    </td>
                                @else
                                    <td style="border-color:#211d1d !important">
                                        <small>{{ $lista['SOPA'] != '' ? 'SI' : '' }}</small>
                                    </td>
                                @endif

                                @if ($lista['COCINA'] == 'solo-segundo')
                                    <td style="border-color:#211d1d !important">
                                        <small>
                                            <a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            @if ($lista['SEGUNDO_DESPACHADO_AT'])
                                                <br>
                                                <span class="timer-badge badge badge-info badge-xs mt-1"
                                                    data-timestamp="{{ $lista['SEGUNDO_DESPACHADO_AT'] }}"
                                                    style="font-size: 10px;">
                                                    00:00
                                                </span>
                                            @endif
                                        </small>
                                    </td>
                                @else
                                    <td style="border-color:#211d1d !important"><small>{{ $lista['PLATO'] }}</small>
                                    </td>
                                @endif

                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</small>
                                </td>
                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['EMPAQUE'], 15) }}</small>
                                </td>
                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['ENVIO'], 15) }}</small>
                                </td>
                                <td style="border-color:#211d1d !important">
                                    <small>{{ Str::limit($lista['PLAN'], 25) }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <center style="" class="letra14"><strong>DESPACHADOS</strong></center>
                <table class="table table-responsive-sm" style="table-layout: auto;">
                    <thead style="">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sopa</th>
                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            <th>Empaque</th>
                            <th>Envio</th>
                        </tr>
                    </thead>
                    <tbody style="">
                        @foreach ($coleccion->where('COCINA', 'despachado') as $lista)
                            <tr class="table-danger" style="">
                                <td style="">
                                    {{ $loop->iteration }}
                                </td>
                                <td style="">{{ Str::limit($lista['NOMBRE'], 20) }}</td>
                                <td style="">{{ $lista['SOPA'] != '' ? 'SI' : '' }}</td>
                                <td style="">{{ $lista['PLATO'] }}</td>
                                <td style="">{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</td>
                                <td style="">{{ $lista['EMPAQUE'] }}</td>
                                <td style="">{{ $lista['ENVIO'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            @php
                                $colores = ['warning', 'success', 'danger', 'primary', 'secondary', 'info', 'dark'];
                                $totalColores = count($colores);
                                $cont = 0;
                            @endphp
                            @foreach ($totalDespachado[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                @php
                                                    // Calcular el índice del color basado en la iteración
                                                    $colorIndex = $cont % $totalColores;
                                                    $color = $colores[$colorIndex];
                                                @endphp
                                                <small
                                                    class="text-{{ $color }}">{{ Str::limit($nombre, 15) }}:{{ $cantidad }}</small><br>
                                            @endif
                                            @php
                                                $cont++;
                                            @endphp
                                        @endforeach
                                    </small></th>
                            @endforeach

                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <h4>{{ $search ? 'Encontrados' : 'Planes para este dia' }} : {{ $coleccion->count() }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Función para mostrar el plan de cocina en SweetAlert
        function mostrarPlanCocina(planData) {
            // Verificar si el cliente NO está ingresado
            if (!planData.cliente_ingresado) {
                // Mostrar confirmación para marcar ingreso primero
                Swal.fire({
                    icon: 'question',
                    title: '¡Cliente no ha ingresado!',
                    html: `
                        <div style="text-align: center;">
                            <p style="font-size: 16px; margin-bottom: 10px;">
                                <strong>${planData.nombre}</strong> aún no ha sido marcado como ingresado.
                            </p>
                            <p style="font-size: 14px; color: #6b7280;">
                                ¿Desea marcar su ingreso ahora?
                            </p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check"></i> Sí, marcar ingreso',
                    cancelButtonText: '<i class="fa fa-times"></i> Cancelar',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    customClass: {
                        popup: 'animate__animated animate__pulse',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Llamar al método Livewire para marcar como ingresado
                        @this.call('marcarComoIngresado', planData.id);
                    }
                });
                return; // No continuar mostrando el modal de detalle
            }

            // Si el cliente ya está ingresado, continuar normalmente
            const tieneSopa = planData.sopa !== '';
            const tienePlato = planData.plato !== '';
            const puedeDespacharSopa = tieneSopa && (planData.cocina === 'espera' || planData.cocina === 'solo-segundo');
            const puedeDespacharPlato = tienePlato && (planData.cocina === 'espera' || planData.cocina === 'solo-sopa');

            Swal.fire({
                title: `<div style="padding: 0; margin: 0; line-height: 1.1;">
                            <h3 style="color: white; margin: 0 0 2px 0; font-weight: 700; font-size: clamp(13px, 3.5vw, 18px);">Plan de ${planData.nombre}</h3>
                            <h2 style="color: white; margin: 0; font-weight: 700; font-size: clamp(15px, 4.5vw, 22px);">${planData.plan}</h2>
                        </div>`,
                html: `
                    <div style="display: grid; gap: clamp(6px, 1.5vw, 12px); padding: 0;">
                       
                        <!-- Grid de Platillos -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: clamp(6px, 1.5vw, 12px);">
                            <!-- Tarjeta de Sopa -->
                            <div style="background: white; border: 2px solid ${tieneSopa ? '#10b981' : '#ef4444'}; border-radius: clamp(8px, 1.5vw, 12px); padding: clamp(8px, 2vw, 16px); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <div style="text-align: center;">
                                    <i class="fa fa-bowl-food" style="font-size: clamp(18px, 4.5vw, 28px); color: ${tieneSopa ? '#10b981' : '#ef4444'}; margin-bottom: clamp(4px, 1vw, 8px);"></i>
                                    <div style="font-size: clamp(8px, 2vw, 11px); color: #6b7280; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; margin-bottom: 4px;">Sopa</div>
                                    <div style="font-size: clamp(11px, 3vw, 15px); font-weight: 700; color: ${tieneSopa ? '#1f2937' : '#ef4444'}; min-height: clamp(28px, 7vw, 45px); display: flex; align-items: center; justify-content: center; margin-bottom: clamp(6px, 1.5vw, 12px); line-height: 1.1; padding: 0 2px;">
                                        ${tieneSopa ? planData.sopa : 'SIN SOPA'}
                                    </div>
                                    ${tieneSopa ? `
                                                ${puedeDespacharSopa ? `
                                            <button onclick="despacharItemSopa(${planData.id})" 
                                                    style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: clamp(7px, 2vw, 10px); border-radius: 7px; font-weight: 700; font-size: clamp(9px, 2.2vw, 13px); cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 6px rgba(102, 126, 234, 0.4);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 10px rgba(102, 126, 234, 0.6)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(102, 126, 234, 0.4)'">
                                                Despachar Sopa ?
                                            </button>
                                        ` : `
                                            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: clamp(7px, 2vw, 10px); border-radius: 7px; font-weight: 700; font-size: clamp(9px, 2.2vw, 13px); box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);">
                                                <i class="fa fa-check-circle"></i> Despachado
                                            </div>
                                        `}
                                            ` : ''}
                                </div>
                            </div>

                            <!-- Tarjeta de Segundo -->
                            <div style="background: white; border: 2px solid ${tienePlato ? '#f59e0b' : '#ef4444'}; border-radius: clamp(8px, 1.5vw, 12px); padding: clamp(8px, 2vw, 16px); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <div style="text-align: center;">
                                    <i class="fa fa-drumstick-bite" style="font-size: clamp(18px, 4.5vw, 28px); color: ${tienePlato ? '#f59e0b' : '#ef4444'}; margin-bottom: clamp(4px, 1vw, 8px);"></i>
                                    <div style="font-size: clamp(8px, 2vw, 11px); color: #6b7280; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; margin-bottom: 4px;">Segundo</div>
                                    <div style="font-size: clamp(11px, 3vw, 15px); font-weight: 700; color: ${tienePlato ? '#1f2937' : '#ef4444'}; min-height: clamp(28px, 7vw, 45px); display: flex; align-items: center; justify-content: center; margin-bottom: clamp(6px, 1.5vw, 12px); line-height: 1.1; padding: 0 2px;">
                                        ${tienePlato ? planData.plato : 'SIN PLATO'}
                                    </div>
                                    ${tienePlato ? `
                                                ${puedeDespacharPlato ? `
                                            <button onclick="despacharItemSegundo(${planData.id})" 
                                                    style="width: 100%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; padding: clamp(7px, 2vw, 10px); border-radius: 7px; font-weight: 700; font-size: clamp(9px, 2.2vw, 13px); cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.4);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 10px rgba(245, 158, 11, 0.6)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(245, 158, 11, 0.4)'">
                                                Despachar Segundo ?
                                            </button>
                                        ` : `
                                            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: clamp(7px, 2vw, 10px); border-radius: 7px; font-weight: 700; font-size: clamp(9px, 2.2vw, 13px); box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);">
                                                <i class="fa fa-check-circle"></i> Despachado
                                            </div>
                                        `}
                                            ` : ''}
                                </div>
                            </div>
                        </div>

                        <!-- Grid de Información Adicional - 2 columnas en móvil -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(clamp(85px, 24vw, 145px), 1fr)); gap: clamp(5px, 1.5vw, 10px); margin-top: 0;">
                            <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); padding: clamp(6px, 2vw, 12px); border-radius: 8px; text-align: center; box-shadow: 0 2px 6px rgba(168, 237, 234, 0.3);">
                                <i class="flaticon-033-feather text-black" style="font-size: clamp(14px, 3.5vw, 22px);"></i>
                                <div style="font-size: clamp(7px, 1.8vw, 10px); text-transform: uppercase; letter-spacing: 0.2px; font-weight: 600; color: black; margin-top: 2px;">Carbohidrato</div>
                                <div style="font-size: clamp(9px, 2.2vw, 12px); color: black; font-weight: 700; margin-top: 2px; line-height: 1.1;">${planData.carbohidrato || 'SIN CARBO'}</div>
                            </div>
                            <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); padding: clamp(6px, 2vw, 12px); border-radius: 8px; text-align: center; box-shadow: 0 2px 6px rgba(168, 237, 234, 0.3);">
                                <i class="${planData.envio_icon} text-black" style="font-size: clamp(14px, 3.5vw, 22px);"></i>
                                <div style="font-size: clamp(7px, 1.8vw, 10px); text-transform: uppercase; letter-spacing: 0.2px; font-weight: 600; color: black; margin-top: 2px;">Envío</div>
                                <div style="font-size: clamp(8px, 2vw, 11px); color: black; font-weight: 700; margin-top: 2px; line-height: 1.1;">${planData.envio.replace(/^[a-z]\.- /, '')}</div>
                            </div>
                            <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); padding: clamp(6px, 2vw, 12px); border-radius: 8px; text-align: center; box-shadow: 0 2px 6px rgba(168, 237, 234, 0.3);">
                                <i class="flaticon-381-gift text-black" style="font-size: clamp(14px, 3.5vw, 22px);"></i>
                                <div style="font-size: clamp(7px, 1.8vw, 10px); text-transform: uppercase; letter-spacing: 0.2px; font-weight: 600; color: black; margin-top: 2px;">Empaque</div>
                                <div style="font-size: clamp(9px, 2.2vw, 12px); color: black; font-weight: 700; margin-top: 2px; line-height: 1.1;">${planData.empaque}</div>
                            </div>
                        </div>
                    </div>
                `,
                width: 'clamp(300px, 96vw, 700px)',
                padding: 'clamp(12px, 3vw, 20px)',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-check-double"></i> Despachar Todo',
                denyButtonText: '<i class="fa fa-undo"></i> Restablecer',
                cancelButtonText: '<i class="fa fa-times"></i> Cerrar',
                confirmButtonColor: '#10b981',
                denyButtonColor: '#6b7280',
                cancelButtonColor: '#ef4444',
                customClass: {
                    popup: 'animate__animated animate__zoomIn',
                    confirmButton: 'btn-swal-custom',
                    denyButton: 'btn-swal-custom',
                    cancelButton: 'btn-swal-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('confirmarDespacho', planData.id);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Despachado!',
                        text: 'Plan completo despachado exitosamente',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else if (result.isDenied) {
                    @this.call('restablecerPlan', planData.id);
                    Swal.fire({
                        icon: 'info',
                        title: 'Restablecido',
                        text: 'El plan ha sido restablecido',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        function despacharItemSopa(id) {
            Swal.close();
            @this.call('despacharSopa', id);
            Swal.fire({
                icon: 'success',
                title: '¡Sopa Despachada!',
                timer: 1200,
                showConfirmButton: false
            });
        }

        function despacharItemSegundo(id) {
            Swal.close();
            @this.call('despacharSegundo', id);
            Swal.fire({
                icon: 'success',
                title: '¡Segundo Despachado!',
                timer: 1200,
                showConfirmButton: false
            });
        }

        // Función para actualizar los timers
        function actualizarTimers() {
            const timers = document.querySelectorAll('.timer-badge, .timer-simple');

            timers.forEach(timer => {
                const timestamp = timer.getAttribute('data-timestamp');
                if (!timestamp) return;

                // Crear fecha desde el timestamp
                const fechaDespacho = new Date(timestamp);
                const ahora = new Date();

                // Calcular diferencia en milisegundos
                const diferencia = ahora - fechaDespacho;

                // Convertir a minutos y segundos
                const totalSegundos = Math.floor(diferencia / 1000);
                const minutos = Math.floor(totalSegundos / 60);
                const segundos = totalSegundos % 60;

                // Formatear el tiempo
                const minutosStr = String(minutos).padStart(2, '0');
                const segundosStr = String(segundos).padStart(2, '0');

                // Actualizar el contenido
                timer.textContent = `${minutosStr}:${segundosStr}`;

                // Cambiar color según el tiempo transcurrido (solo para timer-badge)
                if (timer.classList.contains('timer-badge')) {
                    if (minutos < 5) {
                        timer.className = 'timer-badge badge badge-success badge-xs mt-1';
                    } else if (minutos < 10) {
                        timer.className = 'timer-badge badge badge-warning badge-xs mt-1';
                    } else {
                        timer.className = 'timer-badge badge badge-danger badge-xs mt-1';
                    }
                }
            });
        }

        // Actualizar timers cada segundo
        setInterval(actualizarTimers, 1000);

        // Inicializar timers al cargar la página
        actualizarTimers();

        document.addEventListener('livewire:load', function() {
            // Inicializar timers después de que Livewire cargue
            actualizarTimers();

            // Escuchar evento de actualización
            window.addEventListener('actualizarDisponibilidad', event => {
                const menu = event.detail.menu;
                if (!menu) return;

                // Actualizar cada item del menú en el Sweet Alert
                actualizarItemMenu('ejecutivo', menu.ejecutivo, menu.ejecutivo_cant, menu.ejecutivo_estado);
                actualizarItemMenu('dieta', menu.dieta, menu.dieta_cant, menu.dieta_estado);
                actualizarItemMenu('vegetariano', menu.vegetariano, menu.vegetariano_cant, menu
                    .vegetariano_estado);
                actualizarItemMenu('carbohidrato_1', menu.carbohidrato_1, menu.carbohidrato_1_cant, menu
                    .carbohidrato_1_estado);
                actualizarItemMenu('carbohidrato_2', menu.carbohidrato_2, menu.carbohidrato_2_cant, menu
                    .carbohidrato_2_estado);
                actualizarItemMenu('carbohidrato_3', menu.carbohidrato_3, menu.carbohidrato_3_cant, menu
                    .carbohidrato_3_estado);
                actualizarItemMenu('sopa', menu.sopa, menu.sopa_cant, menu.sopa_estado);
            });

            function actualizarItemMenu(tipo, nombre, cantidad, estado) {
                const container = document.querySelector(`[data-item-tipo="${tipo}"]`);
                if (!container) return;

                const estadoTexto = estado ? 'Disponible' : 'Agotado';
                const estadoColor = estado ? '#10b981' : '#ef4444';
                const estadoIcon = estado ? 'fa-check-circle' : 'fa-times-circle';

                // Agregar efecto visual de actualización con pulso
                container.style.transform = 'scale(1.05)';
                container.style.boxShadow = '0 8px 24px rgba(102, 126, 234, 0.5)';

                setTimeout(() => {
                    container.style.transform = 'scale(1)';
                    container.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
                }, 400);

                // Actualizar borde del contenedor
                container.style.borderColor = estadoColor;
                container.style.borderWidth = '3px';

                // Actualizar input de cantidad o mostrar N/A
                const inputContainer = container.querySelector('.input-container');
                if (inputContainer) {
                    if (estado) {
                        inputContainer.innerHTML = `
                        <input type="number" 
                               min="0" 
                               value="${cantidad || 0}"
                               data-tipo="${tipo}"
                               class="cantidad-input"
                               style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 20px; font-weight: 700; text-align: center; transition: all 0.3s; background: #f9fafb;"
                               onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'">
                    `;
                        // Re-adjuntar el event listener
                        const newInput = inputContainer.querySelector('.cantidad-input');
                        newInput.addEventListener('change', function() {
                            const tipo = this.dataset.tipo;
                            const valor = this.value;
                            @this.call('cambiarCantidad', tipo + '_cant', valor);
                        });
                    } else {
                        inputContainer.innerHTML =
                            '<div style="padding: 10px; color: #9ca3af; font-size: 16px; font-weight: 600;">N/A</div>';
                    }
                }

                // Actualizar botón de estado
                const btn = container.querySelector('.estado-btn');
                if (btn) {
                    btn.style.background = estadoColor;
                    btn.innerHTML = `<i class="fa ${estadoIcon}"></i> ${estadoTexto}`;
                }
            }

            window.addEventListener('mostrarDisponibilidad', event => {
                const menu = event.detail.menu;

                if (!menu) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin información',
                        text: 'No hay menú disponible para el día de hoy'
                    });
                    return;
                }

                // Generar HTML para los items
                let htmlContent = `
                <div style="text-align: left; max-height: 700px; overflow-y: auto; padding: 10px;">
                    
                    <div style="margin-bottom: 30px;">
                        <div style="background: linear-gradient(to right, #f093fb 0%, #f5576c 100%); padding: 12px 20px; border-radius: 10px; margin-bottom: 15px; text-align: center;">
                            <h4 style="margin: 0; color: white; font-size: 20px; font-weight: 700;">
                                <i class="fa fa-drumstick-bite"></i> SEGUNDOS
                            </h4>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                            ${crearItemMenu('Ejecutivo', menu.ejecutivo, menu.ejecutivo_cant, menu.ejecutivo_estado, 'ejecutivo')}
                            ${crearItemMenu('Dieta', menu.dieta, menu.dieta_cant, menu.dieta_estado, 'dieta')}
                            ${crearItemMenu('Vegetariano', menu.vegetariano, menu.vegetariano_cant, menu.vegetariano_estado, 'vegetariano')}
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 30px;">
                        <div style="background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%); padding: 12px 20px; border-radius: 10px; margin-bottom: 15px; text-align: center;">
                            <h4 style="margin: 0; color: white; font-size: 20px; font-weight: 700;">
                                <i class="fa fa-bread-slice"></i> CARBOHIDRATOS
                            </h4>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                            ${crearItemMenu('Carbo 1', menu.carbohidrato_1, menu.carbohidrato_1_cant, menu.carbohidrato_1_estado, 'carbohidrato_1')}
                            ${crearItemMenu('Carbo 2', menu.carbohidrato_2, menu.carbohidrato_2_cant, menu.carbohidrato_2_estado, 'carbohidrato_2')}
                            ${crearItemMenu('Carbo 3', menu.carbohidrato_3, menu.carbohidrato_3_cant, menu.carbohidrato_3_estado, 'carbohidrato_3')}
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <div style="background: linear-gradient(to right, #fa709a 0%, #fee140 100%); padding: 12px 20px; border-radius: 10px; margin-bottom: 15px; text-align: center;">
                            <h4 style="margin: 0; color: white; font-size: 20px; font-weight: 700;">
                                <i class="fa fa-bowl-food"></i> SOPA
                            </h4>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                            ${crearItemMenu('Sopa', menu.sopa, menu.sopa_cant, menu.sopa_estado, 'sopa')}
                        </div>
                    </div>
                </div>
            `;

                Swal.fire({
                    title: false,
                    html: htmlContent,
                    width: '1100px',
                    showConfirmButton: true,
                    confirmButtonText: '<i class="fa fa-check"></i> Cerrar',
                    confirmButtonColor: '#667eea',
                    customClass: {
                        container: 'swal-container-custom'
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    didOpen: () => {
                        // Agregar event listeners para los inputs de cantidad
                        document.querySelectorAll('.cantidad-input').forEach(input => {
                            input.addEventListener('change', function() {
                                const tipo = this.dataset.tipo;
                                const valor = this.value;
                                @this.call('cambiarCantidad', tipo + '_cant',
                                    valor);
                            });
                        });

                        // Agregar event listeners para los botones de estado
                        document.querySelectorAll('.estado-btn').forEach(btn => {
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                const tipo = this.dataset.tipo;
                                @this.call('cambiarEstadoPlato', tipo +
                                    '_estado');
                            });
                        });
                    }
                });

                function crearItemMenu(label, nombre, cantidad, estado, tipo) {
                    const estadoTexto = estado ? 'Disponible' : 'Agotado';
                    const estadoColor = estado ? '#10b981' : '#ef4444';
                    const estadoIcon = estado ? 'fa-check-circle' : 'fa-times-circle';

                    return `
                    <div data-item-tipo="${tipo}" style="background: white; border: 3px solid ${estadoColor}; border-radius: 12px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: all 0.3s; display: flex; flex-direction: column; gap: 12px;">
                        <div style="text-align: center;">
                            <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 6px;">${label}</div>
                            <div style="font-weight: 700; font-size: 15px; color: #1f2937; line-height: 1.3; min-height: 40px; display: flex; align-items: center; justify-content: center;">${nombre || 'Sin nombre'}</div>
                        </div>
                        
                        <div class="input-container" style="text-align: center;">
                            ${estado ? `
                                                                            <input type="number" 
                                                                                   min="0" 
                                                                                   value="${cantidad || 0}"
                                                                                   data-tipo="${tipo}"
                                                                                   class="cantidad-input"
                                                                                   style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 20px; font-weight: 700; text-align: center; transition: all 0.3s; background: #f9fafb;"
                                                                                   onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                                                                                   onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'">
                                                                        ` : '<div style="padding: 10px; color: #9ca3af; font-size: 16px; font-weight: 600;">N/A</div>'}
                        </div>
                        
                        <button data-tipo="${tipo}" 
                                class="estado-btn"
                                style="background: ${estadoColor}; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 13px; transition: all 0.3s; box-shadow: 0 2px 8px rgba(0,0,0,0.1); width: 100%;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.15)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">
                            <i class="fa ${estadoIcon}"></i> ${estadoTexto}
                        </button>
                    </div>
                `;
                }
            });

            // Listener para abrir el modal después de marcar ingreso
            window.addEventListener('clienteMarcadoYAbrirModal', event => {
                const itemId = event.detail.itemId;
                
                // Esperar un momento para que Livewire actualice la data
                setTimeout(() => {
                    // Buscar el registro actualizado en la tabla
                    const filas = document.querySelectorAll('tbody tr[onclick*="mostrarPlanCocina"]');
                    filas.forEach(fila => {
                        const onclickAttr = fila.getAttribute('onclick');
                        if (onclickAttr && onclickAttr.includes(`id: ${itemId}`)) {
                            // Extraer los datos del onclick
                            try {
                                const match = onclickAttr.match(/mostrarPlanCocina\(({[\s\S]*?})\)/);
                                if (match) {
                                    const planDataStr = match[1]
                                        .replace(/(\w+):/g, '"$1":') // Agregar comillas a las keys
                                        .replace(/'/g, '"'); // Reemplazar comillas simples por dobles
                                    
                                    const planData = JSON.parse(planDataStr);
                                    // Forzar cliente_ingresado a true ya que acabamos de marcarlo
                                    planData.cliente_ingresado = true;
                                    
                                    // Abrir el modal con los datos actualizados
                                    mostrarPlanCocina(planData);
                                }
                            } catch (e) {
                                console.error('Error al parsear datos del plan:', e);
                                // Si hay error, simplemente recargar la página para mostrar datos actualizados
                                Livewire.emit('refresh');
                            }
                        }
                    });
                }, 500);
            });
        });

        // Actualizar timers después de que Livewire actualice el DOM
        Livewire.hook('message.processed', (message, component) => {
            setTimeout(() => {
                actualizarTimers();
            }, 100);
        });
    </script>
@endpush
@push('css')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            margin: 0 !important;
            padding: 0 !important;
            border: 1px solid #ddd;
            text-align: center;
            /* Esto es opcional, solo para mostrar las celdas de la tabla */
        }

        th {
            background-color: #f2f2f2;
            /* Esto es opcional para darle un color de fondo a los encabezados */
        }

        table {
            font-size: 15px !important;
        }

        /* Estilos para los badges de planes */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .plan-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
        }

        .plan-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .plan-badge button {
            transition: all 0.2s ease;
        }

        .plan-badge button:hover {
            transform: scale(1.2);
        }

        .plan-badge button:hover i {
            transform: rotate(90deg);
            transition: transform 0.2s ease;
        }

        .btn-limpiar {
            transition: all 0.3s ease;
            border-width: 2px !important;
        }

        .btn-limpiar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            background-color: #dc2626 !important;
            color: white !important;
        }

        /* Estilos personalizados para botones de SweetAlert */
        .btn-swal-custom {
            font-weight: 700 !important;
            font-size: 14px !important;
            padding: 12px 24px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        .btn-swal-custom:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
        }

        .swal2-popup {
            border-radius: 20px !important;
            padding: 20px !important;
        }

        .swal2-title {
            padding: 0 !important;
        }

        /* Estilos para los timers */
        .timer-badge {
            display: inline-block;
            font-weight: 700;
            padding: 4px 8px !important;
            border-radius: 8px !important;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .timer-badge.badge-danger {
            animation: pulse-danger 1s infinite;
        }

        @keyframes pulse-danger {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
            }

            50% {
                transform: scale(1.08);
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.6);
            }
        }
    </style>
@endpush
