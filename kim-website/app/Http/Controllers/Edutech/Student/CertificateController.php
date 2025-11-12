<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function index()
    {
        $studentId = session('edutech_user_id');

        $certificates = Enrollment::with('course.instructor')
            ->where('student_id', $studentId)
            ->whereNotNull('certificate_issued_at')
            ->latest('certificate_issued_at')
            ->get();
        
        return view('edutech.student.certificates', compact('certificates'));
    }

    public function download($id)
    {
        $certificate = Enrollment::with(['student', 'course.instructor'])
            ->where('id', $id)
            ->where('student_id', session('edutech_user_id'))
            ->whereNotNull('certificate_issued_at')
            ->firstOrFail();

        // Generate PDF on-the-fly (tidak perlu simpan file)
        $pdf = Pdf::loadView('edutech.student.template', compact('certificate'));
        
        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }
}
