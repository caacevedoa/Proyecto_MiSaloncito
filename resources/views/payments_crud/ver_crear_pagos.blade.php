<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos</title>
</head>
<body>

    <h1>Crear Pago</h1>

    <form action="{{route('payments.store') }}" method="post">
        @csrf

        <label for="order_id">Seleccione la orden:</label>
        <select name="order_id" id="order_id">
            @foreach ($orders as $order)
                <option value="{{ $order->id }}">{{ $order->id }}</option>
            @endforeach
        </select>
        <br>

        <label for="payment_method">Seleccione el medio de pago:</label>
        <select name="payment_method" id="payment_method">
            <option value="efectivo">Efectivo</option>
            <option value="Bancolombia">Bancolombia</option>
            <option value="Nequi">Nequi</option>
            <option value="Daviplata">Daviplata</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="transferencia">Transferencia</option>
        </select><br>

        <label for="total_pay">Total pagado:</label>
        <input type="number" name="total_pay" id="total_pay" step="100" value="{{$total}}" readonly><br>

        <label for="payment_status">Seleccione el estado del pago:</label>
        <select name="payment_status" id="payment_status">
            <option value="completado">Completado</option>
            <option value="pendiente">Pendiente</option>
            <option value="cancelado">Cancelado</option>
        </select><br>

        <button type="submit">Crear Pago</button>

    </form>

    <h1>Listado de pagos</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Orden</th>
                <th>Fecha y hora</th>
                <th>Método de pago</th>
                <th>Total</th>
                <th>Estado</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->order->id }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->total_pay }}</td>
                    <td>{{ $payment->payment_status }}</td>
                    <td>
                        <a href="{{ route('payments.edit', $payment->id) }}">Editar</a>
                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
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