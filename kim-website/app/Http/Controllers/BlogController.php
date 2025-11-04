<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Dummy data untuk demo
        $articles = [
            [
                'slug' => 'transformasi-digital-umkm',
                'title' => 'Transformasi Digital untuk UMKM',
                'excerpt' => 'Bagaimana UMKM dapat memanfaatkan teknologi digital untuk meningkatkan bisnis',
                'image' => 'https://via.placeholder.com/800x400',
                'date' => '2024-01-15',
                'category' => 'Digital'
            ],
            [
                'slug' => 'tips-manajemen-efektif',
                'title' => 'Tips Manajemen Bisnis yang Efektif',
                'excerpt' => 'Strategi manajemen untuk meningkatkan produktivitas tim',
                'image' => 'https://via.placeholder.com/800x400',
                'date' => '2024-01-10',
                'category' => 'Manajemen'
            ]
        ];

        return view('blog.index', compact('articles'));
    }

    public function show($slug)
    {
        // Dummy single article
        $article = [
            'slug' => $slug,
            'title' => 'Judul Artikel',
            'content' => 'Konten artikel lengkap...',
            'image' => 'https://via.placeholder.com/1200x600',
            'date' => '2024-01-15',
            'category' => 'Digital'
        ];

        return view('blog.show', compact('article'));
    }
}
