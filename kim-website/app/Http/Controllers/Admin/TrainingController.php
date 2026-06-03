<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingQuestion;
use App\Models\TrainingParticipant;
use App\Models\TrainingSubmission;
use App\Models\TrainingQuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainingController extends Controller
{
    // ================================================================
    // PELATIHAN CRUD
    // ================================================================

    public function index()
    {
        $trainings = Training::withCount('participants')->latest()->paginate(15);
        return view('admin.digital.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.digital.trainings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'training_date' => 'required|date',
            'start_time'    => 'nullable|date_format:H:i',
            'end_time'      => 'nullable|date_format:H:i',
            'trainer_name'  => 'nullable|string|max:255',
            'organizer'     => 'nullable|string|max:255',
            'description'   => 'nullable|string',
        ]);

        Training::create($request->only([
            'title',
            'description',
            'location',
            'training_date',
            'start_time',
            'end_time',
            'trainer_name',
            'organizer',
            'is_active',
        ]));

        return redirect()->route('admin.digital.trainings.index')
            ->with('success', 'Pelatihan berhasil dibuat!');
    }

    public function show(Training $training)
    {
        $training->load(['materials', 'questions', 'participants.submission', 'participants.quizAttempts']);

        $stats = [
            'total'       => $training->participants->count(),
            'checked_in'  => $training->participants->whereNotNull('checked_in_at')->count(),
            'checked_out' => $training->participants->whereNotNull('checked_out_at')->count(),
            'submitted'   => $training->participants->filter(fn($p) => $p->submission)->count(),
            'certified'   => $training->participants->whereNotNull('certificate_path')->count(),
        ];

        return view('admin.digital.trainings.show', compact('training', 'stats'));
    }

    public function edit(Training $training)
    {
        $training->load(['materials', 'questions']);
        return view('admin.digital.trainings.edit', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'training_date' => 'required|date',
        ]);

        $training->update($request->only([
            'title',
            'description',
            'location',
            'training_date',
            'start_time',
            'end_time',
            'trainer_name',
            'organizer',
            'is_active',
            'total_jp', // meskipun ini dihitung otomatis, tetap bisa diupdate manual kalau mau
        ]));

        return redirect()->route('admin.digital.trainings.show', $training)
            ->with('success', 'Pelatihan berhasil diupdate!');
    }

    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('admin.digital.trainings.index')
            ->with('success', 'Pelatihan berhasil dihapus.');
    }

    // ================================================================
    // MATERI CRUD
    // ================================================================

    public function storeMaterial(Request $request, Training $training)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:pdf,ppt,youtube,gdrive',
            'url'   => 'required|url',
        ]);

        $order = $training->materials()->max('order') + 1;

        $training->materials()->create([
            'title' => $request->title,
            'type'  => $request->type,
            'url'   => $request->url,
            'order' => $order,
        ]);

        return back()->with('success', 'Materi berhasil ditambahkan.');
    }

    public function updateMaterial(Request $request, Training $training, TrainingMaterial $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:pdf,ppt,youtube,gdrive',
            'url'   => 'required|url',
        ]);

        $material->update($request->only('title', 'type', 'url'));
        return back()->with('success', 'Materi berhasil diupdate.');
    }

    public function storeCertificateMaterial(Request $request, Training $training)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $order = \App\Models\TrainingCertificateMaterial::where('training_id', $training->id)->max('order') + 1;

        \App\Models\TrainingCertificateMaterial::create([
            'training_id' => $training->id,
            'title'       => $request->title,
            'order'       => $order,
        ]);

        return back()->with('success', 'Materi sertifikat berhasil ditambahkan.');
    }

    public function updateCertificateMaterial(Request $request, Training $training, \App\Models\TrainingCertificateMaterial $certMaterial)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $certMaterial->update(['title' => $request->title]);
        return back()->with('success', 'Materi sertifikat berhasil diupdate.');
    }

    public function destroyCertificateMaterial(Training $training, \App\Models\TrainingCertificateMaterial $certMaterial)
    {
        $certMaterial->delete();
        return back()->with('success', 'Materi sertifikat berhasil dihapus.');
    }

    public function destroyMaterial(Training $training, TrainingMaterial $material)
    {
        $material->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }

    // ================================================================
    // SOAL CRUD
    // ================================================================

    public function storeQuestion(Request $request, Training $training)
    {
        $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:500',
            'option_b'       => 'required|string|max:500',
            'option_c'       => 'required|string|max:500',
            'option_d'       => 'required|string|max:500',
            'option_e'       => 'nullable|string|max:500',
            'correct_answer' => 'required|in:A,B,C,D,E',
        ]);

        $order = $training->questions()->max('order') + 1;

        $training->questions()->create([
            'question'       => $request->question,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'option_e'       => $request->option_e,
            'correct_answer' => strtoupper($request->correct_answer),
            'order'          => $order,
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, Training $training, TrainingQuestion $question)
    {
        $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:500',
            'option_b'       => 'required|string|max:500',
            'option_c'       => 'required|string|max:500',
            'option_d'       => 'required|string|max:500',
            'option_e'       => 'nullable|string|max:500',
            'correct_answer' => 'required|in:A,B,C,D,E',
        ]);

        $question->update($request->only(
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'option_e',
            'correct_answer'
        ));

        return back()->with('success', 'Soal berhasil diupdate.');
    }

    public function destroyQuestion(Training $training, TrainingQuestion $question)
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // ================================================================
    // PESERTA
    // ================================================================

    public function addParticipant(Request $request, Training $training)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'nip'         => 'nullable|string|max:50',
            'phone'       => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
        ]);

        if ($training->participants()->where('email', $request->email)->exists()) {
            return back()->with('error', 'Email ' . $request->email . ' sudah terdaftar.');
        }

        $participant = $training->participants()->create([
            'name'         => $request->name,
            'email'        => $request->email,
            'nip'          => $request->nip,
            'phone'        => $request->phone,
            'institution'  => $request->institution,
            'access_token' => Str::random(40),
        ]);

        if ($request->boolean('send_email')) {
            $this->sendAccessEmail($participant, $training);
        }

        return back()->with('success', 'Peserta ' . $request->name . ' berhasil ditambahkan.');
    }

    public function importParticipants(Request $request, Training $training)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        $file = $request->file('file');
        $rows = [];

        if ($file->getClientOriginalExtension() === 'csv') {
            $handle = fopen($file->getPathname(), 'r');
            fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) $rows[] = $row;
            fclose($handle);
        } else {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $data = $spreadsheet->getActiveSheet()->toArray();
            array_shift($data);
            $rows = $data;
        }

        $added = 0;
        $skipped = 0;
        foreach ($rows as $row) {
            $name  = trim($row[0] ?? '');
            $nip   = trim($row[1] ?? '');
            $email = trim($row[2] ?? '');
            if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }
            if ($training->participants()->where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            $training->participants()->create([
                'name'         => $name,
                'nip'          => $nip,
                'email'        => $email,
                'phone'        => trim($row[3] ?? ''),
                'institution'  => trim($row[4] ?? ''),
                'access_token' => Str::random(40),
            ]);
            $added++;
        }

        return back()->with('success', "Import selesai: {$added} ditambahkan, {$skipped} dilewati.");
    }

    public function removeParticipant(Training $training, TrainingParticipant $participant)
    {
        $participant->delete();
        return back()->with('success', 'Peserta berhasil dihapus.');
    }

    // ================================================================
    // EMAIL
    // ================================================================

    public function sendEmails(Training $training)
    {
        $participants = $training->participants()->where('token_sent', false)->get();
        $sent = 0;
        foreach ($participants as $p) {
            try {
                $this->sendAccessEmail($p, $training);
                $sent++;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
        return back()->with('success', "Email berhasil dikirim ke {$sent} peserta.");
    }

    public function sendEmailOne(Training $training, TrainingParticipant $participant)
    {
        try {
            $this->sendAccessEmail($participant, $training);
            return back()->with('success', "Email dikirim ke {$participant->email}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal kirim email: ' . $e->getMessage());
        }
    }

    // ================================================================
    // ABSENSI (BY ADMIN)
    // ================================================================

    public function checkIn(Training $training, TrainingParticipant $participant)
    {
        $participant->update(['checked_in_at' => now()]);
        return back()->with('success', "{$participant->name} berhasil check-in.");
    }

    public function checkOut(Training $training, TrainingParticipant $participant)
    {
        $participant->update(['checked_out_at' => now()]);
        return back()->with('success', "{$participant->name} berhasil check-out.");
    }

    // ================================================================
    // REVIEW TUGAS
    // ================================================================

    public function reviewSubmission(Request $request, TrainingSubmission $submission)
    {
        $request->validate(['status' => 'required|in:reviewed,approved,revision', 'feedback' => 'nullable|string']);
        $submission->update(['status' => $request->status, 'feedback' => $request->feedback, 'reviewed_at' => now()]);
        return back()->with('success', 'Tugas berhasil direview.');
    }

    // ================================================================
    // SERTIFIKAT
    // ================================================================

    public function generateCertificates(Training $training)
    {
        $training->load('materials', 'participants.submission');
        $generated = 0;

        foreach ($training->participants as $participant) {
            if (
                $participant->checked_in_at
                && $participant->checked_out_at
                && $participant->post_test_passed
                && !$participant->certificate_path
            ) {
                $this->generateOneCertificate($participant, $training);
                $generated++;
            }
        }

        return back()->with('success', "{$generated} sertifikat berhasil dibuat.");
    }

    public function downloadCertificate(TrainingParticipant $participant)
    {
        if (!$participant->certificate_path) return back()->with('error', 'Sertifikat belum tersedia.');

        $path = storage_path('app/public/' . $participant->certificate_path);
        if (!file_exists($path)) return back()->with('error', 'File tidak ditemukan.');

        $safeNumber = str_replace(['/', '\\'], '-', $participant->certificate_number);
        return response()->download($path, 'sertifikat_' . $safeNumber . '.pdf');
    }

    public function downloadTemplate()
    {
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="template_peserta.csv"'];
        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama', 'NIP/NIKKI', 'Email', 'No HP', 'Instansi']);
            fputcsv($file, ['Neneng Yosi', '198805202015042002', 'neneng@sekolah.sch.id', '08123456789', 'TK Meruya Selatan']);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    private function sendAccessEmail(TrainingParticipant $participant, Training $training)
    {
        $accessUrl = route('training.participant.access', $participant->access_token);
        Mail::send('emails.training-access', compact('participant', 'training', 'accessUrl'), function ($m) use ($participant, $training) {
            $m->to($participant->email, $participant->name)
                ->subject('Link Akses Pelatihan - ' . $training->title);
        });
        $participant->update(['token_sent' => true, 'token_sent_at' => now()]);
    }

    private function generateOneCertificate(TrainingParticipant $participant, Training $training)
    {
        try {
            $participant->generateCertificateNumber();
            $participant->refresh();

            $pdf = Pdf::loadView('pdf.training-certificate', compact('participant', 'training'))
                ->setPaper('a4', 'landscape');

            $safeNumber = str_replace(['/', '\\', ' '], '-', $participant->certificate_number);
            $fileName   = 'sertifikat_' . $safeNumber . '.pdf';
            $filePath   = 'training_certificates/' . $fileName;

            if (!Storage::disk('public')->exists('training_certificates')) {
                Storage::disk('public')->makeDirectory('training_certificates');
            }

            Storage::disk('public')->put($filePath, $pdf->output());

            $participant->update([
                'certificate_path'      => $filePath,
                'certificate_issued_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("Certificate failed for participant {$participant->id}: " . $e->getMessage());
        }
    }


    public function downloadQuestionTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_soal_pelatihan.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            // Header row — SAMA dengan template quiz seminar on demand
            fputcsv($file, [
                'Pertanyaan',
                'Opsi A',
                'Opsi B',
                'Opsi C',
                'Opsi D',
                'Opsi E',
                'Jawaban Benar (A/B/C/D/E)',
            ]);

            // Contoh baris
            fputcsv($file, [
                'Apa kepanjangan dari AI?',
                'Artificial Intelligence',
                'Automatic Information',
                'Advanced Integration',
                'Applied Innovation',
                '',
                'A',
            ]);
            fputcsv($file, [
                'Manakah yang merupakan contoh AI generatif?',
                'Google Maps',
                'ChatGPT',
                'Microsoft Excel',
                'Adobe Photoshop',
                '',
                'B',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Import Soal dari Excel/CSV ───────────────────────────────
    public function importQuestions(Request $request, Training $training)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file      = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $rows      = [];

        if ($extension === 'csv') {
            $handle = fopen($file->getPathname(), 'r');
            // Hapus BOM kalau ada
            $bom = fread($handle, 3);
            if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
                rewind($handle);
            }
            fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
        } else {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $data        = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
            array_shift($data); // skip header
            $rows = $data;
        }

        $added   = 0;
        $skipped = 0;
        $errors  = [];

        $validAnswers = ['A', 'B', 'C', 'D', 'E'];
        $maxOrder     = $training->questions()->max('order') ?? 0;

        foreach ($rows as $i => $row) {
            $question = trim($row[0] ?? '');
            $optA     = trim($row[1] ?? '');
            $optB     = trim($row[2] ?? '');
            $optC     = trim($row[3] ?? '');
            $optD     = trim($row[4] ?? '');
            $optE     = trim($row[5] ?? '');
            $correct  = strtoupper(trim($row[6] ?? ''));

            // Validasi wajib
            if (empty($question) || empty($optA) || empty($optB)) {
                $errors[] = "Baris " . ($i + 2) . ": Pertanyaan, Opsi A, dan Opsi B wajib diisi.";
                $skipped++;
                continue;
            }

            if (!in_array($correct, $validAnswers)) {
                $errors[] = "Baris " . ($i + 2) . ": Jawaban benar '$correct' tidak valid (harus A/B/C/D/E).";
                $skipped++;
                continue;
            }

            // Validasi: jawaban yang dipilih harus punya opsi
            $optMap = ['A' => $optA, 'B' => $optB, 'C' => $optC, 'D' => $optD, 'E' => $optE];
            if (empty($optMap[$correct])) {
                $errors[] = "Baris " . ($i + 2) . ": Opsi '$correct' kosong tapi dijadikan jawaban benar.";
                $skipped++;
                continue;
            }

            $maxOrder++;
            $training->questions()->create([
                'question'       => $question,
                'option_a'       => $optA,
                'option_b'       => $optB,
                'option_c'       => $optC ?: null,
                'option_d'       => $optD ?: null,
                'option_e'       => $optE ?: null,
                'correct_answer' => $correct,
                'order'          => $maxOrder,
            ]);

            $added++;
        }

        $message = "Import selesai: {$added} soal ditambahkan";
        if ($skipped > 0) $message .= ", {$skipped} dilewati";
        if (!empty($errors)) {
            $message .= ". <br>Error: " . implode('<br>', array_slice($errors, 0, 5));
            if (count($errors) > 5) $message .= " (dan " . (count($errors) - 5) . " lainnya)";
        }

        return back()->with($skipped > 0 && $added === 0 ? 'error' : 'success', $message);
    }
}
