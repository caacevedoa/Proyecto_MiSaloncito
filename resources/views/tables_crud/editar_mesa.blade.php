@extends('layouts.app')

@section('title', 'Editar Mesa')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
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

        /* Botón Volver */
        .btn-secondary-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #6c757d;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-secondary-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Editar Mesa</h1>

    <form action="{{ route('tables.update', $table->id) }}" method="post" class="main-form">
        @method('PUT')
        @csrf

        <div class="form-group">
            <label for="table_number">Número de mesa:</label>
            <input 
                type="text" 
                name="table_number" 
                id="table_number" 
                value="{{ $table->table_number }}"
            >
        </div>

        <div class="form-group">
            <label for="table_status">Estado de la mesa:</label>
            <select name="table_status" id="table_status">
                <option value="libre" {{ $table->table_status == 'libre' ? 'selected' : '' }}>Libre</option>
                <option value="ocupada" {{ $table->table_status == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                <option value="reservada" {{ $table->table_status == 'reservada' ? 'selected' : '' }}>Reservada</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Actualizar Mesa</button>

        <a href="{{ route('tables.index') }}" class="btn-secondary-link">← Volver al listado</a>

    </form>

</body>
</html>

@endsection