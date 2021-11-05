<div class="row">
    <div class="col-xl-8 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nuevo Plan</h4>
                </div>
                <div class="card-body">
                    @empty($productoseleccionado)
                    <label for="" class="">Seleccione un producto</label>
                    <input type="text" wire:model.debounce.750ms="producto" class="form-control mb-2">
                    @foreach ($productos as $item)
                      <a href="#" wire:click="seleccionarproducto('{{$item->id}}')"><span class="badge light badge-success">{{$item->nombre}}</span></a>  
                    @endforeach
                    @endempty
                    
                    @isset($productoseleccionado)
                    <div class="">
                        <span class="badge light badge-lg m-3 badge-danger">{{$productoseleccionado->nombre}}({{$productoseleccionado->precio}} Bs)<button type="button" class="btn-close" wire:click="resetproducto()"></button></span>
                        <x-input-create  :lista="([
                            'Nombre'=>['nombre','text'],
                          
                            'Detalle'=>['detalle','text'],
                              ])">
                               </x-input-create>
                       
                    </div> 
                    @endisset
                   
                   
                </div>
            </div>
         
        </div>
    </div>
   
    <div class="col-xl-4 col-lg-12 col-xxl-7 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Listado de Planes</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Detalle</strong></th>
                                  
                                  
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($planes as $item)
                                <tr>
                                    <td><span class="badge light badge-success">{{$item->nombre}}</span></td>
                                    <td>{{$item->detalle}}</td>
                                  
                                   
                                    
                                    
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-danger light sharp" data-bs-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" >Editar</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modaldelete{{$item->id}}">Eliminar</a>
                                            </div>
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
                    <div class="col">{{$planes->links()}}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{$planes->count()}} de {{$planes->total()}} registros</div>
                </div>
            </div>
        
        </div>
    </div>
   
</div>
