<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'course.instructor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->whereNotNull('completed_at');
            } elseif ($request->status === 'in-progress') {
                $query->whereNull('completed_at')->where('progress', '>', 0);
            } elseif ($request->status === 'pending') {
                $query->where('status', 'pending');
            }
        }

        $enrollments = $query->latest()->paginate(20);
        $courses = Course::orderBy('title')->get();

        $stats = [
            'total_enrollments' => Enrollment::count(),
            'in_progress' => Enrollment::whereNull('completed_at')->where('progress_percentage', '>', 0)->count(),
            'completed' => Enrollment::whereNotNull('completed_at')->count(),
            'pending' => Enrollment::where('status', 'pending')->count(),
        ];

        return view('edutech.admin.enrollments.index', compact('enrollments', 'courses', 'stats'));
    }

    public function show($id)
    {
        $enrollment = Enrollment::with([
            'student',
            'course.modules.lessons',
            'course.instructor'
        ])->findOrFail($id);

        $progressDetails = [
            'total_lessons' => $enrollment->course->modules->sum(function($module) {
                return $module->lessons->count();
            }),
            'completed_lessons' => $enrollment->completedLessons()->count(),
            'remaining_lessons' => 0,
        ];

        $progressDetails['remaining_lessons'] = $progressDetails['total_lessons'] - $progressDetails['completed_lessons'];

        return view('edutech.admin.enrollments.show', compact('enrollment', 'progressDetails'));
    }

    public function approve($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = 'approved';
        $enrollment->approved_at = now();
        $enrollment->save();

        return redirect()->back()
            ->with('success', 'Enrollment approved successfully!');
    }

    public function reject($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = 'rejected';
        $enrollment->save();

        return redirect()->back()
            ->with('success', 'Enrollment rejected!');
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return redirect()->route('edutech.admin.enrollments')
            ->with('success', 'Enrollment deleted successfully!');
    }
}