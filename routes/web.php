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
Route::patch('/tables/{id}/estado/{estado}', [TableController::class, 'cambiarEstado'])
    ->name('tables.cambiarEstado');
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
