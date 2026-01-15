<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $studentId = session('edutech_user_id');

        $stats = [
            'active_courses' => Enrollment::where('student_id', $studentId)
                ->where('status', 'active')
                ->count(),
            'completed_courses' => Enrollment::where('student_id', $studentId)
                ->where('status', 'completed')
                ->count(),
            'certificates' => Enrollment::where('student_id', $studentId)
                ->whereNotNull('certificate_issued_at')
                ->count(),
            'avg_progress' => Enrollment::where('student_id', $studentId)
                ->avg('progress_percentage') ?? 0,
        ];

        $activeCourses = Enrollment::with('course.instructor')
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->where('payment_status', 'paid')
            ->latest()
            ->take(6)
            ->get();

        $recommendedCourses = Course::where('is_published', true)
            ->whereDoesntHave('enrollments', function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('edutech.student.dashboard', compact(
            'stats',
            'activeCourses',
            'recommendedCourses'
        ));
    }
}