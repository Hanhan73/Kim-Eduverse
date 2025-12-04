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
     * Start quiz attempt
     */
    public function start($quizId)
    {
        $studentId = session('edutech_user_id');
        
        $quiz = Quiz::with(['course', 'module', 'questions' => function($query) {
            // Hapus orderBy('order') untuk memungkinkan pengacakan
            // $query->orderBy('order');
        }])
        ->where('is_active', true)
        ->findOrFail($quizId);

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->where('status', 'active')
            ->firstOrFail();

        // === CHECK ACCESS RULES ===
        
        // 1. Pre-test: bisa langsung diakses
        if ($quiz->type === 'pre_test') {
            // OK, no extra checks
        }
        
        // 2. Module Quiz: harus selesaikan semua lessons di module ini dulu
        elseif ($quiz->type === 'module_quiz') {
            $module = $quiz->module;
            $totalLessons = $module->lessons()->count();
            $completedLessons = \App\Models\LessonCompletion::where('user_id', $studentId)
                ->whereIn('lesson_id', $module->lessons()->pluck('id'))
                ->where('is_completed', true)
                ->count();
                
            if ($totalLessons !== $completedLessons) {
                return redirect()
                    ->back()
                    ->with('error', 'Complete all lessons in this module first!');
            }
            
            // Cek apakah module sebelumnya sudah accessible
            if (!$enrollment->canAccessModule($module->id)) {
                return redirect()
                    ->back()
                    ->with('error', 'Complete previous module first!');
            }
        }
        
        // 3. Post-test: harus selesaikan SEMUA module + quiz
        elseif ($quiz->type === 'post_test') {
            if (!$enrollment->canAccessPostTest()) {
                return redirect()
                    ->back()
                    ->with('error', 'Complete ALL course modules and quizzes before taking the post-test!');
            }
        }

        // Check attempts
        $attemptCount = QuizAttempt::where('user_id', $studentId)
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()
                ->back()
                ->with('error', 'You have reached the maximum attempts for this quiz.');
        }
        
        // Acak urutan pertanyaan
        $shuffledQuestions = $quiz->questions->shuffle();
        
        // Simpan ID pertanyaan dalam urutan acak untuk digunakan saat submit
        $questionOrder = $shuffledQuestions->pluck('id')->toArray();
        
        // Create new attempt
        $attempt = QuizAttempt::create([
            'user_id' => $studentId,
            'quiz_id' => $quiz->id,
            'attempt_number' => $attemptCount + 1,
            'started_at' => Carbon::now(),
            'score' => 0,
            'is_passed' => false,
            'answers' => json_encode([]),
            'question_order' => json_encode($questionOrder), // Tambahkan kolom ini di database
        ]);
        
        // Redirect kembali ke learning page dengan parameter quiz
        return redirect()->route('edutech.courses.learn', [
            'slug' => $quiz->course->slug,
            'quiz' => $quiz->id,
            'attempt' => $attempt->id,
        ]);
    }

    /**
     * Submit quiz
     */
    public function submit(Request $request, $quizId)
    {
        $studentId = session('edutech_user_id');
        
        $quiz = Quiz::with(['course', 'questions'])->findOrFail($quizId);
        
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->firstOrFail();

        // Find active attempt
        $attempt = QuizAttempt::where('user_id', $studentId)
            ->where('quiz_id', $quiz->id)
            ->whereNull('submitted_at')
            ->latest()
            ->firstOrFail();

        // Ambil urutan pertanyaan yang disimpan saat start
        $questionOrder = json_decode($attempt->question_order, true) ?? [];
        
        // Urutkan pertanyaan sesuai dengan yang disimpan
        $orderedQuestions = [];
        foreach ($questionOrder as $questionId) {
            $question = $quiz->questions->where('id', $questionId)->first();
            if ($question) {
                $orderedQuestions[] = $question;
            }
        }
        
        // Jika tidak ada urutan yang tersimpan, gunakan urutan default
        if (empty($orderedQuestions)) {
            $orderedQuestions = $quiz->questions->all();
        }

        // Process answers
        $answers = $request->input('answers', []);
        $detailedAnswers = [];
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($orderedQuestions as $question) {
            $totalPoints += $question->points;
            
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = false;
            $pointsEarned = 0;

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

        // Calculate score
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

        // Update progress
        $enrollment->updateProgress();

        // Redirect ke learning page dengan hasil quiz
        return redirect()
            ->route('edutech.courses.learn', [
                'slug' => $quiz->course->slug,
                'quiz' => $quiz->id,
                'result' => $attempt->id,
            ])
            ->with('success', 'Quiz submitted successfully!');
    }
}