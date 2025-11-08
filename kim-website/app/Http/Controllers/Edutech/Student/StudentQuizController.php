<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentQuizController extends Controller
{
    /**
     * Start a quiz attempt
     */
    public function start($quizId)
    {
        $studentId = session('edutech_user_id');
        
        // Get quiz with course
        $quiz = Quiz::with(['course', 'questions' => function($query) {
            $query->orderBy('order');
        }])
        ->where('is_active', true)
        ->findOrFail($quizId);

        // Check if student is enrolled in the course
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->where('status', 'active')
            ->firstOrFail();

        // Check if quiz is post-test and student has enough progress
        if ($quiz->type == 'post_test' && $enrollment->progress_percentage < 80) {
            return redirect()
                ->back()
                ->with('error', 'You need to complete at least 80% of the course to take the post-test.');
        }

        // Check if user has attempts left
        $attemptCount = QuizAttempt::where('user_id', $studentId)
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()
                ->back()
                ->with('error', 'You have reached the maximum attempts for this quiz.');
        }
        // Create new attempt
        $attempt = QuizAttempt::create([
            'user_id' => $studentId,
            'quiz_id' => $quiz->id,
            'attempt_number' => $attemptCount + 1,
            'started_at' => Carbon::now(),
            'score' => 0,
            'is_passed' => false,
        ]);
        dd($quiz, $attempt,$enrollment);

        return view('edutech.student.quiz-take', compact('quiz', 'attempt', 'enrollment'));
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, $quizId)
    {
        $studentId = session('edutech_user_id');

        // Get the active attempt
        $attempt = QuizAttempt::where('user_id', $studentId)
            ->where('quiz_id', $quizId)
            ->whereNull('submitted_at')
            ->latest()
            ->firstOrFail();

        // Get quiz with questions
        $quiz = Quiz::with('questions')->findOrFail($quizId);

        // Validate answers
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        // Calculate score
        $totalPoints = 0;
        $earnedPoints = 0;
        $detailedAnswers = [];

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            $userAnswer = $request->answers[$question->id] ?? null;
            
            $isCorrect = false;
            if ($userAnswer) {
                // For essay questions, give full points (instructor will grade later)
                if ($question->type == 'essay') {
                    $earnedPoints += $question->points;
                    $isCorrect = true;
                } else {
                    // Check if answer is correct
                    if (strtolower(trim($userAnswer)) == strtolower(trim($question->correct_answer))) {
                        $earnedPoints += $question->points;
                        $isCorrect = true;
                    }
                }
            }

            $detailedAnswers[$question->id] = [
                'answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'points_earned' => $isCorrect ? $question->points : 0,
            ];
        }

        // Calculate percentage score
        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $isPassed = $score >= $quiz->passing_score;

        // Update attempt
        $attempt->update([
            'answers' => $detailedAnswers,
            'score' => $score,
            'is_passed' => $isPassed,
            'submitted_at' => Carbon::now(),
        ]);

        // Update enrollment if post-test is passed
        if ($quiz->type == 'post_test' && $isPassed) {
            $enrollment = Enrollment::where('student_id', $studentId)
                ->where('course_id', $quiz->course_id)
                ->first();

            if ($enrollment && $enrollment->progress_percentage >= 100) {
                $enrollment->update([
                    'status' => 'completed',
                    'completed_at' => Carbon::now(),
                    'certificate_issued_at' => Carbon::now(),
                ]);
            }
        }

        return redirect()
            ->route('edutech.student.quiz.result', ['quiz' => $quiz->id, 'attempt' => $attempt->id])
            ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Show quiz result
     */
    public function result($quizId, $attemptId)
    {
        $studentId = session('edutech_user_id');

        $attempt = QuizAttempt::where('user_id', $studentId)
            ->where('quiz_id', $quizId)
            ->where('id', $attemptId)
            ->firstOrFail();

        $quiz = Quiz::with('questions')->findOrFail($quizId);

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->first();

        return view('edutech.student.quiz-result', compact('quiz', 'attempt', 'enrollment'));
    }

    /**
     * View all quiz attempts history
     */
    public function history()
    {
        $studentId = session('edutech_user_id');

        $attempts = QuizAttempt::with(['quiz.course'])
            ->where('user_id', $studentId)
            ->whereNotNull('submitted_at')
            ->latest('submitted_at')
            ->paginate(20);

        return view('edutech.student.quiz-history', compact('attempts'));
    }
}