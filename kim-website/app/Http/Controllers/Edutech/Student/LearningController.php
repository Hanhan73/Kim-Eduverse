<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    /**
     * Show learning page
     */
    public function show($slug, Request $request)
    {
        // Check if user logged in
        if (!session()->has('edutech_user_id')) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $studentId = session('edutech_user_id');

        // Load course with all relationships
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['instructor', 'modules.lessons'])
            ->firstOrFail();

        // Check if enrolled
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()
                ->route('edutech.courses.detail', $slug)
                ->with('error', 'Anda harus mendaftar terlebih dahulu');
        }

        // Check payment status for paid courses
        if ($course->price > 0 && $enrollment->payment_status !== 'paid') {
            return redirect()
                ->route('edutech.payment.show', $enrollment->id)
                ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu');
        }

        // Get specific lesson or first lesson
        $currentLesson = null;
        
        if ($request->has('lesson')) {
            $currentLesson = Lesson::find($request->lesson);
        }
        
        // If no lesson specified, get first lesson from first module
        if (!$currentLesson && $course->modules->count() > 0) {
            $firstModule = $course->modules->first();
            if ($firstModule && $firstModule->lessons->count() > 0) {
                $currentLesson = $firstModule->lessons->first();
            }
        }

        // Get lesson completion status
        $completedLessons = LessonCompletion::where('user_id', $studentId)
            ->whereIn('lesson_id', $course->modules->pluck('lessons')->flatten()->pluck('id'))
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        return view('edutech.student.learn', compact('course', 'enrollment', 'currentLesson', 'completedLessons'));
    }

    /**
     * Mark lesson as complete
     */
    public function completeLesson(Request $request, $lessonId)
    {
        $studentId = session('edutech_user_id');
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);

        // Check if enrolled
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $lesson->module->course_id)
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        // Mark lesson as completed
        $completion = LessonCompletion::updateOrCreate(
            [
                'user_id' => $studentId,
                'lesson_id' => $lessonId,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
                'watch_duration' => $request->watch_duration ?? 0,
            ]
        );

        // Update enrollment progress
        $this->updateEnrollmentProgress($enrollment);

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as complete',
            'progress' => $enrollment->fresh()->progress_percentage,
        ]);
    }

    /**
     * Update video progress
     */
    public function updateProgress(Request $request, $lessonId)
    {
        $studentId = session('edutech_user_id');

        LessonCompletion::updateOrCreate(
            [
                'user_id' => $studentId,
                'lesson_id' => $lessonId,
            ],
            [
                'watch_duration' => $request->watch_duration ?? 0,
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Get next lesson
     */
    public function nextLesson($lessonId)
    {
        $lesson = Lesson::with('module.lessons')->findOrFail($lessonId);
        $lessons = $lesson->module->lessons;

        // Find current lesson index
        $currentIndex = $lessons->search(function ($item) use ($lessonId) {
            return $item->id == $lessonId;
        });

        // Get next lesson
        if ($currentIndex !== false && isset($lessons[$currentIndex + 1])) {
            $nextLesson = $lessons[$currentIndex + 1];
            return response()->json([
                'success' => true,
                'next_lesson' => [
                    'id' => $nextLesson->id,
                    'title' => $nextLesson->title,
                    'url' => route('edutech.courses.learn', [
                        'slug' => $lesson->module->course->slug,
                        'lesson' => $nextLesson->id
                    ]),
                ],
            ]);
        }

        // No more lessons in this module
        return response()->json([
            'success' => false,
            'message' => 'Ini lesson terakhir di modul ini',
        ]);
    }

    /**
     * Calculate and update enrollment progress
     */
    private function updateEnrollmentProgress($enrollment)
    {
        $course = $enrollment->course()->with('modules.lessons')->first();
        
        // Count total lessons
        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons->count();
        }

        if ($totalLessons === 0) {
            return;
        }

        // Count completed lessons
        $completedLessons = LessonCompletion::where('user_id', $enrollment->student_id)
            ->whereIn('lesson_id', $course->modules->pluck('lessons')->flatten()->pluck('id'))
            ->where('is_completed', true)
            ->count();

        // Calculate percentage
        $percentage = round(($completedLessons / $totalLessons) * 100);

        // Update enrollment
        $enrollment->update([
            'progress_percentage' => $percentage,
        ]);

        // Auto complete course if 100%
        if ($percentage >= 100 && $enrollment->status !== 'completed') {
            $enrollment->markAsCompleted();
        }
    }
}