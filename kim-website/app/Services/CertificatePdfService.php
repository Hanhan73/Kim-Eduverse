<?php

namespace App\Services;

use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificatePdfService
{
    /**
     * Generate Course Completion Certificate
     */
    public function generateCourseCertificate(Enrollment $enrollment)
    {
        $data = [
            'enrollment' => $enrollment,
            'student' => $enrollment->student,
            'course' => $enrollment->course,
            'instructor' => $enrollment->course->instructor,
            'certificate_number' => $enrollment->certified_number,
            'issued_date' => $enrollment->certificate_issued_at,
            'completed_date' => $enrollment->completed_at,
        ];

        $pdf = Pdf::loadView('pdf.course-completion', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->output();
    }

    /**
     * Generate Degree Certificate
     */
    public function generateDegreeCertificate(Enrollment $enrollment)
    {
        if (!$enrollment->course->has_degree) {
            throw new \Exception('This course does not award a degree certificate');
        }

        $data = [
            'enrollment' => $enrollment,
            'student' => $enrollment->student,
            'course' => $enrollment->course,
            'instructor' => $enrollment->course->instructor,
            'certificate_number' => $enrollment->degree_certificate_number,
            'degree_title' => $enrollment->course->degree_title,
            'issued_date' => $enrollment->degree_certificate_issued_at,
            'completed_date' => $enrollment->completed_at,
        ];

        $pdf = Pdf::loadView('pdf.degree', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->output();
    }

    /**
     * Legacy method for backward compatibility
     */
    public function generate(Enrollment $enrollment)
    {
        return $this->generateCourseCertificate($enrollment);
    }
}
