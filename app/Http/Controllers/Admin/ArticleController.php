<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // Admin dashboard - list all articles
    public function index()
    {
        $articles = Article::latest()->paginate(15);
        
        $stats = [
            'total' => Article::count(),
            'published' => Article::where('is_published', true)->count(),
            'draft' => Article::where('is_published', false)->count(),
            'total_views' => Article::sum('views'),
        ];

        return view('admin.articles.index', compact('articles', 'stats'));
    }

    // Show create form
    public function create()
    {
        $categories = [
            'Digital Transformation',
            'Manajemen',
            'Teknologi',
            'Pendidikan',
            'Bisnis',
            'Tutorial',
            'Berita'
        ];

        return view('admin.articles.create', compact('categories'));
    }

    // Store new article
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:100',
            'is_published' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->extension();
            $image->move(public_path('uploads/articles'), $imageName);
            $validated['image'] = 'uploads/articles/' . $imageName;
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['author'] = $validated['author'] ?? 'Admin';

        Article::create($validated);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    // Show edit form
    public function edit(Article $article)
    {
        $categories = [
            'Digital Transformation',
            'Manajemen',
            'Teknologi',
            'Pendidikan',
            'Bisnis',
            'Tutorial',
            'Berita'
        ];

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    // Update article
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:100',
            'is_published' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($article->image && file_exists(public_path($article->image))) {
                unlink(public_path($article->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->extension();
            $image->move(public_path('uploads/articles'), $imageName);
            $validated['image'] = 'uploads/articles/' . $imageName;
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['author'] = $validated['author'] ?? $article->author;

        $article->update($validated);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diupdate!');
    }

    // Delete article
    public function destroy(Article $article)
    {
        // Delete image if exists
        if ($article->image && file_exists(public_path($article->image))) {
            unlink(public_path($article->image));
        }

        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    // Toggle publish status
    public function togglePublish(Article $article)
    {
        $article->update([
            'is_published' => !$article->is_published
        ]);

        $status = $article->is_published ? 'dipublikasikan' : 'dijadikan draft';

        return back()->with('success', "Artikel berhasil {$status}!");
    }
}