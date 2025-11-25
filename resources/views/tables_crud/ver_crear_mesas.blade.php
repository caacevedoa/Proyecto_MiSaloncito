@extends('layouts.app')

@section('content')

<div class="p-4">
    <div class="container">

        {{-- SECCIÓN CREAR (Se mantiene igual) --}}
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

        <hr> {{-- Separador visual --}}

        {{-- SECCIÓN LISTADO CON EDICIÓN EN LÍNEA --}}
        <h1 class="mb-3">Listado de Mesas</h1>

        <table class="table table-bordered table-striped align-middle"> {{-- align-middle centra verticalmente --}}
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Número de Mesa</th>
                    <th style="width: 35%;">Estado (Cambio Rápido)</th> {{-- Ancho ajustado para el select --}}
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tables as $table)
                    <tr>
                        <td>{{ $table->id }}</td>
                        
                        {{-- Muestra el número de mesa (si quieres editar esto, usas el botón amarillo de editar completo) --}}
                        <td>{{ $table->table_number }}</td>

                        {{-- AQUÍ ESTÁ EL CAMBIO: Formulario para editar estado --}}
                        <td>
                            <form action="{{ route('tables.update', $table->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PUT')

                                {{-- Importante: Si tu validación requiere table_number, enviamos el actual oculto --}}
                                <input type="hidden" name="table_number" value="{{ $table->table_number }}">

                                {{-- Selector de estado --}}
                                <select name="table_status" class="form-select form-select-sm">
                                    <option value="libre" {{ $table->table_status == 'libre' ? 'selected' : '' }}>
                                        Libre
                                    </option>
                                    <option value="ocupada" {{ $table->table_status == 'ocupada' ? 'selected' : '' }}>
                                        Ocupada
                                    </option>
                                    <option value="reservada" {{ $table->table_status == 'reservada' ? 'selected' : '' }}>
                                        Reservada
                                    </option>
                                </select>

                                {{-- Botón para guardar solo el estado --}}
                                <button type="submit" class="btn btn-success btn-sm" title="Actualizar Estado">
                                    Actualizar
                                </button>
                            </form>
                        </td>

                        {{-- Columna de Eliminar y Editar completo --}}
                        <td class="d-flex gap-2">
                            {{-- Botón Editar Completo (por si quieren cambiar el número de mesa) --}}
                            <a href="{{ route('tables.edit', $table->id) }}" class="btn btn-warning btn-sm">Editar</a>

                            {{-- Formulario Eliminar --}}
                            <form action="{{ route('tables.destroy', $table->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection