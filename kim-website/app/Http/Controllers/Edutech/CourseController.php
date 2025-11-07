<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Show course detail
     */
    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['instructor', 'modules.lessons'])
            ->withCount('enrollments')
            ->firstOrFail();

        // Check if user already enrolled
        $isEnrolled = false;
        $enrollment = null;

        if (session()->has('edutech_user_id')) {
            $enrollment = Enrollment::where('student_id', session('edutech_user_id'))
                ->where('course_id', $course->id)
                ->first();
            
            $isEnrolled = $enrollment !== null;
        }

        // Get related courses
        $relatedCourses = Course::where('category', $course->category)
            ->where('id', '!=', $course->id)
            ->where('is_published', true)
            ->with('instructor')
            ->take(3)
            ->get();

        return view('edutech.courses.detail', compact('course', 'isEnrolled', 'enrollment', 'relatedCourses'));
    }

    /**
     * Enroll to course - UPDATED: Menggunakan SLUG bukan ID
     */
    public function enroll(Request $request, $slug)
    {
        // Check if user logged in
        if (!session()->has('edutech_user_id')) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mendaftar course');
        }

        $studentId = session('edutech_user_id');
        
        // Find course by SLUG (bukan ID)
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            // Redirect ke learning page dengan route name yang benar
            return redirect()
                ->route('edutech.courses.learn', $course->slug)
                ->with('info', 'Anda sudah terdaftar di course ini');
        }

        // Create enrollment
        $enrollment = Enrollment::create([
            'student_id' => $studentId,
            'course_id' => $course->id,
            'status' => 'active',
            'progress_percentage' => 0,
            'enrolled_at' => now(),
            'payment_status' => $course->price > 0 ? 'pending' : 'paid',
            'payment_amount' => $course->price,
        ]);

        // If free course, mark as paid immediately and redirect to learning
        if ($course->price == 0) {
            return redirect()
                ->route('edutech.courses.learn', $course->slug)
                ->with('success', 'Selamat! Anda berhasil mendaftar course ini');
        }

        // If paid course, redirect to payment page
        return redirect()
            ->route('edutech.payment.show', $enrollment->id)
            ->with('info', 'Silakan selesaikan pembayaran untuk mengakses course');
    }

    /**
     * Learning page (watch videos, read materials, take quiz)
     */
    public function learn($slug, Request $request)
    {
        // Check if user logged in
        if (!session()->has('edutech_user_id')) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $studentId = session('edutech_user_id');

        // Load course with all relationships
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['instructor', 'modules.lessons', 'modules.quizzes'])
            ->firstOrFail();

        // Check if enrolled
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()
                ->route('edutech.courses.detail', $slug)
                ->with('error', 'Anda harus mendaftar terlebih dahulu');
        }

        // Check payment status for paid courses
        if ($course->price > 0 && $enrollment->payment_status !== 'paid') {
            return redirect()
                ->route('edutech.courses.payment', $enrollment->id)
                ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu');
        }

        // Get specific lesson or first lesson
        $currentLesson = null;
        
        if ($request->has('lesson')) {
            $currentLesson = \App\Models\Lesson::find($request->lesson);
        }
        
        // If no lesson specified, get first lesson from first module
        if (!$currentLesson && $course->modules->count() > 0) {
            $firstModule = $course->modules->first();
            if ($firstModule && $firstModule->lessons->count() > 0) {
                $currentLesson = $firstModule->lessons->first();
            }
        }

        return view('edutech.courses.learn', compact('course', 'enrollment', 'currentLesson'));
    }
}