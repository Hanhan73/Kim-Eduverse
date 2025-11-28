<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use Illuminate\Http\Request;

class DimensionController extends Controller
{
    /**
     * Display a listing of dimensions.
     */
    public function index(Request $request)
    {
        $query = QuestionnaireDimension::with('questionnaire')
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

        $dimensions = $query->orderBy('questionnaire_id')->orderBy('order')->paginate(15);
        $questionnaires = Questionnaire::where('has_dimensions', true)->orderBy('name')->get();

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

        $selectedQuestionnaireId = $request->get('questionnaire_id');

        return view('admin.digital.dimensions.create', compact('questionnaires', 'selectedQuestionnaireId'));
    }

    /**
     * Store a newly created dimension.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'interpretations' => 'required|array',
            'interpretations.low' => 'required|array',
            'interpretations.low.level' => 'required|string',
            'interpretations.low.class' => 'required|string',
            'interpretations.low.description' => 'required|string',
            'interpretations.low.suggestions' => 'nullable|array',
            'interpretations.medium' => 'required|array',
            'interpretations.medium.level' => 'required|string',
            'interpretations.medium.class' => 'required|string',
            'interpretations.medium.description' => 'required|string',
            'interpretations.medium.suggestions' => 'nullable|array',
            'interpretations.high' => 'required|array',
            'interpretations.high.level' => 'required|string',
            'interpretations.high.class' => 'required|string',
            'interpretations.high.description' => 'required|string',
            'interpretations.high.suggestions' => 'nullable|array',
        ]);

        // Auto-generate order if not provided
        if (empty($validated['order'])) {
            $maxOrder = QuestionnaireDimension::where('questionnaire_id', $validated['questionnaire_id'])
                ->max('order');
            $validated['order'] = ($maxOrder ?? 0) + 1;
        }

        // Clean up suggestions - convert empty to empty array
        foreach (['low', 'medium', 'high'] as $level) {
            if (empty($validated['interpretations'][$level]['suggestions'])) {
                $validated['interpretations'][$level]['suggestions'] = [];
            } else {
                // Filter out empty suggestions
                $validated['interpretations'][$level]['suggestions'] = array_filter(
                    $validated['interpretations'][$level]['suggestions'],
                    fn($s) => !empty(trim($s))
                );
                $validated['interpretations'][$level]['suggestions'] = array_values(
                    $validated['interpretations'][$level]['suggestions']
                );
            }
        }

        QuestionnaireDimension::create($validated);

        return redirect()
            ->route('admin.digital.dimensions.index', ['questionnaire_id' => $validated['questionnaire_id']])
            ->with('success', 'Dimensi berhasil ditambahkan!');
    }

    /**
     * Display the specified dimension.
     */
    public function show($id)
    {
        $dimension = QuestionnaireDimension::with(['questionnaire', 'questions'])->findOrFail($id);

        return view('admin.digital.dimensions.show', compact('dimension'));
    }

    /**
     * Show the form for editing the specified dimension.
     */
    public function edit($id)
    {
        $dimension = QuestionnaireDimension::with('questionnaire')->findOrFail($id);
        $questionnaires = Questionnaire::where('has_dimensions', true)->orderBy('name')->get();

        return view('admin.digital.dimensions.edit', compact('dimension', 'questionnaires'));
    }

    /**
     * Update the specified dimension.
     */
    public function update(Request $request, $id)
    {
        $dimension = QuestionnaireDimension::findOrFail($id);

        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'interpretations' => 'required|array',
            'interpretations.low' => 'required|array',
            'interpretations.low.level' => 'required|string',
            'interpretations.low.class' => 'required|string',
            'interpretations.low.description' => 'required|string',
            'interpretations.low.suggestions' => 'nullable|array',
            'interpretations.medium' => 'required|array',
            'interpretations.medium.level' => 'required|string',
            'interpretations.medium.class' => 'required|string',
            'interpretations.medium.description' => 'required|string',
            'interpretations.medium.suggestions' => 'nullable|array',
            'interpretations.high' => 'required|array',
            'interpretations.high.level' => 'required|string',
            'interpretations.high.class' => 'required|string',
            'interpretations.high.description' => 'required|string',
            'interpretations.high.suggestions' => 'nullable|array',
        ]);

        // Clean up suggestions
        foreach (['low', 'medium', 'high'] as $level) {
            if (empty($validated['interpretations'][$level]['suggestions'])) {
                $validated['interpretations'][$level]['suggestions'] = [];
            } else {
                $validated['interpretations'][$level]['suggestions'] = array_filter(
                    $validated['interpretations'][$level]['suggestions'],
                    fn($s) => !empty(trim($s))
                );
                $validated['interpretations'][$level]['suggestions'] = array_values(
                    $validated['interpretations'][$level]['suggestions']
                );
            }
        }

        $dimension->update($validated);

        return redirect()
            ->route('admin.digital.dimensions.index', ['questionnaire_id' => $dimension->questionnaire_id])
            ->with('success', 'Dimensi berhasil diperbarui!');
    }

    /**
     * Remove the specified dimension.
     */
    public function destroy($id)
    {
        $dimension = QuestionnaireDimension::findOrFail($id);
        $questionnaireId = $dimension->questionnaire_id;

        // Check if dimension has questions
        if ($dimension->questions()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus dimensi yang masih memiliki pertanyaan!');
        }

        $dimension->delete();

        return redirect()
            ->route('admin.digital.dimensions.index', ['questionnaire_id' => $questionnaireId])
            ->with('success', 'Dimensi berhasil dihapus!');
    }
}
