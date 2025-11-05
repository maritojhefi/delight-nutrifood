<div>
    <div class="row">
        @if ($isOpen)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $mesaId ? 'Editar Mesa' : 'Crear Nueva Mesa' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <x-input-create :lista="[
                                'Nombre' => ['nombre_mesa', 'text'],
                                'Número' => ['numero', 'number'],
                                // 'Url' => ['url', 'text'],
                            ]">
                            </x-input-create>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-xl-{{ $isOpen ? '8' : '12' }}">
            <div class="card overflow-hidden">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title mb-1 ">Mesas</h4>
                    @if ($isOpen)
                        <button wire:click="create" class="btn btn-danger mb-3">
                            <i class="fa fa-ban"></i> Cancelar
                        </button>
                    @else
                        <button wire:click="create" class="btn btn-primary mb-3">
                            <i class="fa fa-plus-circle"></i> Nueva Mesa
                        </button>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6 px-4 mb-3">
                        <input type="text" class="form-control" placeholder="Buscar por nombre, número o código"
                            wire:model.debounce.750ms="search">
                    </div>
                </div>
                <div class="card-body pt-0 p-0">
                    @if ($mesas->isNotEmpty())
                        @foreach ($mesas as $mesa)
                            <div class="media align-items-center border-bottom p-1"
                                style="justify-content: space-between !important;">
                                <span
                                    class="number  col-1 px-0 align-self-center d-none d-sm-inline-block ">#{{ $mesa->id }}</span>
                                <div>
                                    <a href="javascript:void(0)"
                                        class="badge badge-rounded badge-primary">{{ $mesa->nombre_mesa }}</a>
                                </div>

                                <div class="me-3">
                                    <div class="text-center">
                                        <span
                                            class="text-primary d-block chart-num-6 font-w600">{{ $mesa->numero }}</span>
                                        <span class="fs-14">N° de mesa</span>
                                    </div>
                                </div>
                                <div class="me-3">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-dark btn-xs"
                                            wire:click="descargarQrDiseno('{{ $mesa->id }}')">
                                            <i class="fa fa-picture-o me-2"></i>QR con Diseño
                                        </button>
                                    </div>
                                </div>
                                <div class="me-3">
                                    <div class="text-center">

                                        <button type="button" class="btn btn-dark btn-xs"
                                            wire:click="descargarQr('{{ $mesa->id }}')">
                                            <i class="fa fa-qrcode me-2"></i>QR
                                        </button>
                                    </div>
                                </div>
                                <div class="me-3">
                                    <div class="dropdown ms-auto text-right">
                                        <div class="btn-link text-info" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                wire:click="edit({{ $mesa->id }})">Editar</a>
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                wire:click="delete({{ $mesa->id }})">Eliminar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <h4>No hay mesas registradas</h4>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    {{ $mesas->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        livewire.on('datos-guardados', () => {
            Toast.fire({
                title: "Datos de la mesa guardados.",
                icon: "success"
            });
        });
    </script>
</div>
