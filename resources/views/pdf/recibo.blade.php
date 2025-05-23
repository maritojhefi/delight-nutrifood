<!DOCTYPE html>
<html>
<head>
    <title>Recibo de Compra</title>
</head>
<body>
    <div style="text-align: center;">
        {!! '<img src="' . public_path("delight_logo.jpg") . '" />' !!}
        <h1>Nutri-Food/Comida Nutritiva</h1>
        <p>'NUTRIENDO HABITOS!'</p>
        <p>Contacto : 78227629</p>
        @if (isset($nombreCliente))
            <p>Cliente: {{ $nombreCliente }}</p>
        @endif
        <h2>--------------</h2>

        @foreach ($listaCuenta as $list)
            <div style="text-align: left;">
                @if ($list['cantidad'] == 1)
                    <p>{{ $list['cantidad'] }}x {{ $list['nombre'] }}</p>
                @else
                    <p>{{ $list['cantidad'] }}x {{ $list['nombre'] }} ({{ $list['precio'] }} c/u)</p>
                @endif
                <p>Bs {{ number_format($list['subtotal'], 2) }}</p>
            </div>
        @endforeach

        <h2>----------</h2>
        <p>Subtotal: Bs {{ number_format($subtotal, 2) }}</p>
        <p>Descuento por productos: Bs {{ number_format($descuentoProductos, 2) }}</p>
        <p>Otros descuentos: Bs {{ number_format($otrosDescuentos, 2) }}</p>
        @if ($valorSaldo != null && $valorSaldo != 0)
            <p>Saldo agregado: Bs {{ number_format($valorSaldo, 2) }}</p>
            <h2>TOTAL PAGADO: Bs {{ number_format($subtotal - $otrosDescuentos - $valorSaldo - $descuentoProductos, 2) }}</h2>
        @else
            <h2>TOTAL PAGADO: Bs {{ number_format($subtotal - $otrosDescuentos - $descuentoProductos, 2) }}</h2>
        @endif
        @if (isset($metodo) && $metodo != "")
            <p>Metodo: {{ $metodo }}</p>
        @endif

        <img src="{{ public_path("qrcode.png") }}" />

        @if (isset($observacion))
            <p>{{ $observacion }}</p>
        @endif

        <p>Ingresa a nuestra plataforma!</p>
        <p>Gracias por tu compra</p>
        <p>Vuelve pronto!</p>
        @if (isset($fecha))
            <p>{{ $fecha }}</p>
        @else
            <p>{{ date("Y-m-d H:i:s") }}</p>
        @endif
    </div>
</body>
</html>
