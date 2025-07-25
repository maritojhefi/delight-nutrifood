<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de venta</title>
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap'); */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Source Sans Pro', sans-serif;
        }

        .container {
            display: block;
            width: 100%;
            background: #fff;
            max-width: 450px;
            padding: 0px;
            margin: 0 auto 0;
            box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
        }

        .receipt_header {
            padding-bottom: 40px;
            border-bottom: 1px dashed #000;
            text-align: center;
        }

        .receipt_header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .receipt_header h1 span {
            display: block;
            font-size: 25px;
        }

        .receipt_header h2 {
            font-size: 14px;
            color: #727070;
            font-weight: 300;
        }

        .receipt_header h2 span {
            display: block;
        }

        .receipt_body {
            margin-top: 25px;
        }

        table {
            width: 100%;
        }

        thead,
        tfoot {
            position: relative;
        }

        thead th:not(:last-child) {
            text-align: left;
        }

        thead th:last-child {
            text-align: right;
        }

        thead::after {
            content: '';
            width: 100%;
            border-bottom: 1px dashed #000;
            display: block;
            position: absolute;
        }

        tbody td:not(:last-child),
        tfoot td:not(:last-child) {
            text-align: left;
        }

        tbody td:last-child,
        tfoot td:last-child {
            text-align: right;
        }

        tbody tr:first-child td {
            padding-top: 15px;
        }

        tbody tr:last-child td {
            padding-bottom: 15px;
        }

        tfoot tr:first-child td {
            padding-top: 15px;
        }

        tfoot::before {
            content: '';
            width: 100%;
            border-top: 1px dashed #000;
            display: block;
            position: absolute;
        }

        tfoot tr:first-child td:first-child,
        tfoot tr:first-child td:last-child {
            font-weight: bold;
            font-size: 20px;
        }

        .date_time_con {
            display: flex;
            justify-content: center;
            column-gap: 25px;
        }

        .items {
            margin-top: 25px;
        }

        h3 {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 25px;
            text-align: center;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="receipt_header">
            {!! '<img src="' . public_path(GlobalHelper::getValorAtributoSetting('logo')) . '" />' !!}
            <h1>Recibo de Venta <span>{{ GlobalHelper::getValorAtributoSetting('nombre_empresa') }}</span></h1>
            <h2>Direccion: {{ GlobalHelper::getValorAtributoSetting('direccion') }}<span>Contacto : {{ GlobalHelper::getValorAtributoSetting('telefono') }}</span>
                @if (isset($nombreCliente))
                    <span>Cliente: {{ $nombreCliente }}</span>
                @endif
            </h2>
        </div>

        <div class="receipt_body">

            <div class="date_time_con">

                @if (isset($fecha))
                    <div class="date">Fecha de emision: {{ $fecha }} </div>
                @else
                    <div class="date"> {{ date('Y-m-d') }} </div>
                    <div class="time"> {{ date('H:i:s') }}</div>
                @endif


            </div>

            <div class="items">
                <table>

                    <thead>
                        <th style="width:10%">U.</th>
                        <th style="width:50%">Producto</th>
                        <th>Unitario</th>
                        <th>Precio</th>
                    </thead>

                    <tbody>

                        @foreach ($listaCuenta as $list)
                            <tr>
                                <td>
                                    <center>{{ $list['cantidad'] }}</center>
                                </td>
                                <td><small>{{ $list['nombre'] }}</small></td>
                                <td><small>Bs {{ $list['precio'] }}</small></td>
                                <th><small>Bs {{ $list['precio'] * $list['cantidad'] }}</small></th>
                            </tr>
                            <br>
                        @endforeach

                    </tbody>

                    <tfoot>
                        <tr>
                            <td></td>
                            <td>Subtotal</td>

                            <td></td>
                            <td>Bs {{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <br>
                        @if ($descuentoProductos != 0)
                            <tr>
                                <td></td>
                                <td><small>Descuento por productos</small> </td>

                                <td></td>
                                <td><small>Bs {{ number_format($descuentoProductos, 2) }}</small></td>
                            </tr>
                        @endif

                        @if ($otrosDescuentos != 0)
                            <tr>
                                <td></td>
                                <td><small>Otros descuentos</small></td>

                                <td></td>
                                <td><small>Bs {{ number_format($otrosDescuentos, 2) }}</small></td>
                            </tr>
                        @endif
                        @if ($valorSaldo != null && $valorSaldo != 0)
                            <tr>
                                <td></td>
                                <td>{{ $cuenta->a_favor_cliente ? 'A favor cliente' : 'Deuda Generada' }}</td>

                                <td></td>
                                <td>Bs {{ number_format($valorSaldo, 2) }}</td>
                            </tr>
                            <br>
                            <tr>
                                <td></td>
                                <td><strong> TOTAL PAGADO</strong></td>

                                <td></td>
                                <td><strong>Bs
                                        {{ number_format($cuenta->total_pagado, 2) }}</strong>
                                </td>
                            </tr>
                        @else
                            <br>
                            <tr>
                                <td></td>
                                <td><strong> TOTAL PAGADO</strong></td>

                                <td></td>
                                <td><strong> Bs
                                        {{ number_format($cuenta->total_pagado, 2) }}</strong>
                                </td>
                            </tr>
                        @endif
                    </tfoot>

                </table>
                <br>
                <hr>
                <br>
                @if (isset($metodo) && $metodo != '')
                    Metodos de pago:
                    @foreach ($metodo as $item)
                        <p>- {{ $item->nombre_metodo_pago }}</p>
                    @endforeach
                @else
                    Metodos de pago:
                    <p>- Efectivo</p>
                @endif
                @if (isset($observacion))
                    <p>Informacion Adicional: {{ $observacion }}</p>
                @endif

                <br>
                <br>
                <center> <img src="{{ public_path('qrcode.png') }}" /></center>



            </div>

        </div>


        <h3>Gracias por su compra!</h3>

    </div>

</body>

</html>
