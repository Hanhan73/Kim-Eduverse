<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarEnrollment extends Model
{
    protected $fillable = [
        'seminar_id',
        'customer_email',
        'order_id',
        'pre_test_passed',
        'pre_test_completed_at',
        'pre_test_score',
        'material_viewed',
        'material_viewed_at',
        'post_test_passed',
        'post_test_completed_at',
        'post_test_score',
        'certificate_generated',
        'certificate_number',
        'certificate_path',
        'certificate_issued_at',
        'certificate_sent_via_email',
        'is_completed',
        'completed_at',
        'participant_name',
    ];

    protected $casts = [
        'pre_test_passed' => 'boolean',
        'pre_test_completed_at' => 'datetime',
        'material_viewed' => 'boolean',
        'material_viewed_at' => 'datetime',
        'post_test_passed' => 'boolean',
        'post_test_completed_at' => 'datetime',
        'certificate_generated' => 'boolean',
        'certificate_issued_at' => 'datetime',
        'certificate_sent_via_email' => 'boolean',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relations
    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function order()
    {
        return $this->belongsTo(DigitalOrder::class, 'order_id');
    }

    // Helpers
    public function canAccessMaterial()
    {
        return $this->pre_test_passed;
    }

    public function canAccessPostTest()
    {
        return $this->pre_test_passed && $this->material_viewed;
    }

    public function generateCertificate()
    {
        if (!$this->post_test_passed) {
            return false;
        }

        // Generate certificate number dengan format: 0001/ODS-KE/01/2026
        // UniqueID = auto increment 4 digit (0001, 0002, 0003, ...)
        
        $now = now();
        
        // Hitung total sertifikat yang sudah pernah dibuat (termasuk yang dihapus)
        // Pakai withTrashed() kalau pakai SoftDeletes di model ini
        $totalCertificates = self::whereNotNull('certificate_number')->count();
        
        // Auto increment: tambah 1, format jadi 4 digit dengan leading zero
        $sequenceNumber = str_pad($totalCertificates + 1, 4, '0', STR_PAD_LEFT);
        
        // Format: 0001/ODS-KE/MM/YYYY
        $this->certificate_number = sprintf(
            '%s/ODS-KE/%s/%s',
            $sequenceNumber,    // 0001, 0002, 0003, ...
            $now->format('m'),  // Bulan (01-12)
            $now->format('Y')   // Tahun (2026)
        );
        
        $this->certificate_generated = true;
        $this->certificate_issued_at = now();
        $this->is_completed = true;
        $this->completed_at = now();
        $this->save();

        return true;
    }

    public function getProgressPercentageAttribute()
    {
        $progress = 0;

        if ($this->pre_test_passed) $progress += 33;
        if ($this->material_viewed) $progress += 34;
        if ($this->post_test_passed) $progress += 33;

        return $progress;
    }
}