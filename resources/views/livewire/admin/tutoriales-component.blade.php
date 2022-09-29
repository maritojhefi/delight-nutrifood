<div class="row">
    @empty($tipo)
    <div class="col-xl-4 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Elige el tipo de video</h4>
                </div>
                <div class="card-body">
                    <button class="btn btn-danger m-2" wire:click="elegirTipo('youtube')"><i class="fa fa-youtube"></i> Youtube</button>
                    <button class="btn btn-dark m-2" wire:click="elegirTipo('tiktok')"><i class="fa fa-tik-tok"></i> Tik Tok</button>
                    <button class="btn btn-info m-2" wire:click="elegirTipo('facebook')"><i class="fa fa-facebook"></i> Facebook</button>
                </div>
            </div>
        </div>
    </div>
    @endempty
    
    @isset($tipo)
    <div class="col-xl-4 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nuevo video desde {{$tipo}}</h4>
                   <a href="#" wire:click="resetTipo"><small class="badge badge-warning">Elegir otro</small></a>
                </div>
                @if ($tipo!='youtube')
                <div class="card-body">
                    <div class="">
                        <x-input-create-defer :lista="[                          
                            'Codigo' => ['url', 'textarea'], 
                        ]">
                        </x-input-create-defer>

                    </div>
                </div>
                @else
                <div class="card-body">
                    <div class="">
                        <x-input-create-defer :lista="[
                            'Titulo' => ['titulo', 'text'],
                            'Url' => ['url', 'text'],
                            'Descripcion' => ['descripcion', 'textarea'],
                            
                        ]">
                        </x-input-create-defer>

                    </div>
                </div>
                @endif
                
            </div>


        </div>
    </div>
    @endisset
 
    <div class="col-xl-8 col-lg-12 col-xxl-7 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title">Todas</h4>
                        </div>
                        <div class="col-8"><input type="text" class=" form-control" placeholder="Buscar"
                                wire:model.debounce.500ms="search"></div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Titulo</strong></th>
                                    <th><strong>Descripcion</strong></th>
                                    <th><strong>Url</strong></th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tutoriales as $item)
                                <tr>
                                    <td>{{$item->titulo}}</td>
                                    <td>{{Str::limit($item->descripcion,30)}}</td>
                                    <td>{{$item->url}}</td>
                                    <td><a href="#" wire:click="delete({{$item->id}})" class="badge badge-danger"><i class="fa fa-trash"></i></a></td>
                                </tr>
                                @endforeach
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">{{ $tutoriales->links() }}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{ $tutoriales->count() }} de {{ $tutoriales->total() }}
                        registros</div>
                </div>
            </div>

        </div>
    </div>
    
</div>
