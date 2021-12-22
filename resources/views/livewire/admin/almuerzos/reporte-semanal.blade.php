<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Reporte de esta semana <span class="badge badge-primary light ">
                    {{date("d-M-y", strtotime("last monday"))}}</span> hasta<span
                    class="badge badge-warning">{{date("d-M-y", strtotime("next sunday"))}}</span> </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Dia</th>
                            <th>Nombre</th>
                            <th>Sopas</th>
                            <th>Ensaladas</th>

                            <th>Platos</th>
                            <th>Carbohidratos</th>
                            <th>Jugos</th>
                            <th>Empaques</th>
                            <th>Envios</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($distribuido as $dia=>$item)
                        <tr>
                            <td><a href="#" wire:click="resumenDia('{{$dia}}')" data-bs-toggle="modal" data-bs-target="#modalResumen"><span class="badge badge-info light badge-lg">{{$dia}}</span></a></td>


                            <td> @foreach ($item as $lista)
                               <span class="badge badge-{{$lista['NOMBRE']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['NOMBRE']}}</span> <br>
                                @endforeach</td>
                            <td> @foreach ($item as $lista)
                                <span class="badge badge-{{$lista['SOPA']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['SOPA']}}</span> <br>
                                @endforeach</td>
                            <td> @foreach ($item as $lista)
                                <span class="badge badge-{{$lista['ENSALADA']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['ENSALADA']}}</span> <br>
                                @endforeach</td>
                            <td> @foreach ($item as $lista)
                                <span class="badge badge-{{$lista['PLATO']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['PLATO']}}</span> <br>
                                @endforeach</td>
                            <td> @foreach ($item as $lista)
                                <span class="badge badge-{{$lista['CARBOHIDRATO']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['CARBOHIDRATO']}}</span> <br>
                                @endforeach</td>
                            <td> @foreach ($item as $lista)
                                <span class="badge badge-{{$lista['JUGO']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['JUGO']}}</span> <br>
                                @endforeach</td>
                            <td> @foreach ($item as $lista)
                                <span class="badge badge-{{$lista['EMPAQUE']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['EMPAQUE']}}</span> <br>
                                @endforeach</td>
                            <td> @foreach ($item as $lista)
                                <span class="badge badge-{{$lista['ENVIO']=='N/D'?'danger':'primary'}} light badge-xs"> {{$lista['ENVIO']}}</span> <br>
                                @endforeach</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td><button class="btn btn-warning">RESUMEN</button></td>
                            <td></td>
                            @foreach ($total[0] as $producto=>$array)
                            <th><small>
                            @foreach ($array as $nombre=>$cantidad)
                                <span class="badge badge-pill badge-{{$nombre=='N/D'?'danger':'warning light'}} ">{{$nombre}}: <strong>{{$cantidad}}</strong> </span><br>
                            @endforeach</th>
                            @endforeach
                            </small>
                            
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @isset($diaSeleccionado)
    <div wire:ignore.self class="modal fade" id="modalResumen">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resumen del dia: {{$diaSeleccionado}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($resumen[0] as $nombre=>$array)
                    <span class="badge badge-primary">{{$nombre}}</span>
                    <ul>
                        @foreach ($array as $plato=>$cantidad)
                            <li>{{$plato}}:{{$cantidad}}</li>
                        @endforeach
                    </ul>
                       <br> 
                    @endforeach
                </div>
               
            </div>
        </div>
    </div>
    @endisset
    
</div>
