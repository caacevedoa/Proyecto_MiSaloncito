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

        <label for="order_datetime">Fecha y hora:</label>
        <input 
            type="datetime-local" 
            name="order_datetime" 
            id="order_datetime"
            value="{{ \Carbon\Carbon::parse($order->order_datetime)->format('Y-m-d\TH:i') }}"
        >
        <br>

        <label for="user_id">Seleccione el usuario (mesero):</label>
        <select name="user_id" id="user_id">
            @foreach ($users as $user)
                <option 
                    value="{{ $user->id }}"
                    @if ($user->id == $order->user_id) selected @endif
                >
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        <br>

        <label for="table_id">Seleccione la mesa:</label>
        <select name="table_id" id="table_id">
            @foreach ($tables as $table)
                <option 
                    value="{{ $table->id }}"
                    @if ($table->id == $order->table_id) selected @endif
                >
                    Mesa #{{ $table->table_number }}
                </option>
            @endforeach
        </select>
        <br>

        <label for="order_status">Estado de la orden:</label>
        <select name="order_status" id="order_status">
            <option value="pendiente"   @if($order->order_status == 'pendiente') selected @endif>Pendiente</option>
            <option value="entregado"   @if($order->order_status == 'entregado') selected @endif>Entregado</option>
            <option value="cancelado"   @if($order->order_status == 'cancelado') selected @endif>Cancelado</option>
        </select>
        <br>

        <button type="submit">Actualizar Orden</button>

    </form>

</body>
</html>
