<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class WaiterController extends Controller
    
{
    public function mode()
    {
        $tables = Table::with(['orders' => function($q){
            $q->where('status', 'pendiente');
        }])->get();

        return view('waiter.select_table', compact('tables'));
    }

    public function startOrder($table_id)
    {
        // Si ya hay una orden abierta, reutilizarla
        $order = Order::where('table_id', $table_id)
                    ->where('status', 'pendiente')
                    ->first();

        if (!$order) {
            $order = Order::create([
                'table_id' => $table_id,
                'order_datetime' => now(),
                'status' => 'pendiente',
                'user_id' => auth()->id()
            ]);
        }

        // AGRUPAR productos por categoría (product_type)
        $productsByType = Product::where('product_status', 'activo')
                                ->orderBy('product_type')
                                ->orderBy('product_name')
                                ->get()
                                ->groupBy('product_type');

        // detalles del pedido
        $details = OrderDetail::where('order_id', $order->id)->get();
        $total = $details->sum('subtotal');


        return view('waiter.manage_order', compact('order', 'details', 'productsByType', 'total'));
    }

    public function changeStatus($table_id, $nuevoEstado)
    {
        $table = Table::findOrFail($table_id);

        // Cambiar estado de la mesa
        $table->table_status = $nuevoEstado;
        $table->save();

        // Si hay una orden abierta, actualizarla
        $order = Order::where('table_id', $table_id)
                    ->where('status', 'pendiente')
                    ->first();

        if ($order) {
            if ($nuevoEstado === 'libre') {
                // Si la mesa queda libre, cerrar la orden
                $order->status = 'entregado';
            } else {
                // Si la mesa vuelve a ocuparse
                $order->status = 'pendiente';
            }

            $order->save();
        }

        return redirect()->back();
    }

    public function addProduct(Request $request, $order_id)
    {
        $product = Product::findOrFail($request->product_id);

        // Buscar si ya existe en el pedido
        $detail = OrderDetail::where('order_id', $order_id)
            ->where('product_id', $product->id)
            ->first();

        if ($detail) {
            $detail->quantity += 1;
            $detail->subtotal = $detail->quantity * $detail->unit_price;
            $detail->save();
        } else {
            OrderDetail::create([
                'order_id' => $order_id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $product->unit_price,
                'subtotal' => $product->unit_price,
                'comment' => ''
            ]);
        }

        return redirect()->back();
    }

    public function updateQuantity(Request $request, $detail_id)
    {
        $detail = OrderDetail::findOrFail($detail_id);
        $detail->quantity = $request->quantity;
        $detail->subtotal = $detail->quantity * $detail->unit_price;
        $detail->save();

        return redirect()->back();
    }

    public function deleteDetail($detail_id)
    {
        OrderDetail::findOrFail($detail_id)->delete();
        return redirect()->back();
    }

    public function updateComment(Request $request, $detail_id)
    {
        $detail = OrderDetail::findOrFail($detail_id);
        $detail->comment = $request->comment;
        $detail->save();

        return redirect()->back();
    }

    public function completeOrder($order_id)
    {
        $order = Order::findOrFail($order_id);

        // Cambiar estado
        $order->status = 'entregado';
        $order->save();

        // Vaciar los productos de la orden
        OrderDetail::where('order_id', $order_id)->delete();

        // Cambiar estado de la mesa
        $order->table->table_status = 'libre';
        $order->table->save();

        return redirect()->route('waiter.mode')->with('success', 'Pago completado');
    }


    

}