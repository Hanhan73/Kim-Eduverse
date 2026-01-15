<?php

namespace App\Http\Controllers\Digital\Collaborator;

use App\Http\Controllers\Controller;
use App\Models\CollaboratorRevenue;
use App\Models\CollaboratorWithdrawal;
use App\Models\DigitalProduct;
use App\Models\User;

class CollaboratorDashboardController extends Controller
{
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized - Only collaborators can access this page');
        }

        // Stats Overview
        $stats = [
            'available_revenue' => CollaboratorRevenue::where('collaborator_id', $userId)
                ->where('status', 'available')
                ->sum('collaborator_share'),
            'withdrawn_revenue' => CollaboratorRevenue::where('collaborator_id', $userId)
                ->where('status', 'withdrawn')
                ->sum('collaborator_share'),
            'pending_revenue' => CollaboratorRevenue::where('collaborator_id', $userId)
                ->where('status', 'pending')
                ->sum('collaborator_share'),
            'total_products' => DigitalProduct::where('collaborator_id', $userId)->count(),
            'total_sales' => CollaboratorRevenue::where('collaborator_id', $userId)->count(),
            'pending_withdrawals' => CollaboratorWithdrawal::where('collaborator_id', $userId)
                ->where('status', 'pending')
                ->count(),
        ];

        // Recent revenues
        $recentRevenues = CollaboratorRevenue::where('collaborator_id', $userId)
            ->with(['product', 'order'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Products performance
        $topProducts = CollaboratorRevenue::where('collaborator_id', $userId)
            ->with('product')
            ->selectRaw('product_id, COUNT(*) as sales, SUM(collaborator_share) as revenue')
            ->groupBy('product_id')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();

        return view('digital.collaborator.dashboard', compact('stats', 'recentRevenues', 'topProducts'));
    }
}