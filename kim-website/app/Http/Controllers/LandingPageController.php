<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Tampilkan landing page produk (URL: /promo/{slug})
     */
    public function show($slug)
    {
        $product = DigitalProduct::where('slug', $slug)
            ->where('is_active', true)
            ->with('landingPage')
            ->firstOrFail();

        // Pastikan produk punya landing page aktif
        if (!$product->landingPage || !$product->landingPage->is_active) {
            // Redirect ke halaman detail biasa jika tidak ada landing page
            return redirect()->route('digital.show', $slug);
        }

        return view('digital.landing', compact('product'));
    }
}