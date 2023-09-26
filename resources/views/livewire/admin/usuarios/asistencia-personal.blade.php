<div class="row">
    <div class="col-xl-6 col-lg-6 col-xxl-4 col-sm-12">
        <div class="card overflow-hidden">
            <div class="">
                <div class="card-header">
                    <h4 class="card-title">Seleccione Colaborador</h4>
                </div>
                <div class="card-body">
                    <div class="mail-list rounded">
                        @foreach ($empleados as $empleado)
                            <a href="#" wire:click="seleccionarEmpleado({{ $empleado->id }})"
                                class="list-group-item @isset($empleadoSeleccionado) {{ $empleadoSeleccionado->id == $empleado->id ? 'active' : '' }} @endisset"><i
                                    class="fa fa-user font-18 align-middle me-2"></i> {{ $empleado->name }} </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @isset($empleadoSeleccionado)
        <div class="col-xl-6 col-lg-6 col-xxl-8 col-sm-12">
            <div class="card overflow-hidden">
                <div class="">
                    <div class="card-header">
                        <h4 class="card-title">Asistencias: {{ $empleadoSeleccionado->name }}</h4>
                        <div class="col-2">
                            <div class="d-flex justify-content-center">
                                <div wire:loading class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        Reportes:
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <small>Desde:</small>
                                <input type="date" class="form-control form-control-sm" wire:model="reporteInicio">
                            </div>
                            <div class="col-6">
                                <small>Hasta:</small>
                                <input type="date" class="form-control form-control-sm" wire:model="reporteFin">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-y: auto;max-height:300px;overflow-x: hidden">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        {{-- <th><strong>Nombre</strong></th> --}}

                                        <th><strong>Hora Entrada</strong></th>
                                        <th><strong>Hora Salida</strong></th>
                                        <th><strong>Tiempo total</strong></th>
                                        <th><strong>Fecha</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($reporteInicio && $reporteFin)
                                        @php
                                            $registros = $empleadoSeleccionado->asistencias->filter(function ($asistencia) use ($reporteInicio, $reporteFin) {
                                                // Define aquí las condiciones de filtrado en la tabla pivot
                                                return $asistencia->pivot->created_at > $reporteInicio && $asistencia->pivot->created_at < $reporteFin;
                                            });
                                        @endphp
                                        @foreach ($registros as $item)
                                            <tr>
                                                <td><small
                                                        class="badge badge-success light badge-sm">{{ \App\Helpers\GlobalHelper::fechaFormateada(6, $item->pivot->entrada) . ' (' . $item->pivot->diferencia_entrada . ' min)' }}</small>
                                                </td>
                                                <td><small
                                                        class="badge badge-warning light badge-sm">{{ isset($item->pivot->salida) ? \App\Helpers\GlobalHelper::fechaFormateada(6, $item->pivot->salida) . ' (' . $item->pivot->diferencia_salida . ' min)' : 'Sin registro' }}</small>
                                                </td>
                                                <td>{{ $item->pivot->tiempo_total / 60 }} hora(s)</td>
                                                <td><small>{{ \App\Helpers\GlobalHelper::fechaFormateada(2, $item->pivot->created_at) }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @php
                                            $registros = $empleadoSeleccionado->asistencias;
                                        @endphp
                                        @foreach ($registros as $item)
                                            <tr>
                                                <td><small
                                                        class="badge badge-success light badge-sm">{{ \App\Helpers\GlobalHelper::fechaFormateada(6, $item->pivot->entrada) . ' (' . $item->pivot->diferencia_entrada . ' min)' }}</small>
                                                </td>
                                                <td><small
                                                        class="badge badge-warning light badge-sm">{{ isset($item->pivot->salida) ? \App\Helpers\GlobalHelper::fechaFormateada(6, $item->pivot->salida) . ' (' . $item->pivot->diferencia_salida . ' min)' : 'Sin registro' }}</small>
                                                </td>
                                                <td>{{ $item->pivot->tiempo_total / 60 }} hora(s)</td>
                                                <td><small>{{ \App\Helpers\GlobalHelper::fechaFormateada(2, $item->pivot->created_at) }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>

                        </div>
                    </div>
                    @php
                        $diasTrabajados = $registros->count();
                        $horasTrabajadas =
                            $registros->sum(function ($asistencia) {
                                return $asistencia->pivot->tiempo_total;
                            }) / 60;
                        $retrasos = $registros->sum(function ($asistencia) {
                            return $asistencia->pivot->diferencia_entrada + $asistencia->pivot->diferencia_salida;
                        }) / 60;
                    @endphp

                    <div class="card-footer">
                        <span>Dias trabajados: {{ $diasTrabajados }}</span> <br>
                        <span>Horas trabajadas: {{ round($horasTrabajadas) }} Hora(s)</span> <br>
                        <span>Retrasos: {{ round($retrasos,1) }} Hora(s)</span>
                    </div>

                </div>

            </div>
        </div>
    @endisset

</div>
