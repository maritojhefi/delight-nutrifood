<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                        <h4 class="card-title">Lista de Productos</h4>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-0">
                        <div class="input-group" style="max-height: 35px !important">
                            <button class="btn btn-secondary" type="button">Categoría</button>
                            <select name="" id="" class="form-control"
                                wire:model.lazy="categoria_select">
                                <option value="">Todos</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <input type="text" class="form-control form-control-sm bordeado" style="height: 30px"
                            placeholder="Buscar producto" wire:model.debounce.750ms="buscar">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md m-0 p-0 letra12">
                        <thead class="m-0 p-0">
                            <tr class="m-0 p-0">
                                <th class="m-0 p-0"><strong>Nombre</strong></th>
                                <th class="m-0 p-0  "><strong>Stock</strong></th>
                                <th class="m-0 p-0"><strong>Precio</strong></th>
                                <th class="m-0 p-0  "><strong>Estado</strong></th>
                                <th class="m-0 p-0  "><strong>Contable</strong></th>

                                <th class="m-0 p-0  "><strong>Subcategoria</strong></th>
                                <th class="m-0 p-0 "><strong>Unidad</strong></th>
                                <th class="m-0 p-0 "><strong>Puntos</strong></th>
                                {{-- <th class="m-0 p-0"><strong>Codigo Barra</strong></th> --}}
                                <th class="m-0 p-0"><strong>Acciones</strong></th>
                            </tr>
                        </thead>
                        <tbody class="m-0 p-0">
                            @foreach ($productos as $item)
                                <tr>
                                    <td class="m-0 p-1">
                                        <div class="d-flex align-items-center">
                                            @if ($item->imagen)
                                                <img src="{{ asset($item->pathAttachment()) }}"
                                                    class="rounded-lg me-2  d-sm-block" width="24"
                                                alt="">@else<img src="{{ asset('delight_logo.jpg') }}"
                                                    class="rounded-lg me-2  d-sm-block" width="24" alt="">
                                            @endif
                                            <strong class="">{{ $item->nombre }}</strong>
                                        </div>
                                    </td>
                                    <td class="m-0 p-1  d-sm"><span class="w-space-no">
                                            @if ($item->contable)
                                                <button class="badge badge-outline-info badge-xxs"
                                                    data-bs-toggle="modal" data-bs-target="#modalStock"
                                                    wire:click="verStock({{ $item->id }})">Stock:{{ $item->stock_actual }}</button>
                                            @else
                                                <span class="">No aplica</span>
                                            @endif

                                        </span>
                                    </td>
                                    @if ($item->descuento)
                                        <td class="m-0 p-1 text-truncate fw-bold">
                                            <del class=" text-danger">{{ $item->precio }}</del> 
                                            <span class="">{{ $item->descuento }} Bs</span>
                                        </td>
                                    @else
                                        <td class="m-0 p-1 text-truncate fw-bold"><span
                                                class="">{{ $item->precio }}
                                                Bs</span></td>
                                    @endif
                                    <td class="m-0 p-1  d-md"><a href="#"
                                            wire:click="cambiarestado('{{ $item->id }}')">
                                            <div class="d-flex align-items-center"><i
                                                    class="fa fa-circle text-{{ $item->estado == 'activo' ? 'success' : 'danger' }} me-1"></i>
                                                {{ $item->estado }}</div>
                                        </a></td>
                                    <td class="m-0 p-1  d-lg"><a href="#"
                                            wire:click="cambiarcontable('{{ $item->id }}')"><span
                                                class="badge badge-{{ $item->contable == 0 ? 'warning' : 'success' }}">{{ $item->contable == 0 ? 'NO' : 'SI' }}</span></a>
                                    </td>

                                    <td class="m-0 p-1  d-lg"><span
                                            class="">{{ $item->subcategoria->nombre }}</span></td>
                                    <td class="m-0 p-1 "><span class="w-space-no">{{ $item->medicion }}</span></td>
                                    <td class="m-0 p-1 "><span class="w-space-no">{{ $item->puntos }}</span></td>

                                    {{-- @if ($item->codigoBarra)
                                        <td class="m-0 p-1"><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->codigoBarra, 'C39+', 1, 33) }}"
                                                alt=""></td>
                                    @else
                                        <td class="m-0 p-1"><span class="badge light badge-danger">Sin codigo</span></td>
                                    @endif --}}




                                    <td class="m-0 p-1">
                                        <div class="d-flex flex-column flex-sm-row">
                                            <a href="#"
                                                class="btn btn-primary shadow btn-xs sharp me-1 mb-1 mb-sm-0"
                                                data-bs-toggle="modal" data-bs-target="#modalEditar"
                                                wire:click="editarProducto({{ $item->id }})"><i
                                                    class="fa fa-pencil"></i></a>
                                            <a href="#" class="btn btn-danger shadow btn-xs sharp"
                                                onclick="confirmarEliminacion('{{ $item->id }}', '{{ $item->nombre }}')"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mx-auto">
                <div class="col-12 col-md-8 mb-2 mb-md-0">
                    {{ $productos->links() }}
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <small class="text-muted">Mostrando {{ $productos->count() }} de {{ $productos->total() }}
                        registros</small>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                @isset($subcategorias)
                    <div class="modal-body">

                        <x-input-create-custom-function funcion="actualizarProducto" boton="Actualizar" :lista="[
                            'Nombre' => ['nombre', 'text'],
                            'Precio' => ['precio', 'number'],
                            'Descuento' => ['descuento', 'number', 'Precio que se cobrara'],
                            'Detalle' => ['detalle', 'textarea'],
                            'Puntos' => ['puntos', 'number'],
                            'Imagen' => ['imagen', 'file'],
                        ]">
                            <x-slot name="otrosinputs">
                                <div class="mb-3 row">
                                    <select name=""
                                        class="form-control @error($subcategoria_id) is-invalid @enderror"
                                        wire:model="subcategoria_id" id="">

                                        <option>Seleccione subcategoria ({{ $subcategorias->count() }})</option>
                                        @foreach ($subcategorias as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </x-slot>
                            @if ($imagen)
                                <img src="{{ $imagen->temporaryUrl() }}" class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </x-input-create-custom-function>
                    </div>
                @endisset


            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalStock">
        <div class="modal-dialog">

            <div class="modal-content">
                <center wire:loading>
                    <div class="spinner-border mb-3 mt-3" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </center>
                <div wire:loading.remove class="modal-header">
                    @isset($productoSeleccionado)
                        <h5 class="modal-title">{{ $productoSeleccionado->nombre }}</h5>
                    @endisset
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div wire:loading.remove class="modal-body">
                    @isset($productoSeleccionado)
                        @if ($productoSeleccionado->getStockDetallado()->count() != 0)
                            @foreach ($productoSeleccionado->getStockDetallado() as $stock)
                                <div class="card overflow-hidden bg-image-2 bg-secondary my-1">
                                    <div class="card-header  border-0 m-0 py-1">
                                        <div class="">
                                            <strong class="mb-2 letra12 text-white">
                                                {{ $stock->nombre }} </strong> -
                                            <small class="text-white letra12">Vence:
                                                {{ date('d-m-Y', strtotime($stock->pivot->fecha_venc)) }}</small>

                                            <h3 class="mb-0 fs-24  text-white">
                                                Cantidad restante: {{ $stock->pivot->cantidad }}</h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <hr>
                            <center class="letra14"><strong>Stock total :
                                    {{ $productoSeleccionado->stock_actual }}</strong></center>
                        @else
                            <div class="text-center"><span class="text-muted text-center"> No hay registro de stock para
                                    este producto</span></div>
                        @endif
                    @endisset
                </div>
                @isset($productoSeleccionado)
                    <div wire:loading.remove class="modal-footer">
                        <div class="row">
                            <div class="col-4">
                                <input type="number" class="form-control p-1 bordeado" style="height: 30px"
                                    wire:model.defer="cantidadStock" placeholder="Cantidad">
                                @error('cantidadStock')
                                    <label class="text-danger letra10">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="col-4">
                                <input type="date" class="form-control p-1 bordeado" style="height: 30px"
                                    wire:model.defer="fechaStock" placeholder="Vencimiento">
                                @error('fechaStock')
                                    <label class="text-danger letra10">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="col-4">
                                <button wire:click="guardarStock({{ $productoSeleccionado->id }})"
                                    class="btn btn-sm btn-success p-1">Agregar stock</button>
                            </div>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>



</div>
@push('scripts')
    <script>
        function confirmarEliminacion(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el producto "${nombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('eliminar', id);
                }
            });
        }
    </script>
@endpush
