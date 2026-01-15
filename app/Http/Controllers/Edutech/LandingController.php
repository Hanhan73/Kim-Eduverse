<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    // Landing page utama edutech
    public function index()
    {
        $featuredCourses = Course::where('is_published', true)
            ->where('is_featured', true)
            ->with('instructor')
            ->withCount('enrollments')
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'total_courses' => Course::where('is_published', true)->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_instructors' => User::where('role', 'instructor')->where('is_active', true)->count(),
        ];

        return view('edutech.landing.index', compact('featuredCourses', 'stats'));
    }

    // Halaman daftar courses dengan filter
    public function courses(Request $request)
    {
        $query = Course::where('is_published', true)->with('instructor');

        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        // Filter by level
        if ($request->has('level') && $request->level != 'all') {
            $query->where('level', $request->level);
        }

        // Filter by price
        if ($request->has('price') && $request->price != 'all') {
            switch ($request->price) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'under_500k':
                    $query->where('price', '>', 0)->where('price', '<', 500000);
                    break;
                case 'under_1m':
                    $query->where('price', '>', 0)->where('price', '<', 1000000);
                    break;
                case 'above_1m':
                    $query->where('price', '>=', 1000000);
                    break;
            }
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12);

        // Kategori utama (bukan sub-kategori)
        $categories = [
            'Education',
            'Language',
            'Teknologi Informasi',
            'Desain',
            'Manajemen dan Teknik Industri'
        ];

        return view('edutech.courses.index', compact('courses', 'categories'));
    }

    // Helper method baru
    private function getAllCategoriesFlat()
    {
        $categoriesNested = $this->getCategories();

        $flat = [];
        foreach ($categoriesNested as $parent => $children) {
            foreach ($children as $child) {
                $flat[] = $child;
            }
        }

        return $flat;
    }

    // Detail course
    public function courseDetail($slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['instructor', 'materials', 'liveSessions'])
            ->firstOrFail();

        // Check if user already enrolled
        $isEnrolled = false;
        $isPaid = false;
        if (session()->has('edutech_user_id')) {
            $isEnrolled = Enrollment::where('student_id', session('edutech_user_id'))
                ->where('course_id', $course->id)
                ->exists();
            $isPaid = Enrollment::where('student_id', session('edutech_user_id'))
                ->where('payment_status', 'paid')
                ->exists();
        }

        // Related courses
        $relatedCourses = Course::where('is_published', true)
            ->where('category', $course->category)
            ->where('id', '!=', $course->id)
            ->take(3)
            ->get();

        return view('edutech.courses.show', compact('course', 'isEnrolled', 'relatedCourses'. 'isPaid'));
    }

    // Helper: Get categories
    private function getCategories()
    {
        return [
            'Education' => ['CBTS', 'Teknik Alba', 'Media Pembelajaran', 'AI untuk Pendidikan'],
            'Language' => ['Bahasa Inggris', 'Bahasa Arab'],
            'Teknologi Informasi' => ['Office Computer', 'Coding'],
            'Desain' => ['Desain Interior', 'DKV'],
            'Manajemen dan Teknik Industri' => ['ISO 9001:2015', '7 Tools', 'New 7 Tools', 'Quality Management']
        ];
    }
}
