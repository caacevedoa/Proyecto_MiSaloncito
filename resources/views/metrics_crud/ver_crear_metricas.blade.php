<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Métricas</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: #343a40; color: white; }
        td { background-color: #ffffff; }
        .highlight-green { color: #155724; background-color: #d4edda; font-weight: bold; }
        .highlight-blue { color: #004085; background-color: #cce5ff; font-weight: bold; }
        
        /* Estilos para el Collapsible */
        .collapsible-button {
            background-color: #616161;
            color: white;
            cursor: pointer;
            padding: 15px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 1.2em;
            transition: background-color 0.3s;
            border-radius: 5px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .collapsible-button:hover { background-color: #4e4e4e; }
        
        .collapsible-content {
            padding: 0 18px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            background-color: white;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .collapsible-content.active {
            padding: 18px;
            /* Se usa un valor alto en CSS, luego JS ajusta el valor real */
            max-height: 2000px; 
        }

        .flex-container {
            display: flex;
            gap: 40px;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .flex-item {
            flex: 1; 
            min-width: 300px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .flex-item h2 {
            padding: 10px;
            border-radius: 5px;
            margin-top: 0;
            text-align: center;
        }

        .month-selector {
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    @if (session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h1>MÉTRICAS</h1>
    <form action="{{ route('metrics.store') }}" method="post" style="margin-bottom: 30px;">
        @csrf
        <label>Fecha: <input type="date" name="record_date" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></label>
        <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Generar
        </button>
    </form>

    {{-- ======================= SECCIÓN 1: DIARIAS ======================= --}}
    <button class="collapsible-button" data-target="diarias-content">📅 Métricas Diarias (Histórico de Cierres)</button>
    <div id="diarias-content" class="collapsible-content active">
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
                            {{-- CORRECCIÓN DEL ERROR: Usamos route('metrics.update', $metric->id) --}}
                            <form action="{{ route('metrics.update', $metric->id) }}" method="POST" style="display: inline-block;">
                                @csrf @method("PUT")
                                <button type="submit" title="Recalcular Top Mesero y Producto con órdenes actuales" style="background-color: #ffc107; color: #333; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                                    Actualizar Stats
                                </button>
                            </form>
                            <form action="{{ route('metrics.destroy', $metric->id) }}" method="POST" style="display: inline-block;">
                                @csrf @method("DELETE")
                                <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ======================= SECCIÓN 2: SEMANALES ======================= --}}
    <button class="collapsible-button" data-target="semanales-content">📈 Métricas Semanales (Agregación Dinámica)</button>
    <div id="semanales-content" class="collapsible-content">
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
    </div>

    {{-- ======================= SECCIÓN 3: MENSUALES (RESUMEN) ======================= --}}
    <button class="collapsible-button" data-target="mensuales-resumen-content">🗓️ Métricas Mensuales (Agregación Dinámica)</button>
    <div id="mensuales-resumen-content" class="collapsible-content">
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
    </div>

    {{-- ======================= SECCIÓN 4: GLOBALES (HISTÓRICO TOTAL) ======================= --}}
    <button class="collapsible-button" data-target="globales-historico-content" style="background-color: #343a40;">🏆 Estadísticas Globales (Histórico Total)</button>
    <div id="globales-historico-content" class="collapsible-content">
        <div class="flex-container">
            {{-- TABLA 1: RENDIMIENTO POR PRODUCTO --}}
            <div class="flex-item">
                <h2 style="background-color: #17a2b8; color: white;">🍔 Ranking de Ventas por Producto (Total)</h2>
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
            <div class="flex-item">
                <h2 style="background-color: #e83e8c; color: white;">🤵 Ranking de Ventas por Mesero (Total)</h2>
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
                                <td>{{ optional($waiter->user)->name ?? 'Usuario Eliminado' }}</td>
                                <td style="text-align: center;">{{ $waiter->total_orders_count }}</td>
                                <td style="text-align: right;">${{ number_format($waiter->total_money_sold, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ======================= SECCIÓN 5: GLOBALES POR MES SELECCIONADO (DETALLE) ======================= --}}
    {{-- TÍTULO DE LA SECCIÓN (Botón desplegable) --}}
    <button class="collapsible-button" data-target="mensuales-detalle-content" style="background-color: #28a745;">✨ Estadísticas Detalladas del Mes Seleccionado ({{ $lastMonthName }})</button>
    <div id="mensuales-detalle-content" class="collapsible-content">
        
        {{-- FORMULARIO PARA SELECCIONAR EL MES --}}
        <div class="month-selector">
            <form action="{{ route('metrics.index') }}" method="GET" style="display: inline-block;">
                <label for="selected_month">Seleccionar Mes:</label>
                <select name="selected_month" id="selected_month" onchange="this.form.submit()" 
                        style="padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    
                    {{-- Opciones generadas por el controlador --}}
                    @foreach ($availableMonths as $month)
                        <option value="{{ $month->month_year }}" 
                            {{ $month->month_year == $selectedMonth ? 'selected' : '' }}>
                            {{ $month->month_name }} {{ $month->year }}
                        </option>
                    @endforeach

                </select>
            </form>
        </div>

        <div class="flex-container">
            {{-- TABLA 1: RENDIMIENTO POR PRODUCTO (MENSUAL) --}}
            <div class="flex-item">
                {{-- CAMBIO AQUÍ: Usamos $lastMonthName --}}
                <h2 style="background-color: #17a2b8; color: white;">🍔 Ranking de Ventas por Producto ({{ $lastMonthName }})</h2>
                <table border="1" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cant. Vendida</th>
                            <th>Total Dinero ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthlyProductStats as $prod)
                            <tr>
                                <td>{{ $prod->product_name }}</td>
                                <td style="text-align: center;">{{ $prod->total_qty }}</td>
                                <td style="text-align: right;">${{ number_format($prod->total_money, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TABLA 2: RENDIMIENTO POR MESERO (MENSUAL) --}}
            <div class="flex-item">
                {{-- CAMBIO AQUÍ: Usamos $lastMonthName --}}
                <h2 style="background-color: #e83e8c; color: white;">🤵 Ranking de Ventas por Mesero ({{ $lastMonthName }})</h2>
                <table border="1" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Mesero</th>
                            <th>Órdenes Completadas</th>
                            <th>Total Vendido ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthlyWaiterStats as $waiter)
                            <tr>
                                <td>{{ optional($waiter->user)->name ?? 'Usuario Eliminado' }}</td>
                                <td style="text-align: center;">{{ $waiter->total_orders_count }}</td>
                                <td style="text-align: right;">${{ number_format($waiter->total_money_sold, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var buttons = document.querySelectorAll('.collapsible-button');
            
            buttons.forEach(button => {
                const targetId = button.getAttribute('data-target');
                const content = document.getElementById(targetId);

                // Inicializar el estado si no está activo
                if (content && !content.classList.contains('active')) {
                    content.style.maxHeight = '0';
                    content.style.padding = '0 18px';
                }

                button.addEventListener('click', function() {
                    if (content) {
                        // Toggle the 'active' class
                        content.classList.toggle('active');
                        
                        if (content.classList.contains('active')) {
                            // Abrir: ajustar la altura
                            // Usamos setTimeout para asegurar que el scrollHeight se calcule después del renderizado del DOM
                            setTimeout(() => {
                                content.style.maxHeight = content.scrollHeight + "px";
                            }, 50);
                            content.style.padding = '18px';
                        } else {
                            // Cerrar
                            content.style.maxHeight = '0';
                            content.style.padding = '0 18px'; 
                        }
                    }
                });
            });

            // Ajuste inicial para la sección 'Diarias' que comienza activa
            const activeContent = document.getElementById('diarias-content');
            if (activeContent && activeContent.classList.contains('active')) {
                // Se espera un momento para que el DOM se calcule correctamente
                setTimeout(() => {
                    activeContent.style.maxHeight = activeContent.scrollHeight + "px";
                }, 50);
            }
        });
    </script>
    <br><br><br>

</body>
</html>