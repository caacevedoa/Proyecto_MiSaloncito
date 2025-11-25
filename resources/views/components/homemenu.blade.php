<div class="dropdown mb-4">
    <button class="btn btn-outline-primary dropdown-toggle" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
        Navegar
    </button>

    <ul class="dropdown-menu">

        <li><a class="dropdown-item" href="{{ route('orders.index') }}">Órdenes</a></li>
        <li><a class="dropdown-item" href="{{ route('tables.index') }}">Mesas</a></li>
        <li><a class="dropdown-item" href="{{ route('products.index') }}">Productos</a></li>
        <li><a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a></li>
        <li><a class="dropdown-item" href="{{ route('payments.index') }}">Pagos</a></li>
        <li><a class="dropdown-item" href="{{ route('metrics.index') }}">Métricas</a></li>

    </ul>
</div>
