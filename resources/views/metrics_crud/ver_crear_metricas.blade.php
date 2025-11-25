<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Métricas</title>
</head>
<body>

    @if(session('success'))
        <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px;">
            <ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <h1>Generar Métrica Diaria</h1>

    <form action="{{ route('metrics.store') }}" method="post">
        @csrf
        <label for="record_date">Fecha del registro:</label>
        <input type="date" name="record_date" id="record_date" required>
        <br><br>
        <button type="submit">Generar Métrica</button>
    </form>

    <hr>
    
    <h1 style="color: #007bff;">📊 Listado de Métricas Diarias</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Ventas Día</th>
                <th>Total Órdenes</th>
                <th>Producto Más Vendido</th>
                <th>Usuario Más Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($metrics as $metric)
                <tr>
                    <td>{{ $metric->id }}</td>
                    <td>{{ $metric->record_date }}</td>
                    <td>${{ number_format($metric->total_sales_date, 2) }}</td>
                    <td>{{ $metric->total_orders }}</td>
                    <td>{{ $metric->best_selling_product_id }}</td>
                    <td>{{ $metric->most_active_user_id }}</td>

                    <td>
                        <form action="{{ route('metrics.update_data', $metric->id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" 
                                title="Recalcula la métrica usando los datos de órdenes más recientes para esta fecha."
                                style="background-color: #ffc107; color: black; border: none; padding: 5px; cursor: pointer;">
                                Actualizar
                            </button>
                        </form>
                        |
                        <form action="{{ route('metrics.destroy', $metric->id) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            @method("DELETE")
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr style="margin: 40px 0;">

    <h1 style="color: #28a745;">📅 Listado de Métricas Semanales</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Año</th>
                <th>Semana #</th>
                <th>Ventas Totales</th>
                <th>Órdenes Totales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($weeklyMetrics as $metric)
                <tr>
                    <td>{{ $metric->year }}</td>
                    <td>{{ $metric->period }}</td>
                    <td>${{ number_format($metric->total_weekly_sales, 2) }}</td>
                    <td>{{ $metric->total_weekly_orders }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <hr style="margin: 40px 0;">

    <h1 style="color: #6f42c1;">🗓️ Listado de Métricas Mensuales</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Año</th>
                <th>Mes</th>
                <th>Ventas Totales</th>
                <th>Órdenes Totales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthlyMetrics as $metric)
                <tr>
                    <td>{{ $metric->year }}</td>
                    <td>{{ $metric->period }}</td>
                    <td>${{ number_format($metric->total_monthly_sales, 2) }}</td>
                    <td>{{ $metric->total_monthly_orders }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>