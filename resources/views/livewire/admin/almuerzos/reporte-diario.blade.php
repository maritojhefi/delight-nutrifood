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
                            <th>Envase</th>
                            <th>Ensalada</th>
                            <th>Sopa</th>
                            <th>Plato</th>
                            <th>Carbohidrato</th>
                            <th>Jugo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coleccion as $lista)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                           <td>{{$lista['NOMBRE']}}</td>
                           <td>{{$lista['ENVASE']}}</td>
                           <td>{{$lista['ENSALADA']}}</td>
                           <td>{{$lista['SOPA']}}</td>
                           <td>{{$lista['PLATO']}}</td>
                           <td>{{$lista['CARBOHIDRATO']}}</td>
                           <td>{{$lista['JUGO']}}</td>
                            
                           
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            @foreach ($total[0] as $producto=>$array)
                            <th><small>
                            @foreach ($array as $nombre=>$cantidad)
                                {{$nombre}}:{{$cantidad}}
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