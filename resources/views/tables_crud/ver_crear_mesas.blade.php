@extends('layouts.app')

@section('title', 'Mesas - MiSaloncito')
@section('content')

<style>
    /* DEFINICIÓN DE VARIABLES PARA LA PALETA MONOCROMÁTICA */
    :root {
        --primary-dark: #002244;
        --accent-grey: #ADB5BD;
        --bg-light: #FFFFFF;
        --hover-darker-blue: #001a33;
        --shadow-dark: rgba(0, 34, 68, 0.6);
        --menu-card-bg-initial: rgba(173, 181, 189, 0.1);
        --delete-color: #495057; /* Gris Oscuro para Eliminar, sin usar rojo */
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--primary-dark);
    }

    /* TÍTULOS Y SEPARADOR */
    .crud-title {
        color: var(--primary-dark);
        border-left: 5px solid var(--accent-grey);
        padding-left: 15px;
        font-weight: 600;
    }

    /* ESTILO PARA CONTENEDOR DE FORMULARIO (Como una Tarjeta) */
    .form-container {
        background-color: var(--menu-card-bg-initial);
        border: 1px solid #e9ecef;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 34, 68, 0.1);
        margin-bottom: 40px;
    }

    /* ESTILO DE BOTÓN PRINCIPAL (Crear Mesa) */
    .btn-custom-primary {
        background-color: var(--primary-dark);
        color: var(--bg-light);
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 34, 68, 0.4);
    }

    .btn-custom-primary:hover {
        background-color: var(--hover-darker-blue);
        color: var(--bg-light);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px var(--shadow-dark);
    }
    
    /* INPUTS Y SELECTS */
    .form-control, .form-select {
        border-color: var(--accent-grey);
        box-shadow: none !important;
        transition: border-color 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-dark);
    }

    /* TABLA ESTILO */
    .table-responsive-custom {
        overflow-x: auto;
    }
    
    .table-custom {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--accent-grey);
    }

    /* CABECERA DE TABLA */
    .table-custom thead th {
        background-color: var(--primary-dark);
        color: var(--bg-light);
        border-bottom: 2px solid var(--accent-grey);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* EFECTO DINÁMICO DE FILAS */
    .table-custom tbody tr {
        transition: all 0.3s ease;
        cursor: pointer;
        /* Sombra inicial sutil, como las tarjetas admin */
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); 
    }

    .table-custom tbody tr:nth-child(even) {
        background-color: #f9f9f9; /* Ligeramente diferente para las rayas */
    }
    
    .table-custom tbody tr:hover {
        background-color: #e6f0f8; /* Fondo azul muy claro en hover */
        transform: translateY(-3px); /* Efecto de levantamiento */
        box-shadow: 0 8px 20px rgba(0, 34, 68, 0.15); /* Sombra intensa proporcional */
    }

    /* ESTADO DE LAS MESAS (EXCEPCIÓN - MANTENIENDO SEMÁNTICA) */
    .badge {
        padding: 0.6em 1em;
        font-size: 0.85em;
        font-weight: 700;
        border-radius: 50rem;
    }

    /* ESTILO DE BOTONES DE ACCIÓN */
    .btn-action {
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    
    /* EDITAR */
    .btn-edit {
        background-color: var(--accent-grey);
        color: var(--primary-dark);
        border-color: var(--accent-grey);
    }
    .btn-edit:hover {
        background-color: #90989f;
        border-color: #90989f;
        transform: translateY(-1px);
    }

    /* ELIMINAR (SIN ROJO) */
    .btn-delete {
        background-color: var(--delete-color); /* Gris Oscuro */
        color: var(--bg-light);
        border-color: var(--delete-color);
    }
    .btn-delete:hover {
        background-color: #3e4449; /* Más oscuro en hover */
        border-color: #3e4449;
        transform: translateY(-1px);
    }
</style>

<div class="p-4">
    <div class="container">

        {{-- SECCIÓN CREAR --}}
        <h2 class="crud-title mb-4">Crear Nueva Mesa</h2>

        <div class="form-container">
            <form action="{{ route('tables.store') }}" method="post" class="mb-3">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="table_number" class="form-label fw-semibold">Número de Mesa:</label>
                        <input type="text" name="table_number" id="table_number" class="form-control" placeholder="Ej: 01, Terraza A" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="table_status" class="form-label fw-semibold">Estado Inicial:</label>
                        <select name="table_status" id="table_status" class="form-select">
                            <option value="libre">Libre</option>
                            <option value="ocupada">Ocupada</option>
                            <option value="reservada">Reservada</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-custom-primary mt-3">
                    <i class="fas fa-plus me-1"></i> Registrar Mesa
                </button>
            </form>
        </div>

        <hr class="my-5 border-secondary opacity-25">

        {{-- LISTADO --}}
        <h1 class="crud-title mb-4">Inventario de Mesas Registradas</h1>

        <div class="table-responsive-custom">
            <table class="table table-custom table-striped align-middle">
                <thead>
                    <tr>
                        <th>Número de Mesa</th>
                        <th>Estado Actual</th>
                        <th style="width: 200px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tables as $table)
                        <tr>
                            <td>
                                <i class="fas fa-utensils me-2 text-muted"></i> 
                                <span class="fw-bold">{{ $table->table_number }}</span>
                            </td>

                            {{-- ESTADO ACTUAL (Colores Semánticos) --}}
                            <td>
                                @if($table->table_status == 'libre')
                                    <span class="badge bg-success">Libre</span>
                                @elseif($table->table_status == 'ocupada')
                                    <span class="badge bg-warning text-dark">Ocupada</span>
                                @elseif($table->table_status == 'reservada')
                                    {{-- Usando bg-info por defecto de Bootstrap --}}
                                    <span class="badge bg-info text-dark">Reservada</span>
                                @endif
                            </td>

                            {{-- ACCIONES --}}
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('tables.edit', $table->id) }}" class="btn btn-action btn-edit btn-sm">
                                        <i class="fas fa-pen"></i> Editar
                                    </a>

                                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete btn-sm"
                                            onclick="return confirm('¿Estás seguro de eliminar la mesa {{ $table->table_number }}?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection