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
    }
    .mesa-card:hover { transform: scale(1.05); }

    .estado-libre { background: #0b9e49ff; }      /* Verde */
    .estado-ocupada { background: #e67e22; }    /* Naranja */
    .estado-pedido { background: #c0392b; }     /* Rojo */

    .preview-box {
        background: white;
        padding: 8px;
        border-radius: 8px;
        margin-top: 8px;
        font-size: 13px;
        color: #333;
    }

    .estado-btns a {
        margin: 0 3px;
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 6px;
        display: inline-block;
    }
</style>

<h2 class="mb-3">Vista del Salón</h2>

<div class="d-flex flex-wrap gap-3">

    @foreach($tables as $t)

        {{-- Buscar orden abierta --}}
        @php
        // Buscar si hay una orden PENDIENTE (la única que debe mostrarse)
        $order = \App\Models\Order::where('table_id', $t->id)
            ->where('status', 'pendiente')
            ->first();

        $details = $order
            ? \App\Models\OrderDetail::where('order_id', $order->id)->get()
            : collect();

        $total = $details->sum('subtotal');

                // ACTUALIZAR ESTADO AUTOMÁTICAMENTE SEGÚN PRODUCTOS
        if ($order && $details->count() > 0) {
            if ($t->table_status !== 'ocupada') {
                $t->table_status = 'ocupada';
                $t->save();
            }
        } else {
            if ($t->table_status !== 'libre') {
                $t->table_status = 'libre';
                $t->save();
            }
        }

        // Asignar clase visual
        switch($t->table_status) {
            case 'ocupada':  $class = 'estado-ocupada'; break;
            case 'reservada': $class = 'estado-pedido'; break;
            default:         $class = 'estado-libre'; break;
        }
    @endphp

    <div>
        <a href="{{ route('waiter.order', $t->id) }}" style="text-decoration:none">
            <div class="mesa-card {{ $class }}">

                <div style="font-size:22px;">Mesa {{ $t->table_number }}</div>

              

                {{-- VISTA PREVIA DEL PEDIDO --}}
                @if($order)
                    <div class="preview-box">
                        <strong>Orden #{{ $order->id }}</strong><br>

                        @forelse($details as $d)
                            • {{ $d->product->product_name }} (x{{ $d->quantity }}) <br>
                        @empty
                            <span class="text-muted">Sin productos</span>
                        @endforelse

                        <div class="mt-1 fw-bold">
                            Total: ${{ number_format($total, 0) }}
                        </div>

                    </div>
                    <div  class="mt-1 fw-bold">
                         <a href="{{ route('payments_order.pay', $order->id) }}" class="btn btn-success">
                            Pagar</a>
                    </div>
                @endif
            </div>
            
        </a>


    </div>

@endforeach

</div>
@endsection
