<?php

namespace App\Http\Controllers\Edutech\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\InstructorWithdrawal;
use App\Models\InstructorRevenue;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BendaharaDashboardController extends Controller
{
    public function index()
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized - Only Bendahara EduTech can access this page');
        }

        // Stats Overview
        $stats = [
            'pending_count' => InstructorWithdrawal::where('status', 'pending')->count(),
            'pending_amount' => InstructorWithdrawal::where('status', 'pending')->sum('amount'),
            'approved_today' => InstructorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->count(),
            'approved_amount_today' => InstructorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->sum('amount'),
            'total_revenue_month' => InstructorRevenue::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('instructor_share'),
            'transactions_month' => InstructorRevenue::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'active_instructors' => InstructorRevenue::distinct('instructor_id')->count('instructor_id'),
        ];

        // Recent withdrawals
        $recentWithdrawals = InstructorWithdrawal::with('instructor')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top earning instructors this month
        $topInstructors = InstructorRevenue::select('instructor_id')
            ->selectRaw('SUM(instructor_share) as total_revenue')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('instructor_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $instructor = User::find($item->instructor_id);
                $available = InstructorRevenue::where('instructor_id', $item->instructor_id)
                    ->where('status', 'available')
                    ->sum('instructor_share');
                $withdrawn = InstructorRevenue::where('instructor_id', $item->instructor_id)
                    ->where('status', 'withdrawn')
                    ->sum('instructor_share');
                
                return [
                    'instructor' => $instructor,
                    'total_revenue' => $item->total_revenue,
                    'available' => $available,
                    'withdrawn' => $withdrawn,
                ];
            });

        return view('edutech.bendahara.dashboard', compact('stats', 'recentWithdrawals', 'topInstructors'));
    }
}