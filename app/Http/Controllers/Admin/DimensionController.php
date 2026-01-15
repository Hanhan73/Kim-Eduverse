<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireScoreRange;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DimensionController extends Controller
{
    /**
     * Display a listing of dimensions.
     */
    public function index(Request $request)
    {
        $query = QuestionnaireDimension::with(['questionnaire', 'questions', 'scoreRanges'])
            ->withCount('questions');

        // Filter by questionnaire
        if ($request->filled('questionnaire_id')) {
            $query->where('questionnaire_id', $request->questionnaire_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $dimensions = $query->orderBy('questionnaire_id')
            ->orderBy('order')
            ->paginate(15);

        $questionnaires = Questionnaire::where('has_dimensions', true)
            ->orderBy('name')
            ->get();

        return view('admin.digital.dimensions.index', compact('dimensions', 'questionnaires'));
    }

    /**
     * Show the form for creating a new dimension.
     */
    public function create(Request $request)
    {
        $questionnaires = Questionnaire::where('has_dimensions', true)
            ->orderBy('name')
            ->get();

        $selectedQuestionnaireId = $request->questionnaire_id;
        $cssClasses = QuestionnaireScoreRange::getCssClasses();
        $categories = QuestionnaireScoreRange::getCategories();

        return view('admin.digital.dimensions.create', compact(
            'questionnaires', 
            'selectedQuestionnaireId',
            'cssClasses',
            'categories'
        ));
    }

    /**
     * Store a newly created dimension.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate code if empty
        if (empty($validated['code'])) {
            $validated['code'] = Str::slug($validated['name'], '_');
        }

        // Auto order
        if (empty($validated['order'])) {
            $validated['order'] = QuestionnaireDimension::where('questionnaire_id', $validated['questionnaire_id'])
                ->max('order') + 1;
        }

        $dimension = QuestionnaireDimension::create($validated);

        return redirect()
            ->route('admin.digital.dimensions.edit', $dimension->id)
            ->with('success', 'Dimensi berhasil dibuat. Silakan tambahkan score ranges.');
    }

    /**
     * Display the specified dimension.
     */
    public function show($id)
    {
        $dimension = QuestionnaireDimension::with([
            'questionnaire',
            'questions',
            'scoreRanges' => fn($q) => $q->orderBy('order'),
        ])->findOrFail($id);

        return view('admin.digital.dimensions.show', compact('dimension'));
    }

    /**
     * Show the form for editing the specified dimension.
     */
    public function edit($id)
    {
        $dimension = QuestionnaireDimension::with([
            'questionnaire',
            'questions',
            'scoreRanges' => fn($q) => $q->orderBy('order'),
        ])->findOrFail($id);

        $questionnaires = Questionnaire::where('has_dimensions', true)
            ->orderBy('name')
            ->get();

        $cssClasses = QuestionnaireScoreRange::getCssClasses();
        $categories = QuestionnaireScoreRange::getCategories();
        $bounds = $dimension->getScoreBounds();

        return view('admin.digital.dimensions.edit', compact(
            'dimension', 
            'questionnaires',
            'cssClasses',
            'categories',
            'bounds'
        ));
    }

    /**
     * Update the specified dimension.
     */
    public function update(Request $request, $id)
    {
        $dimension = QuestionnaireDimension::findOrFail($id);

        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $dimension->update($validated);

        return redirect()
            ->route('admin.digital.dimensions.edit', $dimension->id)
            ->with('success', 'Dimensi berhasil diperbarui.');
    }

    /**
     * Remove the specified dimension.
     */
    public function destroy($id)
    {
        $dimension = QuestionnaireDimension::findOrFail($id);
        $questionnaireId = $dimension->questionnaire_id;

        // Check if has questions
        if ($dimension->questions()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus dimensi yang memiliki pertanyaan. Hapus atau pindahkan pertanyaan terlebih dahulu.');
        }

        $dimension->delete();

        return redirect()
            ->route('admin.digital.dimensions.index', ['questionnaire_id' => $questionnaireId])
            ->with('success', 'Dimensi berhasil dihapus.');
    }

    // ==========================================
    // SCORE RANGE MANAGEMENT
    // ==========================================

    /**
     * Add a score range to dimension.
     */
    public function addScoreRange(Request $request, $dimensionId)
    {
        $dimension = QuestionnaireDimension::findOrFail($dimensionId);

        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'level' => 'required|string|max:50',
            'css_class' => 'required|string|max:50',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:0|gte:min_score',
            'description' => 'required|string',
            'suggestions' => 'nullable|array',
            'suggestions.*' => 'nullable|string',
        ]);

        // Filter empty suggestions
        $validated['suggestions'] = array_filter($validated['suggestions'] ?? [], fn($s) => !empty(trim($s)));

        // Auto order
        $validated['order'] = $dimension->scoreRanges()->max('order') + 1;

        $dimension->scoreRanges()->create($validated);

        return back()->with('success', 'Score range berhasil ditambahkan.');
    }

    /**
     * Update a score range.
     */
    public function updateScoreRange(Request $request, $rangeId)
    {
        $range = QuestionnaireScoreRange::findOrFail($rangeId);

        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'level' => 'required|string|max:50',
            'css_class' => 'required|string|max:50',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:0|gte:min_score',
            'description' => 'required|string',
            'suggestions' => 'nullable|array',
            'suggestions.*' => 'nullable|string',
        ]);

        // Filter empty suggestions
        $validated['suggestions'] = array_filter($validated['suggestions'] ?? [], fn($s) => !empty(trim($s)));

        $range->update($validated);

        return back()->with('success', 'Score range berhasil diperbarui.');
    }

    /**
     * Delete a score range.
     */
    public function deleteScoreRange($rangeId)
    {
        $range = QuestionnaireScoreRange::findOrFail($rangeId);
        $dimensionId = $range->dimension_id;
        
        $range->delete();

        return back()->with('success', 'Score range berhasil dihapus.');
    }

    /**
     * Generate default score ranges for a dimension.
     */
    public function generateDefaultRanges($dimensionId)
    {
        $dimension = QuestionnaireDimension::findOrFail($dimensionId);
        
        if ($dimension->questions()->count() == 0) {
            return back()->with('error', 'Dimensi belum memiliki pertanyaan. Tambahkan pertanyaan terlebih dahulu.');
        }

        $dimension->generateDefaultRanges();

        return back()->with('success', 'Default score ranges berhasil dibuat berdasarkan jumlah pertanyaan.');
    }

    /**
     * Migrate legacy interpretations to score ranges.
     */
    public function migrateInterpretations($dimensionId)
    {
        $dimension = QuestionnaireDimension::findOrFail($dimensionId);
        
        if ($dimension->questions()->count() == 0) {
            return back()->with('error', 'Dimensi belum memiliki pertanyaan. Tambahkan pertanyaan terlebih dahulu.');
        }

        $dimension->migrateInterpretationsToRanges();

        return back()->with('success', 'Interpretasi berhasil di-migrate ke score ranges.');
    }

    /**
     * Reorder score ranges.
     */
    public function reorderScoreRanges(Request $request, $dimensionId)
    {
        $dimension = QuestionnaireDimension::findOrFail($dimensionId);
        
        $validated = $request->validate([
            'ranges' => 'required|array',
            'ranges.*' => 'exists:questionnaire_score_ranges,id',
        ]);

        foreach ($validated['ranges'] as $order => $rangeId) {
            QuestionnaireScoreRange::where('id', $rangeId)
                ->where('dimension_id', $dimensionId)
                ->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }
}
