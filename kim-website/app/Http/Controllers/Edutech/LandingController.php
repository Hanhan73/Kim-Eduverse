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

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
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
        $categories = $this->getCategories();

        return view('edutech.landing.courses', compact('courses', 'categories'));
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
        if (session()->has('edutech_user_id')) {
            $isEnrolled = Enrollment::where('user_id', session('edutech_user_id'))
                ->where('course_id', $course->id)
                ->exists();
        }

        // Related courses
        $relatedCourses = Course::where('is_published', true)
            ->where('category', $course->category)
            ->where('id', '!=', $course->id)
            ->take(3)
            ->get();

        return view('edutech.landing.course-detail', compact('course', 'isEnrolled', 'relatedCourses'));
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