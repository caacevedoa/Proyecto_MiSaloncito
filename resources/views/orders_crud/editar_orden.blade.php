<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orden</title>
</head>
<body>

    <h1>Editar Orden</h1>
    <form action="{{ route('orders.update', $order->id) }}" method="post">
        @method('PUT')
        @csrf

        <label for="user_id">Seleccione el usuario (mesero):</label>
        <select name="user_id" id="user_id">
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
        <br>

        <label for="table_id">Seleccione la mesa:</label>
        <select name="table_id" id="table_id">
            @foreach ($tables as $table)
                <option value="{{ $table->id }}" {{ $order->table_id == $table->id ? 'selected' : '' }}>Mesa #{{ $table->table_number }}</option>
            @endforeach
        </select>
        <br>

        <label for="status">Estado de la orden:</label>
        <select name="status" id="status">
            <option value="pendiente" {{ $order->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="entregado" {{ $order->status == 'entregado' ? 'selected' : '' }}>Entregado</option>
            <option value="cancelado" {{ $order->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
        <br>

        <button type="submit">Editar Orden</button>

    </form>

</body>
</html>