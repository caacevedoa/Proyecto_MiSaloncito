@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Detalle de Orden</title>
    <style>
        /* ---------------------------------------------------------------------- */
        /* ESTILOS GENERALES */
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

        /* ---------------------------------------------------------------------- */
        /* FORMULARIO */
        /* ---------------------------------------------------------------------- */
        form.main-form {
            max-width: 700px; 
            padding: 30px; 
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin: 0 auto 40px auto; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-group { margin-bottom: 20px; }

        form label { display: block; margin-bottom: 5px; font-weight: 600; }

        form select, form input[type="number"], form input[type="text"], form input[type="date"] {
            width: 100%; padding: 10px; border: 1px solid var(--accent-grey); border-radius: 6px; box-sizing: border-box; color: var(--primary-dark); background-color: var(--bg-light); font-size: 1rem;
        }

        button.btn-primary {
            background-color: var(--primary-dark); color: var(--bg-light); 
            transition: all 0.3s ease; box-shadow: 0 4px 10px var(--shadow-dark); 
            border: none; padding: 12px 25px; text-transform: uppercase; font-weight: 700; border-radius: 8px; cursor: pointer; width: 100%; margin-top: 10px;
        }
        button.btn-primary:hover { background-color: var(--hover-darker-blue); transform: translateY(-2px); }

        /* ---------------------------------------------------------------------- */
        /* LISTADO DESPLEGABLE */
        /* ---------------------------------------------------------------------- */
        .filter-container {
            background-color: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 15px; align-items: flex-end; border: 1px solid var(--accent-grey);
        }
        .filter-group { flex: 1; }
        .filter-group label { font-size: 0.9em; font-weight: bold; display: block; margin-bottom: 5px;}

        .order-group-container {
            margin-bottom: 15px; /* Menos margen porque estarán cerrados */
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            background: white;
        }

        /* HEADER QUE FUNCIONA COMO BOTÓN */
        .order-header {
            background-color: var(--primary-dark);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer; /* Importante para UX */
            user-select: none;
            transition: background-color 0.2s;
        }
        .order-header:hover {
            background-color: var(--hover-darker-blue);
        }

        .order-header h2 { margin: 0; font-size: 1.2rem; color: white; border: none; padding: 0; }

        /* Flecha de rotación */
        .toggle-icon {
            font-size: 1.2em;
            transition: transform 0.3s ease;
            margin-left: 10px;
        }
        .order-group-container.open .toggle-icon {
            transform: rotate(180deg); /* Rota la flecha cuando está abierto */
        }

        /* CONTENIDO OCULTO/VISIBLE */
        .order-content {
            display: none; /* Oculto por defecto */
            animation: fadeIn 0.3s ease-in-out;
        }
        .order-group-container.open .order-content {
            display: block; /* Visible cuando tiene la clase open */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; }
        th { background-color: #f1f3f5; color: var(--primary-dark); font-weight: 700; text-transform: uppercase; font-size: 0.85rem; }
        
        .action-link { display: inline-block; padding: 5px 10px; border-radius: 4px; font-size: 0.85em; text-decoration: none; font-weight: 600; text-align: center; border: none; cursor: pointer; }
        .action-link.edit { background-color: var(--color-warning); color: var(--primary-dark); }
        .action-link.delete button { background: none; border: none; cursor: pointer; color: var(--color-danger); font-weight: 700; padding: 0; text-decoration: underline; }
        
        .total-row { background-color: #e9ecef; font-weight: bold; color: var(--primary-dark); font-size: 1.1em; }
        .hidden-filter { display: none !important; } /* Clase para el filtro de búsqueda */

    </style>
</head>
<body>

    <h1>Crear Detalle de Orden</h1>

    <form action="{{ route('ordersdetail.store') }}" method="post" class="main-form">
        @csrf
        <div class="form-group">
            <label for="order_id">Seleccione la orden:</label>
            <select name="order_id" id="order_id">
                @foreach ($orders as $order)
                    <option value="{{ $order->id }}">Orden #{{ $order->id }} - Mesa {{ $order->table->table_number ?? '?' }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="product_id">Seleccione el producto:</label>
            <select name="product_id" id="product_id">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Cantidad:</label>
            <input type="number" name="quantity" id="quantity" min="1" value="1">
        </div>
        <button type="submit" class="btn-primary">Crear Detalle</button>
    </form>

    <hr style="margin: 40px 0; border-color: var(--accent-grey);">

    <h1>Listado de Detalles de Orden</h1>

    <div class="filter-container">
        <div class="filter-group">
            <label for="searchOrder">Buscar por N° Orden:</label>
            <input type="number" id="searchOrder" placeholder="Ej: 105" onkeyup="filterOrders()">
        </div>
        <div class="filter-group">
            <label for="searchDate">Filtrar por Fecha:</label>
            <input type="date" id="searchDate" onchange="filterOrders()">
        </div>
        <div class="filter-group" style="flex: 0;">
            <label>&nbsp;</label>
            <button onclick="clearFilters()" class="btn-primary" style="margin:0; padding: 10px 20px; background-color: var(--accent-grey); width: auto;">Limpiar</button>
        </div>
    </div>

    @php
        $groupedDetails = $details->groupBy('order_id');
    @endphp

    <div id="orders-list">
        @foreach ($groupedDetails as $orderId => $orderDetails)
            @php
                $parentOrder = $orderDetails->first()->order;
                $tableNum = $parentOrder->table->table_number ?? 'N/A';
                $orderDateRaw = $parentOrder->order_datetime ?? ''; 
                $orderDate = substr($orderDateRaw, 0, 10); 
            @endphp

            <div class="order-group-container" data-id="{{ $orderId }}" data-date="{{ $orderDate }}">
                
                <div class="order-header" onclick="toggleAccordion(this)">
                    <div style="display: flex; align-items: center;">
                        <h2>🛒 Orden #{{ $orderId }}</h2>
                        <span class="toggle-icon">▼</span> </div>
                    <div style="text-align: right; font-size: 0.9em;">
                        <div><strong>Mesa:</strong> {{ $tableNum }}</div>
                        <div><strong>Fecha:</strong> {{ $orderDateRaw }}</div>
                    </div>
                </div>

                <div class="order-content">
                    <table border="0">
                        <thead>
                            <tr>
                               
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $orderTotal = 0; @endphp
                            
                            @foreach ($orderDetails as $detail)
                                @php $orderTotal += ($detail->subtotal); @endphp
                                <tr>
                                    
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>${{ number_format($detail->unit_price, 2) }}</td>
                                    <td>${{ number_format($detail->subtotal, 2) }}</td>

                                    <td style="text-align: center;">
                                        <a href="{{ route('ordersdetail.edit', $detail->id) }}" class="action-link edit">Editar</a>

                                        <form action="{{ route('ordersdetail.destroy', $detail->id) }}" method="POST" style="display:inline;" class="action-link delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="4" style="text-align: right; padding-right: 20px;">TOTAL ORDEN:</td>
                                <td>${{ number_format($orderTotal, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    @if ($groupedDetails->isEmpty())
        <p style="text-align: center; color: var(--accent-grey); font-size: 1.2em;">No hay detalles de órdenes registrados.</p>
    @endif

    <script>
        // Función para abrir/cerrar acordeón
        function toggleAccordion(headerElement) {
            const container = headerElement.parentElement;
            container.classList.toggle('open');
        }

        // Función de filtrado
        function filterOrders() {
            const idInput = document.getElementById('searchOrder').value.toString();
            const dateInput = document.getElementById('searchDate').value.toString();
            const containers = document.querySelectorAll('.order-group-container');

            containers.forEach(container => {
                const orderId = container.getAttribute('data-id');
                const orderDate = container.getAttribute('data-date');

                let matchId = true;
                let matchDate = true;

                if (idInput !== '' && !orderId.includes(idInput)) {
                    matchId = false;
                }
                if (dateInput !== '' && orderDate !== dateInput) {
                    matchDate = false;
                }

                if (matchId && matchDate) {
                    container.classList.remove('hidden-filter');
                    
                    // UX EXTRA: Si el usuario está buscando una orden específica por ID, 
                    // la abrimos automáticamente para que vea los detalles.
                    if (idInput !== '') {
                        container.classList.add('open');
                    }
                } else {
                    container.classList.add('hidden-filter');
                }
            });
        }

        function clearFilters() {
            document.getElementById('searchOrder').value = '';
            document.getElementById('searchDate').value = '';
            
            // Cerramos todos los acordeones al limpiar
            const containers = document.querySelectorAll('.order-group-container');
            containers.forEach(c => {
                c.classList.remove('open');
                c.classList.remove('hidden-filter');
            });
        }
    </script>

</body>
</html>

@endsection