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
        /* ESTILOS COPIADOS DE HOME.BLADE.PHP PARA CONSISTENCIA VISUAL */
        /* ---------------------------------------------------------------------- */
        :root {
            --primary-dark: #002244;
            --accent-grey: #ADB5BD;
            --bg-light: #FFFFFF;
            --hover-darker-blue: #001a33;
            --shadow-dark: rgba(0, 34, 68, 0.6);
            --color-success: #28a745;
            --color-danger: #dc3545;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--primary-dark);
            margin: 20px;
        }

        /* MODIFICADO: Contenedor para el título y el botón de volver */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--accent-grey);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-row h1 {
            color: var(--primary-dark);
            margin: 0;
            padding: 0;
            border: none; /* Quitamos el borde individual del h1 */
        }

        /* NUEVO: Estilo para el botón volver */
        .btn-back {
            background-color: var(--accent-grey);
            color: var(--primary-dark);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-back:hover {
            background-color: #9aa1a7;
            transform: translateY(-2px);
            color: #000;
        }
        
        /* Contenedor principal de la Orden y el Total (NUEVO) */
        .order-summary-header {
            max-width: 600px;
            margin: 0 auto 30px auto; /* Centrado, espacio abajo */
            padding: 20px 30px;
            border: 2px solid var(--primary-dark);
            border-radius: 8px;
            background-color: #e9ecef; /* Fondo suave para destacar */
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .summary-item {
            text-align: center;
            flex-grow: 1;
            padding: 0 10px;
        }

        .summary-label {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--primary-dark);
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--color-danger); /* El total siempre resalta */
            line-height: 1;
        }
        
        /* Estilos del Formulario General */
        form {
            max-width: 600px; 
            padding: 30px; 
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin: 0 auto; /* Centrar el formulario */
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

        /* Botón Crear Pago (similar a waiter-mode-btn) */
        form button[type="submit"] {
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

        form button[type="submit"]:hover {
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
        /* ESTILOS DEL LISTADO DE PAGOS (Oculto) */
        /* ---------------------------------------------------------------------- */

        .hidden { display: none !important; }

        .list-header { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            margin-top: 30px; 
            border-top: 3px solid var(--accent-grey);
            padding-top: 20px;
        }
        
        /* Estilo del campo de clave */
        #secret-key {
            padding: 8px;
            border: 1px solid var(--accent-grey);
            border-radius: 5px;
            width: 150px;
            color: var(--primary-dark);
            background-color: var(--bg-light);
        }

        /* Botón Mostrar/Ocultar (similar al estilo principal) */
        #list-toggle-btn {
            background-color: var(--primary-dark);
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: 600;
        }
        #list-toggle-btn:hover {
            background-color: var(--hover-darker-blue);
        }

        /* Estilo de la tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { 
            background-color: var(--primary-dark);
            color: var(--bg-light);
            font-weight: 700;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tbody tr:hover {
            background-color: rgba(173, 181, 189, 0.2); /* var(--menu-card-bg-hover) */
        }
        
        /* Estilos de Acciones en la Tabla */
        .action-link {
            display: inline-block;
            padding: 5px 8px;
            margin-right: 5px;
            border-radius: 4px;
            font-size: 0.9em;
            text-decoration: none;
            font-weight: 600;
        }
        .action-link.invoice { background-color: var(--accent-grey); color: var(--primary-dark); }
        .action-link.pdf { background-color: var(--primary-dark); color: var(--bg-light); }
        .action-link.edit { background-color: #ffc107; color: var(--primary-dark); }
        .action-link.delete button {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--color-danger);
            font-weight: 600;
            padding: 0;
            text-decoration: underline;
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

    {{-- NUEVO ENCABEZADO PROMINENTE PARA ORDEN Y TOTAL --}}
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
        <table border="1">
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>Orden</th>
                    <th>Mesa</th> {{-- NUEVA COLUMNA --}}
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

                        {{-- NUEVA COLUMNA CON LA MESA --}}
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

                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline;" class="action-link delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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