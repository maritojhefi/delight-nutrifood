<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">Reporte de Saldos</h4>
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
                                <th>Balance</th>
                                <th>Tipo</th>
                                <th>Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->name }}</td>
                                    @if ($usuario->saldo < 0)
                                        <td><span class="badge badge-warning"><strong>{{ $usuario->saldo }}
                                                    Bs</strong></span></td>
                                    @else
                                        <td><span class="badge badge-danger"><strong>{{ $usuario->saldo }}
                                                    Bs</strong></span></td>
                                    @endif
                                    <td><span
                                            class="badge badge-{{ $usuario->saldo < 0 ? 'warning' : 'danger' }}">{{ $usuario->saldo < 0 ? 'A favor del cliente' : 'Deuda acumulada' }}</span>
                                    </td>
                                    <td><a href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalSaldos{{ $usuario->id }}" class="badge badge-info"><i
                                                class=" fa fa-eye"></i></a></td>
                                </tr>
                                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
                                    id="modalSaldos{{ $usuario->id }}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Balance de Saldo: {{ $usuario->saldo }} Bs</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @foreach ($usuario->saldos->sortByDesc('created_at') as $item)
                                                    <ul class="list-group mb-3">
                                                        @if ($item->es_deuda)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between lh-condensed">
                                                                <div>
                                                                    <h6 class="my-0">Deuda creada a partir de una
                                                                        venta
                                                                        <small>({{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }})</small>
                                                                    </h6>
                                                                    <strong>Detalle productos</strong><br>
                                                                    @foreach ($item->venta->productos as $prod)
                                                                        <small
                                                                            class="text-muted">{{ $prod->pivot->cantidad }}
                                                                            {{ $prod->nombre }}</small><br>
                                                                    @endforeach


                                                                </div>
                                                                <span class="text-muted">{{ $item->monto }} Bs</span>
                                                            </li>
                                                        @else
                                                            <li
                                                                class="list-group-item d-flex justify-content-between active">
                                                                <div class="text-white">
                                                                    <h6 class="my-0 text-white">Saldo creado a favor del
                                                                        cliente
                                                                        <small>({{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }})</small>
                                                                    </h6>
                                                                    <small><strong>Detalle:</strong>
                                                                        {{ $item->detalle }}</small>
                                                                </div>
                                                                <span class="text-white"> {{ $item->monto }} Bs</span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
