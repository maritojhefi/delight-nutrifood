<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row">
                <div class="col">
                    <h4 class="card-title">Productos por expirar</h4>
                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center">
                        <div wire:loading class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="col"> <input type="text" class="form-control form-control-sm"
                        placeholder="Buscar producto" wire:model.debounce.750ms="search"></div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>

                                <th></th>
                                <th><strong>Nombre</strong></th>
                                <th><strong>Restantes/lote</strong></th>
                                <th><strong>Estado</strong></th>

                                <th><strong>Fecha Vencimiento</strong></th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $item)
                                <tr>
                                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#modal"
                                            wire:click="seleccionar({{ $item->id }})"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td>
                                        <a href="{{ route('sucursal.stock') }}">
                                            <div class="d-flex align-items-center">
                                                <strong>{{ Str::limit($item->nombre, 75) }} </strong>
                                                @if ($item->descuento)
                                                    <del class="badge badge-danger">{{ $item->precio }}</del><span
                                                        class="badge badge-success">{{ $item->descuento }} Bs</span>
                                                @else
                                                    <span class="badge badge-success">{{ $item->precio }} Bs</span>
                                                @endif
                                            </div>
                                        </a>

                                    </td>

                                    {{-- <td><a href="#" data-bs-toggle="modal" data-bs-target="#modalCantidad{{$item->folioStock}}">{{ $item->cantidad }}</a></td> --}}
                                    <td>{{ $item->cantidad }}</td>
                                    <td><span class="w-space-no">
                                            @if (Carbon\Carbon::now()->startOfDay()->gte($item->fecha_venc) == true)
                                                <span class="badge badge-danger">expirado (Hace
                                                    {{ Carbon\Carbon::parse($item->fecha_venc)->diffInDays() }}
                                                    dias)</span>
                                            @else
                                                <span class="badge badge-info">vigente (Quedan
                                                    {{ Carbon\Carbon::parse($item->fecha_venc)->diffInDays() }}
                                                    dias)</span>
                                            @endif


                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalFecha{{ $item->folioStock }}"> <span
                                                class="badge badge-warning">{{ date_format(date_create($item->fecha_venc), 'd-M-y') }}</span></a>
                                    </td>
                                    <td>
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar{{ $item->folioStock }}"> <i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                    <div wire:ignore.self class="modal fade" id="modalCantidad{{ $item->folioStock }}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar la cantidad</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
                                                <div class="modal-body"><input type="number" class="form-control"
                                                        wire:model.lazy="cantidad"></div>
                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-success"
                                                        wire:click="actualizarCantidad({{ $item->folioStock }})"
                                                        data-bs-dismiss="modal">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div wire:ignore.self class="modal fade" id="modalFecha{{ $item->folioStock }}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar la fecha de vencimiento</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
                                                <div class="modal-body"><input type="date" class="form-control"
                                                        wire:model.lazy="fecha"></div>
                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-success"
                                                        wire:click="actualizarFecha({{ $item->folioStock }})"
                                                        data-bs-dismiss="modal">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div wire:ignore.self class="modal fade" id="modalEliminar{{ $item->folioStock }}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Esta seguro de eliminar este stock?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-danger"
                                                        wire:click="eliminarStock({{ $item->folioStock }})"
                                                        data-bs-dismiss="modal">Eliminar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div wire:ignore.self class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true"
        id="modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                @isset($seleccionado)
                    <div class="modal-header">
                        <h5 class="modal-title">Editando descuento de {{ $seleccionado->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="number" step="any" wire:model.lazy="nuevoPrecio" class="form-control"
                            placeholder="Ingrese el precio con descuento">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm light"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal"
                            wire:click="cambiar('{{ $seleccionado->id }}')">Aceptar</button>
                    </div>
                @endisset

            </div>
        </div>
    </div>

</div>
