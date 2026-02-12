<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalProduct::with(['category', 'collaborator']);

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $products = $query->latest()->paginate(20);
        $categories = DigitalProductCategory::all();

        return view('admin.digital.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = DigitalProductCategory::where('is_active', true)->get();
        $questionnaires = Questionnaire::all();
        $collaborators = User::where('is_active', true)->where('role', 'collaborator')->get();

        return view('admin.digital.products.create', compact('categories', 'questionnaires', 'collaborators'));
    }

    public function edit($id)
    {
        $product = DigitalProduct::findOrFail($id);
        $categories = DigitalProductCategory::where('is_active', true)->get();
        $questionnaires = Questionnaire::all();
        $collaborators = User::where('is_active', true)->where('role', 'collaborator')->get();

        return view('admin.digital.products.edit', compact('product', 'categories', 'questionnaires', 'collaborators'));
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:digital_products,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|in:questionnaire,module,template,ebook,video,seminar,other',
            'category_id' => 'required|exists:digital_product_categories,id',
            'collaborator_id' => 'nullable|exists:users,id',
            'price' => 'required|numeric|min:0',
            'questionnaire_id' => 'nullable|exists:questionnaires,id',
            'file_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            // Tambahan untuk e-book
            'ebook_access_duration_days' => 'nullable|integer|min:1|max:3650',
        ]);

        $validated['is_active'] = $request->input('is_active') == '1' ? true : false;
        $validated['is_featured'] = $request->input('is_featured') == '1' ? true : false;
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Set default ebook access duration jika type ebook
        if ($validated['type'] === 'ebook' && empty($validated['ebook_access_duration_days'])) {
            $validated['ebook_access_duration_days'] = 90; // default 3 bulan
        }

        $product = DigitalProduct::create($validated);
        
        // JIKA TYPE SEMINAR - Buat Seminar dengan product_id
        if ($validated['type'] === 'seminar') {
            \App\Models\Seminar::create([
                'product_id' => $product->id,
                'collaborator_id' => $validated['collaborator_id'],
                'title' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'thumbnail' => $validated['thumbnail'] ?? null,
                'instructor_name' => null,
                'instructor_bio' => null,
                'duration_minutes' => $request->duration_minutes ?? 60,
                'is_active' => $validated['is_active'] ?? true,
                'is_featured' => $validated['is_featured'] ?? false,
            ]);
        }
        
        return redirect()
            ->route('admin.digital.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $product = DigitalProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:digital_products,slug,' . $id,
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|in:questionnaire,module,template,ebook,video,seminar,other',
            'category_id' => 'required|exists:digital_product_categories,id',
            'collaborator_id' => 'nullable|exists:users,id',
            'price' => 'required|numeric|min:0',
            'questionnaire_id' => 'nullable|exists:questionnaires,id',
            'file_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            // Tambahan untuk e-book
            'ebook_access_duration_days' => 'nullable|integer|min:1|max:3650',
        ]);

        $validated['is_active'] = $request->input('is_active') == '1' ? true : false;
        $validated['is_featured'] = $request->input('is_featured') == '1' ? true : false;
    
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $product->update($validated);

        // SYNC to Seminar if type is seminar
        if ($product->type === 'seminar' && $product->seminar) {
            $product->seminar->update([
                'collaborator_id' => $validated['collaborator_id'],
                'title' => $validated['name'],
                'slug' => $validated['slug'] ?? $product->slug,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'thumbnail' => $validated['thumbnail'] ?? $product->seminar->thumbnail,
                'is_active' => $validated['is_active'],
                'is_featured' => $validated['is_featured'],
            ]);
        }

        return redirect()
            ->route('admin.digital.products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $product = DigitalProduct::findOrFail($id);

        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        // Delete seminar first (it has FK to product)
        if ($product->type === 'seminar' && $product->seminar) {
            $product->seminar->delete();
        }
        
        $product->delete();

        return redirect()
            ->route('admin.digital.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}