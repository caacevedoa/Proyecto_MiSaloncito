@extends('layouts.app')

@section('content')

<style>

    :root {
    --primary-dark: #2c3e50;
    --accent-grey: #bdc3c7;
    }

    /* ======== TITULOS H1 ESTILO PERSONALIZADO ======== */
    h1 {
        color: var(--primary-dark);
        border-bottom: 3px solid var(--accent-grey);
        padding-bottom: 5px;
        margin-bottom: 20px;
        font-weight: 700;
    }
    /* ======== CONTENEDOR GENERAL (margen en todo el borde) ======== */
    .salon-wrapper {
        padding: 25px;
    }

    /* ======== TARJETA DE MESA ======== */
    .mesa-card {
        width: 190px;
        padding: 20px;
        border-radius: 14px;
        text-align: center;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: .25s;
        position: relative;
        display: block;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .mesa-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.22);
    }

    /* ======== COLORES ESTADOS ======== */
    .estado-libre       { background: #4CAF50; }
    .estado-ocupada     { background: #D98E3D; }
    .estado-pedido      { background: #C75B57; }
    .lista_para_cobrar  { background: #4169E1; }

    /* ======== CUADRO DE INFORMACIÓN ======== */
    .preview-box {
        background: white;
        padding: 8px;
        border-radius: 8px;
        margin-top: 8px;
        font-size: 13px;
        color: #333;
        box-shadow: inset 0 0 4px rgba(0,0,0,0.08);
    }

    .action-button-container {
        padding: 5px 0;
        text-align: center;
    }

    /* ======== BOTONES AZUL OSCURO PERSONALIZADO (#2c3e50) ======== */
    .btn-dark-blue {
        background-color: #2c3e50 !important;
        border-color: #2c3e50 !important;
        color: white !important;
        border-radius: 8px;
        font-weight: 600;
        transition: .25s;
    }

    .btn-dark-blue:hover {
        background-color: #1f2d3a !important;
        border-color: #1f2d3a !important;
    }

    /* Ajuste exacto al ancho de cada cuadro */
    .action-button-container .btn,
    .action-button-container .btn-dark-blue,
    .btn-quick-close {
        width: 190px !important;
    }

    /* Botón cerrar rápido */
    .btn-quick-close {
        background-color: #2c3e50;
        color: white;
        border: none;
        padding: 5px 10px;
        font-size: 11px;
        border-radius: 8px;
        margin-top: 5px;
        transition: .25s;
    }

    .btn-quick-close:hover {
        background-color: #1f2d3a;
    }
</style>

<div class="salon-wrapper">

    <h1 class="mb-3">Vista del Salón</h1>

<div class="d-flex flex-wrap gap-3">

@foreach($tables as $t)

    @php
        $order = \App\Models\Order::where('table_id', $t->id)
            ->whereIn('status', ['pendiente', 'entregado'])
            ->orderByDesc('id')
            ->first();

        $details = $order
            ? \App\Models\OrderDetail::with('product')->where('order_id', $order->id)->get()
            : collect();

        $total = $details->sum('subtotal');

        $class = 'estado-libre';

        if ($t->table_status === 'ocupada') {
            $class = 'estado-ocupada';
        } elseif ($t->table_status === 'reservada') {
            $class = 'estado-pedido';
        }

        if ($order && $order->status === 'entregado') {
            $class = 'lista_para_cobrar';
        }
    @endphp

    <div class="d-flex flex-column" data-table-id="{{ $t->id }}">

        <a href="{{ route('waiter.order', $t->id) }}" style="text-decoration:none">
            <div class="mesa-card {{ $class }}" data-table-class-id="{{ $t->id }}">

                <div style="font-size:22px;">Mesa {{ $t->table_number }}</div>

                @if($order)
                    <div class="preview-box" data-order-preview-id="{{ $t->id }}">

                        <strong>Orden #{{ $order->id }}</strong>

                        <div style="
                            margin-top: 5px;
                            border-top:1px dashed #DDD;
                            padding-top:5px;
                            max-height:80px;
                            overflow-y:auto;
                        ">
                            @forelse($details as $d)
                                <p style="margin: 0; font-size: 0.9em; line-height: 1.2;">
                                    <strong>{{ $d->quantity }}x</strong> {{ $d->product->product_name ?? 'Producto Eliminado' }}
                                </p>
                            @empty
                                <p style="margin:0; font-size:0.9em; font-style:italic; color:#ADB5BD;">
                                    Aún no hay productos.
                                </p>
                            @endforelse
                        </div>

                        <div class="mt-1 fw-bold">
                            Total: ${{ number_format($total, 0) }}
                        </div>

                    </div>

                @else
                    <div class="preview-box" data-order-preview-id="{{ $t->id }}">
                        <small style="font-weight: bold;" data-order-status-id="{{ $t->id }}">
                            LIBRE
                        </small>
                    </div>
                @endif

            </div>
        </a>

        @if($order)
            <div class="action-button-container" data-action-buttons-id="{{ $t->id }}">

                <a href="{{ route('payments_order.pay', $order->id) }}"
                   class="btn btn-dark-blue mb-1">
                    Pagar
                </a>

                <form action="{{ route('waiter.complete', $order->id) }}" method="POST"
                      onsubmit="return confirm('¿Seguro que deseas CERRAR y LIBERAR la Mesa {{ $t->table_number }}? Esto marca la orden #{{ $order->id }} como CERRADA.')">
                    @csrf
                    <button type="submit" class="btn-dark-blue btn w-100">
                        Cerrar Orden
                    </button>
                </form>

            </div>
        @else
            <div class="action-button-container" data-action-buttons-id="{{ $t->id }}"></div>
        @endif

    </div>

@endforeach

</div>

</div>

@endsection
