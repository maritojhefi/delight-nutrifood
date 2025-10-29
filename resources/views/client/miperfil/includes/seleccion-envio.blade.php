<div class="row px-2 mb-0">
    <div class="col-6 px-0" id="envio{{ $lista['id'] }}">
        <div class="d-flex flex-row align-items-center gap-1">
            <i class="fa fa-map-marker font-16 color-red-dark" style="margin-left: 0.1rem;"></i> 
            <!-- <i data-lucide="map-pin" class="lucide-icon"></i> -->
            <strong class=" ">Tipo de envío</strong>
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
                <label for="{{ $envio['box_id'] }}{{ $lista['id'] }}" class=" color-theme">
                    {{ $envio['value'] }}
                </label>
            </div>
        @endforeach
    </div>

    <div class="col-6 px-0 empaques d-none" id="empaque{{ $lista['id'] }}">
        <div class="d-flex flex-row align-items-center gap-1 overflow-visible">
            <!-- <i data-lucide="package" class="lucide-icon"></i> -->
            <i class="fa fa-edit font-16 color-red-dark"></i>
            <strong class=" text-nowrap">Tipo de empaque</strong>
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
                <label for="{{ $empaque['box_id'] }}{{ $lista['id'] }}" class=" color-theme">
                    {{ $empaque['label'] ?? $empaque['value'] }}
                </label>
            </div>
        @endforeach
    </div>
    <div class="box7-fac-radio{{ $lista['id'] }}" id="empaque{{ $lista['id'] }}"></div>
</div>