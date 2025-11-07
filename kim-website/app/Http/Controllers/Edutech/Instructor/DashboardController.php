<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $instructorId = session('edutech_user_id');

        // Get instructor's course IDs
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

        // Basic Stats
        $stats = [
            'total_courses' => Course::where('instructor_id', $instructorId)->count(),
            'total_students' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->distinct('student_id')->count('student_id'),
            'certificates_issued' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->whereNotNull('certificate_issued_at')->count(),
            'avg_rating' => 4.8, // Nanti bisa dari review
            
            // Content Stats
            'total_modules' => Module::whereIn('course_id', $courseIds)->count(),
            'total_lessons' => Lesson::whereHas('module', function($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })->count(),
            'total_quizzes' => Quiz::whereIn('course_id', $courseIds)->count(),
            
            // Active Students This Month
            'active_students_month' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->where('enrolled_at', '>=', now()->startOfMonth())
            ->distinct('student_id')
            ->count('student_id'),
        ];

        // Lesson Type Breakdown
        $lessonBreakdown = Lesson::whereHas('module', function($query) use ($courseIds) {
            $query->whereIn('course_id', $courseIds);
        })
        ->select('type', DB::raw('count(*) as count'))
        ->groupBy('type')
        ->pluck('count', 'type')
        ->toArray();

        $stats['video_lessons'] = $lessonBreakdown['video'] ?? 0;
        $stats['pdf_lessons'] = $lessonBreakdown['pdf'] ?? 0;
        $stats['text_lessons'] = $lessonBreakdown['text'] ?? 0;

        // Quiz Stats
        $quizStats = [
            'total_attempts' => QuizAttempt::whereHas('quiz', function($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })->count(),
            
            'passed_attempts' => QuizAttempt::whereHas('quiz', function($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })->where('is_passed', true)->count(),
            
            'avg_score' => QuizAttempt::whereHas('quiz', function($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })->avg('score') ?? 0,
        ];

        if ($quizStats['total_attempts'] > 0) {
            $quizStats['pass_rate'] = ($quizStats['passed_attempts'] / $quizStats['total_attempts']) * 100;
        } else {
            $quizStats['pass_rate'] = 0;
        }

        // Courses with Details
        $courses = Course::where('instructor_id', $instructorId)
            ->withCount(['enrollments', 'modules'])
            ->with(['modules' => function($query) {
                $query->withCount('lessons');
            }])
            ->latest()
            ->get()
            ->map(function($course) {
                $course->total_lessons = $course->modules->sum('lessons_count');
                
                // Check if course has pre-test and post-test
                $course->has_pretest = Quiz::where('course_id', $course->id)
                    ->where('type', 'pre_test')
                    ->exists();
                $course->has_posttest = Quiz::where('course_id', $course->id)
                    ->where('type', 'post_test')
                    ->exists();
                
                return $course;
            });

        // Recent Quiz Attempts
        $recentQuizAttempts = QuizAttempt::with(['user', 'quiz.course'])
            ->whereHas('quiz', function($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })
            ->latest()
            ->take(10)
            ->get();

        // Recent Students
        $recentStudents = Enrollment::with(['student', 'course'])
            ->whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->latest()
            ->take(10)
            ->get();

        // Quizzes by Course
        $quizzesByCourse = Quiz::whereIn('course_id', $courseIds)
            ->with(['course', 'attempts'])
            ->withCount('attempts')
            ->latest()
            ->get()
            ->groupBy('course_id');

        return view('edutech.instructor.dashboard', compact(
            'stats',
            'quizStats',
            'courses',
            'recentStudents',
            'recentQuizAttempts',
            'quizzesByCourse'
        ));
    }

    /**
     * Show all students enrolled in instructor's courses
     */
    public function students()
    {
        $instructorId = session('edutech_user_id');

        // Get all enrollments from instructor's courses
        $students = Enrollment::with(['student', 'course'])
            ->whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->orderBy('enrolled_at', 'desc')
            ->paginate(20);

        // Stats
        $stats = [
            'total_students' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->distinct('student_id')->count('student_id'),
            
            'active_students' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->where('status', 'active')->distinct('student_id')->count('student_id'),
            
            'completed' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->where('status', 'completed')->count(),
            
            'avg_progress' => Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->avg('progress_percentage') ?? 0,
        ];

        return view('edutech.instructor.students', compact('students', 'stats'));
    }
}