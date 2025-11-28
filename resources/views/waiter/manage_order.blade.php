@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary-dark: #2c3e50;
        --primary-dark-hover: #1d2933;
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

    /* ===== CATEGORÍAS ===== */
    .category-btn {
        background: var(--primary-dark);
        color: white;
        border: none;
        font-weight: 600;
    }

    .category-btn:hover {
        background: var(--primary-dark-hover);
    }

    /* ===== PRODUCTOS ===== */
    .product-btn {
        background: #34495e;
        border: none;
        border-radius: 10px;
        padding: 10px;
        color: white;
        font-weight: 600;
        width: 100%;
    }

    .product-btn:hover {
        background: var(--primary-dark);
    }

    /* ===== BOTONES PRINCIPALES (UNIFICADOS) ===== */
    .btn-dark-blue {
        background-color: var(--primary-dark) !important;
        color: white !important;
        border-radius: 8px;
        border: none;
        font-weight: 600;
    }

    .btn-dark-blue:hover {
        background-color: var(--primary-dark-hover) !important;
    }

    /* Botón secundario suave */
    .btn-light-blue {
        background-color: #49627a !important;
        color: white !important;
        border: none;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-light-blue:hover {
        background-color: #3a4e63 !important;
    }

    /* Tabla */
    table {
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

    {{-- ALERTA ESPECIAL --}}
    @if (session('reactivated'))
        <div class="alert alert-warning shadow-sm p-3 border-start border-4 border-warning mb-4">
            <strong>Atención:</strong> Esta orden estaba <strong>ENTREGADA</strong> y fue modificada.
            <br>Especifica claramente a cocina lo que se adicionó o cambió.
        </div>
    @endif

    {{-- ALERTA GENERAL --}}
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

                <button class="btn category-btn w-100"
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
                                    <button class="product-btn">
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
                @if ($order->status !== 'cerrado' && $order->status !== 'cancelado')
                    <th>Notas</th>
                @endif
                <th>Subtotal</th>
                @if ($order->status !== 'cerrado' && $order->status !== 'cancelado')
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
                        <form action="{{ route('waiter.updateQuantity', $d->id) }}" method="POST"
                              class="d-flex justify-content-center align-items-center">
                            @csrf

                            <button name="quantity"
                                    value="{{ $d->quantity > 1 ? $d->quantity - 1 : 0 }}"
                                    class="btn btn-danger btn-sm">-</button>

                            <span class="mx-2">{{ $d->quantity }}</span>

                            <button name="quantity"
                                    value="{{ $d->quantity + 1 }}"
                                    class="btn btn-success btn-sm">+</button>
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
                            <button class="btn btn-danger btn-sm">X</button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3 class="mt-3">Total: ${{ number_format($total, 0) }}</h3>

    @php
        $tieneProductos = $details->count() > 0;
    @endphp

    @if($tieneProductos)
        @php
            // Total pagado desde la tabla payments
            $totalPagado = \Illuminate\Support\Facades\DB::table('payments')
                ->where('order_id', $order->id)
                ->sum('total_pay');
        @endphp

        @if($totalPagado >= $total && $total > 0)
            <div class="alert alert-success mt-2">
                ✔ Esta orden está <strong>PAGADA</strong>.
            </div>
        @else
            <div class="alert alert-danger mt-2">
                ✘ Esta orden <strong>NO está pagada</strong>.
            </div>
        @endif
    @endif

    {{-- ====================================================
        ACCIONES FINALES
    ========================================================= --}}
    <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">

        @if ($order->status === 'entregado' || $order->status === 'pendiente')
            <a href="{{ route('payments_order.pay', $order->id) }}" class="btn btn-dark-blue">
                Pagar
            </a>
        @endif

        <a href="{{ route('payments.invoice', $order->id) }}"
           class="btn btn-light-blue"
           target="_blank">
            Ver Factura
        </a>

        <a href="{{ route('factura.pdf', $order->id) }}"
           class="btn btn-secondary"
           target="_blank">
            Descargar Factura
        </a>

        @if ($order->status !== 'cerrado' && $order->status !== 'cancelado')
            <form action="{{ route('waiter.complete', $order->id) }}" method="POST"
                  onsubmit="return confirm('¿Estás seguro de cerrar la orden y liberar la mesa?')">
                @csrf
                <button type="submit" class="btn btn-dark-blue">
                    Cerrar Orden
                </button>
            </form>

            <button class="btn btn-danger"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#cancelForm">
                Cancelar Orden
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
                    <label class="form-label">Motivo (Obligatorio):</label>
                    <textarea name="reason" class="form-control" rows="2" required></textarea>
                </div>

                <button class="btn btn-danger w-100">
                    Confirmar Cancelación
                </button>
            </form>
        </div>
    @endif

</div>

@endsection
