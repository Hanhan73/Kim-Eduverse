<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\CertificatePdfService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificatePdfService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Show all certificates for the current student
     */
    public function index()
    {
        $studentId = session('edutech_user_id');

        $certificates = Enrollment::with(['course.instructor'])
            ->where('student_id', $studentId)
            ->whereNotNull('certificate_issued_at')
            ->latest('certificate_issued_at')
            ->get();

        return view('edutech.student.certificates', compact('certificates'));
    }

    /**
     * Download course completion certificate
     */
    public function download($id)
    {
        $enrollment = Enrollment::where('student_id', session('edutech_user_id'))
            ->whereNotNull('certificate_issued_at')
            ->findOrFail($id);

        $pdf = $this->certificateService->generateCourseCertificate($enrollment);

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'attachment; filename="certificate-' . $enrollment->certified_number . '.pdf"'
            );
    }

    /**
     * Download degree certificate
     */
    public function downloadDegree($id)
    {
        $enrollment = Enrollment::where('student_id', session('edutech_user_id'))
            ->whereNotNull('degree_certificate_issued_at')
            ->findOrFail($id);

        if (!$enrollment->course->has_degree) {
            abort(404, 'This course does not award a degree certificate');
        }

        $pdf = $this->certificateService->generateDegreeCertificate($enrollment);

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'attachment; filename="degree-certificate-' . $enrollment->degree_certificate_number . '.pdf"'
            );
    }

    /**
     * View certificate in browser
     */
    public function view($id)
    {
        $enrollment = Enrollment::where('student_id', session('edutech_user_id'))
            ->whereNotNull('certificate_issued_at')
            ->with(['student', 'course.instructor'])
            ->findOrFail($id);

        return view('edutech.student.certificate-view', compact('enrollment'));
    }

    /**
     * View degree certificate in browser
     */
    public function viewDegree($id)
    {
        $enrollment = Enrollment::where('student_id', session('edutech_user_id'))
            ->whereNotNull('degree_certificate_issued_at')
            ->with(['student', 'course.instructor'])
            ->findOrFail($id);

        if (!$enrollment->course->has_degree) {
            abort(404, 'This course does not award a degree certificate');
        }

        return view('edutech.student.degree-view', compact('enrollment'));
    }
}
