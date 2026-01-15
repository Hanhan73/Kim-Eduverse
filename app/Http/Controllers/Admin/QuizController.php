<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    /**
     * Show quiz edit page with questions management
     */
    public function edit(Quiz $quiz)
    {
        $quiz->load('questions');
        
        return view('admin.digital.quizzes.edit', compact('quiz'));
    }

    /**
     * Update quiz details
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $quiz->update($validated);

        return redirect()
            ->route('admin.digital.quizzes.edit', $quiz)
            ->with('success', 'Quiz berhasil diupdate!');
    }

    /**
     * Store new question
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'option_e' => 'nullable|string',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
        ]);

        // Get max order
        $maxOrder = $quiz->questions()->max('order') ?? 0;

        // Create question with proper field mapping
        $quiz->questions()->create([
            'question' => $validated['question_text'],
            'type' => 'multiple_choice',
            'options' => [
                'A' => $validated['option_a'],
                'B' => $validated['option_b'],
                'C' => $validated['option_c'] ?? null,
                'D' => $validated['option_d'] ?? null,
                'E' => $validated['option_e'] ?? null,
            ],
            'correct_answer' => $validated['correct_answer'],
            'points' => $validated['points'],
            'order' => $maxOrder + 1,
        ]);

        return redirect()
            ->route('admin.digital.quizzes.edit', $quiz)
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Edit question form (returns JSON for AJAX modal)
     */
    public function editQuestion(Quiz $quiz, QuizQuestion $question)
    {
        return response()->json([
            'success' => true,
            'question' => $question
        ]);
    }

    /**
     * Update question
     */
    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'option_e' => 'nullable|string',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
        ]);

        $question->update([
            'question' => $validated['question_text'],
            'options' => [
                'A' => $validated['option_a'],
                'B' => $validated['option_b'],
                'C' => $validated['option_c'] ?? null,
                'D' => $validated['option_d'] ?? null,
                'E' => $validated['option_e'] ?? null,
            ],
            'correct_answer' => $validated['correct_answer'],
            'points' => $validated['points'],
        ]);

        return redirect()
            ->route('admin.digital.quizzes.edit', $quiz)
            ->with('success', 'Pertanyaan berhasil diupdate!');
    }

    /**
     * Delete question
     */
    public function destroyQuestion(Quiz $quiz, QuizQuestion $question)
    {
        $question->delete();

        // Reorder remaining questions
        $quiz->questions()->orderBy('order')->get()->each(function ($q, $index) {
            $q->update(['order' => $index + 1]);
        });

        return redirect()
            ->route('admin.digital.quizzes.edit', $quiz)
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }

    /**
     * Reorder questions (AJAX)
     */
    public function reorderQuestions(Request $request, Quiz $quiz)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'integer|exists:quiz_questions,id',
        ]);

        foreach ($request->questions as $index => $questionId) {
            QuizQuestion::where('id', $questionId)
                ->where('quiz_id', $quiz->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}