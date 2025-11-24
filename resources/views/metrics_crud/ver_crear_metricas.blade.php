<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Métrica</title>
</head>
<body>

    <h1>Crear Métrica</h1>

    <form action="{{ route('metrics.store') }}" method="post">
        @csrf

        <label for="record_date">Fecha del registro:</label>
        <input type="date" name="record_date" id="record_date">
        <br>

        <label for="total_sales_date">Total ventas del día:</label>
        <input type="number" step="0.01" name="total_sales_date" id="total_sales_date">
        <br>

        <label for="total_orders">Total de órdenes:</label>
        <input type="number" name="total_orders" id="total_orders">
        <br>

        <label for="best_selling_product_id">Producto más vendido:</label>
        <input type="text" name="best_selling_product_id" id="best_selling_product_id">
        <br>

        <label for="most_active_user_id">Usuario más activo:</label>
        <input type="text" name="most_active_user_id" id="most_active_user_id">
        <br>

        <label for="pay_id">Pago asociado:</label>
        <select name="pay_id" id="pay_id">
            @foreach ($payments as $payment)
                <option value="{{ $payment->id }}">Pago #{{ $payment->id }}</option>
            @endforeach
        </select>
        <br>

        <label for="user_id">Usuario asociado:</label>
        <select name="user_id" id="user_id">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>

        <label for="order_id">Orden asociada:</label>
        <select name="order_id" id="order_id">
            @foreach ($orders as $order)
                <option value="{{ $order->id }}">Orden #{{ $order->id }}</option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Crear Métrica</button>

    </form>

    <h1>Listado de Métricas</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Ventas Día</th>
                <th>Total Órdenes</th>
                <th>Producto Más Vendido</th>
                <th>Usuario Más Activo</th>
                <th>Pago</th>
                <th>Usuario</th>
                <th>Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($metrics as $metric)
                <tr>
                    <td>{{ $metric->id }}</td>
                    <td>{{ $metric->record_date }}</td>
                    <td>{{ $metric->total_sales_date }}</td>
                    <td>{{ $metric->total_orders }}</td>
                    <td>{{ $metric->best_selling_product_id }}</td>
                    <td>{{ $metric->most_active_user_id }}</td>
                    <td>{{ $metric->pay_id }}</td>
                    <td>{{ $metric->user_id }}</td>
                    <td>{{ $metric->order_id }}</td>

                    <td>
                        <a href="{{ route('metrics.edit', $metric->id) }}">Editar</a>

                        <form action="{{ route('metrics.destroy', $metric->id) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            @method("DELETE")
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
