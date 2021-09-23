<div class="row">
    <div class="col-xl-8 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nuevo Usuario</h4>
                </div>
                <div class="card-body">
                    <div class="">
                        <x-input-create  :lista="([
                            'Nombre'=>['name','text'],
                            'Correo'=>['email','email'],
                            'Nacimiento'=>['cumpleano','date','(opcional)'],
                            'Direccion'=>['direccion','text','(opcional)'],
                            'Contraseña'=>['password','password'],
                           
                              ])" >
                              <x-slot name="otrosinputs">
                            
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Rol</label>
                                    <div class="col-sm-9">
                                        <select  wire:model="rol"  class="form-control @error($rol)is-invalid @enderror">
                                            <option  class="dropdown-item" aria-labelledby="dropdownMenuButton" >Seleccione una opcion</option>
                                            @foreach ($roles as $rol)
                                            <option value="{{$rol->id}}" class="dropdown-item" aria-labelledby="dropdownMenuButton" >{{$rol->nombre}}</option>
                                            
                                            @endforeach
                                        </select>
                                    </div>
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
                    <h4 class="card-title">Listado de Usuarios</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Rol</strong></th>
                                   
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $item)
                                <tr>
                                    
                                 <td><div class="d-flex align-items-center"><strong>{{$item->name}}</strong></div></td>
                               
                                 <td><span class="w-space-no">{{$item->role->nombre}}</span></td>
                                 
                                 
                                
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
                                         <div class="modal-body">Eliminando <strong>{{$item->name}}</strong> </div>
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
                    <div class="col">{{$usuarios->links()}}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{$usuarios->count()}} de {{$usuarios->total()}} registros</div>
                </div>
            </div>
        
        </div>
    </div>
   
</div>
