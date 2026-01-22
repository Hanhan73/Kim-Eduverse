<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\CertificatePdfService;

class CertificatesController extends Controller
{
    protected $certificateService;

    public function __construct(CertificatePdfService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'course'])
            ->whereNotNull('certificate_issued_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }

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
            'total_degree_certificates' => Enrollment::whereNotNull('degree_certificate_issued_at')->count(),
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

        if (!$enrollment->completed_at) {
            return redirect()->back()
                ->with('error', 'Cannot issue certificate for incomplete course!');
        }

        if ($enrollment->certificate_issued_at) {
            return redirect()->back()
                ->with('error', 'Certificate already issued!');
        }

        $enrollment->certificate_number = 'CERT-' . strtoupper(uniqid());
        $enrollment->certificate_issued_at = now();

        // Issue degree certificate if course has degree
        if ($enrollment->course->has_degree) {
            $enrollment->degree_certificate_number = 'DEGREE-' . strtoupper(uniqid());
            $enrollment->degree_certificate_issued_at = now();
        }

        $enrollment->save();

        return redirect()->back()
            ->with('success', 'Certificate(s) issued successfully!');
    }

    public function revoke($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $enrollment->certificate_issued_at = null;
        $enrollment->degree_certificate_issued_at = null;
        $enrollment->save();

        return redirect()->back()
            ->with('success', 'Certificate(s) revoked successfully!');
    }

    /**
     * Download course certificate
     */
    public function download($id)
    {
        $enrollment = Enrollment::whereNotNull('certificate_issued_at')->findOrFail($id);

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
        $enrollment = Enrollment::whereNotNull('degree_certificate_issued_at')->findOrFail($id);

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

    public function verify(Request $request)
    {
        $certificateNumber = $request->input('certificate_number');

        // Check for course certificate
        $certificate = Enrollment::with(['student', 'course'])
            ->where('certificate_number', $certificateNumber)
            ->orWhere('degree_certificate_number', $certificateNumber)
            ->first();

        if (!$certificate) {
            return view('edutech.admin.certificates.verify', [
                'found' => false,
                'message' => 'Certificate not found'
            ]);
        }

        $isDegree = ($certificate->degree_certificate_number === $certificateNumber);

        return view('edutech.admin.certificates.verify', [
            'found' => true,
            'certificate' => $certificate,
            'isDegree' => $isDegree
        ]);
    }
}
