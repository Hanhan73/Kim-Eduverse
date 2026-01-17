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
        $query = DigitalProduct::with(['category']);

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
        ]);

        // Set default values for checkboxes
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_featured'] = $request->has('is_featured') ? true : false;

        // Generate slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $product = DigitalProduct::create($validated);
        if ($validated['type'] === 'seminar') {
            \App\Models\Seminar::create([
                'product_id' => $product->id,
                'title' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'instructor_name' => $request->instructor_name ?? 'Instruktur',
                'duration_minutes' => $request->duration_minutes ?? 60,
                'is_active' => $validated['is_active'] ?? true,
            ]);
        }
        return redirect()
            ->route('admin.digital.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = DigitalProduct::findOrFail($id);
        $categories = DigitalProductCategory::where('is_active', true)->get();
        $questionnaires = Questionnaire::all();
        $collaborators = User::where('is_active', true)->where('role', 'collaborator')->get();

        return view('admin.digital.products.edit', compact('product', 'categories', 'questionnaires', 'collaborators'));
    }

    public function update(Request $request, $id)
    {
        $product = DigitalProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:digital_products,slug,' . $id,
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|in:questionnaire,module,template,ebook,video,other',
            'category_id' => 'required|exists:digital_product_categories,id',
            'collaborator_id' => 'nullable|exists:users,id',
            'price' => 'required|numeric|min:0',
            'questionnaire_id' => 'nullable|exists:questionnaires,id',
            'file_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
    
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $product->update($validated);

        if ($product->type === 'seminar' && $product->seminar) {
            $product->seminar->update([
                'title' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'instructor_name' => $request->instructor_name ?? $product->seminar->instructor_name,
                'duration_minutes' => $request->duration_minutes ?? $product->seminar->duration_minutes,
                'is_active' => $validated['is_active'] ?? true,
            ]);
        }

        return redirect()
            ->route('admin.digital.products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $product = DigitalProduct::findOrFail($id);

        // Delete thumbnail
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        if ($product->type === 'seminar' && $product->seminar) {
            $product->seminar->delete();
        }
        
        $product->delete();

        return redirect()
            ->route('admin.digital.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}