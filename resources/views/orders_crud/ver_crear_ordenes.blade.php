<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes</title>
</head>
<body>
    <h1>Crear Orden</h1>

    <form action="{{ route('orders.store') }}" method="post">
        @csrf

        <label for="user_id">Seleccione el usuario (mesero):</label>
        <select name="user_id" id="user_id">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>

        <label for="table_id">Seleccione la mesa:</label>
        <select name="table_id" id="table_id">
            @foreach ($tables as $table)
                <option value="{{ $table->id }}">Mesa #{{ $table->table_number }}</option>
            @endforeach
        </select>
        <br>

        <label for="status">Estado de la orden:</label>
        <select name="status" id="status">
            <option value="pendiente">Pendiente</option>
            <option value="entregado">Entregado</option>
            <option value="cancelado">Cancelado</option>
        </select>
        <br>

        <button type="submit">Crear Orden</button>

    </form>

    <h1>Listado de Órdenes</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha y Hora</th>
                <th>Usuario</th>
                <th>Mesa</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_datetime }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>Mesa #{{ $order->table->table_number }}</td>
                    <td>{{ $order->status }}</td>

                    <td>
                        <a href="{{ route('orders.edit', $order->id) }}">Editar</a>

                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
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
