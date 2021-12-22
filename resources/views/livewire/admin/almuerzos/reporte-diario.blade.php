<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Reporte de hoy {{date("d-M");}}</h4> <a href="#" wire:click="cambiarDisponibilidad"
                data-bs-toggle="modal" data-bs-target="#modalDisponibilidad"><span
                    class="badge badge-pill badge-primary">Cambiar Disponibilidad</span></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sopa</th>
                            <th>Ensalada</th>

                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            <th>Jugo</th>
                            <th>Empaque</th>
                            <th>Envio</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coleccion as $lista)
                        <tr class="{{$lista['ESTADO']=='despachado'?'table-success':''}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$lista['NOMBRE']}}</td>

                            <td>{{$lista['SOPA']}}</td>
                            <td>{{$lista['ENSALADA']}}</td>
                            <td>{{$lista['PLATO']}}</td>
                            <td>{{$lista['CARBOHIDRATO']}}</td>
                            <td>{{$lista['JUGO']}}</td>
                            <td>{{$lista['EMPAQUE']}}</td>
                            <td>{{$lista['ENVIO']}}</td>
                            @if ($lista['ESTADO']=="pendiente")
                            <td><button wire:click="cambiarEstado('{{$lista['ID']}}')"
                                    class="btn btn-warning">Pendiente</button></td>

                            @else
                            <td><button wire:click="cambiarAPendiente('{{$lista['ID']}}')"
                                    class="btn btn-success">Despachado</button></td>

                            @endif

                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            @foreach ($total[0] as $producto=>$array)
                            <th><small>
                                    @foreach ($array as $nombre=>$cantidad)
                                    <span
                                        class="badge badge-pill badge-primary light">{{$nombre}}:{{$cantidad}}</span><br>
                                    @endforeach</th>
                            @endforeach
                            </small>

                        </tr>


                    </tbody>
                </table>
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
                        <div class="col"><span class="badge badge-pill badge-primary">{{$menuHoy->ejecutivo}} </span>
                        </div>
                        <a href="#" wire:click="cambiarEstadoPlato('ejecutivo_estado')" class="col"><span
                                class="badge badge-pill badge-{{$menuHoy->ejecutivo_estado==1?'success':'danger'}}">{{$menuHoy->ejecutivo_estado==1?'Disponible':'Agotado'}}</span>
                        </a>
                    </div>
                    <div class="row m-2">
                        <div class="col"> <span class="badge badge-pill badge-primary">{{$menuHoy->dieta}} </span></div>
                        <a href="#" wire:click="cambiarEstadoPlato('dieta_estado')" class="col"><span
                                class="badge badge-pill badge-{{$menuHoy->dieta_estado==1?'success':'danger'}}">{{$menuHoy->dieta_estado==1?'Disponible':'Agotado'}}</span>
                        </a>
                    </div>
                    <div class="row m-2">
                        <div class="col"><span class="badge badge-pill badge-primary">{{$menuHoy->vegetariano}} </span>
                        </div>
                        <a href="#" wire:click="cambiarEstadoPlato('vegetariano_estado')" class="col"><span
                                class="badge badge-pill badge-{{$menuHoy->vegetariano_estado==1?'success':'danger'}}">{{$menuHoy->vegetariano_estado==1?'Disponible':'Agotado'}}</span>
                        </a>
                    </div>
                    <div class="row m-2">
                        <div class="col"><span class="badge badge-pill badge-primary">{{$menuHoy->carbohidrato_1}}
                            </span></div>
                        <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_1_estado')" class="col"><span
                                class="badge badge-pill badge-{{$menuHoy->carbohidrato_1_estado==1?'success':'danger'}}">{{$menuHoy->carbohidrato_1_estado==1?'Disponible':'Agotado'}}</span>
                        </a>
                    </div>
                    <div class="row m-2">
                        <div class="col"><span class="badge badge-pill badge-primary">{{$menuHoy->carbohidrato_2}}
                            </span></div>
                        <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_2_estado')" class="col"><span
                                class="badge badge-pill badge-{{$menuHoy->carbohidrato_2_estado==1?'success':'danger'}}">{{$menuHoy->carbohidrato_2_estado==1?'Disponible':'Agotado'}}</span>
                        </a>
                    </div>
                    <div class="row m-2">
                        <div class="col"><span class="badge badge-pill badge-primary">{{$menuHoy->carbohidrato_3}}
                            </span></div>
                        <a href="#" wire:click="cambiarEstadoPlato('carbohidrato_3_estado')" class="col"><span
                                class="badge badge-pill badge-{{$menuHoy->carbohidrato_3_estado==1?'success':'danger'}}">{{$menuHoy->carbohidrato_3_estado==1?'Disponible':'Agotado'}}</span>
                        </a>
                    </div>

                    @endisset
                </div>

            </div>
        </div>
    </div>
</div>
