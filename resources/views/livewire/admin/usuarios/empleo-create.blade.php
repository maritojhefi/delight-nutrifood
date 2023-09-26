<div>
    <div class="row">
        <div class="col-xl-4 col-lg-12 col-xxl-5 col-sm-12">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Nuevo contrato</h4>
                    </div>
                    <div class="card-body">
                        <div class="">

                            <div class="mb-3 row">
                                <select name="" class="form-control @error($user_id) is-invalid @enderror"
                                    wire:model="user_id" id="">

                                    <option>Seleccione colaborador ({{ $usuarios->count() }})</option>
                                    @foreach ($usuarios as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Sueldo</label>
                                <div class="col-sm-8">
                                    <input type="number" wire:model.lazy="sueldo"
                                        class=" form-control @error($sueldo) is-invalid @enderror" placeholder="En BS">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Fecha de inicio</label>
                                <div class="col-sm-8">
                                    <input type="date" wire:model.lazy="fecha_inicio"
                                        class=" form-control @error($fecha_inicio) is-invalid @enderror">
                                </div>
                            </div>
                            {{-- <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Hora Entrada</label>
                                <div class="col-sm-8">
                                    <input type="time" wire:model.lazy="hora_entrada"
                                        class=" form-control @error($hora_entrada) is-invalid @enderror">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Hora Salida</label>
                                <div class="col-sm-8">
                                    <input type="time" wire:model.lazy="hora_salida"
                                        class=" form-control @error($hora_salida) is-invalid @enderror">
                                </div>
                            </div> --}}
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Modalidad</label>
                                <div class="col-sm-8">
                                    <select name="" wire:model="modalidad"
                                        class="form-control @error($modalidad) is-invalid @enderror">
                                        <option value="mensual">Mensual</option>
                                        <option value="quincenal">Quincenal</option>
                                        <option value="semanal">Semanal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label
                                    class="col-sm-4 col-form-label @error($observacion) is-invalid @enderror">Observacion</label>
                                <div class="col-sm-8">
                                    <textarea wire:model="observacion" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Seleccione los Dias de trabajo</h4>
                </div>
                <div class="card-body">
                    @php
                        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                    @endphp
                    <div class="row m-2">
                        @foreach ($dias as $item)
                            <div class="col-4">
                                <div class="form-check custom-checkbox mb-3 checkbox-warning">
                                    <input type="checkbox" class="form-check-input" checked
                                        wire:model="{{ strtolower($item) }}" id="{{ strtolower($item) }}">
                                    <label class="form-check-label"
                                        for="{{ strtolower($item) }}">{{ $item }}</label>
                                </div>
                            </div>
                            @php
                                $dia = strtolower($item);
                            @endphp
                            <div class="col-8">
                                <div class="row">
                                    @if ($$dia)
                                        <div class="col-5">
                                            <input type="time" step="900"
                                                class="form-control form-control-sm  @error($hora_entrada) is-invalid @enderror"
                                                style="padding-left:5px" wire:model="hora_entrada.{{ $dia }}">
                                        </div>
                                        <div class="col-5">
                                            <input type="time" step="900"
                                                class="form-control form-control-sm  @error($hora_salida) is-invalid @enderror"
                                                style="padding-left:5px" wire:model="hora_salida.{{ $dia }}">
                                        </div>
                                        <div class="col-2">
                                            <a href="#" wire:click="expandirFecha('{{ strtolower($item) }}')">
                                                <span class="ticket-icon-1 mb-3">
                                                    <i class="fa fa-expand"></i>
                                                </span>
                                            </a>

                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                        <hr>
                        <button class="btn btn-success btn-sm" wire:click="crear">Crear nuevo contrato</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Contratos registrados</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <th>Nombre</th>
                            <th>Fecha Inicio</th>
                            {{-- <th>Hora entrada</th>
                            <th>Hora salida</th> --}}
                            @foreach ($dias as $item)
                                <th>{{ $item }}</th>
                            @endforeach
                        </thead>
                        <tbody>
                            @foreach ($contratos as $contrato)
                                <tr>
                                    <td>{{ $contrato->usuario->name }}</td>
                                    <td>{{ $contrato->fecha_inicio }}</td>
                                    {{-- <td>{{ $contrato->hora_entrada }}</td>
                                    <td>{{ $contrato->hora_salida }}</td> --}}
                                    @php
                                        $horario = json_decode($contrato->hora_entrada);
                                        $horario2 = json_decode($contrato->hora_salida);
                                        // dd($horario);
                                    @endphp
                                    <td><span
                                            class="badge badge-success">{{ isset($horario->lunes) ? 'De: ' . $horario->lunes : '' }}</span>
                                        <span
                                            class="badge badge-warning">{{ isset($horario2->lunes) ? 'A: ' . $horario2->lunes : '' }}</span>
                                    </td>
                                    <td><span
                                            class="badge badge-success">{{ isset($horario->martes) ? 'De: ' . $horario->martes : '' }}</span>
                                        <span
                                            class="badge badge-warning">{{ isset($horario2->martes) ? 'A: ' . $horario2->martes : '' }}</span>
                                    </td>
                                    <td><span
                                            class="badge badge-success">{{ isset($horario->miercoles) ? 'De: ' . $horario->miercoles : '' }}</span>
                                        <span
                                            class="badge badge-warning">{{ isset($horario2->miercoles) ? 'A: ' . $horario2->miercoles : '' }}</span>
                                    </td>
                                    <td><span
                                            class="badge badge-success">{{ isset($horario->jueves) ? 'De: ' . $horario->jueves : '' }}</span>
                                        <span
                                            class="badge badge-warning">{{ isset($horario2->jueves) ? 'A: ' . $horario2->jueves : '' }}</span>
                                    </td>
                                    <td><span
                                            class="badge badge-success">{{ isset($horario->viernes) ? 'De: ' . $horario->viernes : '' }}</span>
                                        <span
                                            class="badge badge-warning">{{ isset($horario2->viernes) ? 'A: ' . $horario2->viernes : '' }}</span>
                                    </td>
                                    <td><span
                                            class="badge badge-success">{{ isset($horario->sabado) ? 'De: ' . $horario->sabado : '' }}</span>
                                        <span
                                            class="badge badge-warning">{{ isset($horario2->sabado) ? 'A: ' . $horario2->sabado : '' }}</span>
                                    </td>
                                    <td><span
                                            class="badge badge-success">{{ isset($horario->domingo) ? 'De: ' . $horario->domingo : '' }}</span>
                                        <span
                                            class="badge badge-success">{{ isset($horario2->domingo) ? 'A: ' . $horario2->domingo : '' }}</span>
                                    </td>
                                    <td><a href="#" class="badge badge-xs light badge-danger"
                                            data-bs-toggle="modal" data-bs-target="#modal"><span class="fa fa-trash"
                                                wire:click="seleccionar({{ $contrato->id }})"></span></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
            aria-hidden="true" id="modal">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body" wire:loading wire:target="seleccionar">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    @isset($seleccionado)
                        <div wire:loading.remove wire:target="seleccionar">
                            <div class="modal-header">
                                <h5 class="modal-title">Esta seguro de eliminar el contrato de
                                    {{ $seleccionado->usuario->name }}?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-info" wire:click="delete"
                                    data-bs-dismiss="modal">Confirmar</button>
                            </div>
                        </div>
                    @endisset

                </div>
            </div>
        </div>
    </div>
</div>
