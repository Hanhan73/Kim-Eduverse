<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbookAccess;
use App\Models\DigitalProduct;
use App\Services\EbookAccessService;
use Illuminate\Http\Request;

class EbookAccessController extends Controller
{
    protected $ebookService;

    public function __construct(EbookAccessService $ebookService)
    {
        $this->ebookService = $ebookService;
    }

    /**
     * Display all ebook accesses
     */
    public function index(Request $request)
    {
        $query = EbookAccess::with(['product', 'order']);

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where('expires_at', '>', now());
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<=', now());
            } elseif ($request->status === 'revoked') {
                $query->where('is_active', false);
            }
        }

        // Search by email
        if ($request->filled('search')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        $accesses = $query->latest()->paginate(20);
        $products = DigitalProduct::where('type', 'ebook')->get();

        return view('admin.digital.ebook-access.index', compact('accesses', 'products'));
    }

    /**
     * Show details of an access
     */
    public function show($id)
    {
        $access = EbookAccess::with(['product', 'order'])->findOrFail($id);
        
        return view('admin.digital.ebook-access.show', compact('access'));
    }

    /**
     * Extend access duration
     */
    public function extend(Request $request, $id)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $access = EbookAccess::findOrFail($id);
        
        $this->ebookService->extendAccess($access, $request->days);

        return redirect()
            ->back()
            ->with('success', "Akses berhasil diperpanjang {$request->days} hari");
    }

    /**
     * Revoke access
     */
    public function revoke($id)
    {
        $access = EbookAccess::findOrFail($id);
        
        $this->ebookService->revokeAccess($access);

        return redirect()
            ->back()
            ->with('success', 'Akses berhasil dicabut');
    }

    /**
     * Reactivate access
     */
    public function reactivate($id)
    {
        $access = EbookAccess::findOrFail($id);
        
        $access->update([
            'is_active' => true,
            'expires_at' => now()->addDays(30) // Default 30 hari
        ]);

        return redirect()
            ->back()
            ->with('success', 'Akses berhasil diaktifkan kembali');
    }

    /**
     * Delete access
     */
    public function destroy($id)
    {
        $access = EbookAccess::findOrFail($id);
        $access->delete();

        return redirect()
            ->route('admin.digital.ebook-access.index')
            ->with('success', 'Data akses berhasil dihapus');
    }
}