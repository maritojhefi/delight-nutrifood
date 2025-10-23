<ul class="list-group" style="overflow-y: auto;max-height:450px;overflow-x: hidden">

    @foreach ($productos as $item)
        @php
            $total = 0;
        @endphp

        @foreach ($item->sucursale->where('id', $cuenta->sucursale_id) as $relacion)
            @isset($relacion)
                @php
                    $total += $relacion->pivot->cantidad;
                @endphp
            @endisset
        @endforeach



        <a wire:key="{{ $loop->index }}" href="#"
            {{ $total == 0 && $item->contable == true ? 'disabled' : ' ' }}>
            <li class="list-group-item {{ $total == 0 && $item->contable == true ? '' : ' border border-primary' }}"
                wire:target="adicionar({{ $item->id }})" wire:loading.class="border-success"
                style="padding: 10px">
                @if ($total == 0 && $item->contable == true)
                    <del class=" text-muted"><small>{{ Str::limit($item->nombre, 40) }}</small> </del>
                @else
                    <div class="row" wire:click="adicionar('{{ $item->id }}')">
                        <div class="col-3"><img src="{{ $item->pathAttachment() }}"
                                alt="" class="me-3 rounded" height="40"></div>
                        <div class="col-9"><small>{{ Str::limit($item->nombre, 40) }}
                            </small><span class="spinner-border spinner-border-sm text-primary ml-2"
                                wire:loading wire:target="adicionar({{ $item->id }})" role="status"
                                aria-hidden="true"></span></div>
                    </div>
                @endif
                <small>
                    @if ($item->contable == true)
                        @if ($total == 0)
                            <span class="badge badge-xs light badge-danger mb-2">Agotado</span>
                        @else
                            <span wire:click="adicionar('{{ $item->id }}')"
                                class="badge badge-xs light badge-warning mb-2">Stock
                                :{{ $total }}</span>
                        @endif
                    @endif
                </small>
                <div class="row">

                    <div class="col-6">
                        @if ($item->descuento != 0)
                            <span wire:click="adicionar('{{ $item->id }}')"
                                class="badge badge-xs  badge-primary">{{ $item->descuento }}
                                Bs</span>
                            <del wire:click="adicionar('{{ $item->id }}')"
                                class="badge badge-xs  badge-danger">{{ $item->precio }} Bs</del>
                        @else
                            <span wire:click="adicionar('{{ $item->id }}')"
                                class="badge badge-xs  badge-warning">{{ $item->precio }}
                                Bs</span>
                        @endif
                    </div>
                    <div class="col-6">
                        @if ($item->puntos != 0 && $item->puntos != null)
                            <small class="">{{ $item->puntos }}pts</small>
                        @endif
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

                    </div>
                </div>
            </li>
        </a>
    @endforeach
</ul>