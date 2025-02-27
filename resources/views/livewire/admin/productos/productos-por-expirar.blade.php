<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row">
                <div class="col">
                    <h4 class="card-title">Productos por expirar</h4>
                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center">
                        <div wire:loading class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="col"> <input type="text" class="form-control form-control-sm"
                        placeholder="Buscar producto" wire:model.debounce.750ms="search"></div>

            </div>

            <div class="card-body">
                <div class="col-12">
                    <div class="card ">
                        {{-- <div class="card-header border-0 mb-0">
                            <h4 class="fs-20 card-title">Listado de productos</h4>
                        </div> --}}
                        <div class="card-body pb-0  pt-0">
                            @foreach ($productos as $item)
                                <div class="media align-items-center">
                                    <div class="media-image me-2">
                                        <img src="{{ asset($item->pathAttachment()) }}" alt="">
                                    </div>
                                    <div class="media-body m-0 p-0" style="line-height: 15px">
                                        <h6 class="fs-16 mb-0"><a
                                                href="{{ route('producto.listar', ['buscar' => $item->nombre]) }}"
                                                target="_blank">{{ $item->nombre }} <i
                                                    class="flaticon-083-share"></i></a></h6>
                                        @if ($item->descuento > 0)
                                            <span class="fs-14 ">Precio con descuento: <del
                                                    class="text-muted">{{ $item->precio }}</del> <strong
                                                    class="text-warning">{{ $item->descuento }} Bs</strong>
                                            </span>
                                        @else
                                            <span class="fs-14 ">Precio actual: <strong
                                                    class="text-success">{{ $item->precio }} Bs</strong>
                                            </span>
                                        @endif
                                        <div class="row">
                                            @foreach ($item->sucursale as $sucursal)
                                                <div class="col-4 mb-2">
                                                    <div class="media align-items-center event-list p-2 bordeado">
                                                        {{-- <div class="p-3 text-center me-3 date-bx bgl-primary">
                                                        <h2 class="mb-0 text-primary fs-28 font-w600">28</h2>
                                                        <h5 class="mb-1 text-black">Wed</h5>
                                                    </div> --}}
                                                        <div class="media-body px-0">
                                                            <div class="float-end">
                                                                <div
                                                                    class="dropdown custom-dropdown mb-0 tbl-orders-style">
                                                                    <div class="btn sharp tp-btn"
                                                                        data-bs-toggle="dropdown">
                                                                        <svg width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z"
                                                                                stroke="#194039" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"></path>
                                                                            <path
                                                                                d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z"
                                                                                stroke="#194039" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"></path>
                                                                            <path
                                                                                d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z"
                                                                                stroke="#194039" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"></path>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        {{-- <a class="dropdown-item" href="javascript:void(0);">Eliminar</a> --}}
                                                                        <a class="dropdown-item text-danger"
                                                                            href="javascript:void(0);"
                                                                            onclick="eliminarStock({{ $sucursal->pivot->id }},'{{ $item->nombre }}')">Eliminar
                                                                            lote</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6 class="mt-0 mb-3 fs-12">Vence :
                                                                {{ App\Helpers\GlobalHelper::fechaFormateada(3, $sucursal->pivot->fecha_venc) }}
                                                                <br>

                                                                <span
                                                                    class="text-{{ $sucursal->pivot->fecha_venc < date('Y-m-d') ? 'danger' : 'success' }}">{{ App\Helpers\GlobalHelper::timeago($sucursal->pivot->fecha_venc) }}</span>

                                                            </h6>

                                                            <ul
                                                                class="fs-14 list-inline mb-2 d-flex justify-content-between">
                                                                <li>Restantes</li>
                                                                <li> {{ $sucursal->pivot->cantidad . '/' . $sucursal->pivot->max }}
                                                                </li>
                                                            </ul>
                                                            @php
                                                                $porcentaje =
                                                                    ($sucursal->pivot->cantidad * 100) /
                                                                    $sucursal->pivot->max;
                                                                $clase =
                                                                    $porcentaje <= 25
                                                                        ? 'bg-danger'
                                                                        : ($porcentaje <= 50
                                                                            ? 'bg-warning'
                                                                            : ($porcentaje <= 75
                                                                                ? 'bg-info'
                                                                                : 'bg-success'));
                                                            @endphp
                                                            <div class="progress mb-0" style="height:4px; width:100%;">
                                                                <div class="progress-bar {{ $clase }}"
                                                                    style="width:{{ ($sucursal->pivot->cantidad * 100) / $sucursal->pivot->max }}%;"
                                                                    role="progressbar">
                                                                    <span class="sr-only">60% Complete</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                                <hr class="p-0 m-0">
                            @endforeach


                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        function eliminarStock(id, nombreProducto) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: `Se eliminará el stock de "${nombreProducto}". Esta acción no se puede deshacer.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminarStock', id);
                    Swal.fire(
                        "Eliminado",
                        `El stock de "${nombreProducto}" ha sido eliminado.`,
                        "success"
                    );
                }
            });
        }
    </script>
@endpush
