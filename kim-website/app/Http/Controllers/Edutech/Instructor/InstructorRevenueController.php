<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorRevenue;
use App\Models\InstructorWithdrawal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorRevenueController extends Controller
{
    public function index(Request $request)
    {
        // Pakai session, BUKAN Auth::user()
        $instructorId = session('edutech_user_id');
        $instructor = User::find($instructorId);
        
        if (!$instructor || $instructor->role !== 'instructor') {
            abort(403, 'Unauthorized - Only instructors can access this page');
        }
        
        // Base query
        $revenuesQuery = InstructorRevenue::where('instructor_id', $instructorId)
            ->with(['payment', 'course', 'order']);
        
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
                'total_revenue' => $periodRevenues->sum('course_price'),
                'your_share' => $periodRevenues->sum('instructor_share'),
                'platform_fee' => $periodRevenues->count() * 5000,
                'transaction_count' => $periodRevenues->count(),
                'avg_per_transaction' => $periodRevenues->count() > 0 
                    ? $periodRevenues->sum('instructor_share') / $periodRevenues->count() 
                    : 0,
            ];
        }
        
        // Get paginated results
        $revenues = $revenuesQuery->latest()->paginate(20);
        
        // Total revenue available (belum ditarik)
        $availableRevenue = InstructorRevenue::where('instructor_id', $instructorId)
            ->where('status', 'available')
            ->sum('instructor_share');
        
        // Total yang sudah ditarik
        $withdrawnRevenue = InstructorRevenue::where('instructor_id', $instructor->id)
            ->where('status', 'withdrawn')
            ->sum('instructor_share');
        
        // Total pending
        $pendingRevenue = InstructorRevenue::where('instructor_id', $instructor->id)
            ->where('status', 'pending')
            ->sum('instructor_share');
        
        // Total revenue all time
        $totalRevenue = InstructorRevenue::where('instructor_id', $instructor->id)
            ->sum('instructor_share');
        
        // Withdrawal history
        $withdrawals = InstructorWithdrawal::where('instructor_id', $instructor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'withdrawals_page');
        
        return view('edutech.instructor.revenue.index', compact(
            'availableRevenue',
            'withdrawnRevenue',
            'pendingRevenue',
            'totalRevenue',
            'revenues',
            'withdrawals',
            'periodStats'
        ));
    }
    
    public function createWithdrawal()
    {
        $instructorId = session('edutech_user_id');
        $instructor = User::find($instructorId);
        
        if (!$instructor || $instructor->role !== 'instructor') {
            abort(403, 'Unauthorized');
        }
        
        $availableRevenue = InstructorRevenue::where('instructor_id', $instructorId)
            ->where('status', 'available')
            ->sum('instructor_share');
        
        return view('edutech.instructor.revenue.withdrawal', compact('availableRevenue'));
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
        
        $instructorId = session('edutech_user_id');
        $instructor = User::find($instructorId);
        
        if (!$instructor || $instructor->role !== 'instructor') {
            abort(403, 'Unauthorized');
        }
        
        $availableRevenue = InstructorRevenue::where('instructor_id', $instructorId)
            ->where('status', 'available')
            ->sum('instructor_share');
        
        if ($request->amount > $availableRevenue) {
            return back()->with('error', 'Jumlah penarikan melebihi saldo yang tersedia');
        }
        
        DB::beginTransaction();
        try {
            $withdrawal = InstructorWithdrawal::create([
                'instructor_id' => $instructorId,
                'amount' => $request->amount,
                'status' => 'pending',
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'notes' => $request->notes
            ]);
            
            DB::commit();
            return redirect()->route('edutech.instructor.revenue.index')
                ->with('success', 'Permintaan penarikan berhasil diajukan. Menunggu persetujuan Bendahara.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}