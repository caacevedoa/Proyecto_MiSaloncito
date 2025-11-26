<?php

namespace App\Http\Controllers;

use App\Models\Metric;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MetricController extends Controller
{
    /**
     * Función auxiliar para calcular estadísticas complejas (Tops) en un rango de fechas.
     */
    private function getStatsForPeriod($startDate, $endDate)
    {
        // 1. TOP MESERO (Por dinero vendido y cantidad de órdenes)
        $topWaiter = Order::whereBetween('order_datetime', [$startDate, $endDate])
            ->where('status', '!=', 'cancelado')
            ->select('user_id', DB::raw('SUM(total) as total_sold'), DB::raw('COUNT(*) as total_orders_count'))
            ->groupBy('user_id')
            ->orderByDesc('total_sold')
            ->with('user')
            ->first();

        // 2. PRODUCTO MÁS VENDIDO (Por Cantidad)
        $topProductQty = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->whereBetween('orders.order_datetime', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelado')
            ->select('products.product_name', DB::raw('SUM(order_details.quantity) as total_qty'))
            ->groupBy('products.product_name')
            ->orderByDesc('total_qty')
            ->first();

        // 3. PRODUCTO MÁS RENTABLE (Por Dinero Recaudado)
        $topProductMoney = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->whereBetween('orders.order_datetime', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelado')
            ->select('products.product_name', DB::raw('SUM(order_details.quantity * order_details.unit_price) as total_money'))
            ->groupBy('products.product_name')
            ->orderByDesc('total_money')
            ->first();

        return [
            'waiter_name'  => $topWaiter ? ($topWaiter->user->name ?? 'N/A') : 'N/A',
            'waiter_total' => $topWaiter ? $topWaiter->total_sold : 0,
            'waiter_qty'   => $topWaiter ? $topWaiter->total_orders_count : 0,
            
            'pro_qty_name' => $topProductQty ? $topProductQty->product_name : 'N/A',
            'pro_qty_val'  => $topProductQty ? $topProductQty->total_qty : 0,

            'pro_money_name' => $topProductMoney ? $topProductMoney->product_name : 'N/A',
            'pro_money_val'  => $topProductMoney ? $topProductMoney->total_money : 0,
        ];
    }

    /**
     * Lógica para calcular y guardar métricas diarias en la BD (Usado por store y updateMetric)
     */
    private function calculateAndStoreMetrics(string $date, ?Metric $metric = null): Metric
    {
        $ordersToday = Order::whereDate('order_datetime', $date)->where('status', '!=', 'cancelado')->get();
        
        if ($ordersToday->isEmpty() && $metric === null) {
            throw new \Exception('No hubo órdenes válidas en la fecha ' . $date . '.');
        }

        // Cálculos básicos
        $totalSales = $ordersToday->sum('total');
        $totalOrders = $ordersToday->count();

        // Obtenemos los stats avanzados para guardar los nombres en la tabla metrics (como respaldo histórico)
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();
        $stats = $this->getStatsForPeriod($start, $end);

        // Resolver IDs foráneos obligatorios (Integridad Referencial)
        $lastOrder = Order::whereDate('order_datetime', $date)->latest('id')->first();
        $lastPayment = $lastOrder ? Payment::where('order_id', $lastOrder->id)->first() : null;
        
        // Datos a guardar
        $data = [
            'record_date'             => $date,
            'total_sales_date'        => $totalSales,
            'total_orders'            => $totalOrders,
            'best_selling_product_id' => $stats['pro_qty_name'], // Guardamos el nombre
            'most_active_user_id'     => $stats['waiter_name'],  // Guardamos el nombre
            
            // FKs (Usamos IDs seguros o valores por defecto si la tabla está vacía)
            'user_id'  => $ordersToday->first()->user_id ?? User::first()->id ?? 1,
            'order_id' => $lastOrder->id ?? Order::first()->id ?? 1,
            'pay_id'   => $lastPayment->id ?? Payment::first()->id ?? 1,
        ];
        
        if ($metric) {
            $metric->update($data);
            return $metric;
        } else {
            return Metric::create($data);
        }
    }

    public function index()
    {
        // =============================================================
        // 1. MÉTRICAS DIARIAS (Enriquecidas con stats al vuelo)
        // =============================================================
        $rawDailyMetrics = Metric::orderBy('record_date', 'desc')->get();
        
        $metrics = $rawDailyMetrics->map(function ($metric) {
            $start = Carbon::parse($metric->record_date)->startOfDay();
            $end   = Carbon::parse($metric->record_date)->endOfDay();
            // Recalculamos stats para mostrar datos frescos si se editaron órdenes antiguas
            $metric->stats = $this->getStatsForPeriod($start, $end);
            return $metric;
        });

        // =============================================================
        // 2. MÉTRICAS SEMANALES
        // =============================================================
        $weeklyGroups = Order::select(
                DB::raw('YEAR(order_datetime) as year'),
                DB::raw('WEEKOFYEAR(order_datetime) as period')
            )
            ->where('status', '!=', 'cancelado')
            ->groupBy('year', 'period')
            ->orderBy('year', 'desc')
            ->orderBy('period', 'desc')
            ->get();

        $weeklyMetrics = [];
        foreach ($weeklyGroups as $group) {
            $dto = Carbon::now()->setISODate($group->year, $group->period);
            $start = $dto->startOfWeek();
            $end = $dto->copy()->endOfWeek();

            $totals = Order::whereBetween('order_datetime', [$start, $end])
                ->where('status', '!=', 'cancelado')
                ->selectRaw('SUM(total) as total_sales, COUNT(*) as total_orders')
                ->first();

            $stats = $this->getStatsForPeriod($start, $end);

            $weeklyMetrics[] = (object) [
                'year' => $group->year,
                'period' => $group->period,
                'total_sales' => $totals->total_sales,
                'total_orders' => $totals->total_orders,
                'stats' => $stats
            ];
        }

        // =============================================================
        // 3. MÉTRICAS MENSUALES
        // =============================================================
        $monthlyGroups = Order::select(
                DB::raw('YEAR(order_datetime) as year'),
                DB::raw('MONTH(order_datetime) as month_num'),
                DB::raw('MONTHNAME(order_datetime) as month_name')
            )
            ->where('status', '!=', 'cancelado')
            ->groupBy('year', 'month_num', 'month_name')
            ->orderBy('year', 'desc')
            ->orderBy('month_num', 'desc')
            ->get();

        $monthlyMetrics = [];
        foreach ($monthlyGroups as $group) {
            $start = Carbon::create($group->year, $group->month_num, 1)->startOfMonth();
            $end   = Carbon::create($group->year, $group->month_num, 1)->endOfMonth();

            $totals = Order::whereBetween('order_datetime', [$start, $end])
                ->where('status', '!=', 'cancelado')
                ->selectRaw('SUM(total) as total_sales, COUNT(*) as total_orders')
                ->first();

            $stats = $this->getStatsForPeriod($start, $end);

            $monthlyMetrics[] = (object) [
                'year' => $group->year,
                'period' => $group->month_name,
                'total_sales' => $totals->total_sales,
                'total_orders' => $totals->total_orders,
                'stats' => $stats
            ];
        }

        // =============================================================
        // 4. ESTADÍSTICAS GLOBALES POR PRODUCTO (Histórico)
        // =============================================================
        $productStats = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', '!=', 'cancelado')
            ->select(
                'products.product_name',
                DB::raw('SUM(order_details.quantity) as total_qty'),
                DB::raw('SUM(order_details.quantity * order_details.unit_price) as total_money')
            )
            ->groupBy('products.product_name')
            ->orderByDesc('total_money')
            ->get();

        // =============================================================
        // 5. ESTADÍSTICAS GLOBALES POR MESERO (Histórico)
        // =============================================================
        $waiterStats = Order::with('user')
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_orders_count'),
                DB::raw('SUM(total) as total_money_sold')
            )
            ->where('status', '!=', 'cancelado')
            ->groupBy('user_id')
            ->orderByDesc('total_money_sold')
            ->get();

        return view('metrics_crud.ver_crear_metricas', compact(
            'metrics', 
            'weeklyMetrics', 
            'monthlyMetrics',
            'productStats', 
            'waiterStats'
        ));
    }

    // --- MÉTODOS CRUD BÁSICOS ---

    public function store(Request $request)
    {
        $request->validate([
            'record_date' => 'required|date|unique:metrics,record_date',
        ]);
        
        try {
            $this->calculateAndStoreMetrics($request->record_date);
            return redirect()->route('metrics.index')->with('success', 'Reporte diario generado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
    }

    public function updateMetric(Metric $metric)
    {
        try {
            $this->calculateAndStoreMetrics($metric->record_date, $metric);
            return redirect()->route('metrics.index')->with('success', 'Reporte actualizado con datos recientes.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Metric::destroy($id);
        return redirect()->route('metrics.index')->with('success', 'Métrica eliminada');
    }
}