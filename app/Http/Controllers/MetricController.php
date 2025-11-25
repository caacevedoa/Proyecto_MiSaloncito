<?php

namespace App\Http\Controllers;

use App\Models\Metric;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderDetail; // Asegúrate de tener este modelo o DB

class MetricController extends Controller
{
    /**
     * Lógica reutilizable para calcular y guardar métricas para una fecha dada.
     * @param string $date 'YYYY-MM-DD'
     * @param \App\Models\Metric|null $metric Si se proporciona, actualiza el registro existente.
     * @return \App\Models\Metric
     */
    private function calculateAndStoreMetrics(string $date, ?Metric $metric = null): Metric
    {
        $ordersToday = Order::whereDate('order_datetime', $date)->get();
        
        if ($ordersToday->isEmpty() && $metric === null) {
            throw new \Exception('No hubo órdenes en la fecha ' . $date . ' para crear una métrica.');
        }

        // Inicializar variables
        $totalSales = $ordersToday->sum('total');
        $totalOrders = $ordersToday->count();
        $mostActiveUserId = null;
        $mostActiveUserName = 'N/A';
        $bestProductName = 'N/A';
        $lastOrder = null;
        $lastPayment = null;


        if ($totalOrders > 0) {
            // 2. Obtener el Usuario más activo (el que hizo más órdenes ese día)
            $mostActiveOrder = Order::whereDate('order_datetime', $date)
                ->select('user_id', DB::raw('count(*) as total'))
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->first();
            
            $mostActiveUserId = $mostActiveOrder->user_id;
            $mostActiveUserName = User::find($mostActiveUserId)->name ?? 'Desconocido';

            // 3. Obtener el Producto más vendido (Corregido el nombre de la tabla)
            $bestSellingProduct = DB::table('order_details') // <--- CORRECCIÓN 1: Nombre de la tabla
                ->join('orders', 'orders.id', '=', 'order_details.order_id') // <--- CORRECCIÓN 2: En el join
                ->whereDate('orders.order_datetime', $date)
                ->select('product_id', DB::raw('SUM(order_details.quantity) as total_qty')) // <--- CORRECCIÓN 3: En la columna
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->first();

            if ($bestSellingProduct) {
                $bestProductName = DB::table('products')->where('id', $bestSellingProduct->product_id)->value('product_name') ?? 'N/A';
            }

            // 4. Resolver IDs foráneos (Para integridad referencial)
            $lastOrder = Order::whereDate('order_datetime', $date)->latest('id')->first();
            // Asumo que el pago está relacionado con la orden
            $lastPayment = Payment::where('order_id', $lastOrder->id)->first(); 

            // Fallbacks si no se encuentra un FK (deberías tener una lógica robusta o permitir nulls)
            if (!$lastPayment) { $lastPayment = Payment::first(); }
        }

        $data = [
            'record_date'             => $date,
            'total_sales_date'        => $totalSales,
            'total_orders'            => $totalOrders,
            'best_selling_product_id' => $bestProductName,
            'most_active_user_id'     => $mostActiveUserName,
            
            // FKs (Usamos IDs seguros o nulos si están permitidos)
            'user_id'  => $mostActiveUserId ?? User::first()->id ?? 1, 
            'order_id' => $lastOrder->id ?? Order::first()->id ?? 1,
            'pay_id'   => $lastPayment->id ?? Payment::first()->id ?? 1,
        ];
        
        // Si existe una métrica, la actualiza; si no, la crea.
        if ($metric) {
            $metric->update($data);
            return $metric;
        } else {
            return Metric::create($data);
        }
    }

    /**
     * Mostrar lista + formulario crear
     */
    public function index()
    {
        // --- 1. MÉTRICAS DIARIAS (CRUD original) ---
        $metrics = Metric::orderBy('record_date', 'desc')->get();
        
        // --- 2. MÉTRICAS SEMANALES ---
        $weeklyMetrics = DB::table('metrics')
            ->select(
                DB::raw('WEEKOFYEAR(record_date) as period'),
                DB::raw('YEAR(record_date) as year'),
                DB::raw('SUM(total_sales_date) as total_weekly_sales'),
                DB::raw('SUM(total_orders) as total_weekly_orders'),
            )
            ->groupBy('year', 'period')
            ->orderBy('year', 'desc')
            ->orderBy('period', 'desc')
            ->get();

        // --- 3. MÉTRICAS MENSUALES ---
        $monthlyMetrics = DB::table('metrics')
            ->select(
                DB::raw('MONTH(record_date) as month_num'),
                DB::raw('MONTHNAME(record_date) as period'),
                DB::raw('YEAR(record_date) as year'),
                DB::raw('SUM(total_sales_date) as total_monthly_sales'),
                DB::raw('SUM(total_orders) as total_monthly_orders'),
            )
            ->groupBy('year', 'month_num', 'period')
            ->orderBy('year', 'desc')
            ->orderBy('month_num', 'desc')
            ->get();

        // Pasar todas las colecciones a la misma vista
        return view('metrics_crud.ver_crear_metricas', compact('metrics', 'weeklyMetrics', 'monthlyMetrics'));
    }

    /**
     * Guardar una nueva métrica (Usa el método reutilizable)
     */
    public function store(Request $request)
    {
        $request->validate([
            'record_date' => 'required|date|unique:metrics,record_date',
        ]);
        
        try {
            $this->calculateAndStoreMetrics($request->record_date);
            return redirect()->route('metrics.index')->with('success', 'Métricas del día ' . $request->record_date . ' generadas correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
    }

    /**
     * NUEVO: Recalcular y actualizar una métrica existente
     */
    public function updateMetric(Metric $metric)
    {
        try {
            // El record_date se obtiene del modelo que inyecta Laravel
            $date = $metric->record_date;
            
            $this->calculateAndStoreMetrics($date, $metric);
            
            return redirect()->route('metrics.index')->with('success', 'Métrica del día ' . $date . ' actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    // ... (Mantener el resto de métodos: edit, update, destroy)
    public function destroy($id)
    {
        Metric::destroy($id);
        return redirect()->route('metrics.index')->with('success', 'Métrica eliminada');
    }
   
}