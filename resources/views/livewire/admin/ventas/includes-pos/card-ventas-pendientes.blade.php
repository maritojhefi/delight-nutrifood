<x-card-col tamano="3">
    <div class="mb-2">
        <center class="card-intro-title p-2 " style="font-size:15px">Ventas Pendientes</center>
        <div class="">
            @foreach ($ventas as $item)
                <div class="alert mb-1 {{ $item->productos->count() > 0 ? 'border-success alert-success' : 'alert-danger border-danger' }}@isset($cuenta) {{ $item->id == $cuenta->id ? 'solid alert-alt bordeado-pulse' : '' }} @endisset p-1 px-2 pb-2"
                    style="line-height: 0px">
                    <a href="#" class="p-0 m-0" style="line-height: 20px"
                        wire:click="seleccionar('{{ $item->id }}')">
                        <div wire:loading wire:target="seleccionar({{ $item->id }})"
                            class="spinner-grow spinner-grow-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        {{-- <small class="letra10"> #{{ $item->id }}</small> --}}
                        <div class="d-flex justify-content-between">
                            @switch ($item->tipo_entrega)
                                @case ('mesa')
                                    <small class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset"><i class="fa fa-table"></i> Mesa {{ $item->mesa->numero }}</small>
                                @break

                                @case ('delivery')
                                    <small class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset"><i class="fa fa-truck"></i> Delivery</small>
                                @break

                                @case ('recoger ')
                                    <small class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset"><i class="fa fa-shopping-cart"></i> Recoger</small>
                                @break

                                @default
                                    <small><i class="fa fa-question"></i> </small>
                                @break
                            @endswitch
                            <strong
                                class="float-end text-end @isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset ">{{ $item->totalFinal() }}Bs
                            </strong>
                            @if ($item->productos->count() == 0)
                                <a href="#" class="float-end badge badge-danger badge-pill py-1 px-2"
                                    wire:click="eliminar('{{ $item->id }}')">
                                    <i class="fa fa-trash"></i>
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
                            
                            <span class="p-0 m-0" style="font-size:13px;line-height: 10px"><i class="fa fa-edit"></i>
                                {{ Str::limit($item->usuario_manual, 35) }}</sp>
                            @endisset

                            @isset($item->cliente)
                                
                                <span class="p-0 m-0" style="font-size:13px;line-height: 10px"><i class="fa fa-user"></i>
                                    {{ Str::limit($item->cliente->name, 35) }}</span>
                            @endisset
                    </a>

                </div>
            @endforeach

            <!-- checkbox -->



            <button class="btn light btn-xs btn-outline-warning p-1 px-2 m-0" data-bs-toggle="modal"
                data-bs-target="#modalSeleccionarMesa">
                ABRIR NUEVA VENTA
            </button>
        </div>
    </div>
</x-card-col>

<!-- Modal Seleccionar Mesa -->
<div class="modal fade" id="modalSeleccionarMesa" tabindex="-1" aria-labelledby="modalSeleccionarMesaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSeleccionarMesaLabel">Seleccionar Mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach ($mesas as $mesa)
                        @php
                            $ventaActiva = $mesa->venta;
                            $mesaOcupada = $ventaActiva && !$ventaActiva->pagado;
                        @endphp
                        <div class="col-md-2 col-sm-4 col-3 mb-1" style="line-height: 10px;">
                            <div class="card mesa-card text-center p-1 {{ $mesaOcupada ? 'mesa-ocupada' : 'mesa-disponible' }}"
                                style="cursor: {{ $mesaOcupada ? 'not-allowed' : 'pointer' }}; border: 2px solid {{ $mesaOcupada ? '#dc3545' : '#e9ecef' }}; transition: all 0.3s ease;"
                                @if (!$mesaOcupada) onmouseover="this.style.borderColor='#007bff'; this.style.transform='scale(1.05)'"
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
                                <h6
                                    class="mesa-numero mb-1 {{ $mesaOcupada ? 'text-decoration-line-through text-muted' : '' }}">
                                    Mesa {{ $mesa->numero }}</h6>
                                <small class="text-muted">
                                    @if ($mesaOcupada)
                                        <span class="text-danger">
                                            <i class="fa fa-user"></i>
                                            {{ $ventaActiva->cliente ? Str::limit($ventaActiva->cliente->name, 15) : 'Cliente' }}
                                        </span>
                                        <br>
                                        <small class="text-warning">
                                            <i class="fa fa-clock"></i> Ocupada
                                        </small>
                                        <br>
                                        <small class="text-info">
                                            <i class="fa fa-money"></i> {{ $ventaActiva->totalFinal() }} Bs
                                        </small>
                                        @if ($ventaActiva->productos->count() > 0)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-shopping-cart"></i>
                                                {{ $ventaActiva->productos->count() }} productos
                                            </small>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="abrirVentaConMesa(null,'delivery')">Delivery</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="abrirVentaConMesa(null,'recoger')">Recoger</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para mesas disponibles */
    .mesa-disponible:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .mesa-disponible:active {
        transform: scale(0.95);
    }

    .mesa-icon {
        transition: transform 0.3s ease;
        position: relative;
    }

    .mesa-disponible:hover .mesa-icon {
        transform: rotate(5deg);
    }

    /* Estilos para mesas ocupadas */
    .mesa-ocupada {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        opacity: 0.8;
        position: relative;
        border-color: #dc3545 !important;
    }

    .mesa-ocupada::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(45deg,
                transparent,
                transparent 2px,
                rgba(220, 53, 69, 0.1) 2px,
                rgba(220, 53, 69, 0.1) 4px);
        pointer-events: none;
    }

    .mesa-ocupada:hover {
        box-shadow: none;
        transform: none;
        border-color: #dc3545 !important;
    }

    .mesa-ocupada .mesa-icon {
        transition: none;
        filter: grayscale(50%);
    }

    .mesa-ocupada:hover .mesa-icon {
        transform: none;
        filter: grayscale(50%);
    }

    .mesa-ocupada-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(220, 53, 69, 0.9);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        border: 2px solid white;
    }

    .mesa-ocupada-overlay i {
        color: white !important;
    }

    .mesa-ocupada .mesa-numero {
        text-decoration: line-through;
        color: #6c757d !important;
        font-weight: normal;
    }

    /* Efecto de deshabilitado */
    .mesa-ocupada {
        pointer-events: none;
        cursor: not-allowed !important;
    }

    .mesa-ocupada * {
        pointer-events: none;
    }

    /* Indicador de estado en la esquina */
    .mesa-ocupada::after {
        content: 'OCUPADA';
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: #dc3545;
        color: white;
        font-size: 8px;
        font-weight: bold;
        padding: 2px 4px;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('cerrarModal', function(data) {
            const modal = document.getElementById(data.modalId);
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });
    });
</script>
