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
    public function start($quiz) // ✅ UBAH dari $quizId ke $quiz (sesuai route binding)
    {
        $studentId = session('edutech_user_id');
        
        // ✅ UBAH query karena $quiz sudah berupa ID dari route parameter
        // Jika route binding otomatis, Laravel akan inject model Quiz
        // Jika tidak, kita perlu findOrFail manual
        $quiz = Quiz::with(['course', 'questions' => function($query) {
            $query->orderBy('order');
        }])
        ->where('is_active', true)
        ->findOrFail($quiz); // ✅ $quiz di sini adalah ID dari route

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
            'answers' => json_encode([]),
        ]);
        
        // ✅ HAPUS dd() - ini yang bikin halaman stuck!
        // dd($quiz, $attempt, $enrollment);

        return view('edutech.student.quiz-take', compact('quiz', 'attempt', 'enrollment'));
    }

    /**
     * Submit quiz answers
     */
public function submit(Request $request, $quiz)
{
    $studentId = session('edutech_user_id');

    // Get the active attempt
    $attempt = QuizAttempt::where('user_id', $studentId)
        ->where('quiz_id', $quiz)
        ->whereNull('submitted_at')
        ->latest()
        ->firstOrFail();

    // Get quiz with questions
    $quiz = Quiz::with('questions')->findOrFail($quiz);

    // Validate answers
    $request->validate([
        'answers' => 'required|array',
    ]);

    // Calculate score
    $totalPoints = 0;
    $earnedPoints = 0;
    $detailedAnswers = []; // ✅ Ini akan jadi array, bukan objek

    foreach ($quiz->questions as $question) {
        $totalPoints += $question->points;
        $userAnswer = $request->answers[$question->id] ?? null;
        
        $isCorrect = false;
        $pointsEarned = 0;
        
        if ($userAnswer) {
            // ✅ Cek tipe pertanyaan dengan benar
            if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
                $isCorrect = trim($userAnswer) === trim($question->correct_answer);
            } elseif ($question->type === 'essay') {
                // Essay questions need manual grading
                $isCorrect = false;
            }
            
            if ($isCorrect) {
                $pointsEarned = $question->points;
                $earnedPoints += $question->points;
            }
        }
        
        // ✅ PENTING: Gunakan question->id sebagai KEY
        $detailedAnswers[$question->id] = [
            'question_id' => $question->id,
            'answer' => $userAnswer ?? 'No answer',
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
        'answers' => $detailedAnswers, // ✅ Laravel akan auto-convert ke JSON
        'duration' => $duration,
    ]);

    // Redirect to result page
    return redirect()
        ->route('edutech.student.quiz.result', ['quiz' => $quiz->id, 'attempt' => $attempt->id])
        ->with('success', 'Quiz submitted successfully!');
}

    /**
     * Show quiz result
     */
    public function result($quiz, $attempt) // ✅ UBAH parameter
    {
        $studentId = session('edutech_user_id');
        
        // ✅ Fetch models
        $quiz = Quiz::with(['course', 'questions'])->findOrFail($quiz);
        $attempt = QuizAttempt::where('user_id', $studentId)
            ->where('id', $attempt)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->where('status', 'active')
            ->firstOrFail();

    // dd([
    //     'attempt_answers' => $attempt->answers,
    //     'is_array' => is_array($attempt->answers),
    //     'first_question_id' => $quiz->questions->first()->id ?? null,
    //     'answer_for_first' => $attempt->answers[$quiz->questions->first()->id] ?? 'not found'
    // ]);
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