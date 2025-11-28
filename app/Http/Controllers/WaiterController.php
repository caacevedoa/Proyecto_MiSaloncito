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
        // Buscar órdenes que aún estén activas (pendiente o entregado)
        $tables = Table::with(['orders' => function($q){
            $q->whereIn('status', ['pendiente', 'entregado']);
        }])->get();

        return view('waiter.select_table', compact('tables'));
    }

    /**
     * Devuelve el estado de las mesas en formato JSON para AJAX Polling.
     */
    public function getTablesStatusJson()
    {
        $tables = Table::with(['orders' => function($q){
            $q->whereIn('status', ['pendiente', 'entregado'])
              ->select('id', 'table_id', 'status'); // Limitar campos
        }])->get(['id', 'table_number', 'table_status']); // Limitar campos de la mesa

        // Formatear la respuesta JSON para que sea fácil de consumir por JS
        $formattedTables = $tables->map(function ($table) {
            $order = $table->orders->first();
            
            // Determinar el estado visual/clase para el JS
            $visual_status = $table->table_status;
            if ($order && $table->table_status !== 'reservada') {
                 // Si hay una orden activa, el estado visual debe reflejar la orden
                $visual_status = ($order->status == 'entregado') ? 'lista_para_cobrar' : 'ocupada';
            }
            
            return [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'class' => $visual_status, // Usaremos esto para cambiar la clase CSS
                'order_id' => $order ? $order->id : null,
                'order_status' => $order ? strtoupper($order->status) : 'LIBRE',
            ];
        });

        return response()->json(['tables' => $formattedTables]);
    }

    public function startOrder($table_id)
    {
        // Si ya hay una orden abierta (pendiente O entregado), reutilizarla
        $order = Order::where('table_id', $table_id)
                      ->whereIn('status', ['pendiente', 'entregado'])
                      ->first();

        if (!$order) {
            // Si no hay orden activa, creamos una nueva.
            $order = Order::create([
                'table_id' => $table_id,
                'order_datetime' => now(),
                'status' => 'pendiente',
                'user_id' => auth()->id()
            ]);
            
            // Asegurar que la mesa se marque como ocupada al crear la orden.
            $table = Table::findOrFail($table_id);
            if ($table->table_status !== 'ocupada') {
                $table->table_status = 'ocupada';
                $table->save();
            }
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
        
        // Evitar cantidades negativas o cero
        if ($request->quantity <= 0) {
            // Si la cantidad es cero o menos, se elimina el detalle
            $this->deleteDetail($detail_id);
            return redirect()->back();
        }

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
        
        // Redirige sin un mensaje de éxito para una experiencia fluida de "guardado automático"
        return redirect()->back();
    }

    public function completeOrder($order_id)
    {
        $order = Order::findOrFail($order_id);

        $order->status = 'cerrado'; 
        $order->save();

        $order->table->table_status = 'libre';
        $order->table->save();

        return redirect()->route('waiter.mode')->with('success', 'Orden #' . $order->id . ' cerrada y mesa liberada.');
    }

 public function cancelOrder(Request $request, $order_id)
    {
        // VALIDACIÓN: El motivo de cancelación es obligatorio
        $request->validate([
            'reason' => 'required|string|max:1000',
        ], [
            'reason.required' => 'El motivo de cancelación es obligatorio para cerrar la orden.',
        ]);

        $order = Order::findOrFail($order_id);
        
        $order->status = 'cancelado'; 
        // El campo ya no es opcional debido a la validación
        $order->cancellation_reason = $request->reason; 
        
        $order->save();

        // Liberar la mesa
        $order->table->table_status = 'libre';
        $order->table->save();

        return redirect()->route('waiter.mode')->with('success', 
            'Orden #' . $order->id . ' ha sido cancelada exitosamente y la mesa liberada.');
    }
}