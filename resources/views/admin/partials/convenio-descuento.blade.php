{{-- Píldora con el valor y tipo de descuento. Uso: @include('admin.partials.convenio-descuento', ['convenio' => $convenio]) --}}
@php
    $dsPorcentaje = $convenio->tipo_descuento === 'porcentaje';
    $dsValor = (float) $convenio->valor_descuento;
    $dsTexto = $dsPorcentaje
        ? rtrim(rtrim(number_format($dsValor, 2), '0'), '.') . '%'
        : 'Bs ' . number_format($dsValor, 2);
@endphp
<span class="cl-discount {{ $dsPorcentaje ? 'is-pct' : 'is-fijo' }}">
    <strong>{{ $dsTexto }}</strong>
    <small>{{ $dsPorcentaje ? 'Porcentaje' : 'Monto fijo' }}</small>
</span>
