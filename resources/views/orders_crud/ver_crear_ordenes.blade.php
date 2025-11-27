<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .cancel-reason { 
            background-color: #fcecec; 
            border-left: 3px solid #dc3545; 
            padding: 5px; 
            margin-top: 5px; 
            font-size: 0.85em; 
        }
    </style>
</head>
<body>

    <h1>Crear Orden</h1>
    <form action="{{ route('orders.store') }}" method="post">
        @csrf

        <label for="user_id">Seleccione el usuario (mesero):</label>
        <select name="user_id" id="user_id">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>

        <label for="table_id">Seleccione la mesa:</label>
        <select name="table_id" id="table_id">
            @foreach ($tables as $table)
                <option value="{{ $table->id }}">Mesa #{{ $table->table_number }}</option>
            @endforeach
        </select>
        <br>

        <label for="status">Estado de la orden:</label>
        <select name="status" id="status">
            <option value="pendiente">Pendiente</option>
            <option value="entregado">Entregada</option>
            <option value="cancelado">Cancelada</option>
            <option value="cerrado">Cerrada</option>
        </select>
        <br>

        <button type="submit">Crear Orden</button>
    </form>
    
    @if(session('success'))
        <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
    @endif
    
    @if(session('error'))
        <p style="color: red; font-weight: bold;">{{ session('error') }}</p>
    @endif
    
    <h1>Listado de Órdenes</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha y Hora</th>
                <th>Usuario</th>
                <th>Mesa</th>
                <th>Estado</th>
                <th>Motivo Cancelación</th>  {{-- NUEVA COLUMNA --}}
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
                    
                    {{-- Columna de Estado con Resaltado si es Cancelado --}}
                    <td>
                        {{ $order->status }}
                        
                    </td>
                    
                    {{-- NUEVA CELDA: Motivo de Cancelación --}}
                    <td>
                        @if ($order->status === 'cancelado' && $order->cancellation_reason)
                            <div class="cancel-reason">
                                Motivo:{{ $order->cancellation_reason }}
                            </div>
                        @else
                            N/A
                        @endif
                    </td>

                    <td>
                        ${{ number_format(
                            $order->details->sum(fn($d) => $d->quantity * $d->unit_price),
                            0, ',', '.'
                        ) }}
                    </td>

                    <td>
                        <a href="{{ route('orders.edit', $order->id) }}">Editar</a> |
                        <a href="{{ route('payments_order.pay', $order->id) }}">Pagar</a> |
                        <a href="{{ route('payments.invoice', $order->id) }}">Ver Factura</a> |
                        <a href="{{ route('factura.pdf', $order->id) }}">Descargar Factura</a>

                        <form action="{{ route('orders.destroy', $order->id) }}" 
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>

                        <hr style="margin: 5px 0;">

                        <form action="{{ route('orders.recalculate', $order->id) }}" 
                              method="POST" style="display:inline;">
                            @csrf
                            <button type="submit"
                                style="background-color: #007bff; color: white;
                                       border: none; padding: 5px 8px; cursor: pointer;">
                                Actualizar Total 
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>