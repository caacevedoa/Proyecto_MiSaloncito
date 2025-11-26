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

// Ruta para la raíz del sitio
Route::get('/', function () {
    return redirect()->route('home');
});

// Resources
Route::resource('users', UserController::class);
Route::resource('tables', TableController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::resource('ordersdetail', OrderDetailController::class);
Route::resource('payments', PaymentController::class);
Route::resource('metrics', MetricController::class);

Route::get('payments_order/{id}', [PaymentController::class, 'pay'])->name('payments_order.pay');
Route::get('/factura/{id}', [PaymentController::class, 'invoice'])->name('payments.invoice');
Route::get('/factura/{id}/pdf', [ReceiptController::class, 'descargarFactura'])
    ->name('factura.pdf');

Route::post('orders/{order}/recalculate', 
    [App\Http\Controllers\OrderController::class, 'recalculate'])
    ->name('orders.recalculate');;

Auth::routes();
Route::post('metrics/{metric}/update-data', 
    [App\Http\Controllers\MetricController::class, 'updateMetric'])
    ->name('metrics.update_data');
Route::get('metrics/weekly', 
    [App\Http\Controllers\MetricController::class, 'weekly'])
    ->name('metrics.weekly');
Route::get('metrics/monthly', 
    [App\Http\Controllers\MetricController::class, 'monthly'])
    ->name('metrics.monthly');
    
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Modo Mesero
Route::get('/waiter', [WaiterController::class, 'mode'])->name('waiter.mode');
Route::get('/waiter/order/{table_id}', [WaiterController::class, 'startOrder'])->name('waiter.order');
Route::post('/waiter/order/{order_id}/add', [WaiterController::class, 'addProduct'])->name('waiter.addProduct');
Route::post('/detail/{detail}/quantity', [WaiterController::class, 'updateQuantity'])->name('waiter.updateQuantity');
Route::post('/detail/{detail}/comment', [WaiterController::class, 'updateComment'])->name('waiter.updateComment');
Route::delete('/detail/{detail}/delete', [WaiterController::class, 'deleteDetail'])->name('waiter.deleteDetail');
Route::get('/waiter/{order}/pay', [WaiterController::class, 'goPay'])->name('ver_crear_pagos');
Route::patch('/waiter/{id}/estado/{estado}', [WaiterController::class, 'changeStatus'])
    ->name('waiter.changeStatus');
Route::post('/orders/{order_id}/complete', [WaiterController::class, 'completeOrder'])->name('waiter.complete');

Route::patch('/tables/{id}/estado/{estado}', [TableController::class, 'cambiarEstado'])
    ->name('tables.cambiarEstado');