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
                                    @if ($convenio->usuarios->count() > 0)
                                        {{ $convenio->usuarios->count() }} personas
                                        <a href="#" type="button" wire:click="verUsuarios('{{ $convenio->id }}')"
                                            onclick="seleccionarConvenio('{{ $convenio->id }}')">
                                            <i class="fa fa-info-circle fa-beat text-primary ms-2"
                                                style="font-size: 14px !important;"></i>
                                        </a>
                                    @else
                                        <small class="text-muted">(Sin Usuarios)</small>
                                    @endif
                                </td>
                                <td class="m-0 p-1">
                                    @if (isset($convenio->fecha_limite))
                                        {{ $convenio->fecha_limite }}
                                        <br>
                                        <small
                                            class="text-muted">{{ \App\Helpers\GlobalHelper::timeago($convenio->fecha_limite) }}</small>
                                    @else
                                        <small class="text-muted"> (Sin fecha)</small>
                                    @endif
                                </td>
                                <td class="m-0 p-1">
                                    <div class="d-flex">
                                        <button class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal"
                                            data-bs-target="#basicModal"
                                            wire:click="agregarUsuarios({{ $convenio->id }})" onclick="limpiarSelect()">
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

    <div class="modal fade" id="usuariosListaModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuarios dentro del convenio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="buscarUsuarioModal" class="form-control"
                            placeholder="Buscar por nombre o teléfono">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive-md m-0 p-0 letra12">
                            <thead class="m-0 p-0">
                                <tr class="m-0 p-0">
                                    <th class="m-0 p-0"><strong>Nombre</strong></th>
                                    <th class="m-0 p-0"><strong>Teléfono</strong></th>
                                    <th class="m-0 p-0"><strong>Fecha Agregado</strong></th>
                                    <th class="m-0 p-0"><strong>Acciones</strong></th>
                                </tr>
                            </thead>
                            <tbody id="listaUsuariosBody" class="m-0 p-0">
                                <!-- Los usuarios se insertarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
            function limpiarSelect() {
                $('#usuariosSelect').empty().trigger('change');
            }
            let convenioSeleccionado = null;

            function seleccionarConvenio(convenioId) {
                convenioSeleccionado = convenioId;
            }

            function eliminarUsuarioConvenio(usuarioId) {
                Swal.fire({
                    title: "Desvincular usuario",
                    text: "Esta seguro que desea desvincular al usuario del convenio?. Esta acción no se puede revertir.",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si!. Desvincular",
                    cancelButtonText: "No!, Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('eliminar-usuario-convenio', convenioSeleccionado, usuarioId);
                    }
                });
            }
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
                Livewire.on('notificar', function(event) {
                    // console.log(event)
                    Swal.fire({
                        text: event.mensaje,
                        icon: event.tipo,
                    });
                });

                // Limpiar al cerrar el modal
                $('#usuariosModal').on('hidden.bs.modal', function() {
                    if (select2Instance) {
                        $('#usuariosSelect').val(null).trigger('change');
                    }
                });

                const usuariosModal = new bootstrap.Modal(document.getElementById('usuariosListaModal'));
                let usuariosActuales = [];
                Livewire.on('mostrar-usuarios', function(arrayUsuarios) {
                    // Guardar los usuarios para búsquedas posteriores
                    usuariosActuales = arrayUsuarios;

                    if ($('.modal.show').length = 0) {
                        // Limpiar lista anterior
                        const listaBody = document.getElementById('listaUsuariosBody');
                        listaBody.innerHTML = '';
                    }
                    // Llenar la tabla con los nuevos usuarios
                    renderizarUsuarios(arrayUsuarios);
                    // Mostrar el modal
                    usuariosModal.show();
                });

                // Función para renderizar usuarios filtrados
                function renderizarUsuarios(usuarios) {
                    const listaBody = document.getElementById('listaUsuariosBody');
                    listaBody.innerHTML = '';
                    usuarios.forEach(usuario => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
            <td class="m-0 p-1">${usuario.nombre}</td>
            <td class="m-0 p-1">${usuario.telf}</td>
            <td class="m-0 p-1">${usuario.fecha_creacion} - ${usuario.hora_creacion}<br>(${usuario.hace_tiempo})</td>
            <td class="m-0 p-1"><button type="button" class="btn btn-rounded btn-danger btn-xs" onclick="eliminarUsuarioConvenio(${usuario.id})"><i class="fa fa-trash"></i></button></td>
        `;
                        listaBody.appendChild(row);
                    });
                }

                // Opcional: Limpiar al cerrar el modal
                document.getElementById('usuariosListaModal').addEventListener('hidden.bs.modal', function() {
                    document.getElementById('listaUsuariosBody').innerHTML = '';
                });

                // Añadir esto al final del event listener
                document.getElementById('buscarUsuarioModal')?.addEventListener('input', function(e) {
                    const terminoBusqueda = e.target.value.toLowerCase();
                    if (terminoBusqueda === '') {
                        renderizarUsuarios(usuariosActuales);
                        return;
                    }
                    const usuariosFiltrados = usuariosActuales.filter(usuario => {
                        return usuario.nombre.toLowerCase().includes(terminoBusqueda) ||
                            (usuario.telf && usuario.telf.toLowerCase().includes(terminoBusqueda));
                    });
                    renderizarUsuarios(usuariosFiltrados);
                });

                // Limpiar el input de búsqueda al cerrar el modal
                document.getElementById('usuariosListaModal')?.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('buscarUsuarioModal').value = '';
                    document.getElementById('listaUsuariosBody').innerHTML = '';
                });
            });
        </script>
    @endpush
</div>

</div>
