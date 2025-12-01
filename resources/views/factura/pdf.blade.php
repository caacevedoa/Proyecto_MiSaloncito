<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura - Mi Saloncito</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            width: 310px; /* Ancho tipo impresora térmica */
            margin: auto;
            font-family: "Courier New", monospace;
            font-size: 14px;
        }

        .logo {
            width: 80px;
            display: block;
            margin: 0 auto 10px auto;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .totals {
            font-size: 16px;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }
    </style>
</head>
<body>


    <p class="center"><strong>MI SALONCITO</strong></p>
    <p class="center">NIT: 901.693.558</p>
    <p class="center">Fecha: {{ now()->setTimezone('America/Bogota')->format('d/m/Y h:i A') }}</p>

    <div class="line"></div>

    <p><strong>Orden #: </strong>{{ $order->id }}</p>
    <p><strong>Mesa: </strong>{{ $order->table_id }}</p>

    <div class="line"></div>

    <p class="center"><strong>Detalle de Productos</strong></p>

    @foreach ($order->orderDetails as $item)
        <div class="d-flex justify-content-between">
            <span>{{ $item->quantity }} x {{ $item->product->product_name }}</span>
            <span>${{ number_format($item->unit_price) }}</span>
        </div>

        <div class="d-flex justify-content-between mb-1">
            <small>Subtotal:</small>
            <small>${{ number_format($item->subtotal) }}</small>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="d-flex justify-content-between totals">
        <span>TOTAL:</span>
        <span>${{ number_format($total) }}</span>
    </div>

    <div class="line"></div>

    <p class="center"><strong>PAGO</strong></p>

    @if ($payment)
        <p><strong>Método:</strong> {{ strtoupper($payment->payment_method) }}</p>
        <p><strong>Estado:</strong> {{ strtoupper($payment->payment_status) }}</p>
    @else
        <p style="color:red">❗ Sin pago registrado</p>
    @endif

    <div class="line"></div>

    <p class="center">¡Gracias por su compra!</p>
    <p class="center">Mi Saloncito</p>

</body>
</html>
