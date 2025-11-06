<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $studentId = session('edutech_user_id');

        // Statistics
        $stats = [
            'active_courses' => Enrollment::where('user_id', $studentId)
                ->where('status', 'active')
                ->count(),
            'completed_courses' => Enrollment::where('user_id', $studentId)
                ->where('status', 'completed')
                ->count(),
            'certificates' => \App\Models\Certificate::where('user_id', $studentId)->count(),
            'avg_progress' => Enrollment::where('user_id', $studentId)
                ->where('status', 'active')
                ->avg('progress_percentage') ?? 0,
        ];

        // My active courses
        $activeCourses = Enrollment::where('user_id', $studentId)
            ->where('status', 'active')
            ->with('course.instructor')
            ->latest()
            ->take(6)
            ->get();

        // Upcoming live sessions
        $upcomingSessions = \App\Models\LiveSession::whereHas('course.enrollments', function($q) use ($studentId) {
            $q->where('user_id', $studentId)
              ->where('status', 'active');
        })
        ->where('scheduled_at', '>', now())
        ->with('course')
        ->orderBy('scheduled_at')
        ->take(5)
        ->get();

        // Recommended courses
        $recommendedCourses = Course::published()
            ->whereNotIn('id', function($query) use ($studentId) {
                $query->select('course_id')
                    ->from('enrollments')
                    ->where('user_id', $studentId);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('edutech.student.dashboard', compact(
            'stats',
            'activeCourses',
            'upcomingSessions',
            'recommendedCourses'
        ));
    }

    // === MY COURSES ===
    public function myCourses(Request $request)
    {
        $studentId = session('edutech_user_id');

        $query = Enrollment::where('user_id', $studentId)
            ->with('course.instructor');

        // Filter by status
        $status = $request->get('status', 'active');
        if ($status != 'all') {
            $query->where('status', $status);
        }

        $enrollments = $query->latest()->paginate(12);

        return view('edutech.student.my-courses', compact('enrollments', 'status'));
    }

    // === COURSE DETAIL & LEARNING ===
    public function courseDetail($id)
    {
        $studentId = session('edutech_user_id');

        $enrollment = Enrollment::where('user_id', $studentId)
            ->where('course_id', $id)
            ->with(['course.materials', 'course.instructor', 'course.liveSessions'])
            ->firstOrFail();

        // Get progress for each material
        $materialProgress = \App\Models\MaterialProgress::where('user_id', $studentId)
            ->whereIn('material_id', $enrollment->course->materials->pluck('id'))
            ->get()
            ->keyBy('material_id');

        return view('edutech.student.course-detail', compact('enrollment', 'materialProgress'));
    }

    // === LEARNING PAGE ===
    public function learnMaterial($enrollmentId, $materialId)
    {
        $studentId = session('edutech_user_id');

        $enrollment = Enrollment::where('user_id', $studentId)
            ->where('id', $enrollmentId)
            ->with('course')
            ->firstOrFail();

        $material = CourseMaterial::where('course_id', $enrollment->course_id)
            ->where('id', $materialId)
            ->firstOrFail();

        // Get all materials for navigation
        $allMaterials = CourseMaterial::where('course_id', $enrollment->course_id)
            ->orderBy('order')
            ->get();

        // Mark as in progress
        $progress = \App\Models\MaterialProgress::firstOrCreate([
            'user_id' => $studentId,
            'material_id' => $materialId,
        ], [
            'is_completed' => false,
            'last_accessed_at' => now(),
        ]);

        $progress->update(['last_accessed_at' => now()]);

        return view('edutech.student.learn', compact('enrollment', 'material', 'allMaterials', 'progress'));
    }

    // === COMPLETE MATERIAL ===
    public function completeMaterial(Request $request, $materialId)
    {
        $studentId = session('edutech_user_id');

        $material = CourseMaterial::findOrFail($materialId);

        // Mark material as completed
        $progress = \App\Models\MaterialProgress::updateOrCreate([
            'user_id' => $studentId,
            'material_id' => $materialId,
        ], [
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Update course progress
        $this->updateCourseProgress($studentId, $material->course_id);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diselesaikan!'
        ]);
    }

    // === ENROLL TO COURSE ===
    public function enrollCourse(Request $request, $courseId)
    {
        $studentId = session('edutech_user_id');

        $course = Course::findOrFail($courseId);

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', 'Anda sudah terdaftar di course ini!');
        }

        // Check if course is full
        if ($course->isFull()) {
            return back()->with('error', 'Course sudah penuh!');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => $studentId,
            'course_id' => $courseId,
            'status' => $course->price > 0 ? 'pending' : 'active', // Free courses auto-active
            'paid_amount' => $course->price,
            'enrolled_at' => $course->price > 0 ? null : now(),
        ]);

        if ($course->price > 0) {
            return redirect()
                ->route('edutech.student.payment', $courseId)
                ->with('success', 'Silakan lakukan pembayaran untuk mengakses course!');
        } else {
            return redirect()
                ->route('edutech.student.my-courses')
                ->with('success', 'Selamat! Anda berhasil bergabung dengan course ini!');
        }
    }

    // === CERTIFICATES ===
    public function certificates()
    {
        $studentId = session('edutech_user_id');

        $certificates = \App\Models\Certificate::where('user_id', $studentId)
            ->with('course')
            ->latest()
            ->paginate(12);

        return view('edutech.student.certificates', compact('certificates'));
    }

    // Helper: Update course progress
    private function updateCourseProgress($studentId, $courseId)
    {
        $enrollment = Enrollment::where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) return;

        $totalMaterials = CourseMaterial::where('course_id', $courseId)->count();
        $completedMaterials = \App\Models\MaterialProgress::where('user_id', $studentId)
            ->whereIn('material_id', function($query) use ($courseId) {
                $query->select('id')
                    ->from('course_materials')
                    ->where('course_id', $courseId);
            })
            ->where('is_completed', true)
            ->count();

        $progressPercentage = $totalMaterials > 0 
            ? round(($completedMaterials / $totalMaterials) * 100) 
            : 0;

        $enrollment->update([
            'progress_percentage' => $progressPercentage
        ]);

        // If 100%, mark as completed
        if ($progressPercentage >= 100) {
            $enrollment->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // Generate certificate if passing score met
            $this->generateCertificate($studentId, $courseId);
        }
    }

    // Helper: Generate certificate
    private function generateCertificate($studentId, $courseId)
    {
        // Check if certificate already exists
        $exists = \App\Models\Certificate::where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();

        if (!$exists) {
            \App\Models\Certificate::create([
                'user_id' => $studentId,
                'course_id' => $courseId,
                'certificate_number' => 'CERT-' . strtoupper(uniqid()),
                'issued_at' => now(),
            ]);
        }
    }
}