<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    private function categories()
    {
        return [
            'mobile' => [
                'title' => 'Aplikasi Mobile',
                'description' => 'Android & iOS',
                'icon' => 'fa-mobile-alt',
                'color' => '#4ECDC4'
            ],
            'web' => [
                'title' => 'Aplikasi Web',
                'description' => 'Berbasis browser & cloud',
                'icon' => 'fa-globe',
                'color' => '#45B7D1'
            ],
            'desktop' => [
                'title' => 'Aplikasi Desktop',
                'description' => 'Windows / Mac / Linux',
                'icon' => 'fa-desktop',
                'color' => '#FFA07A'
            ],
            'enterprise' => [
                'title' => 'Aplikasi Enterprise',
                'description' => 'Sistem skala besar',
                'icon' => 'fa-building',
                'color' => '#98D8C8'
            ],
        ];
    }

    public function index()
    {
        return view('developer.index', [
            'applicationCategories' => $this->categories()
        ]);
    }

    public function show($category)
    {
        $categories = $this->categories();

        abort_if(!isset($categories[$category]), 404);

        return view('developer.show', [
            'categoryKey' => $category,
            'category' => $categories[$category],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name' => 'required',
            'whatsapp' => 'required',
            'description' => 'required',
        ]);

        // sementara: kirim ke email / log
        // nanti bisa simpan DB

        return redirect()->back()->with('success', 'Permintaan berhasil dikirim');
    }
}