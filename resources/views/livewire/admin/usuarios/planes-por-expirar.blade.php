<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Reporte de Planes expirados/por expirar</h4>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-5">
                <div class="input-group input-{{ $estadoColor }}">
                    <a href="#" wire:click="cambiarEstadoBuscador" class="input-group-text">{{ $estadoBuscador }}</a>
                    <input type="text" class="form-control" wire:model.debounce.500ms="search">
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <div wire:loading class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Restantes</th>
                                <th>Ultima Fecha</th>
                                <th>Plan</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coleccion as $usuario)
                            <tr>
                                <td><a href="{{route('detalleplan',[$usuario['user_id'],$usuario['plan_id']])}}">{{Str::limit($usuario['nombre'],30,'')}}</a>
                                </td>
                                <td><span class="badge badge-{{$usuario['cantidadRestante']>0?'success':'danger'}}">{{$usuario['cantidadRestante']>0?'Vigente':'Expirado'}}</span>
                                </td>
                                
                                <th class="color-primary">{{$usuario['cantidadRestante']}}</th>
                                <td>{{$usuario['ultimoDia']}}</td>
                                <td>{{Str::limit($usuario['plan'],30,'')}}</td>
                            </tr>
                            @endforeach
                            
                            
                           
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
