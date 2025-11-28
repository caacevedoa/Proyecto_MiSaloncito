@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Cocina Pendientes</title>

    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f6f7;
            margin: 0;
            padding: 25px;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #bdc3c7;
            padding-bottom: 5px;
            margin-bottom: 25px;
        }

        /* Card del pedido */
        .order-card {
            background-color: #ffffff;
            border: 2px solid #2c3e50;
            margin: 25px auto;
            padding: 25px;
            border-radius: 12px;
            max-width: 900px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        /* Destacar pedidos reabiertos o modificados */
        .order-card.reopened {
            border-color: #f1c40f;
            box-shadow: 0 4px 18px rgba(241, 196, 15, 0.4);
        }

        .reopen-alert {
            background-color: #fff6d1;
            color: #8a6d3b;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ffe7a3;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* Header */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #c0392b;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .order-header h2 {
            margin: 0;
            color: #c0392b;
            font-size: 1.8em;
        }
        
        /* Estilo opcional para el nombre del mesero para diferenciarlo un poco */
        .waiter-name {
            color: #2c3e50;
            font-size: 0.8em;
            font-weight: normal;
        }

        .order-info p {
            margin: 4px 0;
            font-size: 1.1em;
        }

        /* Tabla */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #e5e7e9;
            padding: 12px;
            text-align: left;
            font-size: 1.15em;
        }

        .detail-table th {
            background-color: #f8f9f9;
            font-weight: bold;
            color: #2c3e50;
        }

        .product-quantity { font-weight: bold; width: 10%; color: #2980b9; }
        .product-name { width: 45%; }
        .product-notes {
            width: 45%;
            color: #c0392b;
            font-style: italic;
            font-weight: bold;
        }

        /* Botón completar */
        .complete-form {
            text-align: center;
            margin-top: 25px;
        }

        .complete-form button {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.15em;
            font-weight: bold;
            transition: 0.2s;
        }

        .complete-form button:hover {
            background-color: #1b2631;
        }

        /* Mensaje vacío */
        .empty-msg {
            text-align: center;
            font-size: 1.5em;
            color: #555;
            margin-top: 40px;
        }

        /* Alert success */
        .success-box {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            max-width: 900px;
        }
    </style>
</head>


<body>

    <h1>Órdenes Pendientes de Cocina</h1>

    {{-- MENSAJE DE CONFIRMACIÓN --}}
    @if (session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif


    {{-- LISTA DE ÓRDENES --}}
    @forelse ($pendingOrders as $order)

        @php
            $created_at = \Carbon\Carbon::parse($order->created_at);
            $updated_at = \Carbon\Carbon::parse($order->updated_at);

            $is_modified = $updated_at->gt($created_at);

            $is_recently_updated = false;

            if ($order->status === 'pendiente' && $is_modified) {
                $updated_diff_minutes = $updated_at->diffInMinutes(now());
                if ($updated_diff_minutes <= 5) {
                    $is_recently_updated = true;
                }
            }

            $card_class = $is_recently_updated ? 'reopened' : '';
        @endphp


        <div class="order-card {{ $card_class }}">

            {{-- ALERTA DE MODIFICACIÓN RECIENTE --}}
            @if ($is_recently_updated)
                <div class="reopen-alert">
                    ¡Atención! Esta orden fue modificada hace {{ $updated_diff_minutes }} minutos. Revisa los cambios.
                </div>
            @endif

            {{-- ENCABEZADO CON NOMBRE DE USUARIO --}}
            <div class="order-header">
                <h2>
                    Orden #{{ $order->id }} — Mesa {{ $order->table->table_number ?? 'N/A' }} 
                    <span class="waiter-name">
                        (Atiende: {{ $order->user->name ?? 'Sin asignar' }})
                    </span>
                </h2>
                
                <div class="order-info">
                    @php $datetime = \Carbon\Carbon::parse($order->order_datetime); @endphp
                    <p>Fecha: {{ $datetime->format('d/m/Y') }}</p>
                    <p>Hora: {{ $datetime->format('H:i:s') }}</p>
                </div>
            </div>

            {{-- DETALLES --}}
            <table class="detail-table">
                <thead>
                    <tr>
                        <th class="product-quantity">Cant.</th>
                        <th class="product-name">Producto</th>
                        <th class="product-notes">Notas</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($order->details as $detail)
                        <tr>
                            <td class="product-quantity">{{ $detail->quantity }}x</td>
                            <td class="product-name">{{ $detail->product->product_name }}</td>
                            <td class="product-notes">
                                @if ($detail->comment)
                                    {{ $detail->comment }}
                                @else
                                    — Sin notas —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- BOTÓN COMPLETAR --}}
            <form action="{{ route('kitchen.complete', $order->id) }}" method="POST" class="complete-form">
                @csrf
                @method('PATCH')
                <button type="submit">Marcar como Entregado</button>
            </form>

        </div>

    @empty

        <p class="empty-msg">No hay órdenes pendientes en este momento.</p>

    @endforelse

</body>
</html>

@endsection