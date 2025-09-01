<div class="row">
    <div class="col-12">
        @if (!$mostrarDetalles)
            <!-- VISTA INICIAL: CARDS POR MESES -->
            <div class="row">
                @foreach ($mesesDisponibles as $mes)
                    <div class="col-12 col-sm-6 col-md-4 px-1 m-0 py-0">
                        <div class="card py-0 bordeado">
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="col-6 letra14">
                                        <strong>{{ $mes['nombre_completo'] }}</strong>
                                        <ul class="mt-2 text-center">
                                            <li><span class="float-start"><i class="fa fa-stop"
                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion(0) }}"></i>
                                                    Total Ventas:</span> <br>
                                                <strong
                                                    class="">{{ number_format($this->getTotalVentasMes($mes['mes'], $mes['anio']), 2) }}
                                                    Bs</strong>
                                            </li>
                                            <li><span class="float-start"><i class="fa fa-stop"
                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion(1) }}"></i>
                                                    Clientes Activos:</span> <br>
                                                <strong
                                                    class="">{{ $this->getTotalClientesActivos($mes['mes'], $mes['anio']) }}</strong>
                                            </li>
                                            <li><span class="float-start"><i class="fa fa-stop text-success"></i>
                                                    Productos Vendidos:</span> <br>
                                                <strong
                                                    class="">{{ $this->getTotalProductosVendidos($mes['mes'], $mes['anio']) }}</strong>
                                            </li>
                                        </ul>
                                        <a href="#"
                                            wire:click="mostrarDetalles({{ $mes['mes'] }}, {{ $mes['anio'] }})">
                                            <span class="badge badge-primary badge-xxs letra14 py-1">Ver detalles</span>
                                        </a>
                                    </div>
                                    <div class="col-6" style="height: 200px; overflow: hidden;">
                                        <!-- Mini-gráfico del mes -->
                                        <img src="{{ $this->getMiniGraficoMes($mes['mes'], $mes['anio']) }}"
                                            style="width: 100%; height: 100%; object-fit: cover;" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- VISTA DE DETALLES: GRÁFICOS COMPLETOS -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="#" wire:click="volverALista" class="btn btn-warning btn-sm">
                        <i class="fa fa-arrow-left me-2"></i>Volver al listado
                    </a>
                    <span class="ms-3">
                        <strong>Mostrando gráficos de:
                            {{ \App\Helpers\CajaReporteHelper::obtenerNombreMes($mesSeleccionado) }}
                            {{ $anioSeleccionado }}</strong>
                    </span>
                </div>
            </div>

            <!-- GRÁFICO 1: TOP 10 CLIENTES -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card p-0 bordeado">
                        <div class="card-header">
                            <h4 class="card-title">Top 10 Clientes que Más Compraron -
                                {{ \App\Helpers\CajaReporteHelper::obtenerNombreMes($mesSeleccionado) }}
                                {{ $anioSeleccionado }}</h4>
                        </div>

                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mb-3">
                                    <div class="table-responsive">
                                        <div style="max-height: 450px !important; overflow-y: auto;">
                                            <table class="table p-0 m-0 letra12">
                                                <thead>
                                                    <tr>
                                                        <th class="text-white bg-primary">Cliente</th>
                                                        <th class="text-white bg-primary">Compras</th>
                                                        <th class="text-white bg-primary">Monto Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalCompras = 0;
                                                        $montoTotalClientes = 0;
                                                    @endphp
                                                    @foreach ($this->getTop10Clientes() as $cliente)
                                                        <tr>
                                                            <td class="py-1">
                                                                <span class="float-start">
                                                                    <i class="fa fa-stop"
                                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion($loop->index) }}"></i>
                                                                    <strong>{{ Str::limit($cliente->nombre_cliente, 25) }}</strong>
                                                                </span>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ $cliente->total_compras }}</strong>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ number_format($cliente->monto_total, 2) }}
                                                                    Bs</strong>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $totalCompras += $cliente->total_compras;
                                                            $montoTotalClientes += $cliente->monto_total;
                                                        @endphp
                                                    @endforeach
                                                    <tr style="background-color: #20c99745; font-weight: bold;">
                                                        <td class="py-1">
                                                            <strong>Total</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ $totalCompras }}</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ number_format($montoTotalClientes, 2) }}
                                                                Bs</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <div class="row" style="height: 400px; overflow: hidden;"
                                        id="contenedor-grafico">
                                        <img src="{{ $this->getGraficoTop10Clientes() }}" class="img-graficos"
                                            style="width: 100%; height: 95%; object-fit: cover;"
                                            alt="Gráfico Top 10 Clientes">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICO 2: MÉTODOS DE PAGO -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card p-0 bordeado">
                        <div class="card-header">
                            <h4 class="card-title">Métodos de Pago Más Usados -
                                {{ \App\Helpers\CajaReporteHelper::obtenerNombreMes($mesSeleccionado) }}
                                {{ $anioSeleccionado }}</h4>
                        </div>

                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mb-3">
                                    <div class="table-responsive">
                                        <div style="max-height: 450px !important; overflow-y: auto;">
                                            <table class="table p-0 m-0 letra12">
                                                <thead>
                                                    <tr>
                                                        <th class="text-white bg-primary">Método</th>
                                                        <th class="text-white bg-primary">Ventas</th>
                                                        <th class="text-white bg-primary">Monto Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalVentasMetodos = 0;
                                                        $montoTotalMetodos = 0;
                                                    @endphp
                                                    @foreach ($this->getMetodosPagoMasUsados() as $metodo)
                                                        <tr>
                                                            <td class="py-1">
                                                                <span class="float-start">
                                                                    <i class="fa fa-stop"
                                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion($loop->index) }}"></i>
                                                                    <strong>{{ Str::limit($metodo->nombre_metodo_pago, 25) }}</strong>
                                                                </span>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ $metodo->total_ventas }}</strong>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ number_format($metodo->monto_total, 2) }}
                                                                    Bs</strong>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $totalVentasMetodos += $metodo->total_ventas;
                                                            $montoTotalMetodos += $metodo->monto_total;
                                                        @endphp
                                                    @endforeach
                                                    <tr style="background-color: #20c99745; font-weight: bold;">
                                                        <td class="py-1">
                                                            <strong>Total</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ $totalVentasMetodos }}</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ number_format($montoTotalMetodos, 2) }}
                                                                Bs</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <div class="row" style="height: 400px; overflow: hidden;"
                                        id="contenedor-grafico">
                                        <img src="{{ $this->getGraficoMetodosPago() }}" class="img-graficos"
                                            style="width: 100%; height: 95%; object-fit: cover;"
                                            alt="Gráfico Métodos de Pago">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICO 3: TOP 10 PRODUCTOS -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card p-0 bordeado">
                        <div class="card-header">
                            <h4 class="card-title">Top 10 Productos Más Vendidos -
                                {{ \App\Helpers\CajaReporteHelper::obtenerNombreMes($mesSeleccionado) }}
                                {{ $anioSeleccionado }}</h4>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mb-3">
                                    <div class="table-responsive">
                                        <div style="max-height: 450px !important; overflow-y: auto;">
                                            <table class="table p-0 m-0 letra12">
                                                <thead>
                                                    <tr>
                                                        <th class="text-white bg-primary">Producto</th>
                                                        <th class="text-white bg-primary">Cant.</th>
                                                        <th class="text-white bg-primary">Monto Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $cantidadTotalProductos = 0;
                                                        $montoTotalProductos = 0;
                                                    @endphp
                                                    @foreach ($this->getTop10Productos() as $producto)
                                                        <tr>
                                                            <td class="py-1">
                                                                <span class="float-start">
                                                                    <i class="fa fa-stop"
                                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion($loop->index) }}"></i>
                                                                    <strong>{{ Str::limit($producto->nombre, 25) }}</strong>
                                                                </span>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ $producto->cantidad_total }}</strong>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ number_format($producto->monto_total, 2) }}
                                                                    Bs</strong>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $cantidadTotalProductos += $producto->cantidad_total;
                                                            $montoTotalProductos += $producto->monto_total;
                                                        @endphp
                                                    @endforeach
                                                    <tr style="background-color: #20c99745; font-weight: bold;">
                                                        <td class="py-1">
                                                            <strong>Total</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ $cantidadTotalProductos }}</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ number_format($montoTotalProductos, 2) }}
                                                                Bs</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <div class="row" style="height: 400px; overflow: hidden;"
                                        id="contenedor-grafico">
                                        <img src="{{ $this->getGraficoTop10Productos() }}" class="img-graficos"
                                            style="width: 100%; height: 95%; object-fit: cover;"
                                            alt="Gráfico Top 10 Productos">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICO 4: CATEGORÍAS MÁS VENDIDAS -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card p-0 bordeado">
                        <div class="card-header">
                            <h4 class="card-title">Categorías Más Vendidas -
                                {{ \App\Helpers\CajaReporteHelper::obtenerNombreMes($mesSeleccionado) }}
                                {{ $anioSeleccionado }}</h4>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mb-3">
                                    <div class="table-responsive">
                                        <div style="max-height: 450px !important; overflow-y: auto;">
                                            <table class="table p-0 m-0 letra12">
                                                <thead>
                                                    <tr>
                                                        <th class="text-white bg-primary">Categoría</th>
                                                        <th class="text-white bg-primary">Cant.</th>
                                                        <th class="text-white bg-primary">Monto Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $cantidadTotalCategorias = 0;
                                                        $montoTotalCategorias = 0;
                                                    @endphp
                                                    @foreach ($this->getCategoriasMasVendidas() as $categoria)
                                                        <tr>
                                                            <td class="py-1">
                                                                <span class="float-start">
                                                                    <i class="fa fa-stop"
                                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion($loop->index) }}"></i>
                                                                    <strong>{{ Str::limit($categoria->nombre_categoria, 25) }}</strong>
                                                                </span>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ $categoria->cantidad_total }}</strong>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ number_format($categoria->monto_total, 2) }}
                                                                    Bs</strong>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $cantidadTotalCategorias += $categoria->cantidad_total;
                                                            $montoTotalCategorias += $categoria->monto_total;
                                                        @endphp
                                                    @endforeach
                                                    <tr style="background-color: #20c99745; font-weight: bold;">
                                                        <td class="py-1">
                                                            <strong>Total</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ $cantidadTotalCategorias }}</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ number_format($montoTotalCategorias, 2) }}
                                                                Bs</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <div class="row" style="height: 400px; overflow: hidden;"
                                        id="contenedor-grafico">
                                        <img src="{{ $this->getGraficoCategorias() }}" class="img-graficos"
                                            style="width: 100%; height: 95%; object-fit: cover;"
                                            alt="Gráfico Categorías">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICO 5: COMPARATIVA DE MESES -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card p-0 bordeado">
                        <div class="card-header">
                            <h4 class="card-title">Comparativa de Ventas - Últimos 3 Meses</h4>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 mb-3">
                                    <div class="table-responsive">
                                        <div style="max-height: 450px !important; overflow-y: auto;">
                                            <table class="table p-0 m-0 letra12">
                                                <thead>
                                                    <tr>
                                                        <th class="text-white bg-primary">Mes</th>
                                                        <th class="text-white bg-primary">Monto Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $montoTotalMeses = 0;
                                                    @endphp
                                                    @foreach ($this->getComparativaMeses() as $mes)
                                                        <tr>
                                                            <td class="py-1">
                                                                <span class="float-start">
                                                                    <i class="fa fa-stop"
                                                                        style="color: {{ \App\Helpers\GraficosHelper::obtenerColorPosicion($loop->index) }}"></i>
                                                                    <strong>{{ $mes['nombre'] }}
                                                                        {{ $mes['anio'] }}</strong>
                                                                </span>
                                                            </td>
                                                            <td class="py-1">
                                                                <strong>{{ number_format($mes['monto'], 2) }}
                                                                    Bs</strong>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $montoTotalMeses += $mes['monto'];
                                                        @endphp
                                                    @endforeach
                                                    <tr style="background-color: #20c99745; font-weight: bold;">
                                                        <td class="py-1">
                                                            <strong>Total 3 Meses</strong>
                                                        </td>
                                                        <td class="py-1">
                                                            <strong>{{ number_format($montoTotalMeses, 2) }}
                                                                Bs</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <div class="row" style="height: 400px; overflow: hidden;"
                                        id="contenedor-grafico">
                                        <img src="{{ $this->getGraficoComparativaMeses() }}" class="img-graficos"
                                            style="width: 100%; height: 95%; object-fit: cover;"
                                            alt="Gráfico Comparativa de Meses">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>


@push('scripts')
    <script>
        // Auto-refresh cada 5 minutos para mantener los gráficos actualizados
        setInterval(function() {
            Livewire.emit('refresh');
        }, 300000);
    </script>
@endpush

