<div>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        Listado de tickets en atencion
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Cantidad</th>
                                    <th>Paso segundos</th>
                                    <th>Paso Carbohidrato</th>
                                    <th>Paso Metodo Envio</th>
                                    <th>Paso Empaque</th>
                                    <th>Fecha</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($tickets as $ticket)
                                    <td>{{$ticket->cliente->name}}</td>
                                    <td>{{$ticket->cantidad}}</td>
                                    <td>{{$ticket->paso_segundo?'FINALIZADO':'PENDIENTE'}}</td>
                                    <td>{{$ticket->paso_carbohidrato?'FINALIZADO':'PENDIENTE'}}</td>
                                    <td>{{$ticket->paso_metodo_envio?'FINALIZADO':'PENDIENTE'}}</td>
                                    <td>{{$ticket->paso_metodo_empaque?'FINALIZADO':'PENDIENTE'}}</td>
                                    <td>{{$ticket->created_at->format('d-M-Y')}}</td>
                                    <td><button wire:click="eliminar({{$ticket->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                                    @endforeach
                                </tr>
                            </tbody>
                           

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
