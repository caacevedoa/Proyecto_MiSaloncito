<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Detalle de Orden</title>
</head>
<body>

<h1>Editar Detalle de Orden</h1>

<form action="{{ route('ordersdetail.update', $orderDetail->id) }}" method="post">
    @method('PUT')
    @csrf

    <label for="order_id">Orden:</label>
    <input type="text" name="order_id" value="{{ $orderDetail->order_id }}" readonly>
    <br>

    <label for="product_id">Producto:</label>
    <select name="product_id" id="product_id">
        @foreach ($products as $product)
            <option value="{{ $product->id }}" 
                {{ $product->id == $orderDetail->product_id ? 'selected' : '' }}>
                {{ $product->product_name }}
            </option>
        @endforeach
    </select>
    <br>

    <label for="quantity">Cantidad:</label>
    <input type="number" name="quantity" value="{{ $orderDetail->quantity }}">
    <br>

    <button type="submit">Actualizar Detalle</button>
</form>

</body>
</html>
