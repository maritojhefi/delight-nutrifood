<div class="row">
    <div class="col-xl-6" style="max-height:500px">
        <div class="card">
            <div class="card-header d-block">
                <h4 class="card-title">Dias de la semana... 
                    <button wire:click="cambiarMenu" class="btn btn-sm btn-{{$switcher->activo?'success':'danger'}}">{{$switcher->activo?'Menu activo':'Menu bloqueado'}}
                        <small>(cambiar)</small></button></h4>

                <p class="m-0 subtitle">Seleccione un dia y personalize los productos dentro de ella</p>
                @isset($seleccionado)
                    <button class="btn btn-xs btn-warning" wire:click="cambiarAFoto">Cambiar foto semanal</button>
                @endisset
            </div>
            <div class="card-body" style="max-height:400px;overflow-y: scroll;">


                @foreach ($almuerzos as $item)
                    <a href="#" wire:click="editar('{{ $item->id }}')">
                        <div
                            class="alert alert-primary @isset($seleccionado) {{ $item->id == $seleccionado->id ? 'solid' : '' }} @endisset alert-dismissible fade show">
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
            <div class="card" style="max-height:400px">
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

                                <img src="{{ asset('imagenes/almuerzo/' . $almuerzos[0]->foto) }}"
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
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Editando dia <strong> {{ $seleccionado->dia }}</strong></h4>

                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Sopa</label>
                        <input type="text" class="form-control input-default  @error('sopa') is-invalid @enderror"
                            wire:model.lazy="sopa">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ensalada</label>
                        <input type="text" class="form-control input-default  @error('ensalada') is-invalid @enderror"
                            wire:model.lazy="ensalada">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ejecutivo</label>
                        <input type="text" class="form-control input-default  @error('ejecutivo') is-invalid @enderror"
                            wire:model.lazy="ejecutivo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dieta</label>
                        <input type="text" class="form-control input-default  @error('dieta') is-invalid @enderror"
                            wire:model.lazy="dieta">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vegetariano</label>
                        <input type="text" class="form-control input-default  @error('vegetariano') is-invalid @enderror"
                            wire:model.lazy="vegetariano">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Carbohidrato 1</label>
                        <input type="text"
                            class="form-control input-default  @error('carbohidrato_1') is-invalid @enderror"
                            wire:model.lazy="carbohidrato_1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Carbohidrato 2</label>
                        <input type="text"
                            class="form-control input-default  @error('carbohidrato_2') is-invalid @enderror"
                            wire:model.lazy="carbohidrato_2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Carbohidrato 3</label>
                        <input type="text"
                            class="form-control input-default  @error('carbohidrato_3') is-invalid @enderror"
                            wire:model.lazy="carbohidrato_3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jugo/Mate</label>
                        <input type="text" class="form-control input-default @error('jugo') is-invalid @enderror"
                            wire:model.lazy="jugo" placeholder="Jugo/Mate">
                    </div>
                    <button wire:click="actualizar" class="btn btn-primary">Guardar</button>

                </div>

            </div>
        @endisset
    </div>
</div>
