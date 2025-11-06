<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'total_revenue' => Enrollment::sum('paid_amount'),
        ];

        $roleStats = [
            'students' => User::where('role', 'student')->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        $total = $roleStats['students'] + $roleStats['instructors'] + $roleStats['admins'];
        $roleStats['students_percentage'] = $total > 0 ? ($roleStats['students'] / $total) * 100 : 0;
        $roleStats['instructors_percentage'] = $total > 0 ? ($roleStats['instructors'] / $total) * 100 : 0;
        $roleStats['admins_percentage'] = $total > 0 ? ($roleStats['admins'] / $total) * 100 : 0;

        $courseStats = [
            'published' => Course::where('is_published', true)->count(),
            'draft' => Course::where('is_published', false)->count(),
        ];

        $totalCourses = $courseStats['published'] + $courseStats['draft'];
        $courseStats['published_percentage'] = $totalCourses > 0 ? ($courseStats['published'] / $totalCourses) * 100 : 0;
        $courseStats['draft_percentage'] = $totalCourses > 0 ? ($courseStats['draft'] / $totalCourses) * 100 : 0;

        $recentUsers = User::latest()->take(10)->get();
        $recentEnrollments = Enrollment::with(['student', 'course.instructor'])
            ->latest()
            ->take(10)
            ->get();

        return view('edutech.admin.dashboard', compact(
            'stats',
            'roleStats',
            'courseStats',
            'recentUsers',
            'recentEnrollments'
        ));
    }
}