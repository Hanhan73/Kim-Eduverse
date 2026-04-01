<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\Quiz;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use App\Models\SeminarMaterial;
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
        $quizzes = Quiz::where(function ($query) {
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
            'type' => 'required|string|in:pendidikan,manajemen,kearsipan',
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
            'total_jp' => 'nullable|integer|min:1', // TAMBAH INI
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            $destination = '/home/u597258220/domains/kimeduverse.com/public_html/products/thumbnails';

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true); // buat folder kalau belum ada
            }
            
            $file->move($destination, $filename);
            $validated['thumbnail'] = $filename; // simpan nama file SAJA
        }

            $validated['slug'] = Str::slug($validated['title']);
            $validated['created_by'] = auth()->id();

            $category = DigitalProductCategory::firstOrCreate(
                ['slug' => 'seminar'],
                ['name' => 'Seminar', 'is_active' => true]
            );

            $product = DigitalProduct::create([
                'category_id' => $category->id,
                'collaborator_id' => $validated['collaborator_id'],
                'name' => $validated['title'],
                'slug' => $validated['slug'],
                'seminar_id' => $validated['seminar_id'] ?? null, 
                'description' => $validated['description'],
                'price' => $validated['price'],
                'thumbnail' => $validated['thumbnail'] ?? null,
                'type' => 'on-demand-seminar',
                'duration_minutes' => $validated['duration_minutes'],
                'is_active' => $validated['is_active'] ?? true,
                'is_featured' => $validated['is_featured'] ?? false,
                'order' => $validated['order'] ?? 0,
            ]);

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
        $quizzes = Quiz::where(function ($query) {
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
            'type' => 'required|string|in:pendidikan,manajemen,kearsipan',
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
            'total_jp' => 'nullable|integer|min:1', // TAMBAH INI
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

        if ($request->hasFile('thumbnail')) {
            // Hapus file lama
            if ($product->thumbnail) {
                $oldPath = public_path('products/thumbnails/' . $product->thumbnail);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            $destination = '/home/u597258220/domains/kimeduverse.com/public_html/products/thumbnails';

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            
            $file->move($destination, $filename);
            $validated['thumbnail'] = $filename;
        }

            if ($validated['title'] !== $seminar->title) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            $seminar->update($validated);

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

        if ($product->thumbnail) {
            $oldPath = public_path('products/thumbnails/' . $product->thumbnail);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

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

            $quiz = Quiz::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'passing_score' => $validated['passing_score'],
                'max_attempts' => $validated['max_attempts'],
                'is_active' => true,
                'type' => $validated['quiz_type'] === 'pre' ? 'pre_test' : 'post_test',
                'quizable_type' => 'App\Models\Seminar',
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

    // MATERIAL FUNCTIONS - HAPUS KOLOM JP
    public function storeMaterial(Request $request, Seminar $seminar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxOrder = $seminar->materials()->max('order') ?? 0;

        $material = $seminar->materials()->create([
            'title' => $validated['title'],
            'order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil ditambahkan',
            'material' => $material,
        ]);
    }

    public function updateMaterial(Request $request, Seminar $seminar, SeminarMaterial $material)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $material->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diupdate',
            'material' => $material,
        ]);
    }

    public function destroyMaterial(Seminar $seminar, SeminarMaterial $material)
    {
        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus',
        ]);
    }

    public function reorderMaterials(Request $request, Seminar $seminar)
    {
        $validated = $request->validate([
            'materials' => 'required|array',
            'materials.*.id' => 'required|exists:seminar_materials,id',
            'materials.*.order' => 'required|integer',
        ]);

        foreach ($validated['materials'] as $materialData) {
            SeminarMaterial::where('id', $materialData['id'])
                ->update(['order' => $materialData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan materi berhasil diupdate',
        ]);
    }
}