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
     * Start a quiz attempt with NEW SYSTEM checks
     */
    public function start($quiz)
    {
        $studentId = session('edutech_user_id');
        
        $quiz = Quiz::with(['course', 'questions' => function($query) {
            $query->orderBy('order');
        }])
        ->where('is_active', true)
        ->findOrFail($quiz);

        // Check if student is enrolled in the course
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->where('status', 'active')
            ->firstOrFail();

        // ===== NEW SYSTEM: POST-TEST REQUIREMENT =====
        // Post-test can ONLY be accessed if ALL modules are completed
        if ($quiz->type == 'post_test') {
            if (!$enrollment->canAccessPostTest()) {
                return redirect()
                    ->back()
                    ->with('error', 'You must complete ALL course modules before taking the post-test!');
            }
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
            'answers' => json_encode([]),
        ]);
        
        return view('edutech.student.quiz-take', compact('quiz', 'attempt', 'enrollment'));
    }

    /**
     * Submit quiz and UPDATE PROGRESS with NEW SYSTEM
     */
    public function submit(Request $request, $quiz)
    {
        $studentId = session('edutech_user_id');
        
        $quiz = Quiz::with(['course', 'questions'])->findOrFail($quiz);
        
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->firstOrFail();

        // Find active attempt
        $attempt = QuizAttempt::where('user_id', $studentId)
            ->where('quiz_id', $quiz->id)
            ->whereNull('submitted_at')
            ->latest()
            ->firstOrFail();

        // Process answers
        $answers = $request->input('answers', []);
        $detailedAnswers = [];
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = false;
            $pointsEarned = 0;

            // Check answer based on question type
            if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
                $isCorrect = ($userAnswer == $question->correct_answer);
                $pointsEarned = $isCorrect ? $question->points : 0;
            }

            $earnedPoints += $pointsEarned;

            $detailedAnswers[$question->id] = [
                'question' => $question->question,
                'type' => $question->type,
                'answer' => $userAnswer ?: 'No answer',
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
            ];
        }

        // Calculate percentage
        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $isPassed = $score >= $quiz->passing_score;

        // Calculate duration
        $duration = Carbon::now()->diffInMinutes($attempt->started_at);

        // Update attempt
        $attempt->update([
            'submitted_at' => Carbon::now(),
            'score' => $score,
            'is_passed' => $isPassed,
            'answers' => $detailedAnswers,
            'duration' => $duration,
        ]);

        // ===== NEW SYSTEM: UPDATE PROGRESS AFTER QUIZ =====
        // Progress is recalculated including quiz completion
        $enrollment->updateProgress();

        // Redirect to result page
        return redirect()
            ->route('edutech.student.quiz.result', ['quiz' => $quiz->id, 'attempt' => $attempt->id])
            ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Show quiz result
     */
    public function result($quiz, $attempt)
    {
        $studentId = session('edutech_user_id');
        
        $quiz = Quiz::with(['course', 'questions'])->findOrFail($quiz);
        $attempt = QuizAttempt::where('user_id', $studentId)
            ->where('id', $attempt)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->where('status', 'active')
            ->firstOrFail();

        return view('edutech.student.quiz-result', compact('quiz', 'attempt', 'enrollment'));
    }

    /**
     * Show quiz history for student
     */
    public function history()
    {
        $studentId = session('edutech_user_id');
        
        $attempts = QuizAttempt::with(['quiz.course'])
            ->where('user_id', $studentId)
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('edutech.student.quiz-history', compact('attempts'));
    }
}