<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StudentQuizController extends Controller
{
    public function start($quizId)
    {
        try {
            // Log untuk debugging
            Log::info('Attempting to start quiz with ID: ' . $quizId);

            $studentId = session('edutech_user_id');

            if (!$studentId) {
                Log::error('Student ID not found in session');
                return redirect()->back()->with('error', 'Session expired. Please login again.');
            }

            Log::info('Student ID: ' . $studentId);

            // Coba cari quiz dengan ID atau slug
            $quiz = Quiz::with(['course', 'module', 'questions' => function ($query) {}])
                ->where('is_active', true)
                ->where(function ($query) use ($quizId) {
                    $query->where('id', $quizId);
                })
                ->first();

            if (!$quiz) {
                Log::error('Quiz not found with ID: ' . $quizId);
                return redirect()->back()->with('error', 'Quiz not found or inactive.');
            }

            Log::info('Quiz found: ' . $quiz->id . ' - ' . $quiz->title);
            Log::info('Quiz randomize_questions setting: ' . ($quiz->randomize_questions ? 'true' : 'false'));

            // Untuk pre-test, cari enrollment dengan status inactive
            if ($quiz->type === 'pre_test') {
                $enrollment = Enrollment::where('student_id', $studentId)
                    ->where('course_id', $quiz->course_id)
                    ->where('status', 'inactive')
                    ->first();
            } else {
                // Untuk quiz lainnya, cari enrollment dengan status active
                $enrollment = Enrollment::where('student_id', $studentId)
                    ->where('course_id', $quiz->course_id)
                    ->where('status', 'active')
                    ->first();
            }

            if (!$enrollment) {
                Log::error('Enrollment not found for student: ' . $studentId . ' in course: ' . $quiz->course_id);
                return redirect()->back()->with('error', 'You are not enrolled in this course.');
            }

            Log::info('Enrollment found: ' . $enrollment->id);

            // === CHECK ACCESS RULES ===

            // 1. Pre-test: bisa langsung diakses
            if ($quiz->type === 'pre_test') {
                // OK, no extra checks
            }

            // 2. Module Quiz: harus selesaikan semua lessons di module ini dulu
            elseif ($quiz->type === 'module_quiz') {
                $module = $quiz->module;
                if (!$module) {
                    Log::error('Module not found for quiz: ' . $quiz->id);
                    return redirect()->back()->with('error', 'Module not found for this quiz.');
                }

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

            // Log pertanyaan sebelum diacak
            Log::info('Original questions count: ' . $quiz->questions->count());
            Log::info('Original question IDs: ' . json_encode($quiz->questions->pluck('id')->toArray()));

            // Acak urutan pertanyaan
            if ($quiz->randomize_questions) {
                Log::info('Randomizing questions...');
                $shuffledQuestions = $quiz->questions->shuffle();
                Log::info('Questions shuffled');
            } else {
                Log::info('Not randomizing questions (randomize_questions is false)');
                $shuffledQuestions = $quiz->questions;
            }

            // Simpan ID pertanyaan dalam urutan acak untuk digunakan saat submit
            $questionOrder = $shuffledQuestions->pluck('id')->toArray();

            // Log untuk debugging
            Log::info('Final question order: ' . json_encode($questionOrder));

            // Create new attempt
            $attempt = QuizAttempt::create([
                'user_id' => $studentId,
                'quiz_id' => $quiz->id,
                'attempt_number' => $attemptCount + 1,
                'started_at' => Carbon::now(),
                'score' => 0,
                'is_passed' => false,
                'answers' => json_encode([]),
                'question_order' => json_encode($questionOrder),
            ]);

            Log::info('Quiz attempt created: ' . $attempt->id);
            Log::info('Question order saved to database: ' . $attempt->question_order);

            // Redirect kembali ke learning page dengan parameter quiz
            return redirect()->route('edutech.courses.learn', [
                'slug' => $quiz->course->slug,
                'quiz' => $quiz->id,
                'attempt' => $attempt->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error starting quiz: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while starting the quiz: ' . $e->getMessage());
        }
    }

    /**
     * Submit quiz
     */
    public function submit(Request $request, $quizId)
    {
        try {
            Log::info('Attempting to submit quiz with ID: ' . $quizId);

            $studentId = session('edutech_user_id');

            if (!$studentId) {
                Log::error('Student ID not found in session');
                return redirect()->back()->with('error', 'Session expired. Please login again.');
            }

            Log::info('Student ID: ' . $studentId);

            // Coba cari quiz dengan ID atau slug
            $quiz = Quiz::with(['course', 'questions'])
                ->where(function ($query) use ($quizId) {
                    $query->where('id', $quizId);
                })
                ->first();

            if (!$quiz) {
                Log::error('Quiz not found with ID: ' . $quizId);
                return redirect()->back()->with('error', 'Quiz not found.');
            }

            Log::info('Quiz found: ' . $quiz->id . ' - ' . $quiz->title);

            // Cari enrollment (tidak peduli statusnya)
            $enrollment = Enrollment::where('student_id', $studentId)
                ->where('course_id', $quiz->course_id)
                ->first();

            if (!$enrollment) {
                Log::error('Enrollment not found for student: ' . $studentId . ' in course: ' . $quiz->course_id);
                return redirect()->back()->with('error', 'You are not enrolled in this course.');
            }

            Log::info('Enrollment found: ' . $enrollment->id);

            // Find active attempt
            $attempt = QuizAttempt::where('user_id', $studentId)
                ->where('quiz_id', $quiz->id)
                ->whereNull('submitted_at')
                ->latest()
                ->first();

            if (!$attempt) {
                Log::error('No active attempt found for student: ' . $studentId . ' in quiz: ' . $quiz->id);
                return redirect()->back()->with('error', 'No active quiz attempt found.');
            }

            Log::info('Active attempt found: ' . $attempt->id);
            Log::info('Question order from database: ' . $attempt->question_order);

            // Ambil urutan pertanyaan yang disimpan saat start
            $questionOrder = json_decode($attempt->question_order, true) ?? [];
            Log::info('Decoded question order: ' . json_encode($questionOrder));

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
                Log::info('No question order found, using default order');
                $orderedQuestions = $quiz->questions->all();
            }

            Log::info('Final ordered questions count: ' . count($orderedQuestions));

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

            Log::info('Quiz attempt updated: ' . $attempt->id . ' with score: ' . $score);

            // Update enrollment status based on quiz type and result
            if ($quiz->type === 'pre_test' && $isPassed) {
                // Update enrollment status to active after passing pre-test
                $enrollment->update([
                    'status' => 'active',
                    'pre_test_passed_at' => Carbon::now(),
                    'pre_test_score' => $score,
                ]);

                Log::info('Enrollment status updated to active after passing pre-test');
            } elseif ($quiz->type === 'post_test' && $isPassed) {
                // Update enrollment status to completed after passing post-test
                $enrollment->update([
                    'status' => 'completed',
                    'completed_at' => Carbon::now(),
                    'post_test_score' => $score,
                ]);

                Log::info('Enrollment status updated to completed after passing post-test');
            }

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
        } catch (\Exception $e) {
            Log::error('Error submitting quiz: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while submitting the quiz: ' . $e->getMessage());
        }
    }
}
