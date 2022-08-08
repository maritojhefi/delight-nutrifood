<div>
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Reporte de planes con atencion whatsapp</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>

                                    <th><strong>Numero</strong></th>
                                    <th><strong>Segundo</strong></th>
                                    <th><strong>Carbohidrato</strong></th>
                                    <th><strong>Envio</strong></th>
                                    <th><strong>Empaque</strong></th>

                                    <th><strong>Estado</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reporte as $ticket)
                                <tr>
                                    @php
                                        $array=json_decode($ticket->detalle)
                                    @endphp
                                    <td>{{$ticket->name}}</td>
                                    <td>{{$ticket->telf}}</td>
                                    <td>{{$array->PLATO}}</td>
                                    <td>{{$array->CARBOHIDRATO}}</td>
                                    <td>{{$array->ENVIO}}</td>
                                    <td>{{$array->EMPAQUE}}</td>
                                    <td><span class="btn btn-{{$ticket->whatsapp?'success':'snapchat'}} btn-sm">{{$ticket->whatsapp?'Finalizado':'En desarrollo'}}</span></td>
                                </tr>
                                
                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        
        </div>
    </div>

    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Planes Finalizados</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>

                                    <th><strong>Numero</strong></th>
                                    <th><strong>Segundo</strong></th>
                                    <th><strong>Carbohidrato</strong></th>
                                    <th><strong>Envio</strong></th>
                                    <th><strong>Empaque</strong></th>

                                    <th><strong>Estado</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reporteFinalizados as $ticket)
                                <tr>
                                    @php
                                        $array=json_decode($ticket->detalle)
                                    @endphp
                                    <td>{{$ticket->name}}</td>
                                    <td>{{$ticket->telf}}</td>
                                    <td>{{$array->PLATO}}</td>
                                    <td>{{$array->CARBOHIDRATO}}</td>
                                    <td>{{$array->ENVIO}}</td>
                                    <td>{{$array->EMPAQUE}}</td>
                                    <td><span class="btn btn-{{$ticket->whatsapp?'success':'snapchat'}} btn-sm">{{$ticket->whatsapp?'Finalizado':'En desarrollo'}}</span></td>
                                </tr>
                                
                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        
        </div>
    </div>
</div>
