<div class="col-12 px-0" id="plato{{ $lista['id'] }}">
    <div class="d-flex flex-row align-items-center gap-1">
        <!-- <i data-lucide="utensils" class="lucide-icon"></i> -->
        <i class="fa fa-star color-yellow-dark"></i> 
        <strong>Elija su plato</strong>
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
                    class="d-flex flex-row gap-1 align-items-center me-0"
                >
                    <!-- <mark class="highlight px-1 line-height-xs font-10 bg-highlight bg-dtheme-blue color-white rounded-m">
                        {{ $plato['label'] }}
                    </mark> -->
                    <mark class="highlight px-1 line-height-xs font-10 bg-magenta-dark color-white rounded-m">
                        {{ $plato['label'] }}
                    </mark>
                    @if ($lista[$estadoKey])
                        <p class="mb-0 color-theme">{{ $lista[$plato['key']] }} </p>
                        <!-- <i data-lucide="check" class="lucide-icon color-teal-dark" style="width: 1.1rem; height: 1.1rem;"></i> -->
                        <i class="fa fa-check color-green-dark"></i>
                    @else
                        <del class="color-theme">{{ $lista[$plato['key']] }}</del> 
                        <!-- <i data-lucide="x" class="lucide-icon color-delight-red" style="width: 1.1rem; height: 1.1rem;"></i> -->
                        <i class="fa fa-ban color-red-dark"></i>
                    @endif
                </label>
            </div>
        @endif
    @endforeach
</div>