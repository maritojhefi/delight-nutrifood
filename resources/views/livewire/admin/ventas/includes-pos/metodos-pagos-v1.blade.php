<span class="badge light badge-warning">Tipo de pago: </span>
@isset($tipocobro)
    <span class="badge light badge-success">{{ $tipocobro }}</span>
@endisset

@push('scripts')
    <script>
        r5t6
        Livewire.on('cambiarCheck', cambiar => {
            document.getElementById("check-efectivo").checked = true;

        })
    </script>
@endpush

<div class="m-2">
    <div class="form-check">
        <input class="form-check-input" type="radio" name="gridRadios" id="check-efectivo" wire:model="tipocobro"
            value="efectivo">
        <label class="form-check-label">
            Efectivo
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input " type="radio" name="gridRadios" wire:model="tipocobro" value="tarjeta">
        <label class="form-check-label">
            Tarjeta
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input " {{ $deshabilitarBancos ? 'disabled' : '' }} type="radio" name="gridRadios"
            wire:model="tipocobro" value="banco-sol">
        <label class="form-check-label">
            Banco Sol
        </label>
    </div>

    <div class="form-check disabled">
        <input class="form-check-input " type="radio" name="gridRadios" wire:model="tipocobro" value="banco-bnb">
        <label class="form-check-label">
            Banco BNB
        </label>
    </div>
</div>
@isset($cuenta->cliente->name)
    <div class="form-check disabled">
        <input class="form-check-input" type="checkbox" name="checkbox" wire:model="saldo" wire:change="actualizarSaldo">
        <label class="form-check-label">
            A deuda
        </label>
    </div>
    @if ($saldo == true)
        <div class="row d-flex">
            <div id="saldo" class="col-4 mx-auto">
                <div class="input-group input-group-sm mb-3 input-success">
                    <span class="input-group-text">Bs</span>
                    <input type="number" wire:model.debounce.500ms="saldoRestante" wire:change="controlarEntrante"
                        class="form-control">
                </div>

            </div>
            <div id="saldo" class="col-4 mx-auto">
                <div class="input-group input-group-sm mb-3 input-info">
                    <span class="input-group-text">Saldo</span>
                    <input type="number" wire:model.debounce.500ms="valorSaldo" wire:change="controlarSaldo"
                        class="form-control">
                </div>

            </div>

        </div>

        @if ($saldoRestante == 0)
            <div class="alert alert-success notification p-0 my-0">
                <p class="notificaiton-title mb-2"><strong>Correcto!</strong>
                    Se
                    agregara
                    el total de
                    <strong>{{ $subtotal - $cuenta->descuento - $descuentoProductos }}
                        Bs</strong>
                    al saldo por cobrar de
                    <strong>{{ $cuenta->cliente->name }}!</strong>
                </p>

            </div>
        @elseif($saldoRestante != 0)
            <div class="alert alert-warning notification p-0 my-0">
                <p class="notificaiton-title mb-2"><strong>Atencion!</strong>
                    Estas
                    agregando <strong>{{ $valorSaldo }} Bs</strong> al saldo
                    de
                    <strong>{{ $cuenta->cliente->name }}</strong> y cobrando
                    <strong>{{ $saldoRestante }} Bs</strong> por el metodo
                    <strong>"{{ $tipocobro }}"</strong>
                </p>

            </div>
        @endif
    @endif
@endisset
