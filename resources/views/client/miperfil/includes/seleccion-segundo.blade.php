<div class="col-12 mb-1" id="plato{{ $lista['id'] }}">
    <i class="fa fa-star color-yellow-dark"></i> <strong>Elija su
        plato</strong>
    @if ($lista['ejecutivo_estado'])
        <div class="fac fac-radio fac-default" data-group="plato{{ $lista['id'] }}"><span></span>
            <input id="box1-fac-radio{{ $lista['id'] }}" type="radio" class="segundos"
                @if (!$lista['ejecutivo_estado']) disabled @endif name="plato{{ $lista['id'] }}"
                data-id="{{ $lista['id'] }}" value="{{ $lista['ejecutivo'] }}"
                data-carbo="{{ $lista['ejecutivo_tiene_carbo'] }}">
            <label for="box1-fac-radio{{ $lista['id'] }}"><mark
                    class="highlight ps-1 font-10 pe-1 bg-magenta-dark mr-2 rounded-m">Ejecutivo</mark>
                @if ($lista['ejecutivo_estado'])
                    {{ $lista['ejecutivo'] }} <i class="fa fa-check color-green-dark"></i>
                @else
                    <del>{{ $lista['ejecutivo'] }}</del> <i class="fa fa-ban color-red-dark"></i>
                @endif

            </label>
        </div>
    @endif
    @if ($lista['dieta_estado'])
    <div class="fac fac-radio fac-default" data-group="plato{{ $lista['id'] }}"><span></span>
        <input id="box2-fac-radio{{ $lista['id'] }}" type="radio" class="segundos" data-id="{{ $lista['id'] }}"
            @if (!$lista['dieta_estado']) disabled @endif name="plato{{ $lista['id'] }}"
            value="{{ $lista['dieta'] }}" data-carbo="{{ $lista['dieta_tiene_carbo'] }}">
        <label for="box2-fac-radio{{ $lista['id'] }}"><mark
                class="highlight ps-1 font-10 pe-1 bg-magenta-dark mr-2 rounded-m">Dieta</mark>
            @if ($lista['dieta_estado'])
                {{ $lista['dieta'] }} <i class="fa fa-check color-green-dark"></i>
            @else
                <del>{{ $lista['dieta'] }}</del> <i class="fa fa-ban color-red-dark"></i>
            @endif
        </label>
    </div>
    @endif
    @if ($lista['vegetariano_estado'])
    <div class="fac fac-radio fac-default" data-group="plato{{ $lista['id'] }}"><span></span>
        <input id="box3-fac-radio{{ $lista['id'] }}" type="radio" class="segundos" data-id="{{ $lista['id'] }}"
            @if (!$lista['vegetariano_estado']) disabled @endif name="plato{{ $lista['id'] }}"
            value="{{ $lista['vegetariano'] }}" data-carbo="{{ $lista['vegetariano_tiene_carbo'] }}">
        <label for="box3-fac-radio{{ $lista['id'] }}"><mark
                class="highlight ps-1 font-10 pe-1 bg-magenta-dark mr-2 rounded-m">Veggie</mark>
            @if ($lista['vegetariano_estado'])
                {{ $lista['vegetariano'] }} <i class="fa fa-check color-green-dark"></i>
            @else
                <del>{{ $lista['vegetariano'] }}</del> <i class="fa fa-ban color-red-dark"></i>
            @endif
        </label>
    </div>
    @endif

</div>
