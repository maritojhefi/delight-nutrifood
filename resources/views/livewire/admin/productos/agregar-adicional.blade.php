<div class="row">
    <x-card-col4>
        <div class="card-body" >
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
                        <div class="row align-items-center mt-2">
                            <div class="col-6">
                                <li>{{ $item->nombre }}</li>
                            </div>
                            <div class="col-6">

                                <a href="javascript:void(0);" wire:click="eliminar('{{ $item->id }}')"
                                    class="btn btn-danger btn-xxs px-4">Borrar</a>
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
                                class="badge light badge-success m-2">{{ $item->nombre }}({{ $item->precio }} Bs)</span></a>
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
