<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - MiSaloncito</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: "Segoe UI", sans-serif;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 25px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .card {
            background-color: white;
            width: 260px;
            height: 150px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            text-decoration: none;
            transition: 0.2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.04);
            box-shadow: 0 6px 16px rgba(0,0,0,0.25);
        }

        .footer {
            margin-top: 60px;
            padding: 20px;
            background-color: #2c3e50;
            color: white;
            text-align: center;
            font-size: 14px;
        }

    </style>
</head>
<body>

    <div class="header">
        Panel Administrativo - MiSaloncito
    </div>

    <div class="container">
        <a class="card" href="{{ route('tables.index') }}">Mesas</a>
        <a class="card" href="{{ route('products.index') }}">Productos</a>
        <a class="card" href="{{ route('orders.index') }}">Órdenes</a>
        <a class="card" href="{{ route('ordersdetail.index') }}">Detalles Orden</a>
        <a class="card" href="{{ route('payments.index') }}">Pagos</a>
        <a class="card" href="{{ route('metrics.index') }}">Métricas</a>
        <a class="card" href="{{ route('users.index') }}">Usuarios</a>
    </div>

    <div class="footer">
        Sistema de Gestión del MiSaloncito © {{ date("Y") }}
    </div>

</body>
</html>
