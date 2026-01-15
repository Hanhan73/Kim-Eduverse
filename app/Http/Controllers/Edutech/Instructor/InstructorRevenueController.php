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
    public function index()
    {
        // Pakai session, BUKAN Auth::user()
        $instructorId = session('edutech_user_id');
        $instructor = User::find($instructorId);
        
        if (!$instructor || $instructor->role !== 'instructor') {
            abort(403, 'Unauthorized - Only instructors can access this page');
        }
        
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
        
        // History revenue
        $revenues = InstructorRevenue::where('instructor_id', $instructor->id)
            ->with(['payment', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Withdrawal history
        $withdrawals = InstructorWithdrawal::where('instructor_id', $instructor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('edutech.instructor.revenue.index', compact(
            'availableRevenue',
            'withdrawnRevenue',
            'pendingRevenue',
            'revenues',
            'withdrawals'
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