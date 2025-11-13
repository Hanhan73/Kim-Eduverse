<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;

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

    public function download($id)
    {
        $studentId = session('edutech_user_id');
        
        $enrollment = Enrollment::with(['student', 'course.instructor'])
            ->where('student_id', $studentId)
            ->whereNotNull('certificate_issued_at')
            ->findOrFail($id);

        // Path ke template PDF
        $templatePath = storage_path('app/certificates/template.pdf');

        // Check if template exists
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Certificate template not found. Please contact administrator.');
        }

        try {
            // Generate PDF menggunakan FPDI
            $pdf = new Fpdi();
            
            // Load template (Landscape orientation)
            $pdf->AddPage('L'); // L = Landscape (297x210mm)
            $pdf->setSourceFile($templatePath);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 297, 210);

            // Set font untuk text
            $pdf->SetFont('Arial', 'B', 24);
            $pdf->SetTextColor(0, 0, 0);

            // NAMA STUDENT (posisi tengah, Y=100mm dari atas)
            $pdf->SetXY(0, 100);
            $pdf->Cell(297, 10, strtoupper($enrollment->student->name), 0, 0, 'C');

            // COURSE TITLE (posisi tengah, Y=130mm dari atas)
            $pdf->SetFont('Arial', '', 18);
            $pdf->SetXY(0, 130);
            $pdf->Cell(297, 10, $enrollment->course->title, 0, 0, 'C');

            // CERTIFICATE NUMBER (kiri bawah)
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetXY(20, 180);
            $pdf->Cell(80, 5, 'Certificate No: ' . $enrollment->certificate_number, 0, 0, 'L');

            // TANGGAL (kanan bawah)
            $pdf->SetXY(197, 180);
            $pdf->Cell(80, 5, 'Date: ' . $enrollment->certificate_issued_at->format('d F Y'), 0, 0, 'R');

            // INSTRUCTOR NAME (kanan bawah)
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(197, 165);
            $pdf->Cell(80, 5, $enrollment->course->instructor->name, 0, 0, 'R');
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(197, 170);
            $pdf->Cell(80, 5, 'Instructor', 0, 0, 'R');

            // Output PDF
            $filename = 'certificate-' . $enrollment->certificate_number . '.pdf';
            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating certificate: ' . $e->getMessage());
        }
    }
}