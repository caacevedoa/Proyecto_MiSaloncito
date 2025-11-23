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
        $details = OrderDetail::with('order', 'product')->get();
        $orders = Order::all();
        $products = Product::all();

        return view('ordersdetail_crud.ver_crear_detallesorden', compact('details', 'orders', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // Validación
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // Obtener producto
    $product = Product::find($request->product_id);

    // Crear detalle
    $orderDetail = new OrderDetail();
    $orderDetail->order_id = $request->order_id;
    $orderDetail->product_id = $product->id;
    $orderDetail->quantity = $request->quantity;
    $orderDetail->unit_price = $product->unit_price;
    $orderDetail->subtotal = $product->unit_price * $request->quantity;

    $orderDetail->save();

    return redirect()->route('ordersdetail.index', $request->order_id)
                     ->with('success', 'Producto agregado correctamente');
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




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = OrderDetail::findOrFail($id);
        $detail->delete();

        return redirect()->route('ordersdetail.index')
            ->with('success', 'Detalle eliminado exitosamente.');
    }
}
