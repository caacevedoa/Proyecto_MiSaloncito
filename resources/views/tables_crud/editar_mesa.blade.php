@extends('layouts.app')

@section('content')

<div class="container p-4">

    <h1 class="mb-4">Editar Mesa</h1>

    <div class="card shadow-sm p-4">

        <form action="{{ route('tables.update', $table->id) }}" method="post">
            @method('PUT')
            @csrf

            <div class="mb-3">
                <label for="table_number" class="form-label">Número de mesa:</label>
                <input 
                    type="text" 
                    name="table_number" 
                    id="table_number"
                    value="{{ $table->table_number }}"
                    class="form-control"
                >
            </div>

            <div class="mb-3">
                <label for="table_status" class="form-label">Estado de la mesa:</label>
                <select name="table_status" id="table_status" class="form-select">
                    <option value="libre"     @if($table->table_status == 'libre') selected @endif>Libre</option>
                    <option value="ocupada"   @if($table->table_status == 'ocupada') selected @endif>Ocupada</option>
                    <option value="reservada" @if($table->table_status == 'reservada') selected @endif>Reservada</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Mesa</button>

            <a href="{{ route('tables.index') }}" class="btn btn-secondary ms-2">Volver</a>

        </form>

    </div>

</div>

@endsection
