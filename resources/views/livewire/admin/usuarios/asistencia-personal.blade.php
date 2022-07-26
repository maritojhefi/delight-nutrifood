<div>
    <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Asistencia de Hoy</h4>
                    <div class="col-2"><div  class="d-flex justify-content-center">
                        <div wire:loading class="spinner-border" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </div></div>
                    <input type="text" class="form-control" wire:model.debounce.500ms="search" placeholder="Buscar empleado">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Hora Entrada</strong></th>
                                    <th><strong>Hora Salida</strong></th>
                                    <th><strong>Tiempo total</strong></th>
                                    <th><strong>Fecha</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistencias as $item)
                                    <tr>
                                        <td><span class="badge light badge-success">{{ $item->name }}</span></td>
                                        <td>
                                            @isset($item->entrada)
                                                @if ($item->diferencia_entrada < 0)
                                                    <span
                                                        class="badge badge-warning ">{{ date_format(date_create($item->salida), 'H:i') }}
                                                        (retraso de {{ $item->diferencia_entrada }} minutos)
                                                    @else
                                                        <span
                                                            class="badge badge-success ">{{ date_format(date_create($item->salida), 'H:i') }}
                                                            (anticipo de {{ $item->diferencia_entrada }} minutos)
                                                @endif
                                                </span>
                                            @endisset
                                        </td>
                                        @isset($item->salida)
                                            <td>


                                                @if ($item->diferencia_salida < 0)
                                                    <span
                                                        class="badge badge-warning ">{{ date_format(date_create($item->salida), 'H:i') }}
                                                        (salio {{ $item->diferencia_salida }} minutos antes)
                                                    @else
                                                        <span
                                                            class="badge badge-success ">{{ date_format(date_create($item->salida), 'H:i') }}
                                                            (salio {{ $item->diferencia_salida }} minutos despues)
                                                @endif
                                                </span>
                                            @endisset
                                        </td>
                                        <td>
                                            {{number_format($item->tiempo_total/60,2)}} hrs
                                        </td>
                                        <td>
                                          Hace {{ Carbon\Carbon::parse($item->created_at)->diffInDays(Carbon\Carbon::now()) }} dia(s)</td>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
