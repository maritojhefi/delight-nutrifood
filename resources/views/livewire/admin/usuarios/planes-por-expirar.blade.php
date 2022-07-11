<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Reporte de Planes expirados/por expirar</h4>
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
