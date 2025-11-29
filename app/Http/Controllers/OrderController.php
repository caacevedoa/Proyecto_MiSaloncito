<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Table;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $orders = Order::with('user', 'table')->get();
    $users = User::all();
    $tables = Table::all();
    return view('orders_crud.ver_crear_ordenes', compact('orders', 'users', 'tables'));
}

    public function create()
    {
        // Obtener datos para los selects
        $users = User::all();
        $tables = Table::all();
        return view('orders_crud.ver_crear_ordenes', compact('users', 'tables'));
    }

   
    public function store(Request $request)
    {
        $order = new Order;
        $order->order_datetime = now();
        $order->status = $request->status;
        // Foreign Keys
        $order->user_id = $request->user_id;
        $order->table_id = $request->table_id;
        $order->total = 0;
        $order->save();
        return redirect()->route('orders.index')
            ->with('success', 'Orden creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        // Datos para selects
        $users = User::all();
        $tables = Table::all();

        return view('orders_crud.editar_orden', compact('order', 'users', 'tables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $order->order_datetime = $order->order_datetime; 
        $order->status = $request->status;
        $order->user_id = $request->user_id;
        $order->table_id = $request->table_id;
        $order->save();
        return redirect()->route('orders.index')
            ->with('success', 'Orden actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Orden eliminada exitosamente.');
    }

        public function recalculate(Order $order)
    {
        $order = Order::with('details')->findOrFail($order->id);
        $totalcalculated = $order->details->sum(function($detail) {
            return $detail->quantity * $detail->unit_price;
        });
        $order->total = $totalcalculated;
        $order->save();
        return redirect()->back()->with('success', 
            'Total de la Orden #' . $order->id . ' actualizado correctamente.');
    }

    public function kitchenIndex()
{
    // Solo cargamos las órdenes que estén en estado 'pendiente'.
    $pendingOrders = Order::with(['user', 'table', 'details.product'])
                            ->where('status', 'pendiente') 
                            ->orderBy('order_datetime', 'asc') // Ordenamos por las más antiguas
                            ->get();

    // La vista se llamará 'kitchen.index'
    return view('kitchen.index', compact('pendingOrders'));
}

public function completeOrder(string $id)
{
    $order = Order::findOrFail($id);
    
    // Cambiamos el estado a 'entregado' según tu modelo de estados
    $order->status = 'entregado'; 
    $order->save();

    return redirect()->route('kitchen.index')->with('success', 
        'Orden #' . $order->id . ' marcada como lista para entregar.');
}

}
