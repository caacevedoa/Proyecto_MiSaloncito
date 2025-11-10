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
        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name"><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email"><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password"><br>


        <label for="role">Seleccione el rol:</label>
        <select name="role" id="role">
            <option value="administrador">Administrador</option>
            <option value="mesero">Mesero</option>
            <option value="gerencia">Gerencia</option>
        </select><br>

        <button type="submit">Crear Usuario</button>

    </form>

    <h1>Listado de usuarios</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tables as $table)
                <tr>
                    <td>{{ $table->id }}</td>
                    <td>{{ $table->name }}</td>
                    <td>{{ $table->email }}</td>
                    <td>{{ $table->role }}</td>
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