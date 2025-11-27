<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Cocina Pendientes</title>
    <style>
        /* Estilos básicos para la vista de cocina */
        body { font-family: sans-serif; background-color: #f0f0f0; margin: 0; padding: 20px; }
        .order-card { 
            background-color: white; 
            border: 2px solid #333; /* Resalta cada orden */
            margin: 20px auto; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            max-width: 900px;
        }
        .order-card.reopened {
             border-color: #ffc107; /* Borde amarillo para alerta */
             box-shadow: 0 4px 12px rgba(255, 193, 7, 0.5);
        }
        .reopen-alert {
            background-color: #fff3cd; 
            color: #856404; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 15px; 
            font-weight: bold;
            border: 1px solid #ffeeba;
        }
        .order-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 3px solid #d9534f; /* Color de alerta/importante */
            padding-bottom: 10px; 
            margin-bottom: 15px;
        }
        .order-header h2 { margin: 0; color: #d9534f; font-size: 1.8em; }
        .order-info p { margin: 5px 0; font-size: 1.1em; }
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .detail-table th, .detail-table td { border: 1px solid #eee; padding: 12px; text-align: left; font-size: 1.2em; }
        .detail-table th { background-color: #f9f9f9; }
        .product-quantity { font-weight: bold; color: #337ab7; width: 10%; }
        .product-name { width: 45%; }
        .product-notes { width: 45%; color: #dc3545; font-style: italic; font-weight: bold; } /* Resaltar notas */
        .complete-form { text-align: center; margin-top: 20px; }
        .complete-form button {
            background-color: #5cb85c; /* Verde para completar */
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            font-weight: bold;
        }
        .complete-form button:hover { background-color: #4cae4c; }
    </style>
</head>
<body>
    <h1>📋 Órdenes Pendientes de Cocina</h1>

    @if (session('success'))
        <div style="background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif
    
    @forelse ($pendingOrders as $order)
        @php
            // Lógica para detectar si la orden fue reabierta recientemente (en los últimos 5 minutos)
            $is_recently_updated = false;
            if ($order->status === 'pendiente') {
                $updated_diff_minutes = \Carbon\Carbon::parse($order->updated_at)->diffInMinutes();
                if ($updated_diff_minutes <= 5) {
                    $is_recently_updated = true;
                }
            }
            $card_class = $is_recently_updated ? 'reopened' : '';
        @endphp

        <div class="order-card {{ $card_class }}">
            
            {{-- ALERTA SI ES RECIÉN REABIERTA --}}
            @if ($is_recently_updated)
                <div class="reopen-alert">
                    ⚠️ **¡ATENCIÓN!** Esta orden fue reabierta hace menos de 5 minutos. Posiblemente se agregaron nuevos productos.
                </div>
            @endif

            <div class="order-header">
                <h2>#{{ $order->id }} (Mesa: {{ $order->table->table_number ?? 'N/A' }})</h2>
                <div class="order-info">
                    @php
                        $datetime = \Carbon\Carbon::parse($order->order_datetime);
                    @endphp
                    <p>📅 Fecha: **{{ $datetime->format('d/m/Y') }}**</p>
                    <p>⏰ Hora: **{{ $datetime->format('H:i:s') }}**</p>
                </div>
            </div>

            <table class="detail-table">
                <thead>
                    <tr>
                        <th class="product-quantity">Cant.</th>
                        <th class="product-name">Producto</th>
                        <th class="product-notes">Notas / Especificaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->details as $detail)
                        <tr>
                            <td class="product-quantity">{{ $detail->quantity }}x</td>
                            <td class="product-name">{{ $detail->product->product_name }}</td>
                            {{-- MOSTRAR EL CAMPO 'comment' --}}
                            <td class="product-notes">
                                @if (!empty($detail->comment))
                                    {{ $detail->comment }}
                                @else
                                    — Sin notas —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('kitchen.complete', $order->id) }}" method="POST" class="complete-form">
                @csrf
                @method('PATCH')
                <button type="submit">✅ Marcar como Entregado / Listo</button>
            </form>
            
            
        </div>
    @empty
        <p style="text-align: center; font-size: 1.5em; color: #555;">🎉 No hay órdenes pendientes en este momento. ¡Excelente!</p>
    @endforelse

</body>
</html>