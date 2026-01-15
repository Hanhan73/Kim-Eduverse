<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index(Request $request)
    {
        $query = QuestionnaireQuestion::with(['questionnaire', 'dimension']);

        // Filter by questionnaire
        if ($request->filled('questionnaire_id')) {
            $query->where('questionnaire_id', $request->questionnaire_id);
        }

        // Filter by dimension
        if ($request->filled('dimension_id')) {
            $query->where('dimension_id', $request->dimension_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('question_text', 'like', "%{$request->search}%");
        }

        $questions = $query->orderBy('questionnaire_id')->orderBy('order')->paginate(20);
        $questionnaires = Questionnaire::orderBy('name')->get();
        $dimensions = QuestionnaireDimension::orderBy('name')->get();

        return view('admin.digital.questions.index', compact('questions', 'questionnaires', 'dimensions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Request $request)
    {
        $questionnaires = Questionnaire::orderBy('name')->get();
        $dimensions = collect();

        $selectedQuestionnaireId = $request->get('questionnaire_id');
        $selectedDimensionId = $request->get('dimension_id');

        if ($selectedQuestionnaireId) {
            $dimensions = QuestionnaireDimension::where('questionnaire_id', $selectedQuestionnaireId)
                ->orderBy('order')
                ->get();
        }

        return view('admin.digital.questions.create', compact(
            'questionnaires',
            'dimensions',
            'selectedQuestionnaireId',
            'selectedDimensionId'
        ));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'dimension_id' => 'nullable|exists:questionnaire_dimensions,id',
            'question_text' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_reverse_scored' => 'boolean',
            'options' => 'nullable|array',
        ]);

        // Auto-generate order if not provided
        if (empty($validated['order'])) {
            $maxOrder = QuestionnaireQuestion::where('questionnaire_id', $validated['questionnaire_id'])
                ->max('order');
            $validated['order'] = ($maxOrder ?? 0) + 1;
        }

        $validated['is_reverse_scored'] = $request->has('is_reverse_scored');

        // Process options (Likert scale default)
        if (empty($validated['options'])) {
            $validated['options'] = [
                1 => 'Sangat Tidak Setuju',
                2 => 'Tidak Setuju',
                3 => 'Netral',
                4 => 'Setuju',
                5 => 'Sangat Setuju',
            ];
        }

        QuestionnaireQuestion::create($validated);

        // Redirect back with query params preserved
        return redirect()
            ->route('admin.digital.questions.index', [
                'questionnaire_id' => $validated['questionnaire_id'],
                'dimension_id' => $validated['dimension_id'] ?? null
            ])
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Display the specified question.
     */
    public function show($id)
    {
        $question = QuestionnaireQuestion::with(['questionnaire', 'dimension'])->findOrFail($id);

        return view('admin.digital.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit($id)
    {
        $question = QuestionnaireQuestion::with(['questionnaire', 'dimension'])->findOrFail($id);
        $questionnaires = Questionnaire::orderBy('name')->get();
        $dimensions = QuestionnaireDimension::where('questionnaire_id', $question->questionnaire_id)
            ->orderBy('order')
            ->get();

        return view('admin.digital.questions.edit', compact('question', 'questionnaires', 'dimensions'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, $id)
    {
        $question = QuestionnaireQuestion::findOrFail($id);

        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'dimension_id' => 'nullable|exists:questionnaire_dimensions,id',
            'question_text' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_reverse_scored' => 'boolean',
            'options' => 'nullable|array',
        ]);

        $validated['is_reverse_scored'] = $request->has('is_reverse_scored');

        $question->update($validated);

        return redirect()
            ->route('admin.digital.questions.index', ['questionnaire_id' => $question->questionnaire_id])
            ->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified question.
     */
    public function destroy($id)
    {
        $question = QuestionnaireQuestion::findOrFail($id);
        $questionnaireId = $question->questionnaire_id;

        $question->delete();

        // Reorder remaining questions
        $remainingQuestions = QuestionnaireQuestion::where('questionnaire_id', $questionnaireId)
            ->orderBy('order')
            ->get();

        foreach ($remainingQuestions as $index => $q) {
            $q->update(['order' => $index + 1]);
        }

        return redirect()
            ->route('admin.digital.questions.index', ['questionnaire_id' => $questionnaireId])
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }

    /**
     * Bulk store questions.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'dimension_id' => 'nullable|exists:questionnaire_dimensions,id',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.is_reverse_scored' => 'boolean',
        ]);

        $maxOrder = QuestionnaireQuestion::where('questionnaire_id', $validated['questionnaire_id'])
            ->max('order') ?? 0;

        foreach ($validated['questions'] as $index => $questionData) {
            QuestionnaireQuestion::create([
                'questionnaire_id' => $validated['questionnaire_id'],
                'dimension_id' => $validated['dimension_id'],
                'question_text' => $questionData['question_text'],
                'order' => $maxOrder + $index + 1,
                'is_reverse_scored' => $questionData['is_reverse_scored'] ?? false,
                'options' => [
                    1 => 'Sangat Tidak Setuju',
                    2 => 'Tidak Setuju',
                    3 => 'Netral',
                    4 => 'Setuju',
                    5 => 'Sangat Setuju',
                ],
            ]);
        }

        return redirect()
            ->route('admin.digital.questions.index', [
                'questionnaire_id' => $validated['questionnaire_id']
            ])
            ->with('success', count($validated['questions']) . ' pertanyaan berhasil ditambahkan!');
    }

    /**
     * Reorder questions.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'exists:questionnaire_questions,id',
        ]);

        foreach ($validated['questions'] as $order => $questionId) {
            QuestionnaireQuestion::where('id', $questionId)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get dimensions by questionnaire (AJAX).
     */
    public function getDimensions($questionnaireId)
    {
        $dimensions = QuestionnaireDimension::where('questionnaire_id', $questionnaireId)
            ->orderBy('order')
            ->get(['id', 'name', 'code']);

        return response()->json($dimensions);
    }
}
