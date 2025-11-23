@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4 class="mb-3">Bienvenido al sistema</h4>

                    <div class="d-grid gap-2">

                        <a href="{{ route('orders.index') }}" class="btn btn-primary">
                            📦 Gestionar Órdenes
                        </a>

                        <a href="{{ route('ordersdetail.index') }}" class="btn btn-secondary">
                            🧾 Detalles de Órdenes
                        </a>

                        <a href="{{ route('products.index') }}" class="btn btn-success">
                            🛒 Productos
                        </a>

                        <a href="{{ route('tables.index') }}" class="btn btn-warning">
                            🍽️ Mesas
                        </a>

                        <a href="{{ route('users.index') }}" class="btn btn-dark">
                            👤 Usuarios
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
