<div wire:ignore.self class="modal fade" id="modalDetalleVenta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content letra14">
            <div class="modal-header">
                @isset($ventaSeleccionada)
                    <strong>Atendido por: {{ $ventaSeleccionada->usuario->name }}</strong>
                    <strong>Hora de venta:
                        {{ App\Helpers\GlobalHelper::fechaFormateada(6, $ventaSeleccionada->created_at) }}</strong>
                @endisset
            </div>
            <center wire:loading>
                <div class="spinner-border mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </center>


            <div class="modal-body mt-0 pt-0 " wire:loading.remove>
                @isset($ventaSeleccionada)
                    <div class="table-responsive">
                        <table class="table p-0 m-0 letra12 ">
                            <thead>
                                <tr>
                                    <th class="py-1 text-white bg-primary">Producto</th>
                                    <th class="py-1 text-white bg-primary">Cant.</th>
                                    <th class="py-1 text-white bg-primary">Unit.</th>
                                    <th class="py-1 text-white bg-primary">Subt.</th>
                                    {{-- <th class="py-1 text-white bg-primary">Desc.</th> --}}
                                    <th class="py-1 text-white bg-primary">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ventaSeleccionada->productos as $producto)
                                    <tr>
                                        <td class="py-1">{{ Str::limit($producto->nombre, 30) }}</td>
                                        <td class="py-1">{{ $producto->pivot->cantidad }}</td>
                                        <td class="py-1">
                                            {{ $producto->pivot->precio_unitario ?? $producto->precio }} Bs</td>
                                        <td class="py-1">
                                            {{ $producto->pivot->precio_subtotal ?? $producto->precio * $producto->pivot->cantidad }}
                                            Bs
                                        </td>
                                        {{-- <td class="py-1">{{ $producto->pivot->descuento_producto ?? 0 }} Bs</td> --}}
                                        <td class="py-1">
                                            <strong>{{ $producto->pivot->precio_subtotal ?? $producto->precio * $producto->pivot->cantidad - $producto->pivot->descuento_producto }}
                                                Bs</strong>

                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    {{-- <td></td> --}}
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="py-1 text-primary"><span>Total original</span></td>
                                    <td class="py-1 text-primary"><strong>{{ $ventaSeleccionada->subtotal }}
                                            Bs</strong></td>
                                </tr>
                                <tr>
                                    {{-- <td></td> --}}
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="py-1"><span>Descuento productos</span></td>
                                    <td class="py-1"><span>-{{ $ventaSeleccionada->descuento_productos }}
                                            Bs</span></td>
                                </tr>
                                <tr>
                                    {{-- <td></td> --}}
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="py-1"><span>Descuento manual</span></td>
                                    <td class="py-1"><span>-{{ $ventaSeleccionada->descuento_manual }} Bs</span>
                                    </td>
                                </tr>
                                @if ($ventaSeleccionada->saldo_monto != 0 && $ventaSeleccionada->saldo_monto != null)
                                    <tr>
                                        {{-- <td></td> --}}
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        @if ($ventaSeleccionada->a_favor_cliente)
                                            <td class="py-1 text-info">
                                                <span class="letra14 ">{{ $ventaSeleccionada->saldoSituacion() }}<i
                                                        class="flaticon-003-arrow-up"></i></span>
                                            </td>
                                        @else
                                            <td class="py-1 text-warning">
                                                <span class="letra14 ">{{ $ventaSeleccionada->saldoSituacion() }}<i
                                                        class="flaticon-001-arrow-down"></i></span>
                                            </td>
                                        @endif

                                        <td
                                            class="py-1 {{ $ventaSeleccionada->a_favor_cliente ? 'text-info' : 'text-warning' }}">
                                            <strong
                                                class="letra14">{{ $ventaSeleccionada->a_favor_cliente ? '+' : '-' }}{{ $ventaSeleccionada->saldo_monto }}
                                                Bs</strong>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    {{-- <td></td> --}}
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="py-1 "><strong class="letra14">Total Pagado</strong></td>
                                    <td class="py-1 "><strong class="letra14">{{ $ventaSeleccionada->total_pagado }}
                                            Bs</strong>
                                    </td>
                                </tr>
                            </tbody>
                        @endisset
                    </table>
                </div>
                <div class="card-body py-3 letra12">
                    <ul class="d-flex align-items-center mb-1">

                        <li><a href="javascript:void(0);" class="ms-2">Metodos de pago:</a></li>

                    </ul>
                    @isset($ventaSeleccionada->metodosPagos)
                        @foreach ($ventaSeleccionada->metodosPagos as $metodo)
                            <ul class="d-flex align-items-center mb-1">
                                <li><a href="javascript:void(0);"><img src="{{ $metodo->imagen }}" class="rounded-circle"
                                            style="width: 35px;height:35px" alt=""></a>
                                </li>
                                <li><a href="javascript:void(0);" class="ms-2">{{ $metodo->nombre_metodo_pago }}</a></li>
                                <li><strong><a href="javascript:void(0);"
                                            class="mx-2">{{ $metodo->pivot->monto . ' Bs' }}</a></strong>
                                </li>
                                @isset($metodosPagos)
                                    <li>
                                        <div class="dropdown ms-auto">
                                            <a href="#" class="mx-2 badge badge-xs badge-info p-1 py-0 letra10"
                                                data-bs-toggle="dropdown" aria-expanded="false"> <strong>Cambiar
                                                    <i class="flaticon-075-reload"></i></strong></a>
                                            <ul class="dropdown-menu dropdown-menu-end" style="">

                                                @foreach ($metodosPagos as $met)
                                                    <li class="dropdown-item"
                                                        onclick="cambiarMetodo('{{ $met->nombre_metodo_pago }}',{{ $met->id }},{{ $metodo->pivot->id }})">
                                                        <a href="javascript:void(0);"><img src="{{ $met->imagen }}"
                                                                class="rounded-circle" style="width: 35px;height:35px"
                                                                alt="">
                                                            {{ $met->nombre_metodo_pago }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @endisset

                            </ul>
                        @endforeach
                    @endisset

                </div>
            </div>
        </div>
    </div>
</div>
