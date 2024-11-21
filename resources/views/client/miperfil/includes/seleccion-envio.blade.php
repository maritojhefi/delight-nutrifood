<div class="row mb-0">
    <div class="col-6" id="envio{{ $lista['id'] }}">
        <i class="fa fa-map-marker font-16 color-red-dark"></i> <strong>Tipo de
            envio</strong>
        <div class="fac fac-radio fac-default mesa" data-group="envio{{ $lista['id'] }}"><span></span>
            <input id="box7-fac-radio{{ $lista['id'] }}" type="radio" data-id="{{ $lista['id'] }}" class="mesa"
                name="envio{{ $lista['id'] }}" value="{{ $lista['envio1'] }}">
            <label for="box7-fac-radio{{ $lista['id'] }}">{{ $lista['envio1'] }}</label>
        </div>
        <div class="fac fac-radio fac-default" data-group="envio{{ $lista['id'] }}"><span></span>
            <input id="box8-fac-radio{{ $lista['id'] }}" type="radio" data-id="{{ $lista['id'] }}" class="otro"
                name="envio{{ $lista['id'] }}" value="{{ $lista['envio2'] }}">
            <label for="box8-fac-radio{{ $lista['id'] }}">{{ $lista['envio2'] }}</label>
        </div>
        <div class="fac fac-radio fac-default" data-group="envio{{ $lista['id'] }}"><span></span>
            <input id="box9-fac-radio{{ $lista['id'] }}" type="radio" data-id="{{ $lista['id'] }}" class="otro"
                name="envio{{ $lista['id'] }}" value="{{ $lista['envio3'] }}">
            <label for="box9-fac-radio{{ $lista['id'] }}">{{ $lista['envio3'] }}</label>
        </div>

    </div>

    <div class="col-6 empaques d-none" id="empaque{{ $lista['id'] }}">
        <i class="fa fa-edit font-16 color-red-dark"></i> <strong>Tipo de
            empaque</strong>
        <div class="fac fac-radio fac-default" data-group="empaque{{ $lista['id'] }}"><span></span>
            <input id="box10-fac-radio{{ $lista['id'] }}" type="radio" name="empaque{{ $lista['id'] }}"
                value="{{ $lista['empaque1'] }}">
            <label for="box10-fac-radio{{ $lista['id'] }}">{{ $lista['empaque1'] }}</label>
        </div>
        <div class="fac fac-radio fac-default" data-group="empaque{{ $lista['id'] }}"><span></span>
            <input id="box11-fac-radio{{ $lista['id'] }}" type="radio" name="empaque{{ $lista['id'] }}"
                value="{{ $lista['empaque2'] }}">
            <label for="box11-fac-radio{{ $lista['id'] }}">{{ $lista['empaque2'] }}</label>
        </div>
        <div class="fac fac-radio fac-default d-none" data-group="empaque{{ $lista['id'] }}"><span></span>
            <input id="box12-fac-radio{{ $lista['id'] }}" type="radio" name="empaque{{ $lista['id'] }}"
                value="Ninguno">
            <label for="box12-fac-radio{{ $lista['id'] }}">Ninguno(si es para
                mesa)</label>
        </div>


    </div>
    <div class="box7-fac-radio{{ $lista['id'] }}" id="empaque{{ $lista['id'] }}"></div>
</div>
