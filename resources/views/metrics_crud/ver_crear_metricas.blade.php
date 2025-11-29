@extends('layouts.app')

@section('title', 'Métricas y Reportes')

@section('content')

<style>
    /* ---------------------------------------------------------------------- */
    /* ESTILOS DE LA PALETA MONOCROMÁTICA (SIN CAMBIOS) */
    /* ---------------------------------------------------------------------- */
    :root {
        --primary-dark: #002244;
        --accent-grey: #ADB5BD;
        --bg-light: #FFFFFF;
        --hover-darker-blue: #001a33;
        --menu-card-bg-hover: rgba(173, 181, 189, 0.1);
        --shadow-dark: rgba(0, 34, 68, 0.6);
        /* Mantener colores específicos para alertas y feedback visual */
        --color-success: #28a745;
        --color-danger: #dc3545;
        --color-warning: #ffc107;
        --color-info: #17a2b8;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--primary-dark);
    }
    
    /* Título Administrativo */
    .admin-title {
        color: var(--primary-dark);
        border-bottom: 3px solid var(--accent-grey);
        display: inline-block;
    }
    
    /* ---------------------------------------------------------------------- */
    /* ESTILOS ESPECÍFICOS DE LA VISTA DE MÉTRICAS (ACORDEÓN Y HOVER) */
    /* ---------------------------------------------------------------------- */
    .collapse-header-row {
        cursor: pointer; 
        font-weight: bold; 
        background-color: rgba(0, 34, 68, 0.05);
        color: var(--primary-dark);
        transition: all 0.2s ease-in-out; 
        font-size: 1.0em;
    }
    
    /* Efecto de acercamiento */
    .collapse-header-row:hover {
        background-color: rgba(0, 34, 68, 0.15); 
        transform: scale(1.01); 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    }
    
    .collapse-content {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }
    .open .collapse-content {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .table-sm th, .table-sm td {
        padding: 0.5rem; 
    }
    
    /* Estilo para los encabezados de tabla oscuros consistente con la paleta */
    .thead-dark th {
        background-color: var(--primary-dark);
        color: var(--bg-light);
    }
    
    /* Estilo para las filas de productos, métodos de pago y otras métricas detalladas */
    .product-detail-row {
        font-size: 1.1em;
    }
    
    /* ====================================================================== */
    /* MODIFICACIONES RESPONSIVE PARA EL DESGLOSE DE CATEGORÍAS */
    /* ====================================================================== */
    
    /* 1. Ocultar el Desglose de Productos en el Colapsable en móviles */
    /* Oculta las columnas de porcentaje para liberar espacio en la tabla anidada */
    /* Esta regla se aplica a pantallas extra-pequeñas (teléfonos) */
    @media (max-width: 575.98px) {
        /* Oculta las columnas % Cat y % Total (Global) en la tabla de productos (colapsable) */
        .collapse-content .table-sm thead th:nth-child(4), 
        .collapse-content .table-sm tbody td:nth-child(4),
        .collapse-content .table-sm thead th:nth-child(5), 
        .collapse-content .table-sm tbody td:nth-child(5) {
            display: none !important;
        }

        /* En la tabla principal, oculta la columna % Total */
        .table-category-main thead th:nth-child(4), 
        .table-category-main tbody td:nth-child(4) {
             display: none !important;
        }
    }
    
    /* 2. Ajuste para pantallas pequeñas (small devices) - Mantenemos algunas columnas */
    @media (min-width: 576px) and (max-width: 767.98px) {
        /* Oculta solo el % Total Global para el desglose de productos */
        .collapse-content .table-sm thead th:nth-child(5), 
        .collapse-content .table-sm tbody td:nth-child(5) {
            display: none !important;
        }
    }
    
</style>

<div class="container-fluid py-4">

    <div class="text-center mb-4">
        <h1 class="admin-title fs-2 pb-2 px-3 fw-bold text-uppercase">Métricas y Reportes</h1>
    </div>
    
    <hr class="mb-5" style="border-top: 2px dashed var(--accent-grey);">

    {{-- SECCIÓN 1: TARJETAS DE RESUMEN (Diario, Semanal, Mensual) --}}
    <div class="row mb-5">
        
        {{-- DIARIO (Color Primario) --}}
        <div class="col-md-4">
            <div class="card mb-3 shadow" style="border-color: var(--primary-dark) !important;"> 
                <div class="card-header text-white text-center fs-5 fw-bold" style="background-color: var(--primary-dark);">VENTAS HOY</div>
                <div class="card-body">
                    <h5 class="card-title text-center fs-3 fw-bold" style="color: var(--primary-dark);">${{ number_format($dailyMetrics['total_sales']) }}</h5>
                    <p class="text-center text-muted">{{ $dailyMetrics['total_orders'] }} Órdenes cerradas</p>
                    <ul class="list-group list-group-flush">
                        {{-- RENOMBRE DE MÉTRICAS --}}
                        <li class="list-group-item"><strong>Mesero Más Vendedor:</strong> {{ $dailyMetrics['top_waiter'] }}</li>
                        <li class="list-group-item"><strong>Producto Más Vendido (Cantidad):</strong> {{ $dailyMetrics['top_product_qty'] }}</li>
                        <li class="list-group-item"><strong>Producto Más Vendido (Dinero):</strong> {{ $dailyMetrics['top_product_money'] }}</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- SEMANAL (Color Éxito) --}}
        <div class="col-md-4">
            <div class="card mb-3 shadow" style="border-color: var(--color-success) !important;">
                <div class="card-header text-white text-center fs-5 fw-bold" style="background-color: var(--primary-dark);">VENTAS SEMANA</div>
                <div class="card-body">
                    <h5 class="card-title text-center fs-3 fw-bold" style="color: var(--color-success);">${{ number_format($weeklyMetrics['total_sales']) }}</h5>
                    <p class="text-center text-muted">{{ $weeklyMetrics['total_orders'] }} Órdenes cerradas</p>
                    <ul class="list-group list-group-flush">
                        {{-- RENOMBRE DE MÉTRICAS --}}
                        <li class="list-group-item"><strong>Mesero Más Vendedor:</strong> {{ $weeklyMetrics['top_waiter'] }}</li>
                        <li class="list-group-item"><strong>Producto Más Vendido (Cantidad):</strong> {{ $weeklyMetrics['top_product_qty'] }}</li>
                        <li class="list-group-item"><strong>Producto Más Vendido (Dinero):</strong> {{ $weeklyMetrics['top_product_money'] }}</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- MENSUAL (Color Informativo) --}}
        <div class="col-md-4">
            <div class="card mb-3 shadow" style="border-color: var(--color-info) !important;">
                <div class="card-header text-white text-center fs-5 fw-bold" style="background-color: var(--primary-dark);">VENTAS MES</div>
                <div class="card-body">
                    <h5 class="card-title text-center fs-3 fw-bold" style="color: var(--color-info);">${{ number_format($monthlyMetrics['total_sales']) }}</h5>
                    <p class="text-center text-muted">{{ $monthlyMetrics['total_orders'] }} Órdenes cerradas</p>
                    <ul class="list-group list-group-flush">
                        {{-- RENOMBRE DE MÉTRICAS --}}
                        <li class="list-group-item"><strong>Mesero Más Vendedor:</strong> {{ $monthlyMetrics['top_waiter'] }}</li>
                        <li class="list-group-item"><strong>Producto Más Vendido (Cantidad):</strong> {{ $monthlyMetrics['top_product_qty'] }}</li>
                        <li class="list-group-item"><strong>Producto Más Vendido (Dinero):</strong> {{ $monthlyMetrics['top_product_money'] }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <hr class="mb-5" style="border-top: 1px solid var(--accent-grey);">

    {{-- SECCIÓN 2: ESTADÍSTICAS DETALLADAS POR MES --}}
    <div class="card mb-5 shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background-color: var(--primary-dark);">
            <h4 class="mb-0 fw-bold">Estadísticas Detalladas del Mes</h4>
            
            <form action="{{ route('metrics.index') }}" method="GET" class="form-inline">
                <label for="month" class="mr-2 text-white">Seleccionar Mes: </label>
                <input type="month" name="month" id="month" class="form-control mr-2" value="{{ $selectedMonth }}" onchange="this.form.submit()">
            </form>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Tabla por Categoría (CON DESGLOSE Y %) --}}
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Ventas por Categoría</h5>
                    {{-- AGREGAR CLASE table-responsive PARA SCROLL HORIZONTAL --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm border table-category-main">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Categoría</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Dinero</th>
                                    <th class="text-right">% Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailedStats['sales_by_category'] as $cat)
                                    {{-- Fila principal de la categoría (con efecto hover/zoom) --}}
                                    <tr class="collapse-header-row" onclick="toggleAccordion(this.nextElementSibling)"> 
                                        <td>{{ ucfirst($cat['type']) }}</td>
                                        <td class="text-right">{{ $cat['quantity'] }}</td>
                                        <td class="text-right">${{ number_format($cat['total_money']) }}</td>
                                        <td class="text-right">{{ $cat['percentage'] }}%</td>
                                    </tr>

                                    {{-- Fila del contenido colapsable (Desglose de productos) --}}
                                    <tr>
                                        <td colspan="4" class="p-0 border-0">
                                            <div class="collapse-content"> 
                                                {{-- AGREGAR CLASE table-responsive PARA SCROLL HORIZONTAL en el desglose--}}
                                                <div class="table-responsive"> 
                                                    <table class="table table-sm m-0">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>Producto</th>
                                                                <th class="text-right">Cant.</th>
                                                                <th class="text-right">Dinero</th>
                                                                <th>% Cat</th> {{-- Columna 4 --}}
                                                                <th class="text-right">% Total (Global)</th> {{-- Columna 5 --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($cat['products'] as $prod)
                                                                @php
                                                                    $catPercentage = ($cat['total_money'] > 0) 
                                                                        ? number_format(($prod['total_money'] / $cat['total_money']) * 100, 2) 
                                                                        : 0;
                                                                @endphp
                                                                <tr class="product-detail-row"> 
                                                                    <td>&nbsp;&nbsp;&nbsp;→ {{ $prod['product_name'] }}</td>
                                                                    <td class="text-right">{{ $prod['quantity'] }}</td>
                                                                    <td class="text-right">${{ number_format($prod['total_money']) }}</td>
                                                                    <td class="text-right text-success font-weight-bold">{{ $catPercentage }}%</td> 
                                                                    <td class="text-right text-muted">{{ $prod['percentage'] }}%</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div> {{-- Cierre de table-responsive --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- Cierre de table-responsive --}}
                </div>

                {{-- Tabla por Mesero (MES) --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Rendimiento Meseros (Mes Seleccionado)</h5>
                    <div class="table-responsive"> {{-- AGREGAR table-responsive --}}
                        <table class="table table-striped table-hover table-sm border">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Mesero</th>
                                    <th class="text-center">Órdenes</th>
                                    <th class="text-right">Total Vendido</th>
                                    <th class="text-right">% Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $monthlyTotalSales = $detailedStats['total_sales'] ?? 1; // Total para el cálculo
                                @endphp
                                @foreach ($detailedStats['waiter_stats'] as $waiter)
                                    @php
                                        $waiterPercentage = ($monthlyTotalSales > 0) 
                                            ? number_format(($waiter['total_sold'] / $monthlyTotalSales) * 100, 2) 
                                            : 0;
                                    @endphp
                                    <tr class="product-detail-row"> 
                                        <td>{{ $waiter['name'] }}</td>
                                        <td class="text-center">{{ $waiter['orders_count'] }}</td>
                                        <td class="text-right">${{ number_format($waiter['total_sold']) }}</td>
                                        {{-- AJUSTE: % en color negro --}}
                                        <td class="text-right text-black font-weight-bold">{{ $waiterPercentage }}%</td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- Cierre de table-responsive --}}
                </div>
            </div>
            
            {{-- 💳 Tabla por Método de Pago (MES) --}}
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="fw-bold mb-3">Ventas por Método de Pago (Mes Seleccionado)</h5>
                    <div class="table-responsive"> {{-- AGREGAR table-responsive --}}
                        <table class="table table-striped table-hover table-sm border">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Método de Pago</th>
                                    <th class="text-right">Monto Recaudado</th>
                                    <th class="text-right">% Total Recaudado</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $paymentMethods = $detailedStats['payment_methods'] ?? [];
                                    $monthlyRecaudadoTotal = collect($paymentMethods)->sum('total_money');
                                    $monthlyDenominator = ($monthlyRecaudadoTotal > 0) ? $monthlyRecaudadoTotal : 1;
                                @endphp
                                
                                @forelse ($paymentMethods as $method)
                                    @php
                                        $totalMoney = data_get($method, 'total_money', 0);
                                        $methodName = data_get($method, 'payment_method', 'Desconocido'); 
                                        $methodPercentage = number_format(($totalMoney / $monthlyDenominator) * 100, 2); 
                                    @endphp
                                    <tr class="product-detail-row"> 
                                        <td>{{ $methodName }}</td>
                                        <td class="text-right">${{ number_format($totalMoney) }}</td>
                                        <td class="text-right text-black">{{ $methodPercentage }}%</td> 
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            ⚠️ **ADVERTENCIA:** No se encontraron datos de métodos de pago para este mes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> {{-- Cierre de table-responsive --}}
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN 3: ESTADÍSTICAS GLOBALES (HISTÓRICO) --}}
    <div class="card mb-5 shadow-lg" style="background-color: var(--menu-card-bg-hover);">
        <div class="card-header text-white" style="background-color: var(--primary-dark);">
            <h4 class="mb-0 fw-bold">Histórico Global (Desde el inicio)</h4>
        </div>
        <div class="card-body">
            <div class="row text-center mb-4">
                <div class="col-md-6">
                    <h2 class="fw-bolder" style="color: var(--primary-dark);">${{ number_format($globalStats['total_sales']) }}</h2>
                    <span class="text-muted">Ventas Totales Históricas</span>
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bolder" style="color: var(--primary-dark);">{{ $globalStats['total_orders'] }}</h2>
                    <span class="text-muted">Órdenes Completadas Totales</span>
                </div>
            </div>

            <hr class="my-4">

            <div class="row mt-4">
                {{-- Tabla Global por Categoría (CON DESGLOSE Y %) --}}
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Global por Categoría</h5>
                    {{-- AGREGAR CLASE table-responsive PARA SCROLL HORIZONTAL --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover border table-category-main">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Categoría</th>
                                    <th class="text-right">Cant.</th>
                                    <th class="text-right">Dinero</th>
                                    <th class="text-right">% Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($globalStats['sales_by_category'] as $cat)
                                    {{-- Fila principal de la categoría (con efecto hover/zoom) --}}
                                    <tr class="collapse-header-row" onclick="toggleAccordion(this.nextElementSibling)">
                                        <td>{{ ucfirst($cat['type']) }}</td>
                                        <td class="text-right">{{ $cat['quantity'] }}</td>
                                        <td class="text-right">${{ number_format($cat['total_money']) }}</td>
                                        <td class="text-right">{{ $cat['percentage'] }}%</td>
                                    </tr>

                                    {{-- Fila del contenido colapsable (Desglose de productos) --}}
                                    <tr>
                                        <td colspan="4" class="p-0 border-0">
                                            <div class="collapse-content">
                                                {{-- AGREGAR CLASE table-responsive PARA SCROLL HORIZONTAL en el desglose--}}
                                                <div class="table-responsive">
                                                    <table class="table table-sm m-0">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>Producto</th>
                                                                <th class="text-right">Cant.</th>
                                                                <th class="text-right">Dinero</th>
                                                                <th>% Cat</th> {{-- Columna 4 --}}
                                                                <th class="text-right">% Total (Global)</th> {{-- Columna 5 --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($cat['products'] as $prod)
                                                                @php
                                                                    $catPercentage = ($cat['total_money'] > 0) 
                                                                        ? number_format(($prod['total_money'] / $cat['total_money']) * 100, 2) 
                                                                        : 0;
                                                                @endphp
                                                                <tr class="product-detail-row"> 
                                                                    <td>&nbsp;&nbsp;&nbsp;→ {{ $prod['product_name'] }}</td>
                                                                    <td class="text-right">{{ $prod['quantity'] }}</td>
                                                                    <td class="text-right">${{ number_format($prod['total_money']) }}</td>
                                                                    <td class="text-right text-success font-weight-bold">{{ $catPercentage }}%</td>
                                                                    <td class="text-right text-muted">{{ $prod['percentage'] }}%</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div> {{-- Cierre de table-responsive --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- Cierre de table-responsive --}}
                </div>
                
                {{-- Tabla Global por Mesero --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Global por Mesero</h5>
                    <div class="table-responsive"> {{-- AGREGAR table-responsive --}}
                        <table class="table table-bordered table-sm table-hover border">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Mesero</th>
                                    <th class="text-center">Órdenes</th>
                                    <th class="text-right">Vendido</th>
                                    <th class="text-right">% Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $globalTotalSales = $globalStats['total_sales'] ?? 1; // Total para el cálculo
                                @endphp
                                @foreach ($globalStats['waiter_stats'] as $waiter)
                                    @php
                                        $waiterPercentage = ($globalTotalSales > 0) 
                                            ? number_format(($waiter['total_sold'] / $globalTotalSales) * 100, 2) 
                                            : 0;
                                    @endphp
                                    <tr class="product-detail-row"> 
                                        <td>{{ $waiter['name'] }}</td>
                                        <td class="text-center">{{ $waiter['orders_count'] }}</td>
                                        <td class="text-right">${{ number_format($waiter['total_sold']) }}</td>
                                        {{-- AJUSTE: % en color negro --}}
                                        <td class="text-right text-black font-weight-bold">{{ $waiterPercentage }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- Cierre de table-responsive --}}
                </div>
            </div>
            
            {{-- 💳 Tabla por Método de Pago (GLOBAL) --}}
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="fw-bold mb-3">Ventas por Método de Pago (Global)</h5>
                    <div class="table-responsive"> {{-- AGREGAR table-responsive --}}
                        <table class="table table-bordered table-sm table-hover border">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Método de Pago</th>
                                    <th class="text-right">Monto Recaudado</th>
                                    <th class="text-right">% Total Recaudado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $globalPaymentMethods = $globalStats['payment_methods'] ?? [];
                                    $globalRecaudadoTotal = collect($globalPaymentMethods)->sum('total_money');
                                    $globalDenominator = ($globalRecaudadoTotal > 0) ? $globalRecaudadoTotal : 1;
                                @endphp
                                
                                @forelse ($globalPaymentMethods as $method)
                                    @php
                                        $totalMoney = data_get($method, 'total_money', 0);
                                        $methodName = data_get($method, 'payment_method', 'Desconocido');
                                        $methodPercentage = number_format(($totalMoney / $globalDenominator) * 100, 2);
                                    @endphp
                                    <tr class="product-detail-row"> 
                                        <td>{{ $methodName }}</td>
                                        <td class="text-right">${{ number_format($totalMoney) }}</td>
                                        <td class="text-right text-black">{{ $methodPercentage }}%</td> 
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            ⚠️ **ADVERTENCIA:** No se encontraron datos de métodos de pago para el histórico global.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> {{-- Cierre de table-responsive --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Función para abrir/cerrar el acordeón basado en la siguiente fila (sibling).
     */
    function toggleAccordion(contentRow) {
        contentRow.classList.toggle('open');
    }
</script>
@endsection