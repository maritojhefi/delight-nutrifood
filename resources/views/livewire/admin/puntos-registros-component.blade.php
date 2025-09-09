<div>
    <div class="row">
        <div class="form-head mb-1 d-flex flex-wrap align-items-center">
            <div class="me-auto">
                <h2 class="font-w600 mb-0">
                    @if ($vistaPorUsuario)
                        Histórico de Puntos por Cliente
                    @else
                        Histórico Detallado de Puntos
                    @endif
                    <div wire:loading class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </h2>
            </div>

            <!-- Botón para cambiar vista -->
            <div class="me-3">
                <button type="button" class="btn {{ $vistaPorUsuario ? 'btn-outline-primary' : 'btn-primary' }} btn-xxs"
                    wire:click="toggleVista">
                    @if ($vistaPorUsuario)
                        <i class="fa fa-list-ul"></i> Ver últimos registros <i class="fa fa-arrow-right ms-2"></i>
                    @else
                        <i class="fa fa-trophy"></i> Ranking de Puntos <i class="fa fa-arrow-right ms-2"></i>
                    @endif
                </button>
            </div>

            <!-- Campo de búsqueda -->
            <div class="input-group search-area2 d-xl-inline-flex mb-2 me-lg-4 me-md-2">
                <button class="input-group-text"><i class="flaticon-381-search-2 text-primary"></i></button>
                <input type="text" class="form-control"
                    placeholder="@if ($vistaPorUsuario) Buscar cliente...@else Buscar cliente @endif"
                    wire:model.debounce.700ms="search">
            </div>
        </div>



        @if ($vistaPorUsuario)
            <!-- Vista agrupada por cliente -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header mt-0 mb-0 pb-2 pt-2">
                        <h4 class="card-title">Clientes con registros de puntos - {{ $registros->total() }} clientes
                        </h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-responsive-md m-0 p-0 letra12">
                                <thead class="m-2 p-3 bg-primary text-white">
                                    <tr class="m-2 p-3">
                                        <th class="m-2 p-3">Cliente</th>
                                        <th class="m-2 p-3">Total Puntos</th>
                                        <th class="m-2 p-3">Ventas con Puntos</th>
                                        <th class="m-2 p-3">Última Actualización</th>
                                        <th class="m-2 p-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="m-0 p-0">
                                    @if ($registros->isNotEmpty())
                                        @foreach ($registros as $registro)
                                            <tr class="m-0 p-0">
                                                <td class="m-0 p-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <i class="fa fa-user-circle text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $registro->cliente->name ?? 'N/A' }}</strong><br>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <span class=" fs-6">
                                                        <strong>{{ number_format($registro->total_puntos_cliente) }}</strong></span>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <span
                                                        class=""><strong>{{ $registro->total_registros }}</strong></span>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <small>{{ $registro->ultima_actualizacion ? App\Helpers\GlobalHelper::fechaFormateada(3, $registro->ultima_actualizacion) : 'N/A' }}</small>
                                                    <br>
                                                    <small
                                                        class="text-muted">({{ App\Helpers\GlobalHelper::timeago($registro->ultima_actualizacion) }})</small>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <button type="button"
                                                            class="btn btn-primary shadow btn-xs sharp me-1"
                                                            wire:click="verHistorial({{ $registro->cliente_id }})"
                                                            title="Ver Historial">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="m-0 p-0">
                                            <td colspan="5" class="text-center">No se encontraron clientes con
                                                registros de puntos</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($registros->isNotEmpty())
                            <div class="row d-flex justify-content-between align-items-center flex-wrap mt-3 px-3"
                                style="justify-content: center !important;">
                                <div class="col-12 mb-2 mb-md-0 text-center d-flex justify-content-center">
                                    <small class="text-muted">
                                        Mostrando {{ $registros->count() }} de {{ $registros->total() }} clientes
                                    </small>
                                </div>
                                <div class="col-12 d-flex justify-content-center">
                                    {{ $registros->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- Vista detallada -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header mt-0 mb-0 pb-2 pt-2">
                        <h4 class="card-title">Histórico Detallado de Puntos - {{ $registros->total() }} registros</h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-responsive-md m-0 p-0 letra12">
                                <thead class="m-2 p-3 bg-primary text-white">
                                    <tr class="m-2 p-3">
                                        <th class="m-2 p-3">Partner</th>
                                        <th class="m-2 p-3">Cliente</th>
                                        <th class="m-2 p-3">Tipo</th>
                                        <th class="m-2 p-3">Pts. Partner</th>
                                        <th class="m-2 p-3">Pts. Cliente</th>
                                        <th class="m-2 p-3">Total Pts.</th>
                                        <th class="m-2 p-3">Fecha</th>
                                        <th class="m-2 p-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="m-0 p-0">
                                    @if ($registros->isNotEmpty())
                                        @foreach ($registros as $registro)
                                            <tr class="m-0 p-0">
                                                <td class="m-0 p-0">
                                                    @if ($registro->partner)
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <strong>{{ $registro->partner->name }}</strong>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Sin Partner</span>
                                                    @endif
                                                </td>
                                                <td class="m-0 p-0">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <strong>{{ $registro->cliente->name ?? 'N/A' }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <span
                                                        class="badge bg-{{ $registro->tipo == 'venta' ? 'success' : ($registro->tipo == 'descuento' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($registro->tipo) }}
                                                    </span>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <span
                                                        class="">{{ number_format($registro->puntos_partner) }}</span>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <span
                                                        class="">{{ number_format($registro->puntos_cliente) }}</span>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <span
                                                        class="fs-6">{{ number_format($registro->total_puntos) }}</span>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <small>{{ App\Helpers\GlobalHelper::fechaFormateada(3, $registro->created_at) }}
                                                        <br>
                                                        <small
                                                            class="text-muted">({{ App\Helpers\GlobalHelper::timeago($registro->created_at) }})</small>
                                                    </small>
                                                </td>
                                                <td class="m-0 p-0 text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <button type="button"
                                                            class="btn btn-primary shadow btn-xs sharp me-1"
                                                            @if ($registro->historial_venta_id) wire:click="verDetalleVenta({{ $registro->id }})"@else wire:click="verDetalleRegistro({{ $registro->id }})" @endif
                                                            title="Ver Detalle de Venta">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="m-0 p-0">
                                            <td colspan="8" class="text-center">No se encontraron registros de
                                                puntos</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($registros->isNotEmpty())
                            <div class="row d-flex justify-content-between align-items-center flex-wrap mt-3 px-3"
                                style="justify-content: center !important;">
                                <div class="col-12 mb-2 mb-md-0 text-center d-flex justify-content-center">
                                    <small class="text-muted">
                                        Mostrando {{ $registros->count() }} de {{ $registros->total() }} registros
                                    </small>
                                </div>
                                <div class="col-12 d-flex justify-content-center">
                                    {{ $registros->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    @include('livewire.admin.caja.includes.modal-detalle-venta')


    <div class="modal fade" id="modalDetalleRegistro" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content letra14">
                @isset($registroSeleccionado)
                    <div class="card">
                        <div class="modal-header">
                            <strong>Bono por Registro de Cliente: {{ $registroSeleccionado->cliente->name }}</strong>
                            <strong>Hora de registro:
                                {{ App\Helpers\GlobalHelper::fechaFormateada(6, $registroSeleccionado->cliente->created_at) }}</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table p-0 m-0 letra12 ">
                                    <thead>
                                        <tr>
                                            {{-- <th scope="col" class="py-1 text-white bg-primary">Cliente Registrado</th> --}}
                                            <th scope="col" class="py-1 text-white bg-primary">Partner</th>
                                            <th scope="col" class="py-1 text-white bg-primary">Puntos Partner</th>
                                            <th scope="col" class="py-1 text-white bg-primary">Puntos Cliente</th>
                                            <th scope="col" class="py-1 text-white bg-primary">Total Puntos</th>
                                            <th scope="col" class="py-1 text-white bg-primary">Fecha Registro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            {{-- <td>
                                                <span class="">{{ $registroSeleccionado->cliente->name }}</span>
                                            </td> --}}
                                            <td class="py-1">
                                                <span class="">{{ $registroSeleccionado->partner->name }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span class="">{{ $registroSeleccionado->puntos_partner }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span class="">{{ $registroSeleccionado->puntos_cliente }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span class="">{{ $registroSeleccionado->total_puntos }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span class="">
                                                    {{ App\Helpers\GlobalHelper::fechaFormateada(3, $registroSeleccionado->created_at) }}
                                                    <br>
                                                    <small
                                                        class="text-muted">({{ App\Helpers\GlobalHelper::timeago($registroSeleccionado->created_at) }})</small>
                                                </span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        No se pudo cargar la información de la venta.
                    </div>
                @endisset
            </div>
        </div>
    </div>




    <!-- Modal Historial de Cliente -->
    <div class="modal fade" id="modalHistorial" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-history text-primary"></i>
                        Historial de Puntos - {{ $clienteSeleccionado->name ?? 'Cliente' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    @if ($clienteSeleccionado)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Cliente:</strong> {{ $clienteSeleccionado->name }}<br>
                            </div>
                            <div class="col-md-6">
                                <strong>Total de Registros:</strong> {{ $historialCliente->count() }}<br>
                                <strong>Total de Puntos:</strong> {{ $historialCliente->sum('total_puntos') }}
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-responsive-md m-0 p-0 letra12">
                            <thead class="m-2 p-3 bg-primary text-white">
                                <tr class="m-2 p-3">
                                    <th class="m-2 p-3">Fecha</th>
                                    <th class="m-2 p-3">Partner</th>
                                    <th class="m-2 p-3">Tipo</th>
                                    <th class="m-2 p-3">Puntos Partner</th>
                                    <th class="m-2 p-3">Puntos Cliente</th>
                                    <th class="m-2 p-3">Total Puntos</th>
                                    <th class="m-2 p-3">Venta</th>
                                </tr>
                            </thead>
                            <tbody class="m-0 p-0">
                                @forelse($historialCliente as $registro)
                                    <tr class="m-0 p-0">
                                        <td class="m-0 p-0 text-center">
                                            {{ App\Helpers\GlobalHelper::fechaFormateada(3, $registro->created_at) }}
                                            <br>
                                            <small
                                                class="text-muted">({{ App\Helpers\GlobalHelper::timeago($registro->created_at) }})</small>
                                        </td>
                                        <td class="m-0 p-0">
                                            @if ($registro->partner)
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <i class="fa fa-user-tie text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $registro->partner->name }}</strong><br>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Sin Partner</span>
                                            @endif
                                        </td>
                                        <td class="m-0 p-0 text-center">
                                            <span
                                                class="badge bg-{{ $registro->tipo == 'venta' ? 'success' : ($registro->tipo == 'descuento' ? 'warning' : 'info') }}">
                                                {{ ucfirst($registro->tipo) }}
                                            </span>
                                        </td>
                                        <td class="m-0 p-0 text-center">
                                            <span class="">{{ number_format($registro->puntos_partner) }}</span>
                                        </td>
                                        <td class="m-0 p-0 text-center">
                                            <span class="">{{ number_format($registro->puntos_cliente) }}</span>
                                        </td>
                                        <td class="m-0 p-0 text-center">
                                            <span class="">{{ number_format($registro->total_puntos) }}</span>
                                        </td>
                                        <td class="m-0 p-0 text-center">
                                            @if ($registro->historialVenta)
                                                <button type="button"
                                                    class="btn btn-primary shadow btn-xs sharp me-1"
                                                    wire:click="verDetalleVenta({{ $registro->id }})"
                                                    title="Ver Detalle de Venta">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-primary shadow btn-xs sharp me-1"
                                                    wire:click="verDetalleRegistro({{ $registro->id }})"
                                                    title="Ver Detalle de Registro" data-bs-dismiss="modal">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                {{-- <span class="text-muted">N/A</span> --}}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="m-0 p-0">
                                        <td colspan="7" class="text-center">No hay registros de puntos para
                                            este cliente</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-shopping-cart text-success"></i>
                        Detalle de Venta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    @if ($registroSeleccionado)
                        @if ($detalleVenta)
                            <!-- Información de la Venta -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Información de la Venta</h6>
                                    <p class="m-0 p-0"><strong>ID Venta:</strong> {{ $detalleVenta->id }}</p>
                                    <p class="m-0 p-0"><strong>Fecha:</strong>
                                        {{ $detalleVenta->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="m-0 p-0"><strong>Total:</strong> Bs.
                                        {{ number_format($detalleVenta->total, 2) }}</p>
                                    <p class="m-0 p-0"><strong>Puntos Generados:</strong>
                                        <span
                                            class="badge bg-success">{{ number_format($detalleVenta->puntos) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Información de Usuarios</h6>
                                    <p class="m-0 p-0"><strong>Vendedor:</strong>
                                        {{ $detalleVenta->usuario->name ?? 'N/A' }}</p>
                                    <p class="m-0 p-0"><strong>Cliente:</strong>
                                        {{ $detalleVenta->cliente->name ?? 'N/A' }}</p>
                                    @if ($registroSeleccionado->partner)
                                        <p class="m-0 p-0"><strong>Partner:</strong>
                                            {{ $registroSeleccionado->partner->name }}</p>
                                    @endif
                                </div>
                            </div>
                            <!-- Productos de la Venta -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary">Productos Vendidos</h6>
                                    <div class="table-responsive">
                                        <table id="tablaProductos"
                                            class="table table-bordered table-striped table-responsive-md m-0 p-0 letra12">
                                            <thead class="m-2 p-3 bg-primary text-white">
                                                <tr class="m-2 p-3">
                                                    <th class="m-2 p-3">Producto</th>
                                                    <th class="m-2 p-3">Cantidad</th>
                                                    <th class="m-2 p-3">Precio Unitario</th>
                                                    <th class="m-2 p-3">Descuento</th>
                                                    <th class="m-2 p-3">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyProductos" class="m-0 p-0">
                                                <!-- Los productos se llenarán con JavaScript -->
                                            </tbody>
                                            <tfoot class="m-2 p-3 bg-primary text-white">
                                                <tr class="m-2 p-3">
                                                    <th colspan="4" class="m-2 p-3 text-end">Total:</th>
                                                    <th class="m-2 p-3 text-center">
                                                        <span id="totalVenta"
                                                            class="badge bg-success fs-6">$0.00</span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i>
                                Este registro de puntos no está asociado a una venta.
                            </div>
                        @endif

                        <!-- Información de Puntos (siempre visible) -->
                        <div class="row mb-0 mt-3">
                            <div class="col-12">
                                <h6 class="text-primary">Distribución de Puntos</h6>
                                <div class="row">
                                    <div class="card overflow-hidden bg-image-2 bg-transparent mb-0">
                                        <div
                                            class="card-header  border-0 d-flex justify-content-center align-items-center mb-0">
                                            <div class="row">
                                                <div class="media mb-0 align-items-center event-list col-md-4">
                                                    <div class="p-3 text-center me-3 date-bx bgl-primary">
                                                        <h2 class="mb-0 text-primary fs-28 font-w600">
                                                            {{ number_format($registroSeleccionado->puntos_partner) }}
                                                        </h2>
                                                        <h5 class="mb-1 text-black">Pts</h5>
                                                    </div>
                                                    <div class="media-body px-0">
                                                        <h6 class="mt-0 mb-3 fs-14">Puntos Partner</h6>
                                                        <ul
                                                            class="fs-14 list-inline mb-2 d-flex justify-content-between">
                                                            <li>Puntos Agregados al Partner</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="media mb-0 align-items-center event-list col-md-4">
                                                    <div class="p-3 text-center me-3 date-bx bgl-primary">
                                                        <h2 class="mb-0 text-primary fs-28 font-w600">
                                                            {{ number_format($registroSeleccionado->puntos_cliente) }}
                                                        </h2>
                                                        <h5 class="mb-1 text-black">Pts</h5>
                                                    </div>
                                                    <div class="media-body px-0">
                                                        <h6 class="mt-0 mb-3 fs-14">Puntos Cliente</h6>
                                                        <ul
                                                            class="fs-14 list-inline mb-2 d-flex justify-content-between">
                                                            <li>Puntos Agregados al Cliente</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="media mb-0 align-items-center event-list col-md-4">
                                                    <div class="p-3 text-center me-3 date-bx bgl-primary">
                                                        <h2 class="mb-0 text-primary fs-28 font-w600">
                                                            {{ number_format($registroSeleccionado->total_puntos) }}
                                                        </h2>
                                                        <h5 class="mb-1 text-black">Pts</h5>
                                                    </div>
                                                    <div class="media-body px-0">
                                                        <h6 class="mt-0 mb-3 fs-14">Total Puntos</h6>
                                                        <ul
                                                            class="fs-14 list-inline mb-2 d-flex justify-content-between">
                                                            <li>Total Puntos Agregados</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            No se pudo cargar la información de la venta.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>



<script>
    document.addEventListener('livewire:load', function() {
        // Escuchar eventos de Livewire para abrir modales
        Livewire.on('abrirModalHistorial', () => {
            // Cerrar cualquier modal abierto
            $('#modalDetalle').modal('hide');
            // Esperar un poco para que Livewire actualice la vista
            setTimeout(() => {
                $('#modalHistorial').modal('show');
            }, 200);
        });

        Livewire.on('abrirModalDetalle', () => {
            // Cerrar cualquier modal abierto
            $('#modalHistorial').modal('hide');
            // Esperar un poco para que Livewire actualice la vista
            setTimeout(() => {
                $('#modalDetalleVenta').modal('show');
            }, 200);
        });


        Livewire.on('abrirModalDetalleRegistro', () => {
            // Cerrar cualquier modal abierto
            $('#modalDetalle').modal('hide');
            // Esperar un poco para que Livewire actualice la vista
            setTimeout(() => {
                $('#modalDetalleRegistro').modal('show');
            }, 200);
        });

        // Escuchar datos de productos
        Livewire.on('datosProductos', (data) => {
            console.log('Datos de productos recibidos:', data);

            // Limpiar tabla
            $('#tbodyProductos').empty();

            if (data.productos && data.productos.length > 0) {
                let totalVenta = 0;

                data.productos.forEach(function(producto) {
                    const descuento = parseFloat(producto.descuento_producto) || 0;
                    const subtotal = parseFloat(producto.precio_subtotal) || 0;
                    totalVenta += subtotal;

                    const descuentoHtml = descuento > 0 ?
                        `<span class="badge bg-warning">$${parseFloat(producto.descuento_producto).toFixed(2)}</span>` :
                        `<span class="text-muted">Sin descuento</span>`;

                    const row = `
                        <tr class="m-0 p-0">
                            <td class="m-0 p-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <i class="fa fa-box text-info"></i>
                                    </div>
                                    <div>
                                        <strong>${producto.nombre}</strong><br>
                                        <small class="text-muted">${producto.descripcion}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="m-0 p-0 text-center">
                                <span class="badge bg-info">${producto.cantidad}</span>
                            </td>
                            <td class="m-0 p-0 text-center">
                                <span class="badge bg-secondary">Bs. ${parseFloat(producto.precio_unitario).toFixed(2)}</span>
                            </td>
                            <td class="m-0 p-0 text-center">
                                ${descuentoHtml}
                            </td>
                            <td class="m-0 p-0 text-center">
                                <span class="badge bg-success">$${parseFloat(producto.precio_subtotal).toFixed(2)}</span>
                            </td>
                        </tr>
                    `;

                    $('#tbodyProductos').append(row);
                });

                // Actualizar total
                $('#totalVenta').text('$' + totalVenta.toFixed(2));
            } else {
                // Mostrar mensaje si no hay productos
                $('#tbodyProductos').append(`
                    <tr class="m-0 p-0">
                        <td colspan="5" class="text-center">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                No se encontraron productos asociados a esta venta.
                            </div>
                        </td>
                    </tr>
                `);
            }
        });

        Livewire.on('cerrarModalHistorial', () => {
            $('#modalHistorial').modal('hide');
        });

        Livewire.on('cerrarModalDetalle', () => {
            $('#modalDetalle').modal('hide');
            // Limpiar tabla cuando se cierre el modal
            $('#tbodyProductos').empty();
            $('#totalVenta').text('Bs 0.00');
        });

        // Cerrar modales cuando se hace clic fuera o en ESC
        $('#modalHistorial').on('hidden.bs.modal', function() {
            Livewire.emit('cerrarModalHistorial');
        });

        $('#modalDetalle').on('hidden.bs.modal', function() {
            Livewire.emit('cerrarModalDetalle');
            // Limpiar tabla cuando se cierre el modal
            $('#tbodyProductos').empty();
            $('#totalVenta').text('Bs 0.00');
        });
    });
</script>
