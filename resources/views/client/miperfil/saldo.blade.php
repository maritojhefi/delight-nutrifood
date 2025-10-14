@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Mi saldo" cabecera="appkit" />
    <div class="card card-style">
        <div class="content mb-3">
            <h4>Resumen detallado</h4>
            <p class="mb-0">
                Registros de saldos a continuacion:
            </p>
            <div class="d-flex flex-row w-100 rounded-md rounded overflow-hidden my-3 shadow-l">
                <button id="pendientes-saldo-btn" class="w-100 p-1 bg-teal-light table-toggle-btn">
                    <span class="color-theme font-15">
                        <strong>Pendiente</strong>
                    </span>
                </button>
                <button id="historial-saldo-btn" class="w-100 p-1 table-toggle-btn">
                    <span class="color-theme font-15">
                        <strong>Historial</strong>
                    </span>
                </button>
            </div>
            <div class="table-responsive rounded-sm shadow-l">
                <table id="tabla-pendientes" class="table table-borderless rounded-sm text-center mb-0" style="overflow: hidden;">
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
                            <th colspan="2" class="text-center align-middle">
                                TOTAL
                            </th>
                            
                            <td colspan="2" class="text-center align-middle color-{{ $usuario->saldo > 0 ? 'red' : 'green' }}-dark">
                                <strong>{{ $usuario->saldo }} Bs. {{ $usuario->saldo > 0 ? "Deuda" : "Disponibles" }} </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <table id="tabla-completa" class="table table-borderless rounded-sm text-center d-none" style="overflow: hidden;">
                    <thead>
                        <tr>
                            <th style="width:7%"  class="color-theme py-2 font-14 text-center align-middle">Ver</th>
                            <th style="width:33%" class="color-theme py-2 font-14 text-center align-middle">Fecha</th>
                            <th style="width:27%" class="color-theme py-2 font-14 text-center align-middle">Monto (Bs)</th>
                            <th style="width:33%" class="color-theme py-2 font-14 text-center align-middle">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-completa-body">
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
                    </tbody>
                    <tfoot id="tabla-completa-footer">
                        <tr>
                            <th colspan="4" id="paginacion-historial-saldos" class="text-center align-middle">
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <div class="d-flex flex-row gap-2 align-items-center">
                                        <!-- <select id="selector-pagina-historial"  title="pagina_seleccionada" class="form-select form-select-sm d-flex  justify-items-center">
                                            <option value="1" default>1</option>
                                        </select> -->
                                        <div class="dropdown dropup">
                                            <button class="btn btn-xxs bg-highlight dropdown-toggle" 
                                                    type="button" 
                                                    id="pageSelectorButton" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false">
                                                <strong><span id="selector-pagina-text" class="font-13">1</span></strong>
                                            </button>
                                            
                                            <ul class="dropdown-menu bg-dtheme-blue overflow-scroll"  aria-labelledby="pageSelectorButton" id="selector-pagina-options" style="min-width: 4rem !important; max-height: 30vh;">
                                                </ul>
                                        </div>
                                        <p>Página <span id="span-pgactual-historial">1</span> de <span id="span-pgmax-historial">N</span></p>
                                    </div>
                                    <div class="d-flex flex-row gap-2">
                                        <button id="btn-pganterior-historial" class="btn-pg-control btn btn-xxs bg-teal-dark">Anterior</button>
                                        <button id="btn-pgsiguiente-historial" class="btn-pg-control btn btn-xxs bg-teal-dark">Siguiente</button>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
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
                        <div class="card card-style bg-dtheme-blue mx-1">
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
                                        <p class="color-theme font-700">Total Venta</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400 color-highlight">
                                            <strong>{{ $item->venta->total - $item->venta->descuento - $item->venta->saldo }} Bs</strong>
                                        </p>
                                    </div>
                                    <!-- PRUEBAS TOTAL PAGADO REAL -->
                                    <div class="col-5">
                                        <p class="color-theme font-700">Total Pagado</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400 color-highlight">{{ $item->venta->total_pagado }} Bs</p>
                                    </div>
                                    <div class="col-5">
                                        <p class="color-theme font-700">{{ $item->es_deuda ? 'Saldo a deuda' : 'Saldo a favor' }}</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400 color-{{ $item->es_deuda ? 'red' : 'green' }}-dark"><strong>{{ $item->venta->saldo_monto }} Bs</strong></p>
                                    </div>
                                    <!-- FIN PRUEBA -->
                                    <!-- <div class="col-5">
                                        <p class="color-theme font-700">A saldo</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400">{{ $item->venta->saldo }} Bs</p>
                                    </div> -->
                                    <div class="col-5">
                                        <p class="color-theme font-700">Atendido por</p>
                                    </div>
                                    <div class="col-7">
                                        <p class="font-400">{{ $item->venta->usuario->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-style bg-dtheme-blue mx-1">
                            <div class="content">
                                <h2 class="">Detalles de la venta</h2>
                                <!-- <p class="font-600 color-highlight mt-n2 mb-1">Detalle de los items de esta venta:</p> -->
                                <div class="row mb-0">
                                    @foreach ($item->venta->productos->groupBy('nombre') as $detalle)
                                        <div class="d-flex flex-column">
                                            <strong class="color-highlight font-500">{{ Str::limit($detalle[0]->nombre, 50) }}</strong>
                                        </div>
                                        <div class="col-4">
                                            <small class="font-400 color-theme">
                                                <strong>Unidades: </strong>
                                                @foreach ($detalle as $pivot)
                                                        {{ $pivot->pivot->cantidad }}
                                                    @break
                                                @endforeach
                                            </small>
                                        </div>
                                    @if ($detalle[0]->descuento != null && $detalle[0]->descuento < $detalle[0]->precio)
                                        <div class="col-5">
                                            <small class="font-400 color-theme"><strong>Precio:</strong> {{ $detalle->sum('descuento') }} Bs c/u</small>
                                        </div>
                                        <div class="col-4">
                                            <small class="font-400 color-theme">
                                                Total: {{ $detalle[0]->pivot->cantidad * $detalle->sum('descuento') }} Bs</small>
                                        </div>
                                    @else
                                        <div class="col-4">
                                            <small class="font-400 color-theme"><strong>Precio:</strong> {{ $detalle->sum('precio') }} Bs</small>
                                        </div>
                                        <div class="col-4">
                                            <small class="font-400 color-theme">
                                                <strong>Total: </strong>
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
                    <div class="card card-style bg-dtheme-blue mx-1">
                        <div class="content">
                            <div class="d-flex">
                                <div>
                                    <h1>Informacion general</h1>
                                    <p class="font-600 color-highlight mt-n3">Pago registrado</p>
                                </div>
                                <div class="ms-auto">
                                    <img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_medium')) }}" width="50">
                                </div>
                            </div>
                            <div class="divider mt-3 mb-3"></div>
                            <div class="row mb-0">
                                <div class="col-4">
                                    <p class="color-theme font-700">Fecha</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400"><strong class="color-highlight">{{ $item->created_at->format('d-M') }}</strong> (
                                        {{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }} )</p>
                                </div>
                                <div class="col-4">
                                    <p class="color-theme font-700">Monto total</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400 color-green-dark"><strong>{{ $item->monto }} Bs</strong></p>
                                </div>
                                <div class="col-4">
                                    <p class="color-theme font-700">Detalle</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400">{{ trim($item->detalle) }}</p>
                                </div>
                                <div class="col-4">
                                    <p class="color-theme font-700">Atendido por</p>
                                </div>
                                <div class="col-8">
                                    <p class="font-400">{{ $item->atendidoPor->name }}</p>
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
            
            await renderizarListadoHistorial(1,10);
            
            $('#historial-saldo-btn').on('click', function() {
                // Ocultar tabla pendientes
                $('#tabla-pendientes').addClass('d-none');                
                // Revelar tabla historial
                $('#tabla-completa').removeClass('d-none');

                // Deseleccionar Boton Pendientes 
                $('#pendientes-saldo-btn').removeClass('bg-teal-light');
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
                // Seleccionar Boton Pendientes
                $('#pendientes-saldo-btn').removeClass('bg-theme');
                $('#pendientes-saldo-btn').addClass('bg-teal-light');
            });

            $('#btn-pganterior-historial').on('click', async function() {
                const nuevaPagina = $(this).data('page');
                await renderizarListadoHistorial(nuevaPagina, 10); 
            });

            $('#btn-pgsiguiente-historial').on('click', async function() {
                const nuevaPagina = $(this).data('page');
                await renderizarListadoHistorial(nuevaPagina, 10); 
            });

            $('#selector-pagina-options').on('click', '.dropdown-item', async function(e) {
                e.preventDefault(); 
                const nuevaPagina = parseInt($(this).data('page')); 
                const limiteActual = 10;
                await renderizarListadoHistorial(nuevaPagina, limiteActual);
            });
        });

        const renderizarListadoHistorial = async(pagina, limite) => {
            try {
                await SaldoService.obtenerHistorialSaldo(pagina, 10).then(data => {
                    renderizarRegistrosHistorial(data.saldos);
                    renderizarControlesPaginacion(data.registros_totales, data.pagina_actual, data.cantidad_pagina);
                });
                
            } catch (error) {
                console.error(error);
                mostrarToastError("Ocurrio un error al obtener el historial.");
            }
        }

        const renderizarRegistrosHistorial = (registros) => {
            const contenedor = $('#tabla-completa-body');
            const formatoFecha = (isoString) => {
                if (!isoString) return '';
                const date = new Date(isoString);
                
                // return date.toLocaleDateString(); 

                // Formatear a anio, mes, dia
                return date.toISOString().split('T')[0];
            };
            const htmlRegistros = `${registros.map(registro => `
                <tr>
                    <td>
                        <a href="#" data-bs-toggle="modal"
                            data-bs-target="#modalSaldo${registro.id}">
                            <i data-lucide="book-text" class="lucide-icon color-teal-light"></i>
                        </a>
                    </td>
                    <td scope="row" class="color-theme">${formatoFecha(registro.created_at)}</td>
                    <td class="color-${registro.es_deuda ? 'red' : 'green'}-dark">${registro.monto}</td>
                    ${registro.es_deuda ? 
                    `<td>
                        <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">DEUDA <strong>-</strong></mark> <i
                            class="fa fa-arrow-right rotate-135 color-red-dark"></i>
                    </td>`
                    :
                    `<td >
                        <mark class="highlight ps-2 font-12 pe-2 bg-green-dark">FAVOR
                            <strong>+</strong></mark> <i class="fa fa-arrow-up rotate-45 color-green-dark"></i>
                    </td>`}
                </tr>
            `)}`;
            contenedor.html(htmlRegistros);
            reinitializeLucideIcons();
        }

        const renderizarControlesPaginacion = (totales, paginaActual, limite) => {
            const totalPaginas = Math.ceil(totales / limite);
            const primerPagina = paginaActual === 1;
            const ultimaPagina = paginaActual === totalPaginas;
            const botonAnterior = $('#btn-pganterior-historial');
            const botonSiguiente = $('#btn-pgsiguiente-historial');

            const contenedorOpciones = $('#selector-pagina-options');
            let arrayOpciones = [];

            for (let index = 1; index <= totalPaginas; index++) {
                arrayOpciones.push(
                    `<li style="max-width: 4rem;"><a class="dropdown-item bg-dtheme-blue color-theme text-center"  href="#" data-page="${index}">${index}</a></li>`
                );
            }
            
            contenedorOpciones.html(arrayOpciones.join(''));
            $('#selector-pagina-text').text(paginaActual); 

            $('#span-pgactual-historial').text(paginaActual);
            $('#span-pgmax-historial').text(totalPaginas);

            if (primerPagina) {
                botonAnterior.prop('disabled', true);
            } else {
                botonAnterior.prop('disabled', false);
            }

            if (ultimaPagina || totalPaginas === 0) {
                botonSiguiente.prop('disabled', true);
            } else {
                botonSiguiente.prop('disabled', false);
            }

            botonAnterior.data('page', paginaActual - 1);
            botonSiguiente.data('page', paginaActual + 1);
        }
    </script>
    @endpush
@endsection
