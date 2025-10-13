@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Mi saldo" cabecera="appkit" />
    <div class="card card-style">
        <div class="content mb-2">
            <h4>Resumen detallado</h4>
            <p class="mb-0">
                Registros de saldos a continuacion:
            </p>
            <div class="d-flex flex-row w-100 rounded-md rounded overflow-hidden my-3 shadow-l">
                <button id="pendientes-saldo-btn" class="w-100 p-1 bg-teal-light table-toggle-btn">
                    <span class="color-theme font-15">
                        Pendiente
                    </span>
                </button>
                <button id="historial-saldo-btn" class="w-100 p-1 table-toggle-btn">
                    <span class="color-theme font-15">
                        Historial
                    </span>
                </button>
            </div>
            <div class="shadow-l">
                <table id="tabla-pendientes" class="table table-borderless text-center  rounded-sm" style="overflow: hidden;">
                    <thead class="">
                        <tr>
                            <th style="width:7%"  class="color-theme py-2 font-14 text-center align-middle">Ver</th>
                            <th style="width:33%" class="color-theme py-2 font-14 text-center align-middle">Fecha</th>
                            <th style="width:27%" class="color-theme py-2 font-14 text-center align-middle">Monto (Bs)</th>
                            <th style="width:33%" class="color-theme py-2 font-14 text-center align-middle">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saldosPendientes as $item)
                            <tr>
                                <td><a href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalSaldo{{ $item->id }}">
                                        <i data-lucide="book-text" class="lucide-icon color-teal-light"></i>
                                        <!-- <span
                                            class="fa-fw select-all fas"></span> -->
                                    </a>
                                </td>
                                <td scope="row" class="color-theme">
                                    {{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }}</td>
                                <td class="color-{{ $item->es_deuda ? 'red' : 'green' }}-dark">{{ $item->monto }}</td>
                                @if ($item->es_deuda)
                                    <td>
                                        <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">DEUDA <strong>-</strong></mark> <i
                                            class="fa fa-arrow-right rotate-135 color-red-dark"></i>
                                    </td>
                                @else
                                    <td >
                                        <mark class="highlight ps-2 font-12 pe-2 bg-green-dark">FAVOR
                                            <strong>+</strong></mark> <i class="fa fa-arrow-up rotate-45 color-green-dark"></i>
                                    </td>
                                @endif

                            </tr>
                        @endforeach 
                    </tbody>
                    <tfoot>
                        <tr>
                            {{-- Cell 1: Total Label --}}
                            <th colspan="2" class="text-center align-middle">
                                TOTAL
                            </th>
                            
                            {{-- Cell 2: Total Amount --}}
                            <td colspan="2" class="text-center align-middle color-{{ $usuario->saldo > 0 ? 'red' : 'green' }}-dark">
                                <strong>{{ $usuario->saldo }} Bs. {{ $usuario->saldo > 0 ? "Deuda" : "Disponibles" }} </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <table id="tabla-completa" class="table table-borderless text-center rounded-sm d-none" style="overflow: hidden;">
                    <thead>
                        <tr>
                            <th style="width:7%"  class="color-theme py-2 font-14 text-center align-middle">Ver</th>
                            <th style="width:33%" class="color-theme py-2 font-14 text-center align-middle">Fecha</th>
                            <th style="width:27%" class="color-theme py-2 font-14 text-center align-middle">Monto (Bs)</th>
                            <th style="width:33%" class="color-theme py-2 font-14 text-center align-middle">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saldosHistorial as $item)
                            <tr>
                                <td>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalSaldo{{ $item->id }}">
                                        <i data-lucide="book-text" class="lucide-icon color-teal-light"></i>
                                        <!-- <span
                                            class="fa-fw select-all fas"></span> -->
                                    </a>
                                </td>
                                <td scope="row" class="color-theme">
                                    {{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }}</td>
                                <td class="color-{{ $item->es_deuda ? 'red' : 'green' }}-dark">{{ $item->monto }}</td>
                                @if ($item->es_deuda)
                                    <td>
                                        <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">DEUDA <strong>-</strong></mark> <i
                                            class="fa fa-arrow-right rotate-135 color-red-dark"></i>
                                    </td>
                                @else
                                    <td >
                                        <mark class="highlight ps-2 font-12 pe-2 bg-green-dark">FAVOR
                                            <strong>+</strong></mark> <i class="fa fa-arrow-up rotate-45 color-green-dark"></i>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                        <!-- <tr>
                            <th>TOTAL</th>
                            <td></td>
                            <th class="color-{{ $usuario->saldo > 0 ? 'red' : 'green' }}-dark">{{ $usuario->saldo }} Bs</th>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        @foreach ($usuario->saldos as $item)
            <div class="modal fade" id="modalSaldo{{ $item->id }}" tabindex="-1" aria-labelledby="modalSaldo"
                aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                @if ($item->venta)
                    <div class="d-flex flex-column">
                        <div class="card card-style bg-dtheme-blue">
                            <div class="content">
                                <div class="d-flex">
                                    <div>
                                        <h1>Informacion general</h1>
                                        <p class="font-600 color-highlight mt-n3">Venta registrada</p>
                                    </div>
                                    <div class="ms-auto">
                                        <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_medium')) }}" width="50">
                                    </div>
                                </div>
                                <div class="divider mt-3 mb-3"></div>
                                <div class="row mb-0">
                                    <div class="col-5">
                                        <p class="color-theme font-700">Fecha</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400"><strong class="color-highlight">{{ $item->created_at->format('d-M') }}</strong> (
                                            {{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }} )</p>
                                    </div>
                                    <div class="col-5">
                                        <p class="color-theme font-700">Subtotal de venta</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400">{{ $item->venta->total }} Bs</p>
                                    </div>
                                    <div class="col-5">
                                        <p class="color-theme font-700">Descuento</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400">{{ $item->venta->descuento }} Bs</p>
                                    </div>
                                    <div class="col-5">
                                        <p class="color-theme font-700">A saldo</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400">{{ $item->venta->saldo }} Bs</p>
                                    </div>
                                    <div class="col-5">
                                        <p class="color-theme font-700">Total pagado</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400">
                                            <strong>{{ $item->venta->total - $item->venta->descuento - $item->venta->saldo }}
                                                Bs</strong>
                                        </p>
                                    </div>
                                    <div class="col-5">
                                        <p class="color-theme font-700">Atendido por</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400">{{ $item->venta->usuario->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-style bg-dtheme-blue">
                            <div class="content">
                                <h4 class="mb-n1">Descripcion</h4>
                                <p>
                                    Detalle de los items de esta venta:
                                </p>
                                <div class="row mb-0">
                                    @foreach ($item->venta->productos->groupBy('nombre') as $detalle)
                                        <div class="d-flex flex-column">
                                            <small class="color-theme font-500">{{ Str::limit($detalle[0]->nombre, 50) }}</small>
                                        </div>
                                        <!-- <div class="col-4">
                                            
                                        </div> -->
                                        <div class="col-2">
                                            <small class="font-400">
                                                @foreach ($detalle as $pivot)
                                                    {{ $pivot->pivot->cantidad }}
                                                @break
                                            @endforeach u.
                                        </small>
                                    </div>
                                    @if ($detalle[0]->descuento != null && $detalle[0]->descuento < $detalle[0]->precio)
                                        <div class="col-3">
                                            <small class="font-400">{{ $detalle->sum('descuento') }} Bs c/u</small>
                                        </div>
                                        <div class="col-3">
                                            <small class="font-400">
                                                {{ $detalle[0]->pivot->cantidad * $detalle->sum('descuento') }} Bs</small>
                                        </div>
                                    @else
                                        <div class="col-3">
                                            <small class="font-400">{{ $detalle->sum('precio') }} Bs c/u</small>
                                        </div>
                                        <div class="col-3">
                                            <small class="font-400">
                                                {{ $detalle[0]->pivot->cantidad * $detalle->sum('precio') }}
                                                Bs</small>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        </div>
                        </div>
                    </div>
                @else
                    <div class="card card-style">
                        <div class="content">
                            <div class="d-flex">
                                <div>
                                    <h1>Informacion general</h1>
                                    <p class="font-600 color-highlight mt-n3">Pago registrado</p>
                                </div>
                                <div class="ms-auto">
                                    <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}" width="40">
                                </div>
                            </div>
                            <div class="divider mt-3 mb-3"></div>
                            <div class="row mb-0">
                                <div class="col-4">
                                    <p class="color-theme font-700">Fecha</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400"><strong>{{ $item->created_at->format('d-M') }}</strong> (
                                        {{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }} )</p>
                                </div>
                                <div class="col-4">
                                    <p class="color-theme font-700">Monto total</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400"><strong>{{ $item->monto }} Bs</p>
                                </div>
                                <div class="col-4">
                                    <p class="color-theme font-700">Detalle</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400"><strong>{{ $item->detalle }} Bs</p>
                                </div>
                                <div class="col-4">
                                    <p class="color-theme font-700">Atendido por</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400"><strong>{{ $item->atendidoPor->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    @endpush
    @push('scripts')
    <script>
        $(document).ready( async function() {

            $('#historial-saldo-btn').on('click', function() {
                // Ocultar tabla pendientes
                $('#tabla-pendientes').addClass('d-none');                
                // Revelar tabla historial
                $('#tabla-completa').removeClass('d-none');

                // Deseleccionar Boton Pendientes 
                $('#pendientes-saldo-btn').removeClass('bg-teal-light');
                // $('#pendientes-saldo-btn').addClass('bg-theme');
                // Seleccionar Boton Historial
                $('#historial-saldo-btn').removeClass('bg-theme');
                $('#historial-saldo-btn').addClass('bg-teal-light');
            });

            $('#pendientes-saldo-btn').on('click', function() {
                // Ocultar tabla historial
                $('#tabla-completa').addClass('d-none');
                // Revelar tabla pendientes
                $('#tabla-pendientes').removeClass('d-none');

                // Deseleccionar Boton Historial
                $('#historial-saldo-btn').removeClass('bg-teal-light');
                // $('#historial-saldo-btn').addClass('bg-theme');
                // Seleccionar Boton Pendientes
                $('#pendientes-saldo-btn').removeClass('bg-theme');
                $('#pendientes-saldo-btn').addClass('bg-teal-light');
            });
        });
    </script>
    @endpush
@endsection
