<div class="row">
    @empty($productoSeleccionado)
        <div class="col-xl-6 col-lg-12 col-xxl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Creando nuevo producto</h4>
                </div>
                <div class="card-body">
                    <div class="">

                        <x-input-create :lista="[
                            'Nombre' => ['nombre', 'text'],
                            'Precio' => ['precio', 'number'],
                            'Detalle' => ['detalle', 'textarea'],
                            'Imagen' => ['imagen', 'file'],
                            'Descuento' => ['descuento', 'number', 'Opcional'],
                            'Puntos' => ['puntos', 'number', 'Opcional'],
                        ]">

                            @if ($imagen)
                                <img src="{{ $imagen->temporaryUrl() }}" class="w-100 border-radius-lg shadow-sm">
                            @endif
                            <x-slot name="otrosinputs">

                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Categoria</label>
                                    <div class="col-sm-9">
                                        <select wire:model="cat" class="form-control @error($cat)is-invalid @enderror">
                                            <option class="dropdown-item" aria-labelledby="dropdownMenuButton">Seleccione
                                                una opcion</option>
                                            @foreach ($subcategorias as $cat)
                                                <option value="{{ $cat->id }}" class="dropdown-item"
                                                    aria-labelledby="dropdownMenuButton">{{ $cat->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Medicion</label>
                                    <div class="col-sm-9">
                                        <select wire:model="medicion"
                                            class="form-control @error($medicion)is-invalid @enderror">


                                            <option value="unidad" class="dropdown-item"
                                                aria-labelledby="dropdownMenuButton">Unidades</option>
                                            <option value="gramo" class="dropdown-item"
                                                aria-labelledby="dropdownMenuButton">Gramos</option>



                                        </select>
                                    </div>
                                </div>
                            </x-slot>
                        </x-input-create>

                    </div>
                </div>
            </div>
        </div>
    @endempty
    @isset($productoSeleccionado)
        <div class="col-xl-6 col-lg-12 col-xxl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Agregando stock a {{ $productoSeleccionado->nombre }} <a href="#"
                            wire:click="resetProducto"><span class="badge badge-xs badge-danger pt-1">X</span></a></h4>
                </div>
                <div class="card-body">
                    <x-input-create-custom-function funcion="addStock" boton="Agregar Stock" :lista="[
                        'Cantidad' => ['cantidad', 'number'],
                        'Fecha' => ['fecha', 'date'],
                    ]">
                        <x-slot name="otrosinputs">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Sucursal</label>
                                <div class="col-sm-8">
                                    <div class=" input-group">
                                        <select name=""
                                            class="form-control  @error('sucursalSeleccionada') is-invalid @enderror"
                                            id="" wire:model="sucursalSeleccionada">
                                            @foreach ($sucursales as $sucur)
                                                <option value="{{ $sucur->id }}">{{ $sucur->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('sucursalSeleccionada')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </x-slot>
                    </x-input-create-custom-function>
                </div>
            </div>
        </div>
    @endisset
    {{-- <div class="col-xl-6 col-lg-12 col-xxl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Productos recientes</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                    @foreach ($productos as $producto)
                    <a href="#" wire:click="seleccionarProducto({{$producto->id}})">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">{{$producto->nombre}}</h6>
                                <small class="text-muted">{{$producto->subcategoria->nombre}}</small>
                            </div>
                            <span class="text-muted">{{$producto->precio}} Bs</span>
                        </li>
                    </a>
                    
                    @endforeach
                </ul>
                </div>
            </div>
        </div> --}}
</div>
