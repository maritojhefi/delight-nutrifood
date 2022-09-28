<div>
    <div class="row">
        @foreach ($ventas as $item)
            <div class="col-sm-4 col-4">
                <div class="card active_users">
                    <div class="card-header bg-primary ">
                        <h4 class="card-title text-white">#{{ $item->id }} <i class="fa fa-list"></i></h4>
                        <span id="counter"></span>
                    </div>
                    <div class="card-body pt-0">
                        <div class="list-group-flush mt-4">
                            @foreach ($item->productos as $prod)
                                @if ($prod->subcategoria->categoria->nombre != 'ECO-TIENDA')
                                    <div class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1">
                                        @if ($prod->pivot->estado_actual=="pendiente")
                                        <a href="#" wire:click="cambiarEstado('{{$prod->pivot->id}}','{{$prod->pivot->estado_actual}}')"><p class="mb-0">{{ Str::limit($prod->nombre, 20) }}</p></a>
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
