<div class="row">
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">Ventas Abiertas</h4>

            <div class="">
                
                    @foreach ($ventas as $item)
                    <div class="alert alert-{{$item->productos->count()>0?'success':'dark'}} alert-dismissible alert-alt fade show">
                        @if($item->productos->count()==0)
                        <button type="button" class="btn-close" wire:click="eliminar('{{ $item->id }}')">
                        </button>
                        @endif
                        
                        <a href="#" wire:click="seleccionar('{{ $item->id }}')">
                            #{{$item->id}} <span class="badge badge-xs light badge-dark">{{$item->sucursale->nombre}}</span>  <strong>{{$item->total}} Bs</strong>
                        </a>
                    </div>
                      @endforeach
              
                <!-- checkbox -->
              
                <a href="javascript:void()"  wire:click="crear" class="btn btn-primary btn-event w-100">
                    <span class="align-middle"><i class="fa fa-plus"></i></span> Crear Nueva
                </a>
                <div class="col-md-12">
                   
                    <select class="form-control mt-2 form-white @error($sucursal) is-invalid @enderror" wire:model="sucursal">
                        <option value="">Seleccione sucursal</option>
                        @foreach ($sucursales as $nombre=>$id)
                        <option value="{{$id}}">{{$nombre}}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
        </div>
    </x-card-col4>
    @isset($cuenta)
   <x-card-col4>
        <div class="basic-list-group m-3">
            <ul class="list-group">
                <li class="list-group-item active"><input type="search" wire:model.debounce.750ms="search" class="form-control" placeholder="Busca Productos"></li>
                @foreach ($productos as $item)
                <li class="list-group-item">
                    <div class="row">
                        {{$item->nombre}}
                    </div>
                    <div class="row">
                        <div class="col">
                            @if ($item->descuento!=0)
                            <span class="badge badge-xs light badge-success">{{$item->descuento}} Bs</span>  
                            <del class="badge badge-xs light badge-danger">{{$item->precio}} Bs</del>  
                            @else
                            <span class="badge badge-xs light badge-warning">{{$item->precio}} Bs</span>
                            @endif
                            <a href="#" wire:click="adicionar('{{ $item->id }}')">
                            <span class="badge badge-rounded badge-outline-success">
                            <span class="ms-1 fa fa-plus"></span>
                            </span></a>
                        </div>
                       
                    </div>
                   
               
                </li>
                
                @endforeach   
            </ul>
        </div>
    
   </x-card-col4>
    <x-card-col4>
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="m-3 text-muted" wire:loading.remove>Productos agregados (#{{$cuenta->id}})</span>
            <span class="m-3 text-muted" wire:loading>Actualizando...</span>
            <span class="badge badge-primary badge-pill">{{$itemsCuenta}}</span>
        </h4>
        <ul class="list-group mb-3">
           
           
                @foreach ($listacuenta as $item)
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">{{$item['nombre']}}</h6>
                        <small class="text-muted">
                          <a href="#" wire:click="adicionar('{{ $item['id'] }}')"><span class="badge badge-xs light badge-success"><i class="fa fa-plus"></i></span></a> 
                            <strong>{{$item['cantidad']}}</strong> 
                           <a href="#" wire:click="eliminaruno('{{ $item['id'] }}')"> <span class="badge badge-xs light badge-danger"><i class="fa fa-minus"></i></span></a>
                             ({{$item['precio']}}Bs c/u)

                            <a href="#" class="btn btn-danger shadow btn-xs sharp" wire:click="eliminarproducto('{{ $item['id'] }}')"><i class="fa fa-trash"></i></a>
                        </small>
                    </div>
                    <span class="text-muted">{{$item['subtotal']}} Bs</span>
                </li>
                @endforeach
                
          
            <li class="list-group-item d-flex justify-content-between">
                <span>Total</span>
                <strong>{{$cuenta->total}}</strong>
            </li>
        </ul>

   
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Codigo Promocional">
                <button type="submit" class="input-group-text">Agregar</button>
            </div>
    </x-card-col4>
    @endisset
    
</div>
