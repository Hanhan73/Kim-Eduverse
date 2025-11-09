<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'modules']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $isPublished = $request->status === 'published';
            $query->where('is_published', $isPublished);
        }

        if ($request->filled('instructor')) {
            $query->where('instructor_id', $request->instructor);
        }

        $courses = $query->withCount(['enrollments', 'modules'])
            ->latest()
            ->paginate(12);

        $instructors = User::where('role', 'instructor')->get();

        $stats = [
            'total_courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'draft_courses' => Course::where('is_published', false)->count(),
            'total_enrollments' => Enrollment::count(),
        ];

        return view('edutech.admin.courses.index', compact('courses', 'instructors', 'stats'));
    }

    public function show($id)
    {
        $course = Course::with(['instructor', 'modules.lessons', 'enrollments.student'])
            ->findOrFail($id);

        $stats = [
            'total_enrollments' => $course->enrollments->count(),
            'completed' => $course->enrollments->whereNotNull('completed_at')->count(),
            'in_progress' => $course->enrollments->whereNull('completed_at')->where('progress', '>', 0)->count(),
            'average_progress' => $course->enrollments->avg('progress'),
        ];

        return view('edutech.admin.courses.show', compact('course', 'stats'));
    }

    public function edit($id)
    {
        $course = Course::with('modules.lessons')->findOrFail($id);
        $instructors = User::where('role', 'instructor')->get();
        
        return view('edutech.admin.courses.edit', compact('course', 'instructors'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'is_published' => 'boolean',
        ]);

        $course->update($validated);

        return redirect()->route('edutech.admin.courses.show', $course->id)
            ->with('success', 'Course updated successfully!');
    }

    public function togglePublish($id)
    {
        $course = Course::findOrFail($id);
        $course->is_published = !$course->is_published;
        $course->save();

        $status = $course->is_published ? 'published' : 'unpublished';
        return redirect()->back()
            ->with('success', "Course {$status} successfully!");
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        if ($course->enrollments()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete course with active enrollments!');
        }

        $course->delete();

        return redirect()->route('edutech.admin.courses')
            ->with('success', 'Course deleted successfully!');
    }
}