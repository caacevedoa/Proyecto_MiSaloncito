@extends('layouts.app')

@section('title', 'Editar Pago')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago</title>
    <style>
        /* ---------------------------------------------------------------------- */
        /* ESTILOS GENERALES (TEMA AZUL OSCURO) */
        /* ---------------------------------------------------------------------- */
        :root {
            --primary-dark: #002244;
            --accent-grey: #ADB5BD;
            --bg-light: #FFFFFF;
            --hover-darker-blue: #001a33;
            --shadow-dark: rgba(0, 34, 68, 0.6);
            --color-success: #28a745;
            --color-danger: #dc3545;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--primary-dark);
            margin: 20px;
        }

        h1 {
            color: var(--primary-dark);
            border-bottom: 3px solid var(--accent-grey);
            padding-bottom: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DEL FORMULARIO */
        /* ---------------------------------------------------------------------- */
        form.main-form {
            max-width: 600px; 
            padding: 30px; 
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin: 0 auto; /* Centrar formulario */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--accent-grey);
            border-radius: 6px;
            box-sizing: border-box;
            color: var(--primary-dark);
            background-color: var(--bg-light);
            font-size: 1rem;
        }

        /* Botón Editar */
        button.btn-primary {
            background-color: var(--primary-dark);
            color: var(--bg-light); 
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px var(--shadow-dark); 
            border: none;
            padding: 12px 25px;
            text-transform: uppercase;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        button.btn-primary:hover {
            background-color: var(--hover-darker-blue);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <h1>Editar Pago #{{ $payment->id }}</h1>

    <form action="{{route('payments.update', $payment->id)}}" method="post" class="main-form">
        @method('PUT')
        @csrf

        <div class="form-group">
            <label for="order_id">Seleccione la orden:</label>
            <select name="order_id" id="order_id">
                @foreach ($orders as $order)
                    <option value="{{ $order->id }}" {{ $payment->order_id == $order->id ? 'selected' : '' }}>
                        Orden #{{ $order->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="payment_method">Seleccione el medio de pago:</label>
            <select name="payment_method" id="payment_method">
                <option value="efectivo" {{ $payment->payment_method == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="Bancolombia" {{ $payment->payment_method == 'Bancolombia' ? 'selected' : '' }}>Bancolombia</option>
                <option value="Nequi" {{ $payment->payment_method == 'Nequi' ? 'selected' : '' }}>Nequi</option>
                <option value="Daviplata" {{ $payment->payment_method == 'Daviplata' ? 'selected' : '' }}>Daviplata</option>
                <option value="tarjeta" {{ $payment->payment_method == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ $payment->payment_method == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
            </select>
        </div>

        <div class="form-group">
            <label for="total_pay">Total pagado:</label>
            <input type="number" name="total_pay" id="total_pay" value="{{ $payment->total_pay }}">
        </div>

        <div class="form-group">
            <label for="payment_status">Seleccione el estado del pago:</label>
            <select name="payment_status" id="payment_status">
                <option value="completado" {{ $payment->payment_status == 'completado' ? 'selected' : '' }}>Completado</option>
                <option value="pendiente" {{ $payment->payment_status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="cancelado" {{ $payment->payment_status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Editar Pago</button>

    </form>

</body>
</html>

@endsection