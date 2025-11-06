<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'total_students' => User::where('role', 'student')->count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
        ];

        // Recent courses
        $recentCourses = Course::with('instructor')
            ->latest()
            ->take(5)
            ->get();

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        // Top instructors
        $topInstructors = User::where('role', 'instructor')
            ->withCount('coursesAsInstructor')
            ->orderBy('courses_as_instructor_count', 'desc')
            ->take(5)
            ->get();

        return view('edutech.admin.dashboard', compact(
            'stats',
            'recentCourses',
            'recentEnrollments',
            'topInstructors'
        ));
    }

    // === COURSES MANAGEMENT ===
    public function courses()
    {
        $courses = Course::with('instructor')
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);

        return view('edutech.admin.courses.index', compact('courses'));
    }

    public function createCourse()
    {
        $instructors = User::where('role', 'instructor')
            ->where('is_active', true)
            ->get();

        $categories = $this->getCategories();

        return view('edutech.admin.courses.create', compact('instructors', 'categories'));
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'max_students' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course = Course::create($validated);

        return redirect()
            ->route('edutech.admin.courses.edit', $course->id)
            ->with('success', 'Course berhasil dibuat!');
    }

    public function editCourse($id)
    {
        $course = Course::findOrFail($id);
        $instructors = User::where('role', 'instructor')
            ->where('is_active', true)
            ->get();
        $categories = $this->getCategories();

        return view('edutech.admin.courses.edit', compact('course', 'instructors', 'categories'));
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'max_students' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()
            ->route('edutech.admin.courses')
            ->with('success', 'Course berhasil diupdate!');
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()
            ->route('edutech.admin.courses')
            ->with('success', 'Course berhasil dihapus!');
    }

    // === INSTRUCTORS MANAGEMENT ===
    public function instructors()
    {
        $instructors = User::where('role', 'instructor')
            ->withCount('coursesAsInstructor')
            ->latest()
            ->paginate(15);

        return view('edutech.admin.instructors.index', compact('instructors'));
    }

    public function createInstructor()
    {
        return view('edutech.admin.instructors.create');
    }

    public function storeInstructor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'instructor';
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()
            ->route('edutech.admin.instructors')
            ->with('success', 'Instructor berhasil ditambahkan!');
    }

    // === STUDENTS MANAGEMENT ===
    public function students()
    {
        $students = User::where('role', 'student')
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);

        return view('edutech.admin.students.index', compact('students'));
    }

    // === ENROLLMENTS MANAGEMENT ===
    public function enrollments()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->paginate(20);

        return view('edutech.admin.enrollments.index', compact('enrollments'));
    }

    public function approveEnrollment($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update([
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return back()->with('success', 'Enrollment berhasil disetujui!');
    }

    // Helper: Get categories
    private function getCategories()
    {
        return [
            'Education' => [
                'CBTS',
                'Teknik Alba',
                'Media Pembelajaran',
                'AI untuk Pendidikan'
            ],
            'Language' => [
                'Bahasa Inggris',
                'Bahasa Arab'
            ],
            'Teknologi Informasi' => [
                'Office Computer',
                'Coding'
            ],
            'Desain' => [
                'Desain Interior',
                'DKV'
            ],
            'Manajemen dan Teknik Industri' => [
                'ISO 9001:2015',
                '7 Tools',
                'New 7 Tools',
                'Quality Management'
            ]
        ];
    }
}