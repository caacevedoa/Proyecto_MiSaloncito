<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    {{-- Agregamos Font Awesome para los iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/sass/app.scss'])
</head>

<body>
    <div id="app">

        {{-- NAVBAR --}}
        <nav class="navbar navbar-expand-md navbar-dark" 
              style="background-color:#002244;">
            <div class="container">

                <a class="navbar-brand fw-bold text-white" href="{{ url('/') }}">
                    MiSaloncito
                </a>

                <button class="navbar-toggler" type="button" 
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    {{-- Este ul queda vacío para empujar todo a la derecha --}}
                    <ul class="navbar-nav me-auto"></ul>

                    <ul class="navbar-nav ms-auto">
                        
                        @auth
                            {{-- INICIO DEL MENÚ DE NAVEGACIÓN "NAVEGAR" --}}
                            <li class="nav-item dropdown me-3"> 
                                <a id="navbarDropdownNavegar" class="nav-link dropdown-toggle text-white fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bars me-1"></i> Navegar
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdownNavegar">
                                    
                                    {{-- Rutas Operativas --}}
                                    
                                    {{-- MODO MESERO --}}
                                    {{-- @role('administrador|gerente|mesero') --}} 
                                    <a class="dropdown-item fw-bold text-black" href="{{ route('waiter.mode') }}">
                                        <i class="fas fa-concierge-bell me-2"></i> MODO MESERO
                                    </a>
                                    {{-- @endrole --}}

                                    {{-- VISTA COCINA --}}
                                    {{-- @role('administrador|gerente|cocina') --}} 
                                    <a class="dropdown-item fw-bold text-black" href="{{ route('kitchen.index') }}">
                                        <i class="fas fa-utensils me-2"></i> VISTA COCINA
                                    </a>
                                    {{-- @endrole --}}

                                    <div class="dropdown-divider"></div>
                                    
                                    {{-- Rutas Administrativas --}}
                                    {{-- @role('administrador|gerente') --}}
                                        <h6 class="dropdown-header">Administración</h6>
                                        
                                        {{-- HOME --}}
                                        <a class="dropdown-item" href="{{ route('home') }}">
                                            <i class="fas fa-home me-2"></i> Home
                                        </a>
                                        
                                        {{-- MÉTRICAS --}}
                                        <a class="dropdown-item" href="{{ route('metrics.index') }}">
                                            <i class="fas fa-chart-bar me-2"></i> Métricas
                                        </a>
                                        
                                        {{-- PAGOS (NUEVO ENLACE) --}}
                                        <a class="dropdown-item" href="{{ route('payments.index') }}">
                                            <i class="fas fa-credit-card me-2"></i> Pagos
                                        </a>
                                        
                                        {{-- ÓRDENES --}}
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            <i class="fas fa-receipt me-2"></i> Órdenes
                                        </a>
                                        
                                        {{-- PRODUCTOS --}}
                                        <a class="dropdown-item" href="{{ route('products.index') }}">
                                            <i class="fas fa-hamburger me-2"></i> Productos
                                        </a>
                                        
                                        {{-- MESAS --}}
                                        <a class="dropdown-item" href="{{ route('tables.index') }}">
                                            <i class="fas fa-chair me-2"></i> Mesas
                                        </a>
                                        
                                        {{-- PERSONAL --}}
                                        <a class="dropdown-item" href="{{ route('users.index') }}">
                                            <i class="fas fa-user-friends me-2"></i> Personal
                                        </a>
                                    {{-- @endrole --}}
                                </div>
                            </li>
                            {{-- FIN DEL MENÚ DE NAVEGACIÓN "NAVEGAR" --}}
                        @endauth
                        
                        {{-- USER DROPDOWN --}}
                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">
                                    Iniciar Sesión
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white fw-semibold" 
                                   href="#" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Cerrar sesión
                                        </a>
                                    </li>
                                </ul>

                                <form id="logout-form" action="{{ route('logout') }}"
                                      method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>