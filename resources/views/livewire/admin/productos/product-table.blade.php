<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row">
                <div class="col">
                    <h4 class="card-title">Lista de Productos</h4>
                </div>
                <div class="col"> <input type="text" class="form-control form-control-sm bordeado"
                        style="height: 30px" placeholder="Buscar" wire:model.debounce.750ms="buscar"></div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md m-0 p-0 letra12">
                        <thead class="m-0 p-0">
                            <tr class="m-0 p-0">
                                <th class="m-0 p-0"><strong>Nombre</strong></th>
                                <th class="m-0 p-0"><strong>Precio</strong></th>
                                <th class="m-0 p-0"><strong>Estado</strong></th>
                                <th class="m-0 p-0"><strong>Contable</strong></th>
                                <th class="m-0 p-0"><strong>Stock</strong></th>
                                <th class="m-0 p-0"><small>Subcategoria</small></th>
                                <th class="m-0 p-0"><strong>Medicion</strong></th>
                                <th class="m-0 p-0"><strong>Puntos</strong></th>
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
                                                <img src="{{ asset($item->pathAttachment()) }}" class="rounded-lg me-2"
                                                width="24" alt="">@else<img
                                                    src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}" class="rounded-lg me-2"
                                                    width="24" alt="">
                                            @endif
                                            <strong>{{ $item->nombre }}</strong>
                                        </div>
                                    </td>
                                    @if ($item->descuento)
                                        <td class="m-0 p-1"><del
                                                class="w-space-no badge light badge-danger">{{ $item->precio }}</del><span
                                                class="w-space-no badge light badge-success">{{ $item->descuento }}
                                                Bs</span></td>
                                    @else
                                        <td class="m-0 p-1"><span
                                                class="w-space-no badge light badge-success">{{ $item->precio }}
                                                Bs</span></td>
                                    @endif
                                    <td class="m-0 p-1"><a href="#"
                                            wire:click="cambiarestado('{{ $item->id }}')">
                                            <div class="d-flex align-items-center"><i
                                                    class="fa fa-circle text-{{ $item->estado == 'activo' ? 'success' : 'danger' }} me-1"></i>
                                                {{ $item->estado }}</div>
                                        </a></td>
                                    <td class="m-0 p-1"><a href="#"
                                            wire:click="cambiarcontable('{{ $item->id }}')"><span
                                                class="badge badge-{{ $item->contable == 0 ? 'warning' : 'success' }}">{{ $item->contable == 0 ? 'NO' : 'SI' }}</span></a>
                                    </td>
                                    <td class="m-0 p-1"><span class="w-space-no">
                                            <button class="btn btn-success light btn-xxs" data-bs-toggle="modal"
                                                data-bs-target="#modalStock"
                                                wire:click="verStock({{ $item->id }})">Ver stock</button>
                                        </span>
                                    </td>
                                    <td class="m-0 p-1"><span
                                            class="w-space-no">{{ $item->subcategoria->nombre }}</span></td>
                                    <td class="m-0 p-1"><span class="w-space-no">{{ $item->medicion }}</span></td>
                                    <td class="m-0 p-1"><span class="w-space-no">{{ $item->puntos }}</span></td>

                                    {{-- @if ($item->codigoBarra)
                                        <td class="m-0 p-1"><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->codigoBarra, 'C39+', 1, 33) }}"
                                                alt=""></td>
                                    @else
                                        <td class="m-0 p-1"><span class="badge light badge-danger">Sin codigo</span></td>
                                    @endif --}}




                                    <td class="m-0 p-1">
                                        <div class="d-flex">
                                            <a href="#" class="btn btn-primary shadow btn-xs sharp me-1"
                                                data-bs-toggle="modal" data-bs-target="#modalEditar"
                                                wire:click="editarProducto({{ $item->id }})"><i
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
                                            <div class="modal-body">Eliminando <strong>{{ $item->nombre }}</strong>
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
            <div class="row  mx-auto">
                <div class="col">{{ $productos->links() }}</div>
            </div>
            <div class="row  mx-auto">
                <div class="col">Mostrando {{ $productos->count() }} de {{ $productos->total() }} registros</div>
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
                        @php
                            $totalStock = 0;
                        @endphp
                        @if ($productoSeleccionado->sucursale->count() != 0)
                            @foreach ($productoSeleccionado->sucursale as $stock)
                                @php
                                    $totalStock += $stock->pivot->cantidad;
                                @endphp
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
                            <center class="letra14"><strong>Stock total : {{ $totalStock }}</strong></center>
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
