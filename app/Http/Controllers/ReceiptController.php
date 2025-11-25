<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function descargarFactura($id)
    {
        // Traer la orden con detalles y productos
        $order = Order::with('orderDetails.product', 'payment')->findOrFail($id);

        // Calcular total de la orden
        $total = $order->orderDetails->sum('subtotal');

        // Pago (puede ser null)
        $payment = $order->payment;

        // Renderizar el PDF
        $pdf = Pdf::loadView('factura.pdf', compact('order', 'total', 'payment'));

        return $pdf->download("factura_orden_$id.pdf");
    }
}
