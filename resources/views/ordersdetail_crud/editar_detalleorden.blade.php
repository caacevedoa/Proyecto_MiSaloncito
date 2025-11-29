@extends('layouts.app')

@section('title', 'Editar Detalle de Orden')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Detalle de Orden</title>
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

        form input[type="text"],
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

        /* Estilo para campos de solo lectura */
        form input[readonly] {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }

        /* Botón Actualizar */
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

    <h1>Editar Detalle de Orden #{{ $orderDetail->id }}</h1>

    <form action="{{ route('ordersdetail.update', $orderDetail->id) }}" method="post" class="main-form">
        @method('PUT')
        @csrf

        <div class="form-group">
            <label for="order_id">Orden Asignada:</label>
            <input type="text" name="order_id" value="Orden #{{ $orderDetail->order_id }}" readonly>
            <!-- Nota: Enviamos el valor real, pero mostramos algo legible. 
                 Si necesitas enviar el ID puro, asegura que el backend reciba lo correcto 
                 o usa un input hidden adicional si modificas el value visible. 
                 Aquí mantuve la lógica visual de "Solo Lectura". -->
        </div>

        <div class="form-group">
            <label for="product_id">Producto:</label>
            <select name="product_id" id="product_id">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" 
                        {{ $product->id == $orderDetail->product_id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Cantidad:</label>
            <input type="number" name="quantity" id="quantity" value="{{ $orderDetail->quantity }}" min="1">
        </div>

        <button type="submit" class="btn-primary">Actualizar Detalle</button>
    </form>

</body>
</html>

@endsection