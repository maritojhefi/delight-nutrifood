<div class="row">
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">Usuarios</h4>
            <input type="text" class="form-control  form-control-sm" placeholder="Busque un usuario"
                wire:model.debounce.1000ms='user'>

            <span class="badge light badge-info" wire:loading wire:target='user'> Cargando... </span>
            @foreach ($usuarios as $item)
            <a href="#" wire:click="seleccionar('{{ $item['id'] }}')"><span
                    class="badge light badge-primary mt-2">{{$item->name}}</span></a>
            @endforeach
        </div>
    </x-card-col4>
    @empty($seleccionado)
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">Usuarios recientes</h4>
            <ul class="list-group mb-3">

               
            @foreach ($recientes as $item)
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                 <a href="#" wire:click="seleccionar('{{ $item['id'] }}')"><span
                    class="badge light badge-primary mt-2">{{$item->name}}</span></a>
            </li>
            @endforeach

            
        </div>
    </x-card-col4>
    @endempty
    @isset($seleccionado)
    <x-card-col4>
        <div class="card-header">
            Planes para {{$seleccionado->name}} ({{$seleccionado->planes->count()}})

        </div>
        <div class="card-body">
            <ul class="index-chart-point-list">
                @foreach ($lista as $item)
                <div class="row align-items-center mt-2">
                    <ul class="list-group mb-3">

                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <a href="{{route('detalleplan',[$seleccionado->id,$item['id']])}}">
                                    <button class="btn btn-primary light btn-lg">{{$item['plan']}}</button>
                                </a><br>
                                <small class="my-0">Cantidad restante:</small>
                                <small class="text-muted">{{$item['cantidad']}}</small><br>
                                <button class="btn btn-danger btn-xs" data-bs-toggle="modal"
                                    data-bs-target="#modal{{$item['id']}}">Eliminar plan</button>
                            </div>


                        </li>



                    </ul>
                </div>
                <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true"
                    id="modal{{$item['id']}}">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Esta seguro?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">Se borraran todos los registros del plan: {{$item['plan']}}</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" wire:click="eliminar('{{ $item['id']}}')"
                                    data-bs-dismiss="modal">Confirmar</button>
                            </div>
                        </div>
                    </div>
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
                <a href="#" wire:click="agregar('{{$item->id}}')">
                    <li><span class="badge light badge-warning">{{$item->nombre}} <i class="fa fa-check"></i></span>
                    </li>
                </a>
                @endforeach
            </ul>

        </div>
    </x-card-col4>
    @endisset
</div>
