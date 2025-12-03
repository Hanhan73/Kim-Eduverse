<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeminarController extends Controller
{
    /**
     * Display a listing of seminars
     */
    public function index()
    {
        $seminars = Seminar::with(['preTest', 'postTest', 'enrollments'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);

        return view('admin.digital.seminars.index', compact('seminars'));
    }

    /**
     * Show the form for creating a new seminar
     */
    public function create()
    {
        $quizzes = Quiz::where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('admin.digital.seminars.create', compact('quizzes'));
    }

    /**
     * Store a newly created seminar
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'instructor_name' => 'required|string|max:255',
            'instructor_bio' => 'nullable|string',
            'material_pdf_path' => 'nullable|string',
            'material_description' => 'nullable|string',
            'certificate_template' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')
                    ->store('seminars/thumbnails', 'public');
            }

            // Generate slug
            $validated['slug'] = Str::slug($validated['title']);

            // Handle Pre-Test
            if ($request->pre_test_mode === 'new') {
                $preTest = $this->createQuiz($request, 'pre');
                $validated['pre_test_id'] = $preTest->id;
            } else {
                $validated['pre_test_id'] = $request->pre_test_id;
            }

            // Handle Post-Test
            if ($request->post_test_mode === 'new') {
                $postTest = $this->createQuiz($request, 'post');
                $validated['post_test_id'] = $postTest->id;
            } else {
                $validated['post_test_id'] = $request->post_test_id;
            }

            // Create seminar
            $seminar = Seminar::create($validated);

            // Create digital product entry
            $category = DigitalProductCategory::firstOrCreate(
                ['slug' => 'seminar'],
                ['name' => 'Seminar', 'is_active' => true]
            );

            DigitalProduct::create([
                'category_id' => $category->id,
                'name' => $seminar->title,
                'slug' => $seminar->slug,
                'description' => $seminar->description,
                'price' => $seminar->price,
                'thumbnail' => $seminar->thumbnail,
                'type' => 'seminar',
                'seminar_id' => $seminar->id,
                'duration_minutes' => $seminar->duration_minutes,
                'is_active' => $seminar->is_active,
                'is_featured' => $seminar->is_featured,
                'order' => $seminar->order ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.digital.seminars.index')
                ->with('success', 'Seminar berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan seminar: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified seminar
     */
    public function show(Seminar $seminar)
    {
        $seminar->load([
            'preTest.questions',
            'postTest.questions',
            'enrollments' => function ($query) {
                $query->latest()->limit(10);
            }
        ]);

        $stats = [
            'total_enrollments' => $seminar->enrollments->count(),
            'completed' => $seminar->enrollments->where('is_completed', true)->count(),
            'in_progress' => $seminar->enrollments->where('is_completed', false)->count(),
            'avg_pre_test' => round($seminar->enrollments->where('pre_test_passed', true)->avg('pre_test_score'), 1),
            'avg_post_test' => round($seminar->enrollments->where('post_test_passed', true)->avg('post_test_score'), 1),
        ];

        return view('admin.digital.seminars.show', compact('seminar', 'stats'));
    }

    /**
     * Show the form for editing the specified seminar
     */
    public function edit(Seminar $seminar)
    {
        $quizzes = Quiz::where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('admin.digital.seminars.edit', compact('seminar', 'quizzes'));
    }

    /**
     * Update the specified seminar
     */
    public function update(Request $request, Seminar $seminar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'instructor_name' => 'required|string|max:255',
            'instructor_bio' => 'nullable|string',
            'material_pdf_path' => 'nullable|string',
            'material_description' => 'nullable|string',
            'pre_test_id' => 'required|exists:quizzes,id',
            'post_test_id' => 'required|exists:quizzes,id',
            'certificate_template' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($seminar->thumbnail) {
                    Storage::disk('public')->delete($seminar->thumbnail);
                }
                $validated['thumbnail'] = $request->file('thumbnail')
                    ->store('seminars/thumbnails', 'public');
            }

            // Update slug if title changed
            if ($validated['title'] !== $seminar->title) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // Update seminar
            $seminar->update($validated);

            // Update digital product entry
            if ($seminar->digitalProduct) {
                $seminar->digitalProduct->update([
                    'name' => $seminar->title,
                    'slug' => $seminar->slug,
                    'description' => $seminar->description,
                    'price' => $seminar->price,
                    'thumbnail' => $seminar->thumbnail,
                    'duration_minutes' => $seminar->duration_minutes,
                    'is_active' => $seminar->is_active,
                    'is_featured' => $seminar->is_featured,
                    'order' => $seminar->order ?? 0,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.digital.seminars.index')
                ->with('success', 'Seminar berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate seminar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified seminar
     */
    public function destroy(Seminar $seminar)
    {
        // Check if there are enrollments
        if ($seminar->enrollments()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus seminar yang sudah memiliki peserta!');
        }

        // Delete thumbnail
        if ($seminar->thumbnail) {
            Storage::disk('public')->delete($seminar->thumbnail);
        }

        // Delete digital product entry
        if ($seminar->digitalProduct) {
            $seminar->digitalProduct->delete();
        }

        // Delete seminar
        $seminar->delete();

        return redirect()
            ->route('admin.digital.seminars.index')
            ->with('success', 'Seminar berhasil dihapus!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Seminar $seminar)
    {
        $seminar->update(['is_active' => !$seminar->is_active]);

        // Update digital product
        if ($seminar->digitalProduct) {
            $seminar->digitalProduct->update(['is_active' => $seminar->is_active]);
        }

        return back()->with('success', 'Status seminar berhasil diubah!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Seminar $seminar)
    {
        $seminar->update(['is_featured' => !$seminar->is_featured]);

        // Update digital product
        if ($seminar->digitalProduct) {
            $seminar->digitalProduct->update(['is_featured' => $seminar->is_featured]);
        }

        return back()->with('success', 'Status featured berhasil diubah!');
    }

    /**
     * View enrollments for a seminar
     */
    public function enrollments(Seminar $seminar)
    {
        $enrollments = $seminar->enrollments()
            ->with('order')
            ->latest()
            ->paginate(20);

        return view('admin.digital.seminars.enrollments', compact('seminar', 'enrollments'));
    }

    /**
     * Create a new quiz with questions
     */
    private function createQuiz(Request $request, $type)
    {
        // Create quiz
        $quiz = Quiz::create([
            'title' => $request->input("{$type}_test_title"),
            'slug' => Str::slug($request->input("{$type}_test_title")),
            'description' => "Quiz untuk seminar: " . $request->title,
            'duration_minutes' => $request->input("{$type}_test_duration", 30),
            'passing_score' => $request->input("{$type}_test_passing_score", 70),
            'max_attempts' => $request->input("{$type}_test_max_attempts", 3),
            'is_active' => true,
        ]);

        // Create questions
        $questions = $request->input("{$type}_questions", []);
        foreach ($questions as $index => $questionData) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question'],
                'option_a' => $questionData['option_a'],
                'option_b' => $questionData['option_b'],
                'option_c' => $questionData['option_c'],
                'option_d' => $questionData['option_d'],
                'option_e' => $questionData['option_e'] ?? null,
                'correct_answer' => $questionData['correct'],
                'points' => $questionData['points'] ?? 1,
                'order' => $index + 1,
            ]);
        }

        return $quiz;
    }

    // Di dalam AdminSeminarController.php

    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'required|integer|min:1',
            'quiz_type' => 'required|in:pre,post', // Asumsi ada kolom ini
        ]);

        try {
            DB::beginTransaction();

            $quiz = Quiz::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'passing_score' => $validated['passing_score'],
                'max_attempts' => $validated['max_attempts'],
                'type' => $validated['quiz_type'] . '_test',
                'is_active' => true,
                // Tidak perlu course_id atau quizable_id di sini
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'quiz' => $quiz,
                'message' => 'Quiz berhasil dibuat!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat quiz: ' . $e->getMessage()
            ], 500);
        }
    }
}
