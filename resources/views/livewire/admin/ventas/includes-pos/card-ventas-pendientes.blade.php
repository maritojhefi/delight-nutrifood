<x-card-col tamano="3">
    <div class="mb-2">
        <center class="card-intro-title p-2 " style="font-size:15px">Ventas Pendientes</center>
        <div class="p-2" style="max-height: 450px; overflow-y: auto;">
            @foreach ($ventas as $item)
                @if (isset($cuenta) && $cuenta->id == $item->id)
                    <div wire:key="venta-pendiente-{{ $item->id }}"
                        class="alert mb-1  {{ $item->productos->count() > 0 ? 'border-success alert-success' : 'alert-danger border-danger' }}@isset($cuenta) {{ $item->id == $cuenta->id ? 'solid alert-alt bordeado-pulse' : '' }} @endisset p-1 px-2 pb-2"
                        style="line-height: 0px; cursor: pointer;" data-registro-venta-id="{{ $item->id }}">
                    @else
                        <div wire:key="venta-pendiente-{{ $item->id }}"
                            onclick="seleccionar('{{ $item->id }}')"
                            class="alert mb-1  {{ $item->productos->count() > 0 ? 'border-success alert-success' : 'alert-danger border-danger' }}@isset($cuenta) {{ $item->id == $cuenta->id ? 'solid alert-alt bordeado-pulse' : '' }} @endisset p-1 px-2 pb-2"
                            style="line-height: 0px; cursor: pointer;" data-registro-venta-id="{{ $item->id }}">
                @endif

                <a href="#" class="p-0 m-0" style="line-height: 20px">
                    {{-- <small class="letra10"> #{{ $item->id }}</small> --}}
                    <div class="d-flex justify-content-between">
                        @switch ($item->tipo_entrega)
                            @case ('mesa')
                                <small
                                    class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset"><i
                                        class="fa fa-table"></i> Mesa {{ $item->mesa->numero }}
                                </small>
                            @break

                            @case ('delivery')
                                <small
                                    class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset"><i
                                        class="fa fa-truck"></i> Delivery</small>
                            @break

                            @case ('recoger')
                                <small
                                    class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset"><i
                                        class="fa fa-bolt"></i> </small>
                            @break

                            @default
                                <small
                                    class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset"><i
                                        class="fa fa-bolt"></i>
                                </small>
                            @break
                        @endswitch
                        <div wire:loading wire:target="seleccionar({{ $item->id }})"
                            class="spinner-grow spinner-grow-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        @if ($item->totalItems() > 0)
                            <strong class="letra12">({{ $item->totalItems() }} items)</strong>
                        @endif
                        @if ($item->totalFinal() > 0)
                            <strong
                                class="float-end text-end @isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset ">{{ $item->totalFinal() }}Bs
                            </strong>
                        @endif

                        @if ($item->productos->count() == 0 && isset($cuenta) && $cuenta->id == $item->id)
                            <a href="#"
                                class="float-end badge badge-light color-white bg-white badge-pill py-1 px-2 text-danger"
                                wire:click="eliminar('{{ $item->id }}')">
                                <i class="fa fa-trash "></i>
                            </a>
                        @endif
                    </div>

                    @if ($item->pagado)
                        <span class="badge badge-dark badge-sm popover-container p-1">Pagado <span
                                class="popover-text">Esta venta se encuentra pagada</span></span>
                    @endif
                    @if ($item->cocina && !$item->despachado_cocina)
                        <span class="badge badge-info badge-sm popover-container py-1"><i class="fa fa-send"></i>
                            <span class="popover-text">Enviado a cocina</span></span>
                    @elseif($item->despachado_cocina)
                        <span class="badge badge-success badge-sm popover-container"><i class="fa fa-thumbs-up"></i>
                            <span class="popover-text">Venta despachada desde cocina</span></span>
                    @endif
                    @isset($item->usuario_manual)
                        <span class="p-0 m-0 letra14 fw-bold" style="font-size:13px;line-height: 10px"><i
                                class="fa fa-edit"></i>
                            {{ Str::limit($item->usuario_manual, 35) }}</sp>
                        @endisset

                        @isset($item->cliente)
                            <span class="p-0 m-0 letra14 fw-bold" style="font-size:13px;line-height: 10px"><i
                                    class="fa fa-user"></i>
                                {{ Str::limit($item->cliente->name, 35) }}</span>
                        @endisset

                        @if ($item->reservado_at)
                            @php
                                $now = \Carbon\Carbon::now();
                                $reservado = \Carbon\Carbon::parse($item->reservado_at);
                                $diffInSeconds = $now->diffInSeconds($reservado, false);

                                // Determinar el color
                                if ($diffInSeconds > 10800) {
                                    // 3 horas
                                    $colorClass = 'bg-dark';
                                } elseif ($diffInSeconds > 1800) {
                                    // 30 minutos
                                    $colorClass = 'bg-warning';
                                } else {
                                    $colorClass = 'bg-danger';
                                }

                                // Calcular tiempo
                                $hours = floor($diffInSeconds / 3600);
                                $minutes = floor(($diffInSeconds % 3600) / 60);
                                $seconds = $diffInSeconds % 60;
                            @endphp

                            <div class="mt-1">
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="fa fa-hourglass-half faa-flash animated"></i> Reserva:
                                </small>
                                <span class="badge badge-sm {{ $colorClass }}" id="timer-{{ $item->id }}"
                                    data-reservado="{{ $item->reservado_at }}"
                                    style="font-size: 10px; padding: 2px 6px;">
                                    @if ($diffInSeconds > 0)
                                        {{ $hours }}h {{ $minutes }}m {{ $seconds }}s
                                    @else
                                        Tiempo cumplido
                                    @endif
                                </span>
                            </div>
                        @endif

                </a>

        </div>
        @endforeach
    </div>
    <button class="btn light btn-xs btn-outline-warning p-1 px-2 m-0 mx-auto mt-2 w-100" data-bs-toggle="modal"
        data-bs-target="#modalSeleccionarMesa">
        VER TODAS LAS VENTAS
    </button>
    </div>
</x-card-col>

<!-- Modal Seleccionar Mesa -->
<div class="modal fade" id="modalSeleccionarMesa" wire:ignore.self tabindex="-1"
    aria-labelledby="modalSeleccionarMesaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="max-height: 100%;">

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 m-0 p-0 letra12 pe-2" style="max-height: 37.5rem; overflow-y: auto;">

                        @foreach ($ventas->where('tipo_entrega', '!=', 'mesa') as $item)
                            @switch($item->tipo_entrega)
                                @case('recoger')
                                    <div class="alert alert-primary solid" style="cursor: pointer;"
                                        wire:click="seleccionar('{{ $item->id }}')" data-bs-dismiss="modal">
                                        <span><i class="fa fa-bolt " style="font-size: 18px !important;"></i>
                                            {{ $item->cliente ? Str::limit($item->cliente->name, 25) : $item->usuario_manual }}
                                        </span>
                                        <br>
                                        <small class="letra10">{{ GlobalHelper::timeago($item->created_at) }}</small>
                                        <strong
                                            class="badge badge-sm light badge-primary ms-1 float-end">{{ $item->totalFinal() }}
                                            Bs</strong>

                                        @if ($item->reservado_at)
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $reservado = \Carbon\Carbon::parse($item->reservado_at);
                                                $diffInSeconds = $now->diffInSeconds($reservado, false);

                                                if ($diffInSeconds > 10800) {
                                                    // 3 horas
                                                    $colorClass = 'bg-dark';
                                                } elseif ($diffInSeconds > 1800) {
                                                    // 30 minutos
                                                    $colorClass = 'bg-warning';
                                                } else {
                                                    $colorClass = 'bg-danger';
                                                }

                                                $hours = floor($diffInSeconds / 3600);
                                                $minutes = floor(($diffInSeconds % 3600) / 60);
                                                $seconds = $diffInSeconds % 60;
                                            @endphp
                                            <br>
                                            <small style="font-size: 10px; color: #fff;">
                                                <i class="fa fa-hourglass-half faa-flash animated"></i> Reserva:
                                            </small>
                                            <span class="badge badge-sm {{ $colorClass }}"
                                                id="timer-modal-recoger-{{ $item->id }}"
                                                data-reservado="{{ $item->reservado_at }}"
                                                style="font-size: 9px; padding: 2px 5px;">
                                                @if ($diffInSeconds > 0)
                                                    {{ $hours }}h {{ $minutes }}m {{ $seconds }}s
                                                @else
                                                    Tiempo cumplido
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                @break

                                @case('delivery')
                                    <div class="alert alert-secondary solid" style="cursor: pointer;"
                                        wire:click="seleccionar('{{ $item->id }}')" data-bs-dismiss="modal">
                                        <span><i class="fa fa-truck " style="font-size: 18px !important;"></i>
                                            {{ $item->cliente ? Str::limit($item->cliente->name, 25) : $item->usuario_manual }}
                                        </span>
                                        <br>
                                        <small class="letra10">{{ GlobalHelper::timeago($item->created_at) }}</small>
                                        <strong
                                            class="badge badge-sm light badge-primary ms-1 float-end">{{ $item->totalFinal() }}
                                            Bs</strong>

                                        @if ($item->reservado_at)
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $reservado = \Carbon\Carbon::parse($item->reservado_at);
                                                $diffInSeconds = $now->diffInSeconds($reservado, false);

                                                if ($diffInSeconds > 10800) {
                                                    // 3 horas
                                                    $colorClass = 'bg-dark';
                                                } elseif ($diffInSeconds > 1800) {
                                                    // 30 minutos
                                                    $colorClass = 'bg-warning';
                                                } else {
                                                    $colorClass = 'bg-danger';
                                                }

                                                $hours = floor($diffInSeconds / 3600);
                                                $minutes = floor(($diffInSeconds % 3600) / 60);
                                                $seconds = $diffInSeconds % 60;
                                            @endphp
                                            <br>
                                            <small style="font-size: 10px; color: #fff;">
                                                <i class="fa fa-hourglass-half faa-flash animated"></i> Reserva:
                                            </small>
                                            <span class="badge badge-sm {{ $colorClass }}"
                                                id="timer-modal-delivery-{{ $item->id }}"
                                                data-reservado="{{ $item->reservado_at }}"
                                                style="font-size: 9px; padding: 2px 5px;">
                                                @if ($diffInSeconds > 0)
                                                    {{ $hours }}h {{ $minutes }}m {{ $seconds }}s
                                                @else
                                                    Tiempo cumplido
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                @break

                                @default
                            @endswitch
                        @endforeach
                    </div>
                    <div class="col-1 d-flex justify-content-center m-0 p-0" style="width: 30px !important;">
                        <div class="vertical-divider" style="background-color: #3f3e3e !important;"></div>
                    </div>
                    <div class="col-md-8 m-0 p-0 ps-3 pe-3"
                        style="max-height: 37.5rem; overflow-y: auto;overflow-x: hidden;">
                        <div class="row">
                            @foreach ($mesas as $mesa)
                                @php
                                    $ventaActiva = $mesa->venta;
                                    $mesaOcupada = $ventaActiva && !$ventaActiva->pagado;
                                @endphp
                                <div class="col-md-2 col-sm-4 col-3 mb-1"
                                    style="line-height: 13px;padding-right: 5px;padding-left: 5px;">
                                    <div class="card mesa-card text-center p-1 {{ $mesaOcupada ? 'mesa-ocupada' : 'mesa-disponible' }}"
                                        style="cursor: pointer; border: 2px solid {{ $mesaOcupada ? '#dc3545' : '#e9ecef' }}; transition: all 0.3s ease;"
                                        @if ($mesaOcupada) onmouseover="this.style.borderColor='#dc3545'; this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.borderColor='#dc3545'; this.style.transform='scale(1)'"
                                            wire:click="seleccionar('{{ $ventaActiva->id }}')"
                                            data-bs-dismiss="modal"
                                        @else
                                            onmouseover="this.style.borderColor='#007bff'; this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.borderColor='#e9ecef'; this.style.transform='scale(1)'"
                                            wire:click="abrirVentaConMesa({{ $mesa->id }},'mesa')"
                                            data-bs-dismiss="modal" @endif>
                                        <div class="mesa-icon mb-2">
                                            <i
                                                class="fa fa-table fa-3x {{ $mesaOcupada ? 'text-danger' : 'text-primary' }}"></i>
                                            @if ($mesaOcupada)
                                                <div class="mesa-ocupada-overlay">
                                                    <i class="fa fa-times fa-2x text-danger"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <strong class=" mb-1 font-12 text-black">
                                            Mesa {{ $mesa->numero }}</strong>
                                        <small class="text-muted">
                                            @if ($mesaOcupada)
                                                @if ($ventaActiva->cliente)
                                                    <span class="text-black">
                                                        <i class="fa fa-user"></i>
                                                        {{ Str::words($ventaActiva->cliente->name, 2) }}
                                                    </span>
                                                @elseif ($ventaActiva->usuario_manual)
                                                    <span class="text-muted">
                                                        <i class="fa fa-edit"></i>
                                                        {{ Str::limit($ventaActiva->usuario_manual, 15) }}
                                                    </span>
                                                @endif

                                                <br>
                                                <strong class="text-info fs-16">
                                                    <i class="fa fa-money"></i> {{ $ventaActiva->totalFinal() }} Bs
                                                </strong>
                                                @if ($ventaActiva->productos->count() > 0)
                                                    <br>
                                                    <small class="text-black">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        {{ $ventaActiva->totalItems() }} items
                                                    </small>
                                                @endif

                                                @if ($ventaActiva->reservado_at)
                                                    @php
                                                        $now = \Carbon\Carbon::now();
                                                        $reservado = \Carbon\Carbon::parse($ventaActiva->reservado_at);
                                                        $diffInSeconds = $now->diffInSeconds($reservado, false);

                                                        if ($diffInSeconds > 10800) {
                                                            // 3 horas
                                                            $colorClass = 'bg-dark';
                                                        } elseif ($diffInSeconds > 1800) {
                                                            // 30 minutos
                                                            $colorClass = 'bg-warning';
                                                        } else {
                                                            $colorClass = 'bg-danger';
                                                        }

                                                        $hours = floor($diffInSeconds / 3600);
                                                        $minutes = floor(($diffInSeconds % 3600) / 60);
                                                        $seconds = $diffInSeconds % 60;
                                                    @endphp
                                                    <br>
                                                    <small style="font-size: 9px;">
                                                        <i class="fa fa-hourglass-half faa-flash animated"></i>
                                                    </small>
                                                    <span class="badge badge-sm {{ $colorClass }}"
                                                        id="timer-modal-mesa-{{ $ventaActiva->id }}"
                                                        data-reservado="{{ $ventaActiva->reservado_at }}"
                                                        style="font-size: 8px; padding: 1px 4px;">
                                                        @if ($diffInSeconds > 0)
                                                            {{ $hours }}h {{ $minutes }}m
                                                            {{ $seconds }}s
                                                        @else
                                                            Tiempo cumplido
                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-success">
                                                    <i class="fa fa-check-circle"></i> Disponible
                                                </span>
                                                @if ($mesa->capacidad)
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fa fa-users"></i> {{ $mesa->capacidad }} personas
                                                    </small>
                                                @endif
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-primary float-start" data-bs-dismiss="modal"
                    wire:click="abrirVentaConMesa(null,'recoger')">Venta Rapida <i class="fa fa-bolt"></i></button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
                    onclick="confirmarVentaDelivery()">Venta Delivery <i class="fa fa-truck"></i></button>
                <button type="button" class="btn btn-sm btn-success" data-bs-dismiss="modal"
                    onclick="crearUsuarioRapido(false)">Nuevo Cliente <i class="fa fa-user"></i><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-sm btn-info" data-bs-dismiss="modal"
                    onclick="confirmarVentaReserva()">Reserva <i class="flaticon-088-time"></i></button>
            </div>
        </div>
    </div>
</div>
