<div>
    @livewire('admin.pedidos-realtime-component')
    <div class="card">
        
        <div class="card-header">
            <div class="row">
                <div class="col-sm-6">
                    <a href="#" wire:click="cambiarDisponibilidad"
                    data-bs-toggle="modal" data-bs-target="#modalDisponibilidad"><span
                        class="badge badge-pill badge-primary">Disponibilidad</span></a>
                </div>
                <div class="col-sm-6">
                    <span>Fecha {{ date_format(date_create($fechaSeleccionada), 'd-M') }} </span>
                </div>
                <div class="col-sm-6">
                    <h4>Planes por despachar</h4>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-6 col-md-3 col-lg-5">
                        <div class="input-group input-{{ $estadoColor }}">
                            <a href="#" wire:click="cambiarEstadoBuscador" class="input-group-text">{{ $estadoBuscador }}</a>
                            <input type="text" class="form-control" wire:model.debounce.500ms="search">
                        </div>
                    </div>
                </div>
                
                
                <div class="d-flex justify-content-center">
                    <div wire:loading class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
           
        </div>
        <div class="">
            <div class="table-responsive " style="padding:5px">
                <table class=" table  table-responsive-sm">
                    <thead style="padding:5px">
                        <tr>
                            <td></td>
                            <td></td>
                            @php
                                $colores = collect(['warning', 'success', 'danger', 'primary', 'secondary', 'info', 'dark']);
                            @endphp
                            @foreach ($totalEspera[0] as $producto => $array)
                                <th><small>

                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                <strong
                                                    class="badge badge-lg badge-{{ $colores->random() }} ">{{ Str::limit($nombre, '15') }}:{{ $cantidad }}</strong><br>
                                            @endif
                                        @endforeach
                                </th>
                            @endforeach
                            </small>

                        </tr>
                        <tr>
                            <th>#</th>
                            <th style="width:30%">Nombre</th>
                            <th style="width:5%">Sopa</th>

                            <th style="width:15%">Plato</th>
                            <th style="width:20%">Carbohidrato</th>

                            <th style="width:15%">Empaque</th>
                            <th style="width:15%">Envio</th>


                        </tr>
                    </thead>

                    <tbody style="padding:5px">

                        @foreach ($coleccion->where('COCINA', 'espera') as $lista)
                            <tr style="padding:5px"
                                class="@if ($lista['ENVIO'] == 'a.- Delivery') {{ 'table-primary' }}@elseif($lista['ENVIO'] == 'b.- Para llevar(Paso a recoger)'){{ 'table-info' }}@elseif($lista['ENVIO'] == 'c.- Para Mesa'){{ 'table-success' }} @endif">

                                <td style="padding:5px">{{ $loop->iteration }}</td>

                                <td style="padding:5px"><small><a href="#" data-toggle="modal"
                                            data-target="#modalCocina{{ $lista['ID'] }}">{{ Str::limit($lista['NOMBRE'], 25) }}
                                        </a></small>
                                </td>

                                <td style="padding:5px"><small>{{ $lista['SOPA'] != '' ? 'SI' : '' }}</small></td>

                                <td style="padding:5px"><small>{{ $lista['PLATO'] }}</small></td>
                                <td style="padding:5px"><small>{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</small>
                                </td>

                                <td style="padding:5px"><small>{{ Str::limit($lista['EMPAQUE'], 15) }}</small></td>
                                <td style="padding:5px"><small>{{ Str::limit($lista['ENVIO'], 15) }}</small></td>



                            </tr>

                            <div wire:ignore.self class="modal fade" id="modalCocina{{ $lista['ID'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Despachando pedido para
                                                {{ $lista['NOMBRE'] }}</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-primary"
                                                wire:click="confirmarDespacho({{ $lista['ID'] }})"
                                                data-dismiss="modal">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach



                    </tbody>



                </table>
                <h4>DESPACHADOS</h4>
                <table class="table table-responsive-sm">
                    <thead style="padding:5px">
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
                    <tbody style="padding:5px">

                        @foreach ($coleccion->where('COCINA', 'despachado') as $lista)
                            <tr class="table-danger" style="padding:5px">

                                <td style="padding:5px">{{ $loop->iteration }}</td>

                                <td style="padding:5px"> {{ Str::limit($lista['NOMBRE'], 20) }}

                                </td>

                                <td style="padding:5px">{{ $lista['SOPA'] != '' ? 'SI' : '' }}</td>

                                <td style="padding:5px">{{ $lista['PLATO'] }}</td>
                                <td style="padding:5px">{{ Str::limit($lista['CARBOHIDRATO'], 20) }}</td>

                                <td style="padding:5px">{{ $lista['EMPAQUE'] }}</td>
                                <td style="padding:5px">{{ $lista['ENVIO'] }}</td>



                            </tr>

                            <div wire:ignore.self class="modal fade" id="modalCocina{{ $lista['ID'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Despachando pedido para
                                                {{ $lista['NOMBRE'] }}</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-primary"
                                                wire:click="confirmarDespacho({{ $lista['ID'] }})"
                                                data-dismiss="modal">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>

                            @foreach ($totalDespachado[0] as $producto => $array)
                                <th><small>
                                        @foreach ($array as $nombre => $cantidad)
                                            @if ($nombre != '' && $nombre != 'sin carbohidrato' && $nombre != 'Ninguno')
                                                <span
                                                    class="badge badge-pill badge-lg badge-{{ $colores->random() }}">{{ Str::limit($nombre, '15') }}:{{ $cantidad }}</span><br>
                                            @endif
                                        @endforeach
                                </th>
                            @endforeach
                            </small>

                        </tr>


                    </tbody>
                </table>


                <div class="d-flex justify-content-center">
                    <h4>{{ $search ? 'Encontrados' : 'Planes para este dia' }} : {{ $coleccion->count() }}</h4>
                    {{-- <a href="#" wire:click="exportarexcel" class="badge badge-success pill light">Exportar</a> --}}
                </div>
            </div>
        </div>
    </div>



    <div wire:ignore.self class="modal fade" id="modalDisponibilidad">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menu de Hoy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    @isset($menuHoy)
                   
                        <div class="row m-2">
                           
                            <div class="col-md-2">
                                @if ($menuHoy->ejecutivo_estado)
                                <input type="number" wire:model.lazy="ejecutivo_cant" wire:change="cambiarCantidad('ejecutivo_cant')" class="form-control form-control-sm " style="padding: 0px;height:12px" >
                                @endif
                            </div> 
                            
                                                      
                            <div class="col">  
                                <span class="badge badge-pill badge-primary">{{ $menuHoy->ejecutivo }}
                                </span>
                            </div>
                            <a href="#" wire:click="cambiarEstadoPlato('ejecutivo_estado')" class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->ejecutivo_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->ejecutivo_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            
                            <div class="col-md-2">
                                @if ($menuHoy->dieta_estado)
                                <input type="number" wire:model.debounce.1500ms="dieta_cant" wire:change="cambiarCantidad('dieta_cant')" class="form-control form-control-sm " style="padding: 0px;height:12px" value="{{$menuHoy->dieta_cant}}">
                                @endif
                            </div>  
                            
                            <div class="col"> <span class="badge badge-pill badge-primary">{{ $menuHoy->dieta }} </span>
                            </div>
                            <a href="#" wire:click="cambiarEstadoPlato('dieta_estado')" class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->dieta_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->dieta_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            
                            <div class="col-md-2">
                                @if ($menuHoy->vegetariano_estado)
                                <input type="number" wire:model.debounce.1500ms="vegetariano_cant" wire:change="cambiarCantidad('vegetariano_cant')" class="form-control form-control-sm " style="padding: 0px;height:12px" value="{{$menuHoy->vegetariano_cant}}">
                                @endif
                            </div>  
                            
                            <div class="col"><span class="badge badge-pill badge-primary">{{ $menuHoy->vegetariano }}
                                </span>
                            </div>
                            <a href="#" wire:click="cambiarEstadoPlato('vegetariano_estado')" class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->vegetariano_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->vegetariano_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            
                            <div class="col-md-2">
                                @if ($menuHoy->carbohidrato_1_estado)
                                <input type="number" wire:model.debounce.1500ms="carbohidrato_1_cant" wire:change="cambiarCantidad('carbohidrato_1_cant')" class="form-control form-control-sm " style="padding: 0px;height:12px" value="{{$menuHoy->carbohidrato_1_cant}}">
                                @endif
                            </div>  
                            
                            <div class="col"><span class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_1 }}
                                </span></div>
                            <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_1_estado')" class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->carbohidrato_1_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_1_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            
                            <div class="col-md-2">
                                @if ($menuHoy->carbohidrato_2_estado)
                                <input type="number" wire:model.debounce.1500ms="carbohidrato_2_cant" wire:change="cambiarCantidad('carbohidrato_2_cant')" class="form-control form-control-sm " style="padding: 0px;height:12px" value="{{$menuHoy->carbohidrato_2_cant}}">
                                @endif
                            </div>  
                           
                            <div class="col"><span
                                    class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_2 }}
                                </span></div>
                            <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_2_estado')"
                                class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->carbohidrato_2_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_2_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                        <div class="row m-2">
                            
                            <div class="col-md-2">
                                @if ($menuHoy->carbohidrato_3_estado)
                                <input type="number" wire:model.debounce.1500ms="carbohidrato_3_cant" wire:change="cambiarCantidad('carbohidrato_3_cant')" class="form-control form-control-sm " style="padding: 0px;height:12px" value="{{$menuHoy->carbohidrato_3_cant}}">
                                @endif
                            </div>  
                           
                            <div class="col"><span
                                    class="badge badge-pill badge-primary">{{ $menuHoy->carbohidrato_3 }}
                                </span></div>
                            <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_3_estado')"
                                class="col"><span
                                    class="badge badge-pill badge-{{ $menuHoy->carbohidrato_3_estado == 1 ? 'success' : 'danger' }}">{{ $menuHoy->carbohidrato_3_estado == 1 ? 'Disponible' : 'Agotado' }}</span>
                            </a>
                        </div>
                    @endisset
                </div>

            </div>
        </div>
    </div>

</div>
