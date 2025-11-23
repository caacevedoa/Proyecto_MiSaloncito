<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
</head>
<body>
    
    <h1>Editar Producto</h1>
    <form action="{{route('products.update', $product->id)}}" method="post">
        @method('PUT')
        @csrf
        <label for="product_name">Nombre del producto:</label>
        <input type="text" name="product_name" id="product_name" value="{{ $product->product_name }}"><br>

        <label for="product_type">Seleccione el tipo de producto:</label>
        <select name="product_type" id="product_type">
            <option value="Panaderia">Panadería</option>
            <option value="Desayunos">Desayunos</option>
            <option value="Almuerzos">Almuerzos</option>
            <option value="Bebidas">Bebidas</option>
            <option value="Especiales">Especiales</option>
            <option value="Otros">Otros</option>
        </select><br>

        <label for="unit_price">Precio Unitario:</label>
        <input type="number" name="unit_price" id="unit_price" value="{{ $product->unit_price }}"><br>

        <label for="product_status">Seleccione el estado del producto:</label>
        <select name="product_status" id="product_status">
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select><br>

        <button type="submit">Editar Producto</button>


    </form>


   
</body>
</html>