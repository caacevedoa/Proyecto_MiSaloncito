<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MetricController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filtro de Mes para "Estadísticas Detalladas"
        // Si no viene fecha, usamos el mes actual
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

        // --- CÁLCULOS PARA TARJETAS PRINCIPALES (KPIs) ---
        
        // A. Métricas Diarias (HOY)
        $dailyMetrics = $this->getMetricsByRange(Carbon::today(), Carbon::today()->endOfDay());

        // B. Métricas Semanales (Esta semana)
        $weeklyMetrics = $this->getMetricsByRange(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());

        // C. Métricas Mensuales (Este mes actual)
        $monthlyMetrics = $this->getMetricsByRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());

        // --- D. ESTADÍSTICAS DETALLADAS (POR MES SELECCIONADO) ---
        $detailedStats = $this->getDetailedStats($startOfMonth, $endOfMonth);

        // --- E. ESTADÍSTICAS GLOBALES (HISTÓRICO) ---
        $globalStats = $this->getDetailedStats(null, null); // Null significa "todo el tiempo"

        return view('metrics_crud.ver_crear_metricas', compact(
            'dailyMetrics', 
            'weeklyMetrics', 
            'monthlyMetrics', 
            'detailedStats', 
            'globalStats',
            'selectedMonth'
        ));
    }

    /**
     * Función auxiliar para obtener KPIs básicos en un rango de fechas.
     * Retorna: Ventas, Ordenes, Tops.
     */
    private function getMetricsByRange($startDate, $endDate)
    {
        // Consultamos órdenes CERRADAS en el rango
        $orders = Order::where('status', 'cerrado')
            ->whereBetween('order_datetime', [$startDate, $endDate])
            ->with(['details.product', 'user']) // Eager loading
            ->get();

        return [
            'date_label' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'total_sales' => $orders->sum('total'),
            'total_orders' => $orders->count(),
            'top_waiter' => $this->getTopWaiter($orders),
            'top_product_qty' => $this->getTopProduct($orders, 'quantity'),
            'top_product_money' => $this->getTopProduct($orders, 'money'),
        ];
    }

    /**
     * Función auxiliar para estadísticas detalladas (Categorías y Meseros).
     * Si las fechas son null, calcula histórico global.
     */
    private function getDetailedStats($startDate, $endDate)
    {
        // Consulta base para obtener TODAS las órdenes CERRADAS en el rango
        $query = Order::where('status', 'cerrado')->with(['details.product', 'user']);

        if ($startDate && $endDate) {
            $query->whereBetween('order_datetime', [$startDate, $endDate]);
        }

        $orders = $query->get();

        // 1. Calcular el Total General de Ventas del período
        $totalGeneralVentas = $orders->sum('total');

        // Si no hay ventas, retornamos cero para evitar división por cero.
        if ($totalGeneralVentas == 0) {
            return [
                'sales_by_category' => collect(),
                'waiter_stats' => collect(),
                'payment_methods' => collect(), 
                'total_sales' => 0,
                'total_orders' => $orders->count(),
            ];
        }

        // 2. Aplanamos todos los detalles de todas las órdenes
        $allDetails = $orders->flatMap(function ($order) {
            return $order->details;
        });

        // 3. Agrupar, calcular y desglosar por CATEGORÍA
        $salesByCategory = $allDetails->groupBy(function ($detail) {
            return $detail->product->product_type;
        })->map(function ($categoryDetails, $type) use ($totalGeneralVentas) {
            
            // Desglose por Producto dentro de la Categoría
            $productBreakdown = $categoryDetails->groupBy('product_id')->map(function ($productDetails) use ($totalGeneralVentas) {
                $subtotalProducto = $productDetails->sum('subtotal');
                return [
                    'product_name' => $productDetails->first()->product->product_name,
                    'quantity' => $productDetails->sum('quantity'),
                    'total_money' => $subtotalProducto,
                    'percentage' => round(($subtotalProducto / $totalGeneralVentas) * 100, 2),
                ];
            })->values()->sortByDesc('total_money');

            // Cálculo total de la categoría
            $subtotalCategoria = $categoryDetails->sum('subtotal');
            
            return [
                'type' => $type,
                'quantity' => $categoryDetails->sum('quantity'),
                'total_money' => $subtotalCategoria,
                'percentage' => round(($subtotalCategoria / $totalGeneralVentas) * 100, 2),
                'products' => $productBreakdown, // ¡El desglose anidado!
            ];
        })->values()->sortByDesc('total_money');

        // 4. Rendimiento de MESEROS
        $waiterStats = $orders->groupBy('user_id')->map(function ($userOrders) {
            return [
                'name' => $userOrders->first()->user->name,
                'orders_count' => $userOrders->count(),
                'total_sold' => $userOrders->sum('total'),
            ];
        })->values();


        // ----------------------------------------------------------------------
        // 5. MÉTODOS DE PAGO - CORREGIDO con payments.total_pay
        // ----------------------------------------------------------------------
        $paymentsQuery = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            // CORRECCIÓN APLICADA: payments.total_pay
            ->select('payments.payment_method', DB::raw('SUM(payments.total_pay) as total_money'));

        if ($startDate && $endDate) {
            // Filtramos por fecha si no es la consulta global
            $paymentsQuery->whereBetween('orders.order_datetime', [$startDate, $endDate]);
        }
        
        // Filtramos solo los pagos asociados a órdenes cerradas
        $paymentsQuery->where('orders.status', 'cerrado');

        $paymentsByMethod = $paymentsQuery
            ->groupBy('payments.payment_method')
            ->get()
            ->toArray(); 
        
        // ----------------------------------------------------------------------

        return [
            'sales_by_category' => $salesByCategory,
            'waiter_stats' => $waiterStats,
            'payment_methods' => $paymentsByMethod, // ¡Datos de pagos incluidos!
            'total_sales' => $totalGeneralVentas,
            'total_orders' => $orders->count(),
        ];
    }

    // --- HELPERS PRIVADOS PARA ENCONTRAR TOPS ---

    private function getTopWaiter($orders)
    {
        if ($orders->isEmpty()) return 'N/A';

        // Agrupamos por usuario y sumamos su total vendido
        $top = $orders->groupBy('user_id')->map(function ($group) {
            return [
                'name' => $group->first()->user->name,
                'total' => $group->sum('total')
            ];
        })->sortByDesc('total')->first();

        return $top ? $top['name'] . ' ($' . number_format($top['total']) . ')' : 'N/A';
    }

    private function getTopProduct($orders, $criteria)
    {
        if ($orders->isEmpty()) return 'N/A';

        // Aplanamos detalles
        $details = $orders->flatMap->details;

        if ($details->isEmpty()) return 'N/A';

        // Agrupamos por producto
        $top = $details->groupBy('product_id')->map(function ($group) {
            return [
                'name' => $group->first()->product->product_name,
                'qty' => $group->sum('quantity'),
                'money' => $group->sum('subtotal')
            ];
        })->sortByDesc($criteria == 'quantity' ? 'qty' : 'money')->first();

        if (!$top) return 'N/A';

        if ($criteria == 'quantity') {
            return $top['name'] . ' (' . $top['qty'] . ' un.)';
        } else {
            return $top['name'] . ' ($' . number_format($top['money']) . ')';
        }
    }
}