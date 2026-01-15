<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ConsultantController extends Controller
{
    // Kategori konsultasi
    private $categories = [
        'pendidikan' => [
            'title' => 'Pendidikan',
            'icon' => 'fa-graduation-cap',
            'description' => 'Konsultasi untuk pengembangan sistem pendidikan dan kurikulum',
            'color' => '#667eea'
        ],
        'manajemen-industri' => [
            'title' => 'Manajemen Industri',
            'icon' => 'fa-industry',
            'description' => 'Optimalisasi proses produksi dan efisiensi industri',
            'color' => '#48bb78',
            'parent' => 'manajemen'
        ],
        'manajemen-operasional' => [
            'title' => 'Manajemen Operasional',
            'icon' => 'fa-cogs',
            'description' => 'Pengelolaan operasional bisnis yang efektif',
            'color' => '#3182ce',
            'parent' => 'manajemen'
        ],
        'manajemen-sdm' => [
            'title' => 'Manajemen SDM',
            'icon' => 'fa-users',
            'description' => 'Pengembangan dan pengelolaan sumber daya manusia',
            'color' => '#805ad5',
            'parent' => 'manajemen'
        ],
        'manajemen-strategic' => [
            'title' => 'Manajemen Strategik',
            'icon' => 'fa-chess',
            'description' => 'Perencanaan dan implementasi strategi bisnis',
            'color' => '#d69e2e',
            'parent' => 'manajemen'
        ],
        'manajemen-logistik' => [
            'title' => 'Manajemen Logistik',
            'icon' => 'fa-truck',
            'description' => 'Optimalisasi supply chain dan distribusi',
            'color' => '#e53e3e',
            'parent' => 'manajemen'
        ],
        'manajemen-perubahan' => [
            'title' => 'Manajemen Perubahan',
            'icon' => 'fa-exchange-alt',
            'description' => 'Implementasi perubahan organisasi yang efektif',
            'color' => '#38b2ac',
            'parent' => 'manajemen'
        ],
        'teknik-industri' => [
            'title' => 'Teknik Industri',
            'icon' => 'fa-tools',
            'description' => 'Konsultasi teknik dan optimalisasi proses industri',
            'color' => '#f56565'
        ],
        'tik' => [
            'title' => 'Teknologi Informasi & Komunikasi',
            'icon' => 'fa-laptop-code',
            'description' => 'Transformasi digital dan implementasi teknologi',
            'color' => '#4299e1'
        ],
        'pertanian' => [
            'title' => 'Pertanian',
            'icon' => 'fa-leaf',
            'description' => 'Konsultasi agribisnis dan pengembangan pertanian modern',
            'color' => '#48bb78'
        ],
        'pariwisata' => [
            'title' => 'Pariwisata',
            'icon' => 'fa-umbrella-beach',
            'description' => 'Pengembangan destinasi dan pengelolaan pariwisata',
            'color' => '#ed8936'
        ],
        'desain' => [
            'title' => 'Desain',
            'icon' => 'fa-palette',
            'description' => 'Konsultasi desain produk dan branding',
            'color' => '#9f7aea'
        ]
    ];

    public function index()
    {
        // Group categories
        $mainCategories = [];
        $managementCategories = [];

        foreach ($this->categories as $key => $category) {
            if (isset($category['parent']) && $category['parent'] === 'manajemen') {
                $managementCategories[$key] = $category;
            } else {
                $mainCategories[$key] = $category;
            }
        }

        return view('consultant.index', compact('mainCategories', 'managementCategories'));
    }

    public function show($category)
    {
        if (!isset($this->categories[$category])) {
            abort(404);
        }

        $categoryData = $this->categories[$category];

        return view('consultant.show', compact('category', 'categoryData'));
    }

    public function submitInquiry(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'perusahaan' => 'nullable|string|max:255',
        'telepon' => 'nullable|string|max:20',
        'kategori' => 'required|string',
        'pesan' => 'nullable|string|max:1000'
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $data = $validator->validated();

    try {
        Mail::send('emails.consultant-inquiry', $data, function ($message) use ($data) {
            $message->to(config('mail.from.address')) // email admin
                ->subject('Inquiry Konsultasi - ' . ucfirst(str_replace('-', ' ', $data['kategori'])))
                ->replyTo($data['email'], $data['nama']);
        });

        return back()->with('success', 'Terima kasih! Tim kami akan segera menghubungi Anda melalui email atau WhatsApp.');

    } catch (\Exception $e) {
        \Log::error('Failed to send consultant inquiry email: ' . $e->getMessage());

        return back()->with('error', 'Terjadi kesalahan saat mengirim inquiry. Silakan coba lagi.');
    }
}
}