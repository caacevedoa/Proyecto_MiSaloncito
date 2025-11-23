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

        <label for="order_id">ID de Orden:</label>
        <input 
            type="text" 
            name="order_id" 
            id="order_id"
            value="{{ $orderDetail->order_id }}"
        >
        <br>

        <label for="product">Producto:</label>
        <input 
            type="text" 
            name="product" 
            id="product"
            value="{{ $orderDetail->product }}"
        >
        <br>

        <label for="quantity">Cantidad:</label>
        <input 
            type="number" 
            name="quantity" 
            id="quantity"
            value="{{ $orderDetail->quantity }}"
        >
        <br>

        <label for="price">Precio:</label>
        <input 
            type="number" 
            step="0.01"
            name="price" 
            id="price"
            value="{{ $orderDetail->price }}"
        >
        <br>

        <button type="submit">Actualizar Detalle</button>

    </form>

</body>
</html>
