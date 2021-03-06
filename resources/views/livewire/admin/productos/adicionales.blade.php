<div class="row">
    <div class="col-xl-8 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nuevo Adicional</h4>
                </div>
                <div class="card-body">
                    <div class="">
                        <x-input-create :lista="([
                            'Nombre'=>['nombre','text'],
                            'Precio'=>['precio','number'],
                              ])">

                        </x-input-create>

                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="col-xl-4 col-lg-12 col-xxl-7 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Listado de adicionales</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Precio</strong></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adicionales as $item)
                                <tr>
                                    <td><span class="badge light badge-info">{{$item->nombre}}</span></td>
                                    <td>{{$item->precio}} Bs</td>



                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-danger light sharp"
                                                data-bs-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a  class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditar" wire:click="editar({{$item->id}})" >Editar</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#modaldelete{{$item->id}}">Eliminar</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
                                    aria-hidden="true" id="modaldelete{{$item->id}}">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Esta seguro?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <div class="modal-body">Eliminando <strong>{{$item->nombre}}</strong> </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger btn-sm light"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    data-bs-dismiss="modal"
                                                    wire:click="eliminar('{{ $item->id }}')">Aceptar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">{{$adicionales->links()}}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{$adicionales->count()}} de {{$adicionales->total()}} registros</div>
                </div>
            </div>

        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editando</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Adicional</label>
                        <div class="col-sm-8">
                            <input type="text" wire:model.defer="nombre"
                                class=" form-control  @error('nombre') is-invalid @enderror">
                            @error('nombre') <small class="error">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Precio</label>
                        <div class="col-sm-8">
                            <input type="number" step="any" wire:model.defer="precio"
                                class=" form-control  @error('precio') is-invalid @enderror">
                            @error('precio') <small class="error">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" wire:click="guardarEdit">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>
