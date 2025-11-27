@extends('layouts.app')

@section('content')

<a href="{{ route('waiter.mode') }}" class="btn btn-secondary mb-3">← Volver al Salón</a>

{{-- 🚨 ALERTA DE REAPERTURA (FLASH MESSAGE) 🚨 --}}
@if (session('info'))
<div class="alert alert-warning mb-4 p-3 border-start border-3 border-warning" role="alert">
{{ session('info') }}
</div>
@endif

<h2>
Mesa {{ $order->table->table_number }}
<small class="text-muted">
| Orden #{{ $order->id }} - Estado: {{ strtoupper($order->status) }}
</small>
</h2>

{{-- ====================================================
FORMULARIO DE AGREGAR PRODUCTOS (solo si está PENDIENTE O ENTREGADO)
======================================================== --}}
@if ($order->status === 'pendiente' || $order->status === 'entregado')

<h4>Agregar Productos</h4>

@foreach($productsByType as $type => $items)
<div class="mb-3">
<button class="btn btn-dark w-100"
type="button"
data-bs-toggle="collapse"
data-bs-target="#cat-{{ $type }}">
{{ $type }}
</button>

    <div class="collapse mt-2" id="cat-{{ $type }}">
        <div class="row">

            @foreach($items as $p)
                <div class="col-6 col-md-4 mb-2">
                    <form action="{{ route('waiter.addProduct', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <button class="btn btn-primary w-100">
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
======================================================== --}}

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
                <form action="{{ route('waiter.updateQuantity', $d->id) }}" method="POST" class="d-flex">
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

<h4 class="mt-3">Total: ${{ number_format($total, 0) }}</h4>

{{-- ====================================================
ACCIONES FINALES
======================================================== --}}

<div class="mt-3 d-flex flex-wrap gap-2 align-items-center">

@if ($order->status === 'entregado' || $order->status === 'pendiente')
<a href="{{ route('payments_order.pay', $order->id) }}" class="btn btn-success">
Pagar
</a>
@endif

{{-- Factura/PDF --}}
<a href="{{ route('payments.invoice', $order->id) }}"
class="btn btn-primary btn-sm" target="_blank">
🧾 Ver Factura
</a>

<a href="{{ route('factura.pdf', $order->id) }}"
class="btn btn-secondary btn-sm" target="_blank">
⬇ Descargar PDF
</a>

{{-- Botón de Cierre / Liberar Mesa (Completa la orden) --}}
@if ($order->status !== 'cerrado' && $order->status !== 'cancelado')
<form action="{{ route('waiter.complete', $order->id) }}" method="POST"
onsubmit="return confirm('¿Estás seguro de completar la orden y liberar la mesa? Esta acción la marca como CERRADA.')">
@csrf
<button type="submit" class="btn btn-danger">Completar Orden / Liberar Mesa</button>
</form>

{{-- Botón para Cancelar (muestra el formulario colapsable) --}}
<button class="btn btn-warning btn-sm"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#cancelForm">
    ❌ Cancelar Orden
</button>


@endif

</div>

{{-- ====================================================
FORMULARIO DE CANCELACIÓN CON MOTIVO
======================================================== --}}
@if ($order->status !== 'cerrado' && $order->status !== 'cancelado')

<div class="collapse mt-3 p-3 border border-danger bg-light" id="cancelForm">
<h5>Motivo de Cancelación</h5>
{{-- ESTE FORMULARIO ACTIVA EL MÉTODO cancelOrder EN EL CONTROLADOR --}}
<form action="{{ route('waiter.cancelOrder', $order->id) }}" method="POST">
@csrf
@method('PATCH')

<div class="mb-3">
    <label for="cancellation_reason" class="form-label text-danger">Motivo (Opcional):</label>
    <textarea name="reason"
                id="cancellation_reason"
                class="form-control"
                rows="2"
                placeholder="Ej: Cliente canceló el pedido / Producto agotado"></textarea>
</div>

<button type="submit"
        class="btn btn-danger w-100"
        onclick="return confirm('¿Confirmas la CANCELACIÓN de la Orden {{ $order->id }}? Esto liberará la mesa.')">
    CONFIRMAR CANCELACIÓN
</button>


</form>
</div>

@endif

@endsection