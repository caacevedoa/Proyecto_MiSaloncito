@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.0">
    <title>Gestión de Usuarios</title>
    <style>
        /* ---------------------------------------------------------------------- */
        /* ESTILOS GENERALES (TEMA AZUL OSCURO) */
        /* ---------------------------------------------------------------------- */
        :root {
            --primary-dark: #002244;
            --accent-grey: #ADB5BD;
            --bg-light: #FFFFFF;
            --hover-darker-blue: #001a33;
            --shadow-dark: rgba(0, 34, 68, 0.6);
            --color-success: #28a745;
            --color-danger: #dc3545;
            --color-warning: #ffc107;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--primary-dark);
            margin: 20px;
        }

        h1 {
            color: var(--primary-dark);
            border-bottom: 3px solid var(--accent-grey);
            padding-bottom: 5px;
            margin-bottom: 20px;
            margin-top: 40px;
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DEL FORMULARIO */
        /* ---------------------------------------------------------------------- */
        form.main-form {
            max-width: 600px; 
            padding: 30px; 
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-group { margin-bottom: 20px; }

        form label { display: block; margin-bottom: 5px; font-weight: 600; }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form select {
            width: 100%; padding: 10px; border: 1px solid var(--accent-grey);
            border-radius: 6px; box-sizing: border-box; color: var(--primary-dark);
            background-color: var(--bg-light); font-size: 1rem;
        }

        button.btn-primary {
            background-color: var(--primary-dark); color: var(--bg-light); 
            transition: all 0.3s ease; box-shadow: 0 4px 10px var(--shadow-dark); 
            border: none; padding: 12px 25px; text-transform: uppercase; font-weight: 700;
            border-radius: 8px; cursor: pointer; width: 100%; margin-top: 10px;
        }

        button.btn-primary:hover {
            background-color: var(--hover-darker-blue); transform: translateY(-2px);
        }

        /* ---------------------------------------------------------------------- */
        /* BOTONES DE FILTRO (NUEVO ESTILO) */
        /* ---------------------------------------------------------------------- */
        .filter-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid var(--primary-dark);
            background-color: white;
            color: var(--primary-dark);
            cursor: pointer;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-btn:hover {
            background-color: #e9ecef;
        }

        /* Clase activa para el botón seleccionado */
        .filter-btn.active {
            background-color: var(--primary-dark);
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* ---------------------------------------------------------------------- */
        /* ESTILOS DE LA TABLA */
        /* ---------------------------------------------------------------------- */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; vertical-align: middle; }
        th { background-color: var(--primary-dark); color: var(--bg-light); font-weight: 700; }
        tbody tr:nth-child(even) { background-color: #f8f9fa; }
        tbody tr:hover { background-color: rgba(173, 181, 189, 0.2); }

        .action-link { display: inline-block; padding: 5px 10px; border-radius: 4px; font-size: 0.85em; text-decoration: none; font-weight: 600; text-align: center; border: none; cursor: pointer; margin-right: 5px; }
        .action-link.edit { background-color: var(--color-warning); color: var(--primary-dark); }
        .delete-form { display: inline-block; }
        .delete-btn { background: none; border: none; cursor: pointer; color: var(--color-danger); font-weight: 700; text-decoration: underline; padding: 5px 10px; font-size: 0.9em; }
        .delete-btn:hover { color: #bd2130; }

        /* Clase utilitaria para ocultar filas */
        .hidden-row { display: none; }

    </style>
</head>
<body>

    <h1>Crear Usuario</h1>

    <form action="{{route('users.store')}}" method="post" class="main-form">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="role">Seleccione el rol:</label>
            <select name="role" id="role">
                <option value="administrador">Administrador</option>
                <option value="mesero">Mesero</option>
                <option value="gerencia">Gerencia</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">Crear Usuario</button>
    </form>

    <h1>Listado de usuarios</h1>

    <div class="filter-container">
        <button class="filter-btn active" onclick="filterTable('all', this)">Todos</button>
        <button class="filter-btn" onclick="filterTable('administrador', this)">Administradores</button>
        <button class="filter-btn" onclick="filterTable('mesero', this)">Meseros</button>
        <button class="filter-btn" onclick="filterTable('gerencia', this)">Gerencia</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="users-table-body">
            @foreach ($users as $user)
                {{-- Agregamos data-role para identificar el rol en JS --}}
                <tr class="user-row" data-role="{{ strtolower($user->role) }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="action-link edit">Editar</a>
                        
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function filterTable(role, btn) {
            // 1. Manejo visual de botones (clase active)
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // 2. Filtrado de filas
            const rows = document.querySelectorAll('.user-row');
            
            rows.forEach(row => {
                const rowRole = row.getAttribute('data-role');
                
                if (role === 'all') {
                    row.classList.remove('hidden-row');
                } else {
                    if (rowRole === role) {
                        row.classList.remove('hidden-row');
                    } else {
                        row.classList.add('hidden-row');
                    }
                }
            });
        }
    </script>
    
</body>
</html>

@endsection