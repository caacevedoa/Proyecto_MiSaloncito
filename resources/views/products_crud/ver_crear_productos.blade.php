@extends('layouts.app')

@section('title', 'Productos - MiSaloncito')

@section('content')

<style>
    /* VARIABLES DE ESTILO */
    :root {
        --primary-dark: #002244;
        --accent-grey: #ADB5BD;
        --bg-light: #FFFFFF;
        --hover-darker-blue: #001a33;
        --shadow-dark: rgba(0, 34, 68, 0.6);
        --menu-card-bg-initial: rgba(173, 181, 189, 0.1);
        --delete-color: #495057;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--primary-dark);
    }

    .crud-title {
        color: var(--primary-dark);
        border-left: 5px solid var(--accent-grey);
        padding-left: 15px;
        font-weight: 600;
    }

    .form-container {
        background-color: var(--menu-card-bg-initial);
        border: 1px solid #e9ecef;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 34, 68, 0.1);
        margin-bottom: 40px;
    }

    .btn-custom-primary {
        background-color: var(--primary-dark);
        color: var(--bg-light);
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 34, 68, 0.4);
    }
    .btn-custom-primary:hover {
        background-color: var(--hover-darker-blue);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px var(--shadow-dark);
    }

    .form-control, .form-select {
        border-color: var(--accent-grey);
        box-shadow: none !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-dark);
    }

    /* ========  TABLA MEJORADA – COLUMNAS UNIFORMES ======== */
    .table-custom {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--accent-grey);
        table-layout: fixed; /* Columnas uniformes */
        width: 100%;
    }
    .table-custom thead th {
        background-color: var(--primary-dark);
        color: var(--bg-light);
        font-weight: 600;
        text-align: center;
    }
    .table-custom tbody td {
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Columnas con ancho definido */
    .table-custom th:nth-child(1),
    .table-custom td:nth-child(1) {
        width: 8%;
    }
    .table-custom th:nth-child(2),
    .table-custom td:nth-child(2) {
        width: 35%;
        text-align: left; /* Mejora lectura de nombres */
    }
    .table-custom th:nth-child(3),
    .table-custom td:nth-child(3) {
        width: 15%;
    }
    .table-custom th:nth-child(4),
    .table-custom td:nth-child(4) {
        width: 15%;
    }
    .table-custom th:nth-child(5),
    .table-custom td:nth-child(5) {
        width: 20%;
    }

    .table-custom tbody tr {
        transition: 0.3s;
    }
    .table-custom tbody tr:hover {
        background-color: #e6f0f8;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 34, 68, 0.15);
    }

    .btn-edit {
        background: var(--accent-grey);
        color: var(--primary-dark);
    }
    .btn-delete {
        background: var(--delete-color);
        color: white;
    }
</style>


<div class="container py-4">

    {{-- CREAR PRODUCTO --}}
    <h2 class="crud-title mb-4">Crear Producto</h2>

    <div class="form-container">
        <form action="{{ route('products.store') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_name" class="form-label fw-semibold">Nombre del Producto:</label>
                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="product_type" class="form-label fw-semibold">Tipo de Producto:</label>
                    <select name="product_type" id="product_type" class="form-select">
                        <option value="Panaderia">Panadería</option>
                        <option value="Desayunos">Desayunos</option>
                        <option value="Almuerzos">Almuerzos</option>
                        <option value="Bebidas">Bebidas</option>
                        <option value="Especiales">Especiales</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="unit_price" class="form-label fw-semibold">Precio unitario:</label>
                    <input type="number" name="unit_price" id="unit_price" class="form-control" step="100">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="product_status" class="form-label fw-semibold">Estado:</label>
                    <select name="product_status" id="product_status" class="form-select">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-custom-primary mt-3">
                <i class="fas fa-plus me-1"></i> Crear Producto
            </button>
        </form>
    </div>


    {{-- LISTADO POR CATEGORÍAS --}}
    <h2 class="crud-title mb-4 mt-5">Listado de Productos</h2>

    @php
        $categories = [
            'Panaderia' => 'Panadería',
            'Desayunos' => 'Desayunos',
            'Almuerzos' => 'Almuerzos',
            'Bebidas' => 'Bebidas',
            'Especiales' => 'Especiales',
            'Otros' => 'Otros',
        ];
    @endphp

    @foreach ($categories as $key => $label)
        <h4 class="mt-4 mb-2">{{ $label }}</h4>

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products->where('product_type', $key) as $product)
                        <tr>
                            <td>{{ $product->id }}</td>

                            <td><strong>{{ $product->product_name }}</strong></td>

                            <td>${{ number_format($product->unit_price, 0, ',', '.') }}</td>

                            <td>
                                @if($product->product_status == 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                        class="btn btn-sm btn-edit">
                                        Editar
                                    </a>

                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($products->where('product_type', $key)->count() === 0)
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No hay productos en esta categoría.
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    @endforeach

</div>

@endsection
