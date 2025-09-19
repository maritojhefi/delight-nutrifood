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

                        <!-- Contenedor para mostrar usuarios seleccionados -->
                        <div id="usuariosSeleccionadosContainer" class="mt-2" 
                             style="min-height: 40px; max-height: 120px; overflow-y: auto;"
                             wire:ignore>
                            <!-- Los badges se mostrarán aquí -->
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
        <style>
            #usuariosSeleccionadosContainer {
                max-height: 120px;
                overflow-y: auto;
                word-wrap: break-word;
                line-height: 1.4;
            }

            #usuariosSeleccionadosContainer .badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.6rem;
                border-radius: 0.375rem;
                max-width: 100%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: inline-flex;
                align-items: center;
                margin-right: 0.5rem;
                margin-bottom: 0.5rem;
            }

            #usuariosSeleccionadosContainer .btn-close {
                font-size: 0.5rem;
                padding: 0.2rem;
                margin-left: 0.4rem;
                flex-shrink: 0;
            }

            #usuariosSeleccionadosContainer .btn-close:hover {
                background-color: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
            }

            #usuariosSeleccionadosContainer .badge:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease;
            }

            /* Responsive para pantallas pequeñas */
            @media (max-width: 768px) {
                #usuariosSeleccionadosContainer .badge {
                    font-size: 0.75rem;
                    padding: 0.3rem 0.5rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script>
            function limpiarSelect() {
                try {
                    jQuery('#usuariosSelect').empty().trigger('change');
                } catch (e) {
                    console.log('Error al limpiar select:', e);
                }
            }
            let convenioSeleccionado = null;
            let usuariosDisponiblesGlobales = [];

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

            // Función para inicializar Select2 de forma segura
            function inicializarSelect2() {
                try {
                    // Destruir instancia previa si existe
                    if (jQuery('#usuariosSelect').hasClass('select2-hidden-accessible')) {
                        jQuery('#usuariosSelect').select2('destroy');
                    }

                    // Limpiar el select
                    jQuery('#usuariosSelect').empty();

                    // Obtener datos de Livewire de forma segura
                    const usuariosDisponibles = @this.usuariosDisponibles || [];
                    const usuariosSeleccionados = @this.usuarios_seleccionados || [];

                    console.log('Usuarios disponibles:', usuariosDisponibles);
                    console.log('Usuarios seleccionados:', usuariosSeleccionados);

                    // Verificar que tenemos datos antes de inicializar
                    if (usuariosDisponibles.length === 0) {
                        console.warn('No hay usuarios disponibles para cargar en Select2');
                        return;
                    }

                    // Inicializar Select2
                    jQuery('#usuariosSelect').select2({
                        placeholder: 'Seleccione usuarios',
                        width: '100%',
                        dropdownParent: jQuery('#usuariosModal'),
                        data: usuariosDisponibles,
                        allowClear: true
                    });

                    // Establecer selecciones previas después de un pequeño delay
                    setTimeout(() => {
                        if (usuariosSeleccionados.length > 0) {
                            jQuery('#usuariosSelect').val(usuariosSeleccionados).trigger('change');
                            console.log('Valores establecidos:', jQuery('#usuariosSelect').val());
                        }
                    }, 50);

                    // Actualizar Livewire cuando cambia la selección
                    jQuery('#usuariosSelect').off('change.select2').on('change.select2', function(e) {
                        const valores = jQuery(this).val() || [];
                        console.log('Valores seleccionados:', valores);
                        @this.set('usuarios_seleccionados', valores);
                    });
                } catch (e) {
                    console.error('Error al inicializar Select2:', e);
                }
            }

            // Función para verificar si el DOM está listo
            function verificarDOMListo() {
                const selectElement = document.getElementById('usuariosSelect');
                const modalElement = document.getElementById('usuariosModal');

                return selectElement &&
                    modalElement &&
                    selectElement.offsetParent !== null &&
                    !selectElement.hasAttribute('disabled');
            }

            // Función para mostrar badges de usuarios seleccionados
            function mostrarUsuariosSeleccionados(usuariosSeleccionados, usuariosDisponibles) {
                const container = document.getElementById('usuariosSeleccionadosContainer');
                container.innerHTML = '';

                if (!usuariosSeleccionados || usuariosSeleccionados.length === 0) {
                    container.innerHTML = '<small class="text-muted">No hay usuarios seleccionados</small>';
                    return;
                }

                usuariosSeleccionados.forEach(usuarioId => {
                    // Buscar el usuario en la lista de disponibles
                    const usuario = usuariosDisponibles.find(u => u.id == usuarioId);
                    if (usuario) {
                        // Extraer solo el nombre (antes del paréntesis)
                        const nombreUsuario = usuario.text.split(' (')[0];
                        
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-primary d-inline-flex align-items-center';
                        badge.style.fontSize = '0.8rem';
                        badge.innerHTML = `
                            ${nombreUsuario}
                            <button type="button" class="btn-close btn-close-white ms-2" 
                                    style="font-size: 0.6rem;" 
                                    onclick="eliminarUsuarioSeleccionado('${usuarioId}')"
                                    aria-label="Eliminar usuario">
                            </button>
                        `;
                        container.appendChild(badge);
                    }
                });
            }

            // Función para eliminar un usuario de la selección
            function eliminarUsuarioSeleccionado(usuarioId) {
                const valoresActuales = @this.usuarios_seleccionados || [];
                const nuevosValores = valoresActuales.filter(id => id != usuarioId);

                // Actualizar Livewire
                @this.set('usuarios_seleccionados', nuevosValores);

                // Actualizar Select2
                if (jQuery('#usuariosSelect').hasClass('select2-hidden-accessible')) {
                    jQuery('#usuariosSelect').val(nuevosValores).trigger('change');
                }

                // Actualizar badges inmediatamente
                mostrarUsuariosSeleccionados(nuevosValores, usuariosDisponiblesGlobales);

                console.log('Usuario eliminado:', usuarioId, 'Valores actuales:', nuevosValores);
            }

            // Función simplificada para inicializar Select2
            function inicializarSelect2(data) {
                try {
                    console.log('Inicializando Select2...');

                    // Almacenar datos globalmente
                    usuariosDisponiblesGlobales = data.usuariosDisponibles;

                    // Destruir instancia previa si existe
                    if (jQuery('#usuariosSelect').hasClass('select2-hidden-accessible')) {
                        jQuery('#usuariosSelect').select2('destroy');
                    }

                    // Limpiar el select
                    jQuery('#usuariosSelect').empty();

                    // Agregar opciones al select HTML
                    data.usuariosDisponibles.forEach(usuario => {
                        const option = document.createElement('option');
                        option.value = usuario.id;
                        option.textContent = usuario.text;
                        document.getElementById('usuariosSelect').appendChild(option);
                    });

                    // Inicializar Select2
                    jQuery('#usuariosSelect').select2({
                        placeholder: 'Seleccione usuarios',
                        width: '100%',
                        dropdownParent: jQuery('#usuariosModal'),
                        allowClear: true
                    });

                    // Establecer selecciones previas
                    if (data.usuariosSeleccionados && data.usuariosSeleccionados.length > 0) {
                        jQuery('#usuariosSelect').val(data.usuariosSeleccionados).trigger('change');
                        console.log('Valores establecidos:', jQuery('#usuariosSelect').val());
                        // Mostrar badges de usuarios pre-seleccionados
                        mostrarUsuariosSeleccionados(data.usuariosSeleccionados, data.usuariosDisponibles);
                    } else {
                        // Mostrar mensaje de no seleccionados
                        mostrarUsuariosSeleccionados([], data.usuariosDisponibles);
                    }

                    // Event listener para cambios
                    jQuery('#usuariosSelect').off('change.select2').on('change.select2', function(e) {
                        const valores = jQuery(this).val() || [];
                        console.log('Valores seleccionados:', valores);
                        // Actualizar Livewire
                        @this.set('usuarios_seleccionados', valores);
                        // Actualizar badges inmediatamente con los datos actuales
                        mostrarUsuariosSeleccionados(valores, data.usuariosDisponibles);
                    });

                    console.log('Select2 inicializado exitosamente');

                } catch (e) {
                    console.error('Error al inicializar Select2:', e);
                }
            }

            // Función para cargar datos desde el servidor
            function cargarDatosSelect2() {
                console.log('Iniciando carga de datos...');

                // Hacer una petición AJAX para obtener los datos frescos
                fetch('/admin/convenios/usuarios-disponibles/' + @this.convenio_id)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Datos recibidos del servidor:', data);
                        if (data.usuariosDisponibles && data.usuariosDisponibles.length > 0) {
                            // Inicializar Select2
                            inicializarSelect2(data);
                        } else {
                            console.warn('No hay usuarios disponibles');
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar datos:', error);
                    });
            }


            // Función para limpiar Select2
            function limpiarSelect2() {
                try {
                    if (jQuery('#usuariosSelect').hasClass('select2-hidden-accessible')) {
                        jQuery('#usuariosSelect').val(null).trigger('change');
                    }
                    // Limpiar badges
                    const container = document.getElementById('usuariosSeleccionadosContainer');
                    if (container) {
                        container.innerHTML = '';
                    }
                } catch (e) {
                    console.log('Error al limpiar Select2:', e);
                }
            }

            document.addEventListener('livewire:load', function() {
                // Verificar que jQuery esté disponible
                if (typeof jQuery === 'undefined') {
                    console.error('jQuery no está disponible');
                    return;
                }

                // Escuchar el evento del navegador
                window.addEventListener('abrirModalUsuarios', function() {
                    console.log('Abriendo modal de usuarios...');

                    // Mostrar el modal primero
                    jQuery('#usuariosModal').modal('show');

                    // Esperar a que el modal esté completamente visible
                    jQuery('#usuariosModal').on('shown.bs.modal', function() {
                        console.log('Modal visible, cargando datos...');
                        // Cargar datos después de un pequeño delay
                        setTimeout(() => {
                            cargarDatosSelect2();
                        }, 500);
                    });
                });

                Livewire.on('cerrarModalUsuarios', function() {
                    jQuery('#usuariosModal').modal('hide');
                    limpiarSelect2();
                });

                Livewire.on('notificar', function(event) {
                    Swal.fire({
                        text: event.mensaje,
                        icon: event.tipo,
                    });
                });

                // Listener para actualizar badges cuando Livewire actualice los datos
                Livewire.on('usuariosVinculados', function() {
                    // Actualizar badges con los datos actuales
                    const usuariosSeleccionados = @this.usuarios_seleccionados || [];
                    const usuariosDisponibles = @this.usuariosDisponibles || [];
                    mostrarUsuariosSeleccionados(usuariosSeleccionados, usuariosDisponibles);
                });

                // Limpiar al cerrar el modal
                jQuery('#usuariosModal').on('hidden.bs.modal', function() {
                    limpiarSelect2();
                    // Remover el event listener para evitar múltiples inicializaciones
                    jQuery('#usuariosModal').off('shown.bs.modal');
                });

                const usuariosModal = new bootstrap.Modal(document.getElementById('usuariosListaModal'));
                let usuariosActuales = [];

                Livewire.on('mostrar-usuarios', function(arrayUsuarios) {
                    // Guardar los usuarios para búsquedas posteriores
                    usuariosActuales = arrayUsuarios;

                    // Corregir la condición - era = en lugar de ==
                    if (jQuery('.modal.show').length === 0) {
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
                        // console.log(usuario);
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

                // Limpiar al cerrar el modal de usuarios
                document.getElementById('usuariosListaModal').addEventListener('hidden.bs.modal', function() {
                    document.getElementById('listaUsuariosBody').innerHTML = '';
                });

                // Búsqueda en el modal de usuarios
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
