<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalOrder::with(['items.product']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.digital.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = DigitalOrder::with(['items.product'])->findOrFail($id);

        return view('admin.digital.orders.show', compact('order'));
    }

    public function downloadInvoice($id)
    {
        $order = DigitalOrder::with(['items.product'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.digital.orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $order = DigitalOrder::findOrFail($id);

        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
            'order_status' => 'required|in:pending,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.digital.orders.show', $id)
            ->with('success', 'Status order berhasil diupdate');
    }
}
