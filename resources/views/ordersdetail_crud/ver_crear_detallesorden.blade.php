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


    {{-- ========================================================= --}}
    {{-- 👇 TABLA AJUSTADA A LOS DETALLES DE ORDEN --}}
    {{-- ========================================================= --}}

    <h1>Listado de Detalles de Orden</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Orden</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($details as $detail)
                <tr>
                    <td>{{ $detail->id }}</td>
                    <td>Orden {{ $detail->order->id }}</td>
                    <td>{{ $detail->product->product_name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->unit_price }}</td>
                    <td>{{ $detail->subtotal }}</td>

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
        </tbody>
    </table>

</body>
</html>
