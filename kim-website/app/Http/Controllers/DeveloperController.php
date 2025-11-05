<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function index()
    {
        // Define main categories (excluding 'aplikasi' for now, as per user request)
        $mainCategories = [
            // Add other categories here if needed in the future
            // Example:
            // 'web' => [
            //     'title' => 'Pengembangan Web',
            //     'description' => 'Solusi pengembangan website dan aplikasi web',
            //     'icon' => 'fa-globe',
            //     'color' => '#FF6B6B'
            // ],
        ];

        // Define application categories (subtypes of 'aplikasi')
        $applicationCategories = [
            'mobile' => [
                'title' => 'Aplikasi Mobile',
                'description' => 'Pengembangan aplikasi untuk Android dan iOS',
                'icon' => 'fa-mobile-alt',
                'color' => '#4ECDC4'
            ],
            'web' => [
                'title' => 'Aplikasi Web',
                'description' => 'Pengembangan aplikasi berbasis web',
                'icon' => 'fa-globe',
                'color' => '#45B7D1'
            ],
            'desktop' => [
                'title' => 'Aplikasi Desktop',
                'description' => 'Pengembangan aplikasi untuk desktop',
                'icon' => 'fa-desktop',
                'color' => '#FFA07A'
            ],
            'enterprise' => [
                'title' => 'Aplikasi Enterprise',
                'description' => 'Solusi aplikasi untuk bisnis skala besar',
                'icon' => 'fa-building',
                'color' => '#98D8C8'
            ],
        ];

        return view('developer.index', compact('mainCategories', 'applicationCategories'));
    }

    public function show($category)
    {
        // Logic to show details of a specific category
        // For now, just return a view with the category key
        return view('developer.show', compact('category'));
    }
}