<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\LiveSession;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    /**
     * Show learning page with tabs: Lessons, Quizzes, Live Sessions
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

        $userId = session('edutech_user_id');
        $isInstructor = ($course->instructor_id == $userId);

        if (!$isInstructor) {
            // Regular student - check enrollment & payment
            $enrollment = Enrollment::where('student_id', $userId)
                ->where('course_id', $course->id)
                ->first();

            if (!$enrollment) {
                return redirect()
                    ->route('edutech.courses.detail', $slug)
                    ->with('error', 'Anda harus mendaftar terlebih dahulu');
            }

            if ($course->price > 0 && $enrollment->payment_status !== 'paid') {
                return redirect()
                    ->route('edutech.payment.show', $enrollment->id)
                    ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu');
            }
        }  else {
            // INSTRUCTOR PREVIEW MODE
            $enrollment = new \stdClass();
            $enrollment->id = 0;
            $enrollment->student_id = $studentId;
            $enrollment->course_id = $course->id;
            $enrollment->progress_percentage = 0;
            $enrollment->status = 'preview';
            $enrollment->payment_status = 'paid';  // ← FIX!
            $enrollment->enrolled_at = now();
        }

        // Check payment status for paid courses
        if ($course->price > 0 && $enrollment->payment_status !== 'paid') {
            return redirect()
                ->route('edutech.payment.show', $enrollment->id)
                ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu');
        }

        // Get current lesson
        $currentLesson = null;
        if ($request->has('lesson')) {
            $currentLesson = Lesson::find($request->lesson);
        }
        
        // If no lesson, get first lesson
        if (!$currentLesson && $course->modules->count() > 0) {
            $firstModule = $course->modules->first();
            if ($firstModule && $firstModule->lessons->count() > 0) {
                $currentLesson = $firstModule->lessons->first();
            }
        }

        // Get completed lessons
        $completedLessons = LessonCompletion::where('user_id', $studentId)
            ->whereHas('lesson.module', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        // Get course quizzes (Pre-test & Post-test)
        $preTest = Quiz::where('course_id', $course->id)
            ->where('type', 'pre_test')
            ->where('is_active', true)
            ->with('questions')
            ->first();

        $postTest = Quiz::where('course_id', $course->id)
            ->where('type', 'post_test')
            ->where('is_active', true)
            ->with('questions')
            ->first();

        // Check if user has attempted tests
        $preTestAttempt = null;
        $postTestAttempt = null;

        if ($preTest) {
            $preTestAttempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $preTest->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        if ($postTest) {
            $postTestAttempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $postTest->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // Get live sessions
        $liveSessions = LiveSession::where('course_id', $course->id)
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $pastSessions = LiveSession::where('course_id', $course->id)
            ->where('scheduled_at', '<', now())
            ->orderBy('scheduled_at', 'desc')
            ->take(5)
            ->get();

        return view('edutech.student.learn', compact(
            'course',
            'enrollment',
            'currentLesson',
            'completedLessons',
            'preTest',
            'postTest',
            'preTestAttempt',
            'postTestAttempt',
            'liveSessions',
            'pastSessions',
            'isInstructor'
        ));
    }

    /**
     * Mark lesson as complete
     */
    public function completeLesson($lessonId)
    {
        $studentId = session('edutech_user_id');

        $lesson = Lesson::findOrFail($lessonId);

        LessonCompletion::updateOrCreate(
            [
                'user_id' => $studentId,
                'lesson_id' => $lessonId,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        // Update enrollment progress
        $this->updateEnrollmentProgress($studentId, $lesson->module->course_id);

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as complete!',
        ]);
    }

    /**
     * Get next lesson
     */
    public function nextLesson($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $module = $lesson->module;
        $course = $module->course;

        // Try to get next lesson in same module
        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        // If no next lesson in module, try first lesson of next module
        if (!$nextLesson) {
            $nextModule = $course->modules()
                ->where('order', '>', $module->order)
                ->orderBy('order', 'asc')
                ->first();

            if ($nextModule) {
                $nextLesson = $nextModule->lessons()
                    ->orderBy('order', 'asc')
                    ->first();
            }
        }

        if ($nextLesson) {
            return redirect()->route('edutech.courses.learn', [
                'slug' => $course->slug,
                'lesson' => $nextLesson->id,
            ]);
        }

        return redirect()->route('edutech.courses.learn', $course->slug)
            ->with('success', 'Selamat! Anda telah menyelesaikan semua lesson!');
    }

    /**
     * Update enrollment progress percentage
     */
    private function updateEnrollmentProgress($studentId, $courseId)
    {
        $course = Course::with('modules.lessons')->find($courseId);
        $totalLessons = $course->modules->sum(function($module) {
            return $module->lessons->count();
        });

        if ($totalLessons == 0) return;

        $completedLessons = LessonCompletion::where('user_id', $studentId)
            ->whereHas('lesson.module', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('is_completed', true)
            ->count();

        $progressPercentage = ($completedLessons / $totalLessons) * 100;

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if ($enrollment) {
            $enrollment->update([
                'progress_percentage' => round($progressPercentage, 2),
                'status' => $progressPercentage >= 100 ? 'completed' : 'active',
                'completed_at' => $progressPercentage >= 100 ? now() : null,
            ]);
        }
    }
}