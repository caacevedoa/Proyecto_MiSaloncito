@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #f4f4f4;
    }

    .top-title {
        background-color: #2c3e50;
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 35px;
        font-weight: bold;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .menu-container {
        max-width: 1000px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
    }

    .menu-card {
        background-color: white;
        width: 260px;
        height: 150px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        text-decoration: none;
        transition: 0.25s;
    }

    .menu-card:hover {
        transform: scale(1.06);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        background-color: #fafafa;
    }

    .footer {
        margin-top: 60px;
        padding: 22px;
        background-color: #2c3e50;
        color: white;
        text-align: center;
        font-size: 14px;
        border-radius: 6px;
    }
</style>

<div class="container">

    <div class="top-title">
        Panel Administrativo - MiSaloncito
    </div>

    <div class="menu-container">
        <a class="menu-card" href="{{ route('tables.index') }}">Mesas</a>
        <a class="menu-card" href="{{ route('products.index') }}">Productos</a>
        <a class="menu-card" href="{{ route('orders.index') }}">Órdenes</a>
        <a class="menu-card" href="{{ route('ordersdetail.index') }}">Detalles Orden</a>
        <a class="menu-card" href="{{ route('payments.index') }}">Pagos</a>
        <a class="menu-card" href="{{ route('users.index') }}">Usuarios</a>
        <a class="menu-card" href="{{ route('metrics.index') }}">Métricas</a> {{-- ÚLTIMA OPCIÓN --}}
    </div>

    <div class="footer">
        Sistema de Gestión MiSaloncito © {{ date("Y") }}
    </div>

</div>

@endsection
