{{-- <div class="row">
    @isset($prodlisto)
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Stocks Registrados para este producto</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group" style="overflow-y: auto;max-height:300px;overflow-x: hidden">
                        @foreach ($prodlisto->sucursale as  $item)
                        
                        <li class="mt-2 mb-2 list-group-item">{{ $item->nombre }} : {{ $item->pivot->cantidad }} <span class="badge badge-xs light badge-info">Expira {{date_format(date_create($item->pivot->fecha_venc),"d-M-y")}} </span> 
                            @if ($item->pivot->fecha_venc<Carbon\Carbon::now() && $item->pivot->cantidad>0)
                            <span class="badge badge-xs pill badge-danger">Expirado! </span>
                            @else
                            <span class="badge badge-xs pill badge-success">Vigente! {{Carbon\Carbon::parse($item->pivot->fecha_venc)->diffInDays()  }} dias restantes</span>
                            @endif
                            <a href="#" data-toggle="modal" data-target="#modalEliminar{{$item->pivot->id}}"><span class="fa fa-trash "></span></a>
                        </li>
                        <div wire:ignore.self class="modal fade"  aria-hidden="true" tabindex="-1" role="dialog" id="modalEliminar{{$item->pivot->id}}">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Estas seguro?</h5>
                                  <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                
                                <div class="modal-footer">
                                  <button type="button" wire:click="eliminarStock({{$item->pivot->id}})" data-dismiss="modal" aria-label="Close" class="btn btn-danger">Confirmar</button>
                                  
                                </div>
                              </div>
                            </div>
                          </div>
                    @endforeach
                    </ul>
                   
                </div>
            </div>
        </div>
    @endisset

    @empty($prodlisto)
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Seleccione Sucursal</h4>
                </div>
                <div class="card-body">
                    <select name="" wire:model="sucursal" class="form-control" id="">
                        <option value="">Elija una opcion</option>

                        @foreach ($sucursales as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endempty


    @isset($sucursal)
        <div class="col-xl-4">
            <div class="card">
               
                <div class="card-body">
                    <div class="basic-list-group">
                        <ul class="list-group">
                            <li class="list-group-item active"><input type="search" wire:model.debounce.750ms="search"
                                    class="form-control" placeholder="Busca Productos"></li>
                            @foreach ($productos as $item)
                                <a href="#" wire:click="seleccionar('{{ $item->id }}')">
                                    <li class="list-group-item">{{ Str::limit($item->nombre, 25) }}<br><strong
                                            class="">{{ $item->precio }} Bs</strong>

                                        <span class="badge light badge-success">
                                            Selec
                                        </span>
                                    </li>
                                </a>
                            @endforeach


                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endisset
    @isset($prodlisto)
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $prodlisto->nombre }}</h4>
                    stock:{{ $stock }} {{ $prodlisto->medicion }}(s)
                </div>
                <div class="card-body">
                    <div class="basic-list-group">
                        <x-input-create :lista="[
                            'Cantidad' => ['cantidad', 'number'],
                            'Fecha de vencimiento' => ['fecha_venc', 'date'],
                        ]">
                        </x-input-create>
                    </div>
                </div>
            </div>
        </div>
    @endisset
</div> --}}
