<?php

namespace App\Http\Controllers;

use App\Models\Metric;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class MetricController extends Controller
{
    /**
     * Mostrar lista + formulario crear
     */
    public function index()
    {
        $metrics = Metric::with(['payment', 'user', 'order'])->get();
        $payments = Payment::all();
        $users = User::all();
        $orders = Order::all();

        return view('metrics_crud.ver_crear_metricas', compact('metrics', 'payments', 'users', 'orders'));
    }

    /**
     * Guardar una nueva métrica
     */
    public function store(Request $request)
    {
        $request->validate([
            'record_date' => 'required|date',
            'total_sales_date' => 'required|numeric',
            'total_orders' => 'required|integer',
            'best_selling_product_id' => 'required|string',
            'most_active_user_id' => 'required|string',
            'pay_id' => 'required|exists:payments,id',
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
        ]);

        Metric::create($request->all());

        return redirect()->route('metrics.index')->with('success', 'Métrica creada correctamente');
    }

    /**
     * Editar una métrica
     */
    public function edit($id)
    {
        $metric = Metric::findOrFail($id);
        $payments = Payment::all();
        $users = User::all();
        $orders = Order::all();

        return view('metrics_crud.editar_metrica', compact('metric', 'payments', 'users', 'orders'));
    }

    /**
     * Actualizar métrica
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'record_date' => 'required|date',
            'total_sales_date' => 'required|numeric',
            'total_orders' => 'required|integer',
            'best_selling_product_id' => 'required|string',
            'most_active_user_id' => 'required|string',
            'pay_id' => 'required|exists:payments,id',
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
        ]);

        $metric = Metric::findOrFail($id);
        $metric->update($request->all());

        return redirect()->route('metrics.index')->with('success', 'Métrica actualizada correctamente');
    }

    /**
     * Eliminar
     */
    public function destroy($id)
    {
        Metric::destroy($id);

        return redirect()->route('metrics.index')->with('success', 'Métrica eliminada');
    }
}
