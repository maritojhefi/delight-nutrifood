<div>
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Listado de Subwhatsapps</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>

                                    <th><strong>Numero</strong></th>
                                    <th><strong>Contenido</strong></th>
                                    <th><strong>Template</strong></th>
                                    <th><strong>Tipo</strong></th>
                                    <th><strong></strong></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($whatsapps as $item)
                                <tr>
                                    <td>{{$item->usuario->name}}</td>
                                    <td><span class="badge light badge-info">{{$item->destino}}</span></td>
                                    <td>{{Str::limit($item->contenido,'20')}}</td>
                                    <td>{{$item->template}}</td>
                                    <td><span class="badge light badge-warning">{{$item->tipo}}</span></td>
                                   
                                   
                                </tr>
                                
                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">{{$whatsapps->links()}}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{$whatsapps->count()}} de {{$whatsapps->total()}} registros</div>
                </div>
            </div>
        
        </div>
    </div>
</div>
