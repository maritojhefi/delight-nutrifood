<div class="row">
    <div class="col-xl-6">
        <div class="card bordeado">
            <div class="card-header d-block">
                <h4 class="card-title">Dias de la semana...
                    <button wire:click="cambiarMenu"
                        class="btn btn-xs p-1 btn-{{ $switcher->activo ? 'success' : 'danger' }}">{{ $switcher->activo ? 'Menu activo' : 'Menu bloqueado' }}
                        <small>(cambiar)</small></button>
                </h4>

                <p class="m-0 subtitle">Seleccione un dia y personalize los productos dentro de ella</p>
                @isset($seleccionado)
                    <button class="btn btn-xs btn-warning" wire:click="cambiarAFoto">Cambiar foto semanal</button>
                @endisset
            </div>
            <div class="card-body">


                @foreach ($almuerzos as $item)
                    <a href="#" wire:click="editar('{{ $item->id }}')">
                        <div
                            class="alert alert-primary my-2 p-2 @isset($seleccionado) {{ $item->id == $seleccionado->id ? 'solid' : '' }} @endisset alert-dismissible fade show">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                class="me-2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                <line x1="15" y1="9" x2="15.01" y2="9"></line>
                            </svg>
                            <strong>{{ $item->dia }}</strong>

                            </button>
                        </div>
                    </a>
                @endforeach



            </div>
        </div>
    </div>
    @empty($seleccionado)
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3 row">
                        <div class="col-6 col-sm-6">

                            <label for="Imagen" class="btn btn-danger btn-sm btn-rounded">Subir Foto Semanal</label>
                            <div class="col-sm-8">
                                <input id="Imagen" type="file" wire:model="imagen" class=" form-control "
                                    style="display:none">
                            </div>
                            <div class="avatar avatar-xl position-relative mt-3 border  ">
                                @if ($imagen)
                                    <img src="{{ $imagen->temporaryUrl() }}" class="w-100 border-radius-lg shadow-sm">
                                @endif
                            </div>
                            <div wire:loading="" wire:target="imagen" class="mb-3 row">
                                Cargando...
                            </div>
                        </div>


                        <div class="col-6 col-sm-6">

                            <button class="btn btn-success" {{ $imagen ? '' : 'disabled' }}
                                wire:click="guardarFoto">Guardar</button>
                            <div class="avatar avatar-xl position-relative mt-3 border  ">

                                <img src="{{ $almuerzos[0]->pathFoto }}"
                                    class="w-100 border-radius-lg shadow-sm">
                                Foto actual
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endempty

    <div class="col-xl-6">
        @isset($seleccionado)
            <div class="card bordeado">

                <div class="card-header d-block letra14">
                    <h4 class="card-title">Editando dia <strong> {{ $seleccionado->dia }} <button
                                wire:click="cambiarEstadoDia({{ $seleccionado->id }})"
                                class="btn btn-xxs py-1 btn-{{ $seleccionado->estado_dia ? 'success' : 'danger' }}">{{ $seleccionado->estado_dia ? 'Activo' : 'Inactivo' }}
                                <i class="flaticon-075-reload"></i></button></strong>
                    </h4>

                </div>
                @if ($seleccionado->estado_dia)
                    <div class="card-body py-0 m-0 letra12">
                        <div class="row">
                            <hr>
                            <center><strong>Segundos</strong></center>
                            <div class="mb-3 col-6">
                                <label class="form-label"><strong>Ejecutivo</strong>
                                    <input type="checkbox" class="form-check-input my-0 ms-2 p-0"
                                        wire:model="ejecutivo_estado">
                                </label>
                                <input type="text" {{ $ejecutivo_estado ? '' : 'disabled' }}
                                    class="form-control bordeado input-default  @error('ejecutivo') is-invalid @enderror"
                                    wire:model.lazy="ejecutivo" style="height: 30px">
                            </div>
                            <div class="mb-3 col-6">
                                <div class="form-check pt-4 custom-checkbox checkbox-info">
                                    <input type="checkbox" class="form-check-input" wire:model="ejecutivo_tiene_carbo"
                                        id="checkejecutivo" required="">
                                    <label
                                        class="form-check-label {{ $ejecutivo_tiene_carbo ? 'text-success' : 'text-danger' }}"
                                        for="checkejecutivo">{{ $ejecutivo_tiene_carbo ? 'Con ' : 'Sin ' }}
                                        carbohidrato</label>
                                </div>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label"><strong>Dieta</strong>
                                    <input type="checkbox" class="form-check-input my-0 ms-2 p-0" wire:model="dieta_estado">
                                </label>
                                <input type="text" {{ $dieta_estado ? '' : 'disabled' }}
                                    class="form-control bordeado input-default  @error('dieta') is-invalid @enderror"
                                    wire:model.lazy="dieta" style="height: 30px">
                            </div>
                            <div class="mb-3 col-6">
                                <div class="form-check pt-4 custom-checkbox checkbox-info">
                                    <input type="checkbox" class="form-check-input" wire:model="dieta_tiene_carbo"
                                        id="checkdieta" required="">
                                    <label
                                        class="form-check-label {{ $dieta_tiene_carbo ? 'text-success' : 'text-danger' }}"
                                        for="checkdieta">{{ $dieta_tiene_carbo ? 'Con ' : 'Sin ' }} carbohidrato</label>
                                </div>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label"><strong>Vegetariano</strong>
                                    <input type="checkbox" class="form-check-input my-0 ms-2 p-0"
                                        wire:model="vegetariano_estado">
                                </label>
                                <input type="text"  {{ $vegetariano_estado ? '' : 'disabled' }}
                                    class="form-control bordeado input-default  @error('vegetariano') is-invalid @enderror"
                                    wire:model.lazy="vegetariano" style="height: 30px">
                            </div>
                            <div class="mb-3 col-6">
                                <div class="form-check pt-4 custom-checkbox checkbox-info">
                                    <input type="checkbox" class="form-check-input" wire:model="vegetariano_tiene_carbo"
                                        id="checkvegetariano" required="">
                                    <label
                                        class="form-check-label {{ $vegetariano_tiene_carbo ? 'text-success' : 'text-danger' }}"
                                        for="checkvegetariano">{{ $vegetariano_tiene_carbo ? 'Con ' : 'Sin ' }}
                                        carbohidrato</label>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3 col-6">
                                <label class="form-label"><strong>Carbohidrato 1</strong>
                                    <input type="checkbox" class="form-check-input my-0 ms-2 p-0"
                                        wire:model="carbohidrato_1_estado">
                                </label>
                                <input type="text" {{ $carbohidrato_1_estado ? '' : 'disabled' }}
                                    class="form-control input-default bordeado @error('carbohidrato_1') is-invalid @enderror"
                                    wire:model.lazy="carbohidrato_1" style="height: 30px">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Sopa</label>
                                <input type="text"
                                    class="form-control bordeado input-default  @error('sopa') is-invalid @enderror"
                                    wire:model.lazy="sopa" style="height: 30px">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label"><strong>Carbohidrato 2</strong>
                                    <input type="checkbox" class="form-check-input my-0 ms-2 p-0"
                                        wire:model="carbohidrato_2_estado">
                                </label>
                                <input type="text" {{ $carbohidrato_2_estado ? '' : 'disabled' }}
                                    class="form-control input-default bordeado  @error('carbohidrato_2') is-invalid @enderror"
                                    wire:model.lazy="carbohidrato_2" style="height: 30px">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Ensalada</label>
                                <input type="text"
                                    class="form-control bordeado input-default  @error('ensalada') is-invalid @enderror"
                                    wire:model.lazy="ensalada" style="height: 30px">
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label"><strong>Carbohidrato 3</strong>
                                    <input type="checkbox" class="form-check-input my-0 ms-2 p-0"
                                        wire:model="carbohidrato_3_estado">
                                </label>
                                <input type="text" {{ $carbohidrato_3_estado ? '' : 'disabled' }}
                                    class="form-control input-default bordeado  @error('carbohidrato_3') is-invalid @enderror"
                                    wire:model.lazy="carbohidrato_3" style="height: 30px">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Jugo/Mate</label>
                                <input type="text"
                                    class="form-control bordeado input-default @error('jugo') is-invalid @enderror"
                                    wire:model.lazy="jugo" placeholder="Jugo/Mate" style="height: 30px">
                            </div>
                        </div>

                        <button wire:click="actualizar" class="btn btn-xs p-2 mb-2 btn-primary">Guardar</button>

                    </div>
                @else
                    <div class="card-body mx-auto">
                        <span class="">Dia inactivo, no se mostrara a los clientes <i class="fa fa-ban"></i></span>
                    </div>
                @endif


            </div>
        @endisset
    </div>
</div>
