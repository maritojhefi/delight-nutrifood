<div class="col-12" id="plato{{ $lista['id'] }}">
    <div class="ms-n1 d-flex flex-row align-items-center gap-2">
        <!-- <div class="bg-highlight rounded rounded-circle d-flex align-items-center justify-content-center p-1" style="height: 1.5rem; width: 1.5rem">
            <i data-lucide="utensils" class="lucide-icon color-white" style="width: 1rem; height: 1rem;"></i>
        </div> -->
        <i data-lucide="utensils" class="lucide-icon"></i>
        <strong class="color-theme">Elija su plato</strong>
    </div>
    
    @php
        $platos = [
            [
                'key' => 'ejecutivo',
                'label' => 'Ejecutivo',
                'box_id' => 'box1-fac-radio',
            ],
            [
                'key' => 'dieta',
                'label' => 'Dieta',
                'box_id' => 'box2-fac-radio',
            ],
            [
                'key' => 'vegetariano',
                'label' => 'Veggie',
                'box_id' => 'box3-fac-radio',
            ],
        ];
    @endphp

    @foreach($platos as $plato)
        @php
            $estadoKey = $plato['key'] . '_estado';
            $carboKey = $plato['key'] . '_tiene_carbo';
        @endphp
        
        @if ($lista[$estadoKey])
            <div class="fac fac-radio fac-default" data-group="plato{{ $lista['id'] }}">
                <span></span>
                <input 
                    id="{{ $plato['box_id'] }}{{ $lista['id'] }}" 
                    type="radio" 
                    class="segundos"
                    name="plato{{ $lista['id'] }}"
                    data-id="{{ $lista['id'] }}" 
                    value="{{ $lista[$plato['key']] }}"
                    data-carbo="{{ $lista[$carboKey] }}"
                    @if (!$lista[$estadoKey]) disabled @endif
                >
                <label for="{{ $plato['box_id'] }}{{ $lista['id'] }}"
                    class="d-flex flex-row gap-1 align-items-center"
                >
                    <mark class="highlight px-2 line-height-xs font-10 bg-magenta-dark rounded-m">
                        {{ $plato['label'] }}
                    </mark>
                    @if ($lista[$estadoKey])
                        {{ $lista[$plato['key']] }} 
                        <i data-lucide="check" class="lucide-icon color-teal-dark"></i>
                    @else
                        <del>{{ $lista[$plato['key']] }}</del> 
                        <i data-lucide="x" class="lucide-icon color-delight-red"></i>
                    @endif
                </label>
            </div>
        @endif
    @endforeach
</div>