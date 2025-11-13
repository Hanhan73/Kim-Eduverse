<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;

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
        try {
            // Debug 1: Check enrollment
            $enrollment = Enrollment::with(['student', 'course.instructor'])
                ->whereNotNull('certificate_issued_at')
                ->findOrFail($id);
            
            \Log::info('Certificate Download - Enrollment found', [
                'enrollment_id' => $enrollment->id,
                'student_name' => $enrollment->student->name ?? 'NULL',
                'course_title' => $enrollment->course->title ?? 'NULL',
            ]);

            // Debug 2: Check template file
            $templatePath = storage_path('app/certificates/template.pdf');
            
            \Log::info('Certificate Download - Template path', [
                'path' => $templatePath,
                'exists' => file_exists($templatePath),
                'readable' => file_exists($templatePath) ? is_readable($templatePath) : false,
            ]);

            if (!file_exists($templatePath)) {
                \Log::error('Certificate template not found', ['path' => $templatePath]);
                return response()->json([
                    'error' => 'Template not found',
                    'path' => $templatePath,
                    'storage_path' => storage_path(),
                ], 404);
            }

            // Debug 3: Check FPDI class
            if (!class_exists('\setasign\Fpdi\Fpdi')) {
                \Log::error('FPDI class not found - run: composer require setasign/fpdi');
                return response()->json([
                    'error' => 'FPDI library not installed',
                    'solution' => 'Run: composer require setasign/fpdi',
                ], 500);
            }

            // Generate PDF
            $pdf = new \setasign\Fpdi\Fpdi();
            
            \Log::info('Certificate Download - FPDI instance created');
            
            // Load template
            $pdf->AddPage('L');
            $pageCount = $pdf->setSourceFile($templatePath);
            
            \Log::info('Certificate Download - Template loaded', ['pages' => $pageCount]);
            
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 297, 210);

            // Add text
            $pdf->SetFont('Arial', 'B', 24);
            $pdf->SetTextColor(0, 0, 0);

            // Student name
            $pdf->SetXY(0, 100);
            $pdf->Cell(297, 10, strtoupper($enrollment->student->name), 0, 0, 'C');

            // Course title
            $pdf->SetFont('Arial', '', 18);
            $pdf->SetXY(0, 130);
            $pdf->Cell(297, 10, $enrollment->course->title, 0, 0, 'C');

            // Certificate number
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetXY(20, 180);
            $pdf->Cell(80, 5, 'Certificate No: ' . $enrollment->certificate_number, 0, 0, 'L');

            // Date
            $pdf->SetXY(197, 180);
            $pdf->Cell(80, 5, 'Date: ' . $enrollment->certificate_issued_at->format('d F Y'), 0, 0, 'R');

            // Instructor
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(197, 165);
            $pdf->Cell(80, 5, $enrollment->course->instructor->name ?? 'Instructor', 0, 0, 'R');
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(197, 170);
            $pdf->Cell(80, 5, 'Instructor', 0, 0, 'R');

            \Log::info('Certificate Download - PDF generated successfully');

            // Output
            $filename = 'certificate-' . $enrollment->certificate_number . '.pdf';
            $pdfContent = $pdf->Output('S');
            
            \Log::info('Certificate Download - Output generated', [
                'filename' => $filename,
                'size' => strlen($pdfContent),
            ]);

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($pdfContent));

        } catch (\Exception $e) {
            \Log::error('Certificate Download - Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
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