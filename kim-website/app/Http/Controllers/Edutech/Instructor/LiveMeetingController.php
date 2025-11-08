<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LiveSession;
use Illuminate\Http\Request;

class LiveMeetingController extends Controller
{
    public function index()
    {
        $instructorId = session('edutech_user_id');
        
        $sessions = LiveSession::with('course')
            ->where('instructor_id', $instructorId)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('edutech.instructor.live-meetings.index', compact('sessions'));
    }

    public function create()
    {
        $instructorId = session('edutech_user_id');
        $courses = Course::where('instructor_id', $instructorId)->get();

        return view('edutech.instructor.live-meetings.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_url' => 'required|url',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15',
        ]);

        LiveSession::create([
            'course_id' => $request->course_id,
            'instructor_id' => session('edutech_user_id'),
            'title' => $request->title,
            'description' => $request->description,
            'meeting_url' => $request->meeting_url,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes,
            'status' => 'scheduled',
        ]);

        return redirect()
            ->route('edutech.instructor.live-meetings.index')
            ->with('success', 'Live meeting berhasil dijadwalkan!');
    }

    public function edit(LiveSession $session)
    {
        $instructorId = session('edutech_user_id');
        
        if ($session->instructor_id !== $instructorId) {
            abort(403);
        }

        $courses = Course::where('instructor_id', $instructorId)->get();
        return view('edutech.instructor.live-meetings.edit', compact('session', 'courses'));
    }

    public function update(Request $request, LiveSession $session)
    {
        $instructorId = session('edutech_user_id');
        
        if ($session->instructor_id !== $instructorId) {
            abort(403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_url' => 'required|url',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $session->update($request->all());

        return redirect()
            ->route('edutech.instructor.live-meetings.index')
            ->with('success', 'Live meeting berhasil diupdate!');
    }

    public function destroy(LiveSession $session)
    {
        $instructorId = session('edutech_user_id');
        
        if ($session->instructor_id !== $instructorId) {
            abort(403);
        }

        $session->delete();

        return redirect()
            ->route('edutech.instructor.live-meetings.index')
            ->with('success', 'Live meeting berhasil dihapus!');
    }
}