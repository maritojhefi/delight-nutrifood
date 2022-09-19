<div>
    <div class="row">
        @foreach ($ventas as $item)
            <div class="col-sm-6 col-4">
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
                                        <p class="mb-0">{{ Str::limit($prod->nombre, 20) }}</p>
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
