<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class MyCourseController extends Controller
{
    public function index()
    {
        $studentId = session('edutech_user_id');

        $activeCourses = Enrollment::with(['course.instructor'])
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->latest()
            ->get();

        $completedCourses = Enrollment::with(['course.instructor'])
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->latest()
            ->get();

        return view('edutech.student.my-courses', compact('activeCourses', 'completedCourses'));
    }
}