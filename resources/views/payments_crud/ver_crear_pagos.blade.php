@extends('layouts.app')

@section('title', 'Pagos - MiSaloncito')
@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos</title>
    <style>
        /* ---------------------------------------------------------------------- */
        /* ESTILOS BASE Y VARIABLES (RESPONSIVE OPTIMIZADO) */
        /* ---------------------------------------------------------------------- */
        :root {
            --primary-dark: #002244;
            --accent-grey: #ADB5BD;
            --bg-light: #FFFFFF;
            --hover-darker-blue: #001a33;
            --shadow-dark: rgba(0, 34, 68, 0.6);
            --color-success: #28a745;
            --color-danger: #dc3545; /* Rojo para eliminar */
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--primary-dark);
            margin: 10px;
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DE ENCABEZADO Y BOTÓN VOLVER */
        /* ---------------------------------------------------------------------- */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--accent-grey);
            padding-bottom: 10px;
            margin-bottom: 20px;
            flex-wrap: nowrap;
        }

        .header-row h1 {
            color: var(--primary-dark);
            margin: 0;
            padding: 0;
            border: none;
            font-size: 1.5rem; /* Ajuste para móviles */
        }

        .btn-back {
            background-color: var(--accent-grey);
            color: var(--primary-dark);
            text-decoration: none;
            padding: 6px 10px; /* Ajuste para móviles */
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            white-space: nowrap;
        }

        .btn-back:hover {
            background-color: #9aa1a7;
            transform: translateY(-2px);
            color: #000;
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DEL RESUMEN DE LA ORDEN */
        /* ---------------------------------------------------------------------- */
        .order-summary-header {
            max-width: 600px;
            margin: 0 auto 30px auto;
            padding: 15px 20px; /* Reducción de padding */
            border: 2px solid var(--primary-dark);
            border-radius: 8px;
            background-color: #e9ecef;
            display: flex;
            justify-content: space-around; /* Usamos space-around para mejor espaciado */
            align-items: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .summary-item {
            text-align: center;
            flex-grow: 1;
            padding: 0 5px; /* Reducción de padding interno */
        }

        .summary-label {
            font-size: 0.9rem; /* Ajuste para móviles */
            font-weight: 500;
            color: var(--primary-dark);
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 1.8rem; /* Ajuste para móviles */
            font-weight: 900;
            color: var(--primary-dark);
            line-height: 1.2;
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DEL FORMULARIO */
        /* ---------------------------------------------------------------------- */
        form {
            max-width: 600px;
            padding: 20px; /* Reducción de padding */
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin: 0 auto;
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

        /* Botón Crear Pago */
        form button[type="submit"]:not(.action-link button) { /* Aseguramos que no aplique a los botones de acción */
            background-color: var(--primary-dark);
            color: var(--bg-light);
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px var(--shadow-dark);
            border: none;
            padding: 10px 20px; /* Ajuste de padding */
            text-transform: uppercase;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }

        form button[type="submit"]:hover:not(.action-link button) {
            background-color: var(--hover-darker-blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px var(--shadow-dark);
        }

        /* Manejo de errores de validación */
        .alert-danger {
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f8d7da;
            border-radius: 5px;
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DEL LISTADO DE PAGOS (RESPONSIVE) */
        /* ---------------------------------------------------------------------- */

        .hidden { display: none !important; }

        .list-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            border-top: 3px solid var(--accent-grey);
            padding-top: 20px;
            flex-wrap: wrap; 
        }

        .list-header h1 {
            font-size: 1.4rem; 
            margin: 0;
            white-space: nowrap; 
        }

        #secret-key {
            padding: 8px;
            border: 1px solid var(--accent-grey);
            border-radius: 5px;
            width: 120px;
            color: var(--primary-dark);
            background-color: var(--bg-light);
        }

        #list-toggle-btn {
            background-color: var(--primary-dark);
            color: white;
            padding: 8px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        #list-toggle-btn:hover {
            background-color: var(--hover-darker-blue);
        }

        /* Nuevo contenedor para hacer la tabla scrollable en móvil */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
            margin-top: 10px;
        }

        /* Estilo de la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px; 
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px; 
            text-align: left;
            font-size: 0.9rem;
            vertical-align: middle;
        }
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

        /* Estilos de Acciones en la Tabla */
        td:last-child {
            white-space: nowrap;
            display: flex;
            flex-wrap: nowrap; 
            gap: 8px; 
            align-items: center;
        }

        /* ESTILOS DE BOTONES DE ACCIÓN (A y FORM) */
        .action-link {
            /* Propiedades de tamaño y apariencia iguales para A y FORM */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 10px; 
            border-radius: 6px;
            font-size: 0.85em;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            text-align: center;
            line-height: 1; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            min-width: 125px; /* Ancho uniforme (basado en 'Descargar Factura') */
            box-sizing: border-box; 
            margin: 0; /* Aseguramos que el form no tenga margen por defecto */
        }
        .action-link:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15); 
        }

        /* Colores de los botones A */
        .action-link.invoice { background-color: var(--accent-grey); color: var(--primary-dark); }
        .action-link.invoice:hover { background-color: #9aa1a7; }

        .action-link.pdf { background-color: var(--primary-dark); color: var(--bg-light); }
        .action-link.pdf:hover { background-color: var(--hover-darker-blue); }

        .action-link.edit { background-color: #ffc107; color: var(--primary-dark); }
        .action-link.edit:hover { background-color: #e0a800; }

        /* Estilo para el botón de eliminar (FORM) - Color rojo */
        .action-link.delete {
            background-color: var(--color-danger);
            color: var(--bg-light);
        }
        .action-link.delete:hover {
            background-color: #c82333; 
        }

        /* El botón <button> dentro del <form class="action-link delete">
           Lo hacemos transparente para que el FORM actúe como el botón. */
        .action-link.delete button {
            /* Copiamos el estilo de fuente exacto del action-link */
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            
            /* Eliminamos todo el estilo nativo de botón */
            background: none;
            border: none;
            cursor: pointer;
            color: inherit; 
            padding: 0; 
            
            /* Hacemos que ocupe el 100% del formulario para que el área de clic sea el fondo rojo */
            width: 100%; 
            height: 100%;
            display: flex; 
            align-items: center;
            justify-content: center;
            line-height: 1; /* Esencial para el centrado vertical */
            margin: 0; 
        }

        /* ---------------------------------------------------------------------- */
        /* MEDIA QUERIES PARA PANTALLAS GRANDES */
        /* ---------------------------------------------------------------------- */
        @media (min-width: 768px) {
            body {
                margin: 20px;
            }
            .header-row h1 {
                font-size: 2rem;
            }
            
            th, td {
                padding: 12px;
                font-size: 1rem;
            }
            td:last-child {
                display: flex; 
                flex-wrap: nowrap; 
                gap: 8px; 
                white-space: normal; 
            }
            .action-link {
                padding: 8px 10px; 
                min-width: 125px; 
            }
        }
    </style>
</head>
<body>

    {{-- HEADER CON TÍTULO Y BOTÓN DE VOLVER --}}
    <div class="header-row">
        <h1>Registrar Pago</h1>
        {{-- Botón para volver al Salón (waiter.mode) --}}
        <a href="{{ route('waiter.mode') }}" class="btn-back">
            ← Volver al Salón
        </a>
    </div>

    @if(session('success'))
        <p style="color: var(--color-success); font-weight: bold;">{{ session('success') }}</p>
    @endif

    {{-- Manejo de errores de validación --}}
    @if ($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ENCABEZADO PROMINENTE PARA ORDEN Y TOTAL --}}
    <div class="order-summary-header">
        <div class="summary-item">
            <div class="summary-label">Orden Número</div>
            <div class="summary-value" style="color: var(--primary-dark);">{{ $id ?? 'N/A' }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total a Pagar</div>
            {{-- APLICAR FORMATO DE MILES AQUÍ --}}
            <div class="summary-value" style="color: var(--primary-dark);">${{ number_format($total ?? 0, 0, '.', ',') }}</div>
        </div>
    </div>
    {{-- FIN DEL ENCABEZADO PROMINENTE --}}


    <form action="{{ route('payments.store') }}" method="post">
        @csrf

        {{-- CAMPOS OCULTOS PARA ENVIAR DATOS DE ORDEN Y TOTAL --}}
        <input type="hidden" name="order_id" value="{{ $id ?? '' }}">
        <input type="hidden" name="total_pay" value="{{ $total ?? '' }}">

        <div class="form-group">
            <label for="payment_method">Medio de pago:</label>
            <select name="payment_method" id="payment_method">
                <option value="efectivo">Efectivo</option>
                <option value="Bancolombia">Bancolombia</option>
                <option value="Nequi">Nequi</option>
                <option value="Daviplata">Daviplata</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
        </div>

        <div class="form-group">
            <label for="payment_status">Estado del pago:</label>
            <select name="payment_status" id="payment_status">
                <option value="completado">Completado</option>
                <option value="pendiente">Pendiente</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

        <button type="submit">Registrar Pago</button>

    </form>

    <hr>

    {{-- ENCABEZADO Y CAMPO DE CLAVE PARA DESPLEGAR --}}
    <div class="list-header">
        <h1>Listado de Pagos</h1>
        <input type="password" id="secret-key" placeholder="Ingresa Clave (1234)">
        <button type="button" id="list-toggle-btn">Mostrar/Ocultar</button>
    </div>

    {{-- LISTADO DE PAGOS (Inicialmente oculto) --}}
    <div id="payment-list-container" class="hidden">
        <div class="table-responsive">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID Pago</th>
                        <th>Orden</th>
                        <th>Mesa</th>
                        <th>Fecha y hora</th>
                        <th>Método de pago</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->order->id }}</td>

                            {{-- COLUMNA CON LA MESA --}}
                            <td>
                                {{ $payment->order->table->table_number ?? 'N/A' }}
                            </td>

                            <td>{{ $payment->payment_date }}</td>
                            <td>{{ $payment->payment_method }}</td>

                            {{-- Formato de miles --}}
                            <td>${{ number_format($payment->total_pay, 0, '.', ',') }}</td>

                            <td>{{ $payment->payment_status }}</td>

                            <td>
                                <a href="{{ route('payments.invoice', $payment->order_id) }}" class="action-link invoice">
                                    Ver Factura
                                </a>

                                <a href="{{ route('factura.pdf', $payment->order_id) }}" class="action-link pdf">
                                    Descargar Factura
                                </a>

                                <a href="{{ route('payments.edit', $payment->id) }}" class="action-link edit">
                                    Editar
                                </a>

                                {{-- El formulario de eliminar --}}
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="action-link delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">ELIMINAR</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const keyInput = document.getElementById('secret-key');
            const toggleButton = document.getElementById('list-toggle-btn');
            const listContainer = document.getElementById('payment-list-container');
            const CORRECT_KEY = '1234'; // CLAVE SECRETA

            toggleButton.addEventListener('click', function() {
                // 1. Si el listado ya está visible, lo ocultamos inmediatamente
                if (!listContainer.classList.contains('hidden')) {
                    listContainer.classList.add('hidden');
                    return;
                }

                // 2. Si está oculto, verificamos la clave
                if (keyInput.value === CORRECT_KEY) {
                    listContainer.classList.remove('hidden');
                    keyInput.value = ''; // Limpiar la clave después de usarla
                } else {
                    alert('Clave incorrecta. Acceso denegado.');
                    keyInput.value = '';
                }
            });

            // Permite desplegar presionando Enter en el campo de clave
            keyInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Evitar envío del formulario principal
                    toggleButton.click();
                }
            });
        });
    </script>

</body>
</html>

@endsection