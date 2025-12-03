<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz)
    {
        // Muat quiz beserta pertanyaannya, diurutkan berdasarkan kolom 'order'
        $quiz->load(['questions' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('admin.digital.quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified quiz in storage.
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

        $quiz->update($validated);

        return back()
            ->with('success', 'Informasi quiz berhasil diperbarui!');
    }

    /**
     * Store a newly created question in storage.
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d,e',
            'points' => 'required|integer|min:1',
        ]);

        // Tentukan urutan baru untuk pertanyaan ini
        $maxOrder = $quiz->questions()->max('order');
        $validated['order'] = $maxOrder ? $maxOrder + 1 : 1;

        $quiz->questions()->create($validated);

        return back()
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Update the specified question in storage.
     */
    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d,e',
            'points' => 'required|integer|min:1',
        ]);

        $question->update($validated);

        return back()
            ->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroyQuestion(Quiz $quiz, QuizQuestion $question)
    {
        $question->delete();

        return back()
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }

    /**
     * Reorder questions.
     */
    public function reorderQuestions(Request $request, Quiz $quiz)
    {
        $questionIds = $request->input('question_ids', []);

        foreach ($questionIds as $index => $questionId) {
            QuizQuestion::where('id', $questionId)
                ->where('quiz_id', $quiz->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function editQuestion(Quiz $quiz, QuizQuestion $question)
    {
        return view('admin.digital.quizzes._edit_question_form', [
            'quiz' => $quiz,
            'question' => $question
        ]);
    }
}
