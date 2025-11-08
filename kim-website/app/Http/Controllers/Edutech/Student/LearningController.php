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
     * Show learning page with NEW QUIZ SYSTEM
     */
    public function show(Request $request, $slug)
    {
        $studentId = session('edutech_user_id');
        
        // Get course with all relations
        $course = Course::where('slug', $slug)
            ->with(['modules.lessons', 'instructor', 'quizzes' => function($query) {
                $query->where('is_active', true)->with('questions');
            }])
            ->firstOrFail();

        // Get enrollment
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Check if instructor
        $isInstructor = $course->instructor_id == $studentId;

        // Set enrolled_at if null
        if (!$enrollment->enrolled_at) {
            $enrollment->enrolled_at = now();
            $enrollment->save();
        }

        // Check payment status for paid courses
        if ($course->price > 0 && $enrollment->payment_status !== 'paid') {
            return redirect()
                ->route('edutech.payment.show', $enrollment->id)
                ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu');
        }

        // ===== NEW QUIZ SYSTEM =====
        
        // Get Pre-test & Post-test
        $preTest = $course->quizzes->where('type', 'pre_test')->first();
        $postTest = $course->quizzes->where('type', 'post_test')->first();
        
        // Get quiz attempts
        $preTestAttempt = null;
        $postTestAttempt = null;
        
        if ($preTest) {
            $preTestAttempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $preTest->id)
                ->where('is_passed', true) // Only get passed attempt
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        if ($postTest) {
            $postTestAttempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $postTest->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        // Check if user can access materials (must pass pre-test first)
        $canAccessMaterials = $enrollment->canAccessMaterials();
        
        // Check if user can access post-test (must complete all modules)
        $canAccessPostTest = $enrollment->canAccessPostTest();
        
        // Get current lesson (only if can access materials)
        $currentLesson = null;
        if ($canAccessMaterials) {
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
        }

        // Get completed lessons
        $completedLessons = LessonCompletion::where('user_id', $studentId)
            ->whereHas('lesson.module', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

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
            'canAccessMaterials',
            'canAccessPostTest',
            'liveSessions',
            'pastSessions',
            'isInstructor'
        ));
    }

    /**
     * Mark lesson as complete and UPDATE PROGRESS with NEW SYSTEM
     */
    public function completeLesson($lessonId)
    {
        $studentId = session('edutech_user_id');

        $lesson = Lesson::findOrFail($lessonId);
        $courseId = $lesson->module->course_id;

        // Check if user can access materials (pre-test gate)
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment->canAccessMaterials()) {
            return response()->json([
                'success' => false,
                'message' => 'You must pass the pre-test first to access course materials!',
            ], 403);
        }

        // Mark lesson as complete
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

        // Update enrollment progress using NEW CALCULATION
        $newProgress = $enrollment->updateProgress();

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as complete!',
            'progress' => $newProgress,
        ]);
    }

    /**
     * Get next lesson
     */
    public function nextLesson($lessonId)
    {
        $studentId = session('edutech_user_id');
        
        $lesson = Lesson::findOrFail($lessonId);
        $module = $lesson->module;
        $course = $module->course;

        // Check if user can access materials
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment->canAccessMaterials()) {
            return redirect()
                ->route('edutech.courses.learn', $course->slug)
                ->with('error', 'You must pass the pre-test first to access course materials!');
        }

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
}