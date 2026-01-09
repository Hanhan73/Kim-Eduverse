<?php

namespace App\Http\Controllers\Digital\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\CollaboratorWithdrawal;
use App\Models\CollaboratorRevenue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BendaharaDigitalWithdrawalController extends Controller
{
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized');
        }

        $withdrawals = CollaboratorWithdrawal::with('collaborator')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'pending' => CollaboratorWithdrawal::where('status', 'pending')->count(),
            'pending_amount' => CollaboratorWithdrawal::where('status', 'pending')->sum('amount'),
            'approved_today' => CollaboratorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->count(),
            'approved_amount_today' => CollaboratorWithdrawal::where('status', 'approved')
                ->whereDate('approved_at', today())->sum('amount'),
        ];

        return view('digital.bendahara.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function show(CollaboratorWithdrawal $withdrawal)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized');
        }

        $withdrawal->load('collaborator');

        // Get collaborator's available revenue
        $availableRevenue = CollaboratorRevenue::where('collaborator_id', $withdrawal->collaborator_id)
            ->where('status', 'available')
            ->sum('collaborator_share');

        // Get collaborator's recent revenues
        $recentRevenues = CollaboratorRevenue::where('collaborator_id', $withdrawal->collaborator_id)
            ->with(['product', 'order'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('digital.bendahara.withdrawals.show', compact(
            'withdrawal',
            'availableRevenue',
            'recentRevenues'
        ));
    }

    public function approve(CollaboratorWithdrawal $withdrawal)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized');
        }

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal sudah diproses sebelumnya');
        }

        $availableRevenue = CollaboratorRevenue::where('collaborator_id', $withdrawal->collaborator_id)
            ->where('status', 'available')
            ->sum('collaborator_share');

        if ($withdrawal->amount > $availableRevenue) {
            return back()->with('error', 'Saldo collaborator tidak mencukupi');
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
            $revenues = CollaboratorRevenue::where('collaborator_id', $withdrawal->collaborator_id)
                ->where('status', 'available')
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($revenues as $revenue) {
                if ($remainingAmount <= 0) break;

                if ($revenue->collaborator_share <= $remainingAmount) {
                    $revenue->update([
                        'status' => 'withdrawn',
                        'withdrawal_id' => $withdrawal->id
                    ]);
                    $remainingAmount -= $revenue->collaborator_share;
                } else {
                    // Split revenue if partial withdrawal
                    $withdrawnPart = CollaboratorRevenue::create([
                        'collaborator_id' => $revenue->collaborator_id,
                        'order_id' => $revenue->order_id,
                        'product_id' => $revenue->product_id,
                        'product_price' => $remainingAmount / 0.7,
                        'collaborator_share' => $remainingAmount,
                        'platform_share' => $remainingAmount * 0.3 / 0.7,
                        'status' => 'withdrawn',
                        'withdrawal_id' => $withdrawal->id
                    ]);

                    $revenue->update([
                        'collaborator_share' => $revenue->collaborator_share - $remainingAmount,
                        'product_price' => $revenue->product_price - ($remainingAmount / 0.7),
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

    public function reject(Request $request, CollaboratorWithdrawal $withdrawal)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
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