<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\CertificatePdfService;

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
        
        $enrollment->certificate_issued_at = null;
        $enrollment->save();

        return redirect()->back()
            ->with('success', 'Certificate revoked successfully!');
    }



    public function download($id, CertificatePdfService $service)
    {
        $enrollment = Enrollment::whereNotNull('certificate_issued_at')->findOrFail($id);

        return response(
            $service->generate($enrollment),
            200,
            ['Content-Type' => 'application/pdf']
        );
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

    // DEBUG ENDPOINT - Hapus setelah selesai troubleshoot
    public function debug()
    {
        $checks = [];

        // Check 1: FPDI installed?
        $checks['fpdi_installed'] = class_exists('\setasign\Fpdi\Fpdi');

        // Check 2: Template exists?
        $templatePath = storage_path('app/certificates/template.pdf');
        $checks['template_path'] = $templatePath;
        $checks['template_exists'] = file_exists($templatePath);
        $checks['template_readable'] = file_exists($templatePath) ? is_readable($templatePath) : false;
        $checks['template_size'] = file_exists($templatePath) ? filesize($templatePath) : 0;

        // Check 3: Enrollments with certificates
        $checks['total_certificates'] = Enrollment::whereNotNull('certificate_issued_at')->count();
        $checks['sample_certificate'] = Enrollment::with(['student', 'course.instructor'])
            ->whereNotNull('certificate_issued_at')
            ->first();

        // Check 4: Storage permissions
        $checks['storage_path'] = storage_path();
        $checks['storage_writable'] = is_writable(storage_path('app'));
        $checks['certificates_dir_exists'] = is_dir(storage_path('app/certificates'));
        $checks['certificates_dir_writable'] = is_dir(storage_path('app/certificates')) ? is_writable(storage_path('app/certificates')) : false;

        return response()->json($checks, 200, [], JSON_PRETTY_PRINT);
    }
}