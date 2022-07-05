<div>
  
    
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Reporte de fecha {{(date_format(date_create($fechaSeleccionada), 'd-M'))}}
                <button class="btn btn-xs btn-info"  data-toggle="modal" data-target="#exampleModal">Finalizar todos</button></h4> 
            
            <div class="col-3"><div  class="d-flex justify-content-center">
                <div wire:loading class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div></div>
            <div class="col-sm-6 col-md-4 col-lg-3"><input type="date" class="form-control" wire:model="fechaSeleccionada" wire:change="cambioDeFecha"></div>
            
            {{-- <a href="#" wire:click="cambiarDisponibilidad"
                data-bs-toggle="modal" data-bs-target="#modalDisponibilidad"><span
                    class="badge badge-pill badge-primary">Cambiar Disponibilidad</span></a> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive " style="padding:5px">
                <table class="table table-responsive-sm">
                    <thead style="padding:5px">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sopa</th>
                            {{-- <th>Ensalada</th> --}}
                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            {{-- <th>Jugo</th> --}}
                            <th>Empaque</th>
                            <th>Envio</th>
                            <th>Plan</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    
                    <tbody wire:loading.remove wire:target="cambioDeFecha" style="padding:5px">
                        @foreach ($coleccion as $lista)
                        <tr class="{{$lista['ESTADO']=='finalizado'?'table-success':'' }}{{$lista['ESTADO']=='permiso'?'table-warning':''}}" style="padding:5px">
                            <td style="padding:5px">{{$loop->iteration}}</td>
                            
                            <td style="padding:5px">{{Str::limit($lista['NOMBRE'],20)}}</td>

                            <td style="padding:5px">{{$lista['SOPA']!=""?'SI':''}}</td>
                            {{-- <td style="padding:5px">{{$lista['ENSALADA']!=""?'SI':''}}</td> --}}
                            <td style="padding:5px">{{$lista['PLATO']}}</td>
                            <td style="padding:5px">{{$lista['CARBOHIDRATO']}}</td>
                            {{-- <td style="padding:5px">{{$lista['JUGO']!=""?'SI':''}}</td> --}}
                            <td style="padding:5px">{{$lista['EMPAQUE']}}</td>
                            <td style="padding:5px">{{$lista['ENVIO']}}</td>
                            <td style="padding:5px">{{Str::limit($lista['PLAN'],20)}}</td>
                            @if ($lista['ESTADO']=="pendiente")
                            <td style="padding:5px"><button wire:click="cambiarEstado('{{$lista['ID']}}')" wire:loading.attr="disabled" wire:target="cambiarEstado('{{$lista['ID']}}')"
                                    class="btn btn-info">Pendiente</button></td>

                            @elseif($lista['ESTADO']=="finalizado")
                            <td style="padding:5px"><button wire:click="cambiarAPendiente('{{$lista['ID']}}')"
                                    class="btn btn-success">Finalizado</button></td>
                            @elseif($lista['ESTADO']=="permiso")
                            <td style="padding:5px"><button 
                                class="btn btn-warning" disabled>Permiso</button></td>
                            @endif

                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            
                            @foreach ($total[0] as $producto=>$array)
                            <th><small>
                                    @foreach ($array as $nombre=>$cantidad)
                                    @if ($nombre!='')
                                    <span
                                    class="badge badge-pill badge-primary light">{{$nombre}}:{{$cantidad}}</span><br>
                                    @endif
                                   
                                    @endforeach</th>
                            @endforeach
                            </small>

                        </tr>


                    </tbody>
                   
                </table>
                <div  class="d-flex justify-content-center">
                    <h4>Planes para este dia:{{$coleccion->count()}}</h4>
                    <a href="#" wire:click="exportarexcel" class="badge badge-success pill light">Exportar</a>
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

  <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Esta seguro?</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
         Los usuarios ya no podran registrar sus planes para el dia {{date_format(date_create($fechaSeleccionada),"d-M")}}
        </div>
        <div class="modal-footer">
          
          <button type="button" class="btn btn-primary" wire:click="finalizarTodos">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
</div>
