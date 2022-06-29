<div class="row">
    <div class="col-xl-8 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nueva SubCategoria</h4>
                </div>
                <div class="card-body">
                    <div class="">
                        <x-input-create  :lista="([
                            'Nombre'=>['nombre','text'],
                            'Descripcion'=>['descripcion','textarea'],
                              ])" >
                        <x-slot name="otrosinputs">
                            <div class="mb-3 row">
                               <select name="" class="form-control @error($categoria) is-invalid @enderror" wire:model="categoria" id="">

                                   <option>Seleccione categoria ({{$categorias->count()}})</option>
                                   @foreach ($categorias as $item)
                                   <option  value="{{$item->id}}">{{$item->nombre}}</option>
                                   @endforeach
                               </select>
                            </div>
                        </x-slot>
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
                    <div class="row">
                        <div class="col"> <h4 class="card-title">Todas</h4></div>
                        <div class="col"><input type="text" class=" form-control" placeholder="Buscar" wire:model.debounce.500ms="search"></div>
                    </div>
                   
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Descripcion</strong></th>
                                    <th><strong>Categoria</strong></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategorias as $item)
                                <tr>
                                    <td><span class="badge light badge-info">{{$item->nombre}}</span></td>
                                    <td>{{$item->descripcion}}</td>
                                    @if ($item->categoria)
                                    <td>{{$item->categoria->nombre}}</td> 
                                    @else
                                    <td><span class="badge light badge-danger">Sin categoria</span></td> 
                                    @endif
                                    
                                    
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-danger light sharp" data-bs-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"  href="#" data-bs-toggle="modal" wire:click="seleccionarSubcategoria({{$item->id}})" data-bs-target="#modaleditar">Editar</a>
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
                    <div class="col">{{$subcategorias->links()}}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{$subcategorias->count()}} de {{$subcategorias->total()}} registros</div>
                </div>
            </div>
        
        </div>
    </div>
    <div wire:ignore.self class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="modaleditar">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body" wire:loading wire:target="seleccionarSubcategoria">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                </div>
                @isset($subcategoria)
                <div wire:loading.remove wire:target="seleccionarSubcategoria">
                    <div class="modal-header">
                        <h5 class="modal-title">Editando : {{$subcategoria->nombre}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <x-input-create-custom-function funcion="actualizar" boton="Actualizar" :lista="([
                            'Nombre'=>['nombreE','text'],
                            'Descripcion'=>['descripcionE','textarea'],
                            
                              ])">
                            <x-slot name="otrosinputs">
                                <div class="mb-3 row">
                                   <select name="" class="form-control @error($categoria) is-invalid @enderror" wire:model="categoriaE" id="">
    
                                       <option>Seleccione categoria ({{$categorias->count()}})</option>
                                       @foreach ($categorias as $item)
                                       <option  value="{{$item->id}}">{{$item->nombre}}</option>
                                       @endforeach
                                   </select>
                                </div>
                            </x-slot>
                       
                    </x-input-create-custom-function>
                    </div>
                </div>
               
                
                @endisset
                
            </div>
        </div>
    </div>
</div>