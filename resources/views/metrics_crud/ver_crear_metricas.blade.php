<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Métricas</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .highlight-green { color: #155724; background-color: #d4edda; font-weight: bold; }
        .highlight-blue { color: #004085; background-color: #cce5ff; font-weight: bold; }
        .section-title { margin-top: 40px; padding: 10px; color: white; border-radius: 5px; }
    </style>
</head>
<body>

    <h1>Generar Métrica Diaria (Cierre de Caja)</h1>
    <form action="{{ route('metrics.store') }}" method="post">
        @csrf
        <label>Fecha: <input type="date" name="record_date" required></label>
        <button type="submit">Generar</button>
    </form>

    {{-- ======================= DIARIAS ======================= --}}
    <h2 class="section-title" style="background-color: #616161ff;">Métricas Diarias</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Ventas</th>
                <th>Órdenes</th>
                <th>Mesero Top ($)</th>
                <th>Prod. Top (Cant.)</th>
                <th>Prod. Top ($)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($metrics as $metric)
                <tr>
                    <td>{{ $metric->record_date }}</td>
                    <td>${{ number_format($metric->total_sales_date, 0) }}</td>
                    <td>{{ $metric->total_orders }}</td>
                    
                    <td>
                        {{ $metric->stats['waiter_name'] }} <br>
                        <span style="font-size: 0.85em; color: green;">(${{ number_format($metric->stats['waiter_total'], 0) }})</span>
                    </td>
                    <td class="highlight-blue">
                        {{ $metric->stats['pro_qty_name'] }} <br>
                        <span style="font-size: 0.85em;">({{ $metric->stats['pro_qty_val'] }} unds)</span>
                    </td>
                    <td class="highlight-green">
                        {{ $metric->stats['pro_money_name'] }} <br>
                        <span style="font-size: 0.85em;">(${{ number_format($metric->stats['pro_money_val'], 0) }})</span>
                    </td>

                    <td>
                        <form action="{{ route('metrics.destroy', $metric->id) }}" method="POST">
                            @csrf @method("DELETE")
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ======================= SEMANALES ======================= --}}
    <h2 class="section-title" style="background-color: #616161ff;">Métricas Semanales</h2>
    <table>
        <thead>
            <tr>
                <th>Año-Semana</th>
                <th>Ventas Totales</th>
                <th>Órdenes</th>
                <th>Mesero Top ($)</th>
                <th>Prod. Top (Cant.)</th>
                <th>Prod. Top ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($weeklyMetrics as $metric)
                <tr>
                    <td>{{ $metric->year }} - Sem {{ $metric->period }}</td>
                    <td>${{ number_format($metric->total_sales, 0) }}</td>
                    <td>{{ $metric->total_orders }}</td>

                    <td>
                        {{ $metric->stats['waiter_name'] }} <br>
                        <small>(${{ number_format($metric->stats['waiter_total'], 0) }})</small>
                    </td>
                    <td>
                        {{ $metric->stats['pro_qty_name'] }} <br>
                        <small>({{ $metric->stats['pro_qty_val'] }} unds)</small>
                    </td>
                    <td>
                        {{ $metric->stats['pro_money_name'] }} <br>
                        <small>(${{ number_format($metric->stats['pro_money_val'], 0) }})</small>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ======================= MENSUALES ======================= --}}
    <h2 class="section-title" style="background-color: #616161ff;">Métricas Mensuales</h2>
    <table>
        <thead>
            <tr>
                <th>Mes</th>
                <th>Ventas Totales</th>
                <th>Órdenes</th>
                <th>Mesero Top ($)</th>
                <th>Prod. Top (Cant.)</th>
                <th>Prod. Top ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthlyMetrics as $metric)
                <tr>
                    <td>{{ $metric->period }} {{ $metric->year }}</td>
                    <td>${{ number_format($metric->total_sales, 0) }}</td>
                    <td>{{ $metric->total_orders }}</td>

                    <td>
                        {{ $metric->stats['waiter_name'] }} <br>
                        <small>(${{ number_format($metric->stats['waiter_total'], 0) }})</small>
                    </td>
                    <td>
                        {{ $metric->stats['pro_qty_name'] }} <br>
                        <small>({{ $metric->stats['pro_qty_val'] }} unds)</small>
                    </td>
                    <td>
                        {{ $metric->stats['pro_money_name'] }} <br>
                        <small>(${{ number_format($metric->stats['pro_money_val'], 0) }})</small>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

<hr style="margin: 50px 0; border: 2px dashed #ccc;">

    <h1 style="text-align: center; color: #333;">🏆 Estadísticas Globales (Histórico)</h1>

    <div style="display: flex; gap: 40px; justify-content: space-between; flex-wrap: wrap;">

        {{-- TABLA 1: RENDIMIENTO POR PRODUCTO --}}
        <div style="flex: 1; min-width: 300px;">
            <h2 style="background-color: #17a2b8; color: white; padding: 10px; border-radius: 5px;">
                🍔 Ventas por Producto
            </h2>
            <table border="1" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant. Vendida</th>
                        <th>Total Dinero ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productStats as $prod)
                        <tr>
                            <td>{{ $prod->product_name }}</td>
                            <td style="text-align: center;">{{ $prod->total_qty }}</td>
                            <td style="text-align: right;">${{ number_format($prod->total_money, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TABLA 2: RENDIMIENTO POR MESERO --}}
        <div style="flex: 1; min-width: 300px;">
            <h2 style="background-color: #e83e8c; color: white; padding: 10px; border-radius: 5px;">
                🤵 Ventas por Mesero
            </h2>
            <table border="1" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Mesero</th>
                        <th>Órdenes Completadas</th>
                        <th>Total Vendido ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($waiterStats as $waiter)
                        <tr>
                            {{-- Usamos optional() por si se borró el usuario --}}
                            <td>{{ optional($waiter->user)->name ?? 'Usuario Eliminado' }}</td>
                            <td style="text-align: center;">{{ $waiter->total_orders_count }}</td>
                            <td style="text-align: right;">${{ number_format($waiter->total_money_sold, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <br><br><br>

</body>
</html>

</body>
</html>