<div class="row">
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title" wire:loading.remove>Busque una subcategoria</h4>
            <h4 class="card-intro-title" wire:loading>Buscando...</h4>
            <input type="text" class="form-control" wire:model.debounce.500ms="searchSub">
            <div style="overflow-y: scroll;height:400px;">
                @foreach ($subcategorias as $item)
                    <div class="media pb-3 border-bottom mb-3 align-items-center">
                        <a href="#" wire:click="seleccionado('{{ $item->id }}')">
                            <div class="media-body">
                                <h6 class="fs-16 mb-0">{{ $item->nombre }}</h6>
                                <div class="d-flex">
                                    <span class="fs-14 text-nowrap">Adicionales</span>
                                    <span class="fs-14 me-auto text-secondary"><i
                                            class="fa fa-ticket me-1"></i>{{ $item->adicionales->count() }}</span>

                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </x-card-col4>
    @isset($subcategoria)
        <x-card-col4>
            <div class="card-body" id="field-you-want-to-focus">
                <h4 class="card-intro-title">Lista de adicionales para {{ $subcategoria->nombre }}
                    ({{ $subcategoria->adicionales->count() }})</h4>
                <ul class="index-chart-point-list">
                    @foreach ($subcategoria->adicionales as $item)
                        <div class="row align-items-center">
                            <div class="col-12">
                                <li><small>{{ $item->nombre }}</small></li>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-9">
                                        @if ($item->pivot->id_grupo != null)
                                            @php
                                                $grupito = $grupos->where('id', $item->pivot->id_grupo)->first();
                                            @endphp
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#modalGrupos{{ $item->id }}"
                                                class="badge badge-info badge-sm  px-4">{{ $grupito->nombre_grupo }} <i
                                                    class="fa fa-edit"></i></a>
                                        @else
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#modalGrupos{{ $item->id }}"
                                                class="badge badge-success badge-sm  px-4">Añadir <i
                                                    class="fa fa-plus"></i></a>
                                        @endif
                                    </div>
                                    <div class="col-3"><a href="javascript:void(0);"
                                            wire:click="eliminar('{{ $item->id }}')"
                                            class="badge badge-danger badge-sm px-4"><i class="fa fa-trash"></i></a></div>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="modal fade" wire:ignore.self id="modalGrupos{{ $item->id }}" style="display: none;"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Agregar <span
                                                class="badge badge-dark">{{ $item->nombre }}</span> a grupo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="row">
                                                <x-input-create :lista="[
                                                    'Nombre Grupo' => ['nombreGrupo', 'text'],
                                                    'Adicionales Maximos' => ['maximoGrupo', 'number'],
                                                ]">

                                                </x-input-create>
                                            </div>

                                        </div>
                                        <div class="">
                                            Seleccione grupo:
                                            <ul class="list-group ">
                                                @foreach ($grupos as $grupo)
                                                    <a href="#"
                                                        wire:click="anadirGrupo({{ $item->id }},{{ $grupo->id }})">
                                                        <li
                                                            class="list-group-item d-flex justify-content-between lh-condensed {{ $grupo->id == $item->pivot->id_grupo ? 'active' : '' }}">

                                                            <div>
                                                                <h6 class="my-0">{{ $grupo->nombre_grupo }}</h6>
                                                                <small class="text-muted">Cantidad maxima:
                                                                    {{ $grupo->max }}</small>
                                                            </div>

                                                            <span class="text-muted"><i class="fa fa-plus"></i>
                                                                Añadir</span>

                                                        </li>
                                                    </a>
                                                @endforeach

                                            </ul>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </ul>
            </div>


        </x-card-col4>
        <x-card-col4>
            <div class="card-body">
                <h4 class="card-intro-title">Agregar nuevo</h4>
                <input type="text" class="form-control" wire:model.debounce.750ms="search"
                    placeholder="Busque adicionales">
                <ul class="index-chart-point-list">
                    @foreach ($adicionales as $item)
                        <a href="#" wire:click="agregar('{{ $item->id }}')"><span
                                class="badge light badge-success m-2"><i class="fa fa-plus"></i>
                                {{ $item->nombre }}({{ $item->precio }} Bs) </span></a>
                    @endforeach

                </ul>
            </div>
        </x-card-col4>
    @endisset
    @push('scripts')
        <script>
            window.livewire.on('change-focus-other-field', function() {

                document.getElementById("field-you-want-to-focus").focus();
                console.log('ya esta');
            });
        </script>
    @endpush
</div>
