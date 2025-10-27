<div class="col-12 mb-1" id="carb{{ $lista['id'] }}">
    <i class="fa fa-star color-yellow-dark"></i> <strong>Elija su
        carbohidrato</strong>
    @if ($lista['carbohidrato_1_estado'])
        <div class="fac fac-radio fac-default carbos{{ $lista['id'] }}" data-group="carb{{ $lista['id'] }}">
            <span></span>
            <input id="box4-fac-radio{{ $lista['id'] }}" type="radio" @if (!$lista['carbohidrato_1_estado']) disabled @endif
                name="carb{{ $lista['id'] }}" value="{{ $lista['carbohidrato_1'] }}">
            <label for="box4-fac-radio{{ $lista['id'] }}">
                @if ($lista['carbohidrato_1_estado'])
                    {{ $lista['carbohidrato_1'] }} <i class="fa fa-check color-green-dark"></i>
                @else
                    <del>{{ $lista['carbohidrato_1'] }}</del> <i class="fa fa-ban color-red-dark"></i>
                @endif

            </label>
        </div>
    @endif
    @if ($lista['carbohidrato_2_estado'])
        <div class="fac fac-radio fac-default carbos{{ $lista['id'] }}" data-group="carb{{ $lista['id'] }}">
            <span></span>
            <input id="box5-fac-radio{{ $lista['id'] }}" type="radio"
                @if (!$lista['carbohidrato_2_estado']) disabled @endif name="carb{{ $lista['id'] }}"
                value="{{ $lista['carbohidrato_2'] }}">
            <label for="box5-fac-radio{{ $lista['id'] }}">
                @if ($lista['carbohidrato_2_estado'])
                    {{ $lista['carbohidrato_2'] }} <i class="fa fa-check color-green-dark"></i>
                @else
                    <del>{{ $lista['carbohidrato_2'] }}</del> <i class="fa fa-ban color-red-dark"></i>
                @endif
            </label>
        </div>
    @endif
    @if ($lista['carbohidrato_3_estado'])
        <div class="fac fac-radio fac-default carbos{{ $lista['id'] }}" data-group="carb{{ $lista['id'] }}">
            <span></span>
            <input id="box6-fac-radio{{ $lista['id'] }}" type="radio"
                @if (!$lista['carbohidrato_3_estado']) disabled @endif name="carb{{ $lista['id'] }}"
                value="{{ $lista['carbohidrato_3'] }}">
            <label for="box6-fac-radio{{ $lista['id'] }}">
                @if ($lista['carbohidrato_3_estado'])
                    {{ $lista['carbohidrato_3'] }} <i class="fa fa-check color-green-dark"></i>
                @else
                    <del>{{ $lista['carbohidrato_3'] }}</del> <i class="fa fa-ban color-red-dark"></i>
                @endif
            </label>
        </div>
    @endif

    <div class="fac fac-radio fac-default" data-group="carb{{ $lista['id'] }}"><span></span>
        <input id="box13-fac-radio{{ $lista['id'] }}" type="radio" name="carb{{ $lista['id'] }}"
            value="sin carbohidrato">
        <label for="box13-fac-radio{{ $lista['id'] }}">Sin
            carbohidrato</label>
    </div>

</div>