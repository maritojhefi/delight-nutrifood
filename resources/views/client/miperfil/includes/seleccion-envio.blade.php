<div class="row mb-0">
    <div class="col-6 px-0" id="envio{{ $lista['id'] }}">
        <div class="ms-n1 d-flex flex-row align-items-center gap-2 color-theme">
            <i data-lucide="map-pin" class="lucide-icon"></i>
            <strong>Tipo de envío</strong>
        </div>

        @php
            $tiposEnvio = [
                ['box_id' => 'box7-fac-radio', 'value' => $lista['envio1'], 'class' => 'mesa'],
                ['box_id' => 'box8-fac-radio', 'value' => $lista['envio2'], 'class' => 'otro'],
                ['box_id' => 'box9-fac-radio', 'value' => $lista['envio3'], 'class' => 'otro'],
            ];
        @endphp

        @foreach($tiposEnvio as $envio)
            <div class="fac fac-radio fac-default {{ $envio['class'] === 'mesa' ? 'mesa' : '' }}" data-group="envio{{ $lista['id'] }}">
                <span></span>
                <input 
                    id="{{ $envio['box_id'] }}{{ $lista['id'] }}" 
                    type="radio" 
                    data-id="{{ $lista['id'] }}" 
                    class="{{ $envio['class'] }}"
                    name="envio{{ $lista['id'] }}" 
                    value="{{ $envio['value'] }}"
                >
                <label for="{{ $envio['box_id'] }}{{ $lista['id'] }}">
                    <p class="mb-0 line-height-s">
                        {{ $envio['value'] }}
                    </p>
                </label>
            </div>
        @endforeach
    </div>

    <div class="col-6 px-0 empaques d-none" id="empaque{{ $lista['id'] }}">
        <div class="ms-n2 d-flex flex-row align-items-center gap-1 color-theme">
            <i data-lucide="package" class="lucide-icon"></i>
            <strong class="line-height-s">Tipo de empaque</strong>
        </div>

        @php
            $tiposEmpaque = [
                ['box_id' => 'box10-fac-radio', 'value' => $lista['empaque1'], 'hidden' => false],
                ['box_id' => 'box11-fac-radio', 'value' => $lista['empaque2'], 'hidden' => false],
                ['box_id' => 'box12-fac-radio', 'value' => 'Ninguno', 'label' => 'Ninguno(si es para mesa)', 'hidden' => true],
            ];
        @endphp

        @foreach($tiposEmpaque as $empaque)
            <div class="fac fac-radio fac-default {{ $empaque['hidden'] ? 'd-none' : '' }}" data-group="empaque{{ $lista['id'] }}">
                <span></span>
                <input 
                    id="{{ $empaque['box_id'] }}{{ $lista['id'] }}" 
                    type="radio" 
                    name="empaque{{ $lista['id'] }}"
                    value="{{ $empaque['value'] }}"
                >
                <label for="{{ $empaque['box_id'] }}{{ $lista['id'] }}">
                    <p class="mb-0 line-height-s">
                        {{ $empaque['label'] ?? $empaque['value'] }}
                    </p>    
                </label>
            </div>
        @endforeach
    </div>
    <div class="box7-fac-radio{{ $lista['id'] }}" id="empaque{{ $lista['id'] }}"></div>
</div>