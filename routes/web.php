<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\WaiterController;

// ------------------------------------------------------
// RUTA PRINCIPAL
// ------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('home');
});

// ------------------------------------------------------
// AUTH
// ------------------------------------------------------
Auth::routes();

// ------------------------------------------------------
// HOME
// ------------------------------------------------------
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

// ------------------------------------------------------
// USERS CONTROLLER
// ------------------------------------------------------
Route::resource('users', UserController::class);

// ------------------------------------------------------
// TABLES CONTROLLER
// ------------------------------------------------------
Route::resource('tables', TableController::class);

Route::patch('/tables/{id}/estado/{estado}', 
    [TableController::class, 'cambiarEstado'])
    ->name('tables.cambiarEstado');

// ------------------------------------------------------
// PRODUCTS CONTROLLER
// ------------------------------------------------------
Route::resource('products', ProductController::class);

// ------------------------------------------------------
// ORDERS CONTROLLER
// ------------------------------------------------------
Route::resource('orders', OrderController::class);

Route::post('orders/{order}/recalculate', 
    [OrderController::class, 'recalculate'])
    ->name('orders.recalculate');

// RUTAS DE COCINA
Route::prefix('kitchen')->group(function () {
    Route::get('/', [OrderController::class, 'kitchenIndex'])
        ->name('kitchen.index');

    Route::patch('/{id}/complete', [OrderController::class, 'completeOrder'])
        ->name('kitchen.complete');

    Route::patch('/{id}/close', [OrderController::class, 'closeOrder'])
        ->name('kitchen.close');
});

// ------------------------------------------------------
// ORDER DETAIL CONTROLLER
// ------------------------------------------------------
Route::resource('ordersdetail', OrderDetailController::class);

// ------------------------------------------------------
// PAYMENT CONTROLLER
// ------------------------------------------------------
Route::resource('payments', PaymentController::class);

//Route::get('payments_order/{id}', [PaymentController::class, 'pay'])->name('payments_order.pay');

Route::get('/factura/{id}', [PaymentController::class, 'invoice'])
    ->name('payments.invoice');

Route::get('payments_order/{id}', 
    [PaymentController::class, 'pay'])
    ->middleware(['auth', 'role:mesero, administrador, gerente'])
    ->name('payments_order.pay');

// ------------------------------------------------------
// RECEIPT CONTROLLER
// ------------------------------------------------------
Route::get('/factura/{id}/pdf', [ReceiptController::class, 'descargarFactura'])
    ->name('factura.pdf');

// ------------------------------------------------------
// METRICS CONTROLLER
// ------------------------------------------------------
Route::resource('metrics', MetricController::class);

Route::post('metrics/{metric}/update-data', 
    [MetricController::class, 'updateMetric'])
    ->name('metrics.update_data');

Route::get('metrics/weekly', 
    [MetricController::class, 'weekly'])
    ->name('metrics.weekly');

Route::get('metrics/monthly', 
    [MetricController::class, 'monthly'])
    ->name('metrics.monthly');

// ------------------------------------------------------
// WAITER CONTROLLER
// ------------------------------------------------------
Route::get('/waiter', [WaiterController::class, 'mode'])
    ->name('waiter.mode');

Route::get('/waiter/status-json', 
    [WaiterController::class, 'getTablesStatusJson'])
    ->name('waiter.status-json');

Route::get('/waiter/order/{table_id}', 
    [WaiterController::class, 'startOrder'])
    ->name('waiter.order');

Route::post('/waiter/order/{order_id}/add', 
    [WaiterController::class, 'addProduct'])
    ->name('waiter.addProduct');

Route::post('/detail/{detail}/quantity', 
    [WaiterController::class, 'updateQuantity'])
    ->name('waiter.updateQuantity');

Route::post('/detail/{detail}/comment', 
    [WaiterController::class, 'updateComment'])
    ->name('waiter.updateComment');

Route::delete('/detail/{detail}/delete', 
    [WaiterController::class, 'deleteDetail'])
    ->name('waiter.deleteDetail');

//Route::get('/waiter/{order}/pay', [WaiterController::class, 'goPay'])->name('ver_crear_pagos');

Route::patch('/waiter/{id}/estado/{estado}', 
    [WaiterController::class, 'changeStatus'])
    ->name('waiter.changeStatus');

Route::post('/orders/{order_id}/complete', 
    [WaiterController::class, 'completeOrder'])
    ->name('waiter.complete');

Route::patch('/waiter/order/{order_id}/cancel', 
    [WaiterController::class, 'cancelOrder'])
    ->name('waiter.cancelOrder');

Route::get('/waiter/{order}/pay', 
    [WaiterController::class, 'goPay'])
    ->middleware(['auth', 'role:administrador'])
    ->name('ver_crear_pagos');
