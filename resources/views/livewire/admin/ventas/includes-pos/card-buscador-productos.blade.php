<x-card-col tamano="6">
    <input type="search" id="input-buscador" wire:model.debounce.750ms="search"
        style="border: 2px solid #20c996b3;height:30px" class="form-control mt-2"
        placeholder="Busca productos y categorias">
    <ul class=" m-1" role="tablist"
        style="white-space: nowrap; overflow-x: auto; overflow-y: hidden; overflow-x: hidden; display: flex; flex-wrap: nowrap; -webkit-overflow-scrolling: touch;">
        <div class="nav-container" style="overflow-x: auto; white-space: nowrap;">
            @foreach ($subcategorias as $subcategoria)
                <a href="#"
                    class="nav-item m-0 p-0 mb-2 letra14 popover-container {{ $subcategoriaSeleccionada == $subcategoria->id ? 'selected bg-primary text-white' : '' }}"
                    role="presentation"
                    style="border-style: solid;border-color:rgb(14, 178, 79);
                    border-width: 1px;border-radius:15px; display: inline-block;"
                    wire:click="seleccionarSubcategoria({{ $subcategoria->id }})"
                    data-subcategoria-id="{{ $subcategoria->id }}">
                    <span class="nav-link m-0 p-1 " style="font-size: 10px; white-space: nowrap;">
                        {{ Str::limit($subcategoria->nombre, 15) }}
                        <span class="popover-text">{{ $subcategoria->nombre }}</span>
                    </span>
                </a>
            @endforeach
        </div>




    </ul>

    <div class="row mt-2 p-2" style="max-height: 500px; overflow-y: auto;overflow-x: hidden;">
        <center style="font-size: 12px">Productos encontrados: {{ $productos->count() }}</center>
        @foreach ($productos as $item)
            @php
                $total = $item->stockTotal();
            @endphp

            <div class="card-body product-grid-card col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-3 m-0 p-1"
                style="border-style: solid;{{ $total == 0 && $item->contable == true ? 'border-color:red;' : 'border-color:rgb(14, 178, 79);' }}
                border-width: 2px;border-radius:15px">
                <div class="new-arrival-product m-0 p-0">
                    <div wire:click="adicionar('{{ $item->id }}')" class="new-arrivals-img-contnent mx-auto"
                        style="width: 100px;height:100px">
                        <img class="img-fluid rounded" src="{{ $item->pathAttachment() }}" alt="">
                    </div>
                    <div wire:click="adicionar('{{ $item->id }}')" class="new-arrival-content text-center mt-1">
                        <h4 class="p-0 m-0" style="font-size:10px">{{ Str::limit($item->nombre, 40) }}
                            @if ($item->puntos != 0 && $item->puntos != null)
                                <small class="">({{ $item->puntos }}pts)</small>
                            @endif
                        </h4>
                        @if ($item->descuento != 0)
                            <del class="discount" style="font-size:11px">{{ $item->precio }} Bs</del>
                            <span class="price" style="font-size:13px">{{ $item->descuento }} Bs</span>
                        @else
                            <span class="price" style="font-size:13px">{{ $item->precio }} Bs</span>
                        @endif

                    </div>
                    <div class="row ">
                        <div class="col-6">
                            <a href="#">
                                @switch($item->prioridad)
                                    @case(1)
                                        <span wire:click="cambiarPrioridad('{{ $item->id }}','2')"
                                            class="badge badge-xs light badge-dark"><i class="fa fa-high"></i>
                                            |</span>
                                    @break

                                    @case(2)
                                        <span wire:click="cambiarPrioridad('{{ $item->id }}','3')"
                                            class="badge badge-xs light badge-info">||</span>
                                    @break

                                    @case(3)
                                        <span wire:click="cambiarPrioridad('{{ $item->id }}','1')"
                                            class="badge badge-xs light badge-success">|||</span>
                                    @break

                                    @default
                                @endswitch
                            </a>
                        </div>
                        <div class="col-6">
                            @if ($item->contable == true)
                                <div class="float-end">
                                    <span
                                        class="badge {{ $total == 0 ? 'badge-danger text-white' : 'badge-outline-dark text-dark' }} badge-xs badge-pill p-1  letra14"
                                        style="line-height: 8px">{{ $total }}
                                    </span>
                                </div>
                            @else
                                <div class="float-end">
                                    <span class="badge badge-primary badge-xs badge-pill p-1"
                                        style="line-height: 8px"><i class="fa fa-check"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

</x-card-col>
