@extends('layouts.app')

@section('title', 'Editar Órden')
@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orden</title>
    <style>
        /* ---------------------------------------------------------------------- */
        /* ESTILOS (TEMA PRINCIPAL) */
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
            
        }

        /* Estilos del Formulario Centrado */
        form.main-form {
            max-width: 600px; 
            padding: 30px; 
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin: 0 auto; /* Centrar el formulario */
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

        /* Botón Principal */
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
            margin-top: 10px;
            width: 100%;
        }

        button.btn-primary:hover {
            background-color: var(--hover-darker-blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px var(--shadow-dark);
        }
    </style>
</head>
<body>

    <h1>Editar Orden</h1>

    <form action="{{ route('orders.update', $order->id) }}" method="post" class="main-form">
        @method('PUT')
        @csrf

        <div class="form-group">
            <label for="user_id">Seleccione el usuario (mesero):</label>
            <select name="user_id" id="user_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="table_id">Seleccione la mesa:</label>
            <select name="table_id" id="table_id">
                @foreach ($tables as $table)
                    <option value="{{ $table->id }}" {{ $order->table_id == $table->id ? 'selected' : '' }}>Mesa #{{ $table->table_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Estado de la orden:</label>
            <select name="status" id="status">
                <option value="pendiente" {{ $order->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="entregado" {{ $order->status == 'entregado' ? 'selected' : '' }}>Entregada</option>
                <option value="cancelado" {{ $order->status == 'cancelado' ? 'selected' : '' }}>Cancelada</option>
                <option value="cerrado" {{ $order->status == 'cerrado' ? 'selected' : '' }}>Cerrada</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Editar Orden</button>

    </form>

</body>
</html>

@endsection