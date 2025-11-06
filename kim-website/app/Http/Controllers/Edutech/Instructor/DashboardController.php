<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\LiveSession;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $instructorId = session('edutech_user_id');

        $stats = [
            'total_courses' => Course::where('instructor_id', $instructorId)->count(),
            'published_courses' => Course::where('instructor_id', $instructorId)->where('is_published', true)->count(),
            'total_students' => Enrollment::whereHas('course', function($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })->where('status', 'active')->count(),
            'pending_enrollments' => Enrollment::whereHas('course', function($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })->where('status', 'pending')->count(),
        ];

        $myCourses = Course::where('instructor_id', $instructorId)
            ->withCount('enrollments')
            ->latest()
            ->take(5)
            ->get();

        $upcomingSessions = LiveSession::where('instructor_id', $instructorId)
            ->where('scheduled_at', '>', now())
            ->with('course')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        return view('edutech.instructor.dashboard', compact('stats', 'myCourses', 'upcomingSessions'));
    }

    // My Courses
    public function myCourses()
    {
        $instructorId = session('edutech_user_id');

        $courses = Course::where('instructor_id', $instructorId)
            ->withCount('enrollments')
            ->latest()
            ->paginate(12);

        return view('edutech.instructor.courses.index', compact('courses'));
    }

    // Create Course
    public function createCourse()
    {
        $categories = $this->getCategories();
        return view('edutech.instructor.courses.create', compact('categories'));
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
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

        $validated['instructor_id'] = session('edutech_user_id');
        $validated['is_published'] = false;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course = Course::create($validated);

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Course berhasil dibuat! Silakan tambahkan materi.');
    }

    // Publish/Unpublish Course
    public function publishCourse($id)
    {
        $instructorId = session('edutech_user_id');
        $course = Course::where('instructor_id', $instructorId)->findOrFail($id);

        $course->update(['is_published' => !$course->is_published]);

        $status = $course->is_published ? 'dipublikasikan' : 'di-draft';
        return back()->with('success', "Course berhasil {$status}!");
    }

    // My Students
    public function myStudents()
    {
        $instructorId = session('edutech_user_id');

        $enrollments = Enrollment::whereHas('course', function($q) use ($instructorId) {
            $q->where('instructor_id', $instructorId);
        })
        ->with(['user', 'course'])
        ->where('status', 'active')
        ->latest()
        ->paginate(20);

        return view('edutech.instructor.students.index', compact('enrollments'));
    }

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