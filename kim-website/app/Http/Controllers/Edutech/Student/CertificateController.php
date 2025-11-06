<?php

namespace App\Http\Controllers\Edutech\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $studentId = session('edutech_user_id');

        $certificates = Certificate::with('course')
            ->where('user_id', $studentId)
            ->latest()
            ->get();

        return view('edutech.student.certificates', compact('certificates'));
    }

    public function download($id)
    {
        $certificate = Certificate::where('id', $id)
            ->where('user_id', session('edutech_user_id'))
            ->firstOrFail();

        return response()->download(storage_path('app/' . $certificate->file_path));
    }
}