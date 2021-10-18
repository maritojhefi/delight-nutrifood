<div class="row">
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">Ventas Abiertas</h4>

            <div class="">
                
                    @foreach ($ventas as $item)
                    <div class="alert alert-{{$item->productos->count()>0?'success':'dark' }}@isset($cuenta) {{$item->id==$cuenta->id?'solid':''}}@endisset  alert-dismissible alert-alt fade show">
                        @if($item->productos->count()==0)
                        <button type="button" class="btn-close" wire:click="eliminar('{{ $item->id }}')">
                        </button>
                        @endif
                        
                        <a href="#" wire:click="seleccionar('{{ $item->id }}')">
                            #{{$item->id}}@isset($item->cliente)<span class="badge badge-xs light badge-dark">{{Str::limit($item->cliente->name, 10)}}</span> @endisset  <strong>{{$item->total}} Bs</strong>
                        </a>
                    </div>
                      @endforeach
              
                <!-- checkbox -->
              
               
               
                <div x-data="{ count: 0 }">
                    <div x-data="{ open: false, count:'abrir' }">
                        <button  class="btn light btn-xs btn-outline-warning" @click="open = ! open, count='cerrar'">
                            <template x-if="open">
                            <div>CERRAR</div>
                        </template>
                        <template x-if="!open">
                            <div>ABRIR NUEVA CUENTA</div>
                        </template>
                    </button>
                     
                        <div x-show="open" > 
                                    <div class="row">
                                        <div class="mb-3 col-md-6 mt-2">
                                            <label class="form-label">Sucursal</label>
                                            <select class="form-control form-control-sm  form-white @error($sucursal) is-invalid @enderror" wire:model.lazy="sucursal">
                                                <option >Elija 1</option>
                                                @foreach ($sucursales as $nombre=>$id)
                                                <option value="{{$id}}">{{$nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-6 mt-2">
                                            <label class="form-label">Cliente</label>
                                            <input type="text" class="form-control  form-control-sm" placeholder="Opcional" wire:model.debounce.1000ms='user'>
                                        </div>
                                        <span class="badge light badge-info" wire:loading wire:target='user'> Cargando... </span>
                                        @foreach ($usuarios as $item)
                                    
                                   <a href="#" class="m-2" wire:click="seleccionarcliente('{{ $item->id }}','{{$item->name}}')"><span class="badge light badge-primary"> {{$item->name}} <i class="fa fa-check"></i></span></a> 
                                    @endforeach
                                    </div>
                                    <button type="button" wire:click="crear" class="btn btn-primary btn-sm">Crear Cuenta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card-col4>
    @isset($cuenta)
    <x-card-col4>
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <small class="m-3 text-muted" wire:loading.remove>Venta #{{$cuenta->id}}</small> <br>
            <small class="m-3 text-muted" wire:loading>Actualizando...</small>
            @if($cuenta->cliente)
            <a href="#" data-bs-toggle="modal" data-bs-target="#planesusuario"><span class="badge light badge-success">{{$cuenta->cliente->name}}</span></a>
            @else
            <span class="badge light badge-danger">Sin usuario</span>

            @endif
           
            
            <span class="badge badge-primary badge-pill">{{$itemsCuenta}}</span>
        </h4>
        <ul class="list-group mb-3">
           
           
                @foreach ($listacuenta as $item)
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <div class="row">
                            <div class="col-3"><img src="{{asset($item['foto'])}}" alt="" class="me-3 rounded" width="50"></div>
                            <div class="col"><a href="#" wire:click="mostraradicionales('{{ $item['id'] }}')"><h6 class="my-0"><small class="@isset($productoapuntado){{$item['nombre']==$productoapuntado->nombre?'text-success':''}} @endisset">{{$item['nombre']}}</small></h6></a></div>
                        </div>
                        
                        <small class="text-muted">
                            <div class="row">
                                <div class="col">
                                    <a href="#" wire:click="adicionar('{{ $item['id'] }}')"><span class="badge badge-xs light badge-success"><i class="fa fa-plus"></i></span></a> 
                                    <strong>{{$item['cantidad']}}</strong> {{$item['medicion']}}(s)
                                   <a href="#" wire:click="eliminaruno('{{ $item['id'] }}')"> <span class="badge badge-xs light badge-danger"><i class="fa fa-minus"></i></span></a>
                                    <a href="#" class="btn btn-danger shadow btn-xs sharp" wire:click="eliminarproducto('{{ $item['id'] }}')"><i class="fa fa-trash"></i></a>
                                </div> 
                            </div>
                         
                        </small>
                        @isset($productoapuntado)
                        @if($productoapuntado->id==$item['id'])
                        @foreach ($array as $lista)
                       <ul>
                           <li> <small class="badge badge-xs badge-warning">{{$loop->iteration}}</small>
                            @foreach ($lista as $posicion=>$adic)
                           
                                @foreach ($adic as $nombre=>$precio)
                                <small class="badge badge-xs light badge-warning">{{$nombre}} <label class="text-dark">{{$precio}}Bs</label></small>
                            @endforeach
                            
                           
                          
                           @endforeach</li>
                       </ul>
                       
                        
                           
                        @endforeach
                        @endif
                        @endisset
                       
                    </div>
                    <div>
                        <span class="text-muted row">{{$item['subtotal']}} Bs</span>
                        <div x-data="{ open: false }">
                            <button @click="open = ! open" class="badge badge-xs light badge-info">Añadir</button>
                         
                            <div x-show="open" @click.outside="open = false"> 
                                <div class="mb-3 col-md-2">
                                <input type="number" class="form-control" wire:model.lazy="cantidadespecifica" style="padding: 3px;height:30px;width:40px" value="{{$item['cantidad']}}">
                                </div>
                                <button class="btn btn-xxs light btn-warning" wire:click="adicionarvarios('{{ $item['id'] }}')"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                       
                    </div>
                    
                </li>
                @endforeach
                
          
            <li class="list-group-item d-flex justify-content-between">
                <small>Subtotal</small>
                <strong>{{$cuenta->total}} Bs</strong>
                
            </li>
            
            <li class="list-group-item d-flex justify-content-between">
                <small>Descuento</small>
               
                <div x-data="{ open: false }">
                    <button @click="open = ! open" class="badge badge-xs light badge-secondary">Editar</button>
                 
                    <div x-show="open" @click.outside="open = false"> 
                        <div class="mb-3 col-md-2">
                        <input type="number" class="form-control" wire:model.lazy="descuento" style="padding: 3px;height:30px;width:80px" value="{{$item['cantidad']}}">
                        </div>
                        <button class="btn btn-xxs btn-warning" wire:click="editardescuento"><i class="fa fa-check"></i>Guardar</button>
                    </div>
                </div>
                <strong>{{$cuenta->descuento}} Bs</strong>
            </li>
           
            <li class="list-group-item d-flex justify-content-between">
                <span>Total a pagar</span>
                <strong>{{$cuenta->total-$cuenta->descuento}} Bs/{{$cuenta->puntos}} pts</strong>
                
            </li>
        </ul>
        
        @isset($productoapuntado)
        
        <label>Adicionales para {{$productoapuntado->nombre}}({{$adicionales->count()}})</label>
        <div class="input-group">
            @foreach ($productoapuntado->ventas->where('id',$cuenta->id) as $agregados)
            @for ($i = 1; $i <= $agregados->pivot->cantidad; $i++)
            <a href="#" wire:click="seleccionaritem('{{ $i }}')"><i class="badge badge-rounded badge-outline-warning {{$itemseleccionado==$i?'badge-outline-dark':''}} m-2">{{ $i }}</i></a> 
            @endfor
            @endforeach
       
        
            @isset($itemseleccionado)
           
            <ul id="myList">
            @foreach ($adicionales as $item)
            <li><a href="#" wire:click="agregaradicional('{{ $item->id }}', '{{ $itemseleccionado }}')"><i class="badge badge-rounded badge-outline-primary m-2">{{$item->nombre}}</i></a> </li>  
             @endforeach
            </ul>
            @endisset
         
        </div>
        @endisset
        @if ($cuenta->total!=0)
        <div class="row m-2">
            <div x-data="{ open: false }">
                <button @click="open = ! open" class="badge badge-xs light badge-info">Cobrar</button>
             
                <div x-show="open" @click.outside="open = false"> 
                    <div class="row">
                        
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro" value="efectivo" checked="">
                                <label class="form-check-label">
                                    Efectivo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro" value="transferencia">
                                <label class="form-check-label">
                                    Transferencia
                                </label>
                            </div>
                            <div class="form-check disabled">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro" value="tarjeta" >
                                <label class="form-check-label">
                                    Tarjeta
                                </label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-xs light btn-warning" wire:click="cobrar">Cobrar Cuenta</button>
                </div>
            </div>
           </div>   
        @endif
       
            
    </x-card-col4>
   <x-card-col4>
        <div class="basic-list-group m-3">
            <ul class="list-group">
                <li class="list-group-item active"><input type="search" wire:model.debounce.750ms="search" class="form-control" placeholder="Busca Productos"></li>
                @foreach ($productos as $item)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{asset($item->pathAttachment())}}" alt="" class="me-3 rounded" width="50">

                        </div>
                        <div class="col">
                            {{$item->nombre}}
                             @if ($item->puntos!=0 && $item->puntos !=null)
                            ({{$item->puntos}}pts)
                            @endif
                        </div>
                           
                       
                       
                    </div>
                    <div class="row">
                        <div class="col">
                            @if ($item->descuento!=0)
                            <span class="badge badge-xs light badge-success">{{$item->descuento}} Bs</span>  
                            <del class="badge badge-xs light badge-danger">{{$item->precio}} Bs</del>  
                            @else
                            <span class="badge badge-xs light badge-warning">{{$item->precio}} Bs</span>
                            @endif
                            <button type="button" class="btn btn-rounded btn-primary btn-xxs pull-right" wire:click="adicionar('{{ $item->id }}')" ><span class="btn-icon-start text-primary btn-xxs" ><i class="fa fa-shopping-cart"></i>
                            </span>Añadir</button>
                            
                        </div>
                       
                    </div>
                   
               
                </li>
                
                @endforeach   
            </ul>
        </div>
    
   </x-card-col4>
   
    @endisset
   
  
    <!-- Modal -->
    <div class="modal fade" id="planesusuario">
        <div class="modal-dialog">
            <div class="modal-content">
                @isset($cuenta->cliente->planes)
                <div class="modal-header">
                    <h5 class="modal-title">Planes existentes ({{$cuenta->cliente->planes->count()}})</h5>
                    <a href="{{route('planes')}}" class="btn btn-xs btn-warning">Ir a todos los planes</a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                
                @foreach ($cuenta->cliente->planes as $item)
                <div class="card overflow-hidden bg-image-2 bg-secondary">
                   <div class="card-header  border-0">
                       <div>
                           <p class="mb-2 font-w100 text-white">Plan: {{$item->nombre}}  - Expira : {{$item->pivot->dia_limite}}
                           
                           
                           <h3 class="mb-0 fs-24 font-w600 text-white">Restante: {{$item->pivot->restante}}</h3> <button data-bs-dismiss="modal" wire:click="agregardesdeplan({{$item->pivot->user_id}},{{$item->pivot->plane_id}},{{$item->producto_id}})" class="btn light btn-xxs btn-success">Agregar al carro</button>
                       </div>
                   </div>
               </div>
               
                @endforeach  
                
               
                </div>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Cerrar</button>
                    
                </div>
                @endisset
            </div>
        </div>
    </div>

</div>
