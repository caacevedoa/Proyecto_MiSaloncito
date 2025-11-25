<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     $payments = Payment::with('order')->get();
     $orders = Order::all();
     return view('payments_crud.ver_crear_pagos', compact('payments', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::all();
        return view('payments_crud.crear_pago', compact('orders'));
    }
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $payment = new Payment;
        $payment->payment_method = $request->payment_method;
        $payment->total_pay = $request->total_pay;
        $payment->payment_date = now();
        $payment->payment_status = $request->payment_status;
        $payment->order_id = $request->order_id;
        $payment->save();
        return redirect()->route('payments.index')->with('success', 'Pago realizado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $orders = Order::all();
        return view('payments_crud.editar_pago', compact('payment', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);
        
        $payment->payment_method = $request->payment_method;
        $payment->total_pay = $request->total_pay;
         $payment->payment_date = now();
        $payment->payment_status = $request->payment_status;
        $payment->order_id = $request->order_id;
        $payment->save();
        return redirect()->route('payments.index')->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Pago eliminado exitosamente.');
    }

    public function pay($id)
    {
        $order_detail = OrderDetail::where('order_id', $id)->get();
        $total = 0;
        foreach ($order_detail as $detail) {
            $total += $detail->subtotal;

        }
        $payments = Payment::all();
        $orders = Order::all();
        return view('payments_crud.ver_crear_pagos', compact('id', 'total', 'payments', 'orders'));

    }
    public function invoice($id)
    
{
    $order = Order::with('orderDetails.product')->findOrFail($id);
    $payment = Payment::where('order_id', $id)->first();

    // Calcular total
    $total = $order->orderDetails->sum('subtotal');

    return view('payments_crud.factura', compact('order', 'payment', 'total'));
}

}
