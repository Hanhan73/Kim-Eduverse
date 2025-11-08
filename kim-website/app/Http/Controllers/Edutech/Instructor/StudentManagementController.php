<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\CourseBatch;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentManagementController extends Controller
{
    public function index()
    {
        $instructorId = session('edutech_user_id');
        
        // Ambil semua course milik instructor
        $courses = Course::where('instructor_id', $instructorId)
            ->withCount('enrollments')
            ->get();

        // Hitung siswa unik dan statistik
        $courseIds = $courses->pluck('id')->toArray();

        $studentIds = Enrollment::whereIn('course_id', $courseIds)
            ->pluck('student_id')
            ->unique();

        $totalStudents = $studentIds->count();
        $activeEnrollments = Enrollment::whereIn('course_id', $courseIds)
            ->where('status', 'active')
            ->count();
        $completedEnrollments = Enrollment::whereIn('course_id', $courseIds)
            ->where('status', 'completed')
            ->count();

        // Ambil semua batch untuk course-course ini (satu query), urutkan terbaru dulu
        $batches = CourseBatch::whereIn('course_id', $courseIds)
            ->orderBy('start_date', 'desc')
            ->get()
            ->groupBy('course_id'); // collection keyed by course_id

        // Pasang latest_batch_id (atau null) ke tiap course — aman walau tidak ada batch
        foreach ($courses as $course) {
            $batchGroup = $batches->get($course->id); // returns Collection|null
            $course->latest_batch_id = optional($batchGroup?->first())->id; // null jika tidak ada
        }

        return view('edutech.instructor.students.index', compact(
            'courses',
            'totalStudents',
            'activeEnrollments',
            'completedEnrollments'
        ));
    }


    public function courseStudents($courseId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batchId = request('batch');
        
        // Load batches
        $batches = CourseBatch::where('course_id', $courseId)
            ->withCount('enrollments')
            ->orderBy('start_date', 'desc')
            ->get();

        // Load enrollments - filter by batch if selected
        $query = Enrollment::with('student')
            ->where('course_id', $courseId);
        
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }
        
        $enrollments = $query->orderBy('enrolled_at', 'desc')->get();

        return view('edutech.instructor.students.course-students', compact('course', 'enrollments', 'batches', 'batchId'));
    }

    public function assignBatch(Request $request, $courseId)
    {
        $request->validate([
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'exists:enrollments,id',
            'batch_id' => 'required|exists:course_batches,id',
        ]);

        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $request->batch_id)
            ->firstOrFail();

        // Update enrollments
        Enrollment::whereIn('id', $request->enrollment_ids)
            ->where('course_id', $courseId)
            ->update(['batch_id' => $request->batch_id]);

        return redirect()
            ->route('edutech.instructor.students.course', $courseId)
            ->with('success', 'Siswa berhasil dimasukkan ke batch: ' . $batch->batch_name);
    }

    public function attendance($courseId, $batchId = null)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->with('liveSessions')
            ->firstOrFail();

        // If batch not specified, redirect to batch selection
        if (!$batchId) {
            $batches = CourseBatch::where('course_id', $courseId)
                ->withCount('enrollments')
                ->get();
            
            return view('edutech.instructor.students.select-batch', compact('course', 'batches'));
        }

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $batchId)
            ->with('enrollments.student')
            ->firstOrFail();

        $students = $batch->enrollments->map(function($enrollment) {
            return $enrollment->student;
        });

        $selectedDate = request('date', now()->format('Y-m-d'));
        
        // Get attendance records for selected date and batch
        $attendances = Attendance::where('course_id', $courseId)
            ->where('batch_id', $batchId)
            ->where('attendance_date', $selectedDate)
            ->get()
            ->keyBy('student_id');

        return view('edutech.instructor.students.attendance', compact(
            'course',
            'batch',
            'students',
            'selectedDate',
            'attendances'
        ));
    }

    public function storeAttendance(Request $request, $courseId, $batchId)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'type' => 'required|in:offline,online',
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:users,id',
            'students.*.status' => 'required|in:present,absent,late,excused',
            'students.*.notes' => 'nullable|string',
        ]);

        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $batchId)
            ->firstOrFail();

        foreach ($request->students as $studentData) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentData['student_id'],
                    'course_id' => $courseId,
                    'batch_id' => $batchId,
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'type' => $request->type,
                    'status' => $studentData['status'],
                    'notes' => $studentData['notes'] ?? null,
                    'check_in_time' => $studentData['status'] === 'present' ? now()->format('H:i:s') : null,
                ]
            );
        }

        return redirect()
            ->route('edutech.instructor.students.attendance', [$courseId, $batchId])
            ->with('success', 'Presensi berhasil disimpan!');
    }

    public function attendanceReport($courseId, $batchId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        // If no batch specified, show all batches report
        if (!$batchId) {
            $batches = CourseBatch::where('course_id', $courseId)
                ->withCount('enrollments')
                ->get();
            
            return view('edutech.instructor.students.select-batch-report', compact('course', 'batches'));
        }

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $batchId)
            ->with('enrollments.student')
            ->firstOrFail();

        $students = $batch->enrollments->map(function($enrollment) use ($courseId, $batchId) {
            $student = $enrollment->student;
            
            $attendances = Attendance::where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->where('batch_id', $batchId)
                ->get();

            $student->total_sessions = $attendances->count();
            $student->present_count = $attendances->where('status', 'present')->count();
            $student->absent_count = $attendances->where('status', 'absent')->count();
            $student->late_count = $attendances->where('status', 'late')->count();
            $student->attendance_percentage = $student->total_sessions > 0 
                ? round(($student->present_count / $student->total_sessions) * 100, 1)
                : 0;

            return $student;
        });

        return view('edutech.instructor.students.attendance-report', compact('course', 'batch', 'students'));
    }

    public function studentDetail($studentId)
    {
        $instructorId = session('edutech_user_id');
        
        $student = User::findOrFail($studentId);
        
        // Get enrollments in instructor's courses
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');
        
        $enrollments = Enrollment::with('course')
            ->where('student_id', $studentId)
            ->whereIn('course_id', $courseIds)
            ->get();

        // Get attendance summary
        $attendances = Attendance::where('student_id', $studentId)
            ->whereIn('course_id', $courseIds)
            ->get();

        return view('edutech.instructor.students.detail', compact('student', 'enrollments', 'attendances'));
    }
}