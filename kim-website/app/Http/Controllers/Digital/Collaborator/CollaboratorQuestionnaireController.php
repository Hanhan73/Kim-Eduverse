<?php

namespace App\Http\Controllers\Digital\Collaborator;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollaboratorQuestionnaireController extends Controller
{
    private function checkAuth()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }
        return $userId;
    }

    public function index()
    {
        $userId = $this->checkAuth();
        $questionnaires = Questionnaire::where('created_by', $userId)
            ->withCount(['questions', 'dimensions', 'responses'])
            ->latest()
            ->paginate(10);
        
        $stats = [
            'total' => Questionnaire::where('created_by', $userId)->count(),
            'active' => Questionnaire::where('created_by', $userId)->where('is_active', true)->count(),
            'with_dimensions' => Questionnaire::where('created_by', $userId)->where('has_dimensions', true)->count(),
        ];
        
        return view('digital.collaborator.questionnaires.index', compact('questionnaires', 'stats'));
    }

    public function create()
    {
        $this->checkAuth();
        $types = [
            'burnout' => 'Burnout',
            'stress' => 'Stress',
            'anxiety' => 'Anxiety',
            'depression' => 'Depression',
            'motivation' => 'Motivation',
            'personality' => 'Personality',
            'procrastination' => 'Procrastination',
            'other' => 'Lainnya',
        ];
        return view('digital.collaborator.questionnaires.create', compact('types'));
    }

    public function store(Request $request)
    {
        $userId = $this->checkAuth();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'type' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'has_dimensions' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = $userId;
        $validated['is_active'] = true;
        $validated['has_dimensions'] = $request->has('has_dimensions');

        $questionnaire = Questionnaire::create($validated);

        return redirect()->route('digital.collaborator.questionnaires.builder', $questionnaire)
            ->with('success', 'CEKMA berhasil dibuat! Sekarang tambahkan pertanyaan.');
    }

    public function builder(Questionnaire $questionnaire)
    {
        $userId = $this->checkAuth();
        if ($questionnaire->created_by !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke CEKMA ini');
        }

        $questionnaire->load(['dimensions' => function($q) {
            $q->orderBy('order');
        }, 'questions' => function($q) {
            $q->orderBy('order');
        }]);
        
        return view('digital.collaborator.questionnaires.builder', compact('questionnaire'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $userId = $this->checkAuth();
        if ($questionnaire->created_by !== $userId) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'type' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $questionnaire->update($validated);

        return back()->with('success', 'CEKMA berhasil diupdate!');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $userId = $this->checkAuth();
        if ($questionnaire->created_by !== $userId) {
            abort(403);
        }

        if ($questionnaire->responses()->where('is_completed', true)->exists()) {
            return back()->with('error', 'Tidak dapat menghapus CEKMA yang sudah memiliki respons!');
        }

        $questionnaire->delete();
        return redirect()->route('digital.collaborator.questionnaires.index')
            ->with('success', 'CEKMA berhasil dihapus!');
    }

    // === DIMENSION MANAGEMENT ===
    
    public function storeDimension(Request $request, Questionnaire $questionnaire)
    {
        $userId = $this->checkAuth();
        if ($questionnaire->created_by !== $userId) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $maxOrder = QuestionnaireDimension::where('questionnaire_id', $questionnaire->id)->max('order') ?? 0;

        QuestionnaireDimension::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'order' => $maxOrder + 1,
            'interpretations' => [
                'low' => ['level' => 'RENDAH', 'class' => 'level-rendah', 'description' => ''],
                'medium' => ['level' => 'SEDANG', 'class' => 'level-sedang', 'description' => ''],
                'high' => ['level' => 'TINGGI', 'class' => 'level-tinggi', 'description' => ''],
            ],
        ]);

        return back()->with('success', 'Dimensi berhasil ditambahkan!');
    }

    public function deleteDimension($dimensionId)
    {
        $dimension = QuestionnaireDimension::findOrFail($dimensionId);
        $userId = $this->checkAuth();
        
        if ($dimension->questionnaire->created_by !== $userId) {
            abort(403);
        }

        if ($dimension->questions()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus dimensi yang memiliki pertanyaan!');
        }

        $dimension->delete();
        return back()->with('success', 'Dimensi berhasil dihapus!');
    }

    // === QUESTION MANAGEMENT ===
    
    public function storeQuestion(Request $request, Questionnaire $questionnaire)
    {
        $userId = $this->checkAuth();
        if ($questionnaire->created_by !== $userId) {
            abort(403);
        }

        $validated = $request->validate([
            'dimension_id' => 'nullable|exists:questionnaire_dimensions,id',
            'question_text' => 'required|string',
            'is_reverse_scored' => 'boolean',
        ]);

        $maxOrder = QuestionnaireQuestion::where('questionnaire_id', $questionnaire->id)->max('order') ?? 0;

        QuestionnaireQuestion::create([
            'questionnaire_id' => $questionnaire->id,
            'dimension_id' => $validated['dimension_id'],
            'question_text' => $validated['question_text'],
            'order' => $maxOrder + 1,
            'is_reverse_scored' => $request->has('is_reverse_scored'),
            'options' => [
                1 => 'Sangat Tidak Setuju',
                2 => 'Tidak Setuju',
                3 => 'Netral',
                4 => 'Setuju',
                5 => 'Sangat Setuju',
            ],
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    public function deleteQuestion($questionId)
    {
        $question = QuestionnaireQuestion::findOrFail($questionId);
        $userId = $this->checkAuth();
        
        if ($question->questionnaire->created_by !== $userId) {
            abort(403);
        }

        $question->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus!');
    }
}