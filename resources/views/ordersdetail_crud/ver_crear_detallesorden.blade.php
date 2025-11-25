<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Detalle de Orden</title>
</head>
<body>

    <h1>Crear Detalle de Orden</h1>

    <form action="{{ route('ordersdetail.store') }}" method="post">
        @csrf

        <label for="order_id">Seleccione la orden:</label>
        <select name="order_id" id="order_id">
            @foreach ($orders as $order)
                <option value="{{ $order->id }}">Orden {{ $order->id }}</option>
            @endforeach
        </select>
        <br>

        <label for="product_id">Seleccione el producto:</label>
        <select name="product_id" id="product_id">
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
            @endforeach
        </select>
        <br>

        <label for="quantity">Cantidad:</label>
        <input type="number" name="quantity" id="quantity">
        <br>

        <button type="submit">Crear Detalle</button>

    </form>


    <h1>Listado de Detalles de Orden</h1>

    @php
        $groupedDetails = $details->groupBy('order_id');
    @endphp

    @foreach ($groupedDetails as $orderId => $orderDetails)
        <h2 style="margin-top: 30px; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
            🛒 Detalles de la Orden #{{ $orderId }}
            (Mesa: {{ $orderDetails->first()->order->table->table_number ?? 'N/A' }})
        </h2>

        <table border="1" width="100%">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>ID Detalle</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $orderTotal = 0;
                @endphp
                
                @foreach ($orderDetails as $detail)
                    @php
                        // Sumamos el subtotal para calcular el total de la orden
                        $orderTotal += ($detail->subtotal); 
                    @endphp
                    <tr>
                        <td>{{ $detail->id }}</td>
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>${{ number_format($detail->unit_price, 2) }}</td>
                        <td>${{ number_format($detail->subtotal, 2) }}</td>

                        <td>
                            <a href="{{ route('ordersdetail.edit', $detail->id) }}">Editar</a>

                            <form action="{{ route('ordersdetail.destroy', $detail->id) }}" 
                                    method="POST" 
                                    style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                
                <tr style="font-weight: bold; background-color: #e0e0e0;">
                    <td colspan="4" style="text-align: right; padding-right: 10px;">TOTAL ORDEN #{{ $orderId }}:</td>
                    <td>${{ number_format($orderTotal, 2) }}</td>
                    <td></td> {{-- Celda vacía para acciones --}}
                </tr>
            </tbody>
        </table>
    @endforeach

    @if ($groupedDetails->isEmpty())
        <p>No hay detalles de órdenes registrados.</p>
    @endif

</body>
</html>