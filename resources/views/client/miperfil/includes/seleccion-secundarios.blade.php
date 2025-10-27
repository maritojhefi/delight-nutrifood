<div class="row mb-0">
    @if ($plan->sopa)
        <h4 class="col-6 font-500  font-13"> <i class="fa fa-check color-green-dark"></i>
            <strong>Sopa</strong>
        </h4>
        <p class="col-6 mb-3 text-end  font-900 mb-0">
            {{ $lista['sopa'] }}
        </p>
        <input type="hidden" value="{{ $lista['sopa'] }}" name="sopa">
    @endif
    @if ($plan->ensalada)
        <h4 class="col-6 font-500 font-13 "> <i class="fa fa-check color-green-dark"></i>
            <strong>Ensalada</strong>
        </h4>
        <p class="col-6 mb-3 text-end  font-900 mb-0">
            {{ $lista['ensalada'] }}
        </p>
        <input type="hidden" value="{{ $lista['ensalada'] }}" name="ensalada">
    @endif
    @if ($plan->jugo)
        <h4 class="col-6 font-500 font-13"> <i class="fa fa-check color-green-dark"></i>
            <strong>Jugo</strong>
        </h4>
        <p class="col-6 mb-3 text-end  font-900 mb-0">
            {{ $lista['jugo'] }}
        </p>
        <input type="hidden" value="{{ $lista['jugo'] }}" name="jugo">
    @endif

    <input type="hidden" value="{{ $lista['dia'] }}" name="dia">
    <input type="hidden" value="{{ $lista['id'] }}" name="id">
    <input type="hidden" value="{{ $plan->id }}" name="plan">


    <div class="col">
        <button type="submit" disabled
            class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-mint-dark">Guardar
            {{ $lista['dia'] }}</button>
    </div>
</div>