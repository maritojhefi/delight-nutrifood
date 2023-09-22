<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">Reporte de Cumpleaños</h4>
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
                            <a href="#" wire:click="cambiarEstadoBuscador" class="input-group-text">Buscar</a>
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
                                <th>Nombre</th>
                                <th>Fecha nacimiento</th>
                                <th>Dias Restantes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->name }}</td>
                                    <td><span class="badge badge-success">{{ $usuario->nacimiento }}</span>
                                        {{App\Helpers\GlobalHelper::fechaFormateada(2,$usuario->nacimiento)}}</td>
                                    <td>{{ $usuario->days_until_birthday }}</td>
                                   

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
