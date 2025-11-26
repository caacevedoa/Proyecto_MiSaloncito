@extends('layouts.app')

@section('content')
<a href="{{ route('waiter.mode') }}" class="btn btn-secondary mb-3">← Volver al Salón</a>

<h2>Mesa {{ $order->table->table_number }}  
    <small class="text-muted">| Orden #{{ $order->id }}</small>
</h2>

<h4>Agregar Productos</h4>

{{-- 🔽 Categorías desplegables --}}
@foreach($productsByType as $type => $items)
    <div class="mb-3">
        <button class="btn btn-dark w-100" type="button" data-bs-toggle="collapse" data-bs-target="#cat-{{ $type }}">
            {{ $type }}
        </button>

        <div class="collapse mt-2" id="cat-{{ $type }}">
            <div class="row">
                @foreach($items as $p)
                    <div class="col-6 col-md-4 mb-2">
                        <form action="{{ route('waiter.addProduct', $order->id) }}" method="POST" onclick="playSound()">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <button class="btn btn-primary w-100">
                                {{ $p->product_name }} <br>
                                <small>${{ number_format($p->unit_price,0) }}</small>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach

<hr>

{{-- ========================
      PEDIDO ACTUAL
========================= --}}
<h3>Pedido Actual</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cant.</th>
            <th>Notas</th>
            <th>Subtotal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($details as $d)
        <tr>
            <td>{{ $d->product->product_name }}</td>

            <td>
                <form action="{{ route('waiter.updateQuantity', $d->id) }}" method="POST" class="d-flex">
                    @csrf
                    <button name="quantity" value="{{ $d->quantity - 1 }}" class="btn btn-danger btn-sm">-</button>
                    <span class="mx-2">{{ $d->quantity }}</span>
                    <button name="quantity" value="{{ $d->quantity + 1 }}" class="btn btn-success btn-sm">+</button>
                </form>
            </td>

            <td>
                <form action="{{ route('waiter.updateComment', $d->id) }}" method="POST">
                    @csrf
                    <input type="text" name="comment" value="{{ $d->comment }}" class="form-control form-control-sm">
                </form>
            </td>

            <td>${{ number_format($d->subtotal, 0) }}</td>

            <td>
                <form action="{{ route('waiter.deleteDetail', $d->id) }}" method="POST">
                    @csrf 
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">✕</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<h4 class="mt-3">Total: ${{ number_format($total, 0) }}</h4>
<a href="{{ route('payments_order.pay', $order->id) }}" class="btn btn-success">
    Pagar
</a>

<div class="mt-3">
    <a href="{{ route('payments.invoice', $order->id) }}" 
       class="btn btn-primary btn-sm" target="_blank">
        🧾 Ver Factura
    </a>

    <a href="{{ route('factura.pdf', $order->id) }}" 
       class="btn btn-secondary btn-sm" target="_blank">
        ⬇ Descargar PDF
    </a>
    
    <form action="{{ route('waiter.complete', $order->id) }}" method="POST"
        onsubmit="return confirm('¿Estás seguro de completar la orden?')">
        @csrf
        <button type="submit" class="btn btn-success">Completar Orden</button>
    </form>
</div>

@endsection
