<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\LiveSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $studentId = session('edutech_user_id');

        $stats = [
            'active_courses' => Enrollment::where('user_id', $studentId)->where('status', 'active')->count(),
            'completed_courses' => Enrollment::where('user_id', $studentId)->where('status', 'completed')->count(),
            'certificates' => Certificate::where('user_id', $studentId)->count(),
            'avg_progress' => Enrollment::where('user_id', $studentId)->where('status', 'active')->avg('progress_percentage') ?? 0,
        ];

        $activeCourses = Enrollment::where('user_id', $studentId)
            ->where('status', 'active')
            ->with('course.instructor')
            ->latest()
            ->take(6)
            ->get();

        $upcomingSessions = LiveSession::whereHas('course.enrollments', function($q) use ($studentId) {
            $q->where('user_id', $studentId)->where('status', 'active');
        })
        ->where('scheduled_at', '>', now())
        ->with('course')
        ->orderBy('scheduled_at')
        ->take(5)
        ->get();

        $recommendedCourses = Course::where('is_published', true)
            ->whereNotIn('id', function($query) use ($studentId) {
                $query->select('course_id')->from('enrollments')->where('user_id', $studentId);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('edutech.student.dashboard', compact('stats', 'activeCourses', 'upcomingSessions', 'recommendedCourses'));
    }

    // My Courses
    public function myCourses(Request $request)
    {
        $studentId = session('edutech_user_id');
        $query = Enrollment::where('user_id', $studentId)->with('course.instructor');

        $status = $request->get('status', 'active');
        if ($status != 'all') {
            $query->where('status', $status);
        }

        $enrollments = $query->latest()->paginate(12);

        return view('edutech.student.my-courses', compact('enrollments', 'status'));
    }

    // Enroll Course
    public function enrollCourse(Request $request, $courseId)
    {
        $studentId = session('edutech_user_id');
        $course = Course::findOrFail($courseId);

        $existingEnrollment = Enrollment::where('user_id', $studentId)->where('course_id', $courseId)->first();

        if ($existingEnrollment) {
            return back()->with('error', 'Anda sudah terdaftar di course ini!');
        }

        if ($course->isFull()) {
            return back()->with('error', 'Course sudah penuh!');
        }

        Enrollment::create([
            'user_id' => $studentId,
            'course_id' => $courseId,
            'status' => $course->price > 0 ? 'pending' : 'active',
            'paid_amount' => $course->price,
            'enrolled_at' => $course->price > 0 ? null : now(),
        ]);

        if ($course->price > 0) {
            return redirect()->route('edutech.student.payment', $courseId)
                ->with('success', 'Silakan lakukan pembayaran untuk mengakses course!');
        } else {
            return redirect()->route('edutech.student.my-courses')
                ->with('success', 'Selamat! Anda berhasil bergabung dengan course ini!');
        }
    }

    // Certificates
    public function certificates()
    {
        $studentId = session('edutech_user_id');

        $certificates = Certificate::where('user_id', $studentId)
            ->with('course')
            ->latest()
            ->paginate(12);

        return view('edutech.student.certificates', compact('certificates'));
    }
}