<div class="col-12">
    <div class="row">
        @foreach ($ventas as $item)
            <div class="col-sm-3 col-3">
                <div class="card active_users bordeado">
                    <div class="card-header bg-primary p-1 m-0 ">
                        <h4 class="card-title text-white letra10 text-white">Pedido #{{ $item->id }} </h4>
                        <strong class="text-white letra12">{{$item->cliente? Str::limit($item->cliente->name,20):'Desconocido'}}</strong>
                    </div>
                    <div class="card-body p-2 m-0 letra12">
                        <div class="list-group-flush">
                            @foreach ($item->productos as $prod)
                                @if ($prod->subcategoria->categoria->nombre != 'ECO-TIENDA')
                                    <div class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1">
                                        @if ($prod->pivot->estado_actual=="pendiente")
                                        <a href="#" wire:click="cambiarEstado('{{$prod->pivot->id}}','{{$prod->pivot->estado_actual}}')"><p class="mb-0">{{ Str::limit($prod->nombre, 30) }}</p></a>
                                        @else
                                        <a href="#" wire:click="cambiarEstado('{{$prod->pivot->id}}','{{$prod->pivot->estado_actual}}')"><i class="fa fa-check text-success"></i><del class="mb-0 text-success">{{ Str::limit($prod->nombre, 20) }}</del></a>
                                        @endif
                                        
                                        <p class="mb-0"><strong>{{ $prod->pivot->cantidad }}</strong></p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
        <script>
            Livewire.on('notificacionCocina', respuesta => {
                var audio = new Audio('/tonococina.mp3');
                audio.play();
                console.log('play');
            })
        </script>
    @endpush
</div>
