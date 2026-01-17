<?php

namespace App\Http\Controllers\Digital\Collaborator;

use App\Http\Controllers\Controller;
use App\Models\CollaboratorRevenue;
use App\Models\CollaboratorWithdrawal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollaboratorRevenueController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        // Base query
        $revenuesQuery = CollaboratorRevenue::where('collaborator_id', $userId)
            ->with(['product', 'order']);
        
        // Apply period filter
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $revenuesQuery->whereDate('created_at', today());
                    break;
                case 'this_week':
                    $revenuesQuery->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $revenuesQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $revenuesQuery->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $revenuesQuery->whereYear('created_at', now()->year);
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $revenuesQuery->whereBetween('created_at', [
                            $request->start_date . ' 00:00:00',
                            $request->end_date . ' 23:59:59'
                        ]);
                    }
                    break;
            }
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $revenuesQuery->where('status', $request->status);
        }
        
        // Calculate period stats if filtered
        $periodStats = null;
        if ($request->hasAny(['period', 'status'])) {
            $periodRevenuesClone = clone $revenuesQuery;
            $periodRevenues = $periodRevenuesClone->get();
            
            $periodStats = [
                'total_revenue' => $periodRevenues->sum('product_price'),
                'your_share' => $periodRevenues->sum('collaborator_share'),
                'platform_fee' => $periodRevenues->count() * 5000,
                'transaction_count' => $periodRevenues->count(),
                'avg_per_transaction' => $periodRevenues->count() > 0 
                    ? $periodRevenues->sum('collaborator_share') / $periodRevenues->count() 
                    : 0,
            ];
        }
        
        // Get paginated results
        $revenues = $revenuesQuery->latest()->paginate(20);
        
        // Calculate summary stats (all time)
        $availableRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'available')
            ->sum('collaborator_share');
        
        $pendingRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'pending')
            ->sum('collaborator_share');
        
        $withdrawnRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'withdrawn')
            ->sum('collaborator_share');
        
        $totalRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->sum('collaborator_share');
        
        // Get withdrawal history
        $withdrawals = CollaboratorWithdrawal::where('collaborator_id', $userId)
            ->latest()
            ->paginate(10, ['*'], 'withdrawals_page');
        
        return view('digital.collaborator.revenue.index', compact(
            'revenues',
            'availableRevenue',
            'pendingRevenue',
            'withdrawnRevenue',
            'totalRevenue',
            'withdrawals',
            'periodStats'
        ));
    }

    public function createWithdrawal()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $availableRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'available')
            ->sum('collaborator_share');

        return view('digital.collaborator.revenue.withdrawal', compact('availableRevenue'));
    }

    public function storeWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $availableRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'available')
            ->sum('collaborator_share');

        if ($request->amount > $availableRevenue) {
            return back()->with('error', 'Jumlah penarikan melebihi saldo yang tersedia');
        }

        DB::beginTransaction();
        try {
            $withdrawal = CollaboratorWithdrawal::create([
                'collaborator_id' => $userId,
                'amount' => $request->amount,
                'status' => 'pending',
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'notes' => $request->notes
            ]);

            DB::commit();
            return redirect()->route('digital.collaborator.revenue.index')
                ->with('success', 'Permintaan penarikan berhasil diajukan. Menunggu persetujuan Bendahara.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}