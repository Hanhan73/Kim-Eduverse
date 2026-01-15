<?php

namespace App\Http\Controllers\Edutech\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\InstructorWithdrawal;
use App\Models\InstructorRevenue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BendaharaWithdrawalController extends Controller
{
    public function index()
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized - Only Bendahara EduTech can access this page');
        }

        $withdrawals = InstructorWithdrawal::with('instructor')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $stats = [
            'pending' => InstructorWithdrawal::where('status', 'pending')->count(),
            'pending_amount' => InstructorWithdrawal::where('status', 'pending')->sum('amount'),
            'approved_today' => InstructorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->count(),
            'approved_amount_today' => InstructorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->sum('amount'),
        ];
        
        return view('edutech.bendahara.withdrawals.index', compact('withdrawals', 'stats'));
    }
    
    public function show(InstructorWithdrawal $withdrawal)
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        $withdrawal->load('instructor');
        
        // Get instructor's available revenue
        $availableRevenue = InstructorRevenue::where('instructor_id', $withdrawal->instructor_id)
            ->where('status', 'available')
            ->sum('instructor_share');
        
        // Get instructor's recent revenues
        $recentRevenues = InstructorRevenue::where('instructor_id', $withdrawal->instructor_id)
            ->with(['course', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('edutech.bendahara.withdrawals.show', compact(
            'withdrawal',
            'availableRevenue',
            'recentRevenues'
        ));
    }
    
    public function approve(InstructorWithdrawal $withdrawal)
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal sudah diproses sebelumnya');
        }
        
        $availableRevenue = InstructorRevenue::where('instructor_id', $withdrawal->instructor_id)
            ->where('status', 'available')
            ->sum('instructor_share');
        
        if ($withdrawal->amount > $availableRevenue) {
            return back()->with('error', 'Saldo instructor tidak mencukupi');
        }
        
        DB::beginTransaction();
        try {
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'approved',
                'approved_by' => $userId,
                'approved_at' => now()
            ]);
            
            // Mark revenues as withdrawn
            $remainingAmount = $withdrawal->amount;
            $revenues = InstructorRevenue::where('instructor_id', $withdrawal->instructor_id)
                ->where('status', 'available')
                ->orderBy('created_at', 'asc')
                ->get();
            
            foreach ($revenues as $revenue) {
                if ($remainingAmount <= 0) break;
                
                if ($revenue->instructor_share <= $remainingAmount) {
                    $revenue->update([
                        'status' => 'withdrawn',
                        'withdrawal_id' => $withdrawal->id
                    ]);
                    $remainingAmount -= $revenue->instructor_share;
                } else {
                    // Split revenue if partial withdrawal
                    $withdrawnPart = InstructorRevenue::create([
                        'instructor_id' => $revenue->instructor_id,
                        'payment_id' => $revenue->payment_id,
                        'course_id' => $revenue->course_id,
                        'course_price' => $remainingAmount / 0.7,
                        'instructor_share' => $remainingAmount,
                        'platform_share' => $remainingAmount * 0.3 / 0.7,
                        'status' => 'withdrawn',
                        'withdrawal_id' => $withdrawal->id
                    ]);
                    
                    $revenue->update([
                        'instructor_share' => $revenue->instructor_share - $remainingAmount,
                        'course_price' => $revenue->course_price - ($remainingAmount / 0.7),
                        'platform_share' => $revenue->platform_share - ($remainingAmount * 0.3 / 0.7)
                    ]);
                    
                    $remainingAmount = 0;
                }
            }
            
            DB::commit();
            return back()->with('success', 'Withdrawal berhasil disetujui');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function reject(Request $request, InstructorWithdrawal $withdrawal)
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal sudah diproses sebelumnya');
        }
        
        $withdrawal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => $userId,
            'approved_at' => now()
        ]);
        
        return back()->with('success', 'Withdrawal ditolak');
    }
}