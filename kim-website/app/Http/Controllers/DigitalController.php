<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::info('=== CART PAGE ACCESSED ===');
        
        $cart = session()->get('digital_cart', []);
        
        Log::info('Cart contents', [
            'items_count' => count($cart),
            'cart_data' => $cart
        ]);
        
        if (empty($cart)) {
            Log::info('Cart is empty');
            $subtotal = 0;
            $tax = 0;
            $total = 0;
        } else {
            $subtotal = collect($cart)->sum('price');
            $tax = round($subtotal * 0.01); // 1% admin fee
            $total = $subtotal + $tax;
            
            Log::info('Cart totals calculated', [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total
            ]);
        }

        Log::info('Rendering cart view');
        return view('digital.cart', compact('cart', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add product to cart and show cart page
     */
    public function addToCart($id)
    {
        Log::info('=== ADD TO CART STARTED ===');
        Log::info('Product ID: ' . $id);
        
        try {
            $product = DigitalProduct::where('id', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            Log::info('Product found', [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'type' => $product->type
            ]);

            // Clear any existing cart (only 1 product allowed)
            $oldCart = session()->get('digital_cart', []);
            Log::info('Old cart', ['items' => count($oldCart)]);
            
            session()->forget('digital_cart');
            Log::info('Cart cleared');

            // Add single product to cart
            $cart = [
                $id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'type' => $product->type,
                    'thumbnail' => $product->thumbnail,
                    'slug' => $product->slug,
                ]
            ];

            session()->put('digital_cart', $cart);
            Log::info('New cart created', ['cart' => $cart]);
            
            // Verify cart saved
            $savedCart = session()->get('digital_cart', []);
            Log::info('Cart verification', ['items_in_session' => count($savedCart)]);

            // Redirect ke CART PAGE
            Log::info('Redirecting to cart page');
            $redirectUrl = route('digital.cart');
            Log::info('Redirect URL: ' . $redirectUrl);
            
            return redirect()->route('digital.cart')
                ->with('success', 'Produk berhasil ditambahkan. Silakan lanjutkan ke pembayaran.');
                
        } catch (\Exception $e) {
            Log::error('Add to cart failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Gagal menambahkan produk. Silakan coba lagi.');
        }
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