<div class="row">
    @if ($usuarioSeleccionado)
        <div class="col-8 mx-0">
            <div class="card">
                <div class="card-header py-1">
                    <span class="">Registros pendientes de:
                        <br><strong>{{ $usuarioSeleccionado->name }}</strong></span>
                    <div class="float-end"><a href="#" class="badge badge-sm badge-danger p-1"
                            wire:click="cerrarDetalle">Cambiar <i class="flaticon-075-reload"></i></a></div>
                </div>
                <div class="card-body p-1">
                    <table class="table table-striped table-responsive-sm table-hover">
                        <thead>
                            <tr class="">
                                <th class="p-1">Detalle</th>
                                <th class="p-1">Monto</th>
                                <th class="p-1">Balance</th>
                                <th class="p-1">Tipo</th>
                                <th class="p-1">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <td class="p-0"><a href="#"
                                wire:click="seleccionarVenta({{ $venta->id }})"
                                data-bs-target="#modalDetalleVenta" data-bs-toggle="modal"
                                class="badge badge-xxs badge-info py-0 px-1 m-0"><i
                                    class="fa fa-list"></i></a></td> --}}
                            @foreach ($usuarioSeleccionado->saldosVigentes as $saldo)
                                <tr class="" style="cursor: pointer;" data-bs-target="#modalDetalleVenta"
                                    data-bs-toggle="modal" wire:click="verSaldoDetalleVenta({{ $saldo->id }})">
                                    <td class="p-1">
                                        @if ($saldo->historial_ventas_id)
                                            <a href="#" class="text-info">Saldo desde Venta</a>
                                        @else
                                            <span class="text-muted">{{ $saldo->detalle }}</span>
                                        @endif
                                    </td>
                                    <td class="p-1">{{ $saldo->monto }} Bs</td>
                                    @if ($saldo->saldo_restante > 0)
                                        <td class="p-1 text-danger">{{ $saldo->saldo_restante_formateado }} Bs </td>
                                    @else
                                        <td class="p-1 text-success">{{ $saldo->saldo_restante_formateado }} Bs</td>
                                    @endif

                                    @if ($saldo->es_deuda)
                                        <td class="p-1 text-danger"><strong class=" p-2">Deuda</strong>
                                            <i class="flaticon-001-arrow-down"></i>
                                        </td>
                                    @else
                                        <td class="p-1 text-success"><strong class="p-2">A
                                                favor</strong> <i class="flaticon-003-arrow-up"></i>
                                        </td>
                                    @endif

                                    <td class="p-1 letra10">
                                        {{ App\Helpers\GlobalHelper::fechaFormateada(7, $saldo->created_at) }}<br><small
                                            class="text-muted">{{ App\Helpers\GlobalHelper::timeago($saldo->created_at) }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('livewire.admin.caja.includes.modal-detalle-venta')
    @else
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header py-1">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title"></h4>
                        </div>
                        <div class="col">
                            <div class="d-flex justify-content-center">
                                <div wire:loading class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group input-info">
                                <a href="#" wire:click="cambiarEstadoBuscador" class="input-group-text">Buscar</a>
                                <input type="text" class="form-control" wire:model.debounce.700ms="search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-6 mx-auto">
                            <h3 class="text-center">Con Deuda</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-sm table-hover">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="p-1">Cliente</th>
                                            <th class="p-1">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clientesConDeuda as $usuario)
                                            <tr style="cursor: pointer;"
                                                wire:click="seleccionarUsuario({{ $usuario->id }})">
                                                <td class="p-1 align-middle">{{ $usuario->name }}</td>
                                                <td class="p-1 align-middle"><span
                                                        class="badge badge-warning badge-sm p-2"><strong>{{ $usuario->saldo_formateado }}
                                                            Bs</strong></span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row mx-auto">
                                    {{ $clientesConDeuda->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mx-auto">
                            <h3 class="text-center">Con Saldo a Favor</h3>
                            <table class="table table-striped table-responsive-sm table-hover">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="p-1">Cliente</th>
                                        <th class="p-1">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clientesConExcedente as $usuario)
                                        <tr style="cursor: pointer;"
                                            wire:click="seleccionarUsuario({{ $usuario->id }})">
                                            <td class="p-1 align-middle">{{ $usuario->name }}</td>
                                            <td class="p-1 align-middle"><span
                                                    class="badge badge-success badge-sm p-2"><strong>{{ $usuario->saldo_formateado }}
                                                        Bs</strong></span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row mx-auto">
                                {{ $clientesConExcedente->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
