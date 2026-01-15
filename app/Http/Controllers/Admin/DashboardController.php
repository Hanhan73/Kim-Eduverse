<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use App\Models\DigitalOrder;
use App\Models\DigitalProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalProducts = DigitalProduct::count();
        $totalOrders = DigitalOrder::count();
        $totalRevenue = DigitalOrder::where('payment_status', 'paid')->sum('total');
        $pendingOrders = DigitalOrder::where('payment_status', 'pending')->count();

        // Recent orders
        $recentOrders = DigitalOrder::with(['items.product'])
            ->latest()
            ->limit(10)
            ->get();

        // Top products
        $topProducts = DigitalProduct::withCount(['orderItems'])
            ->orderBy('sold_count', 'desc')
            ->limit(5)
            ->get();

        // Revenue by month (last 6 months)
        $monthlyRevenue = DigitalOrder::where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.digital.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'recentOrders',
            'topProducts',
            'monthlyRevenue'
        ));
    }
}
