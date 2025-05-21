<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <h4 class="card-title">Convenios y vinculos</h4>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <input type="text" class="form-control form-control-sm bordeado" style="height: 30px"
                    placeholder="Buscar" wire:model.debounce.750ms="buscar">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-md m-0 p-0 letra12">
                    <thead class="m-0 p-0">
                        <tr class="m-0 p-0">
                            <th class="m-0 p-0"><strong>Nombre</strong></th>
                            <th class="m-0 p-0"><strong>Cant. Usuarios</strong></th>
                            <th class="m-0 p-0"><strong>Fecha Limite</strong></th>
                            <th class="m-0 p-0"><strong>Acciones</strong></th>
                        </tr>
                    </thead>
                    <tbody class="m-0 p-0">
                        @foreach ($convenios as $convenio)
                            <tr>
                                <td class="m-0 p-1">
                                    {{ $convenio->nombre_convenio }}
                                </td>
                                <td class="m-0 p-1">
                                    {{ $convenio->usuarios->count() }}
                                </td>
                                <td class="m-0 p-1">
                                    @if (isset($convenio->fecha_limite))
                                        {{ $convenio->fecha_limite }}
                                        <br>
                                        <small
                                            class="text-muted">{{ \App\Helpers\GlobalHelper::timeago($convenio->fecha_limite) }}</small>
                                    @else
                                        Sin fecha
                                    @endif
                                </td>
                                <td class="m-0 p-1">
                                    <div class="d-flex">
                                        <button class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal"
                                            data-bs-target="#basicModal"
                                            wire:click="agregarUsuarios({{ $convenio->id }})">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row  mx-auto">
            <div class="col">{{ $convenios->links() }}</div>
        </div>
        <div class="row  mx-auto">
            <div class="col">Mostrando {{ $convenios->count() }} de {{ $convenios->total() }} registros</div>
        </div>
    </div>
    <div class="modal fade" id="usuariosModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vincular usuarios al convenio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Seleccione usuarios</label>
                        <div wire:ignore>
                            <select id="usuariosSelect" class="form-control" multiple style="width: 100%">
                                <!-- Los usuarios se cargarán via JavaScript -->
                            </select>
                        </div>
                        @error('usuarios_seleccionados')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="vincularUsuarios">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                let select2Instance = null;

                Livewire.on('abrirModalUsuarios', function() {
                    // Destruir instancia previa si existe
                    if (select2Instance) {
                        $('#usuariosSelect').select2('destroy');
                    }

                    $('#usuariosModal').modal('show');

                    // Inicializar Select2
                    select2Instance = $('#usuariosSelect').select2({
                        placeholder: 'Seleccione usuarios',
                        width: '100%',
                        dropdownParent: $('#usuariosModal'),
                        data: @this.usuariosDisponibles
                    });

                    // Establecer selecciones previas
                    $('#usuariosSelect').val(@this.usuarios_seleccionados).trigger('change');

                    // Actualizar Livewire cuando cambia la selección
                    $('#usuariosSelect').on('change', function(e) {
                        @this.set('usuarios_seleccionados', $(this).val());
                    });
                });

                Livewire.on('cerrarModalUsuarios', function() {
                    $('#usuariosModal').modal('hide');
                    if (select2Instance) {
                        $('#usuariosSelect').val(null).trigger('change');
                    }
                });

                // Limpiar al cerrar el modal
                $('#usuariosModal').on('hidden.bs.modal', function() {
                    if (select2Instance) {
                        $('#usuariosSelect').val(null).trigger('change');
                    }
                });
            });
        </script>
    @endpush
</div>

</div>
