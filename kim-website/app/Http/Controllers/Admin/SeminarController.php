<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\Quiz;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeminarController extends Controller
{
    public function index()
    {
        $seminars = Seminar::with(['collaborator', 'preTest', 'postTest', 'enrollments'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);

        return view('admin.digital.seminars.index', compact('seminars'));
    }

    public function create()
    {
        // HANYA ambil quiz yang:
        // 1. Tidak punya course_id DAN tidak punya module_id (quiz standalone untuk seminar)
        // 2. ATAU quiz yang quizable_type = 'App\Models\Seminar'
        $quizzes = Quiz::where(function($query) {
                $query->whereNull('course_id')
                      ->whereNull('module_id');
            })
            ->orWhere('quizable_type', 'App\Models\Seminar')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();
        
        $collaborators = User::where('role', 'collaborator')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.digital.seminars.create', compact('quizzes', 'collaborators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'collaborator_id' => 'required|exists:users,id',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_bio' => 'nullable|string',
            'material_pdf_path' => 'nullable|url',
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

            // Handle thumbnail
            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')
                    ->store('seminars/thumbnails', 'public');
            }

            // Generate slug
            $validated['slug'] = Str::slug($validated['title']);
            $validated['created_by'] = auth()->id();

            // Create DigitalProduct
            $category = DigitalProductCategory::firstOrCreate(
                ['slug' => 'seminar'],
                ['name' => 'Seminar', 'is_active' => true]
            );

            $product = DigitalProduct::create([
                'category_id' => $category->id,
                'collaborator_id' => $validated['collaborator_id'],
                'name' => $validated['title'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'thumbnail' => $validated['thumbnail'] ?? null,
                'type' => 'seminar',
                'duration_minutes' => $validated['duration_minutes'],
                'is_active' => $validated['is_active'] ?? true,
                'is_featured' => $validated['is_featured'] ?? false,
                'order' => $validated['order'] ?? 0,
            ]);

            // Create Seminar with product_id
            $validated['product_id'] = $product->id;
            $seminar = Seminar::create($validated);

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

    public function show(Seminar $seminar)
    {
        $seminar->load([
            'collaborator',
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

    public function edit(Seminar $seminar)
    {
        // HANYA ambil quiz yang:
        // 1. Tidak punya course_id DAN tidak punya module_id (quiz standalone untuk seminar)
        // 2. ATAU quiz yang quizable_type = 'App\Models\Seminar'
        $quizzes = Quiz::where(function($query) {
                $query->whereNull('course_id')
                      ->whereNull('module_id');
            })
            ->orWhere('quizable_type', 'App\Models\Seminar')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();
        
        $collaborators = User::where('role', 'collaborator')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.digital.seminars.edit', compact('seminar', 'quizzes', 'collaborators'));
    }

    public function update(Request $request, Seminar $seminar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'collaborator_id' => 'required|exists:users,id',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_bio' => 'nullable|string',
            'material_pdf_path' => 'nullable|url',
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

            // Handle thumbnail
            if ($request->hasFile('thumbnail')) {
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

            // Sync to DigitalProduct
            if ($seminar->digitalProduct) {
                $seminar->digitalProduct->update([
                    'collaborator_id' => $validated['collaborator_id'],
                    'name' => $validated['title'],
                    'slug' => $validated['slug'] ?? $seminar->slug,
                    'description' => $validated['description'],
                    'price' => $validated['price'],
                    'thumbnail' => $validated['thumbnail'] ?? $seminar->thumbnail,
                    'duration_minutes' => $validated['duration_minutes'],
                    'is_active' => $validated['is_active'] ?? $seminar->is_active,
                    'is_featured' => $validated['is_featured'] ?? $seminar->is_featured,
                    'order' => $validated['order'] ?? $seminar->order,
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

    public function destroy(Seminar $seminar)
    {
        if ($seminar->enrollments()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus seminar yang sudah memiliki peserta!');
        }

        if ($seminar->thumbnail) {
            Storage::disk('public')->delete($seminar->thumbnail);
        }

        // Delete digital product first
        if ($seminar->digitalProduct) {
            $seminar->digitalProduct->delete();
        }

        $seminar->delete();

        return redirect()
            ->route('admin.digital.seminars.index')
            ->with('success', 'Seminar berhasil dihapus!');
    }

    public function toggleActive(Seminar $seminar)
    {
        $seminar->update(['is_active' => !$seminar->is_active]);

        if ($seminar->digitalProduct) {
            $seminar->digitalProduct->update(['is_active' => $seminar->is_active]);
        }

        return back()->with('success', 'Status seminar berhasil diubah!');
    }

    public function toggleFeatured(Seminar $seminar)
    {
        $seminar->update(['is_featured' => !$seminar->is_featured]);

        if ($seminar->digitalProduct) {
            $seminar->digitalProduct->update(['is_featured' => $seminar->is_featured]);
        }

        return back()->with('success', 'Status featured berhasil diubah!');
    }

    public function enrollments(Seminar $seminar)
    {
        $enrollments = $seminar->enrollments()
            ->with('order')
            ->latest()
            ->paginate(20);

        return view('admin.digital.seminars.enrollments', compact('seminar', 'enrollments'));
    }

    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'required|integer|min:1',
            'quiz_type' => 'required|in:pre,post',
        ]);

        try {
            DB::beginTransaction();

            // Buat quiz khusus untuk seminar (tanpa course_id dan module_id)
            $quiz = Quiz::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'passing_score' => $validated['passing_score'],
                'max_attempts' => $validated['max_attempts'],
                'is_active' => true,
                'type' => $validated['quiz_type'] === 'pre' ? 'pre_test' : 'post_test',
                'quizable_type' => 'App\Models\Seminar', // Tandai sebagai quiz seminar
                // course_id dan module_id akan NULL (default)
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