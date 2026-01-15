<?php

namespace App\Http\Controllers\Digital\Collaborator;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\Quiz;
use App\Models\User;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CollaboratorSeminarController extends Controller
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
        
        $seminars = Seminar::where('created_by', $userId)
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Seminar::where('created_by', $userId)->count(),
            'active' => Seminar::where('created_by', $userId)->where('is_active', true)->count(),
            'total_enrollments' => Seminar::where('created_by', $userId)->withCount('enrollments')->get()->sum('enrollments_count'),
        ];

        return view('digital.collaborator.seminars.index', compact('seminars', 'stats'));
    }

    public function create()
    {
        $this->checkAuth();
        
        // Get available quizzes created by this collaborator for pre/post tests
        $userId = session('digital_admin_id');
        $quizzes = Quiz::where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('digital.collaborator.seminars.create', compact('quizzes'));
    }

    public function store(Request $request)
    {
        $userId = $this->checkAuth();
        
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
        ]);

        try {
            DB::beginTransaction();

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')
                    ->store('seminars/thumbnails', 'public');
            }

            // Generate slug
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
            $validated['created_by'] = $userId;
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = false;
            $validated['order'] = 0;

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
                'collaborator_id' => $userId,
                'is_active' => $seminar->is_active,
                'is_featured' => false,
                'order' => 0,
            ]);

            DB::commit();

            return redirect()
                ->route('digital.collaborator.seminars.index')
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
        $userId = $this->checkAuth();
        if ($seminar->created_by !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke seminar ini');
        }

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

        return view('digital.collaborator.seminars.show', compact('seminar', 'stats'));
    }

    public function edit(Seminar $seminar)
    {
        $userId = $this->checkAuth();
        if ($seminar->created_by !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke seminar ini');
        }

        $quizzes = Quiz::where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('digital.collaborator.seminars.edit', compact('seminar', 'quizzes'));
    }

    public function update(Request $request, Seminar $seminar)
    {
        $userId = $this->checkAuth();
        if ($seminar->created_by !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke seminar ini');
        }

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
                $validated['slug'] = Str::slug($validated['title']) . '-' . time();
            }

            $validated['is_active'] = $request->has('is_active');

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
                ]);
            }

            DB::commit();

            return redirect()
                ->route('digital.collaborator.seminars.index')
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
        $userId = $this->checkAuth();
        if ($seminar->created_by !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke seminar ini');
        }

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
            ->route('digital.collaborator.seminars.index')
            ->with('success', 'Seminar berhasil dihapus!');
    }

    public function toggleActive(Seminar $seminar)
    {
        $userId = $this->checkAuth();
        if ($seminar->created_by !== $userId) {
            abort(403);
        }

        $seminar->update(['is_active' => !$seminar->is_active]);

        // Update digital product
        if ($seminar->digitalProduct) {
            $seminar->digitalProduct->update(['is_active' => $seminar->is_active]);
        }

        return back()->with('success', 'Status seminar berhasil diubah!');
    }
}