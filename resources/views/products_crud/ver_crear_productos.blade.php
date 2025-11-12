<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="{{route('tables.store')}}" method="post">
        @csrf
        <label for="table_number">Número de mesa:</label>
        <input type="text" name="table_number" id="table_number"><br>

        <label for="table_status">Seleccione el estado de la mesa:</label>
        <select name="table_status" id="table_status">
            <option value="libre">Libre</option>
            <option value="ocupada">Ocupada</option>
            <option value="reservada">Reservada</option>
        </select><br>

        <button type="submit">Crear Mesa</button>

    </form>

    <h1>Listado de mesas</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Mesa</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tables as $table)
                <tr>
                    <td>{{ $table->id }}</td>
                    <td>{{ $table->table_number }}</td>
                    <td>{{ $table->table_status }}</td>
                    <td>
                        <a href="{{ route('tables.edit', $table->id) }}">Editar</a>
                        <form action="{{ route('tables.destroy', $table->id) }}" method="POST" style="display:inline;">
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