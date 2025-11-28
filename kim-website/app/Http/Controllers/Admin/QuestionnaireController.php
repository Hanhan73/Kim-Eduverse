<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionnaireController extends Controller
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
        $types = Questionnaire::distinct()->pluck('type');

        // Stats
        $stats = [
            'total' => Questionnaire::count(),
            'active' => Questionnaire::where('is_active', true)->count(),
            'inactive' => Questionnaire::where('is_active', false)->count(),
            'total_responses' => \App\Models\QuestionnaireResponse::where('is_completed', true)->count(),
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
            ->route('admin.digital.questionnaires.edit', $questionnaire->id)
            ->with('success', 'Angket berhasil dibuat! Sekarang tambahkan dimensi dan pertanyaan.');
    }

    /**
     * Display the specified questionnaire.
     */
    public function show($id)
    {
        $questionnaire = Questionnaire::with([
            'dimensions.questions',
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

        return view('admin.digital.questionnaires.show', compact('questionnaire', 'responseStats'));
    }

    /**
     * Show the form for editing the specified questionnaire.
     */
    public function edit($id)
    {
        $questionnaire = Questionnaire::with(['dimensions.questions', 'questions.dimension'])->findOrFail($id);

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
            ->route('admin.digital.questionnaires.index')
            ->with('success', 'Angket berhasil diperbarui!');
    }

    /**
     * Remove the specified questionnaire.
     */
    public function destroy($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        // Check if questionnaire has responses
        if ($questionnaire->responses()->where('is_completed', true)->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus angket yang sudah memiliki respons!');
        }

        $questionnaire->delete();

        return redirect()
            ->route('admin.digital.questionnaires.index')
            ->with('success', 'Angket berhasil dihapus!');
    }

    /**
     * Show questions management page for a questionnaire.
     */
    public function questions($id)
    {
        $questionnaire = Questionnaire::with(['dimensions', 'questions.dimension'])->findOrFail($id);

        return view('admin.digital.questionnaires.questions', compact('questionnaire'));
    }

    /**
     * Toggle active status.
     */
    public function toggleActive($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        $questionnaire->is_active = !$questionnaire->is_active;
        $questionnaire->save();

        $status = $questionnaire->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()
            ->back()
            ->with('success', "Angket berhasil {$status}!");
    }
}
