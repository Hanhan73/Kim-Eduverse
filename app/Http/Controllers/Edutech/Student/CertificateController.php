<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use App\Services\CertificatePdfService;

class CertificateController extends Controller
{
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

    public function download($id, CertificatePdfService $service)
{
    $enrollment = Enrollment::where('student_id', session('edutech_user_id'))
        ->whereNotNull('certificate_issued_at')
        ->findOrFail($id);

    $pdf = $service->generate($enrollment);

    return response($pdf, 200)
        ->header('Content-Type', 'application/pdf')
        ->header(
            'Content-Disposition',
            'attachment; filename="sertifikat-' . $enrollment->certificate_number . '.pdf"'
        );
}
}