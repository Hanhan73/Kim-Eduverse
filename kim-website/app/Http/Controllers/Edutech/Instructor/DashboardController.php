<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $instructorId = session('edutech_user_id');

        $stats = [
            'total_courses' => Course::where('instructor_id', $instructorId)->count(),
            'total_students' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->distinct('student_id')->count('student_id'),
            'certificates_issued' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->whereNotNull('certificate_issued_at')->count(),
            'avg_rating' => 4.8, // Nanti bisa dari review
        ];

        $courses = Course::where('instructor_id', $instructorId)
            ->withCount('enrollments')
            ->latest()
            ->get();

        $recentStudents = Enrollment::with(['student', 'course'])
            ->whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->latest()
            ->take(10)
            ->get();

        return view('edutech.instructor.dashboard', compact(
            'stats',
            'courses',
            'recentStudents'
        ));
    }
}