<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <h4 class="card-title">Lista de Convenios</h4>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control form-control-sm bordeado" style="height: 30px"
                    placeholder="Buscar por Nombre de convenio/Tipo/Monto/Producto" wire:model.debounce.750ms="buscar">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-end">
                <button type="button" class="btn btn-primary btn-xs" data-bs-toggle="modal"
                    data-bs-target="#basicModal" wire:click="crearNuevo"><i
                        class="fa fa-plus-circle me-2"></i>Nuevo</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-md m-0 p-0 letra12">
                    <thead class="m-0 p-0">
                        <tr class="m-0 p-0">
                            <th class="m-0 p-0"><strong>Nombre</strong></th>
                            <th class="m-0 p-0"><strong>Tipo</strong></th>
                            <th class="m-0 p-0"><strong>Monto</strong></th>
                            <th class="m-0 p-0" style="width: 30%;"><strong>Productos</strong></th>
                            <th class="m-0 p-0"><strong>Fecha Limite</strong></th>
                            <th class="m-0 p-0"><strong>Acciones</strong></th>
                        </tr>
                    </thead>
                    <tbody class="m-0 p-0">
                        @foreach ($convenios as $convenio)
                            <tr>
                                <td class="m-0 p-1">{{ $convenio->nombre_convenio }}</td>
                                <td class="m-0 p-1">
                                    <span
                                        class="badge light {{ $convenio->tipo_descuento == 'porcentaje' ? 'badge-success' : 'badge-primary' }}">
                                        {{ $convenio->tipo_descuento == 'porcentaje' ? 'Porcentaje' : 'Monto Fijo' }}
                                    </span>
                                </td>
                                <td class="m-0 p-1">
                                    {{ $convenio->tipo_descuento == 'porcentaje' ? $convenio->valor_descuento . '%' : 'Bs. ' . number_format($convenio->valor_descuento, 2) }}
                                </td>
                                <td class="m-0 p-1">
                                    @php
                                        $productos = json_decode($convenio->productos_afectados);
                                        // dd($productos);
                                    @endphp
                                    @foreach ($productos as $key => $productoId)
                                        <span
                                            class="badge badge-pill badge-primary badge-xs">{{ GlobalHelper::obtenerModeloProducto($productoId)->nombre }}</span>
                                    @endforeach
                                </td>
                                <td class="m-0 p-1">
                                    @if (isset($convenio->fecha_limite))
                                        {{ $convenio->fecha_limite }}
                                        <br>
                                        <small
                                            class="text-muted text-primary"><strong>{{ GlobalHelper::timeago($convenio->fecha_limite) }}</strong></small>
                                    @else
                                        <small class="text-muted">(Sin fecha)</small>
                                    @endif
                                </td>
                                <td class="m-0 p-1">
                                    <div class="d-flex">
                                        <button class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal"
                                            data-bs-target="#basicModal"
                                            wire:click="editarConvenio({{ $convenio->id }})">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger shadow btn-xs sharp"
                                            wire:click="confirmarEliminacion('{{ $convenio->id }}')">
                                            <i class="fa fa-trash"></i>
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


    {{-- modal crear/editar  --}}
    <div class="modal fade" id="basicModal" style="display: none;" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document" wire:ignore.self>
            <div class="modal-content" wire:ignore.self>
                <div class="modal-header">
                    <h5 class="modal-title">{{ $is_editing ? 'Editar' : 'Nuevo' }} Convenio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetearCampos">
                </div>
                <div class="modal-body">
                    @php
                        $hoy = \Carbon\Carbon::now()->toDateString();
                    @endphp
                    <x-input-create-defer :lista="[
                        'Nombre Convenio' => ['nombre_convenio', 'text'],
                        'Valor' => ['valor_descuento', 'text', '(opcional)'],
                        'Fecha Limite' => ['fecha_limite', 'date', '(opcional)', $hoy],
                    ]">
                        <x-slot name="custominput" :posicion="2">

                            <div class="mb-3 row">
                                <div class="col-4">
                                    Tipo
                                </div>
                                <div class="col-8">
                                    <div>
                                        <select name=""
                                            class="form-control default-select bordeado wide mb-3 @error($tipo_descuento) is-invalid @enderror"
                                            id="" name="" wire:model="tipo_descuento"
                                            style="margin-bottom: 0px !important;">
                                            <option value="">--Elija una opción--</option>
                                            <option value="porcentaje">Porcentaje</option>
                                            <option value="fijo">Monto Fijo</option>
                                        </select>
                                        @error('tipo_descuento')
                                            <small class="error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </x-slot>
                        <x-slot name="otrosinputs">
                            <div class="mb-3 row" wire:ignore>
                                <div class="col-4">
                                    Productos
                                </div>
                                <div class="col-8">
                                    <div wire:ignore>
                                        <select name=""
                                            class="form-control default-select form-control wide mb-3 bordeado"
                                            id="productos_afectados" name="productos_afectados[]" multiple ="multiple">
                                        </select>
                                    </div>

                                </div>
                            </div>
                            @error('productos_afectados')
                                <small class="error d-flex align-items-end justify-content-end">{{ $message }}</small>
                            @enderror
                        </x-slot>
                        <x-slot name="nocerrarmodal">
                        </x-slot>

                    </x-input-create-defer>
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
            Livewire.on('iniciar-librerias', (productos, seleccionados) => {

                $('#productos_afectados').empty().trigger('change');

                // Destruye Select2 si ya existe
                if ($('#productos_afectados').hasClass('select2-hidden-accessible')) {
                    $('#productos_afectados').select2('destroy');
                }

                // Inicializa Select2
                $('#productos_afectados').select2({
                    width: '100%',
                    placeholder: "Seleccione productos",
                    dropdownParent: $('#basicModal'),
                    data: Object.entries(productos).map(([id, texto]) => ({
                        id: id,
                        text: texto
                    }))
                });

                if (seleccionados && seleccionados.length) {
                    $('#productos_afectados').val(seleccionados).trigger('change');
                }

                // Maneja cambios
                $('#productos_afectados').on('change', function(e) {
                    @this.set('productos_afectados', $(this).val());
                });
            });

            Livewire.on('sweet-detalles-productos-eliminar', (usuarios, convenioId) => {
                var usersAffected = usuarios;
                let htmlContent = '<p>¿Estás seguro de eliminar el convenio?</p>';

                if (Array.isArray(usersAffected) && usersAffected.length > 0) {
                    htmlContent += `
            <p>Al eliminar el convenio también eliminarás a los usuarios registrados en él:</p>
            <ul style="text-align: left; margin: 10px 0 0 20px;">
                ${usersAffected.map(user => `<li> - ${user}</li>`).join('')}
            </ul>
        `;
                } else {
                    htmlContent += '<p>Esta acción no se puede deshacer.</p>';
                }
                Swal.fire({
                    title: 'Confirmar eliminación',
                    html: htmlContent,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('eliminar-convenio', convenioId);
                    }
                });
            });

            Livewire.on('mostrar-notificacion', (event) => {
                Toast.fire({
                    title: event.mensaje,
                    icon: event.icono
                });

            });
            Livewire.on('cerrar-modal', (event) => {
                $('.modal').modal('hide');

            });

            // Manejar cierre del modal
            document.addEventListener('livewire:load', function() {
                $('#basicModal').on('hidden.bs.modal', function() {
                    @this.resetearCampos();
                });
            });
        </script>
    @endpush

</div>
