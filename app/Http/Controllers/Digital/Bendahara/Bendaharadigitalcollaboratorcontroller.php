<?php

namespace App\Http\Controllers\Digital\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\CollaboratorRevenue;
use App\Models\CollaboratorWithdrawal;
use App\Models\DigitalProduct;
use App\Models\User;

class BendaharaDigitalCollaboratorController extends Controller
{
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized');
        }

        // Get all collaborators with their stats
        $collaborators = User::where('role', 'collaborator')
            ->get()
            ->map(function ($collaborator) {
                $totalRevenue = CollaboratorRevenue::where('collaborator_id', $collaborator->id)
                    ->sum('collaborator_share');
                $available = CollaboratorRevenue::where('collaborator_id', $collaborator->id)
                    ->where('status', 'available')
                    ->sum('collaborator_share');
                $withdrawn = CollaboratorRevenue::where('collaborator_id', $collaborator->id)
                    ->where('status', 'withdrawn')
                    ->sum('collaborator_share');
                $totalProducts = DigitalProduct::where('collaborator_id', $collaborator->id)->count();
                $totalSales = CollaboratorRevenue::where('collaborator_id', $collaborator->id)->count();

                return [
                    'collaborator' => $collaborator,
                    'total_revenue' => $totalRevenue,
                    'available' => $available,
                    'withdrawn' => $withdrawn,
                    'total_products' => $totalProducts,
                    'total_sales' => $totalSales,
                ];
            })
            ->sortByDesc('total_revenue');

        return view('digital.bendahara.collaborators', compact('collaborators'));
    }

    public function show($collaboratorId)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized');
        }

        $collaborator = User::findOrFail($collaboratorId);

        // Collaborator stats
        $stats = [
            'total_revenue' => CollaboratorRevenue::where('collaborator_id', $collaboratorId)
                ->sum('collaborator_share'),
            'available' => CollaboratorRevenue::where('collaborator_id', $collaboratorId)
                ->where('status', 'available')
                ->sum('collaborator_share'),
            'withdrawn' => CollaboratorRevenue::where('collaborator_id', $collaboratorId)
                ->where('status', 'withdrawn')
                ->sum('collaborator_share'),
            'pending' => CollaboratorRevenue::where('collaborator_id', $collaboratorId)
                ->where('status', 'pending')
                ->sum('collaborator_share'),
        ];

        // Revenues
        $revenues = CollaboratorRevenue::where('collaborator_id', $collaboratorId)
            ->with(['product', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Products
        $products = DigitalProduct::where('collaborator_id', $collaboratorId)
            ->get()
            ->map(function ($product) {
                $sales = CollaboratorRevenue::where('product_id', $product->id)->count();
                $revenue = CollaboratorRevenue::where('product_id', $product->id)
                    ->sum('collaborator_share');
                
                return [
                    'product' => $product,
                    'sales' => $sales,
                    'revenue' => $revenue,
                ];
            });

        // Withdrawals history
        $withdrawals = CollaboratorWithdrawal::where('collaborator_id', $collaboratorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('digital.bendahara.collaborator_detail', compact('collaborator', 'stats', 'revenues', 'products', 'withdrawals'));
    }
}