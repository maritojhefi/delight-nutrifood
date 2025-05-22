<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de venta</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.2;
        }

        .ticket-pos {
            width: 58mm;
            padding: 5px;
        }

        .ticket-header,
        .ticket-footer,
        .ticket-payment-method,
        .ticket-notes,
        .ticket-qr,
        .ticket-client {
            text-align: center;
        }

        .ticket-title {
            font-size: 14px;
            font-weight: bold;
            margin: 3px 0;
        }

        .ticket-subtitle {
            font-size: 12px;
            margin: 2px 0;
        }

        .ticket-contact {
            font-size: 10px;
            margin-bottom: 6px;
        }

        .ticket-client {
            font-size: 11px;
            margin-bottom: 8px;
        }

        .ticket-divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .ticket-items {
            margin-bottom: 8px;
        }

        .ticket-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .ticket-item-name {
            flex: 3;
            text-align: left;
            word-break: break-word;
        }

        .ticket-item-price {
            flex: 1;
            text-align: right;
            white-space: nowrap;
        }

        .ticket-totals {
            margin-top: 8px;
        }

        .ticket-total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .ticket-total-label {
            flex: 2;
            text-align: left;
        }

        .ticket-total-value {
            flex: 1;
            text-align: right;
        }

        .ticket-grand-total {
            font-size: 13px;
            font-weight: bold;
            margin: 8px 0;
            text-align: center;
        }

        .ticket-payment-method {
            font-size: 11px;
            margin: 8px 0;
        }

        .ticket-notes {
            font-size: 10px;
            font-style: italic;
            margin: 8px 0;
        }

        .ticket-footer {
            font-size: 10px;
            margin-top: 8px;
        }

        .ticket-feed-1 {
            height: 8px;
        }

        .ticket-feed-2 {
            height: 16px;
        }

        .ticket-feed-3 {
            height: 24px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="ticket-pos">
        <!-- Encabezado -->
        <div class="ticket-header">
            <img src="{{ asset('delight_logo.jpg') }}" width="150"> <!-- Usar asset() para rutas -->
            <div class="ticket-title">Nutri-Food/Eco-Tienda</div>
            <div class="ticket-subtitle">'NUTRIENDO HABITOS!'</div>
            <div class="ticket-contact">
                Contacto: 78227629<br>
                Campero e/15 de abril y Madrid
            </div>
            @if (isset($nombreCliente))
                <div class="ticket-client">Cliente: {{ $nombreCliente }}</div>
            @endif
        </div>

        <!-- Línea divisoria -->
        <div class="ticket-divider">--------------</div>

        <!-- Items -->
        <div class="ticket-items">
            @foreach ($listaCuenta as $list)
                <div class="ticket-item">
                    <span class="ticket-item-name">
                        @if ($list['cantidad'] == 1)
                            {{ $list['cantidad'] }}x {{ $list['nombre'] }}
                        @else
                            {{ $list['cantidad'] }}x {{ $list['nombre'] }}({{ $list['precio'] }} c/u)
                        @endif
                    </span>
                    <span class="ticket-item-price">Bs {{ number_format($list['subtotal'], 2) }}</span>
                </div>
            @endforeach
        </div>

        <!-- Línea divisoria -->
        <div class="ticket-divider">--------</div>

        <!-- Totales -->
        <div class="ticket-totals">
            <div class="ticket-total-line">
                <span class="ticket-total-label">Subtotal:</span>
                <span class="ticket-total-value">Bs {{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="ticket-total-line">
                <span class="ticket-total-label">Descuento por productos:</span>
                <span class="ticket-total-value">Bs {{ number_format($descuentoProductos, 2) }}</span>
            </div>
            <div class="ticket-total-line">
                <span class="ticket-total-label">Otros descuentos:</span>
                <span class="ticket-total-value">Bs {{ number_format($otrosDescuentos, 2) }}</span>
            </div>

            @if ($valorSaldo != null && $valorSaldo != 0)
                <div class="ticket-feed-1"></div>
                <div class="ticket-total-line">
                    <span class="ticket-total-label">Saldo agregado:</span>
                    <span class="ticket-total-value">Bs {{ number_format($valorSaldo, 2) }}</span>
                </div>
                <div class="ticket-feed-1"></div>
                <div class="ticket-grand-total">
                    TOTAL PAGADO: Bs
                    {{ number_format($subtotal - $otrosDescuentos - $valorSaldo - $descuentoProductos, 2) }}
                </div>
            @else
                <div class="ticket-feed-1"></div>
                <div class="ticket-grand-total">
                    TOTAL PAGADO: Bs {{ number_format($subtotal - $otrosDescuentos - $descuentoProductos, 2) }}
                </div>
            @endif
        </div>

        <!-- Método de pago -->
        @if (isset($metodo) && $metodo != '')
            <div class="ticket-payment-method">Método: {{ $metodo }}</div>
        @endif

        <!-- QR Code -->
        <div class="ticket-feed-1"></div>
        <div class="ticket-qr">
            <img src="{{ asset('qrcode.png') }}" width="100">
        </div>

        <!-- Observaciones -->
        @if (isset($observacion))
            <div class="ticket-feed-1"></div>
            <div class="ticket-notes">{{ $observacion }}</div>
            <div class="ticket-feed-1"></div>
        @endif

        <!-- Pie de página -->
        <div class="ticket-footer">
            <div>Ingresa a nuestra plataforma!</div>
            <div class="ticket-feed-1"></div>
            <div>Gracias por tu compra</div>
            <div>Vuelve pronto!</div>
            <div class="ticket-feed-1"></div>
            <div>{{ $fecha ?? date('Y-m-d H:i:s') }}</div>
        </div>

        <!-- Espacio final equivalente a feed(3) -->
        <div class="ticket-feed-3"></div>
    </div>

</body>

</html>
