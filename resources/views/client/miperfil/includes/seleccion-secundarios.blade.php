<div class="row mb-0">

    @php
        $labels = [
            'sopa' => 'Sopa',
            'ensalada' => 'Ensalada',
            'jugo' => 'Jugo'
        ];
    @endphp

    @foreach($labels as $key => $label)
        @if ($plan->$key)
            <div class="col-12 px-0 d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex flex-row gap-1 align-items-center">
                    <strong class="color-theme">{{ $label }}</strong>
                    <i data-lucide="check" class="lucide-icon color-teal-dark"></i>
                </div>
                <p class="text-end font-400 mb-0">
                    {{ ucfirst($lista[$key]) }}
                </p>
                <input type="hidden" value="{{ $lista[$key] }}" name="{{ $key }}">
            </div>
        @endif
    @endforeach

    <input type="hidden" value="{{ $lista['dia'] }}" name="dia">
    <input type="hidden" value="{{ $lista['id'] }}" name="id">
    <input type="hidden" value="{{ $plan->id }}" name="plan">


    <div class="col my-2">
        <div class="d-flex flex-row justify-content-evenly align-items-center w-100 px-0 gap-3">
            @if ($lista['estado'] == 'pendiente')
                <button type="button" 
                    class="btn btn-s rounded-s w-auto text-uppercase font-600 shadow-s bg-delight-red permiso-pedido-btn">
                    <span class="text-white">Permiso</span>
                </button>
            @endif
            <!-- <button type="submit" disabled
                class="btn btn-s rounded-s w-auto text-uppercase font-600 shadow-s bg-teal-dark bg-dtheme-blue">
                <span class="text-white">Guardar {{ $lista['dia'] }}</span>
            </button> -->
            <button type="submit" disabled
                class="btn btn-s rounded-s w-auto text-uppercase font-600 shadow-s bg-teal-dark">
                <span class="text-white">Guardar Pedido</span>
            </button>
        </div>
    </div>
</div>