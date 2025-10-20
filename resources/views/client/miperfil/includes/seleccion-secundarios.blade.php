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
                    <!-- <h4 class="font-900 font-13 mb-0"> -->
                        <strong class="color-theme">{{ $label }}</strong>
                    <!-- </h4> -->
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
        <div class="d-flex flex-row justify-content-center align-items-center w-100 px-0 gap-2">
            <!-- <button type="button" disabled 
                class="btn btn-m rounded-m w-50 text-uppercase font-900 shadow-s bg-gray-light">Permiso</button> -->
            <button type="submit" disabled
                class="btn btn-m rounded-m w-100 text-uppercase font-900 shadow-s bg-teal-dark bg-dtheme-blue">
                <span class="text-white">Guardar {{ $lista['dia'] }}</span>
            </button>
        </div>
    </div>
</div>
