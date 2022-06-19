<div class="col-xl-12 col-xxl-12">
    <div class="row">
        <div class="col-12">
            <button data-toggle="modal" class="btn btn-warning btn-block" data-target="#modelId">Cambiar Usuario <small>(Actual:{{$usuario->name}})</small></button>
        </div>
        
    </div>

    
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buscar Usuario</h5>
                        <a type="button" href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" wire:model.debounce.500ms="search">
                    <ul class="m-3">
                        @foreach ($usuarios as $user)
                        @if ($usuario->id!=$user->id)
                        <li>
                            <a href="{{route('detalleplan',[$user->id,$plan->id])}}" class="btn btn-xxs btn-block btn-floating light btn-warning">{{$user->name}}</a> 
                         </li>
                        @endif
                        
                        @endforeach
                        @if ($search!=null || $search!='')
                        
                        <span class="badge light badge-success">Se encontraron {{$usuarios->count()}} usuarios</span>
                        @endif
                        
                    </ul>
                   
                </div>
               
            </div>
        </div>
    </div>
</div>
