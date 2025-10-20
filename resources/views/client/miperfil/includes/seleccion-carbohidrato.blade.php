<div class="col-12" id="carb{{ $lista['id'] }}">
    <div class="ms-n1 d-flex flex-row align-items-center gap-2">
        <i data-lucide="sprout" class="lucide-icon"></i>
        <strong class="color-theme">Elija su carbohidrato</strong>
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
                        {{ $lista[$carbo['key']] }} 
                        <i data-lucide="check" class="lucide-icon color-teal-dark"></i>
                    @else
                        <del>{{ $lista[$carbo['key']] }}</del> 
                        <i data-lucide="x" class="lucide-icon color-delight-red"></i>                        
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
            Sin carbohidrato
        </label>
    </div>
</div>