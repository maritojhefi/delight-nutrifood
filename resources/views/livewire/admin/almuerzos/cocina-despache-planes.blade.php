<div>
    @livewire('admin.pedidos-realtime-component')
    <div class="card col-12 letra12 bordeado">
        <div class="card-header">
            <div class="row ">
                <div class="col-sm-6 d-flex">
                    <a href="#" wire:click="cambiarDisponibilidad" onclick="event.preventDefault()">
                        <span class="badge badge-pill badge-primary">Disponibilidad</span>
                    </a>
                    <div class="m-1">
                        <div wire:loading class="spinner-border" style="width: 20px;height:20px" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <strong>Viendo fecha : {{ date_format(date_create($fechaSeleccionada), 'd-M') }} </strong>

                </div>
                <div class="col-sm-4">
                    <h4 class="letra12">Planes por despachar</h4>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6 col-md-3 col-lg-5">
                            <div class="input-group input-{{ $estadoColor }}">
                                <a href="#" wire:click="cambiarEstadoBuscador"
                                    class="input-group-text p-0 m-0 letra12"
                                    style="height: 30px">{{ $estadoBuscador }}</a>
                                <input type="text" class="form-control form-control-sm p-0 m-0" style="height: 30px"
                                    wire:model.debounce.500ms="search">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-4"><input type="date"
                                class="form-control bordeado p-0 m-0 ps-1" style="height: 30px"
                                wire:model="fechaSeleccionada" wire:change="cambioDeFecha"></div>
                    </div>


                </div>

            </div>
        </div>
        {{-- <div class="row">
            <div class="col-12">
                <select class="form-control text-center bg-info text-white">
                    <option value="">asdasdsa</option>
                </select>
            </div>
           
        </div> --}}
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
                                                    <small
                                                        class="text-{{ $color }}">{{ $cantidad }}</small><br>
                                                @elseif ($cont == 4)
                                                    <strong
                                                        class="text-{{ $color }}">{{ Str::limit($nombre, 8) }}:{{ $cantidad }}</strong><br>
                                                @else
                                                    <strong
                                                        class="text-{{ $color }}">{{ Str::limit($nombre, 15) }}:{{ $cantidad }}</strong><br>
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
                                style="border-color:#211d1d !important">
                                <td style="border-color:#211d1d !important">
                                    @if(isset($lista['CLIENTE_INGRESADO']) && $lista['CLIENTE_INGRESADO'])
                                        <i class="fa fa-user fa-beat text-success" style="font-size: 16px;" title="Cliente ingresado"></i>
                                    @endif
                                    {!! $lista['ESTADO'] == 'permiso'
                                        ? '<a href="javascript:void(0)" class="text-primary"><strong>PERMISO</strong> </a>'
                                        : $loop->iteration !!}
                                </td>
                                <td style="border-color:#211d1d !important"><small>
                                        @if ($lista['ESTADO'] == 'permiso')
                                            <del class="text-muted">{{ Str::limit($lista['NOMBRE'], 25) }}</del>
                                        @else
                                            <a href="#" data-toggle="modal"
                                                data-target="#modalCocina{{ $lista['ID'] }}">{{ Str::limit($lista['NOMBRE'], 25) }}</a>
                                        @endif

                                    </small>
                                </td>
                                @if ($lista['COCINA'] == 'solo-sopa')
                                    <td style="border-color:#211d1d !important"><small><a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1"><i
                                                    class="fa fa-check"></i></a> </small></td>
                                @else
                                    <td style="border-color:#211d1d !important">
                                        <small>{{ $lista['SOPA'] != '' ? 'SI' : '' }}</small>
                                    </td>
                                @endif

                                @if ($lista['COCINA'] == 'solo-segundo')
                                    <td style="border-color:#211d1d !important"><small><a href="javascript:void(0)"
                                                class="badge badge-circle badge-sm badge-success p-1"><i
                                                    class="fa fa-check"></i></a> </small></td>
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
                            <div wire:ignore.self class="modal fade" id="modalCocina{{ $lista['ID'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content m-0 p-0">
                                        <div class="modal-header m-0 p-2">
                                            <h5 class="modal-title mx-auto">
                                                Plan de: <strong>{{ $lista['NOMBRE'] }}</strong></h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body m-0 p-0">
                                            <ul class="list-group  m-0 p-0">
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed py-2">
                                                    <div>
                                                        <small class="text-muted">Nombre del plan</small>
                                                    </div>
                                                    <span class="">
                                                        <h6 class="my-0">{{ $lista['PLAN'] }}</h6>
                                                    </span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed  py-2">
                                                    <div>
                                                        <small class="text-muted">Sopa</small>
                                                    </div>
                                                    <h6 class="my-0 {{ $lista['SOPA'] != '' ? '' : 'text-danger' }}">
                                                        {{ $lista['SOPA'] != '' ? $lista['SOPA'] : 'SIN SOPA' }}
                                                        @if ($lista['SOPA'] != '')
                                                            <span class="letra10 p-2">
                                                                @if ($lista['COCINA'] == 'espera' || $lista['COCINA'] == 'solo-segundo')
                                                                    <a href="#"
                                                                        wire:click="despacharSopa({{ $lista['ID'] }})"
                                                                        data-dismiss="modal"><span
                                                                            class="badge badge-xs  badge-primary">Despachar
                                                                        </span></a>
                                                                @else
                                                                    <del class="text-danger"><span
                                                                            class="text-black">Despachado</span></del>
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>


                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed  py-2">
                                                    <div>

                                                        <small class="text-muted">Segundo</small>

                                                    </div>
                                                    @if ($lista['PLATO'] != '')
                                                        <h6
                                                            class="my-0 {{ $lista['PLATO'] != '' ? '' : 'text-danger' }}">
                                                            {{ $lista['PLATO'] != '' ? $lista['PLATO'] : 'DESCONOCIDO' }}
                                                            <span class="letra10 p-2">
                                                                @if ($lista['COCINA'] == 'espera' || $lista['COCINA'] == 'solo-sopa')
                                                                    <a href="#"
                                                                        wire:click="despacharSegundo({{ $lista['ID'] }})"
                                                                        data-dismiss="modal"><span
                                                                            class="badge badge-xs  badge-primary">
                                                                            Despachar</span>
                                                                    </a>
                                                                @else
                                                                    <del class="text-danger"><span
                                                                            class="text-black">Despachado</span></del>
                                                                @endif
                                                            </span>
                                                        </h6>
                                                    @endif
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between lh-condensed  py-2">
                                                    <div>

                                                        <small class="text-muted">Carbohidrato</small>
                                                    </div>
                                                    <h6
                                                        class="my-0 {{ $lista['CARBOHIDRATO'] != '' ? '' : 'text-danger' }}">
                                                        {{ $lista['CARBOHIDRATO'] != '' ? $lista['CARBOHIDRATO'] : 'SIN CARBOHIDRATO' }}
                                                    </h6>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between  py-2">
                                                    <span>Envio</span>
                                                    <strong>{{ $lista['ENVIO'] }}</strong>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between  py-2">
                                                    <span>Empaque</span>
                                                    <strong>{{ $lista['EMPAQUE'] }}</strong>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-xxs mx-auto"
                                                wire:click="restablecerPlan({{ $lista['ID'] }})"
                                                data-dismiss="modal">Restablecer</button>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                wire:click="confirmarDespacho({{ $lista['ID'] }})"
                                                data-dismiss="modal">Despachar todo</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    @if(isset($lista['CLIENTE_INGRESADO']) && $lista['CLIENTE_INGRESADO'])
                                        <i class="fa fa-user fa-beat text-success" style="font-size: 16px;" title="Cliente ingresado"></i>
                                    @endif
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
    document.addEventListener('livewire:load', function () {
        
        // Escuchar evento de actualización
        window.addEventListener('actualizarDisponibilidad', event => {
            const menu = event.detail.menu;
            if (!menu) return;
            
            // Actualizar cada item del menú en el Sweet Alert
            actualizarItemMenu('ejecutivo', menu.ejecutivo, menu.ejecutivo_cant, menu.ejecutivo_estado);
            actualizarItemMenu('dieta', menu.dieta, menu.dieta_cant, menu.dieta_estado);
            actualizarItemMenu('vegetariano', menu.vegetariano, menu.vegetariano_cant, menu.vegetariano_estado);
            actualizarItemMenu('carbohidrato_1', menu.carbohidrato_1, menu.carbohidrato_1_cant, menu.carbohidrato_1_estado);
            actualizarItemMenu('carbohidrato_2', menu.carbohidrato_2, menu.carbohidrato_2_cant, menu.carbohidrato_2_estado);
            actualizarItemMenu('carbohidrato_3', menu.carbohidrato_3, menu.carbohidrato_3_cant, menu.carbohidrato_3_estado);
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
                    inputContainer.innerHTML = '<div style="padding: 10px; color: #9ca3af; font-size: 16px; font-weight: 600;">N/A</div>';
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
                            @this.call('cambiarCantidad', tipo + '_cant', valor);
                        });
                    });

                    // Agregar event listeners para los botones de estado
                    document.querySelectorAll('.estado-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const tipo = this.dataset.tipo;
                            @this.call('cambiarEstadoPlato', tipo + '_estado');
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
    </style>
@endpush
