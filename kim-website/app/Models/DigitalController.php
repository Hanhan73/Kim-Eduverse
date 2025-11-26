<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use App\Models\DigitalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DigitalController extends Controller
{
    /**
     * Display KIM Digital landing page.
     */
    public function index()
    {
        $featuredProducts = DigitalProduct::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->orderBy('order')
            ->limit(6)
            ->get();

        $categories = DigitalProductCategory::where('is_active', true)
            ->withCount(['activeProducts'])
            ->orderBy('order')
            ->get();

        return view('digital.index', compact('featuredProducts', 'categories'));
    }

    /**
     * Display product catalog.
     */
    public function catalog(Request $request)
    {
        $query = DigitalProduct::where('is_active', true)->with('category');

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'popular':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('order');
        }

        $products = $query->paginate(12);
        $categories = DigitalProductCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('digital.catalog', compact('products', 'categories'));
    }

    /**
     * Display product detail.
     */
    public function show($slug)
    {
        $product = DigitalProduct::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'questionnaire.questions'])
            ->firstOrFail();

        $relatedProducts = DigitalProduct::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('digital.show', compact('product', 'relatedProducts'));
    }

    /**
     * Add product to cart.
     */
    public function addToCart(Request $request, $productId)
    {
        $product = DigitalProduct::findOrFail($productId);

        $cart = Session::get('digital_cart', []);

        // Check if product already in cart
        if (isset($cart[$productId])) {
            return redirect()->back()->with('info', 'Produk sudah ada di keranjang');
        }

        // Add product to cart
        $cart[$productId] = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'type' => $product->type,
            'thumbnail' => $product->thumbnail,
            'quantity' => 1,
        ];

        Session::put('digital_cart', $cart);

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    /**
     * Display cart.
     */
    public function cart()
    {
        $cart = Session::get('digital_cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = 0; // You can add tax calculation here
        $total = $subtotal + $tax;

        return view('digital.cart', compact('cart', 'subtotal', 'tax', 'total'));
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart($productId)
    {
        $cart = Session::get('digital_cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('digital_cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
    }

    /**
     * Display checkout page.
     */
    public function checkout()
    {
        $cart = Session::get('digital_cart', []);

        if (empty($cart)) {
            return redirect()->route('digital.catalog')->with('error', 'Keranjang kosong');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = 0;
        $total = $subtotal + $tax;

        return view('digital.checkout', compact('cart', 'subtotal', 'tax', 'total'));
    }
}
