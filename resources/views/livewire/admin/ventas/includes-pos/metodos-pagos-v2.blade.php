<hr>
<span class="text-info text-center d-block mx-auto letra12">Seleccione los métodos de pago: </span>

<div class="m-2">
    @foreach ($metodosPagos as $metodo)
        <div class="row letra12">
            <div class="col-2"></div>
            <div class="col-4">
                <div class="form-check">
                    <input class="form-check-input checkbox-focus" type="checkbox" name="gridRadios" id="check-{{ $metodo->codigo }}"
                      data-id="{{ $metodo->codigo }}"  wire:model="metodosSeleccionados.{{ $metodo->codigo }}.activo">
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
                      id="input-{{ $metodo->codigo }}"  class="form-control form-control-sm bordeado m-0 py-0 px-2" style="height: 20px;width:60px"
                        step="any" placeholder="Bs">
                </div>
            @endif
        </div>
    @endforeach
    
    <div class=" letra12 p-1 px-2 mt-3 ">
        <table class="table table-responsive">
            <thead class="m-0 px-1 py-0">
                <th class="m-0 px-1 py-0">
                    <strong>Metodos seleccionados:
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                          </div>
                    </strong></th>
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
                    <tr class="m-0 px-1 py-0 letra14">
                        @if ($totalAcumuladoMetodos == $subtotalConDescuento)
                            <td class="m-0 px-1 py-0 bg-success text-white"><strong>MONTO COMPLETADO</strong></td>
                        @elseif($totalAcumuladoMetodos > $subtotalConDescuento)
                            <td class="m-0 px-1 py-0 bg-info text-white"><strong>A FAVOR DEL CLIENTE : {{ $totalAcumuladoMetodos - $subtotalConDescuento }} Bs</strong></td>
                        @else
                            <td class="m-0 px-1 py-0 bg-danger text-white"><strong><i class="fa fa-circle-exclamation"></i>
                                    A DEUDA DEL CLIENTE : {{ $subtotalConDescuento - $totalAcumuladoMetodos }} Bs</strong></td>
                        @endif

                    </tr>
                @endisset
            </tbody>
        </table>

    </div>


</div>

<!-- Para depuración -->
{{-- <pre>{{ json_encode($metodosSeleccionados, JSON_PRETTY_PRINT) }}</pre> --}}
