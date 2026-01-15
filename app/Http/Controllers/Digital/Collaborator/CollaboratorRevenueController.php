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
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        // Total revenue stats
        $availableRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'available')
            ->sum('collaborator_share');
        
        $withdrawnRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'withdrawn')
            ->sum('collaborator_share');
        
        $pendingRevenue = CollaboratorRevenue::where('collaborator_id', $userId)
            ->where('status', 'pending')
            ->sum('collaborator_share');

        // Revenue history
        $revenues = CollaboratorRevenue::where('collaborator_id', $userId)
            ->with(['product', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Withdrawal history
        $withdrawals = CollaboratorWithdrawal::where('collaborator_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('digital.collaborator.revenue.index', compact(
            'availableRevenue',
            'withdrawnRevenue',
            'pendingRevenue',
            'revenues',
            'withdrawals'
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