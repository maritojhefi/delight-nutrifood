<div class="row">
    <div class="col-xl-4 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nuevo Usuario</h4>
                </div>
                <div class="card-body">
                    <div class="">
                        <x-input-create :lista="[
                            'Nombre' => ['name', 'text'],
                            'Correo' => ['email', 'email'],
                            'Telefono' => ['telf', 'number'],
                            'Codigo Pais' => ['codigo_pais', 'text'],
                            'Nacimiento' => ['cumpleano', 'date', '(opcional)'],
                            'Direccion' => ['direccion', 'text', '(opcional)'],
                            'Contraseña' => ['password', 'password'],
                        ]">
                            <x-slot name="otrosinputs">

                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Rol</label>
                                    <div class="col-sm-9">
                                        <select wire:model="rol" class="form-control @error($rol)is-invalid @enderror">
                                            <option class="dropdown-item" aria-labelledby="dropdownMenuButton">
                                                Seleccione una opcion</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->id }}" class="dropdown-item"
                                                    aria-labelledby="dropdownMenuButton">{{ $rol->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </x-slot>
                        </x-input-create>

                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="col-xl-8 col-lg-12 col-xxl-7 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Listado de Usuarios</h5>
                    <input type="text" class="form-control" placeholder="Buscar Usuarios"
                        wire:model.debounce.500ms="searchUser">
                    <div>
                        <button wire:click="descargarExcel" class="btn btn-success btn-xxs"><i class="fa fa-file"></i>
                            Descargar Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Telefono</strong></th>
                                    <th><strong>Rol</strong></th>
                                    <th><strong></strong></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $item)
                                    <tr>

                                        <td>
                                            <div class="d-flex align-items-center"><strong>{{ $item->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center"><strong>{{ $item->telf }}</strong>
                                            </div>
                                        </td>
                                        <td><span class="w-space-no">{{ $item->role->nombre }}</span></td>

                                        <td>
                                            <textarea name="" style="width: 10px;height: 0px;" id="copiar{{ $item->id }}">{{GlobalHelper::getValorAtributoSetting('url_web')}}/login/withid/{{ Crypt::encryptString($item->id) }}</textarea>
                                        </td>

                                        <td>
                                            <div class="d-flex">
                                                <a href="#" wire:click="copiarUrlNfc('{{$item->name}}')"
                                                    onclick="copiarTextoPortapapeles('{{ route('usuario.reconocer-usuario-nfc', Crypt::encrypt($item->id)) }}')"
                                                    class="btn btn-secondary shadow btn-xs sharp me-1"><i
                                                        class="flaticon-381-id-card"></i></a>
                                                <a href="#" wire:click="copiarTexto('{{ $item->id }}')"
                                                    class="btn btn-info shadow btn-xs sharp me-1"><i
                                                        class="fa fa-copy"></i></a>
                                                <a href="#" wire:click="edit({{ $item->id }})"
                                                    data-bs-toggle="modal" data-bs-target="#modalEdit"
                                                    class="btn btn-primary shadow btn-xs sharp me-1"><i
                                                        class="fa fa-pencil"></i></a>
                                                <a href="#" class="btn btn-danger shadow btn-xs sharp"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modaldelete{{ $item->id }}"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
                                        aria-hidden="true" id="modaldelete{{ $item->id }}">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Esta seguro?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
                                                <div class="modal-body">Eliminando <strong>{{ $item->name }}</strong>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger btn-sm light"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-dismiss="modal"
                                                        wire:click="eliminar('{{ $item->id }}')">Aceptar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row  mx-auto table-responsive">
                    <div class="col">{{ $usuarios->links() }}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{ $usuarios->count() }} de {{ $usuarios->total() }} registros</div>
                </div>
            </div>

        </div>
    </div>

    <div wire:ignore.self class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true"
        id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editando Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div wire:loading>
                    <div class="spinner-border text-light d-block mx-auto m-3" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div class="modal-body" wire:loading.remove wire:target="edit()">
                    <x-input-create-custom-function funcion="update" boton="Actualizar" :lista="[
                        'Nombre' => ['nameE', 'text'],
                        'Correo' => ['emailE', 'email'],
                        'Telefono' => ['telfE', 'number'],
                        'Codigo Pais' => ['codigo_paisE', 'text'],
                        'Nacimiento' => ['cumpleanoE', 'date'],
                        'Direccion' => ['direccionE', 'text'],
                        'Contraseña' => ['passwordE', 'password'],
                    ]">
                        @slot('otrosinputs')
                            <select wire:model="rolE" class="form-control mb-3 @error($rolE)is-invalid @enderror">
                                <option class="dropdown-item" aria-labelledby="dropdownMenuButton">
                                    Seleccione un rol</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}" {{ $rolE == $rol->id ? 'selected' : '' }}
                                        class="dropdown-item" aria-labelledby="dropdownMenuButton">{{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        @endslot
                    </x-input-create-custom-function>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copiarTextoPortapapeles(texto) {
                // Crear un elemento de texto temporal
                const tempInput = document.createElement('textarea');
                tempInput.value = texto; // Asignar el texto a copiar
                document.body.appendChild(tempInput); // Agregar el elemento al DOM
                tempInput.select(); // Seleccionar el texto
                tempInput.setSelectionRange(0, 99999); // Para dispositivos móviles
                document.execCommand('copy'); // Copiar al portapapeles
                document.body.removeChild(tempInput); // Eliminar el elemento temporal
                // alert('Texto copiado al portapapeles: ' + texto);
            }
        </script>
    @endpush

</div>
