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
use Barryvdh\DomPDF\Facade\Pdf;

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

        // PERBAIKAN: Get all meetings untuk batch ini
        $meetings = Attendance::where('batch_id', $batchId)
            ->select('meeting_number', 'meeting_topic', 'attendance_date', 'type')
            ->distinct()
            ->orderBy('meeting_number', 'desc')
            ->get();

        // Get next meeting number
        $nextMeeting = $meetings->count() > 0 ? $meetings->first()->meeting_number + 1 : 1;

        $selectedDate = request('date', now()->format('Y-m-d'));
        $selectedMeeting = request('meeting', $nextMeeting);
        
        // Get attendance untuk meeting tertentu
        $attendances = Attendance::where('course_id', $courseId)
            ->where('batch_id', $batchId)
            ->where('meeting_number', $selectedMeeting)
            ->get()
            ->keyBy('student_id');


        return view('edutech.instructor.students.attendance', compact(
            'course',
            'batch',
            'students',
            'selectedDate',
            'selectedMeeting',
            'nextMeeting',
            'meetings',
            'attendances'
        ));
    }

       public function storeAttendance(Request $request, $courseId, $batchId)
    {
        $request->validate([
            'meeting_number' => 'required|integer|min:1',
            'meeting_topic' => 'required|string|max:255',
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

        // Check if meeting already exists
        $exists = Attendance::where('batch_id', $batchId)
            ->where('meeting_number', $request->meeting_number)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Pertemuan ke-' . $request->meeting_number . ' sudah ada! Silakan gunakan nomor pertemuan yang berbeda.');
        }

        foreach ($request->students as $studentData) {
            Attendance::create([
                'student_id' => $studentData['student_id'],
                'course_id' => $courseId,
                'batch_id' => $batchId,
                'meeting_number' => $request->meeting_number,
                'meeting_topic' => $request->meeting_topic,
                'attendance_date' => $request->attendance_date,
                'type' => $request->type,
                'status' => $studentData['status'],
                'notes' => $studentData['notes'] ?? null,
                'check_in_time' => $studentData['status'] === 'present' ? now()->format('H:i:s') : null,
            ]);
        }

        return redirect()
            ->route('edutech.instructor.students.attendance', [$courseId, $batchId])
            ->with('success', 'Presensi pertemuan ke-' . $request->meeting_number . ' berhasil disimpan!');
    }

    public function editAttendance($courseId, $batchId, $meetingNumber)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $batchId)
            ->with('enrollments.student')
            ->firstOrFail();

        $attendances = Attendance::where('batch_id', $batchId)
            ->where('meeting_number', $meetingNumber)
            ->with('student')
            ->get();

        if ($attendances->isEmpty()) {
            abort(404, 'Data pertemuan tidak ditemukan');
        }

        $meeting = $attendances->first();

        return view('edutech.instructor.students.attendance-edit', compact(
            'course',
            'batch',
            'attendances',
            'meeting',
            'meetingNumber'
        ));
    }

    public function updateAttendance(Request $request, $courseId, $batchId, $meetingNumber)
    {
        $request->validate([
            'meeting_topic' => 'required|string|max:255',
            'attendance_date' => 'required|date',
            'type' => 'required|in:offline,online',
            'students' => 'required|array',
            'students.*.status' => 'required|in:present,absent,late,excused',
            'students.*.notes' => 'nullable|string',
        ]);

        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        foreach ($request->students as $studentId => $studentData) {
            Attendance::where('batch_id', $batchId)
                ->where('meeting_number', $meetingNumber)
                ->where('student_id', $studentId)
                ->update([
                    'meeting_topic' => $request->meeting_topic,
                    'attendance_date' => $request->attendance_date,
                    'type' => $request->type,
                    'status' => $studentData['status'],
                    'notes' => $studentData['notes'] ?? null,
                    'check_in_time' => $studentData['status'] === 'present' ? now()->format('H:i:s') : null,
                ]);
        }

        return redirect()
            ->route('edutech.instructor.students.attendance', [$courseId, $batchId])
            ->with('success', 'Presensi pertemuan ke-' . $meetingNumber . ' berhasil diupdate!');
    }

    public function attendanceReport($courseId, $batchId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

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

        // Get all meetings
        $meetings = Attendance::where('batch_id', $batchId)
            ->select('meeting_number', 'meeting_topic', 'attendance_date')
            ->distinct()
            ->orderBy('meeting_number')
            ->get();

        // Get students with attendance data
        $students = $batch->enrollments->map(function($enrollment) use ($courseId, $batchId, $meetings) {
            $student = $enrollment->student;
            
            $attendances = Attendance::where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->where('batch_id', $batchId)
                ->get();

            $student->total_sessions = $meetings->count();
            $student->present_count = $attendances->where('status', 'present')->count();
            $student->absent_count = $attendances->where('status', 'absent')->count();
            $student->late_count = $attendances->where('status', 'late')->count();
            $student->excused_count = $attendances->where('status', 'excused')->count();
            $student->attendance_percentage = $student->total_sessions > 0 
                ? round(($student->present_count / $student->total_sessions) * 100, 1)
                : 0;

            return $student;
        });

        return view('edutech.instructor.students.attendance-report', compact('course', 'batch', 'students', 'meetings'));
    }

    public function downloadAttendanceReport($courseId, $batchId, $meetingNumber = null)
{
    $instructorId = session('edutech_user_id');
    
    $course = Course::where('instructor_id', $instructorId)
        ->where('id', $courseId)
        ->firstOrFail();

    $batch = CourseBatch::where('course_id', $courseId)
        ->where('id', $batchId)
        ->with('enrollments.student')
        ->firstOrFail();

    if ($meetingNumber) {
        // Single meeting report
        $attendances = Attendance::where('batch_id', $batchId)
            ->where('meeting_number', $meetingNumber)
            ->with('student')
            ->get();

        if ($attendances->isEmpty()) {
            return back()->with('error', 'Data attendance tidak ditemukan');
        }

        $meeting = $attendances->first();

        $pdf = Pdf::loadView('edutech.instructor.students.pdf-meeting', compact('course', 'batch', 'attendances', 'meeting'))
            ->setPaper('a4', 'portrait');
        
        return $pdf->download('attendance-' . $batch->batch_name . '-pertemuan-' . $meetingNumber . '.pdf');
    } else {
        // Full batch report
        $meetings = Attendance::where('batch_id', $batchId)
            ->select('meeting_number', 'meeting_topic', 'attendance_date')
            ->distinct()
            ->orderBy('meeting_number')
            ->get();

        if ($meetings->isEmpty()) {
            return back()->with('error', 'Belum ada data attendance');
        }

        // FIX: Gunakan array biasa, bukan assign ke model property
        $studentsData = [];
        
        foreach ($batch->enrollments as $enrollment) {
            $student = $enrollment->student;
            
            $attendanceRecords = [];
            foreach ($meetings as $meeting) {
                $record = Attendance::where('batch_id', $batchId)
                    ->where('student_id', $student->id)
                    ->where('meeting_number', $meeting->meeting_number)
                    ->first();
                
                $attendanceRecords[$meeting->meeting_number] = $record;
            }
            
            // Simpan sebagai array dengan struktur yang jelas
            $studentsData[] = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'attendance_records' => $attendanceRecords
            ];
        }

        $pdf = Pdf::loadView('edutech.instructor.students.pdf-full', compact('course', 'batch', 'meetings', 'studentsData'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('attendance-full-' . $batch->batch_name . '.pdf');
    }
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