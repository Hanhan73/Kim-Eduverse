<?php

namespace App\Http\Controllers\Digital\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\CollaboratorRevenue;
use App\Models\User;

class BendaharaDigitalRevenueController extends Controller
{
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized');
        }

        // Stats
        $stats = [
            'total_revenue' => CollaboratorRevenue::sum('collaborator_share'),
            'platform_share' => CollaboratorRevenue::sum('platform_share'),
            'total_transactions' => CollaboratorRevenue::count(),
            'avg_transaction' => CollaboratorRevenue::avg('product_price') ?? 0,
        ];

        // Revenue list
        $revenues = CollaboratorRevenue::with(['collaborator', 'product', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('digital.bendahara.revenues', compact('stats', 'revenues'));
    }
}