<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago</title>
</head>
<body>

    <h1>Editar Pago</h1>
    <form action="{{route('payments.update', $payment->id)}}" method="post">
        @method('PUT')
        @csrf

        <label for="order_id">Seleccione la orden:</label>
        <select name="order_id" id="order_id">
            @foreach ($orders as $order)
                <option value="{{ $order->id }}" {{ $payment->order_id == $order->id ? 'selected' : '' }}>{{ $order->id }}</option>
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
        <input type="number" name="total_pay" id="total_pay" value="{{ $payment->total_pay }}"><br>

        <label for="payment_status">Seleccione el estado del pago:</label>
        <select name="payment_status" id="payment_status">
            <option value="completado">Completado</option>
            <option value="pendiente">Pendiente</option>
            <option value="cancelado">Cancelado</option>
        </select><br>

        <button type="submit">Editar Pago</button>


    </form>


   
</body>
</html>