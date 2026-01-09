<?php

namespace App\Http\Controllers\Digital\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\CollaboratorWithdrawal;
use App\Models\CollaboratorRevenue;
use App\Models\User;

class BendaharaDigitalDashboardController extends Controller
{
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized - Only Bendahara Digital can access this page');
        }

        // Stats Overview
        $stats = [
            'pending_count' => CollaboratorWithdrawal::where('status', 'pending')->count(),
            'pending_amount' => CollaboratorWithdrawal::where('status', 'pending')->sum('amount'),
            'approved_today' => CollaboratorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->count(),
            'approved_amount_today' => CollaboratorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->sum('amount'),
            'total_revenue_month' => CollaboratorRevenue::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('collaborator_share'),
            'transactions_month' => CollaboratorRevenue::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'active_collaborators' => CollaboratorRevenue::distinct('collaborator_id')->count('collaborator_id'),
        ];

        // Recent withdrawals
        $recentWithdrawals = CollaboratorWithdrawal::with('collaborator')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top earning collaborators this month
        $topCollaborators = CollaboratorRevenue::select('collaborator_id')
            ->selectRaw('SUM(collaborator_share) as total_revenue')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('collaborator_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $collaborator = User::find($item->collaborator_id);
                $available = CollaboratorRevenue::where('collaborator_id', $item->collaborator_id)
                    ->where('status', 'available')
                    ->sum('collaborator_share');
                $withdrawn = CollaboratorRevenue::where('collaborator_id', $item->collaborator_id)
                    ->where('status', 'withdrawn')
                    ->sum('collaborator_share');
                
                return [
                    'collaborator' => $collaborator,
                    'total_revenue' => $item->total_revenue,
                    'available' => $available,
                    'withdrawn' => $withdrawn,
                ];
            });

        return view('digital.bendahara.dashboard', compact('stats', 'recentWithdrawals', 'topCollaborators'));
    }
}