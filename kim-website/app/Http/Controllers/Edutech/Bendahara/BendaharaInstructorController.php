<?php

namespace App\Http\Controllers\Edutech\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\InstructorRevenue;
use App\Models\InstructorWithdrawal;
use App\Models\Course;
use App\Models\User;

class BendaharaInstructorController extends Controller
{
    public function index()
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        // Get all instructors with their stats
        $instructors = User::where('role', 'instructor')
            ->get()
            ->map(function ($instructor) {
                $totalRevenue = InstructorRevenue::where('instructor_id', $instructor->id)
                    ->sum('instructor_share');
                $available = InstructorRevenue::where('instructor_id', $instructor->id)
                    ->where('status', 'available')
                    ->sum('instructor_share');
                $withdrawn = InstructorRevenue::where('instructor_id', $instructor->id)
                    ->where('status', 'withdrawn')
                    ->sum('instructor_share');
                $totalCourses = Course::where('instructor_id', $instructor->id)->count();
                $totalSales = InstructorRevenue::where('instructor_id', $instructor->id)->count();

                return [
                    'instructor' => $instructor,
                    'total_revenue' => $totalRevenue,
                    'available' => $available,
                    'withdrawn' => $withdrawn,
                    'total_courses' => $totalCourses,
                    'total_sales' => $totalSales,
                ];
            })
            ->sortByDesc('total_revenue');

        return view('edutech.bendahara.instructors', compact('instructors'));
    }

    public function show($instructorId)
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        $instructor = User::findOrFail($instructorId);

        // Instructor stats
        $stats = [
            'total_revenue' => InstructorRevenue::where('instructor_id', $instructorId)
                ->sum('instructor_share'),
            'available' => InstructorRevenue::where('instructor_id', $instructorId)
                ->where('status', 'available')
                ->sum('instructor_share'),
            'withdrawn' => InstructorRevenue::where('instructor_id', $instructorId)
                ->where('status', 'withdrawn')
                ->sum('instructor_share'),
            'pending' => InstructorRevenue::where('instructor_id', $instructorId)
                ->where('status', 'pending')
                ->sum('instructor_share'),
        ];

        // Revenues
        $revenues = InstructorRevenue::where('instructor_id', $instructorId)
            ->with(['course', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Courses
        $courses = Course::where('instructor_id', $instructorId)
            ->withCount(['enrollments'])
            ->get()
            ->map(function ($course) {
                $sales = InstructorRevenue::where('course_id', $course->id)->count();
                $revenue = InstructorRevenue::where('course_id', $course->id)
                    ->sum('instructor_share');
                
                return [
                    'course' => $course,
                    'sales' => $sales,
                    'revenue' => $revenue,
                ];
            });

        // Withdrawals history
        $withdrawals = InstructorWithdrawal::where('instructor_id', $instructorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('edutech.bendahara.instructor_detail', compact('instructor', 'stats', 'revenues', 'courses', 'withdrawals'));
    }
}