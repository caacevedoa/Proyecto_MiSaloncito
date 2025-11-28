<style>
    .menu-select {
        background-color: #002244 !important; /* Igual que la barra */
        color: white !important;
        border: 1px solid #002244;
        border-radius: 6px;
        padding: 6px 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .menu-select option {
        background-color: white;
        color: black;
    }

    /* Quitar borde azul feo en focus */
    .menu-select:focus {
        box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
        outline: none;
    }
</style>

<select class="menu-select"
        onchange="if (this.value) window.location.href = this.value;">

    <option value="">Navegar</option>
    <option value="{{ route('tables.index') }}">Mesas</option>
    <option value="{{ route('products.index') }}">Productos</option>
    <option value="{{ route('orders.index') }}">Órdenes</option>
    <option value="{{ route('ordersdetail.index') }}">Detalles</option>
    <option value="{{ route('payments.index') }}">Pagos</option>
    <option value="{{ route('users.index') }}">Usuarios</option>
    <option value="{{ route('metrics.index') }}">Métricas</option>

</select>
