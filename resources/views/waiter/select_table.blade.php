@extends('layouts.app')

@section('content')

<style>
.mesa-card {
width: 190px;
padding: 20px;
border-radius: 12px;
text-align: center;
color: white;
font-weight: bold;
cursor: pointer;
transition: .2s;
position: relative;
display: block; /* Asegura que el enlace funcione bien */
}
.mesa-card:hover { transform: scale(1.05); }

.estado-libre { background: #0b9e49ff; }      /* Verde */
.estado-ocupada { background: #e67e22; }    /* Naranja */
.estado-pedido { background: #c0392b; }     /* Rojo (Usado para &#39;reservada&#39;) */
.lista_para_cobrar { background: #2980b9; } /* Azul para &#39;entregado&#39; */

.preview-box {
    background: white;
    padding: 8px;
    border-radius: 8px;
    margin-top: 8px;
    font-size: 13px;
    color: #333;
}

.action-button-container {
    padding: 5px 0;
}
.btn-quick-close {
    background-color: #dc3545; /* Rojo para cerrar */
    color: white;
    border: none;
    padding: 5px 10px;
    font-size: 11px;
    border-radius: 6px;
    margin-top: 5px;
    width: 100%;
}
.btn-quick-close:hover {
    background-color: #c82333;
}


</style>

<h2 class="mb-3">Vista del Salón</h2>

<div class="d-flex flex-wrap gap-3">

@foreach($tables as $t)

    @php
        // Buscar si hay una orden ACTIVA (PENDIENTE o ENTREGADO)
        $order = \App\Models\Order::where('table_id', $t->id)
            ->whereIn('status', ['pendiente', 'entregado'])
            ->orderByDesc('id') 
            ->first();

        $details = $order
            ? \App\Models\OrderDetail::where('order_id', $order->id)->get()
            : collect();

        $total = $details->sum('subtotal');

        // Determinar la clase visual
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
    {{-- ENLACE PRINCIPAL A LA GESTIÓN DE ORDEN --}}
    <a href="{{ route('waiter.order', $t->id) }}" style="text-decoration:none">
        <div class="mesa-card {{ $class }}" data-table-class-id="{{ $t->id }}">

            <div style="font-size:22px;">Mesa {{ $t->table_number }}</div>

            {{-- VISTA PREVIA DEL PEDIDO --}}
            @if($order)
                <div class="preview-box" data-order-preview-id="{{ $t->id }}">
                    <strong>Orden #{{ $order->id }}</strong><br>
                    
                    {{-- Mostrar estado de la orden --}}
                    <small class="text-{{ $order->status == 'entregado' ? 'success' : 'warning' }}" style="font-weight: bold;" data-order-status-id="{{ $t->id }}">
                         {{ strtoupper($order->status) }}
                    </small>

                    <div class="mt-1 fw-bold">
                        Total: ${{ number_format($total, 0) }}
                    </div>
                </div>
            @else
                <div class="preview-box" data-order-preview-id="{{ $t->id }}">
                     <small style="font-weight: bold;" data-order-status-id="{{ $t->id }}">LIBRE</small>
                </div>
            @endif
        </div>
    </a>
    
    {{-- BOTONES DE ACCIÓN RÁPIDA (FUERA del enlace principal) --}}
    @if($order)
        <div class="action-button-container" data-action-buttons-id="{{ $t->id }}">
            {{-- Botón Pagar Rápido --}}
            <a href="{{ route('payments_order.pay', $order->id) }}" class="btn btn-success w-100 mb-1">
                Pagar
            </a>
            
            {{-- Botón Completar Orden / Liberar Mesa (MISMA ACCIÓN que en manage_order) --}}
            <form action="{{ route('waiter.complete', $order->id) }}" method="POST"
                  onsubmit="return confirm('¿Seguro que deseas CERRAR y LIBERAR la Mesa {{ $t->table_number }}? Esto marca la orden #{{ $order->id }} como CERRADA.')">
                @csrf
                <button type="submit" class="btn-quick-close">
                    Cerrar Orden y Liberar Mesa
                </button>
            </form>
        </div>
    @else
         <div class="action-button-container" data-action-buttons-id="{{ $t->id }}">
            <!-- Botones vacíos para alineación -->
        </div>
    @endif

</div>

@endforeach


</div>
@endsection