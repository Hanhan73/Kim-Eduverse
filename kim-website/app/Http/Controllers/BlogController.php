<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Public blog index
    public function index(Request $request)
    {
        $query = Article::published()->latest();

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(9);
        
        $categories = Article::published()
            ->select('category')
            ->distinct()
            ->pluck('category');

        // Hitung jumlah artikel per kategori (hanya yang published)
        $categoryCounts = Article::published()
            ->select('category', \DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category');

        // Hitung total semua artikel published (untuk "Semua")
        $totalArticles = Article::published()->count();

        $popularArticles = Article::published()
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        return view('blog.index', compact('articles', 'categories', 'popularArticles', 'categoryCounts', 'totalArticles'));
    }

    // Show single article
    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $article->incrementViews();

        // Related articles (same category)
        $relatedArticles = Article::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->published()
            ->latest()
            ->take(3)
            ->get();

        return view('blog.show', compact('article', 'relatedArticles'));
    }
}
