<?php

namespace App\Http\Controllers\Digital\Collaborator;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollaboratorProductController extends Controller
{
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $products = DigitalProduct::where('collaborator_id', $userId)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_products' => DigitalProduct::where('collaborator_id', $userId)->count(),
            'active_products' => DigitalProduct::where('collaborator_id', $userId)->where('is_active', true)->count(),
            'inactive_products' => DigitalProduct::where('collaborator_id', $userId)->where('is_active', false)->count(),
        ];

        return view('digital.collaborator.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $categories = DigitalProductCategory::where('is_active', true)->get();
        $questionnaires = \App\Models\Questionnaire::where('is_active', true)->get();

        return view('digital.collaborator.products.create', compact('categories', 'questionnaires'));
    }

    public function store(Request $request)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:digital_products,slug',
            'category_id' => 'required|exists:digital_product_categories,id',
            'type' => 'required|in:questionnaire,module,template,ebook,video,seminar,other',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'questionnaire_id' => 'nullable|exists:questionnaires,id',
            'file_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        try {
            // Generate slug
            $slug = $request->slug ?: Str::slug($request->name) . '-' . time();

            // Handle thumbnail upload
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            $product = DigitalProduct::create([
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'type' => $request->type,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'questionnaire_id' => $request->questionnaire_id,
                'file_url' => $request->file_url,
                'thumbnail' => $thumbnailPath,
                'duration_minutes' => $request->duration_minutes,
                'collaborator_id' => $userId,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('digital.collaborator.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit(DigitalProduct $product)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        // Check ownership
        if ($product->collaborator_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke produk ini');
        }

        $categories = DigitalProductCategory::where('is_active', true)->get();
        $questionnaires = \App\Models\Questionnaire::where('is_active', true)->get();

        return view('digital.collaborator.products.edit', compact('product', 'categories', 'questionnaires'));
    }

    public function update(Request $request, DigitalProduct $product)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        // Check ownership
        if ($product->collaborator_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke produk ini');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:digital_products,slug,' . $product->id,
            'category_id' => 'required|exists:digital_product_categories,id',
            'type' => 'required|in:questionnaire,module,template,ebook,video,seminar,other',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'questionnaire_id' => 'nullable|exists:questionnaires,id',
            'file_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'category_id' => $request->category_id,
                'type' => $request->type,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'questionnaire_id' => $request->questionnaire_id,
                'file_url' => $request->file_url,
                'duration_minutes' => $request->duration_minutes,
            ];

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($product->thumbnail && \Storage::disk('public')->exists($product->thumbnail)) {
                    \Storage::disk('public')->delete($product->thumbnail);
                }
                $updateData['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            $product->update($updateData);

            return redirect()->route('digital.collaborator.products.index')
                ->with('success', 'Produk berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update produk: ' . $e->getMessage());
        }
    }

    public function destroy(DigitalProduct $product)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        // Check ownership
        if ($product->collaborator_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke produk ini');
        }

        $product->delete();

        return redirect()->route('digital.collaborator.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function toggleStatus(DigitalProduct $product)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        // Check ownership
        if ($product->collaborator_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke produk ini');
        }

        $product->update([
            'is_active' => !$product->is_active
        ]);

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Produk berhasil {$status}!");
    }

    // === SIMPLE PRODUCT CREATION (Ebook, Video, Template, Module) ===

    public function createSimple(Request $request)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $type = $request->get('type', 'ebook');
        $categories = \App\Models\DigitalProductCategory::where('is_active', true)->get();
        
        return view('digital.collaborator.products.create-simple', compact('type', 'categories'));
    }

    public function storeSimple(Request $request)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:digital_product_categories,id',
            'type' => 'required|in:ebook,video,template,module',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'file_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            DigitalProduct::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . time(),
                'category_id' => $request->category_id,
                'type' => $request->type,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'file_url' => $request->file_url,
                'thumbnail' => $thumbnailPath,
                'duration_minutes' => $request->duration_minutes,
                'collaborator_id' => $userId,
                'is_active' => true,
            ]);

            return redirect()->route('digital.collaborator.products.index')
                ->with('success', ucfirst($request->type) . ' berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }
}