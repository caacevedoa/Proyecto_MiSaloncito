@extends('layouts.app')

@section('title', 'Órdenes - MiSaloncito')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes</title>
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
            --color-warning: #ffc107;
            --color-info: #17a2b8;
            --color-purple: #6f42c1; /* NUEVO: Color para botón Detalles */
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

        /* Estilos del Formulario General */
        form.main-form {
            max-width: 600px; 
            padding: 30px; 
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin: 0 auto 30px auto; 
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
        }

        /* Botón Principal (Crear) */
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
            margin-top: 20px;
            width: 100%;
        }

        button.btn-primary:hover {
            background-color: var(--hover-darker-blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px var(--shadow-dark);
        }

        /* Mensajes de Alerta */
        .alert-success {
            color: var(--color-success);
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .alert-danger {
            color: var(--color-danger); 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            margin-bottom: 15px; 
            background-color: #f8d7da; 
            border-radius: 5px;
            font-weight: bold;
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DE LA TABLA */
        /* ---------------------------------------------------------------------- */

        .list-header { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            margin-top: 30px; 
            border-top: 3px solid var(--accent-grey);
            padding-top: 20px;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; vertical-align: middle; }
        th { 
            background-color: var(--primary-dark);
            color: var(--bg-light);
            font-weight: 700;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tbody tr:hover {
            background-color: rgba(173, 181, 189, 0.2); 
        }

        /* Estilos específicos de Ordenes (Cancel Reason) */
        .cancel-reason { 
            background-color: #fcecec; 
            border-left: 3px solid #dc3545; 
            padding: 5px; 
            margin-top: 5px; 
            font-size: 0.85em; 
            color: var(--primary-dark);
        }

        /* ---------------------------------------------------------------------- */
        /* ACCIONES (BOTONES) */
        /* ---------------------------------------------------------------------- */
        .action-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .action-link {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.85em;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            border: none;
            cursor: pointer;
        }

        /* Colores de botones */
        .action-link.details { background-color: var(--color-purple); color: white; } /* <--- ESTILO NUEVO */
        .action-link.edit { background-color: var(--color-warning); color: var(--primary-dark); }
        .action-link.pay { background-color: var(--color-success); color: white; }
        .action-link.invoice { background-color: var(--accent-grey); color: var(--primary-dark); }
        .action-link.pdf { background-color: var(--primary-dark); color: var(--bg-light); }
        .action-link.recalc { background-color: var(--color-info); color: white; }
        
        .action-link.delete {
            background-color: transparent;
            border: 1px solid var(--color-danger);
            padding: 4px 8px;
        }
        .action-link.delete button {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--color-danger);
            font-weight: 700;
            padding: 0;
            text-decoration: underline;
        }
        
        /* Botón Recalcular (dentro de form) */
        .recalc-btn {
            background: none;
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            padding: 4px 8px;
        }

    </style>
</head>
<body>

    <h1>Crear Orden</h1>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('orders.store') }}" method="post" class="main-form">
        @csrf

        <div class="form-group">
            <label for="user_id">Seleccione el usuario (mesero):</label>
            <select name="user_id" id="user_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="table_id">Seleccione la mesa:</label>
            <select name="table_id" id="table_id">
                @foreach ($tables as $table)
                    <option value="{{ $table->id }}">Mesa #{{ $table->table_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Estado de la orden:</label>
            <select name="status" id="status">
                <option value="pendiente">Pendiente</option>
                <option value="entregado">Entregada</option>
                <option value="cancelado">Cancelada</option>
                <option value="cerrado">Cerrada</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Crear Orden</button>
    </form>
    
    
    <div class="list-header">
        <h1>Listado de Órdenes</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha y Hora</th>
                <th>Usuario</th>
                <th>Mesa</th>
                <th>Estado</th>
                <th>Motivo Cancelación</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_datetime }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>Mesa #{{ $order->table->table_number }}</td>
                    
                    <td>
                        {{ $order->status }}
                    </td>
                    
                    <td>
                        @if ($order->status === 'cancelado' && $order->cancellation_reason)
                            <div class="cancel-reason">
                                <strong>Motivo:</strong> {{ $order->cancellation_reason }}
                            </div>
                        @else
                            <span style="color: var(--accent-grey);">N/A</span>
                        @endif
                    </td>

                    <td style="font-weight: bold;">
                        ${{ number_format(
                            $order->details->sum(fn($d) => $d->quantity * $d->unit_price),
                            0, ',', '.'
                        ) }}
                    </td>

                    <td>
                        <div class="action-container">
                            
                            {{-- BOTÓN DETALLES (NUEVO) --}}
                            {{-- Apunta al index del recurso 'ordersdetail' --}}
                            {{-- Se envía el ID de la orden como parámetro por si deseas filtrar en el controlador --}}
                            <a href="{{ route('ordersdetail.index', ['order_id' => $order->id]) }}" class="action-link details">
                                Detalles
                            </a>

                            <a href="{{ route('orders.edit', $order->id) }}" class="action-link edit">Editar</a>
                            
                            <a href="{{ route('payments_order.pay', $order->id) }}" class="action-link pay">Pagar</a>
                            
                            <a href="{{ route('payments.invoice', $order->id) }}" class="action-link invoice">Ver Factura</a>
                            
                            <a href="{{ route('factura.pdf', $order->id) }}" class="action-link pdf">PDF</a>

                            <form action="{{ route('orders.recalculate', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-link recalc recalc-btn">
                                    Actualizar
                                </button>
                            </form>

                        
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>

@endsection