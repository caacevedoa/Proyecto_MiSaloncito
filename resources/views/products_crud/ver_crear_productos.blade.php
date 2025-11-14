<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="{{route('products.store')}}" method="post">
        @csrf
        <label for="product_name">Nombre del producto:</label>
        <input type="text" name="product_name" id="product_name"><br>

        <label for="product_type">Tipo de producto:</label>
        <input type="text" name="product_type" id="product_type"><br>

        <label for="unit_price">Precio unitario:</label>
        <input type="number" name="unit_price" id="unit_price" step="100"><br>

        <label for="product_status">Seleccione del producto:</label>
        <select name="product_status" id="product_status">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>


        </select><br>

        <button type="submit">Crear Producto</button>

    </form>

    <h1>Listado de productos</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del prodcuto</th>
                <th>Tipo de producto</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->product_type }}</td>
                    <td>{{ $product->unit_price }}</td>
                    <td>{{ $product->product_status }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}">Editar</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
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