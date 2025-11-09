<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use PDF; // If using barryvdh/laravel-dompdf

class CertificatesController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'course'])
            ->whereNotNull('certificate_issued_at');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Course filter
        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('certificate_issued_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('certificate_issued_at', '<=', $request->date_to);
        }

        $certificates = $query->latest('certificate_issued_at')->paginate(20);
        $courses = Course::orderBy('title')->get();

        $stats = [
            'total_certificates' => Enrollment::whereNotNull('certificate_issued_at')->count(),
            'this_month' => Enrollment::whereNotNull('certificate_issued_at')
                ->whereMonth('certificate_issued_at', now()->month)
                ->whereYear('certificate_issued_at', now()->year)
                ->count(),
            'this_year' => Enrollment::whereNotNull('certificate_issued_at')
                ->whereYear('certificate_issued_at', now()->year)
                ->count(),
            'total_students' => Enrollment::whereNotNull('certificate_issued_at')
                ->distinct('student_id')
                ->count('student_id'),
        ];

        return view('edutech.admin.certificates.index', compact('certificates', 'courses', 'stats'));
    }

    public function show($id)
    {
        $certificate = Enrollment::with(['student', 'course.instructor'])
            ->whereNotNull('certificate_issued_at')
            ->findOrFail($id);

        return view('edutech.admin.certificates.show', compact('certificate'));
    }

    public function issue($enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);

        // Check if already completed
        if (!$enrollment->completed_at) {
            return redirect()->back()
                ->with('error', 'Cannot issue certificate for incomplete course!');
        }

        // Check if certificate already issued
        if ($enrollment->certificate_issued_at) {
            return redirect()->back()
                ->with('error', 'Certificate already issued!');
        }

        // Generate certificate number
        $enrollment->certificate_number = 'CERT-' . strtoupper(uniqid());
        $enrollment->certificate_issued_at = now();
        $enrollment->save();

        return redirect()->back()
            ->with('success', 'Certificate issued successfully!');
    }

    public function revoke($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        
        $enrollment->certificate_number = null;
        $enrollment->certificate_issued_at = null;
        $enrollment->save();

        return redirect()->back()
            ->with('success', 'Certificate revoked successfully!');
    }

    public function download($id)
    {
        $certificate = Enrollment::with(['student', 'course.instructor'])
            ->whereNotNull('certificate_issued_at')
            ->findOrFail($id);

        // Generate PDF (you'll need to create the view)
        $pdf = PDF::loadView('edutech.certificates.template', compact('certificate'));
        
        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }

    public function verify(Request $request)
    {
        $certificateNumber = $request->input('certificate_number');
        
        $certificate = Enrollment::with(['student', 'course'])
            ->where('certificate_number', $certificateNumber)
            ->first();

        if (!$certificate) {
            return view('edutech.admin.certificates.verify', [
                'found' => false,
                'message' => 'Certificate not found'
            ]);
        }

        return view('edutech.admin.certificates.verify', [
            'found' => true,
            'certificate' => $certificate
        ]);
    }
}