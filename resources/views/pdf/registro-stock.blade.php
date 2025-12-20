<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cambios de Stock</title>
    <style>
        @page {
            margin: 20mm 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            padding: 15px 0 20px 0;
            border-bottom: 3px solid #667eea;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 22px;
            color: #667eea;
            margin-bottom: 8px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
            padding: 4px 0;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #495057;
            width: 35%;
            font-size: 11px;
        }

        .info-value {
            display: table-cell;
            color: #212529;
            width: 65%;
            text-align: right;
            font-size: 11px;
        }

        .detalle-section {
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            border-left: 4px solid #6c757d;
        }

        .detalle-section h3 {
            font-size: 12px;
            color: #495057;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .detalle-text {
            color: #495057;
            font-size: 10px;
            line-height: 1.6;
        }

        .productos-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
            padding: 10px 12px;
            border-radius: 6px;
            border-left: 4px solid;
        }

        .section-title.aumentos {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .section-title.disminuciones {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .section-title.sin-cambio {
            background: #e2e3e5;
            color: #383d41;
            border-left-color: #6c757d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        table thead {
            background: #667eea;
            color: white;
        }

        table thead th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            border: none;
        }

        table thead th.text-center {
            text-align: center;
        }

        table tbody td {
            padding: 9px 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 10px;
            vertical-align: middle;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        table tbody tr:hover {
            background: #e9ecef;
        }

        .stock-cell {
            text-align: center;
            font-weight: 600;
        }

        .stock-anterior {
            color: #6c757d;
            font-size: 10px;
        }

        .stock-cambio {
            font-weight: bold;
            font-size: 11px;
            margin: 0 5px;
        }

        .stock-posterior {
            color: #212529;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            min-width: 35px;
            text-align: center;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .arrow {
            margin: 0 8px;
            color: #6c757d;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 9px;
        }

        .no-data {
            text-align: center;
            padding: 30px 20px;
            color: #6c757d;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .product-name {
            font-weight: 600;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>REGISTRO DE CAMBIOS DE STOCK</h1>
            <h2>ID: #{{ $registro->id }}</h2>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Fecha y Hora:</span>
                <span class="info-value">{{ $fecha }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Usuario:</span>
                <span class="info-value">{{ $usuario ? $usuario->name : 'N/A' }}</span>
            </div>
            @if($caja)
            <div class="info-row">
                <span class="info-label">Caja:</span>
                <span class="info-value">#{{ $caja->id }} - {{ $caja->created_at ? $caja->created_at->format('d/m/Y') : 'N/A' }}</span>
            </div>
            @endif
        </div>

        <div class="detalle-section">
            <h3>Detalle General</h3>
            <div class="detalle-text">
                {{ $registro->detalle ?: 'Sin detalle' }}
            </div>
        </div>

        @if(count($aumentos) > 0)
        <div class="productos-section">
            <div class="section-title aumentos">
                AUMENTOS DE STOCK ({{ count($aumentos) }})
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 35%;">Producto</th>
                        <th style="width: 35%; text-align: center;">Stock Anterior → Cambio → Stock Posterior</th>
                        <th style="width: 30%;">Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aumentos as $producto)
                    <tr>
                        <td class="product-name">{{ $producto['nombre'] }}</td>
                        <td class="stock-cell">
                            <span class="stock-anterior">{{ $producto['stock_anterior'] }}</span>
                            <span class="arrow">→</span>
                            <span class="badge badge-success stock-cambio">+{{ $producto['cantidad'] }}</span>
                            <span class="arrow">→</span>
                            <span class="stock-posterior">{{ $producto['stock_posterior'] }}</span>
                        </td>
                        <td style="font-size: 9px;">{{ $producto['detalle'] ?: 'Sin detalle' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(count($disminuciones) > 0)
        <div class="productos-section">
            <div class="section-title disminuciones">
                DISMINUCIONES DE STOCK ({{ count($disminuciones) }})
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 35%;">Producto</th>
                        <th style="width: 35%; text-align: center;">Stock Anterior → Cambio → Stock Posterior</th>
                        <th style="width: 30%;">Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disminuciones as $producto)
                    <tr>
                        <td class="product-name">{{ $producto['nombre'] }}</td>
                        <td class="stock-cell">
                            <span class="stock-anterior">{{ $producto['stock_anterior'] }}</span>
                            <span class="arrow">→</span>
                            <span class="badge badge-danger stock-cambio">-{{ $producto['cantidad'] }}</span>
                            <span class="arrow">→</span>
                            <span class="stock-posterior">{{ $producto['stock_posterior'] }}</span>
                        </td>
                        <td style="font-size: 9px;">{{ $producto['detalle'] ?: 'Sin detalle' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(count($sinCambio) > 0)
        <div class="productos-section">
            <div class="section-title sin-cambio">
                SIN CAMBIOS ({{ count($sinCambio) }})
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Producto</th>
                        <th style="width: 25%; text-align: center;">Stock</th>
                        <th style="width: 25%;">Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sinCambio as $producto)
                    <tr>
                        <td class="product-name">{{ $producto['nombre'] }}</td>
                        <td class="stock-cell">
                            <span class="badge badge-secondary">{{ $producto['stock_posterior'] }}</span>
                        </td>
                        <td style="font-size: 9px;">{{ $producto['detalle'] ?: 'Sin detalle' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">
            <p>Documento generado el {{ date('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
