<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BatchManagementController extends Controller
{
    public function index($courseId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batches = CourseBatch::where('course_id', $courseId)
            ->withCount('enrollments')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('edutech.instructor.batches.index', compact('course', 'batches'));
    }

    public function create($courseId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        return view('edutech.instructor.batches.create', compact('course'));
    }

    public function store(Request $request, $courseId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $request->validate([
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_students' => 'required|integer|min:1',
        ]);

        // Generate batch code
        $prefix = strtoupper(substr($course->title, 0, 3));
        $month = date('M', strtotime($request->start_date));
        $count = CourseBatch::where('course_id', $courseId)->count() + 1;
        $batchCode = $prefix . '-' . strtoupper($month) . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Handle schedule days
        $scheduleDays = null;
        if ($request->schedule_type === 'custom' && $request->has('schedule_days')) {
            $scheduleDays = $request->schedule_days;
        } elseif ($request->schedule_type === 'weekday') {
            $scheduleDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        } elseif ($request->schedule_type === 'weekend') {
            $scheduleDays = ['saturday', 'sunday'];
        }

        CourseBatch::create([
            'course_id' => $courseId,
            'batch_name' => $request->batch_name,
            'batch_code' => $batchCode,
            'schedule_type' => $request->schedule_type ?? 'weekday',
            'schedule_days' => $scheduleDays,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_students' => $request->max_students,
            'status' => 'upcoming',
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('edutech.instructor.batches.index', $courseId)
            ->with('success', 'Batch berhasil dibuat!');
    }

    public function edit($courseId, $batchId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $batchId)
            ->firstOrFail();

        return view('edutech.instructor.batches.edit', compact('course', 'batch'));
    }

    public function update(Request $request, $courseId, $batchId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $batchId)
            ->firstOrFail();

        $request->validate([
            'batch_name' => 'required|string|max:255',
            'schedule_type' => 'required|in:weekday,weekend,custom',
            'schedule_days' => 'nullable|array',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $batch->update($request->all());

        return redirect()
            ->route('edutech.instructor.batches.index', $courseId)
            ->with('success', 'Batch berhasil diupdate!');
    }

    public function destroy($courseId, $batchId)
    {
        $instructorId = session('edutech_user_id');
        
        $course = Course::where('instructor_id', $instructorId)
            ->where('id', $courseId)
            ->firstOrFail();

        $batch = CourseBatch::where('course_id', $courseId)
            ->where('id', $batchId)
            ->firstOrFail();

        // Check if batch has enrollments
        if ($batch->enrollments()->count() > 0) {
            return redirect()
                ->route('edutech.instructor.batches.index', $courseId)
                ->with('error', 'Tidak dapat menghapus batch yang sudah memiliki siswa!');
        }

        $batch->delete();

        return redirect()
            ->route('edutech.instructor.batches.index', $courseId)
            ->with('success', 'Batch berhasil dihapus!');
    }
}