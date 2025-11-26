@extends('layouts.app')

@section('content')

<style>
    /* DEFINICIÓN DE VARIABLES PARA LA PALETA MONOCROMÁTICA */
    :root {
        --primary-dark: #002244;
        --accent-grey: #ADB5BD;
        --bg-light: #FFFFFF;
        --hover-darker-blue: #001a33;
        --menu-card-bg-hover: rgba(173, 181, 189, 0.1);
        /* Nueva variable para la sombra del botón operativo */
        --shadow-dark: rgba(0, 34, 68, 0.6);
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--primary-dark);
    }

    /* Estilos Customizados que Bootstrap no provee directamente */

    /* Header */
    .header-bar {
        border-bottom: 5px solid var(--accent-grey);
    }

    /* Botón Modo Mesero */
    .waiter-mode-btn {
        background-color: var(--primary-dark);
        color: var(--bg-light) !important; 
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px var(--shadow-dark); /* Sombra intensa */
        border: none;
        text-transform: uppercase;
        font-weight: 700;
    }

    .waiter-mode-btn:hover {
        background-color: var(--hover-darker-blue);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px var(--shadow-dark); /* Sombra más fuerte en hover */
        color: var(--bg-light) !important;
    }

    /* Título Administrativo */
    .admin-title {
        color: var(--primary-dark);
        border-bottom: 3px solid var(--accent-grey);
        display: inline-block;
    }

    /* Tarjetas de Módulos */
    .menu-card {
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        border-left: 5px solid transparent; 
        min-height: 120px;
        color: var(--primary-dark);
        background-color: var(--menu-card-bg-hover); 
        font-size: 1.15rem; 
        
        /* APLICACIÓN DEL EFECTO DE RESALTADO INICIAL (PROPORCIONAL) */
        box-shadow: 0 2px 8px rgba(0, 34, 68, 0.2); 
    }

    .menu-card:hover {
        /* APLICACIÓN DEL EFECTO DE RESALTADO GRANDE (PROPORCIONAL) */
        transform: translateY(-5px); /* Movimiento un poco más sutil que el botón principal */
        box-shadow: 0 12px 25px var(--shadow-dark); /* Sombra profunda y marcada como la del botón */
        
        border-left: 5px solid var(--primary-dark);
        color: var(--primary-dark);
        background-color: rgba(173, 181, 189, 0.2);
    }

    .card-icon {
        color: var(--accent-grey);
        font-size: 2.5rem;
        transition: color 0.3s ease;
    }
    
    .menu-card:hover .card-icon {
        color: var(--primary-dark);
    }

    /* Footer */
    .footer {
        background-color: var(--primary-dark);
        border-top: 3px solid var(--accent-grey);
        color: var(--bg-light);
    }
</style>

<div class="container py-4 py-lg-5">

    <header class="header-bar text-white text-center p-4 mb-5 rounded shadow" style="background-color: var(--primary-dark) !important;">
        <h1 class="display-5 fw-bold mb-0">Mi Saloncito <span class="fw-bolder">Gestión Centralizada</span></h1>
    </header>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="bg-light p-4 p-md-5 rounded-3 shadow-lg text-center border">
                <h2 class="mb-4 fw-light text-secondary">Área Operativa</h2>
                <a class="btn waiter-mode-btn btn-lg w-100 w-sm-75" href="{{ route('waiter.mode') }}">
                    <i class="fas fa-concierge-bell me-2"></i> ACTIVAR MODO MESERO
                </a>
            </div>
        </div>
    </div>

    <hr class="my-5" style="border-top: 2px dashed var(--accent-grey);">

    <div class="text-center mb-4">
        <h2 class="admin-title fs-2 pb-2 px-3 fw-bold text-uppercase">Módulos Administrativos</h2>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        
        <div class="col">
            <a class="card menu-card h-100 d-flex flex-column align-items-center justify-content-center" href="{{ route('tables.index') }}">
                <i class="fas fa-chair card-icon mb-2"></i>
                <span class="fw-semibold">Mesas</span>
            </a>
        </div>

        <div class="col">
            <a class="card menu-card h-100 d-flex flex-column align-items-center justify-content-center" href="{{ route('products.index') }}">
                <i class="fas fa-hamburger card-icon mb-2"></i>
                <span class="fw-semibold">Productos / Menú</span>
            </a>
        </div>

        <div class="col">
            <a class="card menu-card h-100 d-flex flex-column align-items-center justify-content-center" href="{{ route('orders.index') }}">
                <i class="fas fa-receipt card-icon mb-2"></i>
                <span class="fw-semibold">Órdenes Pendientes</span>
            </a>
        </div>

        <div class="col">
            <a class="card menu-card h-100 d-flex flex-column align-items-center justify-content-center" href="{{ route('ordersdetail.index') }}">
                <i class="fas fa-clipboard-list card-icon mb-2"></i>
                <span class="fw-semibold">Detalles Orden</span>
            </a>
        </div>
        
        <div class="col">
            <a class="card menu-card h-100 d-flex flex-column align-items-center justify-content-center" href="{{ route('payments.index') }}">
                <i class="fas fa-credit-card card-icon mb-2"></i>
                <span class="fw-semibold">Pagos / Cajas</span>
            </a>
        </div>

        <div class="col">
            <a class="card menu-card h-100 d-flex flex-column align-items-center justify-content-center" href="{{ route('users.index') }}">
                <i class="fas fa-user-friends card-icon mb-2"></i>
                <span class="fw-semibold">Gestión de Personal</span>
            </a>
        </div>
        
        <div class="col">
            <a class="card menu-card h-100 d-flex flex-column align-items-center justify-content-center" href="{{ route('metrics.index') }}">
                <i class="fas fa-chart-bar card-icon mb-2"></i>
                <span class="fw-semibold">Métricas y Reportes</span>
            </a>
        </div>

    </div>

    <footer class="footer text-center p-3 mt-5 shadow-lg">
        Sistema de Gestión MiSaloncito &bull; Consola Administrativa &copy; {{ date("Y") }}
    </footer>

</div>

@endsection