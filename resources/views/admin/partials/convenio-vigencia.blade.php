{{-- Estado de vigencia de un convenio. Uso: @include('admin.partials.convenio-vigencia', ['convenio' => $convenio]) --}}
@php
    if ($convenio->fecha_limite) {
        $vgFecha = \Carbon\Carbon::parse($convenio->fecha_limite)->startOfDay();
        $vgDias = \Carbon\Carbon::today()->diffInDays($vgFecha, false);
        if ($vgDias < 0) {
            [$vgEstado, $vgTexto] = ['vencido', 'Vencido'];
        } elseif ($vgDias === 0) {
            [$vgEstado, $vgTexto] = ['pronto', 'Vence hoy'];
        } elseif ($vgDias <= 7) {
            [$vgEstado, $vgTexto] = ['pronto', 'Vence en ' . $vgDias . ($vgDias === 1 ? ' día' : ' días')];
        } else {
            [$vgEstado, $vgTexto] = ['vigente', 'Vigente'];
        }
        $vgFechaTexto = $vgFecha->locale('es')->translatedFormat('d M Y');
        $vgRelativo = $vgDias > 7 ? \App\Helpers\GlobalHelper::timeago($convenio->fecha_limite) : null;
    } else {
        [$vgEstado, $vgTexto] = ['sinfecha', 'Sin fecha'];
        $vgFechaTexto = null;
        $vgRelativo = null;
    }
@endphp
<span class="cl-status is-{{ $vgEstado }}">{{ $vgTexto }}</span>
@if ($vgFechaTexto)
    <span class="cl-date">{{ $vgFechaTexto }}{{ $vgRelativo ? ' · ' . $vgRelativo : '' }}</span>
@endif
