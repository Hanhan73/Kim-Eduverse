<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\LiveSession;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $instructorId = session('edutech_user_id');

        // Statistics
        $stats = [
            'total_courses' => Course::where('instructor_id', $instructorId)->count(),
            'published_courses' => Course::where('instructor_id', $instructorId)
                ->where('is_published', true)
                ->count(),
            'total_students' => \App\Models\Enrollment::whereHas('course', function($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })->where('status', 'active')->count(),
            'pending_enrollments' => \App\Models\Enrollment::whereHas('course', function($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })->where('status', 'pending')->count(),
        ];

        // My courses
        $myCourses = Course::where('instructor_id', $instructorId)
            ->withCount('enrollments')
            ->latest()
            ->take(5)
            ->get();

        // Upcoming live sessions
        $upcomingSessions = LiveSession::where('instructor_id', $instructorId)
            ->where('scheduled_at', '>', now())
            ->with('course')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        return view('edutech.instructor.dashboard', compact('stats', 'myCourses', 'upcomingSessions'));
    }

    // === MY COURSES ===
    public function myCourses()
    {
        $instructorId = session('edutech_user_id');

        $courses = Course::where('instructor_id', $instructorId)
            ->withCount('enrollments')
            ->latest()
            ->paginate(12);

        return view('edutech.instructor.courses.index', compact('courses'));
    }

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
        $validated['is_published'] = false; // Draft by default

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course = Course::create($validated);

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Course berhasil dibuat! Silakan tambahkan materi.');
    }

    public function editCourse($id)
    {
        $instructorId = session('edutech_user_id');
        $course = Course::where('instructor_id', $instructorId)
            ->findOrFail($id);

        $categories = $this->getCategories();

        return view('edutech.instructor.courses.edit', compact('course', 'categories'));
    }

    public function updateCourse(Request $request, $id)
    {
        $instructorId = session('edutech_user_id');
        $course = Course::where('instructor_id', $instructorId)
            ->findOrFail($id);

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

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()
            ->route('edutech.instructor.courses')
            ->with('success', 'Course berhasil diupdate!');
    }

    public function publishCourse($id)
    {
        $instructorId = session('edutech_user_id');
        $course = Course::where('instructor_id', $instructorId)
            ->findOrFail($id);

        $course->update(['is_published' => !$course->is_published]);

        $status = $course->is_published ? 'dipublikasikan' : 'di-draft';

        return back()->with('success', "Course berhasil {$status}!");
    }

    // === COURSE MATERIALS ===
    public function courseMaterials($courseId)
    {
        $instructorId = session('edutech_user_id');
        $course = Course::where('instructor_id', $instructorId)
            ->with('materials')
            ->findOrFail($courseId);

        return view('edutech.instructor.materials.index', compact('course'));
    }

    public function storeMaterial(Request $request, $courseId)
    {
        $instructorId = session('edutech_user_id');
        $course = Course::where('instructor_id', $instructorId)
            ->findOrFail($courseId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,document,link,text',
            'content' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'order' => 'required|integer|min:0',
        ]);

        $validated['course_id'] = $courseId;

        CourseMaterial::create($validated);

        return back()->with('success', 'Materi berhasil ditambahkan!');
    }

    // === STUDENTS ===
    public function myStudents()
    {
        $instructorId = session('edutech_user_id');

        $enrollments = \App\Models\Enrollment::whereHas('course', function($q) use ($instructorId) {
            $q->where('instructor_id', $instructorId);
        })
        ->with(['user', 'course'])
        ->where('status', 'active')
        ->latest()
        ->paginate(20);

        return view('edutech.instructor.students.index', compact('enrollments'));
    }

    // === LIVE SESSIONS ===
    public function liveSessions()
    {
        $instructorId = session('edutech_user_id');

        $sessions = LiveSession::where('instructor_id', $instructorId)
            ->with('course')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        return view('edutech.instructor.live-sessions.index', compact('sessions'));
    }

    public function createLiveSession()
    {
        $instructorId = session('edutech_user_id');
        $courses = Course::where('instructor_id', $instructorId)
            ->where('is_published', true)
            ->get();

        return view('edutech.instructor.live-sessions.create', compact('courses'));
    }

    public function storeLiveSession(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_url' => 'required|url',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15',
        ]);

        $validated['instructor_id'] = session('edutech_user_id');
        $validated['status'] = 'scheduled';

        LiveSession::create($validated);

        return redirect()
            ->route('edutech.instructor.live-sessions')
            ->with('success', 'Live session berhasil dijadwalkan!');
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