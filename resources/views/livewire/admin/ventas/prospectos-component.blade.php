<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">Prospectos</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-center">
                            <div wire:loading class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-info">
                            <a href="#" class="input-group-text">Buscar</a>
                            <input type="text" class="form-control" wire:model.debounce.700ms="search">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Telefono</th>
                                <th>Metodo Pago</th>
                                <th>Fecha</th>
                                <th>Observacion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prospectos as $item)
                                <tr>
                                    <td>{{isset($item->cliente)?$item->cliente:'Desconocido'}}</td>
                                    <td>{{isset($item->telefono)?$item->telefono:'N/A'}}</td>
                                    <td>{{isset($item->metodo)?$item->metodo:'No asignado'}}</td>
                                    <td>{{isset($item->fecha)?$item->fecha:'Sin fecha'}}</td>
                                    <td><small>{{isset($item->observacion)?$item->observacion:'N/A'}}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row  mx-auto table-responsive">
                <div class="col">{{$prospectos->links()}}</div>
            </div>
        </div>
    </div>
