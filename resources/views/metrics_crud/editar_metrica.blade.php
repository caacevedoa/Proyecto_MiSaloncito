@extends('layouts.app')

@section('title', 'Editar Métrica')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Métrica</title>
</head>
<body>

    <h1>Editar Métrica</h1>

    <form action="{{ route('metrics.update', $metric->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Fecha -->
        <label for="record_date">Fecha del registro:</label>
        <input type="date" name="record_date" id="record_date"
               value="{{ $metric->record_date }}">
        <br>

        <!-- Total ventas del día (total_sales_date) -->
        <label for="total_sales_date">Total ventas del día:</label>
        <input type="number" step="0.01" name="total_sales_date" id="total_sales_date"
               value="{{ $metric->total_sales_date }}">
        <br>

        <!-- Total órdenes -->
        <label for="total_orders">Total de órdenes:</label>
        <input type="number" name="total_orders" id="total_orders"
               value="{{ $metric->total_orders }}">
        <br>

        <!-- Producto más vendido -->
        <label for="best_selling_product_id">Producto más vendido:</label>
        <input type="text" name="best_selling_product_id" id="best_selling_product_id"
               value="{{ $metric->best_selling_product_id }}">
        <br>

        <!-- Usuario más activo -->
        <label for="most_active_user_id">Usuario más activo:</label>
        <input type="text" name="most_active_user_id" id="most_active_user_id"
               value="{{ $metric->most_active_user_id }}">
        <br>

        <!-- Pago -->
        <label for="pay_id">Pago asociado:</label>
        <select name="pay_id" id="pay_id">
            @foreach ($payments as $payment)
                <option value="{{ $payment->id }}"
                    @if ($metric->pay_id == $payment->id) selected @endif>
                    Pago #{{ $payment->id }}
                </option>
            @endforeach
        </select>
        <br>

        <!-- Usuario -->
        <label for="user_id">Usuario asociado:</label>
        <select name="user_id" id="user_id">
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                    @if ($metric->user_id == $user->id) selected @endif>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        <br>

        <!-- Orden -->
        <label for="order_id">Orden asociada:</label>
        <select name="order_id" id="order_id">
            @foreach ($orders as $order)
                <option value="{{ $order->id }}"
                    @if ($metric->order_id == $order->id) selected @endif>
                    Orden #{{ $order->id }}
                </option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Guardar Cambios</button>
    </form>

    <br>
    <a href="{{ route('metrics.index') }}">Volver a la lista</a>

</body>
</html>
