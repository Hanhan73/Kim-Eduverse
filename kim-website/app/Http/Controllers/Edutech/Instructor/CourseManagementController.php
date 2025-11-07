<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseManagementController extends Controller
{
    /**
     * Show all instructor's courses
     */
    public function index()
    {
        $instructorId = session('edutech_user_id');

        $courses = Course::where('instructor_id', $instructorId)
            ->withCount(['modules', 'enrollments'])
            ->latest()
            ->get();

        return view('edutech.instructor.courses.index', compact('courses'));
    }

    /**
     * Show create course form
     */
    public function create()
    {
        $categories = $this->getCategories();
        return view('edutech.instructor.courses.create', compact('categories'));
    }

    /**
     * Store new course
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $instructorId = session('edutech_user_id');

        $data = $request->all();
        $data['instructor_id'] = $instructorId;
        $data['slug'] = Str::slug($request->title);
        $data['is_published'] = false; // Draft by default

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course = Course::create($data);

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Course berhasil dibuat! Sekarang tambahkan modules & lessons.');
    }

    /**
     * Show edit course form
     */
    public function edit($id)
    {
        $course = Course::with(['modules.lessons'])
            ->where('instructor_id', session('edutech_user_id'))
            ->findOrFail($id);

        $categories = $this->getCategories();

        return view('edutech.instructor.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update course
     */
    public function update(Request $request, $id)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('thumbnail');
        $data['slug'] = Str::slug($request->title);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Course berhasil diupdate!');
    }

    /**
     * Publish/Unpublish course
     */
    public function togglePublish($id)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($id);

        $course->update([
            'is_published' => !$course->is_published,
        ]);

        $status = $course->is_published ? 'published' : 'unpublished';

        return redirect()
            ->back()
            ->with('success', "Course berhasil {$status}!");
    }

    /**
     * Delete course
     */
    public function destroy($id)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($id);

        $course->delete();

        return redirect()
            ->route('edutech.instructor.courses.index')
            ->with('success', 'Course berhasil dihapus!');
    }

    // ========================================
    // MODULE MANAGEMENT
    // ========================================

    /**
     * Store new module
     */
    public function storeModule(Request $request, $courseId)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($courseId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $module = Module::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes ?? 0,
            'order' => Module::where('course_id', $course->id)->max('order') + 1,
            'is_published' => true,
        ]);

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Module berhasil ditambahkan!');
    }

    /**
     * Update module
     */
    public function updateModule(Request $request, $courseId, $moduleId)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($courseId);

        $module = Module::where('course_id', $course->id)->findOrFail($moduleId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $module->update($request->only(['title', 'description', 'duration_minutes']));

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Module berhasil diupdate!');
    }

    /**
     * Delete module
     */
    public function destroyModule($courseId, $moduleId)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($courseId);

        $module = Module::where('course_id', $course->id)->findOrFail($moduleId);
        $module->delete();

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Module berhasil dihapus!');
    }

    // ========================================
    // LESSON MANAGEMENT
    // ========================================

    /**
     * Store new lesson
     */
    public function storeLesson(Request $request, $courseId, $moduleId)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($courseId);

        $module = Module::where('course_id', $course->id)->findOrFail($moduleId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,pdf',
            'video_url' => 'nullable|url',
            'file_path' => 'nullable|url',
            'content' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        // Extract YouTube video ID from URL
        $videoId = null;
        if ($request->video_url) {
            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $request->video_url, $matches);
            $videoId = $matches[1] ?? null;
        }

        Lesson::create([
            'module_id' => $module->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'video_url' => $request->video_url,
            'video_id' => $videoId,
            'file_path' => $request->file_path,
            'content' => $request->content,
            'duration_minutes' => $request->duration_minutes ?? 0,
            'order' => Lesson::where('module_id', $module->id)->max('order') + 1,
            'is_published' => true,
        ]);

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Lesson berhasil ditambahkan!');
    }

    /**
     * Update lesson
     */
    public function updateLesson(Request $request, $courseId, $moduleId, $lessonId)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($courseId);

        $module = Module::where('course_id', $course->id)->findOrFail($moduleId);
        $lesson = Lesson::where('module_id', $module->id)->findOrFail($lessonId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,pdf',
            'video_url' => 'nullable|url',
            'file_path' => 'nullable|url',
            'content' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        // Extract YouTube video ID from URL
        $videoId = $lesson->video_id;
        if ($request->video_url) {
            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $request->video_url, $matches);
            $videoId = $matches[1] ?? null;
        }

        $lesson->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'video_url' => $request->video_url,
            'video_id' => $videoId,
            'file_path' => $request->file_path,
            'content' => $request->content,
            'duration_minutes' => $request->duration_minutes ?? 0,
        ]);

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Lesson berhasil diupdate!');
    }

    /**
     * Delete lesson
     */
    public function destroyLesson($courseId, $moduleId, $lessonId)
    {
        $course = Course::where('instructor_id', session('edutech_user_id'))
            ->findOrFail($courseId);

        $module = Module::where('course_id', $course->id)->findOrFail($moduleId);
        $lesson = Lesson::where('module_id', $module->id)->findOrFail($lessonId);
        
        $lesson->delete();

        return redirect()
            ->route('edutech.instructor.courses.edit', $course->id)
            ->with('success', 'Lesson berhasil dihapus!');
    }

    /**
     * Get categories list
     */
    private function getCategories()
    {
        return [
            'Education' => 'Education',
            'Language' => 'Language',
            'Teknologi Informasi' => 'Teknologi Informasi',
            'Desain' => 'Desain',
            'Manajemen dan Teknik Industri' => 'Manajemen dan Teknik Industri',
        ];
    }
}