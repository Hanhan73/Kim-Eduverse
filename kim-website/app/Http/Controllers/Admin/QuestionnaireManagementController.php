<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireDimensionRange;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionnaireManagementController extends Controller
{
    /**
     * Display listing of questionnaires
     */
    public function index()
    {
        $questionnaires = Questionnaire::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.digital.questionnaires.index', compact('questionnaires'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.digital.questionnaires.create');
    }

    /**
     * Store new questionnaire
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:questionnaires,slug',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['type'] = 'general'; // Default type

        $questionnaire = Questionnaire::create($validated);

        return redirect()->route('admin.digital.questionnaires.edit', $questionnaire->id)
            ->with('success', 'Questionnaire berhasil dibuat! Sekarang tambahkan dimensi & pertanyaan.');
    }

    /**
     * Show edit form with questions & dimensions
     */
    public function edit($id)
    {
        $questionnaire = Questionnaire::with(['questions.options', 'questions.dimensions', 'dimensions.ranges', 'dimensions.questions'])->findOrFail($id);
        return view('admin.digital.questionnaires.edit', compact('questionnaire'));
    }

    /**
     * Update questionnaire
     */
    public function update(Request $request, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:questionnaires,slug,' . $id,
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        $questionnaire->update($validated);

        return back()->with('success', 'Questionnaire berhasil diupdate!');
    }

    /**
     * Delete questionnaire
     */
    public function destroy($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        if ($questionnaire->products()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus questionnaire yang terhubung dengan produk!');
        }

        foreach ($questionnaire->questions as $question) {
            $question->options()->delete();
            $question->dimensions()->detach();
            $question->delete();
        }

        foreach ($questionnaire->dimensions as $dimension) {
            $dimension->ranges()->delete();
            $dimension->delete();
        }

        $questionnaire->delete();

        return redirect()->route('admin.digital.questionnaires.index')
            ->with('success', 'Questionnaire berhasil dihapus!');
    }

    /**
     * Add dimension
     */
    public function addDimension(Request $request, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
        ]);

        $validated['questionnaire_id'] = $id;
        $validated['order'] = $questionnaire->dimensions()->max('order') + 1;

        QuestionnaireDimension::create($validated);

        return back()->with('success', 'Dimensi berhasil ditambahkan!');
    }

    /**
     * Update dimension
     */
    public function updateDimension(Request $request, $dimensionId)
    {
        $dimension = QuestionnaireDimension::findOrFail($dimensionId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
        ]);

        $dimension->update($validated);

        return back()->with('success', 'Dimensi berhasil diupdate!');
    }

    /**
     * Delete dimension
     */
    public function deleteDimension($dimensionId)
    {
        $dimension = QuestionnaireDimension::findOrFail($dimensionId);
        $dimension->ranges()->delete();
        $dimension->questions()->detach();
        $dimension->delete();

        return back()->with('success', 'Dimensi berhasil dihapus!');
    }

    /**
     * Add range to dimension
     */
    public function addRange(Request $request, $dimensionId)
    {
        $validated = $request->validate([
            'min_score' => 'required|integer',
            'max_score' => 'required|integer|gte:min_score',
            'category' => 'required|string|max:255',
            'interpretation' => 'required|string',
            'recommendations' => 'nullable|string',
        ]);

        $validated['dimension_id'] = $dimensionId;

        QuestionnaireDimensionRange::create($validated);

        return back()->with('success', 'Range berhasil ditambahkan!');
    }

    /**
     * Delete range
     */
    public function deleteRange($rangeId)
    {
        QuestionnaireDimensionRange::findOrFail($rangeId)->delete();
        return back()->with('success', 'Range berhasil dihapus!');
    }

    /**
     * Add question
     */
    public function addQuestion(Request $request, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,scale,text',
            'dimension_ids' => 'nullable|array',
            'dimension_ids.*' => 'exists:questionnaire_dimensions,id',
            'options' => 'required_if:question_type,multiple_choice|array',
            'options.*.text' => 'required_with:options|string',
            'options.*.score' => 'nullable|integer',
            'scale_min' => 'required_if:question_type,scale|nullable|integer',
            'scale_max' => 'required_if:question_type,scale|nullable|integer',
            'is_required' => 'boolean',
        ]);

        $question = $questionnaire->questions()->create([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'is_required' => $request->has('is_required'),
            'order' => $questionnaire->questions()->max('order') + 1,
        ]);

        // Attach dimensions
        if (!empty($validated['dimension_ids'])) {
            $question->dimensions()->attach($validated['dimension_ids']);
        }

        // Add options for multiple choice
        if ($validated['question_type'] === 'multiple_choice' && !empty($validated['options'])) {
            foreach ($validated['options'] as $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['text'],
                    'score' => $optionData['score'] ?? 0,
                ]);
            }
        }

        // Add scale options
        if ($validated['question_type'] === 'scale') {
            $min = $validated['scale_min'];
            $max = $validated['scale_max'];
            for ($i = $min; $i <= $max; $i++) {
                $question->options()->create([
                    'option_text' => (string)$i,
                    'score' => $i,
                ]);
            }
        }

        return back()->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Delete question
     */
    public function deleteQuestion($questionId)
    {
        $question = QuestionnaireQuestion::findOrFail($questionId);
        $question->options()->delete();
        $question->dimensions()->detach();
        $question->delete();

        return back()->with('success', 'Pertanyaan berhasil dihapus!');
    }
}