<?php

namespace App\Http\Controllers\Digital\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\CollaboratorRevenue;
use App\Models\User;
use Illuminate\Http\Request;

class BendaharaDigitalRevenueController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_digital') {
            abort(403, 'Unauthorized');
        }

        // Base query
        $revenuesQuery = CollaboratorRevenue::with(['collaborator', 'product', 'order']);
        
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
        
        // Apply collaborator filter
        if ($request->filled('collaborator_id')) {
            $revenuesQuery->where('collaborator_id', $request->collaborator_id);
        }
        
        // Handle export
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportRevenue($revenuesQuery->get());
        }
        
        // Get paginated results
        $revenues = $revenuesQuery->latest()->paginate(20);
        
        // Calculate stats based on current query
        $statsQuery = clone $revenuesQuery;
        $allRevenues = $statsQuery->get();
        
        $stats = [
            'total_revenue' => $allRevenues->sum('product_price'),
            'collaborator_total' => $allRevenues->sum('collaborator_share'),
            'platform_share' => $allRevenues->sum('platform_share'),
            'platform_fee_total' => $allRevenues->count() * 5000,
            'total_transactions' => $allRevenues->count(),
            'avg_transaction' => $allRevenues->count() > 0 
                ? $allRevenues->sum('product_price') / $allRevenues->count() 
                : 0,
        ];
        
        // Get all collaborators for filter
        $collaborators = User::where('role', 'collaborator')->orderBy('name')->get();
        
        return view('digital.bendahara.revenue', compact(
            'revenues',
            'stats',
            'collaborators'
        ));
    }

    private function exportRevenue($revenues)
    {
        $filename = 'revenue_report_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($revenues) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Tanggal',
                'Collaborator',
                'Email',
                'Product',
                'Order Number',
                'Harga Product',
                'Platform Fee',
                'Sisa',
                'Collaborator Share (70%)',
                'Platform Additional (30%)',
                'Total Platform',
                'Status'
            ]);
            
            // Data
            foreach ($revenues as $revenue) {
                $remaining = max(0, $revenue->product_price - 5000);
                $platformAdditional = $revenue->platform_share - 5000;
                
                fputcsv($file, [
                    $revenue->created_at->format('d/m/Y H:i'),
                    $revenue->collaborator->name,
                    $revenue->collaborator->email,
                    $revenue->product->name,
                    $revenue->order->order_number,
                    $revenue->product_price,
                    5000,
                    $remaining,
                    $revenue->collaborator_share,
                    $platformAdditional,
                    $revenue->platform_share,
                    $revenue->status
                ]);
            }
            
            // Total
            fputcsv($file, []);
            fputcsv($file, [
                'TOTAL',
                '',
                '',
                '',
                '',
                $revenues->sum('product_price'),
                $revenues->count() * 5000,
                $revenues->sum('product_price') - ($revenues->count() * 5000),
                $revenues->sum('collaborator_share'),
                $revenues->sum('platform_share') - ($revenues->count() * 5000),
                $revenues->sum('platform_share'),
                ''
            ]);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}