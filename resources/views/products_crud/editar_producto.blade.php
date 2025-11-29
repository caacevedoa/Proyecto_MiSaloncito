@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')

<style>
    :root {
        --primary-dark: #002244;
        --accent-grey: #ADB5BD;
        --bg-light: #FFFFFF;
        --hover-darker-blue: #001a33;
        --shadow-dark: rgba(0, 34, 68, 0.6);
        --menu-card-bg-initial: rgba(173, 181, 189, 0.1);
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
        transform: translateY(-2px);
        box-shadow: 0 8px 20px var(--shadow-dark);
    }

    .btn-secondary-custom {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .form-control, .form-select {
        border-color: var(--accent-grey);
        box-shadow: none !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-dark);
    }
</style>


<div class="container py-4">

    <a href="{{ route('products.index') }}" class="btn btn-secondary-custom mb-3">
        ← Volver al listado
    </a>

    <h2 class="crud-title mb-4">Editar Producto</h2>

    <div class="form-container">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" for="product_name">Nombre del Producto:</label>
                    <input
                        type="text"
                        id="product_name"
                        name="product_name"
                        value="{{ $product->product_name }}"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" for="product_type">Tipo de Producto:</label>
                    <select id="product_type" name="product_type" class="form-select">
                        <option value="Panaderia" {{ $product->product_type == 'Panaderia' ? 'selected' : '' }}>Panadería</option>
                        <option value="Desayunos" {{ $product->product_type == 'Desayunos' ? 'selected' : '' }}>Desayunos</option>
                        <option value="Almuerzos" {{ $product->product_type == 'Almuerzos' ? 'selected' : '' }}>Almuerzos</option>
                        <option value="Bebidas" {{ $product->product_type == 'Bebidas' ? 'selected' : '' }}>Bebidas</option>
                        <option value="Especiales" {{ $product->product_type == 'Especiales' ? 'selected' : '' }}>Especiales</option>
                        <option value="Otros" {{ $product->product_type == 'Otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" for="unit_price">Precio Unitario:</label>
                    <input
                        type="number"
                        id="unit_price"
                        name="unit_price"
                        value="{{ $product->unit_price }}"
                        class="form-control"
                        
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" for="product_status">Estado del Producto:</label>
                    <select id="product_status" name="product_status" class="form-select">
                        <option value="Activo" {{ $product->product_status == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ $product->product_status == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

            </div>

            <button type="submit" class="btn btn-custom-primary mt-2">
                Guardar Cambios
            </button>

        </form>
    </div>
</div>

@endsection
