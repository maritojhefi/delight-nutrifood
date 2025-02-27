<hr>
<span class="text-info text-center d-block mx-auto letra12">Seleccione los métodos de pago: </span>

<div class="m-2">
    @foreach ($metodosPagos as $metodo)
        <div class="row letra12">
            <div class="col-2"></div>
            <div class="col-4">
                <div class="form-check">
                    <input class="form-check-input checkbox-focus" type="checkbox" name="gridRadios"
                        id="check-{{ $metodo->codigo }}" data-id="{{ $metodo->codigo }}"
                        wire:model="metodosSeleccionados.{{ $metodo->codigo }}.activo">
                    <label class="form-check-label" for="check-{{ $metodo->codigo }}">
                        <img src="{{ $metodo->imagen }}" style="height: 25px;width:25px" class="rounded-circle"
                            alt="">
                        {{ $metodo->nombre_metodo_pago }}
                    </label>
                </div>
            </div>
            @if (isset($metodosSeleccionados[$metodo->codigo]['activo']) && $metodosSeleccionados[$metodo->codigo]['activo'])
                <div class="col-4">
                    <input type="number" wire:model.debounce.750ms="metodosSeleccionados.{{ $metodo->codigo }}.valor"
                        id="input-{{ $metodo->codigo }}" class="form-control form-control-sm bordeado m-0 py-0 px-2"
                        style="height: 20px;width:60px" step="any" placeholder="Bs">
                </div>
            @endif
        </div>
    @endforeach
    @if (isset($cuenta->cliente) && $cuenta->cliente->saldo < 0)
        <div class="row letra12">
            <div class="col-2"></div>
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input checkbox-focus color-primary" type="checkbox" id="check-saldo-sobrante"
                        data-id="saldo-sobrante" wire:model="saldoSobranteCheck">
                    <label class="form-check-label text-primary" for="check-saldo-sobrante">
                        <i class="fa fa-user"></i>
                        A favor de {{ Str::words($cuenta->cliente->name, 1, '') }} :
                        <strong>{{ abs((int) $cuenta->cliente->saldo) }} Bs</strong>
                    </label>
                </div>
            </div>
            @if ($saldoSobranteCheck)
                <div class="col-4">
                    <input type="number" max="{{ $maxDescuentoSaldo }}" wire:model.debounce.750ms="descuentoSaldo"
                        id="input-saldo-sobrante" class="form-control form-control-sm bordeado m-0 py-0 px-2"
                        style="height: 20px;width:60px" step="any" placeholder="Bs">
                </div>
            @endif

        </div>
    @endif
    <div class=" letra12 p-1 px-2 mt-3 ">
        <table class="table table-responsive">
            <thead class="m-0 px-1 py-0">
                <th class="m-0 px-1 py-0">
                    <strong>Metodos seleccionados:
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </strong>
                </th>
                <th class="m-0 px-1 py-0"><strong>Monto</strong></th>
            </thead>
            <tbody class="m-0 px-1 py-0">
                @php
                    $totalAcumulado = 0;
                @endphp
                @foreach ($metodosSeleccionados as $codigo => $metodoSelec)
                    @if ($metodoSelec['activo'] == true && isset($metodoSelec['valor']))
                        <tr class="m-0 px-1 py-0">
                            <td class="m-0 px-1 py-0">{{ $codigo }}</td>
                            <td class="m-0 px-1 py-0">{{ $metodoSelec['valor'] }} Bs</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td class="m-0 px-1 py-0 letra14"><strong>Total pagando </strong></td>
                    <td class="m-0 px-1 py-0 letra14"><strong>{{ $totalAcumuladoMetodos }} Bs</strong></td>
                </tr>
                @isset($cuenta->cliente->name)
                    @if ($descuentoSaldo > 0)
                    <tr class="text-primary">
                        <td class="m-0 px-1 py-0 letra12"><strong>DESCONTANDO DE SALDO</strong></td>
                        <td class="m-0 px-1 py-0 letra12"><strong>{{ $descuentoSaldo }} Bs</strong></td>
                    </tr>
                       
                    @endif
                    <tr class="m-0 px-1 py-0 letra12">
                        @if ($totalAcumuladoMetodos == $subtotalConDescuento)
                            <td class="m-0 px-1 py-0 bg-success text-white"><strong>MONTO COMPLETADO</strong></td>
                        @elseif($totalAcumuladoMetodos > $subtotalConDescuento)
                            <td class="m-0 px-1 py-0 bg-info text-white"><strong>A FAVOR DEL CLIENTE :
                                    {{ $totalAcumuladoMetodos - $subtotalConDescuento }} Bs</strong></td>
                        @else
                            <td class="m-0 px-1 py-0 bg-danger text-white"><strong><i class="fa fa-circle-exclamation"></i>
                                    A DEUDA DEL CLIENTE : {{ $subtotalConDescuento - $totalAcumuladoMetodos }} Bs</strong>
                            </td>
                        @endif

                    </tr>
                @endisset
            </tbody>
        </table>

    </div>


</div>

<!-- Para depuración -->
{{-- <pre>{{ json_encode($metodosSeleccionados, JSON_PRETTY_PRINT) }}</pre> --}}
