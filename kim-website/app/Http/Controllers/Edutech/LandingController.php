<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Landing page platform edutech
     */
    public function landing()
    {
        $featuredCourses = Course::published()
            ->featured()
            ->with('instructor')
            ->withCount('enrollments')
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'students' => User::where('role', 'student')->count(),
            'courses' => Course::published()->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'certificates' => \App\Models\Certificate::count(),
        ];

        $categories = Course::published()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('edutech.landing', compact('featuredCourses', 'stats', 'categories'));
    }

    /**
     * About page
     */
    public function about()
    {
        $instructors = User::where('role', 'instructor')
            ->where('is_active', true)
            ->withCount('coursesAsInstructor')
            ->take(8)
            ->get();

        return view('edutech.about', compact('instructors'));
    }

    /**
     * FAQ page
     */
    public function faq()
    {
        $faqs = [
            [
                'category' => 'Umum',
                'items' => [
                    [
                        'question' => 'Apa itu KIM Edutech?',
                        'answer' => 'KIM Edutech adalah platform Learning Management System (LMS) yang menyediakan kursus online berkualitas untuk pengembangan skill profesional. Anda dapat belajar kapan saja, di mana saja dengan materi yang terstruktur.'
                    ],
                    [
                        'question' => 'Bagaimana cara mendaftar?',
                        'answer' => 'Klik tombol "Daftar Gratis" di halaman utama, isi form registrasi dengan email aktif Anda, verifikasi email, dan akun Anda siap digunakan.'
                    ],
                    [
                        'question' => 'Apakah gratis?',
                        'answer' => 'Pendaftaran akun gratis. Namun untuk mengakses kursus tertentu, Anda perlu melakukan pembayaran sesuai harga kursus yang dipilih.'
                    ],
                ]
            ],
            [
                'category' => 'Kursus',
                'items' => [
                    [
                        'question' => 'Berapa lama akses kursus?',
                        'answer' => 'Setelah membeli kursus, Anda mendapatkan akses selamanya (lifetime access) untuk semua materi kursus tersebut.'
                    ],
                    [
                        'question' => 'Apakah ada sertifikat?',
                        'answer' => 'Ya! Setelah menyelesaikan semua materi dan lulus post-test dengan nilai minimal yang ditentukan, Anda akan mendapatkan sertifikat digital yang dapat didownload.'
                    ],
                    [
                        'question' => 'Bagaimana sistem penilaian?',
                        'answer' => 'Setiap kursus memiliki pre-test dan post-test. Anda harus menyelesaikan semua materi dan lulus post-test dengan nilai minimal untuk mendapatkan sertifikat.'
                    ],
                ]
            ],
            [
                'category' => 'Pembayaran',
                'items' => [
                    [
                        'question' => 'Metode pembayaran apa yang tersedia?',
                        'answer' => 'Kami menerima berbagai metode pembayaran: Transfer Bank (BCA, Mandiri, BNI, BRI), E-Wallet (GoPay, OVO, Dana), dan Virtual Account.'
                    ],
                    [
                        'question' => 'Apakah ada refund?',
                        'answer' => 'Refund dapat dilakukan dalam 7 hari setelah pembelian jika belum mengakses lebih dari 20% materi kursus. Hubungi customer support untuk proses refund.'
                    ],
                    [
                        'question' => 'Apakah bisa cicilan?',
                        'answer' => 'Saat ini belum tersedia sistem cicilan. Namun kami sering memberikan diskon dan promo khusus untuk berbagai kursus.'
                    ],
                ]
            ],
            [
                'category' => 'Teknis',
                'items' => [
                    [
                        'question' => 'Perangkat apa yang didukung?',
                        'answer' => 'Platform kami dapat diakses melalui desktop, laptop, tablet, dan smartphone. Kami merekomendasikan menggunakan browser Chrome, Firefox, atau Safari versi terbaru.'
                    ],
                    [
                        'question' => 'Apakah materi bisa didownload?',
                        'answer' => 'Materi PDF dapat didownload. Namun video hanya bisa ditonton secara streaming untuk melindungi hak cipta konten.'
                    ],
                    [
                        'question' => 'Bagaimana jika lupa password?',
                        'answer' => 'Klik "Lupa Password" di halaman login, masukkan email terdaftar, dan kami akan mengirimkan link reset password ke email Anda.'
                    ],
                ]
            ],
        ];

        return view('edutech.faq', compact('faqs'));
    }
}