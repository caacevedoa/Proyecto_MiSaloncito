<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="{{route('products.update', $product->id)}}" method="post">
        @method('PUT')
        @csrf
        <label for="product_name">Nombre del producto:</label>
        <input type="text" name="product_name" id="product_name" value="{{ $product->product_name }}"><br>

        <label for="product_type">Tipo de producto:</label>
        <input type="text" name="product_type" id="product_type" value="{{ $product->product_type }}"><br>

        <label for="unit_price">Precio Unitario:</label>
        <input type="number" name="unit_price" id="unit_price" value="{{ $product->unit_price }}"><br>

        <label for="product_status">Seleccione el estado del producto:</label>
        <select name="product_status" id="product_status">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select><br>

        <button type="submit">Editar Producto</button>


    </form>


   
</body>
</html>