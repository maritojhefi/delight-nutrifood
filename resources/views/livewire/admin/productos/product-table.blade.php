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
                                <th><strong>Stock</strong></th>
                                <th><strong>Puntos</strong></th>
                                <th><strong>Subcategoria</strong></th>
                                <th><strong>Codigo Barra</strong></th>
                                <th><strong>Medicion</strong></th>
                                <th><strong>Estado</strong></th>
                                <th><strong>Contable</strong></th>
                                <th><strong>Acciones</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($productos as $item)
                           <tr>
                               
                            <td><div class="d-flex align-items-center">@if($item->imagen)<img src="{{asset($item->pathAttachment())}}" class="rounded-lg me-2" width="24" alt="">@else<img src="{{asset('delight_logo.jpg')}}" class="rounded-lg me-2" width="24" alt=""> @endif<strong>{{$item->nombre}}</strong></div></td>
                            
                            @if ($item->descuento)
                            <td><del class="w-space-no badge light badge-danger">{{$item->precio}}</del><span class="w-space-no badge light badge-success">{{$item->descuento}} Bs</span></td>
                            @else
                            <td><span class="w-space-no badge light badge-success">{{$item->precio}} Bs</span></td>

                            @endif
                            <td><span class="w-space-no">
                                
                                <button class="btn btn-success light btn-xxs" data-bs-toggle="modal" data-bs-target="#modalgrande{{$item->id}}">Ver stock</button>
                               
                                </span>
                            </td>
                            <td><span class="w-space-no">{{$item->puntos}}</span></td>
                            <td><span class="w-space-no">{{$item->subcategoria->nombre}}</span></td>
                            @if ($item->codigoBarra)
                            <td><img src="data:image/png;base64,{{DNS1D::getBarcodePNG($item->codigoBarra, 'C39+',1,33)}}" alt=""></td>
                            @else
                            <td><span class="badge light badge-danger">Sin codigo</span></td>
                            @endif
                            <td><span class="w-space-no">{{$item->medicion}}</span></td>
                            <td><a href="#" wire:click="cambiarestado('{{$item->id}}')"><div class="d-flex align-items-center"><i class="fa fa-circle text-{{$item->estado=='activo'?'success':'danger'}} me-1" ></i> {{$item->estado}}</div></a></td>
                            <td><a href="#" wire:click="cambiarcontable('{{$item->id}}')"><span class="badge badge-{{$item->contable==0?'warning':'success'}}">{{$item->contable==0?'NO':'SI'}}</span></a></td>
                           
                            <td>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target="#modalEditar" wire:click="editarProducto({{$item->id}})"><i class="fa fa-pencil"></i></a>
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


                        <div class="modal fade" id="modalgrande{{$item->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Lotes dentro de cada sucursal para "{{$item->nombre}}"</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                       @if($item->sucursale->count()!=0)
                                       @foreach ($item->sucursale as $stock)
                                       <div class="card overflow-hidden bg-image-2 bg-secondary">
                                           <div class="card-header  border-0">
                                               <div>
                                                   <small class="mb-2 font-w100 text-white">Lugar: {{$stock->nombre}}  -  Vencimiento: {{date('d-m-Y', strtotime($stock->pivot->fecha_venc))}}</small>
                                                   
                                                   <h3 class="mb-0 fs-24 font-w600 text-white">Cantidad: {{$stock->pivot->cantidad}}</h3>
                                               </div>
                                           </div>
                                       </div>
                                       
                                       @endforeach
                                       
                                       @else
                                           No hay registro de stock para este producto
                                       @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Cerrar</button>
                                       
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

    <div wire:ignore.self class="modal fade" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <x-input-create-custom-function funcion="actualizarProducto" boton="Actualizar" :lista="([
                        'Nombre'=>['nombre','text'],
                        'Precio'=>['precio','number'],
                        'Descuento'=>['descuento','number','Precio que se cobrara'],
                        'Detalle'=>['detalle','textarea'],
                        'Puntos'=>['puntos','number'],
                        'Imagen'=>['imagen','file'],
                          ])">
                        
                    @if ($imagen)
                    <img src="{{ $imagen->temporaryUrl() }}" class="w-100 border-radius-lg shadow-sm">
                   @endif
                </x-input-create-custom-function>
                </div>
                
            </div>
        </div>
    </div>
</div>
