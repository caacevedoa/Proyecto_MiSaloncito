<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="{{route('tables.update', $table->id)}}" method="post">
        @method('PUT')
        @csrf
        <label for="table_number">Número de mesa:</label>
        <input type="text" name="table_number" id="table_number" value="{{ $table->table_number }}"><br>


        <label for="table_status">Seleccione el estado de la mesa:</label>
        <select name="table_status" id="table_status">
            <option value="libre">Libre</option>
            <option value="ocupada">Ocupada</option>
            <option value="reservada">Reservada</option>
        </select><br>

        <button type="submit">Editar Mesa</button>


    </form>


   
</body>
</html>