<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="{{route('users.update', $user->id)}}" method="post">
        @method('PUT')
        @csrf
        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" value="{{ $user->name }}"><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ $user->email }}"><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password"><br>


        <label for="role">Seleccione el rol:</label>
        <select name="role" id="role">
            <option value="administrador" {{ $user->role == 'administrador' ? 'selected' : '' }}>Administrador</option>
            <option value="mesero" {{ $user->role == 'mesero' ? 'selected' : '' }}>Mesero</option>
            <option value="gerencia" {{ $user->role == 'gerencia' ? 'selected' : '' }}>Gerencia</option>
        </select><br>

        <button type="submit">Editar Usuario</button>

    </form>


   
</body>
</html>