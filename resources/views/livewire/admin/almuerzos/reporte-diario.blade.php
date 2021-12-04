<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Reporte de hoy {{date("d-M");}}</h4>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coleccion as $lista)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                           <td>{{$lista['NOMBRE']}}</td>
                           
                           <td>{{$lista['SOPA']}}</td>
                           <td>{{$lista['ENSALADA']}}</td>
                           <td>{{$lista['PLATO']}}</td>
                           <td>{{$lista['CARBOHIDRATO']}}</td>
                           <td>{{$lista['JUGO']}}</td>
                           <td>{{$lista['EMPAQUE']}}</td>
                           <td>{{$lista['ENVIO']}}</td>
                            
                           
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            @foreach ($total[0] as $producto=>$array)
                            <th><small>
                            @foreach ($array as $nombre=>$cantidad)
                                <span class="badge badge-pill badge-primary light">{{$nombre}}:{{$cantidad}}</span><br>
                            @endforeach</th>
                            @endforeach
                            </small>
                            
                        </tr>
                        
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
