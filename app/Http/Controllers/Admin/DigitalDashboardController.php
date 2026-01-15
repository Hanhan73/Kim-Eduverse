<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalOrder;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use Carbon\Carbon;

class DigitalDashboardController extends Controller
{
    /**
     * Display admin digital dashboard.
     */
    public function index()
    {
        // Basic stats
        $stats = [
            'total_products' => DigitalProduct::count(),
            'active_products' => DigitalProduct::where('is_active', true)->count(),
            'total_categories' => DigitalProductCategory::count(),
            'total_questionnaires' => Questionnaire::count(),
            'active_questionnaires' => Questionnaire::where('is_active', true)->count(),
            'total_orders' => DigitalOrder::count(),
            'paid_orders' => DigitalOrder::where('payment_status', 'paid')->count(),
            'pending_orders' => DigitalOrder::where('payment_status', 'pending')->count(),
            'total_responses' => QuestionnaireResponse::count(),
            'completed_responses' => QuestionnaireResponse::where('is_completed', true)->count(),
            'pending_responses' => QuestionnaireResponse::where('is_completed', false)->count(),
        ];

        // Revenue stats
        $stats['total_revenue'] = DigitalOrder::where('payment_status', 'paid')->sum('total');
        $stats['this_month_revenue'] = DigitalOrder::where('payment_status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('total');

        // Recent orders
        $recentOrders = DigitalOrder::with('items.product')
            ->latest()
            ->limit(10)
            ->get();

        // Recent responses
        $recentResponses = QuestionnaireResponse::with(['questionnaire', 'order'])
            ->where('is_completed', true)
            ->latest('completed_at')
            ->limit(10)
            ->get();

        // Popular products
        $popularProducts = DigitalProduct::orderBy('sold_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = DigitalOrder::where('payment_status', 'paid')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('total');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }

        // Response by questionnaire
        $responsesByQuestionnaire = Questionnaire::withCount([
            'responses as completed_count' => function ($query) {
                $query->where('is_completed', true);
            },
            'responses as pending_count' => function ($query) {
                $query->where('is_completed', false);
            }
        ])->get();

        return view('admin.digital.dashboard', compact(
            'stats',
            'recentOrders',
            'recentResponses',
            'popularProducts',
            'monthlyRevenue',
            'responsesByQuestionnaire'
        ));
    }
}
