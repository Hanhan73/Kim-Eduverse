<?php

namespace App\Services;

use App\Models\Enrollment;
use setasign\Fpdi\Fpdi;

class CertificatePdfService
{
    public function generate(Enrollment $enrollment): string
    {
        $templatePath = storage_path('app/certificates/template.pdf');

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        /* =======================
         | HALAMAN 1 – SERTIFIKAT
         ======================= */
        $pdf->AddPage('L');
        $pdf->setSourceFile($templatePath);
        $tpl1 = $pdf->importPage(1);
        $pdf->useTemplate($tpl1, 0, 0, 297, 210);

        // ===== HEADER =====
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(220, 15);
        $pdf->Cell(60, 6, 'KIM Eduvers', 0, 0, 'R');

        // ===== TITLE =====
        $pdf->SetFont('Arial', 'BI', 28);
        $pdf->SetXY(0, 45);
        $pdf->Cell(297, 10, 'Sertifikat Pelatihan', 0, 0, 'C');

        // ===== NOMOR =====
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(0, 62);
        $pdf->Cell(297, 6, 'Nomor: ' . $enrollment->certificate_number, 0, 0, 'C');

        // ===== DIBERIKAN KEPADA =====
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(0, 90);
        $pdf->Cell(297, 6, 'Diberikan kepada:', 0, 0, 'C');

        // ===== NAMA PESERTA =====
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetXY(0, 102);
        $pdf->Cell(297, 8, strtoupper($enrollment->student->name), 0, 0, 'C');

        // Garis bawah nama
        $pdf->SetLineWidth(0.6);
        $pdf->Line(90, 112, 207, 112);

        // ===== DESKRIPSI =====
        $totalJP = $enrollment->course->modules->count() * 6;

        $start = $enrollment->created_at->translatedFormat('d F Y');
        $end   = $enrollment->certificate_issued_at->translatedFormat('d F Y');

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(30, 120);
        $pdf->MultiCell(237, 6,
            'sebagai peserta dalam kegiatan Pelatihan "' .
            strtoupper($enrollment->course->title) .
            '", yang', 0, 'C'
        );

        $pdf->SetXY(30, 130);
        $pdf->MultiCell(237, 6,
            'diselenggarakan oleh PT Kompetensi Mandiri Indonesia. Dilaksanakan secara online selama '
            . $totalJP, 0, 'C'
        );

        $pdf->SetXY(30, 140);
        $pdf->MultiCell(237, 6,
            'Jam Pelajaran (JP) pada tanggal ' . $start . ' sampai dengan ' . $end . '.', 0, 'C'
        );

        // ===== FOOTER =====
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(0, 165);
        $pdf->Cell(297, 5, 'Direktur,', 0, 0, 'C');

        // Garis tanda tangan
        $pdf->SetLineWidth(0.4);
        $pdf->Line(120, 172, 177, 172);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(0, 175);
        $pdf->Cell(297, 5, 'Yosep Hernawan, S.T., M.M., IPM.', 0, 0, 'C');

        /* =======================
         | HALAMAN 2 – MATERI
         ======================= */
        $pdf->AddPage('L');
        $tpl2 = $pdf->importPage(2);
        $pdf->useTemplate($tpl2, 0, 0, 297, 210);

        // Judul
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetXY(0, 40);
        $pdf->Cell(297, 6, 'MATERI PELATIHAN', 0, 0, 'C');

        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(0, 48);
        $pdf->Cell(297, 6, strtoupper($enrollment->course->title), 0, 0, 'C');

        // ===== TABEL =====
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(40, 70);
        $pdf->Cell(20, 10, 'No', 1, 0, 'C');
        $pdf->Cell(187, 10, 'Materi', 1, 0, 'C');
        $pdf->Cell(30, 10, 'JP', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $y = 80;
        $no = 1;

        foreach ($enrollment->course->modules as $module) {
            $pdf->SetXY(40, $y);
            $pdf->Cell(20, 10, $no++, 1, 0, 'C');
            $pdf->Cell(187, 10, $module->title, 1, 0, 'L');
            $pdf->Cell(30, 10, '6', 1, 1, 'C');
            $y += 10;
        }

        // Footer halaman 2
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(0, 165);
        $pdf->Cell(297, 5, 'Direktur,', 0, 0, 'C');

        $pdf->SetLineWidth(0.4);
        $pdf->Line(120, 172, 177, 172);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(0, 175);
        $pdf->Cell(297, 5, 'Yosep Hernawan, S.T., M.M., IPM.', 0, 0, 'C');

        return $pdf->Output('S');
    }
}