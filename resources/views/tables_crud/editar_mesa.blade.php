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
        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" value="{{ $table->name }}"><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ $table->email }}"><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password"><br>


        <button type="submit">Editar Usuario</button>

    </form>


   
</body>
</html>