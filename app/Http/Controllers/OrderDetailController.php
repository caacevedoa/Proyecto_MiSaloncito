<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource + create form.
     */
        public function index()
    {
        // 1. Obtener los detalles y precargar las relaciones (para evitar N+1)
        $details = OrderDetail::with(['order.table', 'product'])
                            ->orderBy('order_id', 'desc') // <-- APLICAMOS ORDEN DESCENDENTE POR order_id
                            ->get();
        
        // 2. Obtener las órdenes y productos para el formulario de creación
        $orders = Order::all();
        $products = Product::all();
        
        // 3. Pasar a la vista
        return view('ordersdetail_crud.ver_crear_detallesorden', compact('details', 'orders', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // ✅ Validación (se conserva la de tu compañero)
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // ✅ Obtener producto con precio correcto
    $product = Product::findOrFail($request->product_id);

    // Determinar precio unitario (tu compañero usa unit_price, tú price)
    $unit_price = $product->unit_price ?? $product->price;

    // Calcular subtotal
    $subtotal = $unit_price * $request->quantity;

    // ✅ Crear el detalle de orden
    $orderDetail = OrderDetail::create([
        'order_id'   => $request->order_id,
        'product_id' => $product->id,
        'quantity'   => $request->quantity,
        'unit_price' => $unit_price,
        'subtotal'   => $subtotal,
    ]);

    // ✅ Recalcular total de la orden
    $order = Order::find($request->order_id);
    if (method_exists($order, 'calculateTotal')) {
        $order->calculateTotal();
    }

    // Redirigir
    return redirect()
        ->route('ordersdetail.index')
        ->with('success', 'Producto agregado y total actualizado correctamente');
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
        {
            $orderDetail = OrderDetail::findOrFail($id);
            $products = Product::all();

        return view('ordersdetail_crud.editar_detalleorden', compact('orderDetail', 'products'));
        }


        /**
         * Update the specified resource in storage.
         */
    public function update(Request $request, $id)
    {
        // Validar
        $validated = $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        // Encontrar detalle
        $orderDetail = OrderDetail::findOrFail($id);

        // Obtener el producto
        $product = Product::findOrFail($request->product_id);

        // Asignar datos
        $orderDetail->order_id   = $request->order_id;
        $orderDetail->product_id = $request->product_id;
        $orderDetail->quantity   = $request->quantity;

        // ASIGNAR EL PRECIO AUTOMÁTICAMENTE
        $orderDetail->unit_price = $product->unit_price;

        // Calcular subtotal
        $orderDetail->subtotal = $product->unit_price * $request->quantity;

        // Guardar
        $orderDetail->save();

        return redirect()->route('ordersdetail.index')
                        ->with('success', 'Detalle actualizado correctamente');
    }


    public function destroy(string $id)
    {
        $detail = OrderDetail::findOrFail($id);
        $detail->delete();

        return redirect()->route('ordersdetail.index')
            ->with('success', 'Detalle eliminado exitosamente.');
    }
}
