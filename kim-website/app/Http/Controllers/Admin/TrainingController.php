<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingSubmission;
use App\Models\Seminar;
use App\Models\SeminarEnrollment;
use App\Models\DigitalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainingController extends Controller
{
    // ========================================
    // INDEX
    // ========================================
    public function index()
    {
        $trainings = Training::withCount('participants')
            ->latest()
            ->paginate(15);

        return view('admin.digital.trainings.index', compact('trainings'));
    }

    // ========================================
    // CREATE
    // ========================================
    public function create()
    {
        $seminars = Seminar::where('is_active', true)->orderBy('title')->get();
        return view('admin.digital.trainings.create', compact('seminars'));
    }

    // ========================================
    // STORE
    // ========================================
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'training_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'trainer_name' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'seminar_id' => 'nullable|exists:seminars,id',
            'description' => 'nullable|string',
        ]);

        Training::create($request->all());

        return redirect()->route('admin.digital.trainings.index')
            ->with('success', 'Pelatihan berhasil dibuat!');
    }

    // ========================================
    // SHOW (Batch View)
    // ========================================
    public function show(Training $training)
    {
        $training->load(['seminar', 'participants.submission', 'participants.enrollment']);

        $stats = [
            'total' => $training->participants->count(),
            'checked_in' => $training->participants->whereNotNull('checked_in_at')->count(),
            'checked_out' => $training->participants->whereNotNull('checked_out_at')->count(),
            'submitted' => $training->participants->filter(fn($p) => $p->submission)->count(),
            'certified' => $training->participants->whereNotNull('certificate_path')->count(),
        ];

        return view('admin.digital.trainings.show', compact('training', 'stats'));
    }

    // ========================================
    // EDIT
    // ========================================
    public function edit(Training $training)
    {
        $seminars = Seminar::where('is_active', true)->orderBy('title')->get();
        return view('admin.digital.trainings.edit', compact('training', 'seminars'));
    }

    // ========================================
    // UPDATE
    // ========================================
    public function update(Request $request, Training $training)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'training_date' => 'required|date',
            'seminar_id' => 'nullable|exists:seminars,id',
        ]);

        $training->update($request->all());

        return redirect()->route('admin.digital.trainings.show', $training)
            ->with('success', 'Pelatihan berhasil diupdate!');
    }

    // ========================================
    // DESTROY
    // ========================================
    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('admin.digital.trainings.index')
            ->with('success', 'Pelatihan berhasil dihapus.');
    }

    // ========================================
    // TAMBAH PESERTA MANUAL
    // ========================================
    public function addParticipant(Request $request, Training $training)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
        ]);

        // Cek duplikat
        if ($training->participants()->where('email', $request->email)->exists()) {
            return back()->with('error', 'Email ' . $request->email . ' sudah terdaftar di pelatihan ini.');
        }

        $participant = $training->participants()->create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'institution' => $request->institution,
            'access_token' => Str::random(40),
        ]);

        // Auto kirim email jika diminta
        if ($request->boolean('send_email')) {
            $this->sendAccessEmail($participant, $training);
        }

        return back()->with('success', 'Peserta ' . $request->name . ' berhasil ditambahkan.');
    }

    // ========================================
    // IMPORT PESERTA VIA EXCEL/CSV
    // ========================================
    public function importParticipants(Request $request, Training $training)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $rows = [];

        if ($extension === 'csv') {
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
        } else {
            // Parse xlsx/xls manual pakai PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            array_shift($data); // skip header
            $rows = $data;
        }

        $added = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $i => $row) {
            // Kolom: Nama, NIP, Email, No HP, Instansi
            $name = trim($row[0] ?? '');
            $nip = trim($row[1] ?? '');
            $email = trim($row[2] ?? '');
            $phone = trim($row[3] ?? '');
            $institution = trim($row[4] ?? '');

            if (empty($name) || empty($email)) {
                $skipped++;
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris " . ($i + 2) . ": Email '$email' tidak valid";
                $skipped++;
                continue;
            }

            if ($training->participants()->where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            $training->participants()->create([
                'name' => $name,
                'nip' => $nip,
                'email' => $email,
                'phone' => $phone,
                'institution' => $institution,
                'access_token' => Str::random(40),
            ]);

            $added++;
        }

        $message = "Import selesai: $added peserta ditambahkan";
        if ($skipped > 0) $message .= ", $skipped dilewati";
        if (!empty($errors)) $message .= ". Error: " . implode('; ', array_slice($errors, 0, 3));

        return back()->with('success', $message);
    }

    // ========================================
    // KIRIM EMAIL AKSES KE SEMUA PESERTA
    // ========================================
    public function sendEmails(Training $training)
    {
        $participants = $training->participants()->where('token_sent', false)->get();

        $sent = 0;
        foreach ($participants as $participant) {
            try {
                $this->sendAccessEmail($participant, $training);
                $sent++;
            } catch (\Exception $e) {
                Log::error("Failed to send email to {$participant->email}: " . $e->getMessage());
            }
        }

        return back()->with('success', "Email akses berhasil dikirim ke $sent peserta.");
    }

    // ========================================
    // KIRIM EMAIL KE 1 PESERTA
    // ========================================
    public function sendEmailOne(Training $training, TrainingParticipant $participant)
    {
        try {
            $this->sendAccessEmail($participant, $training);
            return back()->with('success', "Email akses berhasil dikirim ke {$participant->email}.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal kirim email: " . $e->getMessage());
        }
    }

    // ========================================
    // ABSENSI CHECK-IN (by admin)
    // ========================================
    public function checkIn(Training $training, TrainingParticipant $participant)
    {
        $participant->update(['checked_in_at' => now()]);
        return back()->with('success', "{$participant->name} berhasil check-in.");
    }

    // ========================================
    // ABSENSI CHECK-OUT (by admin)
    // ========================================
    public function checkOut(Training $training, TrainingParticipant $participant)
    {
        $participant->update(['checked_out_at' => now()]);
        return back()->with('success', "{$participant->name} berhasil check-out.");
    }

    // ========================================
    // HAPUS PESERTA
    // ========================================
    public function removeParticipant(Training $training, TrainingParticipant $participant)
    {
        $participant->delete();
        return back()->with('success', 'Peserta berhasil dihapus.');
    }

    // ========================================
    // REVIEW TUGAS
    // ========================================
    public function reviewSubmission(Request $request, TrainingSubmission $submission)
    {
        $request->validate([
            'status' => 'required|in:reviewed,approved,revision',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Tugas berhasil direview.');
    }

    // ========================================
    // GENERATE SERTIFIKAT (semua peserta selesai)
    // ========================================
    public function generateCertificates(Training $training)
    {
        $training->load(['seminar', 'participants.enrollment']);
        $generated = 0;

        foreach ($training->participants as $participant) {
            // Hanya yang sudah check-in, check-out, dan post-test lulus
            $enrollment = $participant->enrollment;
            $postTestPassed = $enrollment ? $enrollment->post_test_passed : true; // jika tidak ada seminar, anggap lulus

            if ($participant->checked_in_at && $participant->checked_out_at && $postTestPassed && !$participant->certificate_path) {
                $this->generateOneCertificate($participant, $training);
                $generated++;
            }
        }

        return back()->with('success', "$generated sertifikat berhasil dibuat.");
    }

    // ========================================
    // DOWNLOAD SERTIFIKAT PESERTA
    // ========================================
    public function downloadCertificate(TrainingParticipant $participant)
    {
        if (!$participant->certificate_path) {
            return back()->with('error', 'Sertifikat belum tersedia.');
        }

        $path = storage_path('app/public/' . $participant->certificate_path);
        if (!file_exists($path)) {
            return back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return response()->download($path, 'sertifikat_' . $participant->certificate_number . '.pdf');
    }

    // ========================================
    // PRIVATE: Kirim email akses
    // ========================================
    private function sendAccessEmail(TrainingParticipant $participant, Training $training)
    {
        $accessUrl = route('training.participant.access', $participant->access_token);

        Mail::send('emails.training-access', [
            'participant' => $participant,
            'training' => $training,
            'accessUrl' => $accessUrl,
        ], function ($message) use ($participant, $training) {
            $message->to($participant->email, $participant->name)
                ->subject('Link Akses Pelatihan - ' . $training->title);
        });

        $participant->update([
            'token_sent' => true,
            'token_sent_at' => now(),
        ]);
    }

    // ========================================
    // PRIVATE: Generate 1 sertifikat
    // ========================================
    private function generateOneCertificate(TrainingParticipant $participant, Training $training)
    {
        try {
            $participant->generateCertificateNumber();
            $participant->refresh();

            $pdf = Pdf::loadView('pdf.training-certificate', [
                'participant' => $participant,
                'training' => $training,
            ])->setPaper('a4', 'landscape');

            $fileName = 'sertifikat_' . $participant->certificate_number . '.pdf';
            $filePath = 'training_certificates/' . $fileName;

            if (!\Storage::disk('public')->exists('training_certificates')) {
                \Storage::disk('public')->makeDirectory('training_certificates');
            }

            \Storage::disk('public')->put($filePath, $pdf->output());

            $participant->update([
                'certificate_path' => $filePath,
                'certificate_issued_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("Certificate generation failed for participant {$participant->id}: " . $e->getMessage());
        }
    }

    public function downloadTemplate()
{
    // Buat CSV sederhana sebagai template
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="template_peserta_pelatihan.csv"',
    ];

    $columns = ['Nama', 'NIP/NIKKI', 'Email', 'No HP', 'Instansi'];

    $callback = function () use ($columns) {
        $file = fopen('php://output', 'w');
        // Header
        fputcsv($file, $columns);
        // Contoh data
        fputcsv($file, ['Neneng Yosi', '198805202015042002', 'neneng@sekolah.sch.id', '08123456789', 'TK Meruya Selatan']);
        fputcsv($file, ['Fitri Wulandari', '199401302020122018', 'fitri@sekolah.sch.id', '08234567890', 'TK Meruya Selatan']);
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}