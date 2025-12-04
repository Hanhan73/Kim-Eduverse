<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use App\Models\ProductLandingPage;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Daftar produk dengan status landing page
     */
    public function index()
    {
        $products = DigitalProduct::with('landingPage')
            ->latest()
            ->paginate(20);

        return view('admin.digital.landing-pages.index', compact('products'));
    }

    /**
     * Form edit/create landing page
     */
    public function edit($productId)
    {
        $product = DigitalProduct::findOrFail($productId);
        $landingPage = $product->landingPage ?? new ProductLandingPage([
            'product_id' => $product->id,
            'navbar_button_text' => 'Beli Sekarang',
            'navbar_logo_text' => 'KIM Digital',
        ]);

        return view('admin.digital.landing-pages.edit', compact('product', 'landingPage'));
    }

    /**
     * Simpan landing page
     */
    public function update(Request $request, $productId)
    {
        $product = DigitalProduct::findOrFail($productId);

        $validated = $request->validate([
            'html_content' => 'required|string',
            'navbar_button_text' => 'nullable|string|max:100',
            'navbar_logo_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['product_id'] = $product->id;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['navbar_button_text'] = $validated['navbar_button_text'] ?: 'Beli Sekarang';

        ProductLandingPage::updateOrCreate(
            ['product_id' => $product->id],
            $validated
        );

        return redirect()
            ->route('admin.digital.landing-pages.edit', $product->id)
            ->with('success', 'Landing page berhasil disimpan!');
    }

    /**
     * Hapus landing page
     */
    public function destroy($productId)
    {
        $product = DigitalProduct::findOrFail($productId);
        
        if ($product->landingPage) {
            $product->landingPage->delete();
        }

        return redirect()
            ->route('admin.digital.landing-pages.index')
            ->with('success', 'Landing page berhasil dihapus.');
    }

    /**
     * Preview landing page (admin)
     */
    public function preview($productId)
    {
        $product = DigitalProduct::with('landingPage')->findOrFail($productId);
        
        if (!$product->landingPage) {
            return redirect()
                ->route('admin.digital.landing-pages.edit', $productId)
                ->with('error', 'Silakan buat landing page terlebih dahulu.');
        }

        return view('digital.landing', compact('product'));
    }
}