@extends('layouts.app')

@section('content')

<div class="p-4">
    <div class="container">

        {{-- SECCIÓN CREAR --}}
        <h2 class="mb-4">Crear Mesa</h2>

        <form action="{{ route('tables.store') }}" method="post" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="table_number" class="form-label">Número de mesa:</label>
                <input type="text" name="table_number" id="table_number" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="table_status" class="form-label">Seleccione el estado de la mesa:</label>
                <select name="table_status" id="table_status" class="form-select">
                    <option value="libre">Libre</option>
                    <option value="ocupada">Ocupada</option>
                    <option value="reservada">Reservada</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Crear Mesa</button>
        </form>

        <hr>

        {{-- LISTADO --}}
        <h1 class="mb-3">Listado de Mesas</h1>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Número de Mesa</th>
                    <th>Estado Actual</th> {{-- ← NUEVA COLUMNA --}}
                    <th style="width: 35%;">Estado (Cambio Rápido)</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tables as $table)
                    <tr>
                        <td>{{ $table->id }}</td>
                        <td>{{ $table->table_number }}</td>

                        {{-- ESTADO ACTUAL --}}
                        <td>
                            @if($table->table_status == 'libre')
                                <span class="badge bg-success">Libre</span>
                            @elseif($table->table_status == 'ocupada')
                                <span class="badge bg-warning text-dark">Ocupada</span>
                            @elseif($table->table_status == 'reservada')
                                <span class="badge bg-info text-dark">Reservada</span>
                            @endif
                        </td>

                        {{-- BOTONES DE CAMBIO RÁPIDO --}}
                        <td>
                            <div class="d-flex gap-2">

                                <form action="{{ route('tables.cambiarEstado', ['id' => $table->id, 'estado' => 'libre']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success btn-sm">Libre</button>
                                </form>

                                <form action="{{ route('tables.cambiarEstado', ['id' => $table->id, 'estado' => 'ocupada']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-warning btn-sm">Ocupada</button>
                                </form>

                                <form action="{{ route('tables.cambiarEstado', ['id' => $table->id, 'estado' => 'reservada']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-info btn-sm">Reservada</button>
                                </form>

                            </div>
                        </td>

                        {{-- ACCIONES --}}
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('tables.edit', $table->id) }}" class="btn btn-secondary btn-sm">
                                    Editar
                                </a>

                                <form action="{{ route('tables.destroy', $table->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar esta mesa?')">
                                        Eliminar
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

@endsection