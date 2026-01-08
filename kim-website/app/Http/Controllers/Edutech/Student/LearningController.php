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
     * Show learning page dengan SISTEM BARU
     */
    public function show(Request $request, $slug)
    {
        $studentId = session('edutech_user_id');
        
        $course = Course::where('slug', $slug)
            ->with([
                'modules.lessons',
                'modules.quiz', // Quiz per module
                'instructor',
                'quizzes' => function($query) {
                    $query->where('is_active', true)->with('questions');
                }
            ])
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $isInstructor = $course->instructor_id == $studentId;

        // Set enrolled_at
        if (!$enrollment->enrolled_at) {
            $enrollment->enrolled_at = now();
            $enrollment->save();
        }

        // Check payment
        if ($course->price > 0 && $enrollment->payment_status !== 'paid') {
            return redirect()
                ->route('edutech.payment.show', $enrollment->id)
                ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu');
        }

        // === GET QUIZZES ===
        $preTest = $course->quizzes->where('type', 'pre_test')->first();
        $postTest = $course->quizzes->where('type', 'post_test')->first();
        
        // Get quiz attempts
        $preTestAttempt = null;
        $postTestAttempt = null;
        
        if ($preTest) {
            $preTestAttempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $preTest->id)
                ->where('is_passed', true)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        if ($postTest) {
            $postTestAttempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $postTest->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        // Check access
        $canAccessMaterials = $enrollment->canAccessMaterials();
        $canAccessPostTest = $enrollment->canAccessPostTest();
        
        // Check jika ada quiz yang sedang ongoing (tidak boleh akses module lain)
        $ongoingQuiz = QuizAttempt::where('user_id', $studentId)
            ->whereNull('submitted_at')
            ->whereHas('quiz', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->with('quiz')
            ->first();

        // Get current lesson/quiz
        $currentView = 'lesson'; // 'lesson' atau 'quiz'
        $currentLesson = null;
        $currentQuiz = null;
        $currentAttempt = null;
        
        if ($request->has('quiz')) {
            // User mau lihat quiz
            $currentQuiz = Quiz::with('questions')->findOrFail($request->quiz);
            $currentView = 'quiz';
            
            // Check if quiz sudah di-attempt tapi belum submit
            $currentAttempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $currentQuiz->id)
                ->whereNull('submitted_at')
                ->latest()
                ->first();
        } elseif ($canAccessMaterials) {
            // User lihat lesson
            if ($request->has('lesson')) {
                $currentLesson = Lesson::find($request->lesson);
            }
            
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

        // Get passed quizzes (untuk badge di sidebar)
        $passedQuizzes = QuizAttempt::where('user_id', $studentId)
            ->whereHas('quiz', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->where('is_passed', true)
            ->pluck('quiz_id')
            ->toArray();

        // Live sessions
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
            'currentView',
            'currentLesson',
            'currentQuiz',
            'currentAttempt',
            'completedLessons',
            'passedQuizzes',
            'preTest',
            'postTest',
            'preTestAttempt',
            'postTestAttempt',
            'canAccessMaterials',
            'canAccessPostTest',
            'ongoingQuiz',
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
        $courseId = $lesson->module->course_id;

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment->canAccessMaterials()) {
            return response()->json([
                'success' => false,
                'message' => 'You must pass the pre-test first!',
            ], 403);
        }

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

        $newProgress = $enrollment->updateProgress();

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as complete!',
            'progress' => $newProgress,
        ]);
    }

    /**
     * Next lesson (dengan cek quiz module)
     */
    public function nextLesson($lessonId)
    {
        $studentId = session('edutech_user_id');
        
        $lesson = Lesson::findOrFail($lessonId);
        $module = $lesson->module;
        $course = $module->course;

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment->canAccessMaterials()) {
            return redirect()
                ->route('edutech.courses.learn', $course->slug)
                ->with('error', 'You must pass the pre-test first!');
        }

        // Try next lesson in same module
        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextLesson) {
            return redirect()->route('edutech.courses.learn', [
                'slug' => $course->slug,
                'lesson' => $nextLesson->id,
            ]);
        }

        // No more lessons in this module
        // Check if module has quiz
        $moduleQuiz = Quiz::where('module_id', $module->id)
            ->where('type', 'module_quiz')
            ->where('is_active', true)
            ->first();

        if ($moduleQuiz) {
            // Cek sudah lulus belum
            $passed = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $moduleQuiz->id)
                ->where('is_passed', true)
                ->exists();

            if (!$passed) {
                return redirect()->route('edutech.courses.learn', [
                    'slug' => $course->slug,
                    'quiz' => $moduleQuiz->id,
                ])->with('info', 'Complete the module quiz to continue!');
            }
        }

        // Try first lesson of next module
        $nextModule = $course->modules()
            ->where('order', '>', $module->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextModule) {
            // Check if can access next module
            if (!$enrollment->canAccessModule($nextModule->id)) {
                return redirect()->route('edutech.courses.learn', $course->slug)
                    ->with('error', 'Complete previous module first (including quiz if any)!');
            }

            $nextLesson = $nextModule->lessons()
                ->orderBy('order', 'asc')
                ->first();

            if ($nextLesson) {
                return redirect()->route('edutech.courses.learn', [
                    'slug' => $course->slug,
                    'lesson' => $nextLesson->id,
                ]);
            }
        }

        return redirect()->route('edutech.courses.learn', $course->slug)
            ->with('success', 'Congratulations! You completed all lessons!');
    }
}