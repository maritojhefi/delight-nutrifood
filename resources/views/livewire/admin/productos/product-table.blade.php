<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row">
            <div class="col"><h4 class="card-title">Lista de Productos</h4> </div>
            <div class="col"> <input type="text" class="form-control form-control-sm" placeholder="Buscar" wire:model.debounce.750ms="buscar"></div>
               
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                
                                
                                <th><strong>Nombre</strong></th>
                                <th><strong>Precio</strong></th>
                                <th><strong>Subcategoria</strong></th>
                                <th><strong>Estado</strong></th>
                                <th><strong>Descuento</strong></th>
                                <th><strong>Acciones</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($productos as $item)
                           <tr>
                               
                            <td><div class="d-flex align-items-center"><img src="{{asset('delight_logo.jpg')}}" class="rounded-lg me-2" width="24" alt=""> <strong>{{$item->nombre}}</strong></div></td>
                            <td><span class="w-space-no">{{$item->precio}} Bs</span></td>
                            <td><span class="w-space-no">{{$item->subcategoria->nombre}}</span></td>
                            <td><a href="#" wire:click="cambiarestado('{{$item->id}}')"><div class="d-flex align-items-center"><i class="fa fa-circle text-{{$item->estado=='activo'?'success':'danger'}} me-1" ></i> {{$item->estado}}</div></a></td>
                            
                            <td>{{$item->descuento}} Bs</td>
                            <td>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-pencil"></i></a>
                                    <a href="#" class="btn btn-danger shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#modaldelete{{$item->id}}" ><i class="fa fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="modaldelete{{$item->id}}">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Esta seguro?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">Eliminando <strong>{{$item->nombre}}</strong> </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger btn-sm light" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal" wire:click="eliminar('{{ $item->id }}')">Aceptar</button>
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
                <div class="col">{{$productos->links()}}</div>
            </div>
            <div class="row  mx-auto">
                <div class="col">Mostrando {{$productos->count()}} de {{$productos->total()}} registros</div>
            </div>
        </div>
    </div>
</div>
