<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use Illuminate\Http\Request;

class DigitalController extends Controller
{
    /**
     * Display landing page
     */
    public function index()
    {
        $categories = DigitalProductCategory::withCount([
            'products' => function ($query) {
                $query->where('is_active', true);
            }
        ])->get();

        $featuredProducts = DigitalProduct::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->latest()
            ->limit(6)
            ->get();

        return view('digital.index', compact('categories', 'featuredProducts'));
    }

    /**
     * Display product catalog with filters
     */
    public function catalog(Request $request)
    {
        $query = DigitalProduct::where('is_active', true)->with('category');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        switch ($request->get('sort', 'popular')) {
            case 'newest':
                $query->latest('created_at');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default: // popular
                $query->orderBy('sold_count', 'desc')->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = DigitalProductCategory::all();

        return view('digital.catalog', compact('products', 'categories'));
    }

    /**
     * Display product detail
     */
    public function show($slug)
    {
        $product = DigitalProduct::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'questionnaire'])
            ->firstOrFail();

        // Get related products from same category
        $relatedProducts = DigitalProduct::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('digital.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display shopping cart
     */
    public function cart()
    {
        $cart = session()->get('digital_cart', []);

        if (empty($cart)) {
            $subtotal = 0;
            $tax = 0;
            $total = 0;
        } else {
            $subtotal = collect($cart)->sum('price');
            $tax = round($subtotal * 0.01); // 1% admin fee
            $total = $subtotal + $tax;
        }

        return view('digital.cart', compact('cart', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add product to cart
     */
    public function addToCart($id)
    {
        $product = DigitalProduct::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $cart = session()->get('digital_cart', []);

        // Check if product already in cart
        if (isset($cart[$id])) {
            return back()->with('info', 'Produk sudah ada di keranjang');
        }

        // Add to cart
        $cart[$id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'type' => $product->type,
            'thumbnail' => $product->thumbnail,
            'slug' => $product->slug,
        ];

        session()->put('digital_cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('digital_cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('digital_cart', $cart);
            return back()->with('success', 'Produk berhasil dihapus dari keranjang');
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang');
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        session()->forget('digital_cart');
        return redirect()->route('digital.catalog')->with('success', 'Keranjang berhasil dikosongkan');
    }

    /**
     * Display checkout page
     */
    public function checkout()
    {
        $cart = session()->get('digital_cart', []);

        if (empty($cart)) {
            return redirect()->route('digital.catalog')
                ->with('error', 'Keranjang belanja kosong. Silakan pilih produk terlebih dahulu.');
        }

        $subtotal = collect($cart)->sum('price');
        $tax = round($subtotal * 0.01);
        $total = $subtotal + $tax;

        return view('digital.checkout', compact('cart', 'subtotal', 'tax', 'total'));
    }
}
