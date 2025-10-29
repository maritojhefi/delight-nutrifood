<div class="col-12 px-0" id="carb{{ $lista['id'] }}">
    <div class="d-flex flex-row align-items-center gap-1">
        <!-- <i data-lucide="sprout" class="lucide-icon"></i> -->
        <i class="fa fa-star color-yellow-dark"></i>
        <strong>Elija su carbohidrato</strong>
    </div>
    
    @php
        $carbohidratos = [
            ['key' => 'carbohidrato_1', 'box_id' => 'box4-fac-radio'],
            ['key' => 'carbohidrato_2', 'box_id' => 'box5-fac-radio'],
            ['key' => 'carbohidrato_3', 'box_id' => 'box6-fac-radio'],
        ];
    @endphp

    @foreach($carbohidratos as $carbo)
        @php
            $estadoKey = $carbo['key'] . '_estado';
        @endphp
        
        @if ($lista[$estadoKey])
            <div class="fac fac-radio fac-default carbos{{ $lista['id'] }}" data-group="carb{{ $lista['id'] }}">
                <span></span>
                <input 
                    id="{{ $carbo['box_id'] }}{{ $lista['id'] }}" 
                    type="radio"
                    name="carb{{ $lista['id'] }}" 
                    value="{{ $lista[$carbo['key']] }}"
                    @if (!$lista[$estadoKey]) disabled @endif
                >
                <label for="{{ $carbo['box_id'] }}{{ $lista['id'] }}"
                    class="d-flex flex-row gap-1 align-items-center"
                >
                    @if ($lista[$estadoKey])
                        <p class="mb-0 color-theme">{{ $lista[$carbo['key']] }}</p>
                        <!-- <i data-lucide="check" class="lucide-icon color-teal-dark" style="width: 1.1rem; height: 1.1rem;"></i> -->
                        <i class="fa fa-check color-green-dark"></i>
                    @else
                        <del class="color-theme">{{ $lista[$carbo['key']] }}</del> 
                        <!-- <i data-lucide="x" class="lucide-icon color-delight-red" style="width: 1.1rem; height: 1.1rem;"></i> -->
                        <i class="fa fa-ban color-red-dark"></i>
                    @endif
                </label>
            </div>
        @endif
    @endforeach

    <div class="fac fac-radio fac-default" data-group="carb{{ $lista['id'] }}">
        <span></span>
        <input 
            id="box13-fac-radio{{ $lista['id'] }}" 
            type="radio" 
            name="carb{{ $lista['id'] }}"
            value="sin carbohidrato"
        >
        <label for="box13-fac-radio{{ $lista['id'] }}">
            <p class="mb-0 color-theme">
                Sin carbohidrato
            </p>
        </label>
    </div>
</div>