<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireResponse;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionnaireManagementController extends Controller
{
    /**
     * Display a listing of questionnaires.
     */
    public function index(Request $request)
    {
        $query = Questionnaire::withCount(['questions', 'dimensions', 'responses']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $questionnaires = $query->latest()->paginate(10);

        // Get unique types for filter
        $types = Questionnaire::distinct()->pluck('type')->filter();

        // Stats
        $stats = [
            'total' => Questionnaire::count(),
            'active' => Questionnaire::where('is_active', true)->count(),
            'inactive' => Questionnaire::where('is_active', false)->count(),
            'total_responses' => QuestionnaireResponse::where('is_completed', true)->count(),
        ];

        return view('admin.digital.questionnaires.index', compact('questionnaires', 'types', 'stats'));
    }

    /**
     * Show the form for creating a new questionnaire.
     */
    public function create()
    {
        $types = [
            'burnout' => 'Burnout',
            'stress' => 'Stress',
            'anxiety' => 'Anxiety',
            'depression' => 'Depression',
            'motivation' => 'Motivation',
            'satisfaction' => 'Satisfaction',
            'personality' => 'Personality',
            'procrastination' => 'Procrastination',
            'other' => 'Lainnya',
        ];

        return view('admin.digital.questionnaires.create', compact('types'));
    }

    /**
     * Store a newly created questionnaire.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'type' => 'required|string|max:100',
            'duration_minutes' => 'nullable|integer|min:1',
            'has_dimensions' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['has_dimensions'] = $request->has('has_dimensions');
        $validated['is_active'] = $request->has('is_active');

        $questionnaire = Questionnaire::create($validated);

        return redirect()
            ->route('admin.digital.questionnaires.show', $questionnaire->id)
            ->with('success', 'Angket berhasil dibuat! Sekarang tambahkan dimensi dan pertanyaan.');
    }

    /**
     * Display the specified questionnaire.
     */
    public function show($id)
    {
        $questionnaire = Questionnaire::with([
            'dimensions' => function ($query) {
                $query->orderBy('order');
            },
            'dimensions.questions' => function ($query) {
                $query->orderBy('order');
            },
            'questions' => function ($query) {
                $query->orderBy('order');
            },
            'questions.dimension',
            'responses' => function ($query) {
                $query->where('is_completed', true)->latest()->limit(10);
            }
        ])->findOrFail($id);

        // Response stats
        $responseStats = [
            'total' => $questionnaire->responses()->count(),
            'completed' => $questionnaire->responses()->where('is_completed', true)->count(),
            'pending' => $questionnaire->responses()->where('is_completed', false)->count(),
        ];

        // Products using this questionnaire
        $products = DigitalProduct::where('questionnaire_id', $questionnaire->id)->get();

        return view('admin.digital.questionnaires.show', compact('questionnaire', 'responseStats', 'products'));
    }

    /**
     * Show the form for editing the specified questionnaire.
     */
    public function edit($id)
    {
        $questionnaire = Questionnaire::with(['dimensions', 'questions.dimension'])->findOrFail($id);

        $types = [
            'burnout' => 'Burnout',
            'stress' => 'Stress',
            'anxiety' => 'Anxiety',
            'depression' => 'Depression',
            'motivation' => 'Motivation',
            'satisfaction' => 'Satisfaction',
            'personality' => 'Personality',
            'procrastination' => 'Procrastination',
            'other' => 'Lainnya',
        ];

        return view('admin.digital.questionnaires.edit', compact('questionnaire', 'types'));
    }

    /**
     * Update the specified questionnaire.
     */
    public function update(Request $request, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'type' => 'required|string|max:100',
            'duration_minutes' => 'nullable|integer|min:1',
            'has_dimensions' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['has_dimensions'] = $request->has('has_dimensions');
        $validated['is_active'] = $request->has('is_active');

        $questionnaire->update($validated);

        return redirect()
            ->route('admin.digital.questionnaires.show', $questionnaire->id)
            ->with('success', 'Angket berhasil diperbarui!');
    }

    /**
     * Remove the specified questionnaire.
     */
    public function destroy($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        // Check if questionnaire has completed responses
        if ($questionnaire->responses()->where('is_completed', true)->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus angket yang sudah memiliki respons!');
        }

        // Check if used by products
        if (DigitalProduct::where('questionnaire_id', $questionnaire->id)->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus angket yang sedang digunakan oleh produk!');
        }

        $questionnaire->delete();

        return redirect()
            ->route('admin.digital.questionnaires.index')
            ->with('success', 'Angket berhasil dihapus!');
    }

    // ========================================
    // DIMENSION MANAGEMENT
    // ========================================

    /**
     * Add dimension to questionnaire.
     */
    public function addDimension(Request $request, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Auto-generate order
        $maxOrder = QuestionnaireDimension::where('questionnaire_id', $id)->max('order') ?? 0;

        // Default interpretations
        $defaultInterpretations = [
            'low' => [
                'level' => 'RENDAH',
                'class' => 'level-rendah',
                'description' => 'Tingkat pada aspek ini tergolong rendah.',
                'suggestions' => [],
            ],
            'medium' => [
                'level' => 'SEDANG',
                'class' => 'level-sedang',
                'description' => 'Tingkat pada aspek ini tergolong sedang.',
                'suggestions' => [],
            ],
            'high' => [
                'level' => 'TINGGI',
                'class' => 'level-tinggi',
                'description' => 'Tingkat pada aspek ini tergolong tinggi.',
                'suggestions' => [],
            ],
        ];

        QuestionnaireDimension::create([
            'questionnaire_id' => $id,
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'order' => $maxOrder + 1,
            'interpretations' => $defaultInterpretations,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Dimensi berhasil ditambahkan!');
    }

    /**
     * Update dimension.
     */
    public function updateDimension(Request $request, $id)
    {
        $dimension = QuestionnaireDimension::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'interpretations' => 'nullable|array',
        ]);

        // Process interpretations if provided
        if ($request->has('interpretations')) {
            $interpretations = $request->interpretations;
            foreach (['low', 'medium', 'high'] as $level) {
                if (isset($interpretations[$level]['suggestions'])) {
                    $interpretations[$level]['suggestions'] = array_filter(
                        $interpretations[$level]['suggestions'],
                        fn($s) => !empty(trim($s))
                    );
                    $interpretations[$level]['suggestions'] = array_values($interpretations[$level]['suggestions']);
                }
            }
            $validated['interpretations'] = $interpretations;
        }

        $dimension->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Dimensi berhasil diperbarui!');
    }

    /**
     * Delete dimension.
     */
    public function deleteDimension($id)
    {
        $dimension = QuestionnaireDimension::findOrFail($id);

        // Check if dimension has questions
        if ($dimension->questions()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus dimensi yang masih memiliki pertanyaan!');
        }

        $dimension->delete();

        return redirect()
            ->back()
            ->with('success', 'Dimensi berhasil dihapus!');
    }

    /**
     * Add score range to dimension.
     */
    public function addRange(Request $request, $id)
    {
        $dimension = QuestionnaireDimension::findOrFail($id);

        $validated = $request->validate([
            'level' => 'required|in:low,medium,high',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:0',
            'label' => 'required|string|max:50',
            'description' => 'required|string',
            'suggestions' => 'nullable|array',
        ]);

        $interpretations = $dimension->interpretations ?? [];
        
        $interpretations[$validated['level']] = [
            'level' => $validated['label'],
            'class' => 'level-' . strtolower($validated['label']),
            'min_score' => $validated['min_score'],
            'max_score' => $validated['max_score'],
            'description' => $validated['description'],
            'suggestions' => array_filter($validated['suggestions'] ?? [], fn($s) => !empty(trim($s))),
        ];

        $dimension->update(['interpretations' => $interpretations]);

        return redirect()
            ->back()
            ->with('success', 'Range skor berhasil ditambahkan!');
    }

    /**
     * Delete score range from dimension.
     */
    public function deleteRange($id)
    {
        // This would need range_id passed, for now redirect back
        return redirect()->back()->with('info', 'Fitur delete range akan segera tersedia.');
    }

    // ========================================
    // QUESTION MANAGEMENT
    // ========================================

    /**
     * Add question to questionnaire.
     */
    public function addQuestion(Request $request, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        $validated = $request->validate([
            'dimension_id' => 'nullable|exists:questionnaire_dimensions,id',
            'question_text' => 'required|string',
            'is_reverse_scored' => 'boolean',
        ]);

        // Auto-generate order
        $maxOrder = QuestionnaireQuestion::where('questionnaire_id', $id)->max('order') ?? 0;

        // Default Likert options
        $defaultOptions = [
            1 => 'Sangat Tidak Setuju',
            2 => 'Tidak Setuju',
            3 => 'Netral',
            4 => 'Setuju',
            5 => 'Sangat Setuju',
        ];

        QuestionnaireQuestion::create([
            'questionnaire_id' => $id,
            'dimension_id' => $validated['dimension_id'],
            'question_text' => $validated['question_text'],
            'order' => $maxOrder + 1,
            'is_reverse_scored' => $request->has('is_reverse_scored'),
            'options' => $defaultOptions,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Delete question.
     */
    public function deleteQuestion($id)
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
            ->back()
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
