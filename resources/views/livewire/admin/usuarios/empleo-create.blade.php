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

                                    <option>Seleccione usuario ({{ $usuarios->count() }})</option>
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
                            <div class="mb-3 row">
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
                            </div>
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
                    <div class="row">
                        @php
                            $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                        @endphp
                        <div class="col-xl-4 col-xxl-6 col-6">
                            @foreach ($dias as $item)
                                <div class="form-check custom-checkbox mb-3 checkbox-warning">
                                    <input type="checkbox" class="form-check-input" checked
                                        wire:model="{{ strtolower($item) }}">
                                    <label class="form-check-label" for="customCheckBox4">{{ $item }}</label>
                                </div>
                            @endforeach
                            <button class="btn btn-success" wire:click="crear">Crear nuevo contrato</button>
                        </div>

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
                            <th>Hora entrada</th>
                            <th>Hora salida</th>
                            @foreach ($dias as $item)
                                <th>{{ $item }}</th>
                            @endforeach
                        </thead>
                        <tbody>
                            @foreach ($contratos as $contrato)
                                <tr>
                                    <td>{{ $contrato->usuario->name }}</td>
                                    <td>{{ $contrato->fecha_inicio }}</td>
                                    <td>{{ $contrato->hora_entrada }}</td>
                                    <td>{{ $contrato->hora_salida }}</td>
                                    <td><span
                                            class="badge {{ $contrato->lunes ? 'badge-success' : 'badge-warning' }}">{{ $contrato->lunes ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td><span
                                            class="badge {{ $contrato->martes ? 'badge-success' : 'badge-warning' }}">{{ $contrato->martes ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td><span
                                            class="badge {{ $contrato->miercoles ? 'badge-success' : 'badge-warning' }}">{{ $contrato->miercoles ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td><span
                                            class="badge {{ $contrato->jueves ? 'badge-success' : 'badge-warning' }}">{{ $contrato->jueves ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td><span
                                            class="badge {{ $contrato->viernes ? 'badge-success' : 'badge-warning' }}">{{ $contrato->viernes ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td><span
                                            class="badge {{ $contrato->sabado ? 'badge-success' : 'badge-warning' }}">{{ $contrato->sabado ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td><span
                                            class="badge {{ $contrato->domingo ? 'badge-success' : 'badge-warning' }}">{{ $contrato->domingo ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td><a href="#" class="badge badge-xs light badge-danger" data-bs-toggle="modal"
                                            data-bs-target="#modal"><span class="fa fa-trash" wire:click="seleccionar({{$contrato->id}})"></span></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true"
            id="modal">
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
                                <h5 class="modal-title">Esta seguro de eliminar el contrato de {{$seleccionado->usuario->name}}?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-info" wire:click="delete"  data-bs-dismiss="modal">Confirmar</button>
                            </div>
                        </div>


                    @endisset

                </div>
            </div>
        </div>
    </div>
</div>
