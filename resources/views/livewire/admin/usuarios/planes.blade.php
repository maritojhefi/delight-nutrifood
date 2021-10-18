

<div class="row">
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">Usuarios</h4>
                    <input type="text" class="form-control  form-control-sm" placeholder="Busque un usuario" wire:model.debounce.1000ms='user'>
                                      
                    <span class="badge light badge-info" wire:loading wire:target='user'> Cargando... </span>
                       @foreach ($usuarios as $item)
                      <a href="#" wire:click="seleccionar('{{ $item['id'] }}')"><span class="badge light badge-primary mt-2" >{{$item->name}}</span></a> 
                       @endforeach               
        </div>
    </x-card-col4>
    @isset($seleccionado)
        <x-card-col4>
            <div class="card-header">
                Planes para {{$seleccionado->name}} ({{$seleccionado->planes->count()}})
                
            </div>
            <div class="card-body">
                <ul class="index-chart-point-list">
                    @foreach ($seleccionado->planes as $item)
                    <div class="row align-items-center mt-2">
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Cantidad restante:</h6>
                                    <small class="text-muted">{{$item->nombre}}</small><br>
                                    <small class="text-muted">Expira:{{DateTime::createFromFormat('Y-m-d',$item->pivot->dia_limite)->format('d-M-Y')}}</small>
                                    <a href="javascript:void(0);" wire:click="eliminar('{{$item->id}}')" class="btn btn-xs btn-danger btn-xxs px-4">Borrar</a>
                                    <a href="#" wire:click="adicionar('{{$item->id}}')" class="badge  light badge-success"><i class="fa fa-angle-double-left"></i></a>
                                    <a href="#" wire:click="restar('{{$item->id}}')" class="badge  light badge-danger"><i class="fa fa-angle-double-right"></i></a>

                                </div>
                                <span class="text-muted">{{$item->pivot->restante}}</span>

                            </li>
                           
                        </ul>
                    </div>
                  
                   
                    @endforeach
                 
                </ul>
            </div>
        </x-card-col4>
        <x-card-col4>
            <div class="card-header">
                Planes disponibles
                <a href="{{route('crear.plan')}}" class="btn btn-success btn-xs">Crear Nuevo</a>
            </div>
            <div class="card-body">
                <ul>
                    @foreach ($planes as $item)
                   <a href="#" wire:click="agregar('{{$item->id}}')"><li><span class="badge light badge-warning">{{$item->nombre}} <i class="fa fa-check"></i></span></li></a> 
                    @endforeach
                </ul>
              
            </div>
        </x-card-col4>
    @endisset
</div>
