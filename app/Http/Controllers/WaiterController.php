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
        $tables = Table::all();
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

        $products = Product::all();
        $details = OrderDetail::where('order_id', $order->id)->get();

        return view('waiter.manage_order', compact('order', 'products', 'details'));
    }

    public function changeStatus($id, $status)
    {
        $order = Order::find($id);
        $order->status = $status;
        $order->save();

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

    public function goToPayment($order_id)
    {
        return redirect()->route('payments_order.pay', $order_id);
    }

    public function changeTableStatus($table_id, $status)
    {
        $table = Table::findOrFail($table_id);
        $table->status = $status;
        $table->save();

        return redirect()->back();
    }

    
}
