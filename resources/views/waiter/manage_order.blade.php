@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary-dark: #2c3e50;
        --accent-grey: #bdc3c7;
    }

    /* ===== TITULOS ===== */
    h1, h2, h3, h4, h5 {
        color: var(--primary-dark);
        font-weight: 700;
    }

    h1, h2 {
        border-bottom: 3px solid var(--accent-grey);
        padding-bottom: 8px;
        margin-bottom: 20px;
    }

    /* ===== CONTENEDOR GENERAL ===== */
    .order-wrapper {
        max-width: 900px;
        margin: auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.1);
    }

    .category-btn {
        background: var(--primary-dark);
        border: none;
        font-weight: 600;
    }

    .category-btn:hover {
        background: #1d2933;
    }

    /* Productos */
    .product-btn {
        background: #34495e;
        border: none;
        border-radius: 10px;
        padding: 10px;
        color: white;
        font-weight: 600;
    }

    .product-btn:hover {
        background: #2c3e50;
    }

    /* Botones principales */
    .btn-dark-blue {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
        color: white !important;
        font-weight: 600;
        border-radius: 8px;
    }

    .btn-dark-blue:hover {
        background-color: #1d2933 !important;
    }

    /* Tabla */
    table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    th {
        background: var(--primary-dark);
        color: white;
        font-weight: 600;
        text-align: center;
    }

    td {
        vertical-align: middle;
        text-align: center;
    }

    /* Caja de cancelación */
    #cancelForm {
        border-radius: 10px;
    }

    .go-back {
        margin-bottom: 20px;
        display: inline-block;
    }
</style>

<div class="order-wrapper">

    <a href="{{ route('waiter.mode') }}" class="btn btn-secondary go-back">
        ← Volver al Salón
    </a>

    {{-- ALERTA --}}
    @if (session('info'))
        <div class="alert alert-warning mb-4 p-3 border-start border-4 border-warning">
            {{ session('info') }}
        </div>
    @endif

    <h2>
        Mesa {{ $order->table->table_number }}
        <small class="text-muted">| Orden #{{ $order->id }} - Estado: {{ strtoupper($order->status) }}</small>
    </h2>

    {{-- ====================================================
        AGREGAR PRODUCTOS
    ========================================================= --}}
    @if ($order->status === 'pendiente' || $order->status === 'entregado')

        <h3>Agregar Productos</h3>

        @foreach($productsByType as $type => $items)
        <div class="mb-3">

            <button 
                class="btn category-btn w-100"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#cat-{{ $type }}">
                {{ strtoupper($type) }}
            </button>

            <div class="collapse mt-2" id="cat-{{ $type }}">
                <div class="row">

                    @foreach($items as $p)
                        <div class="col-6 col-md-4 mb-2">
                            <form action="{{ route('waiter.addProduct', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                <button class="product-btn w-100">
                                    {{ $p->product_name }} <br>
                                    <small>${{ number_format($p->unit_price, 0) }}</small>
                                </button>
                            </form>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
        @endforeach

        <hr>

    @endif

    {{-- ====================================================
        PEDIDO ACTUAL
    ========================================================= --}}
    <h3>Pedido Actual</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant.</th>
                @if ($order->status === 'pendiente' || $order->status === 'entregado')
                <th>Notas</th>
                @endif
                <th>Subtotal</th>
                @if ($order->status === 'pendiente' || $order->status === 'entregado')
                <th></th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach($details as $d)
            <tr>
                <td>{{ $d->product->product_name }}</td>

                {{-- Cantidad --}}
                @if ($order->status === 'pendiente' || $order->status === 'entregado')
                    <td>
                        <form action="{{ route('waiter.updateQuantity', $d->id) }}" method="POST" class="d-flex justify-content-center align-items-center">
                            @csrf
                            <button name="quantity" value="{{ $d->quantity - 1 }}" class="btn btn-danger btn-sm">-</button>
                            <span class="mx-2">{{ $d->quantity }}</span>
                            <button name="quantity" value="{{ $d->quantity + 1 }}" class="btn btn-success btn-sm">+</button>
                        </form>
                    </td>
                @else
                    <td>{{ $d->quantity }}</td>
                @endif

                {{-- Notas --}}
                @if ($order->status === 'pendiente' || $order->status === 'entregado')
                    <td>
                        <form action="{{ route('waiter.updateComment', $d->id) }}" method="POST">
                            @csrf
                            <input type="text"
                                   name="comment"
                                   value="{{ $d->comment }}"
                                   class="form-control form-control-sm"
                                   onchange="this.form.submit()">
                        </form>
                    </td>
                @endif

                {{-- Subtotal --}}
                <td>${{ number_format($d->subtotal, 0) }}</td>

                {{-- Eliminar --}}
                @if ($order->status === 'pendiente' || $order->status === 'entregado')
                    <td>
                        <form action="{{ route('waiter.deleteDetail', $d->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">✕</button>
                        </form>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="mt-3">Total: ${{ number_format($total, 0) }}</h3>

    {{-- ====================================================
        ACCIONES FINALES
    ========================================================= --}}

    <div class="mt-4 d-flex flex-wrap gap-2 align-items-center justify-content-center">

        @if ($order->status === 'entregado' || $order->status === 'pendiente')
        <a href="{{ route('payments_order.pay', $order->id) }}" class="btn btn-dark-blue">
            💳 Pagar
        </a>
        @endif

        <a href="{{ route('payments.invoice', $order->id) }}" class="btn btn-primary btn-sm" target="_blank">
            🧾 Ver Factura
        </a>

        <a href="{{ route('factura.pdf', $order->id) }}" class="btn btn-secondary btn-sm" target="_blank">
            ⬇ Descargar PDF
        </a>

        @if ($order->status !== 'cerrado' && $order->status !== 'cancelado')
        <form action="{{ route('waiter.complete', $order->id) }}" method="POST"
              onsubmit="return confirm('¿Estás seguro de completar la orden y liberar la mesa?')">
            @csrf
            <button type="submit" class="btn btn-danger">
                ✔ Completar Orden
            </button>
        </form>

        <button class="btn btn-warning btn-sm"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#cancelForm">
            ❌ Cancelar Orden
        </button>
        @endif

    </div>

    {{-- ====================================================
        FORMULARIO DE CANCELACIÓN
    ========================================================= --}}
    @if ($order->status !== 'cerrado' && $order->status !== 'cancelado')

    <div class="collapse mt-3 p-3 bg-light border border-danger" id="cancelForm">
        <h5 class="text-danger">Motivo de Cancelación</h5>

        <form action="{{ route('waiter.cancelOrder', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="cancellation_reason" class="form-label">Motivo (Obligatorio):</label>
                <textarea name="reason" id="cancellation_reason" class="form-control" rows="2" required></textarea>
            </div>

            <button type="submit" class="btn btn-danger w-100">
                CONFIRMAR CANCELACIÓN
            </button>
        </form>
    </div>

    @endif

</div>

@endsection
