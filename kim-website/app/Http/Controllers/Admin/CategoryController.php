<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = DigitalProductCategory::withCount('products')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('admin.digital.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.digital.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:digital_product_categories,name',
            'slug' => 'nullable|string|max:255|unique:digital_product_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        DigitalProductCategory::create($validated);

        return redirect()->route('admin.digital.categories.index')
            ->with('success', 'Kategori berhasil dibuat!');
    }

    /**
     * Show the form for editing the category
     */
    public function edit($id)
    {
        $category = DigitalProductCategory::findOrFail($id);
        return view('admin.digital.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = DigitalProductCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:digital_categories,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:digital_categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.digital.categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = DigitalProductCategory::findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus kategori yang masih memiliki produk!');
        }

        $category->delete();

        return redirect()->route('admin.digital.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
