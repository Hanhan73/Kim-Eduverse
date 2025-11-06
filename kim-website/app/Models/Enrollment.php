<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', // ← Pastikan ini student_id bukan user_id
        'course_id',
        'status',
        'progress_percentage',
        'enrolled_at',
        'completed_at',
        'certificate_issued_at',
        'payment_status',
        'payment_amount',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'progress_percentage' => 'integer',
        'payment_amount' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id'); // ← Pastikan ini student_id
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'user_id', 'student_id')
            ->where('course_id', $this->course_id);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helpers
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);

        // Auto issue certificate if passing score met
        $this->issueCertificate();
    }

    public function issueCertificate()
    {
        // Check if certificate already exists
        if ($this->certificate()->exists()) {
            return;
        }

        // Create certificate
        $certificate = Certificate::create([
            'user_id' => $this->student_id, // ← Ini tetap user_id karena di table certificates memang user_id
            'course_id' => $this->course_id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'file_path' => 'certificates/temp.pdf',
            'issued_at' => now(),
        ]);

        // Update enrollment
        $this->update([
            'certificate_issued_at' => now(),
        ]);

        return $certificate;
    }

    public function updateProgress($percentage)
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
        ]);

        // Auto complete if 100%
        if ($percentage >= 100 && $this->status !== 'completed') {
            $this->markAsCompleted();
        }
    }
}